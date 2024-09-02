<?php
require_once 'includes/configurations/config_session.inc.php';
require_once 'includes/views/login_view.inc.php';
?>

<?php
    if(!isset($_SESSION["user_id"])) { ?>
        <h3> Login </h3>
        <form action="includes/login.inc.php" method="post">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="pwd" placeholder="Password">
            <button> Log in</button>
        </form>
<?php } ?>
    <div>
    <p> Don't have an account? </p>
    <p><button id="signup-link">Create Account</button></p>
    </div>


<!-- Modal Structure -->
<div id="signup-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="signup-content">
            <!-- Signup form will be loaded here -->
        </div>
    </div>
</div>