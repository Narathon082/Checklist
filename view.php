<?php
// view.php
// Beautiful print-ready details and report view page for DQA Checklist submissions
session_start();
require_once 'db.php';

function formatChecklistDate($dateStr) {
    if (empty($dateStr)) return "-";
    if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $dateStr, $matches)) {
        $yearBE = intval($matches[1]) + 543;
        return "{$matches[3]}/{$matches[2]}/{$yearBE}";
    }
    return htmlspecialchars($dateStr);
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ไม่พบรหัสใบงานประเมินที่ระบุ");
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM submissions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    die("ไม่พบข้อมูลใบงานประเมินในระบบ");
}

// Decode and merge JSON answers into row for seamless compatibility
if (!empty($row['answers_json'])) {
    $answers = json_decode($row['answers_json'], true);
    if (is_array($answers)) {
        $row = array_merge($row, $answers);
    }
}

// Calculate scores for Self-Assessment (Section 3 / Page 4)
$catScores = [0, 0, 0, 0, 0];
$catCounts = [0, 0, 0, 0, 0];

// Category 1: Accuracy & Completeness (sa_1_1 to sa_1_5)
for ($i = 1; $i <= 5; $i++) {
    $val = $row["sa_1_$i"] ?? null;
    if ($val !== '' && $val !== null) {
        $catScores[0] += intval($val);
        $catCounts[0]++;
    }
}
// Category 2: Consistency (sa_2_1 to sa_2_6)
for ($i = 1; $i <= 6; $i++) {
    $val = $row["sa_2_$i"] ?? null;
    if ($val !== '' && $val !== null) {
        $catScores[1] += intval($val);
        $catCounts[1]++;
    }
}
// Category 3: Relevancy (sa_3_1 to sa_3_2)
for ($i = 1; $i <= 2; $i++) {
    $val = $row["sa_3_$i"] ?? null;
    if ($val !== '' && $val !== null) {
        $catScores[2] += intval($val);
        $catCounts[2]++;
    }
}
// Category 4: Timeliness (sa_4_1 to sa_4_4)
for ($i = 1; $i <= 4; $i++) {
    $val = $row["sa_4_$i"] ?? null;
    if ($val !== '' && $val !== null) {
        $catScores[3] += intval($val);
        $catCounts[3]++;
    }
}
// Category 5: Availability (sa_5_1 to sa_5_5)
for ($i = 1; $i <= 5; $i++) {
    $val = $row["sa_5_$i"] ?? null;
    if ($val !== '' && $val !== null) {
        $catScores[4] += intval($val);
        $catCounts[4]++;
    }
}

$chartData = [0, 0, 0, 0, 0];
for ($i = 0; $i < 5; $i++) {
    if ($catCounts[$i] > 0) {
        $chartData[$i] = round($catScores[$i] / $catCounts[$i], 2);
    }
}

// Overall score
$overallSum = 0;
$validCats = 0;
for ($i = 0; $i < 5; $i++) {
    if ($chartData[$i] > 0) {
        $overallSum += $chartData[$i];
        $validCats++;
    }
}
$overallScore = $validCats > 0 ? round($overallSum / $validCats, 2) : 0;

// Date is stored as a string (วัน/เดือน/ปี) directly.

// Question dictionaries
$questions_dim = [
    'ac' => [
        'title' => 'ความถูกต้อง และสมบูรณ์ (Accuracy and Completeness)',
        'items' => [
            'ac1' => 'ข้อมูลมีความถูกต้องและผ่านการตรวจสอบความครบถ้วน',
            'ac2' => 'ข้อมูลมีแหล่งที่มาที่น่าเชื่อถือ ตรวจสอบแหล่งอ้างอิงได้',
            'ac3' => 'ข้อมูลที่เก็บรวบรวมมีปริมาณที่เพียงพอและตรงตาม KPI',
            'ac4' => 'ผลลัพธ์การรวบรวมข้อมูลอยู่ในช่วงที่เป็นไปได้และสมเหตุสมผล',
            'ac5' => 'ระเบียบวิธีวิจัยที่ใช้รวบรวมข้อมูลเหมาะสม ถูกต้อง และเป็นกลาง',
            'ac6' => 'มีขั้นตอนแก้ไขความผิดพลาดและลดข้อจำกัดในการนำเข้าข้อมูล',
            'ac7' => 'มีการประเมินและตรวจพบปัญหาในการรวบรวมข้อมูลอย่างเหมาะสม',
            'ac8' => 'มีวิธีการหรือระบบป้องกันเพื่อรักษาความปลอดภัยของข้อมูล'
        ]
    ],
    're' => [
        'title' => 'ตรงตามความต้องการของผู้ใช้ (Relevancy)',
        'items' => [
            're1' => 'ข้อมูลตรงตามความต้องการและวัตถุประสงค์การใช้งานของผู้ใช้',
            're2' => 'ต้นทุนของการปรับปรุงคุณภาพคุ้มค่ากับประโยชน์ที่จะได้รับ',
            're3' => 'มีการกำหนดเกณฑ์ส่วนเกินของความคลาดเคลื่อนที่รับได้',
            're4' => 'มีกระบวนการและวิธีการตรวจสอบข้อมูลที่ซ้ำซ้อนหรือขาดหาย',
            're5' => 'ชุดข้อมูลส่วนใหญ่เป็นชุดข้อมูลที่มีคุณค่าสูง (High Value Datasets)'
        ]
    ],
    'co' => [
        'title' => 'ความสอดคล้องกัน (Consistency)',
        'items' => [
            'co1' => 'มีรูปแบบการจัดเก็บที่เป็นมาตรฐานเดียวกัน ทั้งนิยามและหน่วยนับ',
            'co2' => 'คำตอบมีแนวโน้มไปในทิศทางเดียวกัน ไม่มีกรณีขัดแย้งเชิงตรรกะ',
            'co3' => 'คำอธิบายรายละเอียดสอดคล้องกับพจนานุกรมข้อมูล (Metadata)',
            'co4' => 'ชุดข้อมูลมีความสอดคล้องและเชื่อมโยงเข้ากับชุดข้อมูลภายนอกอื่น ๆ ได้'
        ]
    ],
    'ti' => [
        'title' => 'ความเป็นปัจจุบัน (Timeliness)',
        'items' => [
            'ti1' => 'มีการระบุกรอบเวลาที่ชัดเจนในขั้นตอนการรวบรวมประเมินผล',
            'ti2' => 'มีการปรับปรุงข้อมูลตามความถี่ที่กำหนดให้สอดคล้องกับข้อเท็จจริง',
            'ti3' => 'ระยะเวลาความหน่วงข้อมูลสั้นเพียงพอที่จะนำไปใช้ตัดสินใจได้ทัน',
            'ti4' => 'มีการอัปเดตข้อมูลให้เป็นปัจจุบันทันทีตามการเปลี่ยนสถานการณ์',
            'ti5' => 'ระยะเวลาในการปรับปรุงเป็นไปตามที่ระบุไว้ในเกณฑ์บริการของหน่วยงาน'
        ]
    ],
    'av' => [
        'title' => 'ความพร้อมใช้ (Availability)',
        'items' => [
            'av1' => 'รูปแบบไฟล์และโครงสร้างข้อมูลสะดวกต่อการนำไปประมวลผลต่อ',
            'av2' => 'มีคู่มืออธิบายรายละเอียดชุดข้อมูลและการทำงานของระบบที่เกี่ยวข้อง',
            'av3' => 'ผู้ประเมินสามารถเข้าถึงประวัติข้อมูลย้อนหลังและบันทึกอ้างอิงได้ง่าย',
            'av4' => 'การเข้าถึงและการส่งต่อข้อมูลสอดคล้องกับกฎระเบียบและสิทธิ์ใช้งาน'
        ]
    ]
];

$questions_page5 = [
    'g' => [
        'title' => 'ธรรมาภิบาลข้อมูล (Data Governance)',
        'count' => 7,
        'labels' => [
            'g1' => 'มีแผนการดำเนินงานธรรมาภิบาลข้อมูลภายในหน่วยงานและสอดคล้องกับนโยบายกระทรวงพาณิชย์',
            'g2' => 'มีการระบุสิทธิ์ หน้าที่ และความรับผิดชอบของบุคลากรต่อข้อมูล (Data Owner, Data Custodian) อย่างชัดเจน',
            'g3' => 'มีกระบวนการและมาตรฐานการจัดเก็บข้อมูล การเข้าถึงข้อมูลอย่างเป็นระบบ',
            'g4' => 'มีการจัดทำพจนานุกรมข้อมูล (Data Dictionary) และคำอธิบายข้อมูล (Metadata) ที่ชัดเจน',
            'g5' => 'มีคณะกรรมการหรือคณะทำงานทำหน้าที่ควบคุมดูแลและรักษาคุณภาพของข้อมูลอย่างสม่ำเสมอ',
            'g6' => 'มีการทบทวนกระบวนการบริหารจัดการข้อมูลและการเปิดเผยข้อมูลในรูปแบบ Open Data',
            'g7' => 'มีแนวทางปฏิบัติหรือคู่มือการทำงานเกี่ยวกับความปลอดภัยข้อมูลและการควบคุมการเข้าใช้งาน'
        ]
    ],
    'p' => [
        'title' => 'ความเป็นส่วนตัวและความปลอดภัย (Security & Privacy)',
        'count' => 7,
        'labels' => [
            'p1' => 'มีมาตรการรักษาความปลอดภัยของข้อมูลตามมาตรฐานสากล เช่น การเข้ารหัสข้อมูล (Encryption)',
            'p2' => 'มีระบบคัดกรองและการอนุญาตการเข้าถึงข้อมูลตามบทบาทหน้าที่ (Role-based Access Control)',
            'p3' => 'มีมาตรการคุ้มครองข้อมูลส่วนบุคคล (PDPA) สำหรับข้อมูลที่มีความละเอียดอ่อน',
            'p4' => 'มีแผนสำรองข้อมูล (Backup) และแผนกู้คืนระบบเมื่อเกิดภัยพิบัติ (Disaster Recovery Plan)',
            'p5' => 'มีการบันทึกประวัติการเข้าใช้งานและเข้าถึงข้อมูลของระบบ (System Logs/Audit Trails) เพื่อตรวจสอบ',
            'p6' => 'มีขั้นตอนแจ้งเตือนและระงับการเข้าถึงทันทีเมื่อตรวจสอบพบการละเมิดความปลอดภัยของข้อมูล',
            'p7' => 'มีการประเมินและทบทวนความเสี่ยงด้านความปลอดภัยของข้อมูลเป็นประจำทุกปี'
        ]
    ],
    's' => [
        'title' => 'การใช้ประโยชน์และวิเคราะห์ข้อมูล (Analytics & Usage)',
        'count' => 9,
        'labels' => [
            's1' => 'มีการนำข้อมูลไปวิเคราะห์ประมวลผลเพื่อประกอบการบริหารจัดการระดับนโยบายของกอง/สำนัก',
            's2' => 'มีการนำข้อมูลไปสร้างระบบแดชบอร์ด (Dashboard) รายงานเชิงบริหาร หรือระบบเตือนภัย',
            's3' => 'มีการเปิดเผยเชื่อมโยงข้อมูลระหว่างหน่วยงานในสังกัดกระทรวงพาณิชย์',
            's4' => 'มีการพัฒนาโมเดลหรือระบบปัญญาประดิษฐ์เพื่อช่วยคาดการณ์สถานการณ์ข้อมูลอนาคต',
            's5' => 'ข้อมูลที่เผยแพร่ช่วยให้ผู้มีส่วนได้ส่วนเสียนำไปใช้งานได้ง่าย เช่น รองรับการดาวน์โหลดไฟล์ CSV/JSON',
            's6' => 'มีกระบวนการรับฟังความคิดเห็นจากผู้ใช้งานข้อมูล เพื่อนำกลับมาปรับปรุงและแก้ไขคุณภาพข้อมูล',
            's7' => 'มีการแชร์ข้อมูลร่วมกันระหว่างหน่วยงานภาครัฐในกรอบความร่วมมือการแลกเปลี่ยนข้อมูลภาครัฐ (Linkage)',
            's8' => 'มีการจัดระดับความลับของข้อมูลเพื่อการแบ่งปัน (Data Classification) ที่ถูกต้องเหมาะสม',
            's9' => 'มีการทำรายงานสรุปข้อมูลสถิติตัวชี้วัดเผยแพร่ต่อสาธารณะอย่างสม่ำเสมอ'
        ]
    ],
    'e' => [
        'title' => 'ทรัพยากรและศักยภาพบุคลากร (Resources & Skills)',
        'count' => 4,
        'labels' => [
            'e1' => 'หน่วยงานจัดสรรงบประมาณเพียงพอสำหรับการปรับปรุงระบบข้อมูลและอุปกรณ์เทคโนโลยีสารสนเทศ',
            'e2' => 'มีบุคลากรที่มีความเชี่ยวชาญเฉพาะด้าน (เช่น Data Scientist, Data Engineer) ประจำงานดูแล',
            'e3' => 'มีการจัดฝึกอบรมความรู้ด้านคุณภาพข้อมูลและทักษะดิจิทัลแก่บุคลากรอย่างสม่ำเสมอ',
            'e4' => 'มีโครงสร้างพื้นฐานระบบเครือข่ายและเครื่องแม่ข่ายที่เสถียร รองรับการใช้งานโดยไม่สะดุด'
        ]
    ],
    'd' => [
        'title' => 'ทิศทางนโยบายและเป้าหมาย (Policy & Strategy)',
        'count' => 5,
        'labels' => [
            'd1' => 'มีการกำหนดนโยบายบริหารจัดการข้อมูลของหน่วยงานสอดคล้องกับยุทธศาสตร์ชาติและแผนพัฒนาดิจิทัล',
            'd2' => 'เป้าหมายการให้บริการข้อมูลของหน่วยงานมีการชี้วัดความคุ้มค่าและผลประโยชน์ต่อประชาชนเด่นชัด',
            'd4' => 'มีทิศทางแผนงานขยายการบูรณาการข้อมูลเชื่อมต่อโครงข่ายกลางของกระทรวงพาณิชย์และภาครัฐ',
            'd5' => 'หน่วยงานกำหนดให้ประเด็นด้านการจัดการและคุณภาพข้อมูลเป็นหนึ่งในตัวชี้วัดผลการทำงานหลักขององค์กร'
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานรายละเอียดการตรวจประเมินคุณภาพข้อมูล - DQA Checklist</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&family=Outfit:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    
    <style>
        .report-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 1.25rem 2rem;
            border-radius: var(--radius-lg);
            margin-bottom: 2rem;
            box-shadow: var(--shadow-card);
            border-bottom: 3px solid var(--moc-gold);
        }

        .report-section {
            background: white;
            border-radius: var(--radius-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-paper);
        }

        .report-section h3 {
            color: var(--moc-blue-deep);
            font-size: 1.2rem;
            font-weight: 700;
            border-bottom: 2px solid var(--moc-gold);
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Detail Grid */
        .info-detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.2rem;
        }

        @media (max-width: 768px) {
            .info-detail-grid {
                grid-template-columns: 1fr;
            }
        }

        .info-detail-item {
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 0.6rem;
        }

        .info-detail-item strong {
            font-size: 0.88rem;
            color: var(--text-muted);
            display: block;
            margin-bottom: 0.2rem;
        }

        .info-detail-item span {
            font-size: 1rem;
            color: var(--text-dark);
            font-weight: 500;
        }

        /* Quality Checklist Status */
        .status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
            padding: 0.35rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 700;
            white-space: nowrap;
            min-width: 85px;
            box-sizing: border-box;
        }

        .status-pill.yes {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-pill.no {
            background-color: #fef2f2;
            color: #991b1b;
        }

        /* Self Assessment Styles */
        .self-assess-summary-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 2rem;
            align-items: center;
        }

        @media (max-width: 768px) {
            .self-assess-summary-grid {
                grid-template-columns: 1fr;
            }
        }

        .overall-score-large {
            text-align: center;
            background: var(--moc-blue-light);
            border-radius: var(--radius-lg);
            padding: 2rem;
            border: 1px solid var(--border-color);
        }

        .score-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
            font-size: 2.5rem;
            font-weight: 800;
            background: white;
            box-shadow: var(--shadow-card);
            font-family: var(--font-heading);
            border: 4px solid var(--moc-gold);
        }

        /* Premium Table Customizations */
        .table-responsive {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            margin-bottom: 2.2rem;
            background: white;
            width: 100%;
        }

        .db-table, .alignment-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0;
        }

        .db-table th, .alignment-table th {
            background-color: #f8fafc;
            color: #334155;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 1rem;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
            white-space: normal;
            word-wrap: break-word;
        }

        /* Center-align Code (1st column) and Status (3rd column) */
        .db-table th:first-child, .alignment-table th:first-child,
        .db-table td:first-child, .alignment-table td:first-child,
        .db-table th:nth-child(3), .alignment-table th:nth-child(3),
        .db-table td:nth-child(3), .alignment-table td:nth-child(3) {
            text-align: center !important;
        }

        .db-table td, .alignment-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            color: #1e293b;
            vertical-align: top;
            line-height: 1.6;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: anywhere;
            white-space: pre-line;
        }

        .db-table tr:nth-child(even) td, .alignment-table tr:nth-child(even) td {
            background-color: #fcfdfe;
        }

        .db-table tr:hover td, .alignment-table tr:hover td {
            background-color: #f8fafc;
        }

        .db-table td:first-child, .alignment-table td:first-child {
            font-weight: 700;
            color: #0c3c78; /* Brand deep blue */
        }

        .db-table td:last-child, .alignment-table td:last-child {
            color: #475569; /* Slate gray for readability */
            font-size: 0.85rem;
        }

        .badge-align {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 700;
            white-space: nowrap;
            text-align: center;
            min-width: 110px;
            box-sizing: border-box;
        }

        .badge-align.good { background-color: #d1fae5; color: #065f46; }
        .badge-align.partial { background-color: #fef3c7; color: #92400e; }
        .badge-align.none { background-color: #fee2e2; color: #991b1b; }
        .badge-align.yes { background-color: #dbeafe; color: #1e40af; }

        /* Print formatting */
        @media print {
            body {
                background-color: white !important;
                padding: 0 !important;
            }
            .container {
                max-width: 100% !important;
            }
            .report-actions, .top-bar-accent, .no-print {
                display: none !important;
            }
            .report-section {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin-bottom: 3rem !important;
                page-break-inside: avoid;
            }
            .report-section h3 {
                border-bottom: 2px solid black !important;
            }
        }
    </style>
</head>
<body>
    <div class="top-bar-accent"></div>

    <div class="container">
        <!-- Actions Toolbar -->
        <div class="report-actions no-print">
            <div style="display:flex; gap:0.5rem;">
                <a href="dashboard.php" class="btn btn-secondary" style="text-decoration:none; display:inline-flex; align-items:center;">
                    <i data-lucide="arrow-left" style="width:16px; height:16px; margin-right:4px;"></i> แดชบอร์ด
                </a>
            </div>
            <div style="display:flex; gap:0.5rem;">
                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary" style="text-decoration:none; display:inline-flex; align-items:center;">
                    <i data-lucide="edit-3" style="width:16px; height:16px; margin-right:4px;"></i> แก้ไขรายงาน
                </a>
                <button onclick="window.print()" class="btn btn-primary" style="display:inline-flex; align-items:center;">
                    <i data-lucide="printer" style="width:16px; height:16px; margin-right:4px;"></i> พิมพ์รายงาน
                </button>
            </div>
        </div>

        <!-- Official Header Style Report -->
        <main class="document-paper">
            <div class="paper-header" style="text-align: center; border-bottom: 3px double var(--moc-gold); padding-bottom: 1.5rem; margin-bottom: 2rem;">
                <div class="gov-seal" style="margin: 0 auto 1rem auto; width: 64px; height: 64px;">
                    <i data-lucide="award" style="width:32px; height:32px; color: var(--moc-gold);"></i>
                </div>
                <h2 style="font-size: 1.6rem; color: var(--moc-blue-deep); font-weight: 700;">รายงานสรุปผลการตรวจประเมินคุณภาพข้อมูล (DQA Checklist)</h2>
                <span class="agency-tag" style="font-size: 1rem; color: var(--text-muted); font-weight: 500;">
                    <?php echo htmlspecialchars($row['info_agency'] ?: 'สำนักงานปลัดกระทรวงพาณิชย์'); ?>
                </span>
            </div>

            <!-- SECTION 1: General Info -->
            <section class="report-section">
                <h3><i data-lucide="info"></i> ส่วนที่ 1: ข้อมูลทั่วไปของข้อมูลและหน่วยงาน</h3>
                <div class="info-detail-grid">
                    <div class="info-detail-item" style="grid-column: span 2;">
                        <strong>ชื่องานประเมิน / ชุดข้อมูล</strong>
                        <span><?php echo htmlspecialchars(($row['info_title'] ?? '') ?: '-'); ?></span>
                    </div>
                    <div class="info-detail-item">
                        <strong>ชื่อหน่วยงานที่ดำเนินงาน</strong>
                        <span><?php echo htmlspecialchars(($row['info_agency'] ?? '') ?: '-'); ?></span>
                    </div>
                    <div class="info-detail-item">
                        <strong>พันธกิจ / ยุทธศาสตร์ของหน่วยงาน</strong>
                        <span><?php echo htmlspecialchars(($row['info_mission'] ?? '') ?: '-'); ?></span>
                    </div>
                    <div class="info-detail-item" style="grid-column: span 2;">
                        <strong>ชื่อตัวชี้วัดผลการประเมินคุณภาพข้อมูล</strong>
                        <span><?php echo htmlspecialchars(($row['metric_name'] ?? '') ?: '-'); ?></span>
                    </div>
                    <div class="info-detail-item">
                        <strong>ลิงก์ในการเข้าถึงข้อมูลตัวชี้วัด (URL)</strong>
                        <span>
                            <?php if(!empty($row['metric_link'])): ?>
                                <a href="<?php echo htmlspecialchars($row['metric_link']); ?>" target="_blank"><?php echo htmlspecialchars($row['metric_link']); ?></a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="info-detail-item">
                        <strong>ผลลัพธ์ตัวชี้วัดปีงบประมาณปัจจุบัน</strong>
                        <span><?php echo htmlspecialchars(($row['metric_result'] ?? '') ?: '-'); ?></span>
                    </div>
                    <div class="info-detail-item">
                        <strong>แหล่งที่มาข้อมูลที่ใช้วิเคราะห์ตัวชี้วัด</strong>
                        <span><?php echo htmlspecialchars(($row['metric_source'] ?? '') ?: '-'); ?></span>
                    </div>
                    <div class="info-detail-item">
                        <strong>หน่วยงานร่วมจัดทำ/เจ้าของข้อมูลหลัก</strong>
                        <span><?php echo htmlspecialchars(($row['source_partner'] ?? '') ?: '-'); ?></span>
                    </div>
                    <div class="info-detail-item">
                        <strong>ความถี่ในการจัดทำข้อมูล</strong>
                        <span><?php echo htmlspecialchars(($row['source_period'] ?? '') ?: '-'); ?></span>
                    </div>
                    <div class="info-detail-item">
                        <strong>ประเภทมาตรฐานของตัวชี้วัด</strong>
                        <span><?php echo htmlspecialchars(($row['metric_standard_type'] ?? '') ?: '-'); ?></span>
                    </div>
                    <div class="info-detail-item" style="grid-column: span 2;">
                        <strong>รายละเอียดมาตรฐานการจัดทำข้อมูล</strong>
                        <span><?php echo htmlspecialchars(($row['metric_standard_detail'] ?? '') ?: '-'); ?></span>
                    </div>
                    <div class="info-detail-item" style="grid-column: span 2;">
                        <strong>วิธีการรวบรวมประเมินผล</strong>
                        <span><?php echo htmlspecialchars(($row['eval_method'] ?? '') ?: '-'); ?></span>
                    </div>
                    <div class="info-detail-item">
                        <strong>วันที่ประเมินคุณภาพข้อมูล (หน้าแรก)</strong>
                        <span><?php echo formatChecklistDate($row['eval_date'] ?? ''); ?></span>
                    </div>
                    <div class="info-detail-item">
                        <strong>รายชื่อคณะทำงานผู้ประเมิน</strong>
                        <span><?php echo htmlspecialchars(($row['eval_team'] ?? '') ?: '-'); ?></span>
                    </div>
                    <div class="info-detail-item">
                        <strong>ผู้อนุมัติผลการประเมิน</strong>
                        <span><?php echo htmlspecialchars(($row['eval_approver'] ?? '') ?: '-'); ?></span>
                    </div>
                </div>
            </section>

            <!-- SECTION 2: 5 Quality Dimensions Checklist -->
            <section class="report-section">
                <h3><i data-lucide="check-square"></i> ส่วนที่ 2: ผลตรวจประเมินคุณภาพข้อมูล 5 มิติ</h3>
                
                <?php foreach ($questions_dim as $dim_code => $dim_info): ?>
                    <h4 style="color: var(--moc-blue-mid); margin-top: 1.5rem; margin-bottom: 0.75rem; border-left: 3px solid var(--moc-blue-deep); padding-left: 0.5rem;">
                        <?php echo $dim_info['title']; ?>
                    </h4>
                    <div class="table-responsive">
                        <table class="db-table" style="font-size:0.9rem;">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">รหัส</th>
                                    <th style="width: 45%;">เกณฑ์ประเมิน</th>
                                    <th style="width: 100px;">สถานะ</th>
                                    <th style="width: 40%;">ความเห็น / ข้อเสนอแนะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dim_info['items'] as $item_code => $item_desc): 
                                    $status_val = $row[$item_code . '_status'] ?? '';
                                    $comment_val = $row[$item_code . '_comment'] ?? '';
                                ?>
                                    <tr>
                                        <td class="font-bold"><?php echo strtoupper($item_code); ?></td>
                                        <td><?php echo $item_desc; ?></td>
                                        <td>
                                            <?php if ($status_val === 'ใช่'): ?>
                                                <span class="status-pill yes">
                                                    <i data-lucide="check" style="width: 12px; height: 12px;"></i> ใช่
                                                </span>
                                            <?php elseif ($status_val === 'ไม่ใช่'): ?>
                                                <span class="status-pill no">
                                                    <i data-lucide="x" style="width: 12px; height: 12px;"></i> ไม่ใช่
                                                </span>
                                            <?php else: ?>
                                                <span style="color: var(--text-muted); font-style: italic;">ไม่ระบุ</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($comment_val ?: '-'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </section>

            <!-- SECTION 3: Self Assessment (Page 4 Radar) -->
            <section class="report-section">
                <h3><i data-lucide="bar-chart-2"></i> ส่วนที่ 3: ผลประเมินคุณภาพข้อมูลด้วยตนเอง (Self-Assessment)</h3>
                
                <div class="self-assess-summary-grid">
                    <div class="overall-score-large">
                        <div class="score-circle" id="overall-score-ui">
                            <?php echo $overallScore; ?>
                        </div>
                        <h4 style="color: var(--moc-blue-deep); margin-bottom: 0.5rem; font-weight: 700;">คะแนนเฉลี่ยภาพรวม</h4>
                        <p style="font-size: 0.85rem; color: var(--text-muted);">จากคะแนนเต็ม 4.00 (เกณฑ์ระดับ ดีเยี่ยม)</p>
                        
                        <div style="margin-top: 1.5rem; text-align: left; font-size: 0.85rem; line-height: 1.6;" id="score-details-legend">
                            <strong>คะแนนแยกตามมิติคุณภาพ:</strong>
                            <ul style="padding-left: 1.2rem; margin-top: 0.4rem;">
                                <li>ความถูกต้องและสมบูรณ์: <strong><?php echo $chartData[0]; ?> / 4.00</strong></li>
                                <li>ความสอดคล้องกัน: <strong><?php echo $chartData[1]; ?> / 4.00</strong></li>
                                <li>ตรงตามความต้องการผู้ใช้: <strong><?php echo $chartData[2]; ?> / 4.00</strong></li>
                                <li>ความเป็นปัจจุบัน: <strong><?php echo $chartData[3]; ?> / 4.00</strong></li>
                                <li>ความพร้อมใช้: <strong><?php echo $chartData[4]; ?> / 4.00</strong></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Canvas for Radar Chart -->
                    <div style="max-width: 400px; margin: 0 auto; width: 100%;">
                        <canvas id="radarChartDetails"></canvas>
                    </div>
                </div>
            </section>

            <!-- SECTION 4: Alignments and Policy (Page 5) -->
            <section class="report-section">
                <h3><i data-lucide="compass"></i> ส่วนที่ 4 & 5: หลักฐานเชิงประจักษ์และการปรับแนวร่วม (Alignment)</h3>

                <!-- Data Quality Monitoring and Control Sign-off (Part of Section 4 & 5 - Top) -->
                <div class="info-detail-grid" style="margin-bottom: 2rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 1.5rem;">
                    <div class="info-detail-item" style="grid-column: span 2;">
                        <strong>ชื่องานประเมิน / ชุดข้อมูล</strong>
                        <span><?php echo htmlspecialchars(($row['info_title'] ?? '') ?: '-'); ?></span>
                    </div>
                    <div class="info-detail-item">
                        <strong>ชื่อหน่วยงานที่ดำเนินงาน</strong>
                        <span><?php echo htmlspecialchars(($row['info_agency'] ?? '') ?: '-'); ?></span>
                    </div>
                    <div class="info-detail-item">
                        <strong>วันที่ประเมินผลควบคุม (หน้า 5)</strong>
                        <span><?php echo formatChecklistDate($row['control_date'] ?? ''); ?></span>
                    </div>
                    <div class="info-detail-item" style="grid-column: span 2;">
                        <strong>บริการที่ดำเนินการ</strong>
                        <span><?php echo htmlspecialchars(($row['info_service'] ?? '') ?: '-'); ?></span>
                    </div>
                    <div class="info-detail-item" style="grid-column: span 2;">
                        <strong>หัวหน้า กอง/สำนัก/ฝ่าย/ศูนย์ และ/หรือ บริการ</strong>
                        <span><?php echo htmlspecialchars(($row['info_head'] ?? '') ?: '-'); ?></span>
                    </div>
                </div>
                
                <?php foreach ($questions_page5 as $key_prefix => $section_info): ?>
                    <h4 style="color: var(--moc-blue-mid); margin-top: 1.5rem; margin-bottom: 0.75rem; border-left: 3px solid var(--moc-blue-deep); padding-left: 0.5rem;">
                        <?php echo $section_info['title']; ?>
                    </h4>
                    <div class="table-responsive">
                        <table class="alignment-table">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">รหัส</th>
                                    <th style="width: 45%;">เกณฑ์พิจารณา</th>
                                    <th style="width: 140px;">สถานะการจัดทำ</th>
                                    <th style="width: 35%;">หลักฐานอ้างอิงเชิงประจักษ์</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($section_info['labels'] as $code => $desc): 
                                    $status_val = isset($row[$code]) ? $row[$code] : '';
                                    $evidence_val = isset($row[$code . '_evidence']) ? $row[$code . '_evidence'] : '';
                                    
                                    // Determine style class
                                    $badgeClass = 'none';
                                    if ($status_val === 'มีอย่างเหมาะสม' || $status_val === 'ใช่') {
                                        $badgeClass = 'good';
                                    } elseif ($status_val === 'มีบางส่วน') {
                                        $badgeClass = 'partial';
                                    } elseif ($status_val === 'ไม่มี' || $status_val === 'ไม่ใช่') {
                                        $badgeClass = 'none';
                                    }
                                ?>
                                    <tr>
                                        <td class="font-bold"><?php echo strtoupper($code); ?></td>
                                        <td style="font-size:0.9rem; line-height: 1.45;"><?php echo $desc; ?></td>
                                        <td>
                                            <?php if (!empty($status_val)): ?>
                                                <span class="badge-align <?php echo $badgeClass; ?>">
                                                    <?php echo htmlspecialchars($status_val); ?>
                                                </span>
                                            <?php else: ?>
                                                <span style="color: var(--text-muted); font-style: italic;">ไม่ระบุ</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="font-size:0.88rem;"><?php echo htmlspecialchars($evidence_val ?: '-'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </section>
        </main>
    </div>

    <!-- Script to render Radar Chart on Details view page -->
    <script>
        // Init Lucide icons
        lucide.createIcons();

        // Chart.js Radar Config
        const ctx = document.getElementById('radarChartDetails').getContext('2d');
        const radarChart = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: [
                    ['ความถูกต้อง', 'และสมบูรณ์', '(Accuracy)'],
                    ['ความสอดคล้องกัน', '(Consistency)'],
                    ['ตรงความต้องการ', 'ผู้ใช้ (Relevancy)'],
                    ['ความเป็นปัจจุบัน', '(Timeliness)'],
                    ['ความพร้อมใช้', '(Availability)']
                ],
                datasets: [{
                    label: 'คะแนนเฉลี่ย',
                    data: [
                        <?php echo $chartData[0]; ?>,
                        <?php echo $chartData[1]; ?>,
                        <?php echo $chartData[2]; ?>,
                        <?php echo $chartData[3]; ?>,
                        <?php echo $chartData[4]; ?>
                    ],
                    backgroundColor: 'rgba(12, 60, 120, 0.15)',
                    borderColor: '#0c3c78',
                    borderWidth: 2.5,
                    pointBackgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6'],
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        min: 0,
                        max: 4,
                        ticks: {
                            stepSize: 1,
                            font: { size: 9, family: 'Sarabun' },
                            backdropColor: 'transparent'
                        },
                        pointLabels: {
                            font: { size: 10, family: 'Sarabun', weight: 'bold' },
                            color: '#0c3c78'
                        },
                        grid: { color: 'rgba(100,116,139,0.15)' },
                        angleLines: { color: 'rgba(100,116,139,0.25)' }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
</body>
</html>
