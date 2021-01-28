<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    ob_start(); //to solve header() problem
    session_start();
    ?>
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
    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&family=Open+Sans&display=swap" rel="stylesheet">
    <!-- My CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/register.css">
    <title>HKCS Register</title>
</head>

<body>
    <?php
        define('include',true);
        include("nav.php");
        include("conn.php");
        extract($_POST);
        $valueNotNull;
        if(isset($_POST['customerEmail'])){
            //check email
            if (!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
                echo "<div class='alert alert-danger text-center' role='alert'>
                            Invalid email address.
                        </div>";
            } else if (!filter_var($phoneNumber, FILTER_SANITIZE_NUMBER_INT)){
                echo "<div class='alert alert-danger text-center' role='alert'>
                            Invalid phone number.
                        </div>";
            } else {
                //check if user havn't input all the field
                foreach($_POST as $key => $val){
                    if($val == ''){
                        echo "<div class='alert alert-danger text-center' role='alert'>
                            Please input the $key field.
                            </div>";
                        $valueNotNull = false;
                        break;
                    } else 
                        $valueNotNull = true;
                }
                
                if($valueNotNull) {
                    $sql = "SELECT * FROM customer where customerEmail = '$customerEmail' AND password = '$password'";
                    $rs = mysqli_query($conn, $sql) or die (mysqli_error($conn));
                    if(mysqli_num_rows($rs)==0) {
                        $sql = "INSERT into customer values ('$customerEmail', '$firstName', '$lastName', '$password', '$phoneNumber')";
                        mysqli_query($conn, $sql) or die (mysqli_error($conn));
                        session_unset();
                        $_SESSION['id'] = $customerEmail;
                        $_SESSION['role'] = "C";
                        mysqli_free_result($rs);
                        mysqli_close($conn);
                        echo "<div class='alert alert-success text-center' role='alert'>
                                Register Successful! The page will redirect to home page.
                            </div>";
                        header("refresh:1.5;url=index.php");
                    }else{
                        echo "<div class='alert alert-danger text-center' role='alert'>
                                The User ID is existed.
                            </div>";
                    }
                }
            }
        }
    ?>



    <main class="text-center" id="registerMain">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <form class="form-signin" method="post" action="register.php">
                        <img class="mb-4" src="image/logo.png" alt="Register" width="35">
                        <h1 class="h3 mb-3 font-weight-normal">Please Sign Up</h1>
                        <label for="inputEmail" class="sr-only">Email address</label>
                        <input type="email" id="inputEmail" class="form-control" placeholder="Email address" 
                            autofocus name="customerEmail">
                        <label for="inputPassword" class="sr-only">Password</label>
                        <input type="password" id="inputPassword" class="form-control" placeholder="Password" 
                            name="password">
                        <label for="inputFName" class="sr-only">First Name</label>
                        <input type="text" id="inputFName" class="form-control" placeholder="First Name" 
                            name="firstName">
                        <label for="inputLName" class="sr-only">Last Name</label>
                        <input type="text" id="inputLName" class="form-control" placeholder="Last Name" 
                            name="lastName">
                        <label for="inputPhone" class="sr-only">Phone Number</label>
                        <input type="tel" id="inputPhone" class="form-control" placeholder="Phone Number" 
                            name="phoneNumber">
                        <button class="btn btn-lg btn-success" type="submit">Sign up</button>
                    </form>
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
<?php
    ob_end_flush(); //to handle header() problem
?>
</html>