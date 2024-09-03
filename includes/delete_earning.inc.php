<?php

session_start();
require_once 'configurations/dbh.inc.php';
require_once 'models/account_model.inc.php';

if(isset($_POST['earning_id'])) {
    $earning_id = $_POST['earning_id'];

    $stmt = $pdo->prepare("DELETE FROM earnings WHERE id = ?");
    $stmt->execute([$earning_id]);

    header("Location: ../index.php?delete=successful");
}