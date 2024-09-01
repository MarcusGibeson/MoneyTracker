<?php

if($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $pwd = $_POST["pwd"];

    try {
        require_once 'configurations/dbh.inc.php';
        require_once 'models/login_model.inc.php';
        require_once 'controllers/login_contr.inc.php';

        //Error handlers

        require_once 'configurations/config_session.inc.php';


    } catch (PDOException $e) {
        die("Sign-in failed: " . $e->getMessage());
    }
} else {
    header("Location:../index.php");
    die();
}