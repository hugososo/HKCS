<!--This page show all customer orders-->
<?php
session_start();
//  cust_order.php and showCustOrder.php show all content of customer order.
//prevent access when user is not customer
if(empty($_SESSION['role'])){
    header("refresh:3;url=index.php");
    die("<h2>Please login first</h2>");
}else if($_SESSION['role'] != "C"){
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/Inventory.css">
    <link rel="stylesheet" href="css/cust_tent_order.css">
    <link rel="stylesheet" href="css/checkout.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Order records</title>
</head>



<body>


<?php
define('include',true);
  define('nani',true);
include("nav.php");
include("conn.php");
?>

<! --nav-->
<section class="text-body">
    <nav class="breadcrumb">
        <div class="container">
            <div class="row">
                <a href="profile.php" class="breadcrumb-item">User Profile</a>
                <a href="#" class="breadcrumb-item active">Order Records</a>
            </div>
        </div>
    </nav>
</section>

<section class="text-body">
    <div class="container" id="order-list">
        <!--heading-->
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>Order Records</h1>
            </div>
        </div>


        <!--      sorting row-->
        <div class="row text-right filter-row">
            <div class="col-md-5">
                <button type="button" class="btn btn-outline-dark" id="sort_old">Sort by oldest</button>
            </div>
            <div class="col-md-5">
                <button type="button" class="btn btn-outline-dark" id="sort_new">Sort by newest</button>
            </div>
        </div>

        <!--     searching and page button-->
        <div class="row">

            <!--      search section-->
            <div class="col-md-8">
                <label for="">Search</label>
                <div class="input-group mb-3">
                    <select name="" id="search_Opts" width="200px">
                        <option value="searchByOid">Order ID</option>
                        <option value="searchByDate">Date</option>
                    </select>
                    <input type="text" class="form-control" id="keyword">
                    <div class="input-group-append">
                        <button class="form-contorl btn btn-info" id = "filter">Search</button>
                    </div>
                </div>
            </div>

            <!--       page section-->
            <div class="col-md-12" id = "pageRow">
            </div>

        </div>


        <!--      collapsable row for order records-->
 <!--      function in showCustOrder.php show the actual content-->

        <div id="accordion">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" id = "collapseRecord">
                    </div>
                </div>
            </div>
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
<script type="text/javascript" src="js/pagination.js">
</script>

<script type="text/javascript">
    $(function(){

//set parameter for processInfo
        var table = "orders";
        var sortBy = "DESC";


        //ajax function from paginaton.js show all result onload,
        processInfo(1,table,"customerEmail","",sortBy);

        //sort result by new
        $('#sort_new').on('click',function () {
            sortBy = "DESC";
            processInfo(1,table,"customerEmail","",sortBy);
        })

        //sort result by old
        $('#sort_old').on('click',function () {
            sortBy = "ASC";
            processInfo(1,table,"customerEmail","",sortBy);
        })


//search customer record by orderID and Date
        function filterRecords(){
//hide the previous result
            $('.card > *').hide();

//get text from search box
            var $key = $('#keyword').val();

//if search by orderid
            if($('#search_Opts').val() == "searchByOid"){
                where = "orderID";

//ajax call to refresh result
                processInfo(1,table,where,$key,sortBy);
            }else{
//if search by date
                where = "orderDateTime";
                processInfo(1,table,where,$key,sortBy);
            }
        }

//start search when click filter button
        $("#filter").on('click',function(){
            filterRecords();
        })

//start search when press enter in search box

        $("#keyword").keypress(function(e){
            if(e.which === 13){
                filterRecords();
            }
        })

//show previous page when prev button click

        $(document).on('click', '#prev',function() {
            where = "customerEmail";
            prev(table,where);
        })

//show next page

        $(document).on('click', '#next',function() {
            where = "customerEmail";
            next(table,where);
        })

//change page from select element
        $(document).on('change', '#pageSelector', function() {
            where = "customerEmail";
            changePage(table,where);
        })
    })

</script>


</body>

</html>
