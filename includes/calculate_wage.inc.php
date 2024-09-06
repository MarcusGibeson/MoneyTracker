<?php
session_start();
require_once 'configurations/dbh.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $work_dates = $_POST['work_date'];
    $start_times = $_POST['start_time'];
    $end_times = $_POST['end_time'];
    $break_minutes = $_POST['break_minutes'];
    $hourly_rate = $_POST['hourly_rate'];
    $overtime_rate = $_POST['overtime_rate'];

    $total_wage = 0;

    for ($i = 0; $i < count($work_dates); $i++) {
        $start_time = strtotime($start_times[$i]);
        $end_time = strtotime($end_times[$i]);
        $break = $break_minutes[$i];

        // Calculate total hours worked
        $worked_seconds = ($end_time - $start_time) - ($break * 60);
        $worked_hours = $worked_seconds / 3600;

        // Check for overtime
        $regular_hours = min($worked_hours, 8);
        $overtime_hours = max($worked_hours - 8, 0);

        // Calculate wage
        $wage = ($regular_hours * $hourly_rate) + ($overtime_hours * $overtime_rate);

        // Add to total wage
        $total_wage += $wage;

        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO wage_calculator (user_id, work_date, start_time, end_time, break_minutes, hourly_rate, overtime_rate) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $work_dates[$i], date('H:i:s', $start_time), date('H:i:s', $end_time), $break, $hourly_rate, $overtime_rate]);
    }

    // Output the total wage
    echo "Total Wage: $" . number_format($total_wage, 2);

    

$tax_rate = 0.15; // Example tax rate of 15%
$taxes = $total_wage * $tax_rate;
$net_wage = $total_wage - $taxes;

echo "Total Wage: $" . number_format($total_wage, 2) . "<br>";
echo "Taxes: $" . number_format($taxes, 2) . "<br>";
echo "Net Wage: $" . number_format($net_wage, 2);
}
