<!-- Bootstrap CSS , for styling the alert message only-->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">


<?php
session_start();
//access control
if (empty($_SESSION['id'])) {   //havn't login
        header("refresh:1;url=checkout.php");
        die("<div class='alert alert-danger text-center' role='alert'>Please Login in first.</div>");
} else if (($_SESSION['role']) == 'T') {    //Tenant account
        header("refresh:1;url=index.php");
        die("<div class='alert alert-danger text-center' role='alert'>Tenant cannot purchase items</div>");
} else if (empty($_POST['shopid'])) {   //havn't select a pick up place
        header("refresh:1;url=checkout.php");
        die("<div class='alert alert-danger text-center' role='alert'>Please Select a Pick-up Place First.</div>");
} else {
    include("conn.php");
    extract($_SESSION);
    extract($_POST);
    $approve = false;   //the variable for store whether the check out request get approve or not
    $orderStatus;       //the variable for store the order status such as awaiting, delivery and completed
        
    foreach($cart as $key=>$val){
        if($key=="items")   //Because our session array structure, if looping to "items", skip it
            continue;
    $sql = "SELECT * FROM goods where goodsID = '$key'";    //find the goods
    $rs = mysqli_query($conn, $sql) or die (mysqli_error($conn));
    $rc = mysqli_fetch_assoc($rs);
    extract($rc);
        
            if($remainingStock == 0 || $status == 2){   //if goods is unavailable, set it to unavailable and show the alert and die
                $sql = "UPDATE goods SET status = 2 where goodsID = '$key'";
                mysqli_query($conn, $sql);
                header("refresh:2;url=cart.php");
                die("<div class='alert alert-danger text-center' role='alert'>The item $goodsName is no longer available</div>");
            } else if($val > $remainingStock){  //if customer buy quantity more than the goods remaining stock, show alert and die
                header("refresh:2;url=cart.php");
                die("<div class='alert alert-danger text-center' role='alert'>The item $goodsName only has $val stock</div>");
            } else if($val <= 0) {  //if customer wants to buy negative quantity of the goods, show alert and die
                header("refresh:2;url=cart.php");
                die("<div class='alert alert-danger text-center' role='alert'>The items $goodsName should buy at least 1 qty</div>");
            } else {    //else approve the check out request
                $sql = "SELECT goods.goodsID, showcase.showcaseID, showcase.shopID FROM goods,showcase WHERE goods.showcaseID=showcase.showcaseID AND goodsID = '$key'";
                $rs2 = mysqli_query($conn, $sql);
                $rc2 = mysqli_fetch_assoc($rs2);
                if($rc2['shopID'] == $shopid)   //if shop id is same as the goods shop, the status set to awaiting
                    $orderStatus = 2;
                else
                    $orderStatus = 1;   //if shop id is different with the goods shop, the status set to delivery
                $approve = true;    //approve the check out request
            }
        mysqli_free_result($rs);
        mysqli_free_result($rs2);
    }
    
    if($approve){   // if approve, place the order in order table
        $sql = "INSERT INTO orders values (null,'$id',$shopid,NOW(),$orderStatus)";
        mysqli_query($conn, $sql) or die (mysqli_error($conn));
        $order_id = mysqli_insert_id($conn);    // Grab the order id that just placed

        foreach($cart as $key=>$val){
            if($key=="items")
                continue;
            $sql = "SELECT * FROM goods where goodsID = '$key'";
            $rs = mysqli_query($conn, $sql) or die (mysqli_error($conn));
            $rc = mysqli_fetch_assoc($rs);
            extract($rc);

            if($val == $remainingStock){    //if customer buy qty = the goods remaining stock, set the goods qty to 0, and unavailable to buy
                $sql = "UPDATE goods SET remainingStock = 0, status = 2 where goodsID = '$key'";
                mysqli_query($conn, $sql) or die (mysqli_error($conn));
                $sql = "INSERT INTO orderitem values ($order_id,$key,$val,$stockPrice)";    //insert the goods information to orderitem table
                mysqli_query($conn, $sql) or die (mysqli_error($conn));
            } else {    //if the customer buy qty is not = remaining stock, just subtract it 
                $sql = "UPDATE goods SET remainingStock = $remainingStock-$val where goodsID = '$key'";
                mysqli_query($conn, $sql) or die (mysqli_error($conn));
                $sql = "INSERT INTO orderitem values ($order_id,$key,$val,$stockPrice)";    //insert the goods information to orderitem table
                mysqli_query($conn, $sql) or die (mysqli_error($conn));
            }
        }
        unset($_SESSION['cart']);   //clear the shopping cart
        header("Location:confirm.php?orderID=$order_id");
    } else {
        header("refresh:2;url=cart.php");
        die("<div class='alert alert-danger text-center' role='alert'>The items is no longer available</div>");
    }
}
?>
