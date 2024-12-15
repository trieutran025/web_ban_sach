<?php
include_once '../../lib/database.php';
include_once '../../helpers/format.php';
?>

<?php
class product
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function get_product()
    {
        $query = "
            SELECT p.*, s.*, 
                COALESCE(SUM(od.QuantityOrder), 0) AS TotalSold
            FROM product p 
            JOIN supplier s ON p.IdSupplier = s.IdSupplier
            LEFT JOIN orderdetail od ON p.IdProduct = od.IdProduct
            LEFT JOIN `order` o ON od.IdOrder = o.IdOrder AND o.StatusOrder = 3
            GROUP BY p.IdProduct, s.IdSupplier
            ORDER BY TotalSold DESC
            LIMIT 10
        ";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_product_Limit6()
    {
        $limit = 6;
        $quantityProduct = $this->quantity_Product();
        $from = mt_rand(0, $quantityProduct-$limit);
        $query = "
                SELECT 
                    p.*, 
                    s.*, 
                    COALESCE(SUM(od.QuantityOrder), 0) AS TotalSold -- Tổng số sản phẩm đã bán
                FROM `product` p
                JOIN `supplier` s ON p.IdSupplier = s.IdSupplier
                LEFT JOIN `orderdetail` od ON p.IdProduct = od.IdProduct
                LEFT JOIN `order` o ON od.IdOrder = o.IdOrder AND o.StatusOrder = 3 -- Chỉ tính đơn hàng thành công
                GROUP BY p.IdProduct, s.IdSupplier
                ORDER BY p.`IdProduct` ASC
                LIMIT $from, $limit";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_productById($id)
    {
        $query = "
                SELECT p.*, s.*, 
                    COALESCE(SUM(od.QuantityOrder), 0) AS TotalSold
                FROM product p
                JOIN supplier s ON p.IdSupplier = s.IdSupplier
                LEFT JOIN orderdetail od ON p.IdProduct = od.IdProduct
                LEFT JOIN `order` o ON od.IdOrder = o.IdOrder AND o.StatusOrder = 3
                WHERE p.IdProduct = '$id'
                GROUP BY p.IdProduct, s.IdSupplier
            ";

        $result = $this->db->select($query);
        return $result;
    }

    public function get_productByIdCategory($idCategory)
    {
        $query = "
                    SELECT 
                        p.*, 
                        s.*, 
                        COALESCE(SUM(od.QuantityOrder), 0) AS TotalSold -- Tổng số sản phẩm đã bán
                    FROM `product` p
                    JOIN `supplier` s ON p.IdSupplier = s.IdSupplier
                    LEFT JOIN `orderdetail` od ON p.IdProduct = od.IdProduct
                    LEFT JOIN `order` o ON od.IdOrder = o.IdOrder AND o.StatusOrder = 3
                    WHERE p.IdCategory = '$idCategory'
                    GROUP BY p.IdProduct, s.IdSupplier
                    LIMIT 5";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_topProduct()
    {
        $query = "SELECT COUNT(IdProduct), IdProduct FROM `orderdetail` GROUP BY IdProduct ORDER BY COUNT(IdProduct) DESC LIMIT 4";
        $result = $this->db->select($query);
        if($result){
            foreach($result as $data){
                 $sql = "
                    SELECT 
                         p.*, 
                        s.*, 
                        COALESCE(SUM(od.QuantityOrder), 0) AS TotalSold

                    FROM `product` p
                    JOIN `supplier` s ON p.IdSupplier = s.IdSupplier
                    LEFT JOIN `orderdetail` od ON p.IdProduct = od.IdProduct
                    LEFT JOIN `order` o ON od.IdOrder = o.IdOrder AND o.StatusOrder = 3
                    WHERE p.IdProduct = " . $data['IdProduct'] . "
                    GROUP BY p.IdProduct, s.IdSupplier
                     ORDER BY TotalSold DESC
                ";
                $resultData = $this->db->select($sql);
                if($resultData){
                    $listTop[$data['IdProduct']] = array(
                        'listTop' =>  $resultData
                    );
                }   
            }
        }
  
        if($result == true){
            return array(
                'listTop' => $result,
                'listDetailTop' => $listTop,
            );
        } 
    }

    public function get_listProductByCategory($idCategory)
    {
        $query = "SELECT * FROM `product` p JOIN supplier s ON p.IdSupplier = s.IdSupplier WHERE `idCategory` = '$idCategory'";
        $query = "
                SELECT p.*, s.*,
                    COALESCE(SUM(od.QuantityOrder), 0) AS TotalSold
                FROM product p
                JOIN supplier s ON p.IdSupplier = s.IdSupplier
                LEFT JOIN orderdetail od ON p.IdProduct = od.IdProduct
                LEFT JOIN `order` o ON od.IdOrder = o.IdOrder AND o.StatusOrder = 3
                WHERE p.idCategory = '$idCategory'
                GROUP BY p.IdProduct, s.IdSupplier
                ";
        $result = $this->db->select($query);
        return $result;
    }

    public function quantity_Product()
    {
        $query = "SELECT * FROM `product`";
        $result = $this->db->select($query);
        $rows = mysqli_num_rows($result);
        return $rows;
    }

    public function total_page()
    {
        $quantityProduct = $this->quantity_Product();
        $limit = 5;
        $totalPage = ceil($quantityProduct / $limit);
        return $totalPage;
    }

    public function ajaxPaging($page)
    {   
        $totalPage = $this->total_page();
        $limit = 5;
        if($page <= $totalPage){
            $from = ($page - 1) * $limit+5; 
            $query = "
                        SELECT 
                            p.*, 
                            s.*, 
                            COALESCE(SUM(od.QuantityOrder), 0) AS TotalSold -- Tổng số lượng sản phẩm đã bán thành công
                        FROM `product` p
                        JOIN `supplier` s ON p.IdSupplier = s.IdSupplier
                        LEFT JOIN `orderdetail` od ON p.IdProduct = od.IdProduct
                        LEFT JOIN `order` o ON od.IdOrder = o.IdOrder AND o.StatusOrder = 3 -- Chỉ tính đơn hàng thành công
                        GROUP BY p.IdProduct, s.IdSupplier
                        ORDER BY TotalSold DESC
                        LIMIT $from, $limit;
                        ";
            $result = $this->db->select($query);
            return $result;
        }
    }

    public function ajaxSearch($input){
        $query = "
        SELECT p.*, s.*, 
               COALESCE(SUM(od.QuantityOrder), 0) AS TotalSold
        FROM product p
        JOIN supplier s ON p.IdSupplier = s.IdSupplier
        LEFT JOIN orderdetail od ON p.IdProduct = od.IdProduct
        LEFT JOIN `order` o ON od.IdOrder = o.IdOrder AND o.StatusOrder = 3
        WHERE p.NameProduct LIKE '". $input ."%'
        GROUP BY p.IdProduct, s.IdSupplier
    ";
        $result = $this->db->select($query);
        return $result;
    }

    public function getProduct_by_price($option)
    {
        if ($option == 'ASC') {
            $query = "
                SELECT p.*, s.*, 
                    COALESCE(SUM(od.QuantityOrder), 0) AS TotalSold
                FROM product p
                JOIN supplier s ON p.IdSupplier = s.IdSupplier
                LEFT JOIN orderdetail od ON p.IdProduct = od.IdProduct
                LEFT JOIN `order` o ON od.IdOrder = o.IdOrder AND o.StatusOrder = 3
                GROUP BY p.IdProduct, s.IdSupplier
                ORDER BY Price ASC
            ";
        } else if ($option == 'DESC') {
            $query = "
                SELECT p.*, s.*, 
                    COALESCE(SUM(od.QuantityOrder), 0) AS TotalSold
                FROM product p
                JOIN supplier s ON p.IdSupplier = s.IdSupplier
                LEFT JOIN orderdetail od ON p.IdProduct = od.IdProduct
                LEFT JOIN `order` o ON od.IdOrder = o.IdOrder AND o.StatusOrder = 3
                GROUP BY p.IdProduct, s.IdSupplier
                ORDER BY Price DESC
            ";
        } else if ($option == 'NEW') {
            $query = "
                SELECT p.*, s.*, 
                    COALESCE(SUM(od.QuantityOrder), 0) AS TotalSold
                FROM product p
                JOIN supplier s ON p.IdSupplier = s.IdSupplier
                LEFT JOIN orderdetail od ON p.IdProduct = od.IdProduct
                LEFT JOIN `order` o ON od.IdOrder = o.IdOrder AND o.StatusOrder = 3
                GROUP BY p.IdProduct, s.IdSupplier
                ORDER BY TimeAdd DESC
            ";
        } else {
            $query = "
                SELECT p.*, s.*, 
                    COALESCE(SUM(od.QuantityOrder), 0) AS TotalSold
                FROM product p
                JOIN supplier s ON p.IdSupplier = s.IdSupplier
                LEFT JOIN orderdetail od ON p.IdProduct = od.IdProduct
                LEFT JOIN `order` o ON od.IdOrder = o.IdOrder AND o.StatusOrder = 3
                GROUP BY p.IdProduct, s.IdSupplier
                ORDER BY TotalSold DESC
            ";
        }
        
        $result = $this->db->select($query);
        return $result;
    }



    // public function get_CategoryById($id)
    // {
    //     $query = "SELECT IdCategory FROM product WHERE IdProduct = '$id' ";
    //     $result = $this->db->select($query);
    //     return $result;
    // }

    public function getProductByMutileChoice($best_seller, $book_genre, $book_price, $book_publisher)
    {
        // $query = "SELECT p.*, c.*, s.*
        //         FROM product p
        //         LEFT JOIN category c ON p.IdCategory = c.IdCategory
        //         LEFT JOIN supplier s ON p.IdSupplier = s.IdSupplier";
                    $query = "SELECT p.*, COALESCE(SUM(od.QuantityOrder), 0) AS TotalSold, c.*, s.* 
                FROM product p
                LEFT JOIN category c ON p.IdCategory = c.IdCategory
                LEFT JOIN supplier s ON p.IdSupplier = s.IdSupplier
                LEFT JOIN orderdetail od ON p.IdProduct = od.IdProduct
                LEFT JOIN `order` o ON od.IdOrder = o.IdOrder AND o.StatusOrder = 3 ";

        // Apply filters based on user input
        if ($best_seller !== null && $best_seller == '1') {

            $query = "SELECT p.*, COALESCE(SUM(od.QuantityOrder), 0) AS TotalSold, c.*, s.* 
                FROM product p
                LEFT JOIN category c ON p.IdCategory = c.IdCategory
                LEFT JOIN supplier s ON p.IdSupplier = s.IdSupplier
                LEFT JOIN orderdetail od ON p.IdProduct = od.IdProduct
                LEFT JOIN `order` o ON od.IdOrder = o.IdOrder AND o.StatusOrder = 3 ";
        }

         // Áp dụng các bộ lọc
        $conditions = [];

        if ($book_genre !== null && $book_genre != '') {
            $conditions[] = "p.IdCategory = " . (int)$book_genre;
        }

        if ($book_price !== null && $book_price !== '') {
            switch ($book_price) {
                case '1':
                    $conditions[] = "p.Price < 100000";
                    break;
                case '2':
                    $conditions[] = "p.Price BETWEEN 100000 AND 300000";
                    break;
                case '3':
                    $conditions[] = "p.Price BETWEEN 300000 AND 500000";
                    break;
                case '4':
                    $conditions[] = "p.Price > 500000";
                    break;
            }
        }

        if ($book_publisher !== null && $book_publisher !== '') {
            $conditions[] = "p.IdSupplier = " . (int)$book_publisher;
        }

        // Nếu có bộ lọc thì thêm vào câu lệnh WHERE
        if (count($conditions) > 0) {
            $query .= " WHERE " . implode(" AND ", $conditions);  // Nối các điều kiện bằng OR
        }

        // Sắp xếp theo bán chạy nếu được chọn, nếu không thì sắp xếp theo thời gian thêm mới
        if ($best_seller !== null && $best_seller == '1') {
            $query .= " GROUP BY p.IdProduct ORDER BY TotalSold DESC";  // Đảm bảo có dấu chấm phẩy
        }

        // if ($book_genre !== null && $book_genre != '') {
        //     $query .= " OR p.IdCategory = " . (int)$book_genre;
        // }

        // if ($book_price !== null && $book_price !== '') {
        //     switch ($book_price) {
        //         case '1':
        //             $query .= " OR p.Price < 100000";
        //             break;
        //         case '2':
        //             $query .= " OR p.Price BETWEEN 100000 AND 300000";
        //             break;
        //         case '3':
        //             $query .= " OR p.Price BETWEEN 300000 AND 500000";
        //             break;
        //         case '4':
        //             $query .= " OR p.Price > 500000";
        //             break;
        //     }
        // }

        // if ($book_publisher !== null && $book_publisher !== '') {
        //     $query .= " AND p.IdSupplier = " . (int)$book_publisher;
        // }

        // // Sort by best seller first if selected, otherwise by time added (newest first)
        // if ($best_seller !== null && $best_seller == '1') {
        //     $query .= "GROUP BY p.IdProduct, s.IdSupplier, g.IdSale ORDER BY TotalSold DESC"
        // }

        $result = $this->db->select($query);
        return $result;
    }
}
?>