<?php
session_start();
require_once 'includes/configurations/dbh.inc.php';
require_once 'includes/models/account_model.inc.php';
require_once 'includes/controllers/account_contr.inc.php';

$user_id = $_SESSION['user_id'];
$accountController = new AccountController($pdo);

$accounts = $accountController->get_user_accounts($user_id);

// Ensure $accounts is always an array
if ($accounts === null) {
    $accounts = [];
}

$total_balance = $accountController->showAccountTotal($user_id);
$total_balance_all_accounts = array_sum(array_column($accounts, 'balance'));
?>

<style>
    .account-row span {
        color: #007bff;
        text-decoration: underline;
        cursor: pointer;
    }

    .account-row:hover {
        background-color: #f8f9fa;
    }
</style>

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
                <tr class="account-row" data-account-id="<?php echo $account['id']; ?>">
                    <td>
                        <span><?php echo htmlspecialchars($account['account_name']); ?> </span>                    
                        <div id="account-details"></div>
                    </td>
                    <td><?php echo htmlspecialchars($account['account_type']); ?></td>
                    <td><?php echo number_format($account['balance'], 2); ?></td>
                </tr>
                
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"><strong>Total Balance:</strong></td>
                <td><strong><?php echo number_format($total_balance_all_accounts, 2); ?></strong></td>
            </tr>
        </tfoot>
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

<script>
    document.querySelectorAll('.account-row span').forEach(function(span) {
        span.addEventListener('click', function() {
            // Remove any existing details rows
            document.querySelectorAll('.account-details').forEach(function(detailsRow) {
                detailsRow.remove();
            });

            // Get the clicked row
            var row = this.closest('.account-row');

            // Create a new row for details
            var detailsRow = document.createElement('tr');
            detailsRow.classList.add('account-details');

            // Create a new cell to span across all columns
            var detailsCell = document.createElement('td');
            detailsCell.setAttribute('colspan', '3');
            detailsCell.textContent = 'Loading details...'; // Placeholder text

            // Append the cell to the details row
            detailsRow.appendChild(detailsCell);

            // Insert the details row after the clicked row
            row.insertAdjacentElement('afterend', detailsRow);

            // Load the actual details (you may want to fetch data via AJAX here)
            // For now, we'll just update the text after a short delay
            setTimeout(function() {
                detailsCell.textContent = 'Account details for ' + row.querySelector('span').textContent;
            }, 500);
        });
    });
</script>