<?php
include '../../Classes/admin/category.php';
include '../../Classes/web/product.php';
include_once '../../lib/session.php';
Session::init();
$product = new product();
$category = new category();
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $best_seller = isset($_POST['best_seller']) ? $_POST['best_seller'] : null;
    $book_genre = isset($_POST['book_genre']) ? $_POST['book_genre'] : null;
    $book_price = isset($_POST['book_price']) ? $_POST['book_price'] : null;
    $book_publisher = isset($_POST['book_publisher']) ? $_POST['book_publisher'] : null;

    $result = $product->getProductByMutileChoice($best_seller, $book_genre, $book_price, $book_publisher);
}
?>


<body>
    <div class="app">
        <?php
        include '../../inc/web/header.php';
        include '../../inc/web/sidebar.php';
        ?>
        <div class="app__container">
            <div class="grid wide">
                <div class="row sm-gutter app__content">
                    <div class="col l-2 m-0 c-0">
                        <nav class="category">
                            <h3 class="category__heading">Danh mục</h3>

                            <ul class="category-list">
                                <?php 
                                $listCategories = $category->get_category();
                                if ($listCategories) {
                                    foreach ($listCategories as $data) {
                                ?>
                                        <li class="category-item category-item--active">
                                            <a href="#<?php echo $data['IdCategory'] ?>" class="category-item__link"><?php echo $data['NameCategory'] ?></a>
                                        </li>
                                <?php
                                    }
                                }
                                ?>
                            </ul>
                            <script>
                                $(document).ready(function() {
                                    var page = 1;
                                    $(window).scroll(function() {
                                        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
                                            $.ajax({
                                                url: 'ajaxPaging.php',
                                                type: 'POST',
                                                dataType: 'html',
                                                data: {
                                                    'page': page
                                                }
                                            }).done(function(getHtml) {
                                                $('#listProduct').append(getHtml);
                                                if (page <= <?php echo $product->total_page() ?>)
                                                    page++;
                                            });
                                        }
                                    })
                                })
                                $(document).ready(function() {
                                    $('.category-item__link').click(function(e) {
                                        e.preventDefault();
                                        var idCategory = $(this).attr("href").substr(1)
                                        $.ajax({
                                            url: 'listProduct-by-category.php',
                                            type: 'POST',
                                            dataType: 'html',
                                            data: {
                                                'idCategory': idCategory
                                            }
                                        }).done(function(getHtml) {
                                            $('#listProduct').html(getHtml);
                                            page = null;
                                        });
                                    });
                                });
                            </script>
                        </nav>
                    </div>

                    <div class="col l-10 m-12 c-12">
                        <div class="home-filter hide-on-mobile-tablet">
                            <span class="home-filter__label">Sắp xếp theo</span>
                            <?php if (!empty($result)): ?>
                                <a href="" class="home-filter__btn btn btn--primary">Gợi ý</a>
                            <?php endif; ?>
                            <a href="#BEST" class="home-filter__btn btn <?php echo empty($result) ? 'btn--primary' : ''; ?>">Bán chạy</a>
                            <a href="#NEW" class="home-filter__btn btn">Mới nhất</a>

                            <div class="select-input">
                                <span class="select-input__label">Giá</span>
                                <i class="select-input__icon fas fa-chevron-down"></i>

                                <!-- List options -->
                                <ul class="select-input__list">
                                    <li class="select-input__item">
                                        <a href="#ASC" class="select-input__link">Giá: Thấp đến cao</a>
                                    </li>
                                    <li class="select-input__item">
                                        <a href="#DESC" class="select-input__link">Giá: Cao đến thấp</a>
                                    </li>
                                </ul>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('.home-filter__btn').click(function(e) {
                                        e.preventDefault();

                                        // Remove 'btn--primary' from all buttons
                                        $('.home-filter__btn').removeClass('btn--primary');

                                        // Add 'btn--primary' to the clicked button
                                        $(this).addClass('btn--primary');
                                        var option = $(this).attr("href").substr(1)
                                        if(option) {
                                            $.ajax({
                                                url: 'listProduct_by_price.php',
                                                type: 'POST',
                                                dataType: 'html',
                                                data: {
                                                    'option': option
                                                }
                                            }).done(function(getHtml) {
                                                $('#listProduct').html(getHtml);
                                                page = null;
                                            });
                                        }
                                        else {
                                            location.reload();
                                        }
                                    });
                                });
                            </script>
                            <script>
                                $(document).ready(function() {
                                    $('.select-input__link').click(function(e) {
                                        e.preventDefault();
                                        var option = $(this).attr("href").substr(1)
                                        $.ajax({
                                            url: 'listProduct_by_price.php',
                                            type: 'POST',
                                            dataType: 'html',
                                            data: {
                                                'option': option
                                            }
                                        }).done(function(getHtml) {
                                            $('#listProduct').html(getHtml);
                                            page = null;
                                        });
                                    });
                                });
                            </script>
                            <!-- <div class="home-filter__page">
                                <span class="home-filter__page-num">
                                    <span class="home-filter__page-current">1</span>/14
                                </span>

                                <div class="home-filter__page-control">
                                    <a href="" class="home-filter__page-btn home-filter__page-btn--disabled">
                                        <i class="home-filter__page-icon fas fa-chevron-left"></i>
                                    </a>
                                    <a href="" class="home-filter__page-btn">
                                        <i class="home-filter__page-icon fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            </div> -->
                        </div>

                        <nav class="mobile-category">
                            <ul class="mobile-category__list">
                                <?php
                                $listCategories = $category->get_category();
                                if ($listCategories) {
                                    foreach ($listCategories as $data) {
                                ?>
                                        <li class="mobile-category__item">
                                            <a href="#<?php echo $data['IdCategory'] ?>" class="mobile-category__link">
                                                <?php echo $data['NameCategory'] ?>
                                            </a>
                                        </li>
                                <?php
                                    }
                                }
                                ?>
                            </ul>
                            <script>
                                $(document).ready(function() {
                                    $('.mobile-category__link').click(function(e) {
                                        e.preventDefault();
                                        var idCategory = $(this).attr("href").substr(1)
                                        $.ajax({
                                            url: 'listProduct-by-category.php',
                                            type: 'POST',
                                            dataType: 'html',
                                            data: {
                                                'idCategory': idCategory
                                            }
                                        }).done(function(getHtml) {
                                            $('#listProduct').html(getHtml);
                                            page = null;
                                        });
                                    });
                                });
                            </script>
                        </nav>

                        <div class="home-product">
                            <div class="row sm-gutter" id="listProduct">
                                <?php
                                $listProducts = $product->get_product();
                                if(!empty($result)) $listProducts = $result;
                                if ($listProducts) {
                                    foreach ($listProducts as $data) {
                                ?>
                                        <div class="col l-2-4 m-4 c-6">
                                            <a class="product-item" href="product-detail.php?idProduct=<?php echo $data['IdProduct'] ?>&idCate=<?php echo $data['IdCategory'] ?>">
                                                <div class="product-item__img" style="background-image: url('<?php echo '../../public/web/img/products/' . $data['ProductImg1'] ?>'); border-radius: 10px"></div>
                                                <h4 class="product-item__name"><?php echo $data['NameProduct'] ?></h4>
                                                <div class="product-item__price">
                                                    <?php 
                                                        echo '<span class="product-item__price-current">' . $data['Price'] . 'đ</span>';
                                                     ?>

                                                </div>
                                                <div class="product-item__action">
                                                    <span class="product-item__like product-item__like--liked">
                                                        <i class="product-item__like-icon--empty far fa-heart"></i>
                                                    </span>
                                                    <div class="product-item__rating">

                                                    </div>

                                                    <span class="product-item__sold"><?php echo $data['TotalSold'] ?> đã bán</span>
                                                </div>
                                                <div class="product-item__origin">
                                                    <span class="product-item__brand"><?php echo $data['NameSupplier'] ?></span>
                                                    <span class="product-item__origin-name">Việt Nam</span>
                                                </div>
                                            </a>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>

                    
                    </div>
                </div>
            </div>
        </div>

        

    </div>
</body>

</html>