<?php

//this page save functions that are useful for cust_order.php, order_detail.php,
// salesRecord.php, Inventory.php and Tenant.php

if (!defined('nani')) {
    header("refresh:3;url=index.php");
    die("<h2>Direct access not permitted</h2>");
}


//this function return result set for selectPage.php, and sql for FindResultsByArgs.php
function getFilteredRows($table,$where,$args,$getActualResult = true,$sort){
include("conn.php");
extract($_SESSION);
$stmt = "";

//    all about cust_order.php and order_detail.php
    if($table == "orders"){
        switch ($where){
            //used for cust_order.php, default result
            case "customerEmail":
                $sql = "SELECT * FROM $table WHERE customerEmail = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $id);
                break;
            case "orderID":
//                used when user search by orderID
                $sql = "SELECT * FROM $table WHERE customerEmail = ? AND orderID = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $id, $args);
                break;

            //                used when user search by date
            case "orderDateTime":
                $para = "$args%";
                $sql = "SELECT * FROM $table WHERE customerEmail = ? AND orderDateTime LIKE ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $id, $para);
                break;
        }

//        used for order_detail.php
    }else if($table == "orderitem"){
        $sql = "SELECT * FROM $table WHERE orderID = ? ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $args);

//        used for Inventory.php
    }else if($table == "goods"){
        switch($where){

//            if user search by available goods
            case "status":
                $sql = "SELECT * FROM $table,showcase 
                                            where $table.showcaseID = showcase.showcaseID 
                                            and showcase.tenantID = '$id' 
                                            and $table.status = 1 ";
                $stmt = $conn->prepare($sql);
                break;

//                search by showcase
            case  "showcase":
                $sql = "SELECT * FROM $table,showcase 
                                                where $table.showcaseID = showcase.showcaseID 
                                                and showcase.tenantID = '$id' 
                                                and $table.showcaseID = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $args);
                break;

//                sorting by showcase ID order
            case "showcaseOrder":
                $sql = "SELECT * FROM $table,showcase 
                                            where $table.showcaseID = showcase.showcaseID 
                                            and showcase.tenantID = '$id' ORDER BY $table.showcaseID $sort";
                $stmt = $conn->prepare($sql);
                break;

//                search by goodsID
            case "goodsID":
                $sql = "SELECT * FROM $table,showcase 
                                            where $table.showcaseID = showcase.showcaseID 
                                            and showcase.tenantID = '$id' and $table.goodsID = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $args);
                break;

//                search by goods Name
            case "prodName":
                $sql = "SELECT * FROM $table,showcase 
                                            where $table.showcaseID = showcase.showcaseID 
                                            and showcase.tenantID = '$id' 
                                            and $table.goodsName LIKE ? ";
                $para = "%$args%";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $para);
                break;

//                sorting by price order
            case "price":
                $sql = "SELECT * FROM $table,showcase 
                                            where $table.showcaseID = showcase.showcaseID 
                                            and showcase.tenantID = '$id' 
                                            order by $table.stockPrice $sort";
                $stmt = $conn->prepare($sql);
                break;

//                default, show all goods
            default:
                $sql ="SELECT * FROM $table,showcase 
                        where $table.showcaseID = showcase.showcaseID 
                        and showcase.tenantID = '$id'";
                $stmt = $conn->prepare($sql);
                break;
        }

//        used in tent_order.php and salesRecord.php
    }else if($table == "orderitem,orders"){

//        find all orders relate to the tenants
        $sql = "SELECT orders.orderID as orderID,MIN(orders.orderDateTime) AS datetime,
                    MIN(orders.status) as orderStatus,SUM(orderitem.quantity*orderitem.sellingPrice) as total
                    FROM orders,orderitem,tenant,showcase,goods
                    where tenant.tenantID = showcase.tenantID
                    and showcase.showcaseID = goods.showcaseID and goods.goodsID = orderitem.goodsID
                    and orderitem.orderID = orders.orderID and tenant.tenantID = '$id' ";

//        if user filter result
        if($where != ""){
            $para = explode(" ", $where);

//            filter by dateTime
            if(count($para) == 3 || count($para) == 4){
                $sql = getSQL_OrderStatus($para[2],$sql);
                $para[0] .= " 00:00:00";
                $para[1] .= " 23:59:59";
                $sql.= "and orders.orderDateTime between ? and  ? ";

            }else{
//                filter by order status
                $sql = getSQL_OrderStatus($where,$sql);
            }
            $sql .= " GROUP BY orders.orderID ";

//            if user sort the order after filter the result
            if($args != null){
//                $sql = sortTenantOrderByDateOrPrice($sql, $args,$startPos,$pageInfo);
            }else{
                $sql .=" ORDER BY datetime DESC ";
            }

//            prepare statment for different condition
            if(count($para) == 3 || count($para) == 4) {
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $para[0], $para[1]);
            }else{
                $stmt = $conn->prepare($sql);
            }


//            if user dont filter result
        }else{

//            if user sort the result
            if ($args != null){
//                $sql = sortTenantOrderByDateOrPrice($sql, $args,$startPos,$pageInfo);
                $sql .= " GROUP BY orders.orderID ";
                $stmt = $conn->prepare($sql);

//                d//                default setting if no filter and sorting
            }else{
                $sql .= " GROUP BY orders.orderID ORDER BY datetime DESC ";
                $stmt = $conn->prepare($sql);
            }
        }

    }





    $stmt->execute();
    $rs= $stmt->get_result();


    if($getActualResult){
//        for FindResultsByArgs.php
        return $sql;
    }else{
//        for selectPage.php
        return $rs;
    }

}

//get actual result set from different sorting and filter options
function GetActualResult($table,$where,$args,$startPos,$sql,$sort){
    include("conn.php");
    extract($_SESSION);

    //    all about cust_order.php and order_detail.php
    if($table == "orders"){
        switch ($where){
            //used for cust_order.php, default result
            case "customerEmail":
                $sql .=  "ORDER BY orderDateTime $sort LIMIT $startPos,{$pageInfo['orders_In_1Page']}";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $id);
                break;

            //                used when user search by orderID
            case "orderID":
                $sql .=  "ORDER BY orderDateTime $sort LIMIT $startPos,{$pageInfo['orders_In_1Page']}";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $id, $args);
                break;

//                search by date
            case "orderDateTime":
                $para = "$args%";
                $sql .=  "ORDER BY orderDateTime $sort LIMIT $startPos,{$pageInfo['orders_In_1Page']}";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $id, $para);
                break;
        }

//        for order_detail.php
    }else if($table == "orderitem"){
        $sql .= " LIMIT $startPos,{$pageInfo['orders_In_1Page']}";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $args);

//        used for Inventory.php
    }else if($table == "goods"){
        switch($where){

//            different cases with different bind_param
            case  "showcase":
            case "goodsID":
                $sql .= " LIMIT $startPos,{$pageInfo['orders_In_1Page']}";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $args);
                break;

            case "prodName":
                $para = "%$args%";
                $sql .= " LIMIT $startPos,{$pageInfo['orders_In_1Page']}";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $para);
                break;

            case "status":
            case "showcaseOrder":
            case "price":
                $sql .= " LIMIT $startPos,{$pageInfo['orders_In_1Page']}";
                $stmt = $conn->prepare($sql);
                break;

            default:
                $sql = "SELECT * FROM $table,showcase 
                                    where $table.showcaseID = showcase.showcaseID 
                                    and showcase.tenantID = '$id'";
                $stmt = $conn->prepare($sql);
                break;
        }

        //        used in tent_order.php and salesRecord.php
    }else if($table == "orderitem,orders"){

        //        find all orders relate to the tenants
        $sql = "SELECT orders.orderID as orderID,MIN(orders.orderDateTime) AS datetime,
                    MIN(orders.status) as orderStatus,SUM(orderitem.quantity*orderitem.sellingPrice) as total
                    FROM orders,orderitem,tenant,showcase,goods
                    where tenant.tenantID = showcase.tenantID
                    and showcase.showcaseID = goods.showcaseID and goods.goodsID = orderitem.goodsID
                    and orderitem.orderID = orders.orderID and tenant.tenantID = '$id' ";

//        if user filter result
        if($where != null){
            $para = explode(" ", $where);
            if(count($para) == 3 || count($para) == 4){
                //            filter by dateTime
                $sql = getSQL_OrderStatus($para[2],$sql);
                $para[0] .= " 00:00:00";
                $para[1] .= " 23:59:59";
                $sql.= "and orders.orderDateTime between ? and  ? ";

            }else{
                //                filter by order status
                $sql = getSQL_OrderStatus($where,$sql);
            }

                 $sql .= " GROUP BY orders.orderID ";

            if($args != null){
                if($args == " "){
                    $sql .= " ORDER BY datetime DESC LIMIT $startPos,{$pageInfo['orders_In_1Page']}";
                }
//                  call function to decide order by what
                $sql = sortTenantOrderByDateOrPrice($sql, $args,$startPos,$pageInfo);
            }else{
//                default order by
                $sql .= " ORDER BY datetime DESC LIMIT $startPos,{$pageInfo['orders_In_1Page']}";
            }

            if(count($para) == 3 || count($para) == 4){
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $para[0], $para[1]);
            }else{
                $stmt = $conn->prepare($sql);
            }

//            if user dont filter result
        }else{

            //            if user sort the result
            if ($args != null){
                $sql .= " GROUP BY orders.orderID ";
                $sql = sortTenantOrderByDateOrPrice($sql, $args,$startPos,$pageInfo);
                $stmt = $conn->prepare($sql);
            }else{

//                default setting if no filter and sorting
                $sql .= " GROUP BY orders.orderID ORDER BY datetime DESC  LIMIT $startPos,{$pageInfo['orders_In_1Page']}";
                $stmt = $conn->prepare($sql);
            }
        }

    }


    $stmt->execute();
    $rs= $stmt->get_result();

    return $rs;
}


//get total price of orders group by orders belongs to tenants
function getPrice($orderID, $conn){
    $sql = "SELECT orderID, SUM(quantity*sellingPrice) AS price FROM orderitem WHERE orderID = $orderID GROUP BY orderID";
    $rs = mysqli_query($conn, $sql);
    $rc = mysqli_fetch_assoc($rs);

    extract($rc);
    return $price;
}

//get date in orderDateTime
function getOrderDate($orderDateTime){
    $dateTime = explode(" ", $orderDateTime);
    return $dateTime[0];
}

//get time in orderDateTime
function getOrderTime($orderDateTime){
    $dateTime = explode(" ", $orderDateTime);
    return $dateTime[1];
}

//get shop address from shop id
function getAddress($shopID, $conn)
{
    $sql = "SELECT address FROM shop WHERE shopID = '$shopID'";
    $rs = mysqli_query($conn, $sql);
    $rc = mysqli_fetch_assoc($rs);

    extract($rc);
    mysqli_free_result($rs);
    return $address;
}


//get order status
function getStatus($status, $conn)
{
    if($status == 1){
        return "Delivery";
    }else if ($status == 2){
        return "Awaiting";
    }else{
        return "Completed";
    }
}

//get customer name
function getCustName($customerEmail, $conn){
    $sql = "SELECT firstName,lastName FROM customer WHERE customerEmail = '$customerEmail'";
    $rs = mysqli_query($conn, $sql);
    $rc = mysqli_fetch_assoc($rs);

    extract($rc);
    mysqli_free_result($rs);
    return $lastName." ".$firstName;
}


//get total price of single order
function getTotal($orderID){
    include("conn.php");
    $stmt = $conn->prepare("SELECT * FROM orderitem WHERE orderID = ?");
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $set= $stmt->get_result();
    $total = 0;

    while($row = $set->fetch_assoc()){
        extract($row);
        $sellingPrice = getOrderItemSellingPrice($goodsID,$orderID);
        $total += $quantity * $sellingPrice;
    }
    $stmt->close();
    return $total;
}


//get info from particular good
function getProdInfo($goodsID, $flag){
    include("conn.php");
    $stmt = $conn->prepare("SELECT * FROM goods WHERE goodsID = ?");
    $stmt->bind_param("s", $goodsID);
    $stmt->execute();
    $set= $stmt->get_result();

    while($row = $set->fetch_assoc()){
        extract($row);
        switch($flag){
            case "pic":
                $data = $goodsImg;
                break;
            case "name":
                $data = $goodsName;
                break;
            case "price":
                $data = $stockPrice;
                break;
            case "showcase":
                $data = $showcaseID;
                break;
        }
    }
    $stmt->close();
    return $data;
}

//get selling price
function getOrderItemSellingPrice($goodsID,$orderID){
        include("conn.php");
    $stmt = $conn->prepare("SELECT * FROM orderitem WHERE goodsID = ? and orderID = ?");
        $stmt->bind_param("ss", $goodsID, $orderID);
        $stmt->execute();
        $set= $stmt->get_result();
        while($r = $set->fetch_assoc()){
            extract($r);
            return $sellingPrice;
        }
}

//get shop name
function getShopName($shopID){
    include("conn.php");
    $sql = "select * from shop where shopID = $shopID";
    $set = mysqli_query($conn,$sql);
    $rec = mysqli_fetch_assoc($set);
    extract($rec);
    mysqli_free_result($set);
    return $shopName;
}

//concat sql string with order by
function sortTenantOrderByDateOrPrice($sql, $args,$startPos,$pageInfo){
    switch ($args){
        case "n-or-date":
            $sql.= "  ORDER BY orders.orderDateTime desc LIMIT $startPos,{$pageInfo['orders_In_1Page']}";
            break;

        case "o-or-date":
            $sql.= "  ORDER BY orders.orderDateTime asc LIMIT $startPos,{$pageInfo['orders_In_1Page']}";
            break;

        case "l-price":
            $sql.= "  ORDER BY total asc LIMIT $startPos,{$pageInfo['orders_In_1Page']}";
            break;

        case "h-price":
            $sql.= "  ORDER BY total desc LIMIT $startPos,{$pageInfo['orders_In_1Page']}";
            break;
    }
    return $sql;
}

//concat sql string with order status
function getSQL_OrderStatus($where,$sql){
    switch($where){
        case "delivery":
            $sql.=" and orders.status = 1 ";
            break;

        case "awaiting":
            $sql.=" and orders.status = 2 ";
            break;

        case "completed":
            $sql.=" and orders.status = 3 ";
            break;
    }
    return $sql;
}