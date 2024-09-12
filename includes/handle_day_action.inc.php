<?php

require_once 'configurations/dbh.inc.php';
require_once 'controllers/work_schedule_contr.inc.php'; 

$controller = new WorkScheduleController($pdo);

header('Content-Type: application/json');


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Get raw POST body and decode the JSON
    $input = json_decode(file_get_contents('php://input'), true);

    //Handle fetching day details
    if (isset($input['selectedDates'])) {
        $selectedDates = $input['selectedDates'];
        $dayDetails = $controller->getDetailsForSelectedDays($selectedDates);
        if($dayDetails) {
            echo json_encode(['details' => $dayDetails]);
        }else {
            echo json_encode([]);
        }
        
        exit;
    }

    //Handle editing a work entry
    if (isset($input['dayId']) && $input['action'] === 'edit' && isset($input['editedData'])) {
        $dayId = $input['dayId'];
        $editedData = $input['editedData'];
        $success = $controller->updateWorkEntry($dayId, $editedData);
        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Entry updated sucessful']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
        }
        
        return;
    }

    //Handle deleting a work entry
    if (isset($input['action']) && $input['action'] === 'delete' && isset($input['dayId'])) {
        $dayId = $input['dayId'];
        $controller = new WorkScheduleController($pdo);
        $result = $controller->deleteWorkEntry($dayId);
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Entry deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete entry']);
        }
        return;
    }


    //If no valid action, return an error message
    echo json_encode(['error' => 'Invalid action']);
    return;
} else {
    //If the request is not a POST
    echo json_encode(['error' => 'Invalid request method']);
}