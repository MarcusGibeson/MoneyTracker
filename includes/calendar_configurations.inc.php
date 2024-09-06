<?php

require_once 'configurations/dbh.inc.php';
require_once 'controllers/work_schedule_contr.inc.php';

session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();

}

$user_id = $_SESSION['user_id'];
$controller = new WorkScheduleController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Handle form submission to add work schedule entries
    $controller->addWorkScheduleEntry();
} else {
    //Display the calender view
    $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
    $month = isset($_GET['month']) ? intval($_GET['month']) : date('Y');

    $worked_days = $controller->getWorkedDays($user_id, $year, $month); 

    // Ensure $worked_days is an array
    if (!is_array($worked_days)) {
        $worked_days = [];
    }

    //Fetch work data and show calendar
    $controller->showCalendar($user_id, $year, $month);
}