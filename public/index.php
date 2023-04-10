<?php
include "../bootstrap.php";

use CT275\Project\Product;
use CT275\Project\Category;

session_start();
$product = new Product($PDO);
$cat = new Category($PDO);
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Home Page | Shop Hoàng Thượng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
	<?php include('../partials/header.php') ?>
	<?php include('../partials/slider.php') ?>
	<!-- Main Page Content -->
	<div class="row">
        <div class="col-lg-4 col-md-6">
                <div>
                    <div>
                    <a href="#"><img style="width: 415px;height: 130px;" src="img/bg/banner1.png" alt="#"></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
            <div>
                <div>
                    <a href="#"><img style="width: 415px;height: 130px;" src="img/bg/banner2.png" alt="#"></a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div>
                <div>
                    <a href="#"><img style="width: 415px;height: 130px;" src="img/bg/banner3.png" alt="#"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">   
        <div class="col-12 text-center">
            <div>
                <h2>Sản phẩm của chúng tôi</h2>
            </div>
        </div> 
    </div>    
    <div class="row">
        <?php 
            $products = $product->all();
            foreach($products as $product): 
        ?>
        <?php if($cat->find($product->catId)->mode == 1) {
        ?>
        <div class="colsm5 mb-5 ">
            <div class="card shadow-lg colsm5lg" style="width: 15.3rem;  ">
                <a href="productdetails.php?proid=<?=htmlspecialchars($product->getId())?>"><img style="width: 254px;height: 230px;z-index:-1;" class="img-fluid " src="admin/uploads/<?=htmlspecialchars($product->image)?>" alt="First place"></a>
                <div class="card-body">
                    <h5 class="text-center"><a class="text-decoration-none text-warning text-center nameproduct" href="#"><?=htmlspecialchars($product->productName)?></a></h5>
                    <p class="current_price text-center"><?=htmlspecialchars($product->price). " "."VNĐ" ?></p>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
        <?php endforeach ?>
    </div>  

    <div class="row mt-3">   
        <div class="col-12 text-center">
            <div>
                <h2>Sản phẩm vừa mới ra mắt </h2>
                <p>Sản phẩm vừa mới được ra mắt</p>
            </div>
        </div> 
    </div>  

    <div class="row">
        <?php 
            $newproducts = $product->all_new();
            foreach($newproducts as $product): 
        ?>
        <?php if($cat->find($product->catId)->mode == 1) {
        ?>
        <div class="colsm5 mb-5 ">
            <div class="card shadow-lg colsm5lg" style="width: 16rem;  ">
                <a href="productdetails.php?proid=<?=htmlspecialchars($product->getId())?>"><img style="width: 254px;height: 230px;z-index:-1;" class="img-fluid nameproduct" src="admin/uploads/<?=htmlspecialchars($product->image)?>" alt="First place"></a>
                <div class="card-body">
                    <h3><a class="text-decoration-none text-warning" href="#"><?=htmlspecialchars($product->productName)?></a></h3>
                    <p class="current_price text-center"><?=htmlspecialchars($product->price). " "."VNĐ" ?></p>
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