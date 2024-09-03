<?php
session_start();
require_once 'includes/configurations/dbh.inc.php';
require_once 'includes/models/earning_model.inc.php';

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM earnings WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $earnings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<h1>Earnings Overview</h1>
<button id="add-earning-link">Create Earning</button>
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
            <?php foreach ($earnings as $earning): ?>
                <tr>
                    <td><?php echo htmlspecialchars($earning['id']); ?></td>
                    <td><?php echo htmlspecialchars($earning['account_id']); ?></td>
                    <td><?php echo htmlspecialchars($earning['amount']); ?></td>
                    <td><?php echo htmlspecialchars($earning['description']); ?></td>
                    <td><?php echo htmlspecialchars($earning['category']); ?></td>
                    <td><?php echo htmlspecialchars($earning['gain_date']); ?></td>
                    <td>
                        <!-- Delete Button -->
                        <form action="includes/delete_earning.inc.php" method="post" style="display:inline;">
                            <input type="hidden" name="earning_id" value="<?php echo $earning['id']; ?>">
                            <button type="submit" style="border:none;background:none;color:red;cursor:pointer;">X</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Modal Structure -->
<div id="add-earning-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="add-earning-content">
            <!-- Earning form will be loaded here -->
        </div>
    </div>
</div>