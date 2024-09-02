<?php

declare(strict_types = 1);

//boolean function to determine if blocks were filled in
function is_input_empty(string $username, string $pwd, string $email) {
    if (empty($username) || empty($pwd) || empty($email)) {
        return true;
    } else {
        return false;
    }
}

//boolean function to determine if email is valid
function is_email_invalid(string $email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

//boolean function to determine if username is already reserved
function is_username_taken(object $pdo, string $username) {
    if (get_username($pdo, $username)) {
        return true;
    } else {
        return false;
    }
}

function is_email_registered(object $pdo, string $email) {
    if (get_email($pdo, $email)) {
        return true;
    } else {
        return false;
    }
}

function create_user(object $pdo, string $username, string $pwd, string $email) {
    set_user($pdo, $username, $pwd, $email);


}