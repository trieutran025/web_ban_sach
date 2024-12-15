<div class="col l-2 m-3 c-12">
    <div class="user-category">
        <div class="user-category__heading">
            <?php  
                if(Session::get('img')){
            ?>
                <div class="user-category__img" style="background-image: url('../../public/admin/Image/accountImg/<?php echo Session::get('img') ?>');"></div>
            <?php
                }
                else{
            ?>
                <div class="user-category__img" style="background-image: url('https://static2.yan.vn/YanNews/2167221/202102/facebook-cap-nhat-avatar-doi-voi-tai-khoan-khong-su-dung-anh-dai-dien-e4abd14d.jpg');"></div>
            <?php
                }
            ?>
            
            <div class="user-category__heading-info">
                <span class="user-category__title">Tài khoản của</span>
                <p class="user-category__name"><?php echo Session::get('Username') ?></p>
            </div>
        </div>
        <div class="user-category__container">
            <ul class="user-category__sidebar-menu">
                <li class="user-category__sidebar-item">
                    <a href="user.php" class="user-category__sidebar-item-link">
                        <span><i class="user-category__sidebar-item-icon fas fa-user"></i></span>
                        Thông tin tài khoản
                    </a>
                </li>
                <li class="user-category__sidebar-item">
                    <a href="user_order.php" class="user-category__sidebar-item-link">
                        <span><i class="user-category__sidebar-item-icon fas fa-clipboard"></i></span>
                        Đơn hàng
                    </a>
                </li>
                <li class="user-category__sidebar-item">
                    <a href="user_address.php" class="user-category__sidebar-item-link">
                        <span><i class="user-category__sidebar-item-icon fas fa-map-marker-alt"></i></span>
                        Địa chỉ
                    </a>
                </li>
                <li class="user-category__sidebar-item">
                    <a href="user_changePass.php" class="user-category__sidebar-item-link">
                        <span><i class="user-category__sidebar-item-icon fas fa-unlock-alt"></i></span>
                        Đổi mật khẩu
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>