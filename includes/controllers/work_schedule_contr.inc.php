<?php
require_once __DIR__ . '/../models/work_schedule_model.inc.php';

class WorkScheduleController{
    private $workScheduleModel;

    public function __construct($pdo) {
        $this->workScheduleModel = new WorkSchedule($pdo);
    }

    public function getWorkedDays($user_id, $year, $month) {
        $worked_days = $this->workScheduleModel->getWorkedDays($user_id, $year, $month);
        return $worked_days;
    }

    function calculateTimeWorked($worked_days) {
        $results=[];
        foreach($worked_days as $work_data) {
            $start_time = new DateTime($work_data['start_time']);
            $end_time = new DateTime($work_data['end_time']);
            $interval = $start_time->diff($end_time);
    
            $time_worked = $interval->format('%H:%I:%S');
    
            $results[] = [
                'work_date' => $work_data['work_date'],
                'time_worked' => $time_worked,
                'hourly_rate' => $work_data['hourly_rate']
            ];       
        }
        return $results;
    }

    //Display calender view with worked days highlighted
    public function showCalendar($user_id, $year, $month) {
        $work_data = $this->workScheduleModel->fetchWorkData($user_id, $year, $month);
        $worked_days = $work_data;
        $worked_days = $this->calculateTimeWorked($worked_days);
        include __DIR__ . '/../views/calender.php';
    }

    public function addWorkScheduleEntry() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            if (!isset($_SESSION['user_id'])) {
                //Handle unauthenticated access
                header("Location: ../index.php");
                exit();
            }

            $user_id = $_SESSION['user_id'];
            $dates = $_POST['date'];
            $start_times = $_POST['start_time'];
            $end_times = $_POST['end_time'];
            $break_minutes = $_POST['break_minutes'];
            $hourly_rate = floatval($_POST['hourly_rate']);
            $overtime_rate = floatval($_POST['overtime_rate']);

            $total_wage = 0;
            for ($i = 0; $i < count($dates); $i++) {
                $work_date = $dates[$i];
                $start_time = $start_times[$i];
                $end_time = $end_times[$i];
                $break = intval($break_minutes[$i]);

                //Calculate total hours worked
                $start_timestamp = strtotime($start_time);
                $end_timestamp = strtotime($end_time);
                $worked_seconds = ($end_timestamp - $start_timestamp) - ($break * 60);
                $worked_hours = $worked_seconds / 3600; 

                //Handle cases where the end time is past midnight
                if ($worked_hours < 0) {
                    $worked_hours += 24;
                }

                //Calculate regular and overtime hours
                $regular_hours = min($worked_hours, 8);
                $overtime_hours = max($worked_hours - 8, 0);

                //Calculate wage
                $wage = ($regular_hours * $hourly_rate) + ($overtime_hours * $overtime_rate);
                $total_wage += $wage;

                //Insert into database
                $this->workScheduleModel->addWorkSchedule(
                    $user_id,
                    $work_date,
                    date('H:i:s', $start_timestamp),
                    date('H:i:s', $end_timestamp),
                    $break,
                    $hourly_rate,
                    $overtime_rate
                );
            }
        }
    }

    //Fetch details for the selected days
    public function getDetailsForSelectedDays($selectedDates, $user_id) {
        error_log("Fetching details for selected dates " . json_encode($selectedDates));
        return $this->workScheduleModel->getDetailsForSelectedDays($selectedDates, $user_id);
    }

    //Update work entry for specific day
    public function updateWorkEntry($dayId, $updatedData) {
        return $this->workScheduleModel->updateWorkEntry($dayId, $updatedData);
    }

    //Delete a specific work entry
    public function deleteWorkEntry($dayId) {
        error_log("Deleting work entry with ID: $dayId");
        return $this->workScheduleModel->deleteWorkEntry($dayId);
    }

}