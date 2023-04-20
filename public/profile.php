<?php
include "../db.php";
session_start();

use CT275\Project\Customer;

$customer = new Customer($PDO);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Profile | Shop Hoàng Thượng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="css/base.css">

</head>

<body>
	<?php include('../partials/header.php') ?>
    <p class="paddingtop"></p>    

	<!-- Main Page Content -->
    <div class="row" >
        <div class="col-3"></div>
        <div class="col-6 border border-secondary rounded">
            <table class="table table-borderless mt-2" > 
                <?php 
                    $id = $_SESSION["customer_id"];
                    $customers = $customer->find($id);
                ?>
                <tr >
                    <td>Tên</td>
                    <td>:</td>
                    <td><?=htmlspecialchars($customer->name)?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td><?=htmlspecialchars($customer->email)?></td>
                </tr>
                <tr>
                    <td>SĐT</td>
                    <td>:</td>
                    <td><?=htmlspecialchars($customer->phone)?></td>
                </tr>
                <tr>
                    <td>Địa Chỉ</td>
                    <td>:</td>
                    <td><?=htmlspecialchars($customer->address)?></td>
                </tr>
            </table>   
        </div>

        <div style="margin-left:35%" class="mt-4">
            <a class="btn btn-primary" class href="">Cập Nhật Thông Tin</a>
            <a class="btn btn-primary" href="">Đổi Mật Khẩu</a>
        </div>
    </div>


    
	<?php include('../partials/footer.php') ?>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
	
</body>

</html>