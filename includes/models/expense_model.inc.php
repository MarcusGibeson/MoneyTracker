<?php

class ExpenseModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    function create_expense($user_id, $account_id, $amount, $description, $category, $expense_date) {
        $stmt = $this->pdo->prepare("INSERT INTO expenses (user_id, account_id, amount, description, category, expense_date, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $account_id, $amount, $description, $category, $expense_date]);
    }

    function is_expense_exists($user_id, $account_id, $amount, $expense_date) {
        $stmt = $this->pdo->prepare("SELECT 1 FROM expenses WHERE user_id = ? AND account_id = ? AND amount = ? AND expense_date = ?");
        $stmt->execute([$user_id, $account_id, $amount, $expense_date]);
        return $stmt->fetchColumn();
    }

    public function delete_expense($expense_id) {
        $stmt = $this->pdo->prepare("DELETE FROM expenses WHERE id = ?");
        $stmt->execute([$expense_id]);
    }

    public function get_user_expenses($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM expenses WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}



