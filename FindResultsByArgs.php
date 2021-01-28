<?php
session_start();
//this page get result set for pages

if (empty($_GET)) {
    header("refresh:3;url=index.php");
    die("<h2>Direct access not permitted</h2>");
}

include("conn.php");

define('nani',true);

include("showCustOrder.php");
include("functionForTAndC.php");
include("getPastOrderTotal.php");
include("showInventory.php");
include("showTenantOrders.php");
extract($_SESSION);
extract($_GET);

//get the lower limit of record in a new page
$startPos = ($pageInfo['currentPage'] - 1) * $pageInfo['orders_In_1Page'];



//if this is the first page, save all parameter
if($pageInfo['currentPage'] == 1 && $previous == "0"){
//    getFilteredRows() and GetActualResult() is functions from functionForTAndC.php

    //    get the sql without group by and order by
    $sql = getFilteredRows($table,$where,$args,true, $sort);

    //get the actual result from db
    $rs = GetActualResult($table,$where,$args,$startPos,$sql,$sort);
}else{

    extract($prevSql);

    //    get the sql without group by and order by
    $sql = getFilteredRows($ptable,$pwhere,$pargs,true,$psort);

    //get the actual result from db
    $rs = GetActualResult($ptable,$pwhere,$pargs,$startPos,$sql,$psort);
}


//var_dump($_SESSION);
$_SESSION['previous'] = $pageInfo['currentPage'];
//var_dump($_SESSION);

//stop when no result
if(mysqli_num_rows($rs) == 0 || mysqli_num_rows($rs) == false){
    die();
}

//use the results from GetActualResult(), and echo them
switch($table){

//    show all customer orders
    case "orders":
//        function in showCustOrder.php
        show_C_Order($rs);
        break;

//        show particular customer orders
    case "orderitem":
//        function in getPassOrderTotal.php
        showThisOrderDetail($rs);
        break;

//        show all tenant inventory
    case "goods":
//        function in showInventory.php
        showAllInv($rs);
        break;

//        show all tenant orders
    case "orderitem,orders":
//        function in showTenantOrders.php
        showAllTenantOrders($rs);
        break;
}

