<?php
include_once '../../Classes/web/web.php';
include_once '../../Classes/admin/category.php';
include '../../Classes/web/product.php';
include '../../Classes/admin/supplier.php';
$web = new website();
$product = new product();
$category = new category();
$supplier = new Supplier();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="../../public/web/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../public/web/css/grid.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <?php
    $get_logo = $web->get_web();
    if ($get_logo) {
        foreach ($get_logo as $data) {
    ?>
        <title><?php echo $data['webname'] ?></title>
        <link rel="shortcut icon" type="image/png" href="../../public/admin/Image/web/<?php echo $data['favicon'] ?>" />
    <?php
        }
    }
    ?>
</head>

<body>
    <div class="app">
        <header class="header">
            <div class="grid wide">
                <nav class="navbar">
                    <?php
                    $get_logo = $web->get_web();
                    if ($get_logo) {
                        foreach ($get_logo as $data) {
                    ?>
                            <div class="nav__logo">
                                <a href="#" class="nav__logo-link"><img src="../../public/admin/Image/web/<?php echo $data['logo'] ?>" alt="logo" class="nav__logo--img"></a>
                            </div>
                    <?php
                        }
                    }
                    ?>
                    <ul class="nav__list"> 
                        <li class="nav__item nav__item--active">
                            <a href="#" class="nav__item-link">Trang chủ</a>
                        </li>
                        <li class="nav__item">
                            <a href="#category" class="nav__item-link">Thông tin</a>
                        </li>
                        <!-- <li class="nav__item">
                            <a href="#nutrition" class="nav__item-link">Gợi Ý Sách</a>
                        </li> -->
                        <li class="nav__item">
                            <a href="#introduce" class="nav__item-link">Về chúng tôi</a>
                        </li>
                    </ul>
                    <script>
                        let list = document.querySelectorAll('.nav__item');
                        for (let i = 0; i < list.length; i++) {
                            list[i].onclick = function() {
                                let j = 0;
                                while (j < list.length) {
                                    list[j++].className = 'nav__item';
                                }
                                list[i].className = 'nav__item nav__item--active';
                            }
                        }
                    </script>

                    <!-- Mobile + Tablet -->
                    <div class="nav-mobile-btn js-nav-mobile-btn">
                        <i class="nav-mobile-btn-icon fas fa-bars"></i>
                    </div>

                    <div class="nav__overlay js-nav__overlay"></div>

                    <nav class="nav-mobile js-nav-mobile">
                        <div class="nav-mobile-close js-nav-mobile-close">
                            <i class="fas fa-times"></i>
                        </div>
                        <ul class="nav-mobile-list">
                            <li class="nav-mobile-item nav-mobile-item--active">
                                <a href="#" class="nav-mobile-link">TRANG CHỦ</a>
                            </li>
                            <li class="nav-mobile-item">
                                <a href="#category" class="nav-mobile-link">THÔNG TIN</a>
                            </li>
                            <li class="nav-mobile-item">
                                <a href="#product" class="nav-mobile-link">HÌNH ẢNH</a>
                            </li>
                            <li class="nav-mobile-item">
                                <a href="#introduce" class="nav-mobile-link">VỀ CHÚNG TÔI</a>
                            </li>
                        </ul>
                    </nav>
                    <script>
                        let list = document.querySelectorAll('.nav-mobile-item');
                        for (let i = 0; i < list.length; i++) {
                            list[i].onclick = function() {
                                let j = 0;
                                while (j < list.length) {
                                    list[j++].className = 'nav-mobile-item';
                                }
                                list[i].className = 'nav-mobile-item nav-mobile-item--active';
                            }
                        }
                    </script>
                </nav>
            </div>
        </header>
        <?php
        $title = 'Slider';
        $get_img = $web->get_img($title);
        if ($get_img) {
            foreach ($get_img as $data) {
        ?>
                <div id="slider"     style="background-image: url('../../public/admin/Image/web/<?php echo $data['Img'] ?>'); 
                                    background-repeat: no-repeat; 
                                    background-size: cover; 
                                    background-position: center;">
                    <div class="slider__text">
                        <!-- <div class="text-heading">Ngọc Nhi Store</div> -->
                        <div class="text-description" style ="color: #97bd27">Nhà Sách Ngọc Nhi</div>
                        <div class="text-description" style ="color: #97bd27">Tri Thức, Thắp Sáng Tương Lai</div>
                        <!-- <div class="text-footer">Chuyên cung cấp các loại sách nhiều thể loại đến từ các hãng điện thoại lớn trên thế giới với giá thành phải chăng, cùng những chính sách ưu đãi. Chỉ có tại Udo Store.</div> -->
                        <button onclick="window.location.href='product.php'" class="slider__btn">Sản phẩm</button>
                    </div>
                </div>
        <?php
            }
        }
        ?>

        <div class="app__container">
            <!-- Category -->
            <div class="grid wide" id="category">
                <div class="row category">
                    <?php
                    $get_category = $category->get_category();
                    if ($get_category) {
                        foreach ($get_category as $data) {
                    ?>
                            <div class="col l-4 m-4 c-12">
                                <div class="category__item">
                                    <div class="category__item-img" style="background-image: url('../../public/admin/Image/web/<?php echo $data['img'] ?>')"></div>
                                    <h4 class="category__item-name"><?php echo $data['NameCategory'] ?></h4>
                                    <!-- <span class="category__item-description"><?php echo $data['Title'] ?></span> -->
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>

            

            <!-- Product -->
            <!-- <div class="grid wide" id="product">
                <h4 class="product__heading">Hình ảnh sản phẩm</h4>
                <div class="row product">
                    <?php
                    $get_img = $web->get_img_product();
                    if ($get_img) {
                        foreach ($get_img as $data) {
                    ?>
                            <div class="col l-3 m-6 c-12">
                                <div class="product__img" style="background-image: url('../../public/web/img/products/<?php echo $data['ProductImg1'] ?>');"></div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div> -->

            <!-- Introduce -->
            <div class="introduce__wrap" id="introduce">
                <div class="grid wide">
                    <div class="row introduce">
                        <?php
                        $title = 'Session4_a';
                        $get_img = $web->get_img($title);
                        if ($get_img) {
                            foreach ($get_img as $data) {
                        ?>
                                <div class="col l-4 m-12 c-12">
                                    <h4 class="introduce__heading">VỀ CHÚNG TÔI</h4>
                                    <img src='../../public/admin/Image/web/<?php echo $data['Img'] ?>' class="introduce__img"></img>
                                    <p class="introduce__title">Nhà sách Ngọc Nhi chuyên cung cấp các mặt hàng sách thịnh hành trên thị trường, đảm bảo các sản phẩm được bán ra có chất lượng tốt nhất, đã được kiểm tra nghiêm ngặt. Ngoài ra, chúng tôi luôn có các chính sách ưu đãi dành cho khách hàng, tặng voucher giảm giá, hỗ trợ mua sách trả góp lãi suất thấp khi mua trực tiếp tại cửa hàng.</p>
                                </div>
                        <?php
                            }
                        }
                        ?>

                        <div class="col l-4 m-12 c-12">
                            <ul class="introduce-list">
                                <li class="introduce-item">
                                    <div class="introduce-item__name">Nguồn gốc các mặt hàng</div>
                                    <span class="introduce-item__description">Chúng tôi chọn các nhà cung cấp sách uy tín và đã hoạt động lâu dài như Nhà sách Fahasa, Tiki, Vinabook,... để hợp tác và nhận các sản phẩm sách từ phía nhà xuất bản.</span>
                                </li>
                                <li class="introduce-item">
                                    <div class="introduce-item__name">Chính sách</div>
                                    <span class="introduce-item__description">Chúng tôi luôn đặt quyền lợi của khách hàng lên hàng đầu, với những ưu đãi về giá cả như giảm giá sách, khuyến mãi, ưu đãi học sinh sinh viên,....</span>
                                </li>
                                <li class="introduce-item">
                                    <div class="introduce-item__name">Cách thức mua hàng</div>
                                    <span class="introduce-item__description">Nhà sách Ngọc Nhi hiện có mặt trên cả website và các mạng xã hội như Facebook. Mọi người có thể dễ dàng tìm hiểu và mua các sản phẩm sách của chúng tôi thông qua các kênh này hoặc trực tiếp từ trang web chính thức của Nhà sách Ngọc Nhi hoặc trang Facebook của chúng tôi.</span>
                                </li>
                            </ul>
                        </div>
                        <?php
                        $title = 'Session4_b';
                        $get_img = $web->get_img($title);
                        if ($get_img) {
                            foreach ($get_img as $data) {
                        ?>
                                <!-- <div class="col l-4 m-12 c-12">
                                    <div class="introduce-after">
                                        <div class="introduce-after2">
                                            <div class="introduce-child">
                                                <img class="introduce-child__img" src="../../public/admin/Image/web/<?php echo $data['Img'] ?>" alt="ảnh">
                                                <h5 class="introduce-child__name">Quy mô</h5>
                                                <span class="introduce-child__description">Với các quy trình chăm sóc hiện đại theo tiêu chuẩn thực phẩm sạch của quốc tế.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- NUTRITION -->
            <!--  -->
        </div>
    </div>

    <script src="../../public/web/js/index.js"></script>
</body>

</html>
