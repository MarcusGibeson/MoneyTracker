<?php
session_start();
require_once 'configurations/dbh.inc.php';
require_once 'models/work_schedule_model.inc.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $data = json_decode(file_get_contents('php://input'), true);
    $selected_dates = $data['dates'];

    $work_schedule = new WorkSchedule($pdo);
    $total_wage = 0;

    foreach($selected_dates as $date) {
        //Fetch work data for each selected date
        $work_data = $workSchedule->fetchWorkDataByDate($user_id, $date);

        foreach($work_data as $work_day) {
            $start_time = strtotime($work_day['start_time']);
            $end_time = strtotime($work_day['end_time']);
            $break = $work_day['break_minutes'];
            $hourly_rate = $work_day['hourly_rate'];
            $overtime_rate = $work_day['overtime_rate'];

            $worked_seconds = ($end_time - $start_time) - ($break * 60);
            $worked_hours = $worked_seconds / 3600;

            $regular_hours = min($worked_hours, 8);
            $overtime_hours = max($worked_hours - 8, 0);

            $wage = ($regular_hours * $hourly_rate) + ($overtime_hours * $overtime_rate);
            $total_wage = $wage;
        }
    }

    echo json_encode(['totalWage' => number_format($total_wage, 2)]);

        

$tax_rate = 0.15; // Example tax rate of 15%
$taxes = $total_wage * $tax_rate;
$net_wage = $total_wage - $taxes;

echo "Total Wage: $" . number_format($total_wage, 2) . "<br>";
echo "Taxes: $" . number_format($taxes, 2) . "<br>";
echo "Net Wage: $" . number_format($net_wage, 2);
}