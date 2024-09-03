<?php
require_once __DIR__ . '/../models/earning_model.inc.php';

class EarningController {
    private $earningModel;

    public function __construct($pdo) {
        $this->earningModel = new EarningModel($pdo);
    }

    public function addEarning($user_id, $account_id, $amount, $description, $category, $gain_date) {
        if ($this->earningModel->is_earning_exists($user_id, $account_id, $amount, $gain_date)) {
            return "Earning already exists!";
        }
        $this->earningModel->create_earning($user_id, $account_id, $amount, $description, $category, $gain_date);
    }

    public function deleteEarning($earning_id) {
        $this->earningModel->delete_earning($earning_id);
    }

    public function getUserEarnings($user_id) {
        return $this->earningModel->get_user_earnings($user_id);
    }

    function is_input_empty(...$inputs) {
        foreach($inputs as $input) {
            if(empty(trim($input))) {
                return true;
            }
        }
        return false;
    }
}