<?php


function add_Earning_Inputs($pdo) {
    // Fetch user's accounts from the database
    $user_id = $_SESSION['user_id'];  // Assuming user_id is stored in session
    $query = "SELECT id AS account_id, account_name FROM accounts WHERE user_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchALL(PDO::FETCH_ASSOC);

    $amount_value = isset($_SESSION["earning_data"]["amount"]) ? htmlspecialchars($_SESSION["earning_data"]["amount"]) : '';
    $description_value = isset($_SESSION["earning_data"]["description"]) ? htmlspecialchars($_SESSION["earning_data"]["description"]) : '';
    $category_value = isset($_SESSION["earning_data"]["category"]) ? htmlspecialchars($_SESSION["earning_data"]["category"]) : '';
    $gain_date_value = isset($_SESSION["earning_data"]["gain_date"]) ? htmlspecialchars($_SESSION["earning_data"]["gain_date"]) : '';

    echo '<input type="text" name="description" placeholder="Description" value="' . $description_value . '">';
    echo '<input type="text" name="amount" placeholder="Amount" value="' . $amount_value . '">';
    echo '<input type="text" name="category" placeholder="Category" value="' . $category_value . '">';
    echo '<input type="date" name="gain_date" placeholder="Gain Date" value="' . $gain_date_value . '">';

    // Dropdown for account_id
    echo '<select name="account_id">';
    foreach ($result as $row) {
        echo '<option value="' . htmlspecialchars($row['account_id']) . '">' . htmlspecialchars($row['account_name']) . '</option>';
    }
    echo '</select>';
}