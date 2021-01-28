<!-- inventory.php, editInventory.php, addProd.php, showInventory.php manage inventory function-->

<?php
session_start();

if(empty($_SESSION['role'])){
    header("refresh:3;url=index.php");
    die("<h2>Please login first</h2>");
}else if($_SESSION['role'] != "T"){
    header("refresh:3;url=index.php");
    die("<h2>Please login first</h2>");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>HKCS - Edit Inventory</title>
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
    <link rel="stylesheet" href="css/checkout.css">
    <link rel="stylesheet" href="css/style.css">

</head>

<body class="text-body">
  <!-- navbar  -->
  <?php
  define('include',true);
  include("nav.php");
  include("conn.php");
  extract($_SESSION);
  ?>

  <section>
      <nav class="breadcrumb">
          <div class="container">
              <div class="row">
                  <a class="breadcrumb-item" href="profile.php">User Profile</a>
                  <a class="breadcrumb-item active">Inventory Management</a>
              </div>
          </div>
      </nav>
  </section>

  <section>
    <!--  title-->
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <h1>Inventory</h1>
        </div>
      </div>
    </div>
  </section>

  <!--add product form-->
  <section id="addInvent">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2>Add Products</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">

          <form action="addProd.php" method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Product Name:</label>
                  <input type="text" class="form-control" name="proName" placeholder="The name of your product" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Price:</label>
                  <input type="number" class="form-control" min="1" name="proPrice" placeholder="The unit price of your product" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Stock Quantity:</label>
                  <input type="number" class="form-control" min="1"  name="proQty" placeholder="The number of items needed to stock-in" required></div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">In which showcase?</label>
                  <select class="form-control" id="location" required name="showcase">
                      <?php

//show all showcase from tenant
                        $sql ="SELECT showcaseID FROM showcase where tenantID = '$id'";
                        $rs = mysqli_query($conn,$sql);
                        while($rc = mysqli_fetch_assoc($rs)){
                            extract($rc);
                            echo "<option value=\"$showcaseID\">$showcaseID</option>";
                        }
                      ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="ProductPic">Upload the Product Image</label>
                  <input type="file" class="form-control-file" id="proPic" name="proPic" required>
                </div>
              </div>
              <div class="col-md-6 text-right">
                <div class="form-group">
                  <button class="form-contorl btn btn-info" type="submit" value="submit">Submit!</button></div>
              </div>
            </div>
          </form>

        </div>
      </div>
    </div>
  </section>

  <!--edit product section-->
  <section id="editInvent">
    <div class="container">

      <!--     heading-->
      <div class="row div-divider">
        <div class="col-md-12">
          <h2>Edit Products</h2>
        </div>
      </div>

      <!--Sorting or filter-->
      <div class="row filter-row">

        <div class="col-md-5">
          <div class="form-group">
            <label for="">Sorting by</label>
            <select class="form-control" id="sort">
              <option value="h-shownum">Highest Showcase number</option>
              <option value="l-shownum">Lowest Showcase number</option>
              <option value="h-price">Highest Price</option>
              <option value="l-price">Lowest Price</option>
            </select>
          </div>
        </div>

        <div class="col-md-6 offset-md-1">
          <label for="">Search</label>
          <div class="input-group mb-3">
            <select name="" id="SearchFilter">
              <option value="prodName">Product Name</option>
              <option value="caseID">Showcase ID</option>
              <option value="goodID">Goods ID</option>
                <option value="avaProd">Available Product</option>
                <option value="allProd">All Product</option>
            </select>
            <input type="text" class="form-control" id="keyword">
            <div class="input-group-append">
              <button class="form-contorl btn btn-info" id="btnSearch">Search</button>
            </div>
          </div>
        </div>

      </div>

        <div class="row">
            <div id="order-list" class="col-md-12">
                <div class="row">
                    <!--       page section-->
                    <div class="col-md-12" id = "pageRow">
                    </div>
                </div>
            </div>
        </div>

 <!--      show all product in the case-->
 <!--      collapsable row for order records-->
        <div class="row">
            <div id="collapseRecord">
            </div>
        </div>



    </div>
    <hr class="featurette-divider">
  </section>

  <footer class="container">
    <p class="float-right"><a href="#">Back to top</a></p>
    <p>Work by SE1B,Group Glory, So Ho Tai, Chan Hei · <a href="https://www.vtc.edu.hk/" target="_blank">VTC</a></p>
  </footer>
  <!--    jquery, ajax-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


  <script type="text/javascript">
      $(function(){

// initialize variable for ajax function processInfo from paination.js
          var table = "goods";
          var where ="";
          var goodsID = "";
          // show all goods when loaded
          processInfo(1,table, "","","");

//show previous page when click
          $(document).on('click', '#prev',function() {
              where = "goods";
              prev(table,where);
          })

//show next page when click
          $(document).on('click', '#next',function() {
              where = "goods";
              next(table,where);
          })

//show selected page
          $(document).on('change', '#pageSelector', function() {
              where = "goods";
              changePage(table,where);
          })

//search inventory record by keyword when click
          $("#btnSearch").on('click',function(){
              filterRecords();
          })

//search inventory record by keyword when press enter
          $("#keyword").keypress(function(e){
              if(e.which === 13){
                  filterRecords();
              }
          })

//select search type
          $(document).on('change', '#SearchFilter', function() {
              var searchType = $('#SearchFilter').val();

              switch (searchType) {

//disable search box when select all product and available product
                  case "allProd":
                  case "avaProd":
                  $('#keyword').prop("disabled",true);
                      break;

//eable search box when select other option
                  case "caseID":
                  case "goodID":
                  case "prodName":
                      $('#keyword').prop("disabled",false);
                      break;
              }
          })

//get keyword and search type, and reload page by calling ajax function, processInfo
          function filterRecords(){
              var $key = $('#keyword').val();

              var searchType = $('#SearchFilter').val();

              switch (searchType) {
                    case "allProd":
                        processInfo(1,table, "","","");
                        break;
                    case "avaProd":
                        where = "status";
                        processInfo(1,table,where,$key,sortBy);
                        break;
                    case "caseID":
                        where = "showcase";
                        processInfo(1,table,where,$key,sortBy);
                        break;
                    case "goodID":
                        where = "goodsID";
                        processInfo(1,table,where,$key,sortBy);
                        break;
                    case "prodName":
                        where = "prodName";
                        processInfo(1,table,where,$key,sortBy);
                        break;
              }
          }

//select sorting choice and call processInfo from paination.js
          $(document).on('change', '#sort', function() {
                var sortChoice = $("#sort").val();
                switch(sortChoice){

                    //highest case ID
                    case "h-shownum":
                        where = "showcaseOrder";
                        sortBy = "DESC";
                        processInfo(1,table,where,"",sortBy);
                        break;

                        //lowest case ID
                    case "l-shownum":
                        where = "showcaseOrder";
                        sortBy = "ASC";
                        processInfo(1,table,where,"",sortBy);
                        break;

                        //highest price
                    case "h-price":
                        where = "price";
                        sortBy = "DESC";
                        processInfo(1,table,where,"",sortBy);
                        break;


                    //lowest price
                    case "l-price":
                        where = "price";
                        sortBy = "ASC";
                        processInfo(1,table,where,"",sortBy);
                        break;
                }
          })

          // $(document).on('click','.btnDel',function(e) {
          //     goodsID = $(this).data("item");
          //     if(confirm("Delete Goods ID = "+goodsID+ " ?")){
          //         $.ajax({
          //             type: "POST",
          //             url: "deleteInventory.php",
          //             data: {deleteProduct:goodsID},
          //             success:function () {
          //                 processInfo(1,table, "status","","");
          //             }
          //         })
          //     }
          // });
      })

  </script>

  <script type="text/javascript" src="js/pagination.js">
  </script>
</body></html>
