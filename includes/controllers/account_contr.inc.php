<?php
require_once __DIR__ . '/../models/account_model.inc.php';
class AccountController {
    private $accountModel;

    public function __construct($pdo) {
        $this->accountModel = new AccountModel($pdo);
    }

    public function addAccount($user_id, $account_name, $account_type) {
        $errors = [];

        if (empty($account_name) || empty ($account_type)) {
            $errors["empty_input"] = "Fill in all fields!";
        }

        if ($this->accountModel->is_account_exists($user_id, $account_name)) {
            $errors["account_exists"] = "Account already exists!";
        }

        if($errors) {
            $_SESSION["errors_account"] = $errors;
            $_SESSION["account_data"] = [
                "account_name"=> $account_name,
                "account_type"=> $account_type,
            ];
            header("Location: ../add-account.php");
            exit();
        }

        //Create the account with a default balance of $0
        $this->accountModel->create_account($user_id, $account_name, $account_type);
        header("Location: ../index.php?account=success");
    }

    public function showAccountTotal($user_id) {
        $total_balance = $this->accountModel->get_total_balance($user_id);
        return $total_balance;
    }

    function is_input_empty(...$inputs) {
        foreach($inputs as $input) {
            if(empty(trim($input))) {
                return true;
            }
        }
        return false;
    }

    public function get_user_accounts($user_id) {
        return $this->accountModel->get_user_accounts($user_id);
    }

    public function get_total_earnings($user_id) {
        return $this->accountModel->get_total_earnings($user_id);
    }

    public function get_total_expenses($user_id) {
        return $this->accountModel->get_total_expenses($user_id);
    }

    public function getEarnings($account_id) {
        return $this->accountModel->getEarningsByAccountId($account_id);
    }

    public function getExpenses($account_id) {
        return $this->accountModel->getExpensesByAccountId($account_id);
    }
}
