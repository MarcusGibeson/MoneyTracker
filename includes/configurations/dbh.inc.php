<?php

$host = 'localhost';
$dbname = 'budget_tracker';
$dbusername = 'root';
$dbpassword = '';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}