<?php
require_once 'includes/configurations/config_session.inc.php';
require_once 'includes/configurations/dbh.inc.php';
require_once 'includes/views/earning_view.inc.php';
?>

<h3>Add Earning</h3>

<?php if (isset($_SESSION["errors_earning"])): ?>
    <div class="error-messages">
        <?php foreach ($_SESSION["errors_earning"] as $error): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION["errors_earning"]); ?>
<?php endif; ?>

<form action="includes/earnings.inc.php" method="post">
    <?php add_Earning_Inputs($pdo); ?>
    <button type="submit">Add Earning</button>
</form>