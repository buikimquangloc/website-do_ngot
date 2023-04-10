<?php

include_once "C:/xampp/apps/project/bootstrap.php";

use CT275\Project\Adminlogin;

$adminlogin = new Adminlogin($PDO);
	$errors = [];
	if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
		$adminlogin->fill($_POST);
		if($adminlogin->validate()) {
			$adminlogin->login_admin();
		}
	}
$errors = $adminlogin->getValidationErrors();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QUẢN LÍ NƯỚC</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="css/base1.css" media="screen" />
</head>
<body class="container-fluid">
	<div class="container">
		<div class="screen">
			<div class="screen__content">
				<form class="login"style="padding-top: 100px;" method="POST">

					<h1 class="text-center" style="padding-bottom: 20px;">ĐĂNG NHẬP</h1>

					<div class="login__field">
						
						<i class="login__icon fas fa-user"></i>
						<input type="text" name="adminUser" class="login__input" placeholder="Tài Khoản">
					</div>
					<?php if (isset($errors['adminUser'])) : ?>
						<span class="help-block">
							<strong class="tkerror"><i><?= htmlspecialchars($errors['adminUser']) ?></i></strong>
						</span>
                    <?php endif ?>

					<div class="login__field">
						<i class="login__icon fas fa-lock"></i>
						<input type="password" name="adminPass" class="login__input" placeholder="Mật Khẩu">
					</div>

					<?php if (isset($errors['adminPass'])) : ?>
						<span class="help-block">
							<strong class="tkerror"><i><?= htmlspecialchars($errors['adminPass']) ?></i></strong>
						</span>
                    <?php endif ?>
					
					<button type="submit" value="Đăng nhập" name="submit" class="button login__submit dn">	<p class="text-center" style="width:100%;">Đăng Nhập</p>
						<i class="button__icon fas fa-chevron-right" value="Đăng nhập"></i>
					</button>				
				</form>
			</div>

			<div class="screen__background">
				<span class="screen__background__shape screen__background__shape3"></span>		
				<span class="screen__background__shape screen__background__shape2"></span>
				<span class="screen__background__shape screen__background__shape1"></span>
			</div>	

		</div>
	</div>
	
</body>
</html>

