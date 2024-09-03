<?php
require_once 'includes/configurations/config_session.inc.php';
require_once 'includes/configurations/dbh.inc.php';
require_once 'includes/views/expense_view.inc.php';
?>

<h3>Add Expense</h3>

<?php if (isset($_SESSION["errors_expense"])): ?>
    <div class="error-messages">
        <?php foreach ($_SESSION["errors_expense"] as $error): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION["errors_expense"]); ?>
<?php endif; ?>

<form action="includes/expenses.inc.php" method="post">
    <?php add_expense_inputs($pdo); ?>
    <button type="submit">Add Expense</button>
</form>