<?php
session_start();
require_once 'includes/configurations/dbh.inc.php';
require_once 'includes/models/expense_model.inc.php';

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM expenses WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>
<h1>Expenses Overview</h1>
<button id="add-expense-link">Create Expense</button>
<table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Account</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Category</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($expenses as $expense): ?>
                <tr>
                    <td><?php echo htmlspecialchars($expense['id']); ?></td>
                    <td><?php echo htmlspecialchars($expense['account_id']); ?></td>
                    <td><?php echo htmlspecialchars($expense['amount']); ?></td>
                    <td><?php echo htmlspecialchars($expense['description']); ?></td>
                    <td><?php echo htmlspecialchars($expense['category']); ?></td>
                    <td><?php echo htmlspecialchars($expense['expense_date']); ?></td>
                    <td>
                        <!-- Delete button -->
                        <form action="includes/delete_expense.inc.php" method="post" style="display:inline;">
                            <input type="hidden" name="expense_id" value="<?php echo $expense['id']; ?>">
                            <button type="submit" style="border:none;background:none;color:red;cursor:pointer;">X</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Modal Structure -->
<div id="add-expense-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="add-expense-content">
            <!-- Expense form will be loaded here -->
        </div>
    </div>
</div>