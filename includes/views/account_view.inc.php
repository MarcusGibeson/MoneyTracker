<?php 

function account_inputs() {
    $account_name_value = isset($_SESSION["account_data"]["account_name"]) ? htmlspecialchars($_SESSION["account_data"]["account_name"]) : '';
    $account_type_value = isset($_SESSION["account_data"]["account_name"]) ? htmlspecialchars($_SESSION["account_data"]["account_name"]) : '';

    echo '<input type="text" name="account_name" placeholder="Account Name" value = "' . $account_name_value . '">';
    echo '<input type="text" name="account_type" placeholder="Account Name" value = "' . $account_type_value . '">';
}