<!--cart.php,changeFromCart.php, showCartContent.php,deleteFromCart.php handle the shopping cart page-->

<?php

if (empty($_POST)) {
    header("refresh:3;url=index.php");
    die("<h2>Direct access not permitted</h2>");
}



session_start();

//array to store shop address of items
$_SESSION['shop'] = array();

//flag to know whether item is in same shop or not
$_SESSION['shop']['notSameShop'] = '';
include("conn.php");
extract($_SESSION);

$totalFee=0;
$totalItems=0;

//counter to store the shop name of the first item
$count=1;


echo<<<lkj
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-10 desc-tag-s text-center hide">Product</div>
                    <div class="col-md-1 text-right desc-tag-s hide">Price</div>
                </div>
lkj;

//    show msg when no item
    if(!isset($cart) || $cart['items']==0){
        echo "<h2 style='text-align: center;padding: 50px;'>No items yet!</h2>";
    }else{

//      show each items
    foreach($cart as $key=>$val){

        $sql = "SELECT * FROM goods where goodsID = '$key'";
        $rs = mysqli_query($conn, $sql);

        while($rc = mysqli_fetch_array($rs)){
            extract($rc);

            echo<<<EOD
        <div class="row filter-row" id="$goodsID">
        <div class="col-md-3">
          <img class="img-fluid" src="image/$goodsImg" alt="">
        </div>
        <div class="col-md-6">
          <h3 class="prd-name">$goodsName</h3>
          <div class="row">
           <div class="col-md-12">
EOD;

//                msg about stocking status
                if($remainingStock == 0){
                    echo"<span class=\"outstock\">Not available yet!</span>";

                    //if the qty that add to cart > remainingStock
                }else if($val > $remainingStock){
                    echo"<span class=\"outstock\">Not enough stock, please choose again!</span>";
                }else if($remainingStock<5){
                    echo"<span class=\"outstock\">Only few left, order soon!</span>";
                }
                else{
                    echo"<span class=\"instock\">In Stock</span>";
                }

echo<<<ers
           </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <select class="form-control qtySelector" data-item="$goodsID">
ers;

//                if remainingStock is enough
                    if($val <= $remainingStock){
                        echo "<option value=\"0\">0-Delete</option>";

                        for($i = 1; $i <= $remainingStock; $i ++){
                            if($i == $val){
                                $selected = "selected";
                            }else{
                                $selected = "";
                            }
                            echo "<option value=\"$i\" $selected>$i</option>";
                        }

                    }else{
//                      if not enough stock
                        echo "<option value=\"\" selected disabled></option>";
                    }

                    echo<<<qwe
</select>
            </div>
            <div class="col-md-6">
              <button type="button" class="btn btn-outline-dark deleteBtn" data-key="$goodsID">Delete</button>
            </div>
          </div>
        </div>
qwe;

            //calculate item total if qty is enought
            if($val <= $remainingStock){
                $itemSum = $val*$stockPrice;
            }else{
                $itemSum = 0;
            }

        echo "<div class=\"col-md-2 text-right bold-desc\">HK$$itemSum";

echo<<<ihb
           <br><br>
                <span class="desc-tag-s">Unit Price:</span>
                <span class="bold-desc">$stockPrice</span><br>
                <span class="desc-tag-s">Shop Name:</span><br>
ihb;

//            get shop name by showcaseid
            $shop =  getShopName($showcaseID);
            if($count == 1){
//              store the shop name of the first item
                $_SESSION['shop']['first'] = $shop;

//                set the flag to no at start
                $_SESSION['shop']['notSameShop'] = "no";
            }else{

//              if the shop name of other item != the shop name of the first item
                if($_SESSION['shop']['first'] != $shop){

//                  set the flag to yes
                    $_SESSION['shop']['notSameShop'] = "yes";
                }
            }
            $count++;

echo<<<yhn
                <span class="bold-desc-for-shop">$shop</span>
        </div>
      </div>
            <div class="div-divider" id="div$goodsID"></div>
yhn;

//            calculate the grand total in cart
            if($val <= $remainingStock){
                $totalFee+=$val*$stockPrice;
                $totalItems+=$val;
            }
    }
  }
}




echo<<<mnb
<div class="row">
        <div class="col-md-12">
          <span>Subtotal ($totalItems items) : </span>
          <span class="bold-desc">HK$$totalFee</span>
        </div>
      </div>
    </div>

    <div class="col-md-4 price-tag">
      <h3>Total Fee</h3>
      <div class="row">
        <div class="col-md-7">Subtotal:</div>
        <div class="col-md-5">HK$$totalFee</div>
      </div>
      <div class="row">
        <div class="col-md-7">Additional Fee:</div>
        <div class="col-md-5">HK$0.0</div>
      </div>
      <div class="row">
        <div class="col-md-7">Grand Total:</div>
        <div class="col-md-5 bold-desc">HK$$totalFee</div>
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
mnb;

//if all item is in same shop and not empty, show checkout button
    if($_SESSION['shop']['notSameShop'] == "no"){
    echo  "<a href=\"checkout.php\" type=\"button\" class=\"btn btn-outline-info bt-cart\" id=\"checkOut\">Check out</a>";
}

echo<<<peu
        </div>
      </div>
       <div class="row">
        <div class="col-md-12 text-center">
          <button type="button" class="btn btn-outline-info bt-cart" id="resetCart">Reset Cart</button>
        </div>
      </div>
    </div>
  </div>
peu;

//    if items not in same shop
    if($_SESSION['shop']['notSameShop'] == "yes"){

//      set hidden element value to yes, if yes, show element#noDuplicate at cart.php
        echo "<input type=\"hidden\" id=\"remind\" value='yes'>";
    }else{
        echo "<input type=\"hidden\" id=\"remind\" value='no'>";
    }


//    function to get shop name by showcaseid
    function getShopName($showcaseID){
        include("conn.php");
        $sql = "SELECT * FROM goods,showcase,shop 
                    where goods.showcaseID = showcase.showcaseID and 
                    showcase.shopID = shop.shopID and goods.showcaseID = $showcaseID";
        $rs = mysqli_query($conn,$sql);
        if($rc = mysqli_fetch_assoc($rs)){
            extract($rc);
        }
        return $shopName;
    }
