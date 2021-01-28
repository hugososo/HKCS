<!-- inventory.php, editInventory.php, addProd.php, showInventory.php manage inventory function-->

<!--this add new goods info when user submit form from inventory.php-->
<?php

if (empty($_POST)) {
    header("refresh:3;url=index.php");
    die("<h2>Direct access not permitted</h2>");
}

session_start();
include("conn.php");
extract($_SESSION);
extract($_POST);

//show Invalid msg
if($showcase == null){
    header("refresh:3;url=Inventory.php");
    die("<h2>Please fill up all info</h2>");
}else if($proName == null){
    header("refresh:3;url=Inventory.php");
    die("<h2>Invalid Name</h2>");
}else if($proPrice == null || $proPrice <= 0){
    header("refresh:3;url=Inventory.php");
    die("<h2>Invalid Price</h2>");
}else if($proQty == null || $proQty <= 0) {
    header("refresh:3;url=Inventory.php");
    die("<h2>Invalid Qty</h2>");
}

//check if user have this showcase
$stmt = $conn->prepare("SELECT * FROM showcase WHERE tenantID = ? AND showcaseID = ?");
$stmt->bind_param("ii", $id,$showcase);
$stmt->execute();
$rs= $stmt->get_result();

//if user have the showcase
if($rs->num_rows == 1){

//  set up image path
    $imgDest = "image/";
    $path = $imgDest . basename($_FILES['proPic']['name']);

//    show msg when image exist
    if(file_exists($path)){
        header("refresh:3;url=Inventory.php");
        die("Insert Failed! Image already exist");
    }

  //    show msg when file != image
    if(!exif_imagetype($_FILES['proPic']['tmp_name'])){
        header("refresh:1;url=Inventory.php");
        die("Insert Failed! Choose Picture");
    }

  //      check if goods exist
  $st = $conn->prepare("SELECT * FROM goods WHERE goodsName = ? AND showcaseID = ?");
  $st->bind_param("si", $proName,$showcase);
  $st->execute();
  $r= $st->get_result();
  if($r->fetch_assoc() > 0){
    die("<h2>Product exist!</h2>");
  }

//    if image moved successfully
    if (move_uploaded_file($_FILES['proPic']['tmp_name'],$path)){

        //reset path value to store in db
        $path = basename($_FILES['proPic']['name']);
        $stmt = $conn->prepare("INSERT INTO goods VALUES (NULL,?,?,?,?,1,?)");
        $stmt->bind_param("sssss", $showcase,$proName,$proPrice,$proQty,$path);
        $stmt->execute();

        header("refresh:1;url=Inventory.php");
        echo "<h2>Insert Successful</h2>";
    }else{

        header("refresh:1;url=Inventory.php");
        echo "<h2>Insert Failed!</h2>";
    }

}else{
    echo "<h2>Insert Failed!</h2>";
}

