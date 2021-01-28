<!-- inventory.php, editInventory.php, addProd.php, showInventory.php manage inventory function-->
<?php

if (!defined('nani')) {
    header("refresh:3;url=index.php");
    die("<h2>Direct access not permitted</h2>");
}

//show actual inventory in inventory.php inside #collapseRecord
function showAllInv($rs)
{

    while($rc = mysqli_fetch_assoc($rs)) {
        extract($rc);
        $shopName = getShopName($shopID);

        echo<<<eod
<form action="editInventory.php" method="post">
<div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-2">
              <img class="img-fluid" src="image/$goodsImg" alt="">
            </div>
            <div class="col-md-6 text-left">
              <h3>$goodsName</h3>
              <div class="row">
                <div class="col-md-4">
                  <span class="desc-tag-s">Location: </span>
                  <p class="bold-desc">$shopName</p>
                </div>

                <div class="col-md-4">
                  <span class="desc-tag-s">Showcase ID: </span>
                  <p class="bold-desc" id="caseID">$showcaseID</p>
                </div>

                <div class="col-md-4">
                  <span class="desc-tag-s">Availability:</span>
                  <select class="pro-avail" name="status">
eod;

            //select suitable option according to the status
                    if($status == 1){
                        $ava = "selected";
                        $una = "";
                    }else{
                        $ava = "";
                        $una = "selected";
                    }

                    echo<<<qwe
                    <option value="1" $ava>Available</option>
                    <option value="2" $una>Unavailable</option>
                  </select>
                </div>

              </div>
              <div class="input-group mb-3">
                <div class="col-md-5">
                  <p class="desc-tag-s">
Item Price:
                  </p>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">HK$</span>
                    </div>
                    <input type="number" min="1" step="0.1" class="form-control" value="$stockPrice" name="stockPrice">
                  </div>
                </div>
                <div class="col-md-4">
                  <p class="desc-tag-s">
In-Stock
                  </p>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Qty</span>
                    </div>
                    <input type="number" min="0" class="form-control" value="$remainingStock" name="remainingStock">
                  </div>
                </div>

                <div class="col-md-3">
                  <p class="desc-tag-s">
Goods ID
                  </p>
                  <input type="text" readonly class="form-control-plaintext" value="$goodsID">
                </div>
              </div>

            </div>
            <div class="col-md-3 text-right">
              <p class="desc-tag-s">
Items Total
</p>
              <p class="bold-desc">
qwe;
//show  total  price of the goods
echo $remainingStock * $stockPrice;

echo<<<gfd
</p>
              

gfd;
//        <button type="button" class="btn btn-danger btnDel" data-item="$goodsID">Delete Item</button>
//              <br>

echo<<<okn
                 
                <input type="hidden" name="goodsID" value="$goodsID">
                <input type="hidden" name="showcaseID" value="$showcaseID">
                
              <button type="submit" class="btn btn-danger btnEdit" data-key="$goodsID">Edit Saved</button>
            </div>
          </div>
        </div>
      </div></form>
okn;
    }
    mysqli_free_result($rs);
}


