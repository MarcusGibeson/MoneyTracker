<?php

function add_expense_inputs($pdo) {
    // Fetch user's accounts from the database
    $user_id = $_SESSION['user_id'];  // Assuming user_id is stored in session
    $query = "SELECT id AS account_id, account_name FROM accounts WHERE user_id = ?";
    $stmt = $pdo->prepare($query);

    //Bind the parameter value
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchALL(PDO::FETCH_ASSOC);

    //Handle form values
    $amount_value = isset($_SESSION["expense_data"]["amount"]) ? htmlspecialchars($_SESSION["expense_data"]["amount"]) : '';
    $description_value = isset($_SESSION["expense_data"]["description"]) ? htmlspecialchars($_SESSION["expense_data"]["description"]) : '';
    $category_value = isset($_SESSION["expense_data"]["category"]) ? htmlspecialchars($_SESSION["expense_data"]["category"]) : '';
    $expense_date_value = isset($_SESSION["expense_data"]["expense_date"]) ? htmlspecialchars($_SESSION["expense_data"]["expense_date"]) : '';

    //Output the form fields
    echo '<input type="text" name="description" placeholder="Description" value="' . $description_value . '">';
    echo '<input type="text" name="amount" placeholder="Amount" value="' . $amount_value . '">';
    echo '<input type="text" name="category" placeholder="Category" value="' . $category_value . '">';
    echo '<input type="date" name="expense_date" placeholder="Expense Date" value="' . $expense_date_value . '">';

    // Dropdown for account_id
    echo '<select name="account_id">';
    foreach ($result as $row) {
        echo '<option value="' . htmlspecialchars($row['account_id']) . '">' . htmlspecialchars($row['account_name']) . '</option>';
    }
    echo '</select>';
}