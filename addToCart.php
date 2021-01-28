<?php
if (empty($_POST)) {
    header( 'refresh:3;url=product.php' );
    die( '<h2>Direct access not permitted</h2>' );
}
if (!empty($_POST)) {
    session_start();
    $goodsID = $_POST['goodsID'];
    $buyQty = $_POST['buyQty'];
    $jsonarray = array();   //define a json array to store return data
    
    if (isset( $_SESSION['cart'][$goodsID])) {  //check the goods whether put in shopping cart or not
        $alertmsg = "<div class='alert alert-danger text-center' role='alert'>The Item already put in the Shopping Cart!</div>";
        $jsonarray[] = $alertmsg;   //store the alert message to jsonarray for echo later
        $jsonarray[] = itemsPrice();    //store the Item Price to jsonarray for echo later
    }
    if (empty($_SESSION['cart'][$goodsID])) {   //if the goods havn't put in shopping cart, do the following program
        $_SESSION['cart'][$goodsID] = $buyQty;  //save the qty in session that customer put
        $successmsg = "<div class='alert alert-success text-center' role='alert'>Add to Shopping Cart Successfully!</div>";
        $jsonarray[] = $successmsg;
        $jsonarray[] = itemsPrice();
        if (isset($_SESSION['cart']['items']))    //if already have items array, +1 to it
        $_SESSION['cart']['items'] += 1;
        if (empty($_SESSION['cart']['items']))  //if havn't items array, set 1 to it
        $_SESSION['cart']['items'] = 1;
    }
    $itemsNum = $_SESSION['cart']['items'];
    $jsonarray[] = $itemsNum;   //put the item number to json array
    echo json_encode($jsonarray); //echo the json array in json format for the ajax handler
}

function itemsPrice() { //the function that find the items Price
    include('conn.php');
    extract($_SESSION);
    $totalFee = 0;
    foreach ( $cart as $key=>$val ) {
        $sql = "SELECT * FROM goods where goodsID = '$key'";
        $rs = mysqli_query( $conn, $sql );
        while( $rc = mysqli_fetch_array( $rs ) ) {
            extract( $rc );
            $totalFee += $val*$stockPrice;
        }
    }
    return $totalFee;
}
?>
