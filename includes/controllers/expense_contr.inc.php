<?php
require_once __DIR__ . '/../models/expense_model.inc.php';
class ExpenseController {
    private $expenseModel;

    public function __construct($pdo) {
        $this->expenseModel = new ExpenseModel($pdo);
    }

    public function addExpense($user_id, $account_id, $amount, $description, $category, $expense_date) {
        if ($this->expenseModel->is_expense_exists($user_id, $account_id, $amount, $expense_date)) {
            return "Expense already exists!";
        }
        $this->expenseModel->create_expense($user_id, $account_id, $amount, $description, $category, $expense_date);
    }

    public function deleteExpense($expense_id) {
        $this->expenseModel->delete_expense($expense_id);
    }

    public function getUserExpenses($user_id) {
        return $this->expenseModel->get_user_expenses($user_id);
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