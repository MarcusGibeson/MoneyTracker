<?php
require_once 'includes/configurations/config_session.inc.php';
require_once 'includes/views/account_view.inc.php';
?>

<h3>Add earning</h3>

<?php if (isset($_SESSION["errors_account"])): ?>
    <div class="error-messages">
        <?php foreach ($_SESSION["errors_account"] as $error): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION["errors_account"]); ?>
<?php endif; ?>

<form action="includes/accounts.inc.php" method="post">
    <?php account_inputs(); ?>
    <button type="submit">Add Account</button>
</form>