<!--order_detail.php, getPastOrderTotal.php show particular customer order detail-->
<?php
session_start();
if(empty($_GET)){
    header("Location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- jQuery , then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
  integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
  </script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
  integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
  </script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
  integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
  </script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&family=Open+Sans&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/Inventory.css">
  <link rel="stylesheet" href="css/order-info.css">
  <link rel="stylesheet" href="css/order_detail.css">

    <link rel="stylesheet" href="css/cust_tent_order.css">
  <link rel="stylesheet" href="css/checkout.css">
    <link rel="stylesheet" href="css/style.css">
    <!--    jquery, ajax-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <title>Order Detail</title>
</head>

<body>
  <!-- navbar -->
  <?php
      define('include',true);
      include("nav.php");
      include("conn.php");
      include("showCustOrder.php");
        define('nani',true);
      include("functionForTAndC.php");

      include("getPastOrderTotal.php");


      //prevent users access order not belongs to them
      extract($_SESSION);
      extract($_GET);
      $sql = "SELECT * FROM orders where customerEmail = '$id' and orderID = '$orderID'";
      $rs = mysqli_query($conn, $sql);
          if (mysqli_num_rows($rs) == 0){
              header("Location:index.php");
          }

      $rc = $rs->fetch_assoc();
      extract($rc);
  ?>

  <section class="text-body">
      <nav class="breadcrumb">
          <div class="container">
              <div class="row">
                  <a href="profile.php" class="breadcrumb-item">User Profile</a>
                  <a href="cust_order.php" class="breadcrumb-item">Order Records</a>
                  <a href="#" class="breadcrumb-item active">Order Details</a>
              </div>
          </div>
      </nav>
  </section>

  <section class="text-body">
    <div class="container">

        <div class="row">
        <div class="col-md-12">
          <h1>Order Detail</h1>
        </div>
      </div>

      <!--        all detail of order-->
      <div class="row" id="order-summary">
        <div class="col-md-8">
          <div class="row">
            <div class="col-md-6">
              <span class="bold-desc">Order ID :</span>
            </div>
            <div class="col-md-6">
              <span class="bold-desc">Order Date Time :</span>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <span id="actual_orderID"><?php echo $orderID;?></span>
            </div>
            <div class="col-md-6">
              <span><?php echo $orderDateTime;?></span>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <span class="bold-desc">Pick up Location :</span>
            </div>
            <div class="col-md-6">
              <span class="bold-desc">Status :</span>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <span><?php echo getAddress($shopID, $conn);?></span>
            </div>
            <div class="col-md-6">
              <span><?php echo getStatus($status, $conn);?></span>
            </div>
          </div>

          <div class="div-divider"></div>

          <div class="row">
            <div class="col-md-6 bold-desc">
              <span>Customer Name:</span>
            </div>
            <div class="col-md-6 bold-desc">
              <span>Customer Email:</span>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-6">
                  <p class="desc-tag-s"><?php echo getCustName($customerEmail, $conn);?></p>
                </div>
                <div class="col-md-6">
                  <p class="desc-tag-s"><?php echo $customerEmail;?></p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4 price-tag">
          <h3>Total Fee</h3>
          <div class="row">
            <div class="col-md-7">Subtotal:</div>
            <div class="col-md-5">HK$<?php echo getTotal($orderID);?></div>
          </div>
          <div class="row">
            <div class="col-md-7">Additional Fee:</div>
            <div class="col-md-5">HK$0.0</div>
          </div>
          <div class="row">
            <div class="col-md-7">Grand Total:</div>
            <div class="col-md-5 bold-desc">HK$<?php echo getTotal($orderID);?></div>
          </div>
        </div>
      </div>

      <div class="div-divider"></div>

        <div id="order-list">
        <div class="row">
            <!--       page section-->
            <div class="col-md-12" id = "pageRow">
            </div>
        </div>
        </div>
      <!--      fill up actual result-->
        <div id="collapseRecord">

        </div>

      <hr class="featurette-divider">
    </div>
  </section>
  <footer class="container">
    <p class="float-right"><a href="#">Back to top</a></p>
    <p>Work by SE1B,Group Glory, So Ho Tai, Chan Hei · <a href="https://www.vtc.edu.hk/" target="_blank">VTC</a></p>
  </footer>

  <!--    jquery, ajax-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


  <script type="text/javascript">
      $(function(){

          var orderIDForJS = $("#actual_orderID").text();
          var table = "orderitem"

          // fill up #collapseRecord when load
          processInfo(1,table,"orderID",orderIDForJS,sortBy);
      })

  </script>

  <script type="text/javascript" src="js/pagination.js">
  </script>

</body></html>