<!--salesRecords.php,showTenantOrders.php, delTenantOrder.php, tent_order.php show all tenant order info-->
<?php
session_start();
extract($_SESSION);
extract($_POST);
include("conn.php");

if (empty($_POST) || $role != "T"){
    header("refresh:3;url=index.php");
    die("<h2>Direct access not permitted</h2>");
}

//check if this order belong to tenant
$sql = "SELECT orders.orderID as orderID 
                    FROM orders,orderitem,tenant,showcase,goods
                    where tenant.tenantID = showcase.tenantID
                    and showcase.showcaseID = goods.showcaseID and goods.goodsID = orderitem.goodsID
                    and orderitem.orderID = orders.orderID and tenant.tenantID = '$id' and orders.orderID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $del_id);
$stmt->execute();
$rs= $stmt->get_result();

//    flag to check tenant identity
$isFake = false;
if($rs == false){
    $isFake = true;
}

if ($isFake) {
    header("refresh:3;url=index.php");
    die("<h2>Direct access not permitted</h2>");
}

//check if the order is in delivery state
$sql = "SELECT * from orders where orderID = ? and STATUS = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $del_id);
$stmt->execute();
$rs= $stmt->get_result();

//if match, delete orderitem first, and then the order itself
if($rs->num_rows == 1){
    $sql = "DELETE FROM orderitem WHERE orderID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $del_id);
    $stmt->execute();

    $sql = "DELETE FROM orders WHERE orderID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $del_id);
    $stmt->execute();
}



