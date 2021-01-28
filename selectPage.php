<?php
session_start();
//this page decide how many page buttons to create

if (empty($_GET)) {
    header("refresh:3;url=index.php");
    die("<h2>Direct access not permitted</h2>");
}


include("conn.php");
define('nani',true);
include("functionForTAndC.php");

extract($_GET);

//use to check if user want previous result set or new result set
if($isNew === "true"){
    $_SESSION['previous'] = "0";
}

extract($_SESSION);
//var_dump($_SESSION);

$stmt = "";


//save all parameter when in page 1
if($currentPage == 1 &&  $previous == "0"){
    $_SESSION['prevSql']['ptable'] = $table;
    $_SESSION['prevSql']['pwhere'] = $where;
    $_SESSION['prevSql']['pargs'] = $args;
    $_SESSION['prevSql']['pgetActualResult'] = false;
    $_SESSION['prevSql']['psort'] = $sort;

    //    get number of rows to decide how many page buttons to create
    $rs = getFilteredRows($table,$where,$args,false,$sort);
} else{

    extract($prevSql);
        //    get number of rows to decide how many page buttons to create
    $rs = getFilteredRows($ptable,$pwhere,$pargs,false,$psort);
}



$pageInfo['currentPage'] = $currentPage;

//records show in a page
$pageInfo['orders_In_1Page'] = 7;

//total rows
$pageInfo['numOfOrders'] = $rs->num_rows;

//number of pages
$pageInfo['numOfPages'] = ceil($pageInfo['numOfOrders'] / $pageInfo['orders_In_1Page']);

//disable "previous" button if in page 1
if ($currentPage != 1)
    $previous = "";
else
    $previous = "disabled";

echo <<<eod
    
    <nav>
     <ul class="pagination  justify-content-end pagination-sm">
    <li class="page-item $previous"><a class="page-link" id="prev">Previous</a></li>
    <li class="page-item"><select class = "page-link" id = 'pageSelector'>
eod;

//set the value of select element by current page number
for($i = 1; $i <= $pageInfo['numOfPages']; $i++){

    if($i == $currentPage){
        $selected = "selected";
    }else{
        $selected = "";
    }

    echo"<option value=\"$i\" class=\"page-item\" $selected>$i</option>";
}
echo " </select></li >";

//disable "next" button if in last page
if ($pageInfo['numOfPages'] > 1 && $currentPage < $pageInfo['numOfPages'])
    $next = "";
else
    $next = "disabled";

echo<<<eod2
                    <li class="page-item $next">
                    <a class="page-link" id="next">Next</a>
                    </li>
            </ul>
              </nav>
    

eod2;

//save pageInfo[] into session
$_SESSION['pageInfo'] = $pageInfo;
mysqli_free_result($rs);












