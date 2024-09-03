<?php
session_start();

if($_SERVER["REQUEST_METHOD"] === "POST") {

    $user_id = $_SESSION["user_id"];
    $account_name = $_POST["account_name"];
    $account_type = $_POST["account_type"];

    try {
        require_once "configurations/dbh.inc.php";
        require_once "controllers/account_contr.inc.php";

        $accountController = new AccountController($pdo);
        $accountController->addAccount($user_id, $account_name, $account_type);

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location:../add-account.php");
}