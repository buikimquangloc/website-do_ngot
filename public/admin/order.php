<?php

include_once "C:/xampp/apps/project/bootstrap.php";


use CT275\Project\Order;
use CT275\Project\Customer;

$order = new Order($PDO);
$customer = new Customer($PDO);

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['shiftid']) && ($order->find($_GET['shiftid'])) !== null){
	$order->save_admin();
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
    <div class="col-lg-10">
	<div class="container border rounded" >
	<h4 class="m-4 text-primary">Danh sách đơn hàng</h4>
	<div class="row inner-wrapper ms-4 me-4">
		<table id="example" class="table text-center table-bordered align-middle table-striped table-responsive">
			<thead>
				<tr>
					<th>STT</th>
					<th>Tên người dùng</th>
					<th>Tổng hóa đơn</th>
					<th>Ngày Đặt</th>
					<th>Xem Thông Tin</th>
					<th>Hành Động</th>
				</tr>
			</thead>
			
			<tbody>
				<?php 
                    $orders = $order->all();
                    $i = 0;
                    $qty = 0;
                    foreach($orders as $order): $i++; 
				?>
				<tr>

					<td><?=$i?></td>
					<td><a href="#"></a><?=htmlspecialchars($customer->find($order->customer_id)->name)?></td>
					<!-- Currency Format -->
					<td><a href="#"></a><?=htmlspecialchars(number_format($order->total,0,'','.'))." "."VNĐ"?></td> 
					<td><a href="#"></a><?=htmlspecialchars($order->date_order)?></td> 

					<td><a class="text-decoration-none fw-semibold link-warning" href="customer.php?customerid=<?=htmlspecialchars($order->customer_id)?>">Xem Khách Hàng</a></td>
					<td>
						<?php
							if($order->status == '0') {										
						?>	
						<a href="?shiftid=<?=htmlspecialchars($order->getId())?>">Xử lý</a> 
						<?php
							}else if($order->status == '1'){								
						?>
							<?php
								echo 'Đang giao...';
							?>
						<?php
							}else if($order->status == '2'){
						?>
						<?php					
								echo 'N/A';
							}	
						?>
					</td>	
				</tr>
				<?php endforeach ?>	
			</tbody>  
							
		</table>   
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