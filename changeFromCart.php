<!--cart.php,changeFromCart.php, showCartContent.php,deleteFromCart.php handle the shopping cart page-->
<!--this handle changeTotal() in cart.php -->
<?php

if (empty($_POST)) {
    header("refresh:3;url=index.php");
    die("<h2>Direct access not permitted</h2>");
}

session_start();
extract($_POST);
extract($_SESSION);

//$changeProduct is the goodsID that change the qty
foreach($cart as $goodsID=>$qty){
    if($goodsID == $changeProduct){
        $_SESSION['cart'][$goodsID] = $newQty;
    }
}
?>
