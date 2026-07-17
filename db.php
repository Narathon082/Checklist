<?php
// db.php
// Database connection file with auto-initialization for MOC DQA Checklist (Multi-column version)

$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = '';
$db_name = 'checklist';

// 1. Establish connection to MySQL server
$conn = @new mysqli($db_host, $db_user, $db_pass, null);

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

// 6. Create form_config_metadata table if not exists
$createConfigMetaTable = "CREATE TABLE IF NOT EXISTS `form_config_metadata` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) UNIQUE NOT NULL,
    `setting_value` TEXT DEFAULT NULL,
    `category` VARCHAR(50) DEFAULT 'general',
    `label` VARCHAR(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($createConfigMetaTable)) {
    $checkEmptyMeta = $conn->query("SELECT COUNT(*) as count FROM `form_config_metadata`");
    if ($checkEmptyMeta && $checkEmptyMeta->fetch_assoc()['count'] == 0) {
        $defaultMetadata = [
            [
                'setting_key' => 'general_title',
                'setting_value' => 'แบบตรวจประเมินคุณภาพ (DQA Checklist) - รวมทุกขั้นตอน',
                'category' => 'general',
                'label' => 'ชื่อระบบหลัก (แสดงบนเบราว์เซอร์)'
            ],
            [
                'setting_key' => 'general_agency_title',
                'setting_value' => 'สำนักงานปลัดกระทรวงพาณิชย์',
                'category' => 'general',
                'label' => 'ชื่อหน่วยงานในหัวข้อเว็บ'
            ],
            [
                'setting_key' => 'step1_instruction_title',
                'setting_value' => '(ร่าง) แบบตรวจประเมินคุณภาพข้อมูล (DQA Checklist)',
                'category' => 'step1',
                'label' => 'หัวข้อคำชี้แจงขั้นตอนที่ 1'
            ],
            [
                'setting_key' => 'step1_instruction_text',
                'setting_value' => '<strong>คำชี้แจง :</strong> การตรวจประเมินคุณภาพข้อมูล (DQA Checklist) นี้จัดทำขึ้นเพื่อแนะนำเครื่องมือสำหรับ ทีมผู้ประเมินคุณภาพข้อมูล เพื่อใช้ดำเนินการประเมินคุณภาพข้อมูลขององค์กรให้สมบูรณ์ ด้วยการใช้งานแบบตรวจประเมินคุณภาพข้อมูล (DQA Checklist) ซึ่งมีรายละเอียดที่จะช่วยให้การตรวจสอบกระบวนการเตรียมข้อมูลและคุณภาพข้อมูลใน 5 มิติ ได้แก่ ความถูกต้องและสมบูรณ์ (Accuracy and Completeness) ความสอดคล้องกัน (Consistency) ความเป็นปัจจุบัน (Timeliness) ตรงตามความต้องการของผู้ใช้ (Relevancy) ความพร้อมใช้ (Availability) ดังนี้',
                'category' => 'step1',
                'label' => 'เนื้อหาคำชี้แจงขั้นตอนที่ 1'
            ],
            [
                'setting_key' => 'step1_remark_title',
                'setting_value' => 'หมายเหตุ',
                'category' => 'step1',
                'label' => 'หัวข้อหมายเหตุท้ายขั้นตอนที่ 1'
            ],
            [
                'setting_key' => 'step1_remark_text',
                'setting_value' => '<strong>หน้าที่กระทรวงพาณิชย์</strong> มีอำนาจหน้าที่เกี่ยวกับการค้า ธุรกิจบริการ ทรัพย์สินทางปัญญา และราชการอื่นตามที่มีกฎหมายกำหนดให้เป็นอำนาจหน้าที่ ของกระทรวงพาณิชย์ หรือส่วนราชการที่สังกัดกระทรวงพาณิชย์<br><br><span class="remarks-subsection-title">ภารกิจด้านในประเทศ :</span><ol class="remarks-list"><li>การดูแลราคาสินค้าเกษตรและรายได้เกษตรกร</li><li>ดูแลผู้บริโภคภายใต้กรอบกฎหมายของกระทรวงพาณิชย์</li><li>ส่งเสริมและพัฒนาธุรกิจการค้า ทั้งการค้าสินค้าและธุรกิจบริการ</li><li>คุ้มครองด้านทรัพย์สินทางปัญญา</li></ol><br><span class="remarks-subsection-title">ภารกิจด้านต่างประเทศ :</span><ol class="remarks-list"><li>เจรจาการค้าระหว่างประเทศ ซึ่งประกอบด้วยการเจรจาภายใต้กรอบ WTO FTA อนุภูมิภาค ภูมิภาค ฯลฯ</li><li>จัดระเบียบและบริหารการนำเข้าส่งออก รวมทั้งการขายข้าวรัฐต่อรัฐ การค้ามันสำปะหลัง สินค้าข้อตกลงต่างๆ</li><li>แก้ไขปัญหาและรักษาผลประโยชน์ทางการค้า เช่น การดูแลเรื่อง GSP การเก็บภาษีตอบโต้การทุ่มตลาด</li><li>ส่งเสริมและเร่งรัดการส่งออก</li></ol>',
                'category' => 'step1',
                'label' => 'เนื้อหาหมายเหตุท้ายขั้นตอนที่ 1'
            ],
            [
                'setting_key' => 'step2_title',
                'setting_value' => 'มิติคุณภาพข้อมูล',
                'category' => 'step2',
                'label' => 'หัวข้อหลักขั้นตอนที่ 2'
            ],
            [
                'setting_key' => 'step2_desc_ac',
                'setting_value' => 'ข้อมูลมีความถูกต้องแม่นยำสูง หรือถ้ามีความคลาดเคลื่อนอยู่บ้าง ควรที่จะสามารถควบคุมขนาดของความคลาดเคลื่อนให้มีน้อยที่สุด และมีการตรวจสอบค่าความคลาดเคลื่อนของข้อมูลในส่วนต่าง ๆ ในทุกขั้นตอน ข้อมูลควรแสดงผลลัพธ์ที่คาดหวังไว้อย่างชัดเจนและเพียงพอ และควรถูกกำหนดโดยแหล่งที่มาดั้งเดิมของข้อมูล รวมทั้งข้อมูลที่จัดเตรียมควรมีความครบถ้วนตรงตามคุณลักษณะของข้อมูลที่คาดหวังและองค์ประกอบข้อมูลที่จำเป็นทั้งหมดที่ถูกจัดเก็บในระบบฐานข้อมูล',
                'category' => 'step2',
                'label' => 'คำอธิบายมิติ ความถูกต้องและสมบูรณ์'
            ],
            [
                'setting_key' => 'step2_desc_re',
                'setting_value' => 'ข้อมูลที่จัดทำขึ้นมาเป็นข้อมูลที่ผู้ใช้ต้องการ หรือเป็นข้อมูลที่จำเป็นต้องทราบ มีมุมมองและความละเอียดเพียงพอต่อนำไปใช้งาน ข้อมูลสามารถนำไปประยุกต์ใช้และเป็นประโยชน์สำหรับการดำเนินงาน/ภารกิจของหน่วยงาน และข้อมูลมีรายละเอียดในระดับเพียงพอที่จะอนุญาตให้ใช้เป็นข้อมูลประกอบการตัดสินใจในการบริหารจัดการ',
                'category' => 'step2',
                'label' => 'คำอธิบายมิติ ตรงตามความต้องการของผู้ใช้'
            ],
            [
                'setting_key' => 'step2_desc_co',
                'setting_value' => 'ข้อมูลมีความสอดคล้องต่อเนื่องในเชิงการจัดเก็บ จัดทํา และเผยแพร่ (ข้อมูลควรสะท้อนถึงกระบวนการจัดเก็บข้อมูลและวิธีการวิเคราะห์ที่เสถียรและมีสอดคล้องกันอย่างช่วงเวลา) รวมทั้งความสามารถในการนำไปเปรียบเทียบกับข้อมูลเดียวกันในอดีต และข้อมูลอื่นในชวงเวลาเดียวกันได้อย่างกว้างขวางและสอดคล้อง โดยความสอดคล้องนี้จะเกิดจากการใช้แนวคิด การจัดหมวดหมู่ การคัดเลือกประชากรและวิธีการจัดทําด้วยวิธีทางสถิติที่เป็นมาตรฐาน',
                'category' => 'step2',
                'label' => 'คำอธิบายมิติ ความสอดคล้องกัน'
            ],
            [
                'setting_key' => 'step2_desc_ti',
                'setting_value' => 'ความทันเวลาต่อการใช้งานของข้อมูล ไม่ว่าจะเป็นการนําไปใช้ต่อในแง่การประมวลผลหรือการเผยแพร่ข้อมูล ความทันเวลาอ้างอิงจากความล่าช้าของข้อมูลซึ่งวัดได้หลายลักษณะขึ้นอยู่กับประเภทของข้อมูล เช่น วัดจากระยะเวลาที่ได้รับข้อมูลจนถึงเวลาที่ข้อมูลพร้อม ใช้งาน วัดจากระยะเวลาที่กําหนดของการเผยแพร่กับเวลาที่สามารถเผยแพร่ได้จริง',
                'category' => 'step2',
                'label' => 'คำอธิบายมิติ ความเป็นปัจจุบัน'
            ],
            [
                'setting_key' => 'step2_desc_av',
                'setting_value' => 'ข้อมูลควรเข้าถึงได้ง่าย สามารถใช้งานได้จริง และสามารถใช้งานได้ตลอดเวลา',
                'category' => 'step2',
                'label' => 'คำอธิบายมิติ ความพร้อมใช้'
            ],
            [
                'setting_key' => 'step4_title_G',
                'setting_value' => 'ด้านการปรับปรุงการจัดทำธรรมาภิบาลและการจัดการคุณภาพข้อมูล',
                'category' => 'step4',
                'label' => 'หัวข้อด้านธรรมาภิบาลข้อมูล (G)'
            ],
            [
                'setting_key' => 'step4_title_P',
                'setting_value' => 'ด้านการปรับปรุงการเตรียมความพร้อมการรักษาความปลอดภัยและความเป็นส่วนตัวของข้อมูล',
                'category' => 'step4',
                'label' => 'หัวข้อด้านความปลอดภัยและความเป็นส่วนตัว (P)'
            ],
            [
                'setting_key' => 'step4_title_S',
                'setting_value' => 'ด้านการปรับปรุงระบบเทคโนโลยีสารสนเทศและการสื่อสารเพื่อสนับสนุนการให้บริการ',
                'category' => 'step4',
                'label' => 'หัวข้อด้านเทคโนโลยีและการใช้ข้อมูล (S)'
            ],
            [
                'setting_key' => 'step4_title_E',
                'setting_value' => 'ด้านการพัฒนาความรู้ ทักษะ และความสามารถของบุคลากรเพื่อรักษาคุณภาพข้อมูลให้ดียิ่งขึ้น',
                'category' => 'step4',
                'label' => 'หัวข้อด้านทรัพยากรและทักษะบุคลากร (E)'
            ],
            [
                'setting_key' => 'step4_title_D',
                'setting_value' => 'ด้านการปรับปรุงการควบคุมด้านการรายงานผล และการใช้ข้อมูล',
                'category' => 'step4',
                'label' => 'หัวข้อด้านการรายงานและการควบคุม (D)'
            ],
            [
                'setting_key' => 'step4_title_R',
                'setting_value' => 'การวางแผนการให้บริการ (Service Planning)',
                'category' => 'step4',
                'label' => 'หัวข้อการวางแผนการให้บริการ (R)'
            ]
        ];

        $stmt = $conn->prepare("INSERT INTO `form_config_metadata` (setting_key, setting_value, category, label) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            foreach ($defaultMetadata as $m) {
                $stmt->bind_param("ssss", $m['setting_key'], $m['setting_value'], $m['category'], $m['label']);
                $stmt->execute();
            }
            $stmt->close();
        }
    }
}

// 7. Create form_categories table if not exists
$createFormCategoriesTable = "CREATE TABLE IF NOT EXISTS `form_categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `step` INT NOT NULL,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `category_type` VARCHAR(20) DEFAULT 'risk',
    `sort_order` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($createFormCategoriesTable)) {
    $checkEmptyCategories = $conn->query("SELECT COUNT(*) as count FROM `form_categories`");
    if ($checkEmptyCategories && $checkEmptyCategories->fetch_assoc()['count'] == 0) {
        $defaultCategories = [
            // Step 2 categories (yes/no style in checklist)
            [2, 'AC', 'ความถูกต้อง และสมบูรณ์ (Accuracy and Completeness)', 'ข้อมูลมีความถูกต้องแม่นยำสูง หรือถ้ามีความคลาดเคลื่อนอยู่บ้าง ควรที่จะสามารถควบคุมขนาดของความคลาดเคลื่อนให้มีน้อยที่สุด และมีการตรวจสอบค่าความคลาดเคลื่อนของข้อมูลในส่วนต่าง ๆ ในทุกขั้นตอน ข้อมูลควรแสดงผลลัพธ์ที่คาดหวังไว้อย่างชัดเจนและเพียงพอ และควรถูกกำหนดโดยแหล่งที่มาดั้งเดิมของข้อมูล รวมทั้งข้อมูลที่จัดเตรียมควรมีความครบถ้วนตรงตามคุณลักษณะของข้อมูลที่คาดหวังและองค์ประกอบข้อมูลที่จำเป็นทั้งหมดที่ถูกจัดเก็บในระบบฐานข้อมูล', 'yesno', 10],
            [2, 'RE', 'ตรงตามความต้องการของผู้ใช้ (Relevancy)', 'ข้อมูลที่จัดทำขึ้นมาเป็นข้อมูลที่ผู้ใช้ต้องการ หรือเป็นข้อมูลที่จำเป็นต้องทราบ มีมุมมองและความละเอียดเพียงพอต่อนำไปใช้งาน ข้อมูลสามารถนำไปประยุกต์ใช้และเป็นประโยชน์สำหรับการดำเนินงาน/ภารกิจของหน่วยงาน และข้อมูลมีรายละเอียดในระดับเพียงพอที่จะอนุญาตให้ใช้เป็นข้อมูลประกอบการตัดสินใจในการบริหารจัดการ', 'yesno', 20],
            [2, 'CO', 'ความสอดคล้องกัน (Consistency)', 'ข้อมูลมีความสอดคล้องต่อเนื่องในเชิงการจัดเก็บ จัดทํา และเผยแพร่ (ข้อมูลควรสะท้อนถึงกระบวนการจัดเก็บข้อมูลและวิธีการวิเคราะห์ที่เสถียรและมีสอดคล้องกันอย่างช่วงเวลา) รวมทั้งความสามารถในการนำไปเปรียบเทียบกับข้อมูลเดียวกันในอดีต และข้อมูลอื่นในชวงเวลาเดียวกันได้อย่างกว้างขวางและสอดคล้อง โดยความสอดคล้องนี้จะเกิดจากการใช้แนวคิด การจัดหมวดหมู่ การคัดเลือกประชากรและวิธีการจัดทําด้วยวิธีทางสถิติที่เป็นมาตรฐาน', 'yesno', 30],
            [2, 'TI', 'ความเป็นปัจจุบัน (Timeliness)', 'ความทันเวลาต่อการใช้งานของข้อมูล ไม่ว่าจะเป็นการนําไปใช้ต่อในแง่การประมวลผลหรือการเผยแพร่ข้อมูล ความทันเวลาอ้างอิงจากความล่าช้าของข้อมูลซึ่งวัดได้หลายลักษณะขึ้นอยู่กับประเภทของข้อมูล เช่น วัดจากระยะเวลาที่ได้รับข้อมูลจนถึงเวลาที่ข้อมูลพร้อม ใช้งาน วัดจากระยะเวลาที่กําหนดของการเผยแพร่กับเวลาที่สามารถเผยแพร่ได้จริง', 'yesno', 40],
            [2, 'AV', 'ความพร้อมใช้ (Availability)', 'ข้อมูลควรเข้าถึงได้ง่าย สามารถใช้งานได้จริง และสามารถใช้งานได้ตลอดเวลา', 'yesno', 50],
            
            // Step 4 categories (risk style or yes/no)
            [4, 'G', 'ด้านการปรับปรุงการจัดทำธรรมาภิบาลและการจัดการคุณภาพข้อมูล และบทบาทความรับผิดชอบด้านคุณภาพข้อมูล', '', 'risk', 60],
            [4, 'P', 'ด้านการพัฒนานโยบายและแนวปฏิบัติด้านข้อมูล', '', 'risk', 70],
            [4, 'S', 'ด้านระบบเทคโนโลยีสารสนเทศและการสื่อสาร', '', 'risk', 80],
            [4, 'E', 'ด้านการพัฒนาความรู้ ทักษะ และความสามารถของบุคลากรเพื่อรักษาคุณภาพข้อมูลให้ดียิ่งขึ้น', '', 'risk', 90],
            [4, 'D', 'ด้านการปรับปรุงการควบคุมด้านการรายงานผล และการใช้ข้อมูล', '', 'risk', 100],
            [4, 'R', 'การวางแผนการให้บริการ (Service Planning)', '', 'yesno', 110]
        ];

        $stmt = $conn->prepare("INSERT INTO `form_categories` (step, code, title, description, category_type, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            foreach ($defaultCategories as $c) {
                $stmt->bind_param("issssi", $c[0], $c[1], $c[2], $c[3], $c[4], $c[5]);
                $stmt->execute();
            }
            $stmt->close();
        }
    }
}

// 8. Create form_fields table if not exists
$createFormFieldsTable = "CREATE TABLE IF NOT EXISTS `form_fields` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `step` INT NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `field_code` VARCHAR(50) UNIQUE NOT NULL,
    `label` TEXT NOT NULL,
    `description` TEXT DEFAULT NULL,
    `field_type` VARCHAR(50) DEFAULT 'textarea',
    `is_required` TINYINT(1) DEFAULT 0,
    `sort_order` INT DEFAULT 0,
    `status` VARCHAR(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($createFormFieldsTable)) {
    $checkEmptyFields = $conn->query("SELECT COUNT(*) as count FROM `form_fields`");
    if ($checkEmptyFields && $checkEmptyFields->fetch_assoc()['count'] == 0) {
        $defaultFields = [
            // Step 1: General Info Fields
            [1, 'info_general', 'info_title', 'ชื่อข้อมูล :', 'กรอกชื่อข้อมูลหรือชื่อชุดข้อมูล...', 'textarea', 1, 10],
            [1, 'info_general', 'info_agency', 'ชื่อหน่วยงานที่ดำเนินงาน :', '-- เลือกหน่วยงาน --', 'select', 1, 20],
            [1, 'info_general', 'info_mission', 'ภารกิจ :', 'ภารกิจด้าน', 'textarea', 0, 30],
            [1, 'info_general', 'metric_name', 'ชื่อตัวชี้วัดผลการประเมินคุณภาพข้อมูล :', '[ตัวชี้วัดสามารถอ้างอิงจากตัวชี้วัดผลการดำเนินงาน]', 'textarea', 1, 40],
            [1, 'info_general', 'metric_link', 'ลิงก์การเชื่อมโยงโครงสร้างของแผนงานที่เป็นมาตรฐาน :', 'หากมี (เช่น ขอบเขตของแผนงาน องค์ประกอบ เป็นต้น)', 'textarea', 0, 50],
            [1, 'info_general', 'metric_result', 'ผลลัพธ์ของการวัดผลตัวชี้วัด :', '[สำหรับภายในหน่วยงานเท่านั้น] (เช่น ระบุวัตถุประสงค์การพัฒนา ผลลัพธ์ขั้นกลาง หรือวัตถุประสงค์โครงการ เป็นต้น)', 'textarea', 0, 60],
            [1, 'info_general', 'metric_source', 'แหล่งที่มาข้อมูล :', '[ข้อมูลสามารถอ้างอิงจากตัวชี้วัดผลการดำเนินงานตามเอกสารนี้โดยตรง]', 'textarea', 1, 70],
            [1, 'info_general', 'source_partner', 'หน่วยงานเครือข่าย (Partner) / ผู้รับจ้าง (Vendor) ที่ให้ข้อมูล :', '[ข้อเสนอแนะสำหรับจัดทำ checklist นี้ให้ครบถ้วนจากหน่วยงานเครือข่ายที่สนับสนุนข้อมูลตามตัวชี้วัด ควรระบุไว้สัญญา/ความร่วมมือว่าเป็นความรับผิดชอบสำคัญในการสร้างความเชื่อมั่นในคุณภาพข้อมูลของผู้รับจ้างรายย่อยหรือผู้รับทุน]', 'textarea', 0, 80],
            [1, 'info_general', 'source_period', 'ระยะเวลาของข้อมูลนำเสนอในรายงาน :', 'รายวัน เดือน ปี', 'textarea', 0, 90],
            [1, 'info_general', 'metric_standard_type', 'ตัวชี้วัดคุณภาพข้อมูลเป็นไปตามมาตรฐานหรือกำหนดเอง :', '', 'select', 0, 100],
            [1, 'info_general', 'metric_standard_detail', 'ตัวชี้วัดคุณภาพข้อมูลเป็นไปตามมาตรฐานหรือกำหนดเอง (รายละเอียด) :', '(โดย มาตรฐาน... หรือ ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร)', 'textarea', 0, 110],
            [1, 'info_general', 'eval_method', 'วิธีการประเมินคุณภาพข้อมูล :', '[อธิบายหรือแนบเอกสารที่เกี่ยวกับวิธีการและกระบวนการในการประเมินตัวชี้วัดคุณภาพของข้อมูล เช่น ทบทวนกระบวนการเก็บรวบรวมข้อมูลและเอกสาร สัมภาษณ์ผู้รับผิดชอบในการวิเคราะห์ข้อมูล และตรวจสอบตัวอย่างข้อมูลที่ผิดพลาด เป็นต้น]', 'textarea', 0, 120],
            [1, 'info_general', 'eval_date', 'วันที่ประเมินคุณภาพข้อมูล :', '', 'date', 1, 130],
            [1, 'info_general', 'eval_team', 'ทีมผู้ประเมินคุณภาพข้อมูล :', 'ทีมบริการข้อมูล', 'text', 1, 140],
            [1, 'info_general', 'eval_approver', 'ผู้อนุมัติการประเมินคุณภาพข้อมูล :', 'หัวหน้าคณะทำงานบริการข้อมูลของสำนักงานปลัดกระทรวงพาณิชย์', 'textarea', 1, 150],

            // Step 2: Accuracy & Completeness
            [2, 'AC', 'ac1', 'ข้อมูลมีความถูกต้องหรือไม่', 'ข้อมูลไม่มีข้อผิดพลาดมีวิธีการที่ใช้ในการควบคุมข้อมูลนำเข้าและการควบคุมการประมวลผลที่ถูกต้องเชื่อถือได้ และข้อมูลที่จะนำไปใช้งานต้องผ่านการตรวจสอบว่าถูกต้อง ครบถ้วน และสมบูรณ์ เช่น มีการตรวจสอบอัตราความครบถ้วนในการกรอกข้อมูล โดยพิจารณาเฉพาะแถวข้อมูลแถวและฟิลด์ของข้อมูลที่มีความจำเป็นเท่านั้น', 'radio', 1, 210],
            [2, 'AC', 'ac2', 'ข้อมูลมีแหล่งที่มาที่น่าเชื่อถือหรือไม่', 'มีการระบุแหล่งที่มา สามารถตรวจสอบได้ว่ามาจากแหล่งใด แหล่งที่มาข้อมูลต้องได้รับรองจากหน่วยงาน/สถาบันที่น่าเชื่อถือ และมีการเผยแพร่หรือแลกเปลี่ยนเชื่อมโยงจากหน่วยงานที่มีการจดทะเบียนและมีตัวตนอยู่จริง', 'radio', 1, 220],
            [2, 'AC', 'ac3', 'ข้อมูลที่เก็บรวบรวมมาได้จากประชากรหรือตัวอย่างมีสัดส่วนที่เพียงพอหรือไม่', 'และ/หรือข้อมูลที่เก็บรวบรวมมีตรงตามดัชนีชี้วัดความสำเร็จของงาน (KPI) หรือไม่', 'radio', 1, 230],
            [2, 'AC', 'ac4', 'ผลลัพธ์การรวบรวมข้อมูลอยู่ในช่วงค่าคะแนนที่เป็นไปได้หรือสมเหตุสมผลหรือไม่', '', 'radio', 1, 240],
            [2, 'AC', 'ac5', 'ระเบียบวิธีวิจัยที่ใช้ในการรวบรวมข้อมูลเหมาะสมถูกต้องหรือไม่', 'และมีการรับประกันวิธีการ/เครื่องมือที่ใช้ในการรวบรวมข้อมูลมีความละเอียดหรือแม่นยำเพียงพอที่จะบันทึกการเปลี่ยนแปลงที่คาดไว้หรือไม่ มีความเป็นกลางหรือไม่ได้ให้เกิดระบบที่มีอคติของข้อมูล (เช่น มีความสอดคล้องกัน การนับจำนวนที่สูงหรือต่ำเกินไป เป็นต้น) หรือสมเหตุสมผลหรือไม่', 'radio', 1, 250],
            [2, 'AC', 'ac6', 'มีขั้นตอนแก้ไขความผิดพลาดของข้อมูลที่รับรู้', 'เช่น ความผิดพลาดของข้อมูลมีค่าน้อยกว่าที่คาดการณ์หรือไม่ และมีการรายงานค่าความผิดพลาดของข้อมูลหรือไม่ หรือลดข้อจำกัด/ความผิดพลาดในการสำเนา/นำเข้าข้อมูลหรือไม่', 'radio', 1, 260],
            [2, 'AC', 'ac7', 'มีการประเมินปัญหาการรวบรวมข้อมูลที่รับรู้อย่างเหมาะสมหรือไม่', '', 'radio', 1, 270],
            [2, 'AC', 'ac8', 'มีวิธีการ/เครื่องมือป้องกันรักษาความปลอดภัยของข้อมูลหรือไม่', 'เช่น มีขั้นตอนหรือมาตรการป้องกันเพื่อลดความเสี่ยงอคติหรือข้อผิดพลาดในการบันทึกข้อมูล และมีการรักษาความปลอดภัยที่เหมาะสมเพื่อป้องกันการเปลี่ยนแปลงข้อมูลโดยที่ไม่ได้รับอนุญาต', 'radio', 1, 280],

            // Step 2: Relevancy
            [2, 'RE', 're1', 'ข้อมูลตรงตามความต้องการของผู้ใช้งานและตามวัตถุประสงค์ของการใช้งานหรือไม่', 'มีการสำรวจความต้องการใช้งาน/ความพึงพอใจของผู้ใช้งานข้อมูล เพื่อประเมินความต้องการของผู้ใช้งานและนำไปปรับปรุงคุณภาพข้อมูลได้ตรงตามความต้องการใช้งาน', 'radio', 1, 290],
            [2, 'RE', 're2', 'ต้นทุนในการทำให้ระดับความถูกต้องของข้อมูลเพิ่มสูงขึ้นมากกว่ามูลค่าของข้อมูลข่าวสารที่เพิ่มขึ้นจากการใช้ประโยชน์ข้อมูลหรือไม่', '', 'radio', 1, 300],
            [2, 'RE', 're3', 'มีการกำหนดค่าส่วนเกินของความผิดพลาดที่รับได้สำหรับแผนงานการตัดสินใจ/ประมวลผลหรือไม่', '', 'radio', 1, 310],
            [2, 'RE', 're4', 'มีวิธีการตรวจสอบข้อมูลที่ซ้ำกันหรือข้อมูลที่ขาดหายหรือไม่', '', 'radio', 1, 320],
            [2, 'RE', 're5', 'ชุดข้อมูลส่วนใหญ่เป็นชุดข้อมูลที่มีคุณค่าสูง (High Value Datasets) หรือไม่', '', 'radio', 1, 330],

            // Step 2: Consistency
            [2, 'CO', 'co1', 'มีรูปแบบการจัดเก็บข้อมูลที่สอดคล้องและเป็นมาตรฐานเดียวกันหรือไม่', 'ทั้งภายในชุดข้อมูลและฟิลด์ข้อมูลเดียวกัน มีข้อมูลที่เป็นรูปแบบเดียวกัน เช่น ฟิลด์ A มีแต่ข้อมูลตัวเลข จะต้องไม่มีอักษร หรือสัญลักษณ์พิเศษในฟิลด์นี้ เป็นต้น และมีการจัดทำข้อมูลตามมาตรฐานเดียวกัน อาทิ การกำหนดกรอบแนวคิด คำนิยาม หน่วยนับ หรือการจำแนกระยะเวลาจัดเก็บ หรือเผยแพร่', 'radio', 1, 340],
            [2, 'CO', 'co2', 'หากใช้วิธีการจัดเก็บข้อมูลแบบเดียวกันเพื่อวัดผล/สังเกตการณ์ในเรื่องเดียวกันในหลายครั้ง จะได้ผลลัพธ์ที่เหมือนกันในแต่ละครั้งหรือไม่', '', 'radio', 1, 350],
            [2, 'CO', 'co3', 'มีเอกสารและแนวปฏิบัติในการจัดเก็บและวิเคราะห์ข้อมูล และถูกนำไปใช้เพื่อสร้างความเชื่อมั่นว่าเป็นไปตามแนวปฏิบัติเดียวกันในแต่ละครั้งหรือไม่ และมีเอกสารสำหรับการทบทวนการจัดเก็บข้อมูลและการดูแลรักษาเป็นระยะ ๆ หรือไม่', '', 'radio', 1, 360],
            [2, 'CO', 'co4', 'มีความสอดคล้องกันในกระบวนการจัดเก็บข้อมูลที่ถูกใช้ระหว่างปี พื้นที่จัดเก็บ และแหล่งที่มาของข้อมูล หรือไม่', '', 'radio', 1, 370],

            // Step 2: Timeliness
            [2, 'TI', 'ti1', 'ข้อมูลที่จัดหาได้มีความถี่เพียงพอต่อการแจ้งแผนงานในการตัดสินใจบริหารจัดการหรือไม่', '', 'radio', 1, 380],
            [2, 'TI', 'ti2', 'ข้อมูลที่ถูกนำมารายงานส่วนใหญ่ใช้ได้จริงและเป็นปัจจุบันหรือไม่', '', 'radio', 1, 390],
            [2, 'TI', 'ti3', 'ข้อมูลถูกนำมารายงานทันทีเท่าที่จะเป็นไปได้ภายหลังการจัดเก็บข้อมูลหรือไม่', '', 'radio', 1, 400],
            [2, 'TI', 'ti4', 'มีกำหนดตารางเวลาการจัดเก็บข้อมูลเป็นประจำเพื่อตอบสนองต่อความต้องการของแผนงานการบริหารจัดการหรือไม่', '', 'radio', 1, 410],
            [2, 'TI', 'ti5', 'ข้อมูลมีการจัดเก็บอย่างเหมาะสมและพร้อมใช้งานหรือไม่', '', 'radio', 1, 420],

            // Step 2: Availability
            [2, 'AV', 'av1', 'มีกระบวนการจัดทำข้อมูลที่สามารถอ่านได้ด้วยเครื่องคอมพิวเตอร์ (Machine Readable) และที่สามารถนำไปใช้งานต่อได้ง่ายหรือไม่', '', 'radio', 1, 430],
            [2, 'AV', 'av2', 'มีการจัดทำและเผยแพร่คำอธิบายข้อมูล หรือ Metadata สำหรับชุดข้อมูล ของหน่วยงานหรือไม่', '', 'radio', 1, 440],
            [2, 'AV', 'av3', 'มีช่องทางการเผยแพร่ข้อมูลที่หลากหลายและสามารถเข้าถึงได้ง่ายหรือไม่', 'มีระบบเทคโนโลยีสารสนเทศที่ทันสมัยและเหมาะสม และแพลตฟอร์มสื่อสังคมออนไลน์ต่าง ๆ ที่เป็นช่องทางในการเผยแพร่และสื่อสาร หรือ มีเว็บไซต์นำเสนอชุดข้อมูลตามมาตรฐานข้อมูลเปิด และมีการปรับปรุงสม่ำเสมอ', 'radio', 1, 450],
            [2, 'AV', 'av4', 'มีกระบวนการ/แนวปฏิบัติในการขอข้อมูลแชร์ข้อมูล (ที่ไม่ใช่ข้อมูลสาธารณะ) ของหน่วยงานที่ประกาศให้ผู้ขอใช้ข้อมูลหรือไม่', 'เช่น มีศูนย์บริการข้อมูล หรือ มีเจ้าหน้าที่ให้ความช่วยเหลือในการขอข้อมูล', 'radio', 1, 460],

            // Step 4: Data Governance (G)
            [4, 'G', 'g1', 'เจ้าหน้าที่ระดับอาวุโสมีความรับผิดชอบเชิงกลยุทธ์ในภาพรวมสำหรับกำกับดูแลคุณภาพข้อมูล โดยไม่มีการมอบหมายผู้รับผิดชอบแทน หรือไม่', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 510],
            [4, 'G', 'g2', 'มีการสื่อสารข้อกำหนดในการควบคุมคุณภาพข้อมูลให้ผู้มีส่วนเกี่ยวข้องตลอดกระบวนการทำงาน/บริการอย่างชัดเจน และมีการเน้นย้ำว่าเป็นความรับผิดชอบของบุคลากรทุกคนในองค์กรในการควบคุมคุณภาพของข้อมูล หรือไม่', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 520],
            [4, 'G', 'g3', 'มีการกำหนดความรับผิดชอบสำหรับคุณภาพข้อมูลในกระบวนการทำงาน/บริการที่มีขอบเขตเฉพาะเจาะจงอย่างชัดเจนและเป็นทางการ และเป็นส่วนหนึ่งของระบบการประเมินสำหรับผู้ที่ถูกกำหนดให้มีบทบาทและรับผิดชอบในการควบคุมคุณภาพข้อมูลนั้น หรือไม่', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 530],
            [4, 'G', 'g4', 'มีกรอบการติดตามและตรวจสอบคุณภาพข้อมูลที่เหมาะสม โดยมีการตรวจสอบอย่างละเอียดเข้มงวดด้วยผู้มีหน้าที่กำกับดูแลข้อมูล และโปรแกรมที่ใช้ในการตรวจสอบต้องมีความเสี่ยงที่เหมาะสม', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 540],
            [4, 'G', 'g5', 'คุณภาพข้อมูลได้ถูกรวมไว้ในการจัดการความเสี่ยง ซึ่งมีการประเมินความเสี่ยงที่เกี่ยวข้องกับความไม่น่าเชื่อถือ หรือความไม่ถูกต้องของข้อมูลอยู่เป็นประจำ หรือไม่', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 550],
            [4, 'G', 'g6', 'มีการแก้ไขปัญหาในการบริการ อันเนื่องมาจากการตรวจสอบคุณภาพข้อมูลทั้งภายในและภายนอกหน่วยงานก่อนหน้า หรือไม่', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 560],
            [4, 'G', 'g7', 'กรณีที่มีการทำงานร่วมกัน มีการทำข้อตกลงร่วมกันที่ครอบคลุมถึงคุณภาพข้อมูลกับหน่วยงานภาคีการทำงาน หรือไม่ (ตัวอย่างเช่น ในรูปแบบ/ฟอร์มของหลักเกณฑ์การแบ่งปันข้อมูล คำชี้แจง หรือข้อตกลงระดับการบริการ เป็นต้น)', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 570],

            // Step 4: Security & Privacy (P)
            [4, 'P', 'p1', 'มีมาตรการรักษาความปลอดภัยของข้อมูลตามมาตรฐานสากล เช่น การเข้ารหัสข้อมูล (Encryption)', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 580],
            [4, 'P', 'p2', 'มีระบบคัดกรองและการอนุญาตการเข้าถึงข้อมูลตามบทบาทหน้าที่ (Role-based Access Control)', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 590],
            [4, 'P', 'p3', 'มีมาตรการคุ้มครองข้อมูลส่วนบุคคล (PDPA) สำหรับข้อมูลที่มีความละเอียดอ่อน', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 600],
            [4, 'P', 'p4', 'มีแผนสำรองข้อมูล (Backup) และแผนกู้คืนระบบเมื่อเกิดภัยพิบัติ (Disaster Recovery Plan)', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 610],
            [4, 'P', 'p5', 'มีการบันทึกประวัติการเข้าใช้งานและเข้าถึงข้อมูลของระบบ (System Logs/Audit Trails) เพื่อตรวจสอบ', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 620],
            [4, 'P', 'p6', 'มีขั้นตอนแจ้งเตือนและระงับการเข้าถึงทันทีเมื่อตรวจสอบพบการละเมิดความปลอดภัยของข้อมูล', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 630],
            [4, 'P', 'p7', 'มีการประเมินและทบทวนความเสี่ยงด้านความปลอดภัยของข้อมูลเป็นประจำทุกปี', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 640],

            // Step 4: Analytics & Usage (S)
            [4, 'S', 's1', 'มีการนำข้อมูลไปวิเคราะห์ประมวลผลเพื่อประกอบการบริหารจัดการระดับนโยบายของกอง/สำนัก', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 650],
            [4, 'S', 's2', 'มีการนำข้อมูลไปสร้างระบบแดชบอร์ด (Dashboard) รายงานเชิงบริหาร หรือระบบเตือนภัย', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 660],
            [4, 'S', 's3', 'มีการเปิดเผยเชื่อมโยงข้อมูลระหว่างหน่วยงานในสังกัดกระทรวงพาณิชย์', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 670],
            [4, 'S', 's4', 'มีการพัฒนาโมเดลหรือระบบปัญญาประดิษฐ์เพื่อช่วยคาดการณ์สถานการณ์ข้อมูลอนาคต', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 680],
            [4, 'S', 's5', 'ข้อมูลที่เผยแพร่ช่วยให้ผู้มีส่วนได้ส่วนเสียนำไปใช้งานได้ง่าย เช่น รองรับการดาวน์โหลดไฟล์ CSV/JSON', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 690],
            [4, 'S', 's6', 'มีกระบวนการรับฟังความคิดเห็นจากผู้ใช้งานข้อมูล เพื่อนำกลับมาปรับปรุงและแก้ไขคุณภาพข้อมูล', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 700],
            [4, 'S', 's7', 'มีการแชร์ข้อมูลร่วมกันระหว่างหน่วยงานภาครัฐในกรอบความร่วมมือการแลกเปลี่ยนข้อมูลภาครัฐ (Linkage)', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 710],
            [4, 'S', 's8', 'มีการจัดระดับความลับของข้อมูลเพื่อการแบ่งปัน (Data Classification) ที่ถูกต้องเหมาะสม', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 720],
            [4, 'S', 's9', 'มีการทำรายงานสรุปข้อมูลสถิติตัวชี้วัดเผยแพร่ต่อสาธารณะอย่างสม่ำเสมอ', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 730],

            // Step 4: Resources & Skills (E)
            [4, 'E', 'e1', 'หน่วยงานจัดสรรงบประมาณเพียงพอสำหรับการปรับปรุงระบบข้อมูลและอุปกรณ์เทคโนโลยีสารสนเทศ', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 740],
            [4, 'E', 'e2', 'มีบุคลากรที่มีความเชี่ยวชาญเฉพาะด้าน (เช่น Data Scientist, Data Engineer) ประจำงานดูแล', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 750],
            [4, 'E', 'e3', 'มีการจัดฝึกอบรมความรู้ด้านคุณภาพข้อมูลและทักษะดิจิทัลแก่บุคลากรอย่างสม่ำเสมอ', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 760],
            [4, 'E', 'e4', 'มีโครงสร้างพื้นฐานระบบเครือข่ายและเครื่องแม่ข่ายที่เสถียร รองรับการใช้งานโดยไม่สะดุด', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 770],

            // Step 4: Policy & Strategy (D)
            [4, 'D', 'd1', 'มีการกำหนดนโยบายบริหารจัดการข้อมูลของหน่วยงานสอดคล้องกับยุทธศาสตร์ชาติและแผนพัฒนาดิจิทัล', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 780],
            [4, 'D', 'd2', 'เป้าหมายการให้บริการข้อมูลของหน่วยงานมีการชี้วัดความคุ้มค่าและผลประโยชน์ต่อประชาชนเด่นชัด', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 790],
            [4, 'D', 'd3', 'มีทิศทางแผนงานขยายการบูรณาการข้อมูลเชื่อมต่อโครงข่ายกลางของกระทรวงพาณิชย์และภาครัฐ', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 800],
            [4, 'D', 'd4', 'หน่วยงานกำหนดให้ประเด็นด้านการจัดการและคุณภาพข้อมูลเป็นหนึ่งในตัวชี้วัดผลการทำงานหลักขององค์กร', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 810],

            // Step 4: Recommendations (R)
            [4, 'R', 'r1', 'มีการรวบรวมขอบเขตที่มีความเสี่ยงระดับปานกลาง และระดับสูงไว้ในการบริหารจัดการความเสี่ยงของแผนการให้บริการในปัจจุบันของหน่วยงาน หรือไม่', 'ระบุหลักฐานหรือความเห็น...', 'radio', 1, 820]
        ];

        $stmt = $conn->prepare("INSERT INTO `form_fields` (step, category, field_code, label, description, field_type, is_required, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            foreach ($defaultFields as $f) {
                $stmt->bind_param("isssssii", $f[0], $f[1], $f[2], $f[3], $f[4], $f[5], $f[6], $f[7]);
                $stmt->execute();
            }
            $stmt->close();
        }
    }
}

// Check and auto-insert missing step2 category title keys
$newMetadataKeys = [
    [
        'setting_key' => 'step2_title_AC',
        'setting_value' => 'ความถูกต้อง และสมบูรณ์ (Accuracy and Completeness)',
        'category' => 'step2',
        'label' => 'หัวข้อด้านความถูกต้อง และสมบูรณ์ (AC)'
    ],
    [
        'setting_key' => 'step2_title_RE',
        'setting_value' => 'ตรงตามความต้องการของผู้ใช้ (Relevancy)',
        'category' => 'step2',
        'label' => 'หัวข้อด้านตรงตามความต้องการของผู้ใช้ (RE)'
    ],
    [
        'setting_key' => 'step2_title_CO',
        'setting_value' => 'ความสอดคล้องกัน (Consistency)',
        'category' => 'step2',
        'label' => 'หัวข้อด้านความสอดคล้องกัน (CO)'
    ],
    [
        'setting_key' => 'step2_title_TI',
        'setting_value' => 'ความเป็นปัจจุบัน (Timeliness)',
        'category' => 'step2',
        'label' => 'หัวข้อด้านความเป็นปัจจุบัน (TI)'
    ],
    [
        'setting_key' => 'step2_title_AV',
        'setting_value' => 'ความพร้อมใช้ (Availability)',
        'category' => 'step2',
        'label' => 'หัวข้อด้านความพร้อมใช้ (AV)'
    ],
    [
        'setting_key' => 'step2_instruction_title',
        'setting_value' => 'คำชี้แจงขั้นตอนที่ 2: มิติคุณภาพข้อมูล',
        'category' => 'step2',
        'label' => 'หัวข้อคำชี้แจงขั้นตอนที่ 2'
    ],
    [
        'setting_key' => 'step2_instruction_text',
        'setting_value' => '',
        'category' => 'step2',
        'label' => 'เนื้อหาคำชี้แจงขั้นตอนที่ 2'
    ],
    [
        'setting_key' => 'step2_remark_title',
        'setting_value' => 'หมายเหตุขั้นตอนที่ 2',
        'category' => 'step2',
        'label' => 'หัวข้อหมายเหตุท้ายขั้นตอนที่ 2'
    ],
    [
        'setting_key' => 'step2_remark_text',
        'setting_value' => '',
        'category' => 'step2',
        'label' => 'เนื้อหาหมายเหตุท้ายขั้นตอนที่ 2'
    ],
    [
        'setting_key' => 'step3_instruction_title',
        'setting_value' => 'คำชี้แจงขั้นตอนที่ 3: แบบประเมินตนเอง',
        'category' => 'step3',
        'label' => 'หัวข้อคำชี้แจงขั้นตอนที่ 3'
    ],
    [
        'setting_key' => 'step3_instruction_text',
        'setting_value' => '',
        'category' => 'step3',
        'label' => 'เนื้อหาคำชี้แจงขั้นตอนที่ 3'
    ],
    [
        'setting_key' => 'step3_remark_title',
        'setting_value' => 'หมายเหตุขั้นตอนที่ 3',
        'category' => 'step3',
        'label' => 'หัวข้อหมายเหตุท้ายขั้นตอนที่ 3'
    ],
    [
        'setting_key' => 'step3_remark_text',
        'setting_value' => '',
        'category' => 'step3',
        'label' => 'เนื้อหาหมายเหตุท้ายขั้นตอนที่ 3'
    ],
    [
        'setting_key' => 'step4_instruction_title',
        'setting_value' => 'แบบตรวจประเมินการควบคุมและติดตามคุณภาพข้อมูล (Data Quality Monitoring and Control Checklist)',
        'category' => 'step4',
        'label' => 'หัวข้อคำชี้แจงขั้นตอนที่ 4'
    ],
    [
        'setting_key' => 'step4_instruction_text',
        'setting_value' => '<strong>คำชี้แจง :</strong> ให้ผู้ควบคุมคุณภาพข้อมูลประเมินผลการควบคุมคุณภาพข้อมูลตามหัวข้อด้านล่างนี้',
        'category' => 'step4',
        'label' => 'เนื้อหาคำชี้แจงขั้นตอนที่ 4'
    ],
    [
        'setting_key' => 'step4_remark_title',
        'setting_value' => 'หมายเหตุขั้นตอนที่ 4',
        'category' => 'step4',
        'label' => 'หัวข้อหมายเหตุท้ายขั้นตอนที่ 4'
    ],
    [
        'setting_key' => 'step4_remark_text',
        'setting_value' => '',
        'category' => 'step4',
        'label' => 'เนื้อหาหมายเหตุท้ายขั้นตอนที่ 4'
    ]
];

foreach ($newMetadataKeys as $m) {
    $checkKeyQuery = $conn->query("SELECT id FROM `form_config_metadata` WHERE `setting_key` = '" . $conn->real_escape_string($m['setting_key']) . "'");
    if ($checkKeyQuery && $checkKeyQuery->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO `form_config_metadata` (setting_key, setting_value, category, label) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssss", $m['setting_key'], $m['setting_value'], $m['category'], $m['label']);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Check and auto-insert missing step3 (Self-Assessment) fields
$checkSaFields = $conn->query("SELECT COUNT(*) as count FROM `form_fields` WHERE `step` = 3");
if ($checkSaFields && $checkSaFields->fetch_assoc()['count'] == 0) {
    $step3Fields = [
        [3, 'AC', 'sa_1_1', '1.1 มีแหล่งข้อมูลที่น่าเชื่อถือ', "1 ต่ำ : ใช้ข้อมูลจากแหล่งอ้างอิงที่ไม่น่าเชื่อถือ ขาดแหล่งอ้างอิงข้อมูล หรือเป็นความคิดเห็นจากบุคคลโดยขาดหลักฐานเชิงประจักษ์\n2 ปานกลาง : ใช้ข้อมูลจากแหล่งข้อมูลที่ไม่น่าเชื่อถือแต่มีเนื้อหาที่รับรองโดยผู้เชี่ยวชาญเฉพาะด้านได้\n3 ดี : ใช้ข้อมูลจากแหล่งข้อมูลที่น่าเชื่อถือหรือมีแหล่งข้อมูลที่น่าเชื่อถือ\n4 ดีมาก : ใช้ข้อมูลจากแหล่งข้อมูลที่น่าเชื่อถือและถูกต้องตามหลักวิชาการ", 'radio', 1, 310],
        [3, 'AC', 'sa_1_2', '1.2 มีกระบวนการหรือเครื่องมือตรวจสอบจุดผิดพลาดของข้อมูล', "1 ต่ำ : ขาดกระบวนการหรือเครื่องมือตรวจสอบ ความถูกต้องของข้อมูล\n2 ปานกลาง : มีกระบวนการตรวจสอบ จุดผิดพลาดที่ไร้รูปแบบ และอาศัยจากการคาดการณ์ อนุมาน โดยบุคคล\n3 ดี : มีกระบวนการ เครื่องมือตรวจสอบความถูกต้อง เป็นแบบแผน\n4 ดีมาก : มีกระบวนการ เครื่องมือตรวจสอบความถูกต้อง เป็นแบบแผน และแจ้งเตือนอัตโนมัติ", 'radio', 1, 320],
        [3, 'AC', 'sa_1_3', '1.3 มีการตรวจสอบความครบถ้วนของข้อมูล', "1 ต่ำ : ขาดกระบวนการตรวจทาน ความครบถ้วนของข้อมูล\n2 ปานกลาง : มีกระบวนการตรวจสอบความครบถ้วนโดยอาศัยการสังเกตด้วยบุคคล\n3 ดี : มีกระบวนการตรวจสอบความครบถ้วน ด้วยเครื่องมืออัตโนมัติ\n4 ดีมาก : มีกระบวนการรับรองว่าข้อมูล มีความครบถ้วน สมบูรณ์ตั้งแต่ขั้นตอนการเก็บรวบรวมจนถึงการจัดเก็บลงในระบบ", 'radio', 1, 330],
        [3, 'AC', 'sa_1_4', '1.4 มีวิธีเก็บข้อมูลมีความเป็นกลาง น่าเชื่อถือ และไม่สร้างข้อมูลที่มีอคติ', "1 ต่ำ : ขาดการกำหนดวิธีการเก็บข้อมูลด้วยกรอบ มาตรฐานที่น่าเชื่อถือ หรือลดความอคติ\n2 ปานกลาง : มีการกำหนดกลุ่มตัวอย่างการเก็บข้อมูล ตามหลักของสถิติ หรือ มีการใช้เครื่องมือพื้นฐาน\n3 ดี : อย่างใดอย่างหนึ่ง มีการควบคุมการเก็บรวบรวมจากกลุ่มตัวอย่างที่กำหนดตามหลักสถิติ เช่น เพศ ความเชื่อ ความชอบ เป็นต้น หรือ มีเครื่องมือการเก็บที่เป็นมาตรฐาน แบบสอบถามที่ทดสอบความเชื่อมั่น เที่ยงตรงตามหลักวิชาการแล้ว\n4 ดีมาก : มีการควบคุมการเก็บรวบรวมจากกลุ่มตัวอย่างที่กำหนดตาม หลักสถิติทุกประการ เช่น เพศ ความเชื่อ ความชอบ เป็นต้น และ มีเครื่องมือการเก็บที่เป็นมาตรฐาน แบบสอบถามที่ทดสอบความเชื่อมั่น เที่ยงตรงตามหลักวิชาการแล้ว", 'radio', 1, 340],
        [3, 'AC', 'sa_1_5', '1.5 มีการระบุคำนิยามและลักษณะข้อมูลที่ต้องการ', "1 ต่ำ : ขาดคำนิยามของข้อมูล ลักษณะของข้อมูลที่พึงประสงค์ และวิธีการเก็บข้อมูลที่ชัดเจน\n2 ปานกลาง : มีคำนิยามของข้อมูล แต่ขาดความชัดเจน คลุมเครือ และไร้รูปแบบที่เป็นมาตรฐาน\n3 ดี : มีคำนิยามของข้อมูลและมาตรฐานของข้อมูลที่ต้องการ ชัดเจน\n4 ดีมาก : มีคำนิยามของข้อมูลและมีมาตรฐานที่ชัดเจน รวมทั้ง ครอบคลุม กรณีผิดปกติให้ผู้เก็บข้อมูลสามารถเก็บข้อมูลได้ถูกต้อง", 'radio', 1, 350],
        
        [3, 'CO', 'sa_2_1', '2.1 มีการเก็บข้อมูลภายใต้มาตรฐานข้อมูลเดียวกัน หรือมาตรฐานข้อมูลที่สอดคล้องกันทำให้สามารถใช้ประโยชน์ข้อมูลร่วมกันได้', "1 ต่ำ : การเก็บข้อมูลในหน่วยงาน มีมาตรฐานการเก็บข้อมูลแตกต่างกัน และใช้งานข้อมูลร่วมกันไม่ได้\n2 ปานกลาง : การเก็บข้อมูลในหน่วยงาน อยู่ในรูปแบบที่แตกต่างกัน แต่สามารถอ้างอิง จัดชุดข้อมูลและใช้ร่วมกันได้\n3 ดี : การเก็บข้อมูลในหน่วยงาน อยู่ในรูปแบบที่แตกต่างกัน แต่สามารถอ้างอิงและใช้ร่วมกันได้\n4 ดีมาก : การเก็บข้อมูลในหน่วยงานมีมาตรฐานการเก็บแบบเดียวกัน และใช้งานร่วมกันได้", 'radio', 1, 360],
        [3, 'CO', 'sa_2_2', '2.2 มีการตรวจสอบรูปแบบข้อมูลภายในชุดข้อมูลเดียวกัน', "1 ต่ำ : ขาดกระบวนการตรวจสอบรูปแบบ (Format) ข้อมูลในชุดข้อมูลเดียวกัน\n2 ปานกลาง : มีกระบวนการตรวจสอบรูปแบบข้อมูลโดยอาศัยบุคคลหรือผู้ใช้งานข้อมูล\n3 ดี : มีกระบวนการตรวจสอบรูปแบบข้อมูลด้วยระบบคอมพิวเตอร์ โดยมีอาศัยบุคคลเป็นผู้ตรวจสอบ\n4 ดีมาก : มีขั้นตอนหรือเครื่องมือที่แจ้งเตือนผู้ใช้ข้อมูลและผู้เก็บข้อมูลโดยอัตโนมัติเมื่อมีการเก็บข้อมูลผิดจากรูปแบบที่กำหนด", 'radio', 1, 370],
        [3, 'CO', 'sa_2_3', '2.3 มีการตรวจสอบรูปแบบข้อมูลระหว่างหน่วยงาน', "1 ต่ำ : ขาดกระบวนการตรวจสอบรูปแบบ (Format) ข้อมูลในชุดข้อมูลเดียวกัน\n2 ปานกลาง : มีกระบวนการตรวจสอบรูปแบบข้อมูลโดยอาศัยบุคคลหรือผู้ใช้งานข้อมูล\n3 ดี : มีกระบวนการตรวจสอบรูปแบบข้อมูลด้วยระบบคอมพิวเตอร์ โดยมีอาศัยบุคคลเป็นผู้ตรวจสอบ\n4 ดีมาก : มีขั้นตอนหรือเครื่องมือที่แจ้งเตือนผู้ใช้ข้อมูลและผู้เก็บข้อมูลโดยอัตโนมัติเมื่อมีการเก็บข้อมูลผิดจากรูปแบบที่กำหนด", 'radio', 1, 380],
        [3, 'CO', 'sa_2_4', '2.4 ข้อมูลมีความเชื่อมโยงและไม่ขัดแย้งกัน', "1 ต่ำ : หน่วยงานภายใต้สังกัดต่างคนต่างเก็บรวบรวมข้อมูล ไม่สามารถใช้ข้อมูลร่วมกันได้\n2 ปานกลาง : มีข้อตกลงร่วมกันภายในฝ่าย เพื่อกำหนดรูปแบบมาตรฐานข้อมูลให้สามารถทำงานร่วมกันได้\n3 ดี : มีข้อตกลงร่วมกันในหน่วยงาน เรื่องรูปแบบมาตรฐานข้อมูล และกระบวนการที่จัดเก็บข้อมูล เป็นนโยบายให้เกิดความร่วมมือทั้งหน่วยงาน\n4 ดีมาก : มีข้อตกลงร่วมกัน in หน่วยงาน เรื่องรูปแบบมาตรฐานข้อมูล และกระบวนการที่จัดเก็บข้อมูล รวมถึงกำหนดเป็นระเบียบบังคับใช้ทั้งหน่วยงาน", 'radio', 1, 390],
        [3, 'CO', 'sa_2_5', '2.5 มีการใช้กฎ วิธีการตรวจวัดที่สอดคล้องกันทั้งหน่วยงาน รวมถึงหน่วยงานภายนอก', "1 ต่ำ : หน่วยงานภายใต้สังกัดต่างคนต่างเก็บข้อมูล ไม่สามารถใช้ข้อมูลร่วมกันได้\n2 ปานกลาง : ข้อตกลงร่วมกันเฉพาะฝ่ายเพื่อกำหนดวิธีการเก็บข้อมูลร่วมกัน\n3 ดี : มีข้อตกลงร่วมกันในหน่วยงาน เรื่องวิธีการเก็บข้อมูลเพื่อให้เป็นมาตรฐานเดียวกัน\n4 ดีมาก : มีข้อตกลงร่วมกันในหน่วยงาน เรื่องวิธีการเก็บข้อมูลเพื่อให้เป็นมาตรฐานเดียวกัน และมีการปรับปรุงมาตรฐานการเก็บข้อมูลตามวิสัยทัศน์และความต้องการข้อมูล", 'radio', 1, 400],
        [3, 'CO', 'sa_2_6', '2.6 มีการกำหนดบทบาทและผู้รับผิดชอบข้อมูล', "1 ต่ำ : ขาดการกำหนดบทบาทและขอบเขตของผู้ดูแลข้อมูลอย่างชัดเจน และยังไม่มีการมอบหมายให้หน่วยงานดูแลข้อมูลที่เกี่ยวข้อง\n2 ปานกลาง : อย่างใดอย่างหนึ่ง 1. มีการกำหนดบทบาทและขอบเขตของผู้ดูแลข้อมูลอย่างชัดเจนแต่ไม่มีการมอบหมายหน่วยงานให้ปฏิบัติหน้าที่ หรือ 2. มีการมอบหมายให้หน่วยงานดูแล รักษา จัดเก็บข้อมูล แต่ไม่มีการกำหนดบทบาทและขอบเขตที่ชัดเจน\n3 ดี : มีการมอบหมายบทบาทและขอบเขตของผู้รับผิดชอบเก็บข้อมูลและผู้ดูแลข้อมูลอย่างชัดเจน โดยครอบคลุมภารกิจของหน่วยงาน\n4 ดีมาก : มีการมอบหมายบทบาทและขอบเขตของผู้รับผิดชอบเก็บข้อมูลและผู้ดูแลข้อมูลอย่างชัดเจน ครอบคลุมภารกิจของหน่วยงาน และครอบคลุมถึงความต้องการข้อมูลของเหตุสุดวิสัยที่เกิดขึ้น", 'radio', 1, 410],
        
        [3, 'RE', 'sa_3_1', '3.1 ข้อมูลตรงตามความต้องการของผู้ใช้งานและตามวัตถุประสงค์ของการใช้งาน', "1 ต่ำ : ข้อมูลได้รับการประเมินความพึงพอใจจากผู้ใช้งานข้อมูลอยู่ในระดับต่ำ\n2 ปานกลาง : ข้อมูลได้รับการประเมินความพึงพอใจจากผู้ใช้งานข้อมูลในระดับปานกลาง\n3 ดี : ข้อมูลได้รับการประเมินความพึงพอใจจากผู้ใช้งานข้อมูลในระดับดี\n4 ดีมาก : ข้อมูลได้รับการประเมินความพึงพอใจจากผู้ใช้งานข้อมูล in ระดับดีมาก", 'radio', 1, 420],
        [3, 'RE', 'sa_3_2', '3.2 มีผลประเมินความพึงพอใจของผู้ใช้ และมีการปรับปรุงคุณภาพให้ตรงตามความต้องการของผู้ใช้', "1 ต่ำ : ไม่มีการประเมินความพึงพอใจของผู้ใช้งานข้อมูล\n2 ปานกลาง : มีการประเมินความพึงพอใจ แต่ผู้ใช้งานข้อมูลยังไม่สามารถใช้งานได้ตามความต้องการ\n3 ดี : มีการประเมินความพึงพอใจ และผู้ใช้งานสามารถใช้งานได้ตรงตามความต้องการ\n4 ดีมาก : มีการประเมินความพึงพอใจ และผู้ใช้งานสามารถใช้งานข้อมูลได้ตามความต้องการและมีการปรับปรุงคุณภาพข้อมูลตามผลการประเมินความพึงพอใจ", 'radio', 1, 430],
        
        [3, 'TI', 'sa_4_1', '4.1 ข้อมูลมีการเผยแพร่ ส่งต่อตรงเวลา', "1 ต่ำ : มีการเก็บข้อมูลไม่มีการเผยแพร่ หรือส่งต่อไปยังแหล่งจัดเก็บข้อมูล หรือใช้เวลาส่งข้อมูลมากกว่า 14 วัน\n2 ปานกลาง : มีการส่งต่อข้อมูลหลังจากจัดเก็บไปยังฐานข้อมูล หรือเผยแพร่ข้อมูลภายในเวลา 7-14 วัน หลังจากเก็บข้อมูล\n3 ดี : มีการส่งต่อข้อมูลหลังจากจัดเก็บไปยังฐานข้อมูล หรือเผยแพร่ข้อมูลภายในเวลา 1-7 วัน หลังจากเก็บข้อมูล\n4 ดีมาก : มีการส่งต่อข้อมูลหลังจากจัดเก็บไปยังฐานข้อมูล หรือเผยแพร่ข้อมูลทันที (Real time streaming)", 'radio', 1, 440],
        [3, 'TI', 'sa_4_2', '4.2 ข้อมูลมีความเป็นปัจจุบัน', "1 ต่ำ : ข้อมูลที่ใช้หรือเก็บรวบรวมมีอายุข้อมูลมากกว่า 15 ปี\n2 ปานกลาง : ข้อมูลที่ใช้หรือเก็บรวบรวมมีอายุข้อมูล 5-15 ปี\n3 ดี : ข้อมูลที่ใช้หรือเก็บรวบรวมมีอายุข้อมูล 1-5 ปี\n4 ดีมาก : ข้อมูลที่ใช้หรือเก็บรวบรวมต้องเป็นปัจจุบันในวันนั้น หรือมีอายุข้อมูลไม่เกิน 1 ปี", 'radio', 1, 450],
        [3, 'TI', 'sa_4_3', '4.3 ข้อมูลมีการเผยแพร่ข้อมูลในเวลาที่เหมาะสม', "1 ต่ำ : ข้อมูลมีการเผยแพร่หลังจากเกิดเหตุการณ์เกินกว่า 2 สัปดาห์ หรือล่าช้ากว่าปฏิทินการเผยแพร่ข้อมูลมากกว่า 1 เดือน\n2 ปานกลาง : ข้อมูลมีการเผยแพร่หลังจากเกิดเหตุการณ์อย่างน้อยภายใน 7-14 วัน หรือล่าช้ากว่าปฏิทินการเผยแพร่ข้อมูลภายในเวลา 1 เดือน\n3 ดี : ข้อมูลมีการเผยแพร่หลังจากเกิดเหตุการณ์อย่างน้อยภายใน 3-7 วัน หรือล่าช้ากว่าปฏิทินการเผยแพร่ข้อมูลภายในเวลา 1 สัปดาห์\n4 ดีมาก : ข้อมูลมีการเผยแพร่หลังจากเกิดเหตุการณ์อย่างน้อยภายใน 1-3 วัน หรือตรงตามปฏิทินการเผยแพร่ข้อมูล", 'radio', 1, 460],
        [3, 'TI', 'sa_4_4', '4.4 มีการจัดทำปฏิทินเผยแพร่ข้อมูล', "1 ต่ำ : ขาดกระบวนการวางแผนดำเนินงานและปฏิบัติในการเผยแพร่ข้อมูลไม่สอดคล้องกับขั้นตอนการทำงาน\n2 ปานกลาง : มีการกำหนดปฏิทินการเผยแพร่ข้อมูลโดยใช้กรอบเวลาดำเนินการแบบประมาณการ\n3 ดี : มีกระบวนการกำหนดแผนดำเนินการเก็บข้อมูล ประมวลผลและวางกำหนดเวลาเพื่อเผยแพร่ข้อมูลได้อย่างเหมาะสม\n4 ดีมาก : มีการกำหนดแผนดำเนินการเก็บข้อมูล ประมวลผลและวางกำหนดเวลาเพื่อเผยแพร่ข้อมูลได้อย่างเหมาะสมกับสถานการณ์และทรัพยากรที่มี", 'radio', 1, 470],
        
        [3, 'AV', 'sa_5_1', '5.1 ข้อมูลถูกจัดในรูปแบบที่พร้อมนำไปใช้งาน และเหมาะสมกับผู้ใช้งาน', "1 ต่ำ : ข้อมูลอยู่ในรูปแบบที่ไม่พร้อมใช้งานหรือประมวลผลต่อด้วยโปรแกรมคอมพิวเตอร์\n2 ปานกลาง : ข้อมูลอยู่ในรูปแบบที่พร้อมอ่านค่าได้ด้วยคอมพิวเตอร์แต่ไม่พร้อมนำไปประมวลผล จะต้องจัดรูปแบบให้เหมาะสมกับโปรแกรมประมวลผลและวัตถุประสงค์การใช้งาน\n3 ดี : ข้อมูลอยู่ในรูปแบบ (Format) ที่พร้อมนำเข้าโปรแกรมประมวลผล แต่ผู้ใช้ข้อมูลต้องจัดรูปแบบข้อมูลให้ตรงกับวัตถุประสงค์\n4 ดีมาก : ข้อมูลอยู่ในรูปแบบ (Format) ที่พร้อมใช้งานหรือนำไปประมวลผลด้วยโปรแกรมคอมพิวเตอร์ได้ทันที", 'radio', 1, 480],
        [3, 'AV', 'sa_5_2', '5.2 มีการเผยแพร่ข้อมูลที่เหมาะสมและสามารถเข้าถึงได้ โดยผู้ใช้สามารถเข้าถึงข้อมูลได้สะดวกตามสิทธิที่เหมาะสม', "1 ต่ำ : ผู้ใช้งานข้อมูลต้องทำเรื่องขอใช้ข้อมูลเปิด หรือ ขาดการเผยแพร่ข้อมูล\n2 ปานกลาง : ช่องทางการเผยแพร่ขาดโครงสร้างการจัดเก็บข้อมูลและขาดระบบสารบัญเพื่อเข้าถึงข้อมูล\n3 ดี : มีช่องทางการเผยแพร่ข้อมูลที่เหมาะสมกับชนิด ประเภท ขนาด และลำดับชั้นความลับ แต่ช่องทางการเก็บเป็นอุปสรรคในการเข้าถึงข้อมูล\n4 ดีมาก : มีช่องทางการเผยแพร่ข้อมูลที่เหมาะสมกับชนิด ประเภท ขนาด ลำดับชั้นความลับ รวมถึงสิทธิ์การเข้าถึงข้อมูลที่เหมาะสม", 'radio', 1, 490],
        [3, 'AV', 'sa_5_3', '5.3 ข้อมูลสามารถอ่านด้วยโปรแกรมคอมพิวเตอร์ได้', "1 ต่ำ : ข้อมูลที่จัดเก็บในรูปแบบที่คอมพิวเตอร์ไม่สามารถประมวลผลหรืออ่านค่าได้\n2 ปานกลาง : ข้อมูลที่จัดเก็บไม่สามารถประมวลผลได้ด้วยโปรแกรมคอมพิวเตอร์ หรือให้ผู้นำไปประมวลผลต่อได้ เช่น PDF JPEG PNG เป็นต้น\n3 ดี : ข้อมูลที่จัดเก็บสามารถประมวลผลได้ด้วยคอมพิวเตอร์ แต่อยู่ในรูปแบบที่ไม่พร้อมใช้งาน เช่น Text Docx CSV Xlsx เป็นต้น\n4 ดีมาก : ข้อมูลที่จัดเก็บสามารถประมวลผลได้ด้วยคอมพิวเตอร์ และพร้อมนำไปใช้งานได้อย่างครอบคลุมวัตถุประสงค์", 'radio', 1, 500],
        [3, 'AV', 'sa_5_4', '5.4 มีคำอธิบายข้อมูลที่ชัดเจน', "1 ต่ำ : ไม่มีคำอธิบายข้อมูลประกอบชุดข้อมูล นิยาม และหน่วยวัดที่ชัดเจน\n2 ปานกลาง : มีคำนิยามข้อมูลและหน่วยวัดของข้อมูล แต่ขาดคำอธิบาย (Metadata) ประกอบชุดข้อมูล\n3 ดี : มีกระบวนการ ใส่ข้อมูลคำอธิบายข้อมูล (Metadata) ได้อย่างน้อย 50% ของข้อมูล ประเภท ระเบียบ\n4 ดีมาก : มีคำอธิบายข้อมูล (Metadata) ครบถ้วนและกรอกครบถ้วนสมบูรณ์ทั้งหมดตามเกณฑ์ที่กำหนด", 'radio', 1, 510],
        [3, 'AV', 'sa_5_5', '5.5 มีคำอธิบายขั้นตอนการขอข้อมูลที่ไม่เผยแพร่', "1 ต่ำ : ไม่มีคำอธิบาย หรือเอกสารอธิบายขั้นตอนการขอข้อมูลที่ไม่เผยแพร่\n2 ปานกลาง : ต้องประสานงานขอขั้นตอนการขอข้อมูลจากเจ้าหน้าที่ประจำสำนักงาน หรือมีเอกสารเผยแพร่ขั้นตอน ยากต่อการเข้าถึง\n3 ดี : มีคำอธิบายขั้นตอนการขอรับข้อมูลเป็นเอกสาร หรือประกาศในช่องทางการเผยแพร่ข้อมูล\n4 ดีมาก : มีคำอธิบายขั้นตอนการขอข้อมูลที่ไม่เผยแพร่ในช่องทางที่เผยแพร่ที่ชัดเจน หรือมีมาตรการส่งมอบข้อมูลแก่ผู้ใช้ข้อมูลเพื่อรักษาความลับ", 'radio', 1, 520]
    ];

    $stmt = $conn->prepare("INSERT INTO `form_fields` (step, category, field_code, label, description, field_type, is_required, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        foreach ($step3Fields as $f) {
            $stmt->bind_param("isssssii", $f[0], $f[1], $f[2], $f[3], $f[4], $f[5], $f[6], $f[7]);
            $stmt->execute();
        }
        $stmt->close();
    }
}

// Check and auto-insert missing step1 categories
$checkStep1Cats = $conn->query("SELECT COUNT(*) as count FROM `form_categories` WHERE `step` = 1");
if ($checkStep1Cats && $checkStep1Cats->fetch_assoc()['count'] == 0) {
    $step1Cats = [
        [1, 'INFO_GENERAL', 'ส่วนที่ 1: ข้อมูลทั่วไปของข้อมูลและหน่วยงาน', 'ข้อมูลชื่อชุดข้อมูล ชื่อหน่วยงาน และภารกิจ', 'yesno', 10],
        [1, 'INFO_METRIC', 'ส่วนที่ 2: ตัวชี้วัดและเป้าหมาย', 'ชื่อตัวชี้วัด ลิงก์เชื่อมโยง ผลการประเมิน และแหล่งข้อมูล', 'yesno', 20],
        [1, 'INFO_SOURCE', 'ส่วนที่ 3: แหล่งข้อมูลและการกำหนดมาตรฐาน', 'แหล่งข้อมูล รอบการจัดเก็บ และการกำหนดมาตรฐานข้อมูล', 'yesno', 30],
        [1, 'INFO_EVAL', 'ส่วนที่ 4: กระบวนการประเมินและการอนุมัติ', 'กระบวนการประเมิน วันที่ และรายชื่อผู้ตรวจสอบ/อนุมัติ', 'yesno', 40]
    ];
    $stmt = $conn->prepare("INSERT INTO `form_categories` (step, code, title, description, category_type, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        foreach ($step1Cats as $c) {
            $stmt->bind_param("issssi", $c[0], $c[1], $c[2], $c[3], $c[4], $c[5]);
            $stmt->execute();
        }
        $stmt->close();
    }
    
    // Migrate old fields category
    $conn->query("UPDATE `form_fields` SET `category` = 'INFO_GENERAL' WHERE `field_code` IN ('info_title', 'info_agency', 'info_mission') AND `step` = 1");
    $conn->query("UPDATE `form_fields` SET `category` = 'INFO_METRIC' WHERE `field_code` IN ('metric_name', 'metric_link', 'metric_result', 'metric_source') AND `step` = 1");
    $conn->query("UPDATE `form_fields` SET `category` = 'INFO_SOURCE' WHERE `field_code` IN ('source_partner', 'source_period', 'metric_standard_type', 'metric_standard_detail') AND `step` = 1");
    $conn->query("UPDATE `form_fields` SET `category` = 'INFO_EVAL' WHERE `field_code` IN ('eval_method', 'eval_date', 'eval_team', 'eval_approver') AND `step` = 1");
}

// Check and auto-insert Step 4 General Info category
$checkS4Cat = $conn->query("SELECT id FROM `form_categories` WHERE `step` = 4 AND `code` = 'INFO_GENERAL_S4'");
if ($checkS4Cat && $checkS4Cat->num_rows === 0) {
    $conn->query("INSERT INTO `form_categories` (step, code, title, description, category_type, sort_order) VALUES (4, 'INFO_GENERAL_S4', 'ข้อมูลทั่วไป (ขั้นตอนที่ 4)', 'รายละเอียดบริการ ผู้รับผิดชอบ และวันที่ตรวจการควบคุมคุณภาพ', 'yesno', 5)");
}

// Check and auto-insert Step 4 General Info fields
$checkS4Fields = $conn->query("SELECT COUNT(*) as count FROM `form_fields` WHERE `step` = 4 AND `category` = 'INFO_GENERAL_S4'");
if ($checkS4Fields && $checkS4Fields->fetch_assoc()['count'] == 0) {
    $s4Fields = [
        [4, 'INFO_GENERAL_S4', 'info_service', 'บริการ', 'เช่น ข้อมูลสถิติการค้า', 'textarea', 1, 10],
        [4, 'INFO_GENERAL_S4', 'info_head', 'หัวหน้า กอง/สำนัก/ฝ่าย/ศูนย์ และ/หรือ บริการ', 'เช่น ผู้อำนวยการศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร', 'textarea', 1, 20],
        [4, 'INFO_GENERAL_S4', 'control_date', 'วันที่ประเมินผลควบคุม', '', 'date', 1, 30]
    ];
    $stmt = $conn->prepare("INSERT INTO `form_fields` (step, category, field_code, label, description, field_type, is_required, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        foreach ($s4Fields as $f) {
            $stmt->bind_param("isssssii", $f[0], $f[1], $f[2], $f[3], $f[4], $f[5], $f[6], $f[7]);
            $stmt->execute();
        }
        $stmt->close();
    }
}
?>

