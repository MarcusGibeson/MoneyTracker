<?php
require_once 'includes/configurations/config_session.inc.php';
require_once 'includes/views/signup_view.inc.php';
?>

<h3> Signup </h3>

<form action="includes/signup.inc.php" method="post">
    <?php
    signup_inputs();
    ?>
    <button> Register</button>
</form>

<?php
check_signup_errors();


?>
