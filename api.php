<?php
// MOC DQA Checklist API Backend
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$dataFile = 'dqa_data.json';

// Handle Options request (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Handle GET request - Load data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($dataFile)) {
        echo file_get_contents($dataFile);
    } else {
        echo json_encode(new stdClass(), JSON_UNESCAPED_UNICODE);
    }
    exit;
}

// Handle POST request - Save data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if it's a reset request
    if (isset($_GET['reset']) && $_GET['reset'] == '1') {
        if (file_exists($dataFile)) {
            unlink($dataFile);
        }
        echo json_encode(['status' => 'success', 'message' => 'ล้างข้อมูลทั้งหมดเรียบร้อยแล้ว'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $rawInput = file_get_contents('php://input');
    $decodedData = json_decode($rawInput, true);

    if ($decodedData === null) {
        $decodedData = $_POST;
    }

    // Read existing file data to merge, preventing overwriting other pages' data
    $existingData = [];
    if (file_exists($dataFile)) {
        $fileContent = file_get_contents($dataFile);
        $parsed = json_decode($fileContent, true);
        if (is_array($parsed)) {
            $existingData = $parsed;
        }
    }

    // Merge old and new data
    $mergedData = array_merge($existingData, $decodedData);

    $jsonContent = json_encode($mergedData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if (file_put_contents($dataFile, $jsonContent) !== false) {
        echo json_encode(['status' => 'success', 'message' => 'บันทึกข้อมูลสำเร็จ'], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลลงไฟล์ได้'], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

// Method not allowed
http_response_code(405);
echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed'], JSON_UNESCAPED_UNICODE);
?>
