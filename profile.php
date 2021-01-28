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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
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
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/profile.css">
    <title>Hong Kong Cube Shop</title>
</head>

<body>
    <?php
        if(empty($_SESSION['role'])) {
        header("refresh:3;url=index.php");
        die("<h1>You have no permission to access this page</h1><br>
            <p>You will redirect to home page in 3 seconds</p>");
        }
        define('include',true);
        include("nav.php");
        extract($_POST);
        ?>

    <main>
        <div class="container-fluid">
            <div class="row top-2">
                <div class="col-md-2">
                    <div class="row" id="profiletitle">
                        <div class="col-md-2 offset-md-2">
                            <img src="image/user36px.svg" alt="Profile" id="profileheader">
                        </div>
                        <div class="col-md">
                            <h3>Profile</h3>
                        </div>
                    </div>
                    <hr>
                    <ul class="list-group">
                        <li><a href="profile.php">Account Management</a></li>
                        <?php
                        if($_SESSION['role'] == 'C')    //show different information according to the user role
                        echo "<li><a href='cust_order.php'>Order History</a></li>";
                        if($_SESSION['role'] == 'T')
                        echo "<li><a href='tent_order.php'>View All Order</a></li>
                        <li><a href=\"Inventory.php\">Inventory Management</a></li>";
                        ?>

                    </ul>
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-7 offset-md-1">
                            <h4>Account Information</h4>
                            <hr>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p>Email:</p>
                                        </div>
                                        <?php
                                        include("conn.php");
                                        if($_SESSION['role'] == 'C')    //search different table according to the user role
                                            $sql = "SELECT * from customer where customerEmail = '{$_SESSION['id']}'";
                                        if($_SESSION['role'] == 'T')
                                            $sql = "SELECT * from tenant where tenantID = '{$_SESSION['id']}'";
                                        $rs = mysqli_query($conn, $sql) or die (mysqli_error($conn));
                                        $rc = mysqli_fetch_array($rs);
                                        echo "<span class='col-md'>{$rc[0]}</span>";
                                        ?>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p>Name:</p>
                                        </div>
                                        <?php
                                        if($_SESSION['role'] == 'C')
                                            echo "<span class='col-md-6'>$rc[2] $rc[1]</span>";
                                        if($_SESSION['role'] == 'T')
                                            echo "<span class='col-md-6'>$rc[1]</span>";
                                        ?>
                                        <a href="" data-toggle="modal" data-target="#nameModal" class="col-md">
                                            Edit
                                        </a>
                                    </div>
                                    <div class="modal" id="nameModal">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <!-- Modal Header -->
                                                <form action="profile.php" method="POST">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Change Name</h4>
                                                        <button type="reset" class="close" data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <?php
                                                        if($_SESSION['role'] == 'C') {
                                                            echo "<div class=\"modal-body\">
                                                            Your new First Name:
                                                            <input type=\"text\" name=\"fname\" value=\"$rc[1]\" required>
                                                            </div>
                                                            <div class=\"modal-body\">
                                                            Your new Last Name:
                                                            <input type=\"text\" name=\"lname\" value=\"$rc[2]\" required>
                                                            </div>";

                                                            if(isset($fname)&&isset($lname)){
                                                                if(strlen($fname)==0&&strlen($lname)==0) {
                                                    ?>
                                                                    <script type="text/javascript">
                                                                        $("#nameModal").modal('show');

                                                                    </script>
                                                                    <div class='alert alert-info' role='alert'>
                                                                        Please fill in the Name.
                                                                    </div>
                                                                    <?php
                                                                }
                                                                else{
                                                                    $sql = "Update customer set firstName = '$fname', lastName = '$lname' where customerEmail = '{$_SESSION['id']}'";
                                                                    mysqli_query($conn, $sql) or die (mysqli_error($conn));
                                                                    header("Location:profile.php");
                                                                }
                                                            }
                                                        }
                                                        else if($_SESSION['role'] == 'T') {
                                                            echo "<div class=\"modal-body\">
                                                            Your new Name:
                                                            <input type=\"text\" name=\"tname\" value=\"$rc[1]\" required>
                                                            </div>";

                                                            if(isset($tname)){
                                                                if(strlen($tname)==0) {
                                                    ?>
                                                    <script type="text/javascript">
                                                        $("#nameModal").modal('show');

                                                    </script>
                                                    <div class='alert alert-info' role='alert'>
                                                        Please fill in the Name.
                                                    </div>
                                                    <?php
                                                                }
                                                                else{
                                                                    $sql = "Update tenant set name = '$tname' where tenantID = '{$_SESSION['id']}'";
                                                                    mysqli_query($conn, $sql) or die (mysqli_error($conn));
                                                                    header("Location:profile.php");
                                                                }
                                                            }   
                                                        }
                                                    ?>
                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-danger">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php
                                if($_SESSION['role'] == 'C') {
                                    echo <<<EOD
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p>Contact Number:</p>
                                        </div>
                                        <span class="col-md-6">$rc[4]</span>
                                        <a href="" data-toggle="modal" data-target="#phoneModal" class="col-md">
                                            Edit
                                        </a>
                                    </div>
                                    <div class="modal" id="phoneModal">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Change Phone Number</h4>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                </div>

                                                <!-- Modal body -->
                                                <form action="profile.php" method="POST">
                                                    <div class="modal-body">
                                                        Phone Number:
                                                        <input type="text" name="phoneNum" value="$rc[4]" required maxlength="8">
                                                    </div>
EOD;

                                                    if(isset($phoneNum)) {
                                                        if(strlen($phoneNum)<8) {
                                                        ?>
                                <script type="text/javascript">
                                    $("#phoneModal").modal('show');

                                </script>
                                <div class='alert alert-info' role='alert'>
                                    Please fill in the Phone number in correct format.
                                </div>
                                <?php
                                                        }
                                                        else{
                                                            $sql = "Update customer set phoneNumber = '$phoneNum' where customerEmail = '{$_SESSION['id']}'";
                                                            mysqli_query($conn, $sql) or die (mysqli_error($conn));
                                                            header("Location:profile.php");
                                                        }
                                                    }
                                                    echo
<<<EOD
                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-danger">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </li>
EOD;
                                }
                                ?>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p>Password:</p>
                                        </div>
                                        <span class="col-md-6">********</span>
                                        <a href="" data-toggle="modal" data-target="#pwModal" class="col-md" id="editpassword">
                                            Edit
                                        </a>
                                    </div>
                                    <div class="modal" id="pwModal">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Change Password</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>

                                                <!-- Modal body -->
                                                <form action="profile.php" method="POST">
                                                    <div class="modal-body">
                                                        Old Password:
                                                        <input type="password" name="opw" required>
                                                    </div>
                                                    <?php
                                                    $approve = false;
                                                    if(isset($opw)){
                                                        if($opw != $rc['password']) {
                                                    ?>
                                                    <script type="text/javascript">
                                                        $("#pwModal").modal('show');

                                                    </script>
                                                    <?php
                                                        echo "<div class='alert alert-danger' role='alert' id='wrongalert'>
                                                        Your Old Password is wrong!
                                                        </div>";
                                                        }else if ($opw == $rc['password'])
                                                            $approve = true;
                                                    }
                                                    else
                                                    echo "<div class='alert alert-info' role='alert'>
                                                        Please fill in the old password.
                                                        </div>";
                                                    ?>
                                                    <div class="modal-body">
                                                        New Password:
                                                        <input type="password" name="npw" required>
                                                    </div>
                                                    <div class="modal-body">
                                                        Re-confirm Password:
                                                        <input type="password" name="repw" required>
                                                    </div>
                                                    <?php
                                                        if(isset($npw)&&isset($repw)){
                                                            if($npw != $repw) {
                                                    ?>
                                                    <script type="text/javascript">
                                                        $("#pwModal").modal('show');

                                                    </script>
                                                    <?php
                                                            $approve = false;
                                                            echo "<div class='alert alert-danger' role='alert'>
                                                            The Re-confirm Password is not match with New Password
                                                            </div>";
                                                            }
                                                            else if($approve == true){
                                                                if($_SESSION['role']=='C')
                                                                $sql = "Update customer set password = '$repw' where customerEmail = '{$_SESSION['id']}'";
                                                                if($_SESSION['role']=='T')
                                                                $sql = "Update tenant set password = '$repw' where tenantID = '{$_SESSION['id']}'";
                                                                mysqli_query($conn, $sql) or die (mysqli_error($conn));
                                                        ?>
                                                    <script type="text/javascript">
                                                        $("#wrongalert").hide();
                                                        $("#pwModal").modal('show');

                                                    </script>
                                                    <?php
                                                                echo "<div class='alert alert-success' role='alert' id='savedmsg'>
                                                                Saved!
                                                                </div>";
                                                        ?>
                                                    <script type="text/javascript">
                                                        $("#savedmsg").show();
                                                    </script>
                                                    <?php        
                                                                header("refresh:1;url=profile.php");
                                                            }
                                                        }
                                                        else
                                                        echo "<div class='alert alert-info' role='alert'>
                                                            Please fill in the blank.
                                                            </div>";
                                                        ?>

                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-danger">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            
                            <?php
                            if($_SESSION['role'] == 'C') {                           
                            ?>
                            <button type="button" class="btn btn-danger mt-4 float-right" data-toggle="modal" data-target="#staticBackdrop">
                                Delete Account
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Do you want to delete
                                                account?</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <form action="deletepw.php" method="POST">
                                                <script type="text/javascript" src="js/profile.js"></script>
                                                <button type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Careful!This will delete whole account" name="delete">Yes</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                    <hr class="featurette-divider">
                </div>
            </div>
        </div>
    </main>

    <footer class="container">
        <p class="float-right"><a href="#">Back to top</a></p>
        <p>Work by SE1B,Group Glory, So Ho Tai, Chan Hei · <a href="https://www.vtc.edu.hk/" target="_blank">VTC</a></p>
    </footer>
</body>
<?php
        ob_end_flush(); //to handle header() problem
        mysqli_free_result($rs);
        mysqli_close($conn);
?>

</html>
