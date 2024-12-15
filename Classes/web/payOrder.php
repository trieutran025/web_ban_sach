<?php
    include_once '../../lib/database.php';
    include_once '../../helpers/format.php';
    
class payOrder
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }
    public function pay(){
        if(Session::get('userLogin') == false){
            header('Location: login.php');
        }
        elseif(Session::get('userLogin') == true)
        {
            $userId = Session::get('userId');
            $queryInfo = "SELECT 
                `a`.`IdAccount`, 
                `Email`, 
                `Username`, 
                `i`.`PhoneNumber`, 
                `Address` 
                FROM `account` a 
                JOIN `info` i 
                ON a.IdAccount = i.IdAccount
                WHERE `StatusInfo` = 1 
                AND i.`IdAccount` = $userId;
            ";
            $resultInfo = $this->db->select($queryInfo);
            if(!$resultInfo)
            {
                header('Location: ../../views/web/user_address.php');
            } 
            elseif($resultInfo != false)
            {
                $value = $resultInfo->fetch_assoc();
                $idOrder = rand(10000000, 99999999);
                $date = new DateTime("now", new DateTimeZone('Asia/Ho_Chi_Minh') );
                $dateFormat = $date->format('Y-m-d H:i:s');
                $userName = $value['Username'];
                $phoneNumber = $value['PhoneNumber'];
                $addressOrder = $value['Address'];
                $email = $value['Email'];
                $total = 0;
                foreach (Session::get('cart') as $data){
                    $total += $data['price']*$data['quantity'];
                }

                $query = "INSERT INTO `order` (
                        `IdOrder`, 
                        `IdAccount`, 
                        `DateOrder`, 
                        `Receiver`, 
                        `PhoneOrder`, 
                        `AddressOrder`, 
                        `Total`, 
                        `PaymentMethods`, 
                        `StatusOrder`, 
                        `Email`) 
                    VALUES (
                        '$idOrder', 
                        '$userId',
                        '$dateFormat', 
                        '$userName', 
                        '$phoneNumber', 
                        '$addressOrder', 
                        '$total',
                        'Thanh toán khi nhận hàng(COD)', 
                        '1', 
                        '$email'
                    );
                ";
                $result = $this->db->insert($query);
                if($result == true)
                {
                    foreach(Session::get('cart') as $data)
                    {
                        $idProduct = $data['idProduct'];
                        $quantityOrder = $data['quantity'];
                        $price = $data['price'];
                        $sumOrder = $price * $quantityOrder;

                        $queryCartItem = "INSERT INTO `orderdetail` (
                            `IdOrder`,
                            `IdProduct`, 
                            `QuantityOrder`, 
                            `SumOrder`) 
                            VALUES (
                                '$idOrder', 
                                '$idProduct', 
                                '$quantityOrder', 
                                '$sumOrder');
                        ";
                        $resultCartItem = $this->db->insert($queryCartItem);
                    }   
                    if($resultCartItem == true)
                    {
                        unset($_SESSION['cart']);
                    }
                    
                }
            }         
        }
    }

    
    //payOne
    public function payOne($idProduct, $quantity, $price)
    {
        if (Session::get('userLogin') == false) {
            header('Location: login.php');
        } elseif (Session::get('userLogin') == true) {
            $userId = Session::get('userId');
            $queryInfo = "SELECT 
                `a`.`IdAccount`, 
                `Email`, 
                `Username`, 
                `i`.`PhoneNumber`, 
                `Address` 
                FROM `account` a 
                JOIN `info` i 
                ON a.IdAccount = i.IdAccount
                WHERE `StatusInfo` = 1 
                AND i.`IdAccount` = $userId;
            ";
            $resultInfo = $this->db->select($queryInfo);
            if (!$resultInfo) {
                header('Location: ../../views/web/user_address.php');
            } elseif ($resultInfo != false) {
                $value = $resultInfo->fetch_assoc();
                $idOrder = rand(10000000, 99999999);
                $date = new DateTime("now", new DateTimeZone('Asia/Ho_Chi_Minh'));
                $dateFormat = $date->format('Y-m-d H:i:s');
                $userName = $value['Username'];
                $phoneNumber = $value['PhoneNumber'];
                $addressOrder = $value['Address'];
                $email = $value['Email'];
                $total = $price * $quantity;

                // Tạo đơn hàng
                $query = "INSERT INTO `order` (
                        `IdOrder`, 
                        `IdAccount`, 
                        `DateOrder`, 
                        `Receiver`, 
                        `PhoneOrder`, 
                        `AddressOrder`, 
                        `Total`, 
                        `PaymentMethods`, 
                        `StatusOrder`, 
                        `Email`) 
                    VALUES (
                        '$idOrder', 
                        '$userId',
                        '$dateFormat', 
                        '$userName', 
                        '$phoneNumber', 
                        '$addressOrder', 
                        '$total',
                        'Thanh toán khi nhận hàng(COD)', 
                        '1', 
                        '$email'
                    );
                ";
                $result = $this->db->insert($query);

                if ($result == true) {
                    // Tạo chi tiết đơn hàng
                    $sumOrder = $price * $quantity;
                    $queryCartItem = "INSERT INTO `orderdetail` (
                        `IdOrder`,
                        `IdProduct`, 
                        `QuantityOrder`, 
                        `SumOrder`) 
                        VALUES (
                            '$idOrder', 
                            '$idProduct', 
                            '$quantity', 
                            '$sumOrder');
                    ";
                    $resultCartItem = $this->db->insert($queryCartItem);

                    if ($resultCartItem == true) {
                        // Nếu muốn xóa sản phẩm khỏi giỏ hàng sau khi thanh toán, xử lý tại đây
                        // Ví dụ: unset($_SESSION['cart'][$idProduct]);
                    }
                }
            }
        }
    }

    
}
?>
