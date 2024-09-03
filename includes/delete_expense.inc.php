<?php
session_start();
require_once 'configurations/dbh.inc.php';

if (isset($_POST['expense_id'])) {
    $expense_id = $_POST['expense_id'];

    $stmt = $pdo->prepare("DELETE FROM expenses WHERE id = ?");
    $stmt->execute([$expense_id]);

    // Redirect back to the expenses page after deletion
    header("Location: ../index.php?delete=success");
    exit();
}