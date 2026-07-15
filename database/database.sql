-- ตารางสำหรับบันทึกข้อมูลแบบประเมิน DQA Checklist แบบแยกคอลัมน์ทุกฟิลด์คำถาม
-- นำเข้า (Import) ไฟล์นี้ผ่านเมนู Import ใน phpMyAdmin ของฐานข้อมูล 'checklist'

DROP TABLE IF EXISTS `submissions`;

CREATE TABLE `submissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    
    -- ส่วนที่ 1: ข้อมูลทั่วไป
    `info_title` VARCHAR(255) DEFAULT '' COMMENT 'ชื่องานประเมิน/ชุดข้อมูล',
    `info_agency` VARCHAR(255) DEFAULT '' COMMENT 'ชื่อหน่วยงานที่ดำเนินงาน',
    `info_mission` TEXT DEFAULT NULL COMMENT 'พันธกิจ/ยุทธศาสตร์',
    `metric_name` VARCHAR(255) DEFAULT '' COMMENT 'ชื่อตัวชี้วัดผลการประเมินคุณภาพข้อมูล',
    `metric_link` TEXT DEFAULT NULL COMMENT 'ลิงก์การเข้าถึงข้อมูลตัวชี้วัด',
    `metric_result` TEXT DEFAULT NULL COMMENT 'ผลลัพธ์ตัวชี้วัดปีงบประมาณปัจจุบัน',
    `metric_source` VARCHAR(255) DEFAULT '' COMMENT 'แหล่งที่มาข้อมูล',
    `source_partner` VARCHAR(255) DEFAULT '' COMMENT 'หน่วยงานเจ้าของข้อมูล/ผู้ร่วมจัดทำ',
    `source_period` VARCHAR(255) DEFAULT '' COMMENT 'ความถี่ในการจัดทำข้อมูล',
    `metric_standard_type` VARCHAR(255) DEFAULT '' COMMENT 'ประเภทมาตรฐานของตัวชี้วัด',
    `metric_standard_detail` TEXT DEFAULT NULL COMMENT 'รายละเอียดการสอดคล้องกับมาตรฐาน',
    `eval_method` TEXT DEFAULT NULL COMMENT 'วิธีการประเมินผล',
    `eval_date` VARCHAR(100) DEFAULT '' COMMENT 'วันที่ดำเนินการประเมิน (หน้าแรก)',
    `control_date` VARCHAR(100) DEFAULT '' COMMENT 'วันที่ประเมินผลควบคุม (หน้า 5)',
    `eval_team` TEXT DEFAULT NULL COMMENT 'รายชื่อทีมผู้ประเมิน',
    `eval_approver` VARCHAR(255) DEFAULT '' COMMENT 'ชื่อผู้อนุมัติผลประเมิน',
    `info_service` VARCHAR(255) DEFAULT '' COMMENT 'บริการ',
    `info_head` VARCHAR(255) DEFAULT '' COMMENT 'หัวหน้า กอง/สำนัก/ฝ่าย/ศูนย์ และ/หรือ บริการ',
    
    -- สถานะและวันเวลาบันทึก
    `status` VARCHAR(50) DEFAULT 'draft' COMMENT 'สถานะ: draft หรือ submitted',
    
    -- ส่วนที่ 2: ความถูกต้องและสมบูรณ์ (Accuracy & Completeness)
    `ac1_status` VARCHAR(20) DEFAULT '',
    `ac1_comment` TEXT DEFAULT NULL,
    `ac2_status` VARCHAR(20) DEFAULT '',
    `ac2_comment` TEXT DEFAULT NULL,
    `ac3_status` VARCHAR(20) DEFAULT '',
    `ac3_comment` TEXT DEFAULT NULL,
    `ac4_status` VARCHAR(20) DEFAULT '',
    `ac4_comment` TEXT DEFAULT NULL,
    `ac5_status` VARCHAR(20) DEFAULT '',
    `ac5_comment` TEXT DEFAULT NULL,
    `ac6_status` VARCHAR(20) DEFAULT '',
    `ac6_comment` TEXT DEFAULT NULL,
    `ac7_status` VARCHAR(20) DEFAULT '',
    `ac7_comment` TEXT DEFAULT NULL,
    `ac8_status` VARCHAR(20) DEFAULT '',
    `ac8_comment` TEXT DEFAULT NULL,

    -- ส่วนที่ 2: ตรงตามความต้องการของผู้ใช้ (Relevance)
    `re1_status` VARCHAR(20) DEFAULT '',
    `re1_comment` TEXT DEFAULT NULL,
    `re2_status` VARCHAR(20) DEFAULT '',
    `re2_comment` TEXT DEFAULT NULL,
    `re3_status` VARCHAR(20) DEFAULT '',
    `re3_comment` TEXT DEFAULT NULL,
    `re4_status` VARCHAR(20) DEFAULT '',
    `re4_comment` TEXT DEFAULT NULL,
    `re5_status` VARCHAR(20) DEFAULT '',
    `re5_comment` TEXT DEFAULT NULL,

    -- ส่วนที่ 2: ความสอดคล้องกัน (Consistency)
    `co1_status` VARCHAR(20) DEFAULT '',
    `co1_comment` TEXT DEFAULT NULL,
    `co2_status` VARCHAR(20) DEFAULT '',
    `co2_comment` TEXT DEFAULT NULL,
    `co3_status` VARCHAR(20) DEFAULT '',
    `co3_comment` TEXT DEFAULT NULL,
    `co4_status` VARCHAR(20) DEFAULT '',
    `co4_comment` TEXT DEFAULT NULL,

    -- ส่วนที่ 2: ความเป็นปัจจุบัน (Timeliness)
    `ti1_status` VARCHAR(20) DEFAULT '',
    `ti1_comment` TEXT DEFAULT NULL,
    `ti2_status` VARCHAR(20) DEFAULT '',
    `ti2_comment` TEXT DEFAULT NULL,
    `ti3_status` VARCHAR(20) DEFAULT '',
    `ti3_comment` TEXT DEFAULT NULL,
    `ti4_status` VARCHAR(20) DEFAULT '',
    `ti4_comment` TEXT DEFAULT NULL,
    `ti5_status` VARCHAR(20) DEFAULT '',
    `ti5_comment` TEXT DEFAULT NULL,

    -- ส่วนที่ 2: ความพร้อมใช้ (Availability)
    `av1_status` VARCHAR(20) DEFAULT '',
    `av1_comment` TEXT DEFAULT NULL,
    `av2_status` VARCHAR(20) DEFAULT '',
    `av2_comment` TEXT DEFAULT NULL,
    `av3_status` VARCHAR(20) DEFAULT '',
    `av3_comment` TEXT DEFAULT NULL,
    `av4_status` VARCHAR(20) DEFAULT '',
    `av4_comment` TEXT DEFAULT NULL,

    -- ส่วนที่ 3: คะแนนการประเมินตนเอง (Self-Assessment Ratings)
    `sa_1_1` VARCHAR(20) DEFAULT '',
    `sa_1_2` VARCHAR(20) DEFAULT '',
    `sa_1_3` VARCHAR(20) DEFAULT '',
    `sa_1_4` VARCHAR(20) DEFAULT '',
    `sa_1_5` VARCHAR(20) DEFAULT '',
    `sa_2_1` VARCHAR(20) DEFAULT '',
    `sa_2_2` VARCHAR(20) DEFAULT '',
    `sa_2_3` VARCHAR(20) DEFAULT '',
    `sa_2_4` VARCHAR(20) DEFAULT '',
    `sa_2_5` VARCHAR(20) DEFAULT '',
    `sa_2_6` VARCHAR(20) DEFAULT '',
    `sa_3_1` VARCHAR(20) DEFAULT '',
    `sa_3_2` VARCHAR(20) DEFAULT '',
    `sa_4_1` VARCHAR(20) DEFAULT '',
    `sa_4_2` VARCHAR(20) DEFAULT '',
    `sa_4_3` VARCHAR(20) DEFAULT '',
    `sa_4_4` VARCHAR(20) DEFAULT '',
    `sa_5_1` VARCHAR(20) DEFAULT '',
    `sa_5_2` VARCHAR(20) DEFAULT '',
    `sa_5_3` VARCHAR(20) DEFAULT '',
    `sa_5_4` VARCHAR(20) DEFAULT '',
    `sa_5_5` VARCHAR(20) DEFAULT '',

    -- ส่วนที่ 4 และ 5: ผลการประเมินและหลักฐานอ้างอิง
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
    `d4` VARCHAR(20) DEFAULT '',
    `d4_evidence` TEXT DEFAULT NULL,
    `d5` VARCHAR(20) DEFAULT '',
    `d5_evidence` TEXT DEFAULT NULL,

    `r1` VARCHAR(50) DEFAULT '',
    `r1_evidence` TEXT DEFAULT NULL,

    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Table structure for table `agencies`
--
DROP TABLE IF EXISTS `agencies`;
CREATE TABLE `agencies` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `agencies`
--
INSERT INTO `agencies` (`name`) VALUES
('กองกลาง (กก.)'),
('กองตรวจราชการ (กตร.)'),
('กองบริหารการคลัง (กบค.)'),
('กองบริหารการพาณิชย์ภูมิภาค (กบภ.)'),
('กองบริหารทรัพยากรบุคคล (กบบ.)'),
('กองยุทธศาสตร์และแผนงาน (กยผ.)'),
('ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร (ศทส.)'),
('สถาบันกรมพระจันทบุรีนฤนาถ (สจป.)'),
('กลุ่มกฎหมาย (กม.)'),
('กลุ่มตรวจสอบภายใน (กตน.)'),
('กลุ่มพัฒนาระบบบริหาร (กพร.)'),
('ศูนย์ปฏิบัติการต่อต้านการทุจริต (ศปท.)');

