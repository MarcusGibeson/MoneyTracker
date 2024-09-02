<?php
session_start();
require_once 'includes/configurations/dbh.inc.php';
require_once 'includes/models/account_model.inc.php';

$user_id = $_SESSION['user_id'];
$accounts = get_user_accounts($pdo, $user_id);
?>

<p>Account Overview</p>
<?php if (count($accounts) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Account Name</th>
                <th>Account Type</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($accounts as $account): ?>
                <tr>
                    <td><?php echo htmlspecialchars($account['account_name']); ?></td>
                    <td><?php echo htmlspecialchars($account['account_type']); ?></td>
                    <td><?php echo htmlspecialchars($account['balance'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No accounts found. </p>
<?php endif; ?>


<div>
    <p> Don't have an account? </p>
    <p><button id="add-account-link">Create Account</button></p>
    </div>


<!-- Modal Structure -->
<div id="add-account-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="add-account-content">
            <!-- Account form will be loaded here -->
        </div>
    </div>
</div>