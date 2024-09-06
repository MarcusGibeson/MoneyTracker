<?php
require_once 'includes/configurations/dbh.inc.php';
require_once 'includes/controllers/account_contr.inc.php';

$account_id = $_GET['account_id'];
$accountController = new AccountController($pdo);

//Fetch earnings and expenses
$earnings = $accountController->getEarnings($account_id);
$expenses = $accountController->getExpenses($account_id);

//Merge and label earnings and expenses
$combined = array_merge (
    array_map(function($item) {
        $item['type'] = 'Earning';
        return $item;
    }, $earnings),
    array_map(function($item) {
        $item['type'] = 'Expense';
        return $item;
    }, $expenses)
);

//sort by date
usort($combined, function($a, $b) {
    return strtotime($b['gain_date'] ?? $b['expense_date']) - strtotime($a['gain_date'] ?? $a['expense_date']);
});
?>

<div class="account=details">
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Category</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($combined as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['type']); ?></td>
                    <td><?php echo number_format($item['amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <td><?php echo htmlspecialchars($item['category']); ?></td>
                    <td><?php echo htmlspecialchars($item['gain_date'] ?? $item['expense_date']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>