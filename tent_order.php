<!--show all order from tenant POV -->
<!--salesRecords.php,showTenantOrders.php, delTenantOrder.php, tent_order.php show all tenant order info-->

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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
    integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&family=Open+Sans&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/Inventory.css">
  <link rel="stylesheet" href="css/cust_tent_order.css">
    <link rel="stylesheet" href="css/checkout.css">
  <link rel="stylesheet" href="css/style.css">

  <title>Order records</title>

</head>

<body>
  <!-- navbar-->
  <?php
  define('include',true);
  define('nani',true);
  include("nav.php");
  include("conn.php");

    //use to check if user want previous result set or new result set
    $_SESSION['previous'] = "0";
  ?>

<!--  show current position-->
  <section>
      <div class="row">
          <div class="col-md-12">
              <nav>
                  <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="profile.php">User Profile</a></li>
                      <li class="breadcrumb-item active" aria-current="page">Order Records</li>
                  </ol>
              </nav>
          </div>
      </div>
  </section>



  <section class="text-body">
    <div class="container" id="order-list">

        <!--heading-->
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>Order Records</h1>
            </div>
        </div>

      <!--      filter for orderDate and order Status-->
      <div class="row filter-row">

        <div class="col-md-12 text-center">
          <div class="row  filter-row">
            <div class="col-md-4">
              <p class="bold-desc">From</p> <input type="date" id="fromWhen">
            </div>
            <div class="col-md-4">
              <p class="bold-desc">To</p><input type="date"  id="toWhen">
            </div>
            <div class="col-md-4">
              <span class="bold-desc">Status</span>

              <select class="form-control" id="status-filter">
                <option value="" selected></option>
                <option value="delivery" >Delivery</option>
                <option value="awaiting">Awaiting</option>
                <option value="completed">Completed</option>
              </select>

            </div>
          </div>

          <div class="row  filter-row">
            <div class="col-md-6">
              <button type="button" class="btn btn-outline-dark" id="filterDate_status">Filter</button>
            </div>
              <div class="col-md-6">
              <button type="button" class="btn btn-outline-dark" id="resetDate_status">Reset All Result</button>
            </div>
          </div>
        </div>
      </div>

      <!--sorting by order date and price-->
      <div class="row">
        <div class="col-md-6">
          <select class="form-control" id="t-order-sorter">
            <option value="n-or-date">Sort by Newest Order Date</option>
            <option value="o-or-date">Sort by Oldest Order Date</option>
            <option value="l-price">Sort by Lowest Price</option>
            <option value="h-price">Sort by Highest Price</option>
          </select>
        </div>


        <!--       page button-->
        <div class="col-md-6">
            <div id="order-list">
                <div class="row">
                    <!--       page section-->
                    <div class="col-md-12" id = "pageRow">

                    </div>
                </div>
            </div>
        </div>
      </div>


      <!--     table-->
      <div class="row">
        <table class="table table-hover table-sm text-center">
          <thead>
            <th>Order #</th>
            <th>Date</th>
            <th>Total</th>
            <th>Status</th>
            <th>Operation</th>
          </thead>

<!--            actual content support by ajax function processInfo()-->
          <tbody id="collapseRecord">

          </tbody>
        </table>
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

          // variable for processInfo
          var table="orderitem,orders";

          // var for filter choices
          var from = "";
          var to ="";
          var statusChoice =" ";

          // var for sorting choices
          var sortChoice = "";

          // show orders when loaded
          processInfo(1,table," ","",sortBy);

          // view previous page
          $(document).on('click', '#prev',function() {
              var pageNum = $("#pageSelector option").filter(':selected').val();
              pageNum = parseInt(pageNum);
              processInfo(pageNum-1,table," ","",sortBy);
          })

          // view next page
          $(document).on('click', '#next',function() {
              var pageNum = $("#pageSelector option").filter(':selected').val();
              pageNum = parseInt(pageNum);
              processInfo(pageNum+1,table," ","",sortBy);
          })


          // view selected page using changePage() from pagination.js
          $(document).on('change', '#pageSelector', function() {

              var pageNum = $("#pageSelector option").filter(':selected').val();
              processInfo(pageNum,table," ","",sortBy);

          })

          // get the filtered start range of order date
          $(document).on('change', '#fromWhen', function() {
              from = $("#fromWhen").val();
          })

          // get the filtered end range of order date
          $(document).on('change', '#toWhen', function() {
              to = $("#toWhen").val();
          })

          // get the filtered  order status
          $(document).on('change', '#status-filter', function() {
              statusChoice = "";
              statusChoice = $("#status-filter").val();
          })

            // filter order when "filter" button clicked
          $(document).on('click', '#filterDate_status', function() {
              filterRecords(" ",sortBy);
          })

          // reset back to normal when "reset all result" clicked
          $("#resetDate_status").on('click', function () {
              from = to = statusChoice = "";
              $("#fromWhen").val("");
              $("#toWhen").val("");
              $("#status-filter").val("");
              location.reload();
          })

          // delete order in delivery and with only 1 item belong to tenant
          $(document).on('click', '.btn_del', function(){
              var del = confirm("Delete this order will mean cancel this order.\nAre you sure?");
              if(del){
                    var del_id = $('.btn_del').data('key');
                      $.ajax({
                          type: "POST",
                          url: "delTenantOrder.php",
                          data: { del_id:del_id},
                          success: function(){
                              processInfo(1,table,"","",sortBy);
                          }
                      });
              }
          })



          $(document).on('change', '#t-order-sorter', function() {
              sortChoice = $("#t-order-sorter").val();
              switch(sortChoice){
                  case "n-or-date":
                      args = "n-or-date";
                      sortBy = "DESC";
                      filterRecords(args,sortBy);
                      break;

                  case "o-or-date":
                      args = "o-or-date";
                      sortBy = "ASC";
                      filterRecords(args,sortBy);
                      break;

                  case "l-price":
                      args = "l-price";
                      sortBy = "ASC";
                      filterRecords(args,sortBy);
                      break;

                  case "h-price":
                      args = "h-price";
                      sortBy = "DESC";
                      filterRecords(args,sortBy);
                      break;
              }

          })

          function filterRecords(argvs,sorting){

              if(from  === "" || to === ""){
                  processInfo(1,table,statusChoice,argvs,sorting);
              }
              else{

                  var fullCondition = from + " " + to + " " + statusChoice;
                  processInfo(1,table,fullCondition,argvs,sorting);
              }
          }

      })

  </script>

  <script type="text/javascript" src="js/pagination.js">
  </script>
</body>

</html>