<!-- access control -->
<?php
if(!defined('include')) {
    header("refresh:3;url=index.php");
    die("<h2>Direct access not permitted</h2>");
}
echo    //show the tenant menu in nav menu
<<<EOD
<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
    aria-expanded="false">
    <img src="image/user.svg" alt="Profile">
</a>
<div class="dropdown-menu" aria-labelledby="navbarDropdown">
    <a class="dropdown-item" href="profile.php">My Account</a>
    <a class="dropdown-item" href="tent_order.php">Check Order</a>
    <a class="dropdown-item" href="Inventory.php">Inverntory Management</a>
    <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="destroySession.php">Logout</a>
</div>
EOD;
?>