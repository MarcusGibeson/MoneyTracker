<?php

function create_account($pdo, $user_id, $account_name, $account_type) {
    $stmt = $pdo->prepare("INSERT INTO accounts (user_id, account_name, account_type, balance, created_at) VALUES (?, ?, ?, 0, NOW())");
    $stmt->execute([$user_id, $account_name, $account_type]);
}

function is_account_exists($pdo, $user_id, $account_name) {
    $stmt = $pdo->prepare("SELECT 1 FROM accounts WHERE user_id = ? AND account_name = ?");
    $stmt->execute([$user_id, $account_name]);
    return $stmt->fetchColumn();
}

function get_user_accounts($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT account_name, account_type, balance FROM accounts WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}