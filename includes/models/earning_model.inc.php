<?php

class EarningModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create_earning($user_id, $account_id, $amount, $description, $category, $gain_date) {
        $stmt = $this->pdo->prepare("INSERT INTO earnings (user_id, account_id, amount, description, category, gain_date, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $account_id, $amount, $description, $category, $gain_date]);
    }

    public function is_earning_exists($user_id, $account_id, $amount, $gain_date) {
        $stmt = $this->pdo->prepare("SELECT 1 FROM earnings WHERE user_id = ? AND account_id = ? AND amount = ? AND gain_date = ?");
        $stmt->execute([$user_id, $account_id, $amount, $gain_date]);
    }

    public function delete_earning($earning_id) {
        $stmt = $this->pdo->prepare("DELETE FROM earnings WHERE id = ?");
        $stmt->execute([$earning_id]);
    }

    public function get_user_earnings($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM earnings WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}



