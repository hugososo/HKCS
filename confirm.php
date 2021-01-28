<!DOCTYPE html>
<html lang="en">
<?php
session_start();    
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <!--icon library-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&family=Open+Sans&display=swap" rel="stylesheet">
    <!-- My CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/confirm.css">
    <title>Hong Kong Cube Shop</title>
</head>

<body>
    <!-- access control -->
    <?php
    if(empty($_GET)){
        header("refresh:1;url=cart.php");
        die("<div class='alert alert-danger text-center' role='alert'>You need to place order first.</div>");
    }
    //define the include that make nav.php can be access and include
    define('include',true);
    //import the navigation menu
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
                    <a class="breadcrumb-item disabled">Pick-up Place Selection</a>
                    <a class="breadcrumb-item disabled text-color">Order Confirmed</a>
                </div>
            </div>
        </nav>
    </section>

    <main>
        <div class="container">
            <div class="row top-2">
                <div class="col-md-12">
                    <div class="card text-center">
                        <div class="card-header">
                            Order Confirmed
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">The order is placed!</h5>
                            <p class="card-text">Welcome to our shop, Thank you for your support!</p>
                            <a href="cust_order.php" class="btn btn-info">All Order History</a>
                            <a href="index.php" class="btn btn-info">Back to Homepage</a>
                            <!-- link to order detail page for parsing the specific orderID with GET method to open the specific Order-->
                            <?php
                            echo "<a href='order_detail.php?orderID={$_GET['orderID']}' class='btn btn-info'>The Previous Order Detail</a>";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="featurette-divider">
        </div>
    </main>

    <footer class="container">
        <p class="float-right"><a href="#">Back to top</a></p>
        <p>Work by SE1B,Group Glory, So Ho Tai, Chan Hei · <a href="https://www.vtc.edu.hk/" target="_blank">VTC</a></p>
    </footer>

</body>

</html>