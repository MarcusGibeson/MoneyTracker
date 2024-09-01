<?php
require_once 'includes/configurations/config_session.inc.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Tracker</title>
</head>
<body>

    <!-- Navigation -->
    <section id="navigation-content">
    <div class="navigation-bar">
        <div class="active">
            <span class="text"> Homepage</span>
        </div>
        <div>
            <span class="text">Bills</span>
        </div> 
        <div>
            <span class="text">Income</span>
        </div> 
        <div>
            <!-- Login or Access Account depening on if user is logged in -->
            <?php if(!isset($_SESSION["user_id"])) { ?>
                <span class="text"><a href="login.php"> Login</a> </span>
            <?php } else { ?>
                <span class="text">Account</span>
            <?php } ?>
        </div>
    </div>
    </section>


    
</body>
</html>