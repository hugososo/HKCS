<!-- access control -->
<?php
if (!defined('include')) {
    header("refresh:3;url=index.php");
    die("<h2>Direct access not permitted</h2>");
}
echo
"<header>
    <nav class='navbar navbar-expand-lg navbar-light bg-light'>
        <a class='navbar-brand' href='index.php'><img src='image/logo.png' alt='Logo'></a>";
if (empty( $_SESSION['id']))    //if havn't login, show the login menu
include('login.php');
else if ($_SESSION['role']=='C')    // if login as Customer role, show the customer menu
include('customerlogged.php');
else
include('tenantlogged.php');    //if login as Tenant role, show the tenant menu


if(empty($_SESSION["cart"]["items"])){  //if the cart havn't any item, define the item qty and the price to 0
    $itemsQty = 0;
    $totalFee = 0;
}
if(isset($_SESSION["cart"]["items"])) { //if the cart have item, define the item qty to the items quantity
    $itemsQty = $_SESSION["cart"]["items"];
    $totalFee = 0;

    include("conn.php");
    extract($_SESSION);
    foreach($cart as $key=>$val){

        $sql = "SELECT * FROM goods where goodsID = '$key'";
        $rs = mysqli_query($conn, $sql);

        while($rc = mysqli_fetch_array($rs)){
            extract($rc);
            $totalFee+=$val*$stockPrice;    //find the total price
        }
    }
}

echo
<<<EOD
            <div class = 'dropdown nav-link'>
                <a class = 'dropbtn' href = 'cart.php'>
                <img src = 'image/shopping-cart.svg' alt = 'Shopping Cart'>
                <span class ='badge badge-warning' id='badge-itemQty'>$itemsQty</span>
                </a>

                <div class = 'dropdown-menu p-4 text-muted dropdown-content' style = 'max-width: 200px;'>
                    <h5 id='text-itemQty'>$itemsQty items</h5>
                    <hr>
                    <p class = 'mb-0' id='total-Fee'>
                    Total Price: $$totalFee
                    </p>
                </div>
            </div>

            <button class = 'navbar-toggler' type = 'button' data-toggle ='collapse' data-target = '#navbarSupportedContent'
            aria-controls = 'navbarSupportedContent' aria-expanded ='false' aria-label='Toggle navigation'>
            <span class = 'navbar-toggler-icon'></span>
            </button>

            <div class = 'collapse navbar-collapse' id = 'navbarSupportedContent'>
                <ul class = 'navbar-nav w-100 nav-justified' id = 'jk'>
                    <li class = 'nav-item'>
                    <a class = 'nav-link' href='index.php'>Home</a>
                    </li>
                    <li class = 'nav-item'>
                    <a class = 'nav-link' href='product.php'>Product</a>
                    </li>
                    <li class = 'nav-item'>
                    <a class = 'nav-link' href='register.php'>Register</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
EOD;
?>
