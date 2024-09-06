<?php
class AccountModel{
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    function create_account($user_id, $account_name, $account_type) {
        $stmt = $this->pdo->prepare("INSERT INTO accounts (user_id, account_name, account_type, balance, created_at) VALUES (?, ?, ?, 0, NOW())");
        $stmt->execute([$user_id, $account_name, $account_type]);
    }

    function is_account_exists($user_id, $account_name) {
        $stmt = $this->pdo->prepare("SELECT 1 FROM accounts WHERE user_id = ? AND account_name = ?");
        $stmt->execute([$user_id, $account_name]);
        return $stmt->fetchColumn();
    }

    function get_user_accounts($user_id) {
        $stmt = $this->pdo->prepare("
        SELECT 
            id,
            account_name, 
            account_type, 
            COALESCE(
                (SELECT SUM(amount) FROM earnings WHERE account_id = accounts.id), 0
            ) - COALESCE(
                (SELECT SUM(amount) FROM expenses WHERE account_id = accounts.id), 0
            ) AS balance 
        FROM accounts 
        WHERE user_id = :user_id
        ");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    

    public function get_total_earnings($user_id) {
        $query = "SELECT SUM(amount) AS total_earnings FROM earnings WHERE user_id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_earnings'] ?: 0;
    }

    public function get_total_expenses($user_id) {
        $query = "SELECT SUM(amount) AS total_expenses FROM expenses WHERE user_id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_expenses'] ?: 0;
    }

    public function get_total_balance($user_id) {
        $total_earnings = $this->get_total_earnings($user_id);
        $total_expenses = $this->get_total_expenses($user_id);
        $total_balance = $total_earnings - $total_expenses;
        return $total_balance;
    }

    public function getEarningsByAccountId($account_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM earnings WHERE account_id = ? ORDER BY gain_date DESC");
        $stmt->execute([$account_id]);
        return $stmt->FetchAll(PDO::FETCH_ASSOC);
    }

    public function getExpensesByAccountId($account_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM expenses WHERE account_id = ? ORDER BY expense_date DESC");
        $stmt->execute([$account_id]);
        return $stmt->FetchAll(PDO::FETCH_ASSOC);
    }
}





