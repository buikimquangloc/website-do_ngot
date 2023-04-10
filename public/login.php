
<?php
include "../bootstrap.php";
session_start();

use CT275\Project\Customer;
use CT275\Project\Cart;

$customer = new Customer($PDO);
$cart = new Cart($PDO);

$errors = [];
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $customer->fill($_POST);
    if($customer->validate()) {
        $insertCustomers = $customer->insert_customers();
    }
    $errors = $customer->getValidationErrors();
    
} 
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $customer->fill($_POST);
    if($customer->validateLogin()) {
        $loginCustomers = $customer->login_customers();
        if($cart->find3($_SESSION["customer_id"]) === NULL){
            $cart->fill($_SESSION); //Session trùng name nên thêm zô dc ngay
            $cart->save();
        }
        echo "<script>window.location ='index.php'</script>"; 
    }
    $errors = $customer->getValidationLoginErrors();
} 
   
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Login | Shop Hoàng Thượng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
	<?php include('../partials/header.php') ?>
    <p class="paddingtop"></p>    

	<!-- Main Page Content -->
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div>
                <h2 class="text-center">Đăng nhập</h2>
                <?php
                    if(isset($loginCustomers)){
                        echo $loginCustomers;
                    }
			    ?>
                <form action="" method="POST">    
                    <div>
                        <label for="email" class="form-label"><b>Email:</b></label>
                        <input type="email" class="form-control" placeholder="Nhập vào email" name="email">
                    </div>
                    <?php if (isset($errors['email']) && isset($_POST['login'])) : ?>
						<span class="text-danger">
							<strong><?= htmlspecialchars($errors['email']) ?></strong>
						</span>
                    <?php endif ?>

                    <div class="my-3">
                        <label for="password" class="form-label"><b>Mật khẩu:</b></label>
                        <input type="password" class="form-control" placeholder="Nhập vào mật khẩu" name="password">
                    </div>
                    <?php if (isset($errors['password']) && isset($_POST['login'])) : ?>
						<span class="text-danger">
							<strong><?= htmlspecialchars($errors['password']) ?></strong>
						</span>
                    <?php endif ?>

                    <div class="row">
                        <div class="col-6">
                            <div class="mt-2">
                                <a class="mt-2 text-decoration-none" href="changepassword.php">Quên mật khẩu?</a>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mt-2">
                                <label for="remember" class="form-check-label">
                                    <input id="remember" type="checkbox" class="form-check-input">
                                    Ghi nhớ tôi
                                </label>   
                            </div>
                        </div>
                        <div class="col-3 d-flex justify-content-end">
                            <input type="submit" name="login" class="btn btn-primary mb-3" value="Đăng nhập">
                        </div>
                    </div>

                </form>
            </div>    
            

        </div>
        <div class="col-lg-6 col-md-6">
            <div>
                <h2 class="text-center">Đăng Ký</h2>
                <?php
                    if(isset($insertCustomers)){
                        echo $insertCustomers;
                    }
                ?>	
                <form id="dangky" action="" method="POST">
                    <div>
                        <label for="email" class="form-label"><b>Tên:</b></label>
                        <input type="text" class="form-control" placeholder="Nhập tên" name="name">
                    </div>
                    <?php if (isset($errors['name']) && isset($_POST['submit'])) : ?>
						<span class="text-danger">
							<strong><?= htmlspecialchars($errors['name']) ?></strong>
						</span>
                    <?php endif ?>

                    <div  class="my-3">
                        <label for="address" class="form-label"><b>Địa Chỉ:</b></label>
                        <input type="text" class="form-control" placeholder="Nhập địa chỉ" name="address">
                    </div>
                    <?php if (isset($errors['address']) && isset($_POST['submit'])) : ?>
						<span class="text-danger">
							<strong><?= htmlspecialchars($errors['address']) ?></strong>
						</span>
                    <?php endif ?>

                    <div  class="my-3">
                        <label for="phone" class="form-label"><b>SĐT:</b></label>
                        <input type="number" class="form-control" placeholder="Nhập SĐT" name="phone">
                    </div>
                    <?php if (isset($errors['phone']) && isset($_POST['submit'])) : ?>
						<span class="text-danger">
							<strong><?= htmlspecialchars($errors['phone']) ?></strong>
						</span>
                    <?php endif ?>

                    <div>
                        <label for="email" class="form-label"><b>Email:</b></label>
                        <input type="email" class="form-control" placeholder="Nhập Email" name="email">
                    </div>
                    <?php if (isset($errors['email']) && isset($_POST['submit'])) : ?>
						<span class="text-danger">
							<strong><?= htmlspecialchars($errors['email']) ?></strong>
						</span>
                    <?php endif ?>
                    
                    <div  class="my-3">
                        <label for="password" class="form-label"><b>Mật Khẩu:</b></label>
                        <input type="password" class="form-control" placeholder="Nhập mật khẩu" name="password">
                    </div>
                    <?php if (isset($errors['password']) && isset($_POST['submit'])) : ?>
						<span class="text-danger">
							<strong><?= htmlspecialchars($errors['password']) ?></strong>
						</span>
                    <?php endif ?>

                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" type="submit" name="submit">Đăng Ký</button>
                    </div>
                </form>
            </div>    
        </div>
    </div>

    <script src="js/dungchung1.js"></script>
	<?php include('../partials/footer.php') ?>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
</body>

</html>