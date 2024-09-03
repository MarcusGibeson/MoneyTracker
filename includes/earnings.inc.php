<?php
session_start();

if($_SERVER["REQUEST_METHOD"] === "POST") {

    $user_id = $_SESSION["user_id"];
    $account_id = $_POST["account_id"];
    $amount = $_POST["amount"];
    $description = $_POST["description"];
    $category = $_POST["category"];
    $gain_date = $_POST["gain_date"];

    try {
        require_once "configurations/dbh.inc.php";
        require_once "controllers/earning_contr.inc.php";

        $earningController = new EarningController($pdo);

        //Error Handling
        $errors = [];

        if ($earningController->is_input_empty($account_id,  $amount, $description, $category, $gain_date)) {
            $errors["empty_input"] = "Fill in all fields!";
        }

        if ($errors) {
            $_SESSION["errors_earning"] = $errors;

            $_SESSION["earning_data"] = [
                "account_id" => $account_id,
                "amount" => $amount,
                "description" => $description,
                "category" => $category,
                "gain_date" => $gain_date,
            ];
            header("Location: ../add-earning.php");
            die();
        }

        //Create the earning record
        $earningController->addEarning($user_id, $account_id, $amount, $description, $category, $gain_date);

        header("Location: ../index.php?earning=success");
        $pdo = null;
        die();

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../add-earning.php");
}