
    <div class="container">
        <!-- Header -->

        <div class="row m-4 position" style="margin:0;">
            
            <div class="col-2">
                <h1><a href="index.php" style="cursor: pointer;"><img src="img/logo/logomeo.png" class="logomeo" /></a></h1>
            </div>

            <div class="col-10" style="padding:0;">
                <nav class="navbar navbar-expand-lg bg-light nav">
                    <div class="container-fluid fst-italic">
                        <a class="nav-link active" aria-current="page" href="index.php">Trang Chủ</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item" style="padding: 0 20px;">
                                    <a class="nav-link active" aria-current="page" href="shopcategory.php">Cửa Hàng</a>
                                </li>
                                <li class="nav-item" style="padding: 0 20px;">
                                    <a class="nav-link active" aria-current="page" href="about.php">Giới Thiệu</a>
                                </li>
                                <li class="nav-item" style="padding: 0 20px;">
                                    <a class="nav-link active" aria-current="page" href="contact.php">Liên Hệ</a>
                                </li>
                                <li class="nav-item dropdown" style="padding: 0 20px;">
                                    <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Trang
                                    </a>
                                    <ul class="dropdown-menu">
                                        <?php
                                            if(isset($_SESSION["customer_login"]) && $_SESSION["customer_login"] == true) {
                                                echo '<li><a class="dropdown-item" href="profile.php">Tài Khoản</a></li>';
                                                
                                            }
                                            else {
                                                echo '<li><a class="dropdown-item" href="login.php">Tài Khoản</a></li>';                 
                                            }
			                            ?>  

                                        <?php
                                            if(isset($_SESSION["customer_login"]) && $_SESSION["customer_login"] == true) {
                                                echo '<li><a class="dropdown-item" href="cart.php">Giỏ Hàng</a></li>';
                                                
                                            }
                                            else {
                                                echo '<li><a class="dropdown-item" href="login.php">Giỏ Hàng</a></li>';                         
                                            }                               
			                            ?> 
                                        
                                        <?php
                                            if(isset($_SESSION["customer_login"]) && $_SESSION["customer_login"] == true) {
                                                echo '<li><a class="dropdown-item" href="orderdetails.php">Đơn Hàng</a></li>';
                                                
                                            }
                                            else {
                                                echo '<li><a class="dropdown-item" href="login.php">Đơn Hàng</a></li>';                         
                                            }                               
			                            ?> 

                                    </ul>
                                </li>
                                
                                
                                

                                <?php
                                    if(isset($_SESSION["customer_login"]) && $_SESSION["customer_login"] == false) {
                                        echo '<li class="nav-item"><a class="nav-link active" aria-current="page" href="login.php">Đăng Nhập</a></li>';
                                        
                                    }
                                    else {
                                        if(isset($_SESSION["customer_id"])) {
                                            echo '<li class="nav-item"><a class="nav-link active" aria-current="page" href="?customer_id='.$_SESSION["customer_id"].'">Đăng Xuất</a></li>';
                                        } 
                                        else {
                                            echo '<li class="nav-item"><a class="nav-link active" aria-current="page" href="login.php">Đăng Nhập</a></li>';
                                        }
                                                                
                                    }   

                                    if(isset($_GET['customer_id'])){
                                        session_destroy();
                                        header("Location:login.php");
                                    } 
                                ?>


                            </ul>
                            <form class="d-flex" role="search" action="search.php" method="POST">
                                <input class="form-control me-2" type="search" placeholder="Tìm kiếm" aria-label="Search" name="tukhoa">
                                <button class="btn btn-outline-success" name="search_product" type="submit">Tìm</button>
                            </form>
                        </div>
                    </div>
                </nav>         
            </div>
            
        </div>
        
        <hr/>

