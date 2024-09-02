<?php
session_start();

if($_SERVER["REQUEST_METHOD"] === "POST") {

    $user_id = $_SESSION["user_id"];
    $account_name = $_POST["account_name"];
    $account_type = $_POST["account_type"];

    try {
        require_once "configurations/dbh.inc.php";
        require_once "models/account_model.inc.php";
        require_once "controllers/account_contr.inc.php";

        //Error Handling
        $errors = [];

        if (is_input_empty($account_name, $account_type)) {
            $errors["empty_input"] = "Fill in all fields!";
        }

        if (is_account_exists($pdo, $user_id, $account_name)) {
            $error["account_exists"] = "Account already exists!";
        }

        if ($errors) {
            $_SESSION["errors_account"] = $errors;

            $_SESSION["account_data"] = [
                "account_name" => $account_name,
                "account_type" => $account_type,
            ];
            header("Location: ../add-account.php");
            die();
        }

        //Create the account with a default balance of $0
        create_account($pdo, $user_id, $account_name, $account_type);

        header("Location: ../index.php?account=success");
        $pdo = null;
        $stmt = null;

        die();

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location:../add-account.php");
}