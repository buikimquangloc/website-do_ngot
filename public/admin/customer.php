<?php

include_once "C:/xampp/apps/project/bootstrap.php";


use CT275\Project\Customer;

$customer = new Customer($PDO);

$customerid = isset($_REQUEST['customerid']) ?
filter_var($_REQUEST['customerid'], FILTER_SANITIZE_NUMBER_INT) : -1;

if ($customerid < 0 || !($customer->find($customerid))) {
    header("Location: order.php"); 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Hoàng Thượng</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="//cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <style>
        a {
            text-decoration:none;
        }
    </style>
</head>
<body class="container-fluid">
    <?php include('partials_admin/header.php') ?>
    <?php
        if($_SESSION["adminlogin"] == FALSE) {
            header("Location:login.php");
        }
    ?>
    <?php include('partials_admin/sidebar.php') ?>
    
    <div class="col-lg-10" >
    <div class="container border rounded" style="padding-bottom:14%">
        <div class="row">
        <div class="col-3"></div>
        <?php 
            $customers = $customer->find($customerid);
		?>
        <div class="mt-5 justify-content-center d-flex col-6"> 
                <table class="table table-borderless">	
                    <!-- Tên -->
                    <tr>
                        <td>Tên</td>      
                        <td>:</td>                     
                        <td>
                            <input  class="form-control" type="text" readonly="readonly" value="<?=htmlspecialchars($customers->name)?>"  />
                        </td>
                    </tr>
                    <!-- Địa chỉ -->
                    <tr>
                        <td>Địa chỉ</td>      
                        <td>:</td>                     
                        <td>
                            <input class="form-control" type="text" readonly="readonly" value="<?=htmlspecialchars($customers->address)?>"  />
                        </td>
                    </tr>
                    <!-- Số điện thoại -->
                    <tr>
                        <td>SĐT</td>      
                        <td>:</td>                     
                        <td>
                            <input class="form-control" type="text" readonly="readonly" value="<?=htmlspecialchars($customers->phone)?>"  />
                        </td>
                    </tr>
                    <!-- Email -->
                    <tr>
                        <td>Email</td>      
                        <td>:</td>                     
                        <td>
                            <input class="form-control" type="text" readonly="readonly" value="<?=htmlspecialchars($customers->email)?>"  />
                        </td>
                    </tr>					
                </table>
            </div>  
        </div>
    </div>
</div>

    <?php include('partials_admin/footer.php') ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    
    <script>
        $(document).ready(function () {
            $("#example").DataTable();
        });
    </script>
    
</body>