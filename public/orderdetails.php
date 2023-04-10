<?php
include "../bootstrap.php";
session_start();

use CT275\Project\Customer;
use CT275\Project\Order;

$customer = new Customer($PDO);
$order = new Order($PDO);

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['confirmid']) && ($order->find($_GET['confirmid'])) !== null){
	$order->fill($_GET);
	$order->save_customer();
	header("Location: orderdetails.php");
}

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['orderId']) && ($order->find($_GET['orderId'])) !== null){
	$order->del_shifted_customer();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Order | Shop Hoàng Thượng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="css/base.css">
</head>

<body>
	<?php include('../partials/header.php') ?>
    <p class="paddingtop"></p>    

	<!-- Main Page Content -->
    <div class="row text-center">
		<div class="col-12">
			<table>
				<thead>
					<tr>
					<th style="width:5%">STT</th>
					<th style="width:12%">Tên người dùng</th>
					<th style="width:12%">Tổng hóa đơn</th>
					<th style="width:15%">Ngày Đặt</th>
					<th style="width:10%">Trạng Thái</th>
					<th style="width:10%">Hành Động</th>
					</tr>
				</thead>
				
                <?php 
                    $customer_id = $_SESSION["customer_id"];
                    $orders = $order->get_ordered($customer_id);
                    $i = 0;
                    $qty = 0;
                    foreach($orders as $order): $i++; 
                ?>
				<tbody>
					<tr>
						<td><?=$i?></td>
						<td><a href="#"></a><?=htmlspecialchars($customer->find($order->customer_id)->name)?></td>
						<!-- Currency Format -->
						<td><a href="#"></a><?=htmlspecialchars(number_format($order->total,0,'','.'))." "."VNĐ"?></td> 
						<td><a href="#"></a><?=htmlspecialchars($order->date_order)?></td> 
						
						<td>
							<?php
							if(htmlspecialchars($order->status) == '0') {
								echo 'Đang xử lý';
							}
							else if(htmlspecialchars($order->status) == '1'){
							?>
							<span>Đang giao...</span>
							<?php
							}else {
								echo 'Đã Nhận';
							}
							?>                                       
						</td>
						<?php
							if(htmlspecialchars($order->status) == '0') {
						?>
						<td><a class="btn btn-danger" onclick = "return confirm('Are you sure?')" href="?orderId=<?=htmlspecialchars($order->getId())?>">Xóa</a></td>                                    
						<?php
							}else if(htmlspecialchars($order->status) =='1'){
						?>
						<td><a class="btn btn-success" href="?confirmid=<?=	htmlspecialchars($order->getId())?>">Xác nhận</a></td>
						<?php
						}else if(htmlspecialchars($order->status) =='2'){   
						?>
						<td><?php echo 'N/A' ?></td>
						<?php
						}
						?>   
					</tr>
				</tbody>  
				<?php endforeach ?>                               
			</table>   
		</div>
	</div>   

	<script>
        function myFunction() {
        alert("Bạn chắc chứ!!!");
        }
    </script> 

    <script src="js/dungchung1.js"></script>
	<?php include('../partials/footer.php') ?>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
	
</body>

</html>