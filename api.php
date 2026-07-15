<?php
// MOC DQA Checklist API Backend (MySQL Version - Multi-column)
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Include database connection
require_once 'db.php';

// Handle Options request (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Get list of allowed columns dynamically from the database structure
$allowedColumns = [];
$res = $conn->query("SHOW COLUMNS FROM submissions");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $allowedColumns[] = $row['Field'];
    }
}

// Handle GET request - Load data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = new stdClass();
    
    if (isset($_SESSION['current_submission_id'])) {
        $stmt = $conn->prepare("SELECT * FROM submissions WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['current_submission_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            // Remove internal system columns
            unset($row['created_at']);
            unset($row['updated_at']);
            $data = $row;
        } else {
            // If the row was dropped or doesn't exist, clean session
            unset($_SESSION['current_submission_id']);
        }
        $stmt->close();
    }
    
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Handle POST request - Save data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if it's a reset request
    if (isset($_GET['reset']) && $_GET['reset'] == '1') {
        unset($_SESSION['current_submission_id']);
        echo json_encode(['status' => 'success', 'message' => 'ล้างข้อมูลการประเมินปัจจุบันเรียบร้อยแล้ว'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $rawInput = file_get_contents('php://input');
    $decodedData = json_decode($rawInput, true);

    if ($decodedData === null) {
        $decodedData = $_POST;
    }

    // Determine status
    // If the request contains 'r1' (the final question of page 5), we mark it as submitted.
    $status = 'draft';
    if (isset($decodedData['r1'])) {
        $status = 'submitted';
    }

    $submissionId = isset($_SESSION['current_submission_id']) ? $_SESSION['current_submission_id'] : null;

    // Check if the record actually exists in the database to prevent orphaned session updates
    if ($submissionId) {
        $checkStmt = $conn->prepare("SELECT id FROM submissions WHERE id = ?");
        $checkStmt->bind_param("i", $submissionId);
        $checkStmt->execute();
        $checkRes = $checkStmt->get_result();
        if ($checkRes->num_rows === 0) {
            unset($_SESSION['current_submission_id']);
            $submissionId = null;
        }
        $checkStmt->close();
    }

    if ($submissionId) {
        // Update existing submission (Only update columns present in the POST data)
        $setParts = [];
        $bindTypes = "";
        $bindVals = [];
        
        foreach ($decodedData as $key => $value) {
            if (in_array($key, $allowedColumns) && !in_array($key, ['id', 'created_at', 'updated_at'])) {
                $setParts[] = "`$key` = ?";
                $bindTypes .= "s";
                $bindVals[] = ($value === '' || $value === null) ? null : (string)$value;
            }
        }
        
        // Also update status
        $setParts[] = "`status` = ?";
        $bindTypes .= "s";
        $bindVals[] = $status;
        
        if (!empty($setParts)) {
            $sql = "UPDATE submissions SET " . implode(", ", $setParts) . " WHERE id = ?";
            $bindTypes .= "i";
            $bindVals[] = $submissionId;
            
            $stmt = $conn->prepare($sql);
            
            // Create reference array for compatibility with older PHP/mysqli versions
            $bindParams = array($bindTypes);
            foreach ($bindVals as $k => $v) {
                $bindParams[] = &$bindVals[$k];
            }
            
            call_user_func_array(array($stmt, 'bind_param'), $bindParams);
            $success = $stmt->execute();
            $stmt->close();
        } else {
            $success = true;
        }
    } else {
        // Create new submission
        $cols = [];
        $placeholders = [];
        $bindTypes = "";
        $bindVals = [];
        
        foreach ($decodedData as $key => $value) {
            if (in_array($key, $allowedColumns) && !in_array($key, ['id', 'created_at', 'updated_at'])) {
                $cols[] = "`$key`";
                $placeholders[] = "?";
                $bindTypes .= "s";
                $bindVals[] = ($value === '' || $value === null) ? null : (string)$value;
            }
        }
        
        // Add status
        $cols[] = "`status`";
        $placeholders[] = "?";
        $bindTypes .= "s";
        $bindVals[] = $status;
        
        if (!empty($cols)) {
            $sql = "INSERT INTO submissions (" . implode(", ", $cols) . ") VALUES (" . implode(", ", $placeholders) . ")";
            $stmt = $conn->prepare($sql);
            
            // Create reference array for compatibility with older PHP/mysqli versions
            $bindParams = array($bindTypes);
            foreach ($bindVals as $k => $v) {
                $bindParams[] = &$bindVals[$k];
            }
            
            call_user_func_array(array($stmt, 'bind_param'), $bindParams);
            $success = $stmt->execute();
            if ($success) {
                $_SESSION['current_submission_id'] = $conn->insert_id;
            }
            $stmt->close();
        } else {
            $success = false;
        }
    }

    if ($success) {
        echo json_encode(['status' => 'success', 'message' => 'บันทึกข้อมูลลงฐานข้อมูลสำเร็จ'], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลลงฐานข้อมูลได้: ' . $conn->error], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

// Method not allowed
http_response_code(405);
echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed'], JSON_UNESCAPED_UNICODE);
?>
