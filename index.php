<?php
require_once 'includes/configurations/config_session.inc.php';

if(isset($_SESSION["user_id"])) {
    include('logged_in.php');
} else {
    include('logged_out.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Tracker</title>
</head>
<link rel="stylesheet" href="css/index.css">
<script src="js/index.js"></script>


<body>
    <section id="dynamic-content">
        <p> --------------------------------------------------------------------------------------------------------------</p>
    </section>

    
</body>
</html>