<?php

include_once "C:/xampp/apps/project/bootstrap.php";


use CT275\Project\Category;

$cat = new category($PDO); 


if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delId']) && ($cat->find($_GET['delId'])) !== null){
	$cat->del_category();
}

if(isset($_GET['id_category']) && isset($_GET['mode']) && ($cat->find($_GET['id_category'])) !== null) {
	$cat->update_mode_category();
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

    <div class="col-lg-10 " >
	<div class="container border rounded ">
		<h4 class="m-4 text-primary">Danh mục sản phẩm</h4>
		<div class="row inner-wrapper ms-4 me-4 ">

			<table id="example" class="table table-bordered align-middle text-center table-striped table-responsive " >
				<thead>
					<tr>
						<th class="text-center">STT</th>
						<th class="text-center">Tên Danh Mục</th>
						<th class="text-center">Mode</th>
						<th class="text-center">Thao Tác</th>
					</tr>
				</thead>
				
				<tbody>
					<?php 
						$cats = $cat->all();
						$i = 0;
						foreach($cats as $cat): $i++
					?>
					<tr>
						
						<td><?= $i ?></td>
						<td><?=htmlspecialchars($cat->catName)?></td>
						<td>
							<?php
								if($cat->mode == 1) {
							?>	
							<a class="text-decoration-none fw-semibold link-danger" href="?id_category=<?=htmlspecialchars($cat->getId())?>&mode=0">Off</a> 
							<?php
							}else{
							?>
							<a class="text-decoration-none fw-semibold link-success" href="?id_category=<?=htmlspecialchars($cat->getId())?>&mode=1">On</a> 
							<?php
							}
							?>
						</td>	
						<td>
							<a class="btn btn-warning" href="catedit.php?catId=<?=htmlspecialchars($cat->getId())?>">Chỉnh sửa</a>
							<a class="btn btn-danger" href="?delId=<?=htmlspecialchars($cat->getId())?>" onclick="return confirm('Are you sure!');" >Xóa</a> 
						</td>	
					</tr>
					<?php endforeach ?>	
				</tbody> 			
			</table>   
		</div >
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