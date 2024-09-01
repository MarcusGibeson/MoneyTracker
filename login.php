<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
        if(!isset($_SESSION["user_id"])) { ?>
        <h3> Login </h3>
        <form action="includes/login.inc.php" method="post">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="pwd" placeholder="Password">
            <button> Log in</button>
        </form>
    <?php } ?>
    <p> Don't have an account? </p>
    <p><a href="sign-up.php">Create Account</a></p>
</body>
</html>