<?php
// db.php
// Database connection file with auto-initialization for MOC DQA Checklist (Multi-column version)

$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = '';
$db_name = 'checklist';

// 1. Establish connection to MySQL server
$conn = @new mysqli($db_host, $db_user, $db_pass);

if ($conn->connect_error) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูล MySQL ได้ กรุณาเปิดใช้งาน XAMPP (MySQL) ก่อน: ' . $conn->connect_error
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Set charset to utf8mb4
$conn->set_charset('utf8mb4');

// 2. Create database if it does not exist
$createDbQuery = "CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (!$conn->query($createDbQuery)) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'ไม่สามารถสร้างฐานข้อมูลได้: ' . $conn->error
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 3. Select database
$conn->select_db($db_name);

// Check if the submissions table exists and is using the old 9-column schema (less than 20 columns)
// Or check if it is using the old VARCHAR(10) for g1 column
$tableCheck = $conn->query("SHOW TABLES LIKE 'submissions'");
if ($tableCheck && $tableCheck->num_rows > 0) {
    $columnsResult = $conn->query("SHOW COLUMNS FROM `submissions`");
    $numCols = $columnsResult ? $columnsResult->num_rows : 0;
    
    $needsRecreate = false;
    if ($numCols < 20) {
        $needsRecreate = true;
    } else {
        // Check if g1 is using varchar(10)
        $g1Check = $conn->query("SHOW COLUMNS FROM `submissions` LIKE 'g1'");
        if ($g1Check && $g1Row = $g1Check->fetch_assoc()) {
            if ($g1Row['Type'] === 'varchar(10)') {
                $needsRecreate = true;
            }
        }
    }
    
    if ($needsRecreate) {
        $conn->query("DROP TABLE IF EXISTS `submissions`");
    } else {
        // Check if column d5 exists, which means we are on the old schema and need to migrate it to d3/d4
        $d5Check = $conn->query("SHOW COLUMNS FROM `submissions` LIKE 'd5'");
        if ($d5Check && $d5Check->num_rows > 0) {
            // Rename d4 -> d3 and d5 -> d4 using CHANGE COLUMN for MySQL 5.7+ compatibility
            $conn->query("ALTER TABLE `submissions` CHANGE COLUMN `d4` `d3` VARCHAR(20) DEFAULT ''");
            $conn->query("ALTER TABLE `submissions` CHANGE COLUMN `d4_evidence` `d3_evidence` TEXT DEFAULT NULL");
            $conn->query("ALTER TABLE `submissions` CHANGE COLUMN `d5` `d4` VARCHAR(20) DEFAULT ''");
            $conn->query("ALTER TABLE `submissions` CHANGE COLUMN `d5_evidence` `d4_evidence` TEXT DEFAULT NULL");
        }
    }
}

// 4. Create submissions table with 150+ separate columns (optimized sizes to fit InnoDB page size limit)
$createTableQuery = "CREATE TABLE IF NOT EXISTS `submissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    
    -- ส่วนที่ 1: ข้อมูลทั่วไป (ใช้ TEXT หรือ VARCHAR ขนาดพอดีเพื่อเลี่ยง Row size limit)
    `info_title` TEXT DEFAULT NULL,
    `info_agency` VARCHAR(100) DEFAULT '',
    `info_mission` TEXT DEFAULT NULL,
    `metric_name` TEXT DEFAULT NULL,
    `metric_link` TEXT DEFAULT NULL,
    `metric_result` TEXT DEFAULT NULL,
    `metric_source` TEXT DEFAULT NULL,
    `source_partner` TEXT DEFAULT NULL,
    `source_period` VARCHAR(50) DEFAULT '',
    `metric_standard_type` VARCHAR(100) DEFAULT '',
    `metric_standard_detail` TEXT DEFAULT NULL,
    `eval_method` TEXT DEFAULT NULL,
    `eval_date` VARCHAR(50) DEFAULT '',
    `control_date` VARCHAR(50) DEFAULT '',
    `eval_team` TEXT DEFAULT NULL,
    `eval_approver` TEXT DEFAULT NULL,
    `info_service` TEXT DEFAULT NULL,
    `info_head` TEXT DEFAULT NULL,
    `status` VARCHAR(20) DEFAULT 'draft',
    
    -- ส่วนที่ 2: ความถูกต้องและสมบูรณ์ (Accuracy & Completeness)
    `ac1_status` VARCHAR(10) DEFAULT '',
    `ac1_comment` TEXT DEFAULT NULL,
    `ac2_status` VARCHAR(10) DEFAULT '',
    `ac2_comment` TEXT DEFAULT NULL,
    `ac3_status` VARCHAR(10) DEFAULT '',
    `ac3_comment` TEXT DEFAULT NULL,
    `ac4_status` VARCHAR(10) DEFAULT '',
    `ac4_comment` TEXT DEFAULT NULL,
    `ac5_status` VARCHAR(10) DEFAULT '',
    `ac5_comment` TEXT DEFAULT NULL,
    `ac6_status` VARCHAR(10) DEFAULT '',
    `ac6_comment` TEXT DEFAULT NULL,
    `ac7_status` VARCHAR(10) DEFAULT '',
    `ac7_comment` TEXT DEFAULT NULL,
    `ac8_status` VARCHAR(10) DEFAULT '',
    `ac8_comment` TEXT DEFAULT NULL,

    -- ส่วนที่ 2: ตรงตามความต้องการของผู้ใช้ (Relevance)
    `re1_status` VARCHAR(10) DEFAULT '',
    `re1_comment` TEXT DEFAULT NULL,
    `re2_status` VARCHAR(10) DEFAULT '',
    `re2_comment` TEXT DEFAULT NULL,
    `re3_status` VARCHAR(10) DEFAULT '',
    `re3_comment` TEXT DEFAULT NULL,
    `re4_status` VARCHAR(10) DEFAULT '',
    `re4_comment` TEXT DEFAULT NULL,
    `re5_status` VARCHAR(10) DEFAULT '',
    `re5_comment` TEXT DEFAULT NULL,

    -- ส่วนที่ 2: ความสอดคล้องกัน (Consistency)
    `co1_status` VARCHAR(10) DEFAULT '',
    `co1_comment` TEXT DEFAULT NULL,
    `co2_status` VARCHAR(10) DEFAULT '',
    `co2_comment` TEXT DEFAULT NULL,
    `co3_status` VARCHAR(10) DEFAULT '',
    `co3_comment` TEXT DEFAULT NULL,
    `co4_status` VARCHAR(10) DEFAULT '',
    `co4_comment` TEXT DEFAULT NULL,

    -- ส่วนที่ 2: ความเป็นปัจจุบัน (Timeliness)
    `ti1_status` VARCHAR(10) DEFAULT '',
    `ti1_comment` TEXT DEFAULT NULL,
    `ti2_status` VARCHAR(10) DEFAULT '',
    `ti2_comment` TEXT DEFAULT NULL,
    `ti3_status` VARCHAR(10) DEFAULT '',
    `ti3_comment` TEXT DEFAULT NULL,
    `ti4_status` VARCHAR(10) DEFAULT '',
    `ti4_comment` TEXT DEFAULT NULL,
    `ti5_status` VARCHAR(10) DEFAULT '',
    `ti5_comment` TEXT DEFAULT NULL,

    -- ส่วนที่ 2: ความพร้อมใช้ (Availability)
    `av1_status` VARCHAR(10) DEFAULT '',
    `av1_comment` TEXT DEFAULT NULL,
    `av2_status` VARCHAR(10) DEFAULT '',
    `av2_comment` TEXT DEFAULT NULL,
    `av3_status` VARCHAR(10) DEFAULT '',
    `av3_comment` TEXT DEFAULT NULL,
    `av4_status` VARCHAR(10) DEFAULT '',
    `av4_comment` TEXT DEFAULT NULL,

    -- ส่วนที่ 3: คะแนนการประเมินตนเอง (Self-Assessment Ratings)
    `sa_1_1` VARCHAR(5) DEFAULT '',
    `sa_1_2` VARCHAR(5) DEFAULT '',
    `sa_1_3` VARCHAR(5) DEFAULT '',
    `sa_1_4` VARCHAR(5) DEFAULT '',
    `sa_1_5` VARCHAR(5) DEFAULT '',
    `sa_2_1` VARCHAR(5) DEFAULT '',
    `sa_2_2` VARCHAR(5) DEFAULT '',
    `sa_2_3` VARCHAR(5) DEFAULT '',
    `sa_2_4` VARCHAR(5) DEFAULT '',
    `sa_2_5` VARCHAR(5) DEFAULT '',
    `sa_2_6` VARCHAR(5) DEFAULT '',
    `sa_3_1` VARCHAR(5) DEFAULT '',
    `sa_3_2` VARCHAR(5) DEFAULT '',
    `sa_4_1` VARCHAR(5) DEFAULT '',
    `sa_4_2` VARCHAR(5) DEFAULT '',
    `sa_4_3` VARCHAR(5) DEFAULT '',
    `sa_4_4` VARCHAR(5) DEFAULT '',
    `sa_5_1` VARCHAR(5) DEFAULT '',
    `sa_5_2` VARCHAR(5) DEFAULT '',
    `sa_5_3` VARCHAR(5) DEFAULT '',
    `sa_5_4` VARCHAR(5) DEFAULT '',
    `sa_5_5` VARCHAR(5) DEFAULT '',

    -- ส่วนที่ 4 และ 5: ผลการประเมินและหลักฐานอ้างอิง (ปรับเพิ่มความกว้าง VARCHAR เป็น 20 เพื่อให้เก็บ 'มีอย่างเหมาะสม' ขนาด 14 ตัวอักษรได้ครบถ้วน)
    `g1` VARCHAR(20) DEFAULT '',
    `g1_evidence` TEXT DEFAULT NULL,
    `g2` VARCHAR(20) DEFAULT '',
    `g2_evidence` TEXT DEFAULT NULL,
    `g3` VARCHAR(20) DEFAULT '',
    `g3_evidence` TEXT DEFAULT NULL,
    `g4` VARCHAR(20) DEFAULT '',
    `g4_evidence` TEXT DEFAULT NULL,
    `g5` VARCHAR(20) DEFAULT '',
    `g5_evidence` TEXT DEFAULT NULL,
    `g6` VARCHAR(20) DEFAULT '',
    `g6_evidence` TEXT DEFAULT NULL,
    `g7` VARCHAR(20) DEFAULT '',
    `g7_evidence` TEXT DEFAULT NULL,

    `p1` VARCHAR(20) DEFAULT '',
    `p1_evidence` TEXT DEFAULT NULL,
    `p2` VARCHAR(20) DEFAULT '',
    `p2_evidence` TEXT DEFAULT NULL,
    `p3` VARCHAR(20) DEFAULT '',
    `p3_evidence` TEXT DEFAULT NULL,
    `p4` VARCHAR(20) DEFAULT '',
    `p4_evidence` TEXT DEFAULT NULL,
    `p5` VARCHAR(20) DEFAULT '',
    `p5_evidence` TEXT DEFAULT NULL,
    `p6` VARCHAR(20) DEFAULT '',
    `p6_evidence` TEXT DEFAULT NULL,
    `p7` VARCHAR(20) DEFAULT '',
    `p7_evidence` TEXT DEFAULT NULL,

    `s1` VARCHAR(20) DEFAULT '',
    `s1_evidence` TEXT DEFAULT NULL,
    `s2` VARCHAR(20) DEFAULT '',
    `s2_evidence` TEXT DEFAULT NULL,
    `s3` VARCHAR(20) DEFAULT '',
    `s3_evidence` TEXT DEFAULT NULL,
    `s4` VARCHAR(20) DEFAULT '',
    `s4_evidence` TEXT DEFAULT NULL,
    `s5` VARCHAR(20) DEFAULT '',
    `s5_evidence` TEXT DEFAULT NULL,
    `s6` VARCHAR(20) DEFAULT '',
    `s6_evidence` TEXT DEFAULT NULL,
    `s7` VARCHAR(20) DEFAULT '',
    `s7_evidence` TEXT DEFAULT NULL,
    `s8` VARCHAR(20) DEFAULT '',
    `s8_evidence` TEXT DEFAULT NULL,
    `s9` VARCHAR(20) DEFAULT '',
    `s9_evidence` TEXT DEFAULT NULL,

    `e1` VARCHAR(20) DEFAULT '',
    `e1_evidence` TEXT DEFAULT NULL,
    `e2` VARCHAR(20) DEFAULT '',
    `e2_evidence` TEXT DEFAULT NULL,
    `e3` VARCHAR(20) DEFAULT '',
    `e3_evidence` TEXT DEFAULT NULL,
    `e4` VARCHAR(20) DEFAULT '',
    `e4_evidence` TEXT DEFAULT NULL,

    `d1` VARCHAR(20) DEFAULT '',
    `d1_evidence` TEXT DEFAULT NULL,
    `d2` VARCHAR(20) DEFAULT '',
    `d2_evidence` TEXT DEFAULT NULL,
    `d3` VARCHAR(20) DEFAULT '',
    `d3_evidence` TEXT DEFAULT NULL,
    `d4` VARCHAR(20) DEFAULT '',
    `d4_evidence` TEXT DEFAULT NULL,

    `r1` VARCHAR(20) DEFAULT '',
    `r1_evidence` TEXT DEFAULT NULL,

    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC";

if (!$conn->query($createTableQuery)) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'ไม่สามารถสร้างตารางข้อมูลใน MySQL ได้: ' . $conn->error
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 5. Create agencies table if not exists
$createAgenciesTableQuery = "CREATE TABLE IF NOT EXISTS `agencies` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($createAgenciesTableQuery)) {
    // Check if table is empty, insert default list
    $checkEmpty = $conn->query("SELECT COUNT(*) as count FROM agencies");
    if ($checkEmpty && $checkEmpty->fetch_assoc()['count'] == 0) {
        $defaults = [
            "กองกลาง (กก.)",
            "กองตรวจราชการ (กตร.)",
            "กองบริหารการคลัง (กบค.)",
            "กองบริหารการพาณิชย์ภูมิภาค (กบภ.)",
            "กองบริหารทรัพยากรบุคคล (กบบ.)",
            "กองยุทธศาสตร์และแผนงาน (กยผ.)",
            "ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร (ศทส.)",
            "สถาบันกรมพระจันทบุรีนฤนาถ (สจป.)",
            "กลุ่มกฎหมาย (กม.)",
            "กลุ่มตรวจสอบภายใน (กตน.)",
            "กลุ่มพัฒนาระบบบริหาร (กพร.)",
            "ศูนย์ปฏิบัติการต่อต้านการทุจริต (ศปท.)"
        ];
        $stmt = $conn->prepare("INSERT INTO agencies (name) VALUES (?)");
        if ($stmt) {
            foreach ($defaults as $name) {
                $stmt->bind_param("s", $name);
                $stmt->execute();
            }
            $stmt->close();
        }
    }
}

