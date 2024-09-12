<?php
session_start();
require_once 'includes/configurations/dbh.inc.php';
require_once 'includes/controllers/work_schedule_contr.inc.php'; 

$user_id = $_SESSION['user_id'];
$workScheduleContr = new WorkScheduleController($pdo);

//Fetch selected dates from POST request
$selectedDates = $_POST['dates'] ?? [];


//Get details from selected dates
$details = $workScheduleContr->getDetailsForSelectedDays($selectedDates);



//Output details to HTML
echo '<table>';
foreach($details as $detail) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($detail['date']) . '</td>';
    echo '<td>' . htmlspecialchars($detail['start_time']) . '</td>';
    echo '<td>' . htmlspecialchars($detail['end_time']) . '</td>';
    echo '<td>' . htmlspecialchars($detail['hourly_rate']) . '</td>';
    echo '<td>' . htmlspecialchars($detail['overtime_rate']) . '</td>';
    echo '<td><button onclick="editEntry(' . htmlspecialchars($detail['id']) . ')"> Edit </button></td>';
    echo '<td><button onclick="deleteEntry(' . htmlspecialchars($detail['id']) . ')"> Delete </button></td>';
    echo '</tr>';
}
echo '</table>';

