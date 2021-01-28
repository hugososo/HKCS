<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- jQuery , then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <!--icon library-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&family=Open+Sans&display=swap" rel="stylesheet">
    <!-- My CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/product.css">
    <title>Hong Kong Cube Shop</title>
</head>

<body>
    <?php
    define('include',true);
    include("nav.php");
    ?>

    <div id="alert"></div>

    <main>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <div class="row" id="top">
                        <div class="col-sm-12">
                            <h5>Filter Product</h5>
                            <hr>
                        </div>
                        <!-- search the database and list the shop for filter -->
                        <div class="col-md-12">
                            <h6 class="text-info">
                                Select Branch</h6>
                            <ul class="list-group">
                                <?php
                                include("conn.php");
                                $sql = "Select * from shop";
                                $rs = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                                while($rc = mysqli_fetch_assoc($rs)) {
                                extract($rc);
                                echo
<<<EOD
                                <li class="list-group-item">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input filter" value="$shopID" id="shop" >$shopName
                                        </label>
                                    </div>
                                </li>
EOD;
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-10 col-md-9" id="top">
                    <h2 class="text-center">Product</h2>
                    <div class="row text-center">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <!-- Search form -->
                                    <input class="form-control mb-3 filter" type="text" placeholder="Search" aria-label="Search" id="search" name="search">
                                </div>
                                <div class="col-md-6 mt-2 mb-2">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input filter" type="radio" name="stock" id="status" value="all">
                                            Show all
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input filter" type="radio" name="stock" id="status" value="1">
                                            Available
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input filter" type="radio" name="stock" id="status" value="2">
                                            Unavailable
                                        </label>
                                    </div>
                                </div>
                                <div class="btn-group col-md-3">
                                    <select class="custom-select browser-default filter" id="price">
                                        <option value="stockPrice ASC">Price - Low to High</option>
                                        <option value="stockPrice DESC">Price - High to Low</option>
                                    </select>
                                </div>
                            </div>
                            <!-- show all the product -->
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4" id="result">
                                <?php
                                $sql = "Select * from goods";
                                $rs = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                                while($rc = mysqli_fetch_assoc($rs)) {
                                    extract($rc);
                                    if($status == 1) {
                                        $statusStr = "Available";
                                        $statusColor = "text-success";
                                        $statusBtn = "";
                                    }
                                    if($status == 2) {
                                        $statusStr = "Unavailable";
                                        $statusColor = "text-danger";
                                        $statusBtn = "disabled";
                                    }
                                    if(strlen($goodsName)>=20){ //if goods name too long, make a short one
                                        $shortGoodsName = substr($goodsName,0,20)."...";
                                    } else
                                        $shortGoodsName = $goodsName;
                                    echo
<<<EOD
                                    <form>
                                        <div class="col mb-4">
                                            <div class="card">
                                                <img src="image/$goodsImg" class="card-img-top" alt="$goodsName" data-toggle="tooltip" title="$goodsName">
                                                <div class="card-body">
                                                    <h5 class="card-title">$shortGoodsName</h5>
                                                    <p class="card-text $statusColor">$statusStr</p>
                                                     <input type="hidden" name="goodsID" value="$goodsID">
                                                    <p class="card-text">$$stockPrice</p>
                                                </div>
                                                <input type="button" class="btn btn-warning buyProduct" value="Add to Cart" $statusBtn>
                                                <div class="input-group mb-3 qty">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Qty</span>
                                                    </div>
                                                    <input type="number" class="form-control" name="buyQty" value="1" min="1" max="$remainingStock">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
EOD;
                                }
                                mysqli_free_result($rs);
                                mysqli_close($conn);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="featurette-divider">
    </main>

    <footer class="container">
        <p class="float-right"><a href="#">Back to top</a></p>
        <p>Work by SE1B,Group Glory, So Ho Tai, Chan Hei · <a href="https://www.vtc.edu.hk/" target="_blank">VTC</a></p>
    </footer>

    <script type="text/javascript">
        $(document).ready(function() {
            
                  $(document).on('click','.buyProduct',function(e) {    //Click add to cart button to trigger the following function
                        
                        var form = $(this).closest('form'); //find the form that user want to submit
                        var data = form.serialize();    //format the form data

                                $.ajax({
                                    url:"addToCart.php",
                                    method:"POST",
                                    data: data,
                                    success: function(response){
                                        var jdata = $.parseJSON(response);  //receive the json format data
                                        console.log(jdata);
                                        $('#alert').html(jdata[0]);
                                        $('#total-Fee').text("Total Price: $"+jdata[1]);
                                        $('#badge-itemQty').text(jdata[2]);
                                        $('#text-itemQty').text(jdata[2]+" items");
                                    }
                                });
                             e.preventDefault();
                            });
                        });
            
                    $('.filter').on('change keyup', function() {    //when the checkbox and textbox change or user type some text, trigger this function
                        var action = 'data';    //set a variable that to do the access control
                        var shop = checked_filter('#shop');
                        var status = checked_filter('#status');
                        var search = search_filter('#search');
                        var price= selected_filter('#price');


                        $.ajax({
                            url:"product_filter.php",
                            method:"POST",
                            data:{action:action,shop:shop,status:status,search:search,price:price},
                            success: function(response){
                                $('#result').html(response);
                            }
                        });
                        return false;
                    });

                    function checked_filter(id) {   //checkbox filter to push the check box value to array
                        var filterData = [];
                        $(id+":checked").each(function() {
                            filterData.push($(this).val());
                        });
                        return filterData;
                    }
            
                    function search_filter(id) {    //search box filter to add the value to string
                        var filterData = $(id).val();
                        return filterData;
                    }
            
                    function selected_filter(id) {
                        var filterData = $(id+" option:selected").val();    //select filter to add the value to string
                        return filterData;
                    }
    </script>
</body>

</html>
