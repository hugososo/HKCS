<?php
//  cust_order.php and showCustOrder.php show all content of customer order.
//if (empty($_POST)) {
//    header("refresh:3;url=index.php");
//    die("<h2>Direct access not permitted</h2>");
//}


function show_C_Order($rs){

    include("conn.php");
//    include("functionForTAndC.php");

    while($rc = $rs->fetch_assoc()) {
        extract($rc);

//function in FunctionforCAndT.php get the required data
        $date = getOrderDate($orderDateTime);
        $time = getOrderTime($orderDateTime);
        $price = getPrice($orderID,$conn);
        $address = getAddress($shopID,$conn);
        $status = getStatus($status,$conn);


        echo<<<eod
        <div class="card-header $date;" id="heading$orderID;">
                <h5 class="mb-0">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapse$orderID" aria-expanded="true" aria-controls="collapse$orderID">
                                <div class="row">
                                    <div class="col-md-3">
            <span class="btn">
              Order ID :
              <span class="bold-desc">$orderID</span>
        </span>
        </div>
        <div class="col-md-3">
            <span class="btn">
              Date:
              <span class="bold-desc">$date</span>
            </span>
        </div>
        <div class="col-md-3">
            <span class="btn">
              Price: HK$
              <span class="bold-desc">$price</span>
            </span>
        </div>
        <div class="col-md-3">
eod;

//redirect to order_detail.php  to show more detail
        echo<<<RTy
       <div class="btn btn-outline-info"><a href="order_detail.php?orderID=$orderID" class="toggleA">Detail</a></div>
        </div>
        </div>
        </button>
        </div>
        </div>
        </h5>
        </div>
        
        
        <div id="collapse$orderID" class="collapse $date" aria-labelledby="heading$orderID" data-parent="#accordion">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <p class="desc-tag-s">Pick Up Cube Shop Address:</p>
                        <p class="bold-desc">$address</p>
                    </div>
                    <div class="col-md-2">
                        <p class="desc-tag-s">Order Time:</p>
                        <p class="bold-desc">$time</p>
                    </div>
                    <div class="col-md-2">
                        <p class="desc-tag-s">Status:</p>
                        <p class="bold-desc">$status</p>
                    </div>
                </div>
            </div>
        </div>
RTy;
    }

    mysqli_free_result($rs);
}



