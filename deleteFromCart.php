<!--cart.php,changeFromCart.php, showCartContent.php,deleteFromCart.php handle the shopping cart page-->
<!--this handle delProdInCart() in cart.php -->

<?php

if (empty($_POST)) {
    header("refresh:3;url=index.php");
    die("<h2>Direct access not permitted</h2>");
}

session_start();
extract($_POST);
extract($_SESSION);

//if reset all item
if($deleteProduct ==="all"){
    unset($_SESSION['cart']);
}else{

//  if delete one item
    foreach($cart as $goodsID=>$qty){
        if($goodsID == $deleteProduct){
            $_SESSION['cart']['items']--;
            unset($_SESSION['cart'][$goodsID]);
        }
    }
}



