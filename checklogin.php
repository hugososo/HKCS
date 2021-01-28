<?php
include( 'conn.php' );
extract( $_POST );

function keeplogin ( $keeplogin ) {
    //set a cookie to keep the user login althought he close the browser
    $lifetime = time () + 14 * 24 * 3600;
    setcookie( session_name(), session_id(), $lifetime, '/' );
}

session_start();
$sql = "SELECT * FROM customer where customerEmail='$id' and password = '$pw'";
//find the login information is it match the customer table
$rs = mysqli_query( $conn, $sql ) or die ( mysqli_error( $conn ) );
$rc = mysqli_fetch_assoc($rs);
if (!empty($rc['customerEmail']) && $rc['customerEmail'] == $id && $rc['password']==$pw) {
    //if >0 that means it match a customer table data
    $_SESSION['role'] = 'C';
    //store the role to session
    $_SESSION['id'] = $id;
    //store the customer id to session
    if ( isset( $keeplogin ) && $keeplogin == 'keeplogin' )    //check if user ticks the keep login check box, run the keeplogin function
        keeplogin( $keeplogin );
    mysqli_free_result( $rs );
} else {
    $sql = "SELECT * FROM tenant where tenantID='$id' and password = '$pw'";
    //if customers table doesn't match, search tenant table
    $rs = mysqli_query($conn, $sql) or die (mysqli_error($conn));
    $rc = mysqli_fetch_assoc($rs);
    if(!empty($rc['tenantID']) && $rc['tenantID'] == $id && $rc['password']==$pw){
        $_SESSION['role'] = "T";
        $_SESSION['id'] = $id;
        if(isset($keeplogin) && $keeplogin=="keeplogin")
            keeplogin($keeplogin);
        mysqli_free_result($rs);
    } else {
    echo "<script type='text/javascript'>alert('UserID/Password incorrect!' );
    </script>";    //if two of table do not match, that means incorrect login.
    }
}
mysqli_close($conn);
header("refresh:0;url = index.php" );
?>