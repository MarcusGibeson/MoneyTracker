<?php

class WorkSchedule{

    private $pdo;

    public function __construct($pdo) {
        $this->pdo=$pdo;
    }

    
    public function getWorkedDays($user_id, $year, $month) {

        if($month <1 || $month > 12) {
            throw new InvalidArgumentException("Invalid month value: $month");
        }

        // Return an array of worked day dates (format: YYYY-MM-DD)
        $stmt = $this->pdo->prepare("
            SELECT work_date,
                end_time,
                start_time,
                hourly_rate
            FROM work_schedule 
            WHERE user_id = :user_id 
                AND YEAR(work_date) = :year 
                AND MONTH(work_date) = :month
        ");
        $stmt->execute(['user_id' => $user_id, 'year' => $year, 'month' => $month]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Returns an array of dates
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

    //update and exisiting work entry by day ID depending on what data has been submitted
    public function updateWorkEntry($dayId, $updatedData) {
        $fieldsToUpdate = [];
        $params = [':id' => $dayId];

        if (isset($updatedData['work_date'])) {
            $fieldsToUpdate[] = "work_date = :work_date";
            $params[':work_date'] = $updatedData['work_date'];
        }

        if (isset($updatedData['start_time'])) {
            $fieldsToUpdate[] = "start_time = :start_time";
            $params[':start_time'] = $updatedData['start_time'];
        }

        if (isset($updatedData['end_time'])) {
            $fieldsToUpdate[] = "end_time = :end_time";
            $params[':end_time'] = $updatedData['end_time'];
        }

        if (isset($updatedData['break_minutes'])) {
            $fieldsToUpdate[] = "break_minutes = :break_minutes";
            $params[':break_minutes'] = $updatedData['break_minutes'];
        }

        if (isset($updatedData['hourly_rate'])) {
            $fieldsToUpdate[] = "hourly_rate = :hourly_rate";
            $params[':hourly_rate'] = $updatedData['hourly_rate'];
        }

        if (isset($updatedData['overtime_rate'])) {
            $fieldsToUpdate[] = "overtime_rate = :overtime_rate";
            $params[':overtime_rate'] = $updatedData['overtime_rate'];
        }

        if(!empty($fieldsToUpdate)) {
            $sql = "UPDATE work_schedule SET " . implode(', ', $fieldsToUpdate) . " WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        }
        return false;
    }

    public function deleteWorkEntry($dayId) {
        $stmt = $this->pdo->prepare("
            DELETE FROM work_schedule WHERE id = :id
        ");
        if (!$stmt->execute([':id' => $dayId])) {
            $errorInfo = $stmt->errorInfo();
            error_log('Delete failed: ' . $errorInfo[2]);
            die('Delete failed: ' . $errorInfo[2]);
        }
        return true;
    }

    public function getDetailsForSelectedDays($selectedDates) {
        //Create placeholders for the query
        $placeholders = implode(',', array_fill(0, count($selectedDates), '?'));

        $sql = "
            SELECT id, work_date, start_time, end_time, break_minutes, hourly_rate, overtime_rate
            FROM work_schedule
            WHERE work_date IN ($placeholders)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($selectedDates);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}