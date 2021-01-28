<!DOCTYPE html>

<html lang="en">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

<?php
  session_start();
    $_SESSION['shop'] = array();    //define a array
    include("conn.php");
    extract($_SESSION);
    $count = 1;
    
    if (empty($cart)) { //access control
        header("refresh:1;url=index.php");
        die("<h2>Please put item to shopping cart first</h2>");
    }
    
    foreach($cart as $key=>$val){   //check the goods is in the same shop
        $sql = "SELECT * FROM goods where goodsID = '$key'";
        $rs = mysqli_query($conn, $sql);

        while($rc = mysqli_fetch_array($rs)){
            extract($rc);
            
        $shop =  getShopName($showcaseID);
        if($count == 1){
            $_SESSION['shop']['first'] = $shop;
            $_SESSION['shop']['notSameShop'] = "no";
        }else{
            if($_SESSION['shop']['first'] != $shop){
                $_SESSION['shop']['notSameShop'] = "yes";
            }
        }
        $count++;
        }
    }
    
    if($_SESSION['shop']['notSameShop'] == "yes"){  //if the goods is from the differt shop
        header("refresh:1;url=cart.php");
        die("<div class='alert alert-danger text-center' role='alert'>Goods should be from same shop.</div>");
    }
    
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
?>

<head>
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
    <link rel="stylesheet" href="css/checkout.css">
    <title>Hong Kong Cube Shop</title>
</head>

<body>
   <!-- import the navigation menu -->
    <?php
    //define the include that make nav.php can be access and include
    define('include',true);
    include("nav.php");
    ?>
    
<!-- check out process navigation banner -->
    <section>
        <nav class="breadcrumb">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 text-color">
                        Check out Process
                    </div>
                    <a class="breadcrumb-item" href="cart.php">View Shopping Cart</a>
                    <a class="breadcrumb-item text-color" href="checkout.php">Pick-up Place Selection</a>
                    <a class="breadcrumb-item disabled">Order Confirmed</a>
                </div>
            </div>
        </nav>
    </section>

    <main>
        <div class="container-fluid">
            <form action="handleCheckout.php" method="post">
                <div class="row top-2">
                    <div class="col-md-7 offset-md-1">
                        <h2>Select a pick-up place</h2>
                        <p class="text-color">Check the order is correct before check out</p>
                        <hr>
                        <!-- find all the shop and list it here -->
                        <?php
                        include("conn.php");
                        $sql = "Select * from shop";
                        $rs = mysqli_query($conn, $sql) or die (mysqli_error($conn));
                        while($rc=$rs->fetch_assoc()){
                            extract($rc);
                            echo <<<EOD
                                <div class="custom-control custom-radio">
                                <input type="radio" value="$shopID" id="$shopID" name="shopid" class="custom-control-input pickUpRadio" required>
                                <label class="custom-control-label" for="$shopID">
                                    <h5><strong>$shopName</strong></h5>
                                    <p>$address</p>
                                </label>
                                </div>
                                <hr>
EOD;
                        }
                        ?>
                    </div>
                    <!-- The default message -->
                    <div class="col">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="card-title">Make a Payment</h3>
                                <div class="alert alert-danger text-center" id="confirmPlace" role="alert">Haven't Select Pick-up Place.</div>
                                <input type="submit" class="btn btn-warning" value="Check out">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <hr class="featurette-divider">
        </div>
    </main>

    <footer class="container">
        <p class="float-right"><a href="#">Back to top</a></p>
        <p>Work by SE1B,Group Glory, So Ho Tai, Chan Hei · <a href="https://www.vtc.edu.hk/" target="_blank">VTC</a></p>
    </footer>
    
    <!-- if user select the shop, replace the default message -->
    <script type="text/javascript">
        $(document).ready(function() {

            $('.pickUpRadio').click(function() {
                var name = $(this).val();
                $('#confirmPlace').replaceWith("<div class='alert alert-success text-center' id='confirmPlace' role='alert'>Pick-up Place Selected.</div>");
            });

        });

    </script>

</body>

</html>
