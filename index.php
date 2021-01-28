<!--
Project: ITP4513 Group Project (2019-20)
Class: SE1B
Group: Glory
Student: So Ho Tai, Chan Hei
-->
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
    <link rel="stylesheet" href="css/index.css">
    <title>Hong Kong Cube Shop</title>
</head>

<body>
    <?php
        //define the include that make nav.php can be access and include
        define('include',true);
        //import the navigation menu
        include("nav.php");
        ?>

    <!-- Banner -->
    <section id="intro">
        <div class="jumbotron">
<!--            <video preload autoplay loop muted>-->
<!--                <source src="video/banner.mp4" type="video/mp4">-->
<!--            </video>-->
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1>Hong Kong Cube Shop</h1>
                        <p class="lead">The Unparalleled Cube Shop In Hong Kong</p>
                        <a class="btn orangebtn" href="register.php">Register!</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- the Best Sale Products section,  just show the first two products-->
    <main>
        <div class="container">
            <div class="container">
                <div class="row">
                    <div class="col-md">
                        <h2>
                            Best Sale Products
                        </h2>
                    </div>
                    <div class="float-right">
                        <a href="product.php" class="btn btn-info">See More!</a>
                    </div>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2">
                <?php
                include("conn.php");
                $sql = "Select * from goods";
                $rs = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                for($i=0;$i<2;$i++) {
                    $rc = mysqli_fetch_assoc($rs);
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
                    echo
<<<EOD
                <form class="buyProduct">
                    <div class="col mb-4">
                        <div class="card text-white bg-dark mb-3">
                            <img class="card-img-top" src="image/$goodsImg" alt="Card image cap">
                            <div class="card-body">
                                <h3 class="card-title">$goodsName</h3>
                                <p class="card-text $statusColor">$statusStr</p>
                                <h5 class="card-text">$$stockPrice</h5>
                                <input type="hidden" name="goodsID" value="$goodsID">
                                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="submit" class="btn btn-success orangebtn pr-3 pl-3 mb-2 cartBorder" value="Add to Cart">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group mb-3 qty">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Qty</span>
                                            </div>
                                            <input type="number" class="form-control" name="buyQty" value="1" min="1" max="$remainingStock">
                                        </div>
                                    </div>
                                </div>
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
            <div id="alert"></div>
            <hr class="featurette-divider">
        </div>
    </main>

    <footer class="container">
        <p class="float-right"><a href="#">Back to top</a></p>
        <p>Work by SE1B,Group Glory, So Ho Tai, Chan Hei · <a href="https://www.vtc.edu.hk/" target="_blank">VTC</a></p>
    </footer>
    
    
    <!-- ajax for update the cart number in the nav menu -->
    <script type="text/javascript">
        $(document).ready(function() {
                //when the form submit, will trigger the following funciton
                $('.buyProduct').on('submit', function(e) {
                    
                        $.ajax({
                            url:"addToCart.php",    //send data to the addToCart.php
                            method:"POST",          //method is POST
                            data: $(this).serialize(),  //format the form data to get the form data and send it
                            success: function(response){
                                var jdata = jQuery.parseJSON(response);
                                $('#alert').html(jdata[0]);
                                $('#total-Fee').text("Total Price: $"+jdata[1]);
                                $('#badge-itemQty').text(jdata[2]);
                                $('#text-itemQty').text(jdata[2]+" items");
                            }
                        });
                     e.preventDefault();
                    });
            });
    </script>
    
</body>
</html>
