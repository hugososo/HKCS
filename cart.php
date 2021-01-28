<!--cart.php,changeFromCart.php, showCartContent.php,deleteFromCart.php handle the shopping cart page-->

<!DOCTYPE html>
<html lang="en">
<?php
  session_start();
    if(!empty($_SESSION['role']) && $_SESSION['role'] == "T"){
        header("refresh:3;url=index.php");
        die("<h2>Tenant account cannot purchase product</h2>");
    }
  ?>
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
  <link rel="stylesheet" href="css/cart.css">
  <link rel="stylesheet" href="css/checkout.css">
  <link rel="stylesheet" href="css/style.css">
  <title>Shopping Cart</title>
</head>

<body>
  <!-- navbar -->
  <?php
  define('include',true);
  include("nav.php");
  ?>


  <section class="text-body">
    <nav class="breadcrumb">
      <div class="container">
          <div class="row">
              <div class="col-md-3 text-color">
                  Check out Process
              </div>
              <a class="breadcrumb-item active">View Shopping Cart</a>
              <a class="breadcrumb-item active">Pick-up Place Selection</a>
              <a class="breadcrumb-item active">Order Confirmed</a>
          </div>
      </div>
  </nav>

<!--      A reminder on the top if user add product from different shop-->
      <div class="alert alert-danger text-center" role="alert" id="noDuplicate" style="display: none">Goods should be from same shop</div>
    <div class="container">
<!--     heading-->
      <div class="row">
        <div class="col-md-12">
          <h1>Shopping Cart</h1>
        </div>
      </div>
      
<!--      entire main body, include product and fee-->
<div id="content">

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

          // this store the goodsID that being delete or change qty
        var goodsID=0;

        // show all items when page fully ready
        load();

        // delete item when "delete" is clicked
          $(document).on('click', '.deleteBtn', function() {
              goodsID = $(this).data("key");
              $itemQty = $('#badge-itemQty').text();
              $('#badge-itemQty').text($itemQty-1);
              delProdInCart(goodsID);
          })

          // clear all item when "reset" is clicked and refresh item list
          $(document).on('click', '#resetCart', function() {
              goodsID = $(this).data("key");
              $itemQty = $('#badge-itemQty').text();
              $('#badge-itemQty').text(0);
              delProdInCart();
              load();
          })


            // change total price when qty is changed
          $(document).on('change', '.qtySelector', function() {
              // the qty of the item
              var qtyNum = $(this).val();
              goodsID = $(this).data("item");
              if(qtyNum == 0){
                  delProdInCart(goodsID);
              }else{

                  changeTotal(qtyNum);
              }
          })


        // element#noDuplicate show when item is at different shop
          $(document).on('mouseover', function() {
              // element#remind created in showCartContent.php store the flag of whether item is in the same shop
              var remind = $("#remind").val();
              if(remind === "yes"){
                  $("#noDuplicate").css('display', 'block');
              }else{
                  $("#noDuplicate").css('display', 'none');
              }
          })


          // ajax call to reload item list section
          function load(){
              $.ajax({
                  type: "POST",
                  url: "showCartContent.php",
                  data: {flag:"ok"},
                  success:function (response) {
                      $("#content").html(response);
                  }
              });
          }

          // ajax call to delete item
          function delProdInCart(g="all"){
              $.ajax({
                  type: "POST",
                  url: "deleteFromCart.php",
                  data: {deleteProduct:g},
                  success:function () {

                      // remove html element of the item
                      $("#div"+goodsID).remove();
                      $("#"+goodsID).remove();
                      //refresh
                      load();
                  }
              });
          }

          // ajax call to change total when qty changed
          function changeTotal(qtyNum){
              $.ajax({
                  type: "POST",
                  url: "changeFromCart.php",
                  data: {changeProduct:goodsID, newQty:qtyNum},
                  success:function () {
                        load();
                  }
              });
          } 

      })

  </script>

  <script type="text/javascript" src="js/pagination.js">
  </script>

</body>

</html>