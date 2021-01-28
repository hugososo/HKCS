<!-- inventory.php, editInventory.php, addProd.php, showInventory.php manage inventory function-->

<!--this save the edit of goods info from tenant-->
<?php
if(empty($_POST)){
    header("refresh:3;url=index.php");
    die("<h2>Direct access not permitted</h2>");
}
session_start();
include("conn.php");
extract($_POST);
extract($_SESSION);

//if any number <1 or <0, show msg
if($stockPrice <1 || $remainingStock < 0){
  header("refresh:3;url=index.php");
  die("<h2>Invalid input!</h2>");
}

//check if user have this showcase
$stmt = $conn->prepare("SELECT * FROM goods,showcase where goods.showcaseID = showcase.showcaseID and showcase.tenantID = '$id' and goods.showcaseID = ? and goods.goodsID = ?");
$stmt->bind_param("ss", $showcaseID,$goodsID);
$stmt->execute();
$rs= $stmt->get_result();

//if tenant have the showcase, update showcase
if($rs->num_rows == 1){

    $stmt = $conn->prepare("UPDATE goods set status = ?, stockPrice = ?, remainingStock = ? where goodsID = ?");
    $stmt->bind_param("ssss", $status,$stockPrice, $remainingStock, $goodsID);
    $stmt->execute();
}

//redirect to Inventory.php
header("Location:Inventory.php");

