<?php
//this page show orderitem in particular customer order


if (!defined('nani')) {
    header("refresh:3;url=index.php");
    die("<h2>Direct access not permitted</h2>");
}


function showThisOrderDetail($rs)
{
    include("conn.php");
    while($rc = $rs->fetch_assoc()) {
        extract($rc);

//        get required data from functions in functionForTAndC.php
        $pic = getProdInfo($goodsID,"pic");
        $prodName = getProdInfo($goodsID,"name");
        $unitPrice = getOrderItemSellingPrice($goodsID,$orderID);
        $showcaseID = getProdInfo($goodsID,"showcase");
        $total = $quantity * $unitPrice;

        echo<<<eod
    <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-2">
              <img class="img-fluid" src = "image/$pic" alt = "">
            </div>
            <div class="col-md-7 text-left">
              <h3>$prodName</h3>

              <div class="input-group mb-3">
                <div class="col-md-4">
                  <p class="desc-tag-s">
Item Price:
                  </p>
                  <p class="bold-desc"> HK$$unitPrice </p>
                </div>
                <div class="col-md-4">
                  <p class="desc-tag-s">
Qty
                  </p>
                  <p class="bold-desc">$quantity</p>
                </div>
                <div class="col-md-4">
                  <p class="desc-tag-s">
Goods ID
                  </p>
                  <p class="bold-desc">$goodsID</p>
                </div>
              </div>
            </div>

            <div class="col-md-3 text-right">
              <span class="desc-tag-s"> Showcase Num: </span>
              <p class="bold-desc" >$showcaseID</p>

              <p class="desc-tag-s">
Items Total:
              </p>
              <p class="bold-desc">
HK$$total
</p >
            </div>
          </div>
        </div>
      </div>
eod;
    }
//    mysqli_free_result($rs);
}

