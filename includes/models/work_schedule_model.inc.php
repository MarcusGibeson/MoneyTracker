<?php

class WorkSchedule{

    private $pdo;

    public function __construct($pdo) {
        $this->pdo=$pdo;
    }

    public function getWorkedDays($user_id, $year, $month) {
        // Return an array of worked day dates (format: YYYY-MM-DD)
        $stmt = $this->pdo->prepare("SELECT work_date FROM work_schedule WHERE user_id = :user_id AND YEAR(work_date) = :year AND MONTH(work_date) = :month");
        $stmt->execute(['user_id' => $user_id, 'year' => $year, 'month' => $month]);
    
        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Returns an array of dates
    }

    //Fetch work data for a specific user, year, and month
    public function fetchWorkData($user_id, $year, $month) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM work_schedule
            WHERE user_id = :user_id
                AND YEAR(work_date) = :year
                AND MONTH(work_date) = :month
        ");
        $stmt->execute([
            ':user_id' => $user_id,
            ':year' => $year,
            ':month' => $month
        ]);
        $work_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $work_data;
    }

    public function addWorkSchedule($user_id, $work_date, $start_time, $end_time, $break_minutes, $hourly_rate, $overtime_rate) {
        $stmt = $this->pdo->prepare("
            INSERT INTO work_schedule
            (user_id, work_date, start_time, end_time, break_minutes, hourly_rate, overtime_rate)
            VALUES
            (:user_id, :work_date, :start_time, :end_time, :break_minutes, :hourly_rate, :overtime_rate)
        ");
        return $stmt->execute([
            ':user_id' => $user_id,
            ':work_date' => $work_date,
            ':start_time' => $start_time,
            ':end_time' => $end_time,
            ':break_minutes' => $break_minutes,
            ':hourly_rate' => $hourly_rate,
            ':overtime_rate' => $overtime_rate
        ]);
    }

    public function fetchWorkDataByDate($user_id, $work_date) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM work_schedule
            WHERE user_id = :user_id AND work_date = :work_date
        ");
        $stmt->execute([':user_id' => $user_id, ':work_date' => $work_date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}