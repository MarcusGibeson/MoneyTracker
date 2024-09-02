<?php

//did user access page legitimately?
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["username"];
    $pwd = $_POST["pwd"];
    $email= $_POST["email"];

    try {
        require_once 'configurations/dbh.inc.php';
        require_once 'models/signup_model.inc.php';
        require_once 'controllers/signup_contr.inc.php';


        // ERROR HANDLERS
        $errors = [];
        if (is_input_empty($username, $pwd, $email)) {
            $errors["empty_input"]= "Fill in all fields!";

        }
        if(is_email_invalid($email)) {
            $errors["invalid_email"] = "Invalid email address";
        }
        if(is_username_taken($pdo, $username)) {
            $errors["username_taken"] = "Username already taken!";
        }
        if (is_email_registered($pdo, $email)) {
            $errors["email_used"] = "Email already registered!";
        }

        require_once 'configurations/config_session.inc.php';

        if($errors) {
            $_SESSION["errors_signup"] = $errors;

            $signupData = [
                "username" => $username,
                "email" => $email
            ];
            $_SESSION["signup_data"] = $signupData;

            header("Location: ../index.php");
            die();
        }

        create_user($pdo, $username, $pwd, $email);

        header("Location:../index.php?signup=success");
        $pdo = null;
        $stmt = null;

        die();


    } catch (PDOException $e) {
        die("Query failed".$e->getMessage());
    }


} else {
    //if not send them back
    header("Location:../index.php");
}