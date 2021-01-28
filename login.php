<!--access control-->
<?php
if(!defined('include')) {
  die('Direct access not permitted');
}
echo    //show the login menu in nav bar
<<<EOD
<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
    aria-expanded="false">
    <img src="image/user.svg" alt="Profile">
</a>

<form class="dropdown-menu p-4" aria-labelledby="navbarDropdown" action="checklogin.php" method="post">
    <div class="form-group">
        <label for="loginid">Login ID</label>
        <input type="text" class="form-control" id="loginid" name="id" placeholder="email@example.com/ID" required>
    </div>
    <div class="form-group">
        <label for="loginPw">Password</label>
        <input type="password" class="form-control" id="loginPw" placeholder="Password" required name="pw">
    </div>
    <div class="form-group">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="dropdownCheck2" name="keeplogin" value="keeplogin">
            <label class="form-check-label" for="dropdownCheck2">
                Keep me logged in
            </label>
        </div>
    </div>
    <button type="submit" class="btn btn-dark">Sign in</button>
</form>
EOD;
?>

