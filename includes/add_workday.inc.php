<?php
session_start();
require_once 'configurations/dbh.inc.php';
require_once 'models/work_schedule_model.inc.php';


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $work_dates = $_POST['work_date'];
    $start_times = $_POST['start_time'];
    $end_times = $_POST['end_time'];
    $break_minutes = $_POST['break_minutes'];
    $hourly_rate = $_POST['hourly_rate'];
    $overtime_rate = $_POST['overtime_rate'];

   $workScheduleModel = new WorkSchedule($pdo); 

    foreach($work_dates as $index => $work_date) {
        //Get the corresponding values for each day
        $start_time = $start_times[$index];
        $end_time = $end_times[$index];
        $break_minute = $break_minutes[$index];

        $success = $workScheduleModel->addWorkSchedule(
            $user_id,
            $work_date,
            $start_time,
            $end_time,
            $break_minute,
            $hourly_rate,
            $overtime_rate
        );

        //if adding the work schedule fails, show error
        if(!$success) {
            echo "Error: Failed to add work schedule for date $work_date.";
            exit();
        }
    }

    //Redirect user after successful 
    header("Location: ../index.php");
    exit();
}
else {
    //illegal entry
    header("Location: ../index.php");
    exit();
}