<?php
include "../bootstrap.php";
session_start();
use CT275\Project\Cart;
use CT275\Project\Product;
use CT275\Project\Cartdetail;
use CT275\Project\Order;

    $cart = new Cart($PDO);
    $product = new Product($PDO);
    $order = new Order($PDO);
    $cartdetail = new Cartdetail($PDO);

    $cartdetails = $cartdetail->all_customer_cart( $cart->find3($_SESSION["customer_id"])->getId() );
    $customer_id = $_SESSION["customer_id"];


    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['cartId']) && isset($_GET['productId']) && ($cartdetail->find1($_GET['cartId'])) !== null  && ($cartdetail->find2($_GET['productId'])) !== null){
		$cartdetail->del_product_cart();
        header("Location: cart.php");
	}

    if($_SERVER['REQUEST_METHOD'] ==='POST' && isset($_POST['submit'])) {
        $cartdetail->fill($_POST);
        if ($cartdetail->update_quantity_cart()) {
            header("Location: cart.php");
        }
    }

    if($_SERVER['REQUEST_METHOD'] ==='POST' && isset($_POST['order'])){
        $order->fill($_POST);  
        $order->save_customer();

        $cartdetail->fill($_POST);
        $cartdetail->del_all_cart();
        header("Location: orderdetails.php");  
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Cart | Shop Hoàng Thượng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="css/base.css">
</head>

<body>
	<?php include('../partials/header.php') ?>
    <p class="paddingtop"></p>    

	<!-- Main Page Content -->
	<form action="" method="POST"> 
        <div class="row text-center">
            <div class="col-12">
            
                <table>
                    <thead>
                        <tr>
                            <th style="width:5%">STT</th>
                            <th style="width:20%">Sản Phẩm</th>
                            <th style="width:20%">Hình Ảnh</th>
                            <th style="width:15%">Giá</th>
                            <th style="width:15%">Số Lượng</th>
                            <th style="width:15%">Tổng Cộng</th>
                            <th style="width:10%">Xóa</th>
                        </tr>
                    </thead>
                    <?php 
                        $i=0;
                        $subtotal = 0;
                        $qty = 0;
                        foreach($cartdetails as $cartdetail): $i++; 
                    ?>
                    <tbody>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=htmlspecialchars($product->find($cartdetail->productId)->productName)?></td>
                            <td><img img style="width:50%" src="admin/uploads/<?=htmlspecialchars($product->find($cartdetail->productId)->image)?>" alt=""/></a></td>
                            <td><?=htmlspecialchars($product->find($cartdetail->productId)->price). " ". "VNĐ"?></td>
                            <td>
                                <form action="" method="POST">
                                    <div class="row">
                                        <input type="hidden" name="cartId" value="<?=htmlspecialchars($cartdetail->getId())?>"/>
                                        <input type="hidden" name="productId" value="<?=htmlspecialchars($cartdetail->productId)?>"/>
                                        <div class="col-6">
                                            <input class="form-control" min="1" max="<?=htmlspecialchars($product->find($cartdetail->productId)->sl_conlai)?>" name="quantity" value="<?=htmlspecialchars($cartdetail->quantity)?>" type="number" class="w-50">
                                        </div>
                                        <div class="col-6">
                                            <input class="btn btn-warning" type="submit" name="submit" value="Update"/>
                                        </div>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <?php 
                                    $price1 = htmlspecialchars($product->find($cartdetail->productId)->price);
                                    $quantity1 = htmlspecialchars($cartdetail->quantity);
                                    $total = $price1 * $quantity1;
                                    echo $total . " " . "VNĐ";
                                ?>           
                            </td>
                            <td><a class="btn btn-danger" onclick = "return confirm('Are you sure?')" href="?cartId=<?=htmlspecialchars($cartdetail->getId())?>&productId=<?=htmlspecialchars($cartdetail->productId)?>">Xóa</a></td>
                        </tr>
                    </tbody>  
                    <?php
                        $subtotal += $total; $qty += $quantity1;
                    ?>
                    <?php endforeach ?>                                 
                </table>   
            </div>
        </div>               
    </form> 

    <div class="row my-5">
            
            <div class="col-lg-8 col-md-8"> 
                <?php
                    $check_cart = $cartdetail->check_cart();
                    if($check_cart != TRUE) {	
                        echo 'Giỏ hàng đang rỗng!!!';
                       
                    }
                ?>
            </div>

            <?php
                $check_cart = $cartdetail->check_cart();
                    if($check_cart == TRUE) {				
            ?>
            <div class="col-lg-4 col-md-4">
                <div>
                    <form action="" method="POST">
                        <h3 class="text-center fw-bolder">Chi Tiết</h3>
                        <div class="border rounded ">
                            <div class="row m-3">
                                <div class="col-4">Tổng cộng:</div>
                                <div class="col-8 text-end">
                                    <?php 							
                                        echo $subtotal." "."VND";
                                    ?>
                                </div>
                            </div>

                            <div class="row m-3">
                                <div class="col-4"></div>
                                <div class="col-8 text-end">
                                    Free Ship
                                </div>
                            </div>

                            <div class="row m-3">
                                <div class="col-4">Thành Tiền</div>
                                <div class="col-8 text-end">
                                    <?php 										
                                        $gtotal = $subtotal;
                                        echo $gtotal." "."VND";
                                    ?> 
                                    
                                    <input type="hidden" name="customer_id" value="<?=htmlspecialchars($cart->customer_id)?>">
                                    <input type="hidden" name="total" value="<?php echo $gtotal ?>">
                                    <input type="hidden" name="cartId" value="<?=htmlspecialchars($cartdetail->getId())?>">
                                </div>
                            </div>
                        
                        </div>
                        <div class="mt-3">
                            <button onclick="myFunction()" class="btn btn-success" type="submit" name="order">Đặt hàng</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php
                }
            ?>
        </div>

    <script>
        function myFunction() {
            alert("Bạn đã đặt hàng thành công, vui lòng nhấn OK để xem chi tiết lịch sử đặt hàng");
        }
    </script> 

    <script src="js/dungchung1.js"></script>
	<?php include('../partials/footer.php') ?>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>

</body>

</html>