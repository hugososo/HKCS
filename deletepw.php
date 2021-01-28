<?php
    session_start();
    include("conn.php");
    $sql = "SELECT orderID, status from orders where customerEmail = '{$_SESSION['id']}'";  //find the order belong to customer
    $rs = mysqli_query($conn, $sql) or die (mysqli_error($conn));
    while($rc = mysqli_fetch_assoc($rs)){
        extract($rc);
        if($status==1 || $status==2){
            //find the goods id and quantity that belong to the order
            $sql = "SELECT goodsID, quantity from orderitem where orderID = '$orderID'";
            $rs2 = mysqli_query($conn, $sql) or die (mysqli_error($conn));
            while($rc2 = mysqli_fetch_assoc($rs2)){
                extract($rc2);
                //if order havn't completed, return the goods quantity to goods table
                $sql = "UPDATE goods SET remainingStock = remainingStock+$quantity, status = 1 WHERE goodsID = '$goodsID'";
                mysqli_query($conn, $sql) or die (mysqli_error($conn));
            }
        }
        mysqli_free_result($rs2);
        //Delete order item first
        $sql = "DELETE from orderitem where orderID = '$orderID'";
        mysqli_query($conn, $sql) or die (mysqli_error($conn));
    }
    //Delete order
    $sql = "DELETE from orders where customerEmail = '{$_SESSION['id']}'";
    mysqli_query($conn, $sql) or die (mysqli_error($conn));
    //Delete account
    $sql = "DELETE from customer where customerEmail = '{$_SESSION['id']}'";
    mysqli_query($conn, $sql) or die (mysqli_error($conn));
    mysqli_free_result($rs);
    mysqli_close();
    include("destroySession.php");
?>
