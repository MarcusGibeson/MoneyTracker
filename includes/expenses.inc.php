<?php
session_start();

if($_SERVER["REQUEST_METHOD"] === "POST") {

    $user_id = $_SESSION["user_id"];
    $account_id = $_POST["account_id"];
    $amount = $_POST["amount"];
    $description = $_POST["description"];
    $category = $_POST["category"];
    $expense_date = $_POST["expense_date"];

    try {
        require_once "configurations/dbh.inc.php";
        require_once "controllers/expense_contr.inc.php";

        $expenseController = new ExpenseController($pdo);

        //Error Handling
        $errors = [];

        if ($expenseController->is_input_empty($account_id, $amount, $description, $category, $expense_date)) {
            $errors["empty_input"] = "Fill in all fields!";
        }

        if ($errors) {
            $_SESSION["errors_expense"] = $errors;

            $_SESSION["expense_data"] = [
                "account_id"=> $account_id,
                "amount" => $amount,
                "description" => $description,
                "category" => $category,
                "expense_date" => $expense_date,
            ];
            header("Location: ../add-expense.php");
            die();
        }

        //Create the expense record
        $expenseController->addExpense($user_id, $account_id, $amount, $description, $category, $expense_date);

        header("Location: ../index.php?expense=success");
        $pdo = null;
        $stmt = null;

        die();

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../add-expense.php");
}
