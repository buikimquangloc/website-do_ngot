<?php
include "../bootstrap.php";
session_start();

use CT275\Project\Product;
use CT275\Project\Category;

$product = new Product($PDO);
$cat = new Category($PDO);

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tukhoa'])) {
    $tukhoa = $_POST['tukhoa']; 
}
else{
    echo "<script>window.location ='index.php'</script>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Search | Shop Hoàng Thượng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
	<?php include('../partials/header.php') ?>

	<!-- Main Page Content -->
    <div class="row">   
		<div class="col-12 text-center">
            <div class="section_title">
                <h2>Đây là sản phẩm của bạn</h2>
                <p>Sản phẩm mang lại sự trải nghiệm tuyệt vời nhất</p>
            </div>
	    </div> 
	</div> 

	<div class="row mt-3">   
        <div class="row">
            <?php 
                $products = $product->search_product($tukhoa);
                foreach($products as $product): 
            ?>
            <?php if($cat->find($product->catId)->mode) {
            ?>
           <div class="col-3 mb-5 ">
                <div class="card shadow-lg" style="width: 16rem;  ">
                    <a href="productdetails.php?proid=<?=htmlspecialchars($product->getId())?>"><img style="width: 254px;height: 230px;z-index:-1;" class="img-fluid" src="admin/uploads/<?=htmlspecialchars($product->image)?>" alt="First place"></a>
                    <div class="card-body">
                        <h5 class="text-center"><a class="text-decoration-none text-warning text-center" href="#"><?=htmlspecialchars($product->productName)?></a></h5>
                        <span class="current_price"><?=htmlspecialchars($product->price)." "."VNĐ" ?></span>         
                    </div>
                </div>
            </div>
            <?php
                }
            ?>
            <?php endforeach ?>
        </div>

    <script src="js/dungchung1.js"></script>
	<?php include('../partials/footer.php') ?>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
	
</body>

</html>