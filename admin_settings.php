<?php
// admin_settings.php
// Highly polished dynamic administration page for MOC DQA Checklist configuration
session_start();
require_once 'db.php';

// Handle POST actions for CRUD operations via AJAX/Form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    header('Content-Type: application/json; charset=utf-8');
    $action = $_GET['action'];
    $response = ['status' => 'error', 'message' => 'Invalid action'];

    // 1. Update general metadata settings
    if ($action === 'update_metadata') {
        $settings = $_POST['settings'] ?? [];
        $successCount = 0;
        $stmt = $conn->prepare("UPDATE `form_config_metadata` SET `setting_value` = ? WHERE `setting_key` = ?");
        
        if ($stmt) {
            foreach ($settings as $key => $val) {
                $stmt->bind_param("ss", $val, $key);
                if ($stmt->execute()) {
                    $successCount++;
                }
            }
            $stmt->close();
            $response = ['status' => 'success', 'message' => "อัปเดตคำชี้แจง/หมายเหตุสำเร็จ {$successCount} รายการ"];
        } else {
            $response = ['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: ' . $conn->error];
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 2. Manage Agencies
    if ($action === 'manage_agencies') {
        $subAction = $_POST['sub_action'] ?? '';
        if ($subAction === 'add') {
            $name = trim($_POST['name'] ?? '');
            if (empty($name)) {
                $response = ['status' => 'error', 'message' => 'กรุณากรอกชื่อหน่วยงาน'];
            } else {
                $stmt = $conn->prepare("INSERT INTO agencies (name) VALUES (?)");
                if ($stmt) {
                    $stmt->bind_param("s", $name);
                    if ($stmt->execute()) {
                        $response = ['status' => 'success', 'message' => 'เพิ่มหน่วยงานสำเร็จ', 'id' => $conn->insert_id];
                    } else {
                        $response = ['status' => 'error', 'message' => 'ชื่อหน่วยงานนี้มีอยู่ในระบบแล้ว'];
                    }
                    $stmt->close();
                }
            }
        } elseif ($subAction === 'edit') {
            $id = intval($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            if ($id > 0 && !empty($name)) {
                $stmt = $conn->prepare("UPDATE agencies SET name = ? WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("si", $name, $id);
                    if ($stmt->execute()) {
                        $response = ['status' => 'success', 'message' => 'แก้ไขชื่อหน่วยงานสำเร็จ'];
                    } else {
                        $response = ['status' => 'error', 'message' => 'ชื่อหน่วยงานนี้มีอยู่ในระบบแล้วหรือข้อมูลไม่ถูกต้อง'];
                    }
                    $stmt->close();
                }
            }
        } elseif ($subAction === 'delete') {
            $id = intval($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $conn->prepare("DELETE FROM agencies WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("i", $id);
                    if ($stmt->execute()) {
                        $response = ['status' => 'success', 'message' => 'ลบหน่วยงานสำเร็จ'];
                    } else {
                        $response = ['status' => 'error', 'message' => 'ไม่สามารถลบหน่วยงานได้: ' . $conn->error];
                    }
                    $stmt->close();
                }
            }
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 3. Manage Form Fields / Criteria
    if ($action === 'manage_fields') {
        $subAction = $_POST['sub_action'] ?? '';
        
        if ($subAction === 'save') {
            $id = intval($_POST['id'] ?? 0);
            $step = intval($_POST['step'] ?? 1);
            $category = trim($_POST['category'] ?? '');
            $field_code = strtolower(trim($_POST['field_code'] ?? ''));
            $label = trim($_POST['label'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $field_type = trim($_POST['field_type'] ?? 'textarea');
            $is_required = isset($_POST['is_required']) ? 1 : 0;
            $sort_order = intval($_POST['sort_order'] ?? 0);

            if (empty($field_code) || empty($label)) {
                echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกรหัสฟิลด์และหัวข้อคำถาม/เกณฑ์ประเมิน'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Ensure field_code is safe and conforms to lowercase alphanumeric
            if (!preg_match('/^[a-z0-9_]+$/', $field_code)) {
                echo json_encode(['status' => 'error', 'message' => 'รหัสฟิลด์ต้องเป็นภาษาอังกฤษตัวเล็ก ตัวเลข หรือเครื่องหมาย _ เท่านั้น'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Perform DB auto-alteration for submissions table if columns don't exist
            $alterQueries = [];
            if ($step === 1) {
                // Single column for data
                $alterQueries[] = "ALTER TABLE submissions ADD COLUMN `$field_code` TEXT DEFAULT NULL";
            } elseif ($step === 2) {
                // Two columns: _status and _comment
                $statusCol = $field_code . "_status";
                $commentCol = $field_code . "_comment";
                $alterQueries[] = "ALTER TABLE submissions ADD COLUMN `$statusCol` VARCHAR(10) DEFAULT ''";
                $alterQueries[] = "ALTER TABLE submissions ADD COLUMN `$commentCol` TEXT DEFAULT NULL";
            } elseif ($step === 3) {
                // Single column for ratings
                $alterQueries[] = "ALTER TABLE submissions ADD COLUMN `$field_code` VARCHAR(5) DEFAULT ''";
            } elseif ($step === 4) {
                // Two columns: code and _evidence
                $statusCol = $field_code;
                $evidenceCol = $field_code . "_evidence";
                $alterQueries[] = "ALTER TABLE submissions ADD COLUMN `$statusCol` VARCHAR(20) DEFAULT ''";
                $alterQueries[] = "ALTER TABLE submissions ADD COLUMN `$evidenceCol` TEXT DEFAULT NULL";
            }

            // Run table alterations if needed
            foreach ($alterQueries as $q) {
                $checkCol = "";
                if (preg_match('/ADD COLUMN `([a-z0-9_]+)`/', $q, $m)) {
                    $checkCol = $m[1];
                }
                
                if (!empty($checkCol)) {
                    $colRes = $conn->query("SHOW COLUMNS FROM `submissions` LIKE '$checkCol'");
                    if ($colRes && $colRes->num_rows === 0) {
                        if (!$conn->query($q)) {
                            // Ignore error if column already exists due to some glitch, else log it
                        }
                    }
                }
            }

            if ($id > 0) {
                // Edit existing field
                $stmt = $conn->prepare("UPDATE `form_fields` SET step = ?, category = ?, label = ?, description = ?, field_type = ?, is_required = ?, sort_order = ? WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("isssssii", $step, $category, $label, $description, $field_type, $is_required, $sort_order, $id);
                    if ($stmt->execute()) {
                        $response = ['status' => 'success', 'message' => 'บันทึกการแก้ไขเกณฑ์/ฟิลด์สำเร็จ'];
                    } else {
                        $response = ['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการแก้ไข: ' . $conn->error];
                    }
                    $stmt->close();
                }
            } else {
                // Add new field
                $stmt = $conn->prepare("INSERT INTO `form_fields` (step, category, field_code, label, description, field_type, is_required, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("isssssii", $step, $category, $field_code, $label, $description, $field_type, $is_required, $sort_order);
                    if ($stmt->execute()) {
                        $response = ['status' => 'success', 'message' => 'เพิ่มเกณฑ์/ฟิลด์ประเมินเรียบร้อยแล้ว และระบบเตรียมโครงสร้างตารางข้อมูล submissions อัตโนมัติ'];
                    } else {
                        $response = ['status' => 'error', 'message' => 'รหัสฟิลด์นี้ซ้ำในระบบ หรือ เกิดข้อผิดพลาด: ' . $conn->error];
                    }
                    $stmt->close();
                }
            }
        } elseif ($subAction === 'delete') {
            $id = intval($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $conn->prepare("DELETE FROM `form_fields` WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("i", $id);
                    if ($stmt->execute()) {
                        $response = ['status' => 'success', 'message' => 'ลบเกณฑ์/ฟิลด์ออกจากระบบการกรอกข้อมูลแล้ว (เพื่อความปลอดภัย ข้อมูลเดิมในฐานข้อมูลจะไม่ถูกลบ)'];
                    } else {
                        $response = ['status' => 'error', 'message' => 'ไม่สามารถลบเกณฑ์ได้: ' . $conn->error];
                    }
                    $stmt->close();
                }
            }
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 4. Manage Categories
    if ($action === 'manage_categories') {
        $subAction = $_POST['sub_action'] ?? '';
        
        if ($subAction === 'save') {
            $id = intval($_POST['id'] ?? 0);
            $step = intval($_POST['step'] ?? 2);
            $code = strtoupper(trim($_POST['code'] ?? ''));
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $category_type = trim($_POST['category_type'] ?? 'risk');
            $sort_order = intval($_POST['sort_order'] ?? 0);

            if (empty($code) || empty($title)) {
                echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกรหัสหัวข้อและชื่อหัวข้อ'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            if (!preg_match('/^[A-Z0-9_]+$/', $code)) {
                echo json_encode(['status' => 'error', 'message' => 'รหัสหัวข้อต้องเป็นภาษาอังกฤษตัวใหญ่ ตัวเลข หรือเครื่องหมาย _ เท่านั้น'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            if ($id > 0) {
                // Edit category
                $stmt = $conn->prepare("UPDATE `form_categories` SET step = ?, title = ?, description = ?, category_type = ?, sort_order = ? WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("isssii", $step, $title, $description, $category_type, $sort_order, $id);
                    if ($stmt->execute()) {
                        $response = ['status' => 'success', 'message' => 'แก้ไขหัวข้อประเมินสำเร็จ'];
                    } else {
                        $response = ['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการแก้ไข: ' . $conn->error];
                    }
                    $stmt->close();
                }
            } else {
                // Add new category
                $stmt = $conn->prepare("INSERT INTO `form_categories` (step, code, title, description, category_type, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("issssi", $step, $code, $title, $description, $category_type, $sort_order);
                    if ($stmt->execute()) {
                        $response = ['status' => 'success', 'message' => 'เพิ่มหัวข้อประเมินเรียบร้อยแล้ว'];
                    } else {
                        $response = ['status' => 'error', 'message' => 'รหัสหัวข้อนี้ซ้ำในระบบ หรือ เกิดข้อผิดพลาด: ' . $conn->error];
                    }
                    $stmt->close();
                }
            }
        } elseif ($subAction === 'delete') {
            $id = intval($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $conn->prepare("DELETE FROM `form_categories` WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("i", $id);
                    if ($stmt->execute()) {
                        $response = ['status' => 'success', 'message' => 'ลบหัวข้อประเมินสำเร็จ'];
                    } else {
                        $response = ['status' => 'error', 'message' => 'ไม่สามารถลบหัวข้อประเมินได้: ' . $conn->error];
                    }
                    $stmt->close();
                }
            }
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// Fetch all settings data
$categories = [];
$catRes = $conn->query("SELECT * FROM `form_categories` ORDER BY step, sort_order ASC, id ASC");
if ($catRes) {
    while ($r = $catRes->fetch_assoc()) {
        $categories[] = $r;
    }
}
$agencies = [];
$agencyRes = $conn->query("SELECT * FROM agencies ORDER BY id ASC");
if ($agencyRes) {
    while ($r = $agencyRes->fetch_assoc()) {
        $agencies[] = $r;
    }
}

$metadata = [];
$metaRes = $conn->query("SELECT * FROM `form_config_metadata` ORDER BY category, id ASC");
if ($metaRes) {
    while ($r = $metaRes->fetch_assoc()) {
        $metadata[$r['setting_key']] = $r;
    }
}

$fields = [];
$fieldsRes = $conn->query("SELECT * FROM `form_fields` ORDER BY step, sort_order ASC, id ASC");
if ($fieldsRes) {
    while ($r = $fieldsRes->fetch_assoc()) {
        $fields[] = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตั้งค่าระบบประเมินคุณภาพ - DQA Checklist Admin Panel</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&family=Outfit:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- CSS Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    
    <style>
        .admin-header {
            background-color: #ffffff;
            border-radius: var(--radius-lg);
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-card);
            border-bottom: 3px solid var(--moc-gold);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-title-group h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--moc-blue-deep);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .admin-tabs {
            display: flex;
            gap: 0.5rem;
            background: white;
            padding: 0.5rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-card);
            overflow-x: auto;
        }

        .admin-tab-btn {
            background: transparent;
            border: none;
            padding: 0.75rem 1.25rem;
            font-family: var(--font-official);
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--text-muted);
            border-radius: var(--radius-sm);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .admin-tab-btn:hover {
            color: var(--moc-blue-deep);
            background: rgba(12, 60, 120, 0.05);
        }

        .admin-tab-btn.active {
            color: white;
            background: var(--moc-blue-deep);
        }

        .admin-panel {
            display: none;
            background: white;
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow-paper);
            animation: fadeIn 0.3s ease;
        }

        .admin-panel.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .meta-form-group {
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 1.5rem;
        }

        .meta-form-group:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .meta-form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--moc-blue-deep);
        }

        .meta-form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            font-family: var(--font-official);
            font-size: 0.95rem;
            line-height: 1.5;
            resize: vertical;
        }

        .meta-form-group textarea:focus {
            border-color: var(--border-focus);
            outline: none;
            box-shadow: 0 0 0 3px rgba(12, 60, 120, 0.1);
        }

        /* CRUD Table Elements */
        .crud-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .crud-table th {
            background-color: var(--moc-blue-deep);
            color: white;
            font-weight: 600;
            text-align: left;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }

        .crud-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.95rem;
        }

        .crud-table tr:hover {
            background-color: #f8fafc;
        }

        .badge-step {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-weight: bold;
            font-size: 0.75rem;
            color: white;
        }

        .badge-step-1 { background-color: #3b82f6; }
        .badge-step-2 { background-color: #f59e0b; }
        .badge-step-3 { background-color: #8b5cf6; }
        .badge-step-4 { background-color: #10b981; }

        .btn-action-group {
            display: flex;
            gap: 0.25rem;
        }

        .btn-mini {
            padding: 0.35rem;
            border-radius: var(--radius-sm);
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .btn-mini-edit { background-color: var(--moc-gold); }
        .btn-mini-delete { background-color: #ef4444; }
        
        .btn-mini:hover {
            opacity: 0.85;
        }

        /* Modal Settings */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 2000;
        }

        .modal.open {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 600px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .modal.open .modal-content {
            transform: scale(1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--moc-gold);
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
            color: var(--moc-blue-deep);
        }

        .modal-close {
            background: transparent;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
        }

        .modal-close:hover {
            color: #ef4444;
        }

        .form-row-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            cursor: pointer;
        }

        .checkbox-container input {
            width: 18px;
            height: 18px;
        }

        /* Toast Message */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--moc-blue-deep);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: var(--radius-md);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            z-index: 9999;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast.error {
            background-color: #ef4444;
        }

        .toast.success {
            background-color: #10b981;
        }
    </style>
</head>
<body>
    <div class="top-bar-accent"></div>

    <div class="container">
        <!-- Admin Header -->
        <header class="admin-header">
            <div class="admin-title-group">
                <h1><i data-lucide="settings-2"></i> ตั้งค่าและจัดการโครงสร้างระบบประเมิน</h1>
            </div>
            <div>
                <a href="dashboard.php" class="btn btn-secondary"><i data-lucide="arrow-left"></i> กลับหน้าแรก</a>
            </div>
        </header>

        <!-- Navigation Tabs -->
        <nav class="admin-tabs">
            <button type="button" class="admin-tab-btn active" data-target="panel-agencies"><i data-lucide="building"></i> รายการหน่วยงาน</button>
            <button type="button" class="admin-tab-btn" data-target="panel-metadata"><i data-lucide="file-text"></i> คำชี้แจงและหมายเหตุ</button>
            <button type="button" class="admin-tab-btn" data-target="panel-step1"><i data-lucide="align-left"></i> ฟิลด์ขั้นตอนที่ 1</button>
            <button type="button" class="admin-tab-btn" data-target="panel-step2"><i data-lucide="check-square"></i> เกณฑ์มิติขั้นตอนที่ 2</button>
            <button type="button" class="admin-tab-btn" data-target="panel-step3"><i data-lucide="user-check"></i> เกณฑ์ประเมินตนเองขั้นตอนที่ 3</button>
            <button type="button" class="admin-tab-btn" data-target="panel-step4"><i data-lucide="shield-check"></i> เกณฑ์ควบคุมขั้นตอนที่ 4</button>
        </nav>

        <!-- Toast Notifications -->
        <div id="admin-toast" class="toast">
            <span id="toast-icon"></span>
            <span id="toast-text"></span>
        </div>

        <!-- PANEL 1: Agencies -->
        <section id="panel-agencies" class="admin-panel active">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="color: var(--moc-blue-deep);"><i data-lucide="building"></i> รายการหน่วยงานประเมิน</h3>
                <button type="button" class="btn btn-primary" onclick="openAgencyModal()"><i data-lucide="plus-circle"></i> เพิ่มหน่วยงานใหม่</button>
            </div>
            
            <div class="table-responsive">
                <table class="crud-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>ชื่อหน่วยงาน</th>
                            <th style="width: 120px; text-align: center;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="agencies-table-body">
                        <?php foreach ($agencies as $a): ?>
                            <tr id="agency-row-<?php echo $a['id']; ?>">
                                <td><?php echo $a['id']; ?></td>
                                <td class="agency-name"><?php echo htmlspecialchars($a['name']); ?></td>
                                <td class="text-center">
                                    <div class="btn-action-group" style="justify-content: center;">
                                        <button type="button" class="btn-mini btn-mini-edit" onclick="openAgencyModal(<?php echo $a['id']; ?>, '<?php echo htmlspecialchars($a['name'], ENT_QUOTES); ?>')" title="แก้ไข"><i data-lucide="pencil" style="width:14px; height:14px;"></i></button>
                                        <button type="button" class="btn-mini btn-mini-delete" onclick="deleteAgency(<?php echo $a['id']; ?>)" title="ลบ"><i data-lucide="trash-2" style="width:14px; height:14px;"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- PANEL 2: General & Instructions Metadata -->
        <section id="panel-metadata" class="admin-panel">
            <form id="metadata-form" method="POST" action="admin_settings.php?action=update_metadata">
                <h3 style="color: var(--moc-blue-deep); margin-bottom: 1.5rem;"><i data-lucide="file-text"></i> แก้ไขคำชี้แจงและหมายเหตุระบบ</h3>
                
                <?php foreach ($metadata as $key => $meta): 
                    // Skip step2 and step4 category title keys as they are managed within their respective tabs
                    if (strpos($key, 'step2_title_') === 0 || strpos($key, 'step4_title_') === 0) continue;
                ?>
                    <div class="meta-form-group">
                        <label for="meta-<?php echo $key; ?>"><?php echo htmlspecialchars($meta['label']); ?></label>
                        <textarea id="meta-<?php echo $key; ?>" name="settings[<?php echo $key; ?>]" rows="<?php echo (strpos($key, 'text') !== false) ? 6 : 2; ?>"><?php echo htmlspecialchars($meta['setting_value']); ?></textarea>
                    </div>
                <?php endforeach; ?>
                
                <div style="margin-top: 1.5rem; text-align: right;">
                    <button type="submit" class="btn btn-primary"><i data-lucide="save"></i> บันทึกข้อมูลทั้งหมด</button>
                </div>
            </form>
        </section>

        <!-- PANEL 3: Step 1 Fields -->
        <section id="panel-step1" class="admin-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="color: var(--moc-blue-deep);"><i data-lucide="align-left"></i> ฟิลด์ข้อความทั่วไปหน้า 1</h3>
                <button type="button" class="btn btn-primary" onclick="openFieldModal(1, 'info_general')"><i data-lucide="plus-circle"></i> เพิ่มฟิลด์ใหม่</button>
            </div>
            <?php renderFieldsTable($fields, 1); ?>
        </section>

        <!-- PANEL 4: Step 2 Criteria -->
        <section id="panel-step2" class="admin-panel">
            <!-- กลุ่มหัวข้อประเมิน (Step 2 Categories) -->
            <div style="margin-bottom: 2rem; border-bottom: 2px solid var(--moc-gold-light); padding-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3 style="color: var(--moc-blue-deep); margin: 0;"><i data-lucide="folder"></i> กลุ่มหัวข้อประเมินขั้นตอนที่ 2 (Categories)</h3>
                    <button type="button" class="btn btn-primary" onclick="openCategoryModal(2)"><i data-lucide="plus-circle"></i> เพิ่มกลุ่มหัวข้อใหม่</button>
                </div>
                <div class="table-responsive">
                    <table class="crud-table" style="font-size: 0.85rem;">
                        <thead>
                            <tr>
                                <th style="width: 80px;">รหัส</th>
                                <th>ชื่อหัวข้อ (Title)</th>
                                <th>คำชี้แจงหัวข้อ (Description)</th>
                                <th style="width: 100px; text-align: center;">รูปแบบตอบ</th>
                                <th style="width: 80px; text-align: center;">ลำดับ</th>
                                <th style="width: 100px; text-align: center;">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $step2CatsExist = false;
                            foreach ($categories as $cat): 
                                if (intval($cat['step']) !== 2) continue;
                                $step2CatsExist = true;
                            ?>
                                <tr id="cat-row-<?php echo $cat['id']; ?>">
                                    <td class="font-bold text-center"><?php echo htmlspecialchars($cat['code']); ?></td>
                                    <td class="cat-title" style="font-weight: 600; color: var(--moc-blue-deep);"><?php echo htmlspecialchars($cat['title']); ?></td>
                                    <td class="cat-desc" style="color: var(--text-muted); font-size: 0.82rem; line-height: 1.4;"><?php echo htmlspecialchars($cat['description']); ?></td>
                                    <td class="text-center"><span class="badge" style="background:#e0f2fe; color:#0369a1; padding:2px 6px; border-radius:4px; font-size:0.75rem; font-weight:bold;"><?php echo $cat['category_type'] === 'yesno' ? 'ใช่/ไม่ใช่' : 'ประเมินความเสี่ยง'; ?></span></td>
                                    <td class="cat-order text-center"><?php echo $cat['sort_order']; ?></td>
                                    <td class="text-center">
                                        <div class="btn-action-group" style="justify-content: center;">
                                            <button type="button" class="btn-mini btn-mini-edit" onclick='openCategoryModal(2, <?php echo json_encode($cat, JSON_UNESCAPED_UNICODE); ?>)' title="แก้ไข"><i data-lucide="pencil" style="width:12px; height:12px;"></i></button>
                                            <button type="button" class="btn-mini btn-mini-delete" onclick="deleteCategory(<?php echo $cat['id']; ?>)" title="ลบ"><i data-lucide="trash-2" style="width:12px; height:12px;"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; 
                            if (!$step2CatsExist): ?>
                                <tr>
                                    <td colspan="6" class="text-center" style="color: var(--text-muted); font-style: italic; padding: 1.5rem;">ยังไม่มีกลุ่มหัวข้อในขั้นตอนนี้</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; margin-top: 1rem;">
                <h3 style="color: var(--moc-blue-deep); margin: 0;"><i data-lucide="check-square"></i> รายการเกณฑ์ตรวจมิติคุณภาพขั้นตอนที่ 2 (Fields)</h3>
                <div style="display: flex; gap: 0.5rem;">
                    <select id="select-step2-cat" class="form-control" style="width: 250px; padding: 0.4rem 0.8rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                        <?php foreach ($categories as $cat): ?>
                            <?php if (intval($cat['step']) === 2): ?>
                                <option value="<?php echo htmlspecialchars($cat['code']); ?>"><?php echo htmlspecialchars($cat['title']); ?> (<?php echo htmlspecialchars($cat['code']); ?>)</option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn btn-primary" onclick="triggerAddFieldStep2()"><i data-lucide="plus-circle"></i> เพิ่มเกณฑ์ประเมิน</button>
                </div>
            </div>
            <?php renderFieldsTable($fields, 2); ?>
        </section>

        <!-- PANEL 6: Step 3 Self-Assessment -->
        <section id="panel-step3" class="admin-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="color: var(--moc-blue-deep); margin: 0;"><i data-lucide="user-check"></i> รายการเกณฑ์ตรวจประเมินตนเองขั้นตอนที่ 3 (Fields)</h3>
                <div style="display: flex; gap: 0.5rem;">
                    <select id="select-step3-cat" class="form-control" style="width: 250px; padding: 0.4rem 0.8rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                        <?php foreach ($categories as $cat): ?>
                            <?php if (intval($cat['step']) === 2): ?>
                                <option value="<?php echo htmlspecialchars($cat['code']); ?>"><?php echo htmlspecialchars($cat['title']); ?> (<?php echo htmlspecialchars($cat['code']); ?>)</option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn btn-primary" onclick="triggerAddFieldStep3()"><i data-lucide="plus-circle"></i> เพิ่มเกณฑ์ประเมินตนเอง</button>
                </div>
            </div>
            <?php renderFieldsTable($fields, 3); ?>
        </section>

        <!-- PANEL 5: Step 4 Criteria -->
        <section id="panel-step4" class="admin-panel">
            <!-- กลุ่มหัวข้อควบคุม (Step 4 Categories) -->
            <div style="margin-bottom: 2rem; border-bottom: 2px solid var(--moc-gold-light); padding-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3 style="color: var(--moc-blue-deep); margin: 0;"><i data-lucide="folder"></i> กลุ่มหัวข้อประเมินขั้นตอนที่ 4 (Categories)</h3>
                    <button type="button" class="btn btn-primary" onclick="openCategoryModal(4)"><i data-lucide="plus-circle"></i> เพิ่มกลุ่มหัวข้อใหม่</button>
                </div>
                <div class="table-responsive">
                    <table class="crud-table" style="font-size: 0.85rem;">
                        <thead>
                            <tr>
                                <th style="width: 80px;">รหัส</th>
                                <th>ชื่อหัวข้อ (Title)</th>
                                <th>คำชี้แจงหัวข้อ (Description)</th>
                                <th style="width: 100px; text-align: center;">รูปแบบตอบ</th>
                                <th style="width: 80px; text-align: center;">ลำดับ</th>
                                <th style="width: 100px; text-align: center;">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $step4CatsExist = false;
                            foreach ($categories as $cat): 
                                if (intval($cat['step']) !== 4) continue;
                                $step4CatsExist = true;
                            ?>
                                <tr id="cat-row-<?php echo $cat['id']; ?>">
                                    <td class="font-bold text-center"><?php echo htmlspecialchars($cat['code']); ?></td>
                                    <td class="cat-title" style="font-weight: 600; color: var(--moc-blue-deep);"><?php echo htmlspecialchars($cat['title']); ?></td>
                                    <td class="cat-desc" style="color: var(--text-muted); font-size: 0.82rem; line-height: 1.4;"><?php echo htmlspecialchars($cat['description']); ?></td>
                                    <td class="text-center"><span class="badge" style="background:#e0f2fe; color:#0369a1; padding:2px 6px; border-radius:4px; font-size:0.75rem; font-weight:bold;"><?php echo $cat['category_type'] === 'yesno' ? 'ใช่/ไม่ใช่' : 'ประเมินความเสี่ยง'; ?></span></td>
                                    <td class="cat-order text-center"><?php echo $cat['sort_order']; ?></td>
                                    <td class="text-center">
                                        <div class="btn-action-group" style="justify-content: center;">
                                            <button type="button" class="btn-mini btn-mini-edit" onclick='openCategoryModal(4, <?php echo json_encode($cat, JSON_UNESCAPED_UNICODE); ?>)' title="แก้ไข"><i data-lucide="pencil" style="width:12px; height:12px;"></i></button>
                                            <button type="button" class="btn-mini btn-mini-delete" onclick="deleteCategory(<?php echo $cat['id']; ?>)" title="ลบ"><i data-lucide="trash-2" style="width:12px; height:12px;"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; 
                            if (!$step4CatsExist): ?>
                                <tr>
                                    <td colspan="6" class="text-center" style="color: var(--text-muted); font-style: italic; padding: 1.5rem;">ยังไม่มีกลุ่มหัวข้อในขั้นตอนนี้</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; margin-top: 1rem;">
                <h3 style="color: var(--moc-blue-deep); margin: 0;"><i data-lucide="shield-check"></i> เกณฑ์การควบคุมและติดตามขั้นตอนที่ 4/5 (Fields)</h3>
                <div style="display: flex; gap: 0.5rem;">
                    <select id="select-step4-cat" class="form-control" style="width: 250px; padding: 0.4rem 0.8rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                        <?php foreach ($categories as $cat): ?>
                            <?php if (intval($cat['step']) === 4): ?>
                                <option value="<?php echo htmlspecialchars($cat['code']); ?>"><?php echo htmlspecialchars($cat['title']); ?> (<?php echo htmlspecialchars($cat['code']); ?>)</option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn btn-primary" onclick="triggerAddFieldStep4()"><i data-lucide="plus-circle"></i> เพิ่มเกณฑ์ควบคุม</button>
                </div>
            </div>
            <?php renderFieldsTable($fields, 4); ?>
        </section>
    </div>

    <!-- Agency Modal -->
    <div id="agency-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="agency-modal-title">เพิ่มหน่วยงาน</h3>
                <button type="button" class="modal-close" onclick="closeAgencyModal()"><i data-lucide="x"></i></button>
            </div>
            <form id="agency-form" method="POST" action="admin_settings.php?action=manage_agencies">
                <input type="hidden" id="agency-sub-action" name="sub_action" value="add">
                <input type="hidden" id="agency-id" name="id" value="">
                
                <div class="form-group">
                    <label for="agency-name-input">ชื่อหน่วยงาน :</label>
                    <input type="text" id="agency-name-input" name="name" class="form-control" placeholder="ระบุกอง/ศูนย์/กลุ่ม..." required style="width:100%; padding:0.6rem 0.8rem; border-radius:var(--radius-sm); border:1px solid var(--border-color); font-family:var(--font-official);">
                </div>
                
                <div style="margin-top: 1.5rem; text-align: right; display:flex; gap:0.5rem; justify-content:flex-end;">
                    <button type="button" class="btn btn-secondary" onclick="closeAgencyModal()">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Modal -->
    <div id="category-modal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3 id="category-modal-title">เพิ่มกลุ่มหัวข้อใหม่</h3>
                <button type="button" class="modal-close" onclick="closeCategoryModal()"><i data-lucide="x"></i></button>
            </div>
            <form id="category-form" method="POST" action="admin_settings.php?action=manage_categories">
                <input type="hidden" id="category-sub-action" name="sub_action" value="save">
                <input type="hidden" id="category-id" name="id" value="0">
                <input type="hidden" id="category-step" name="step" value="2">
                
                <div class="form-row-grid">
                    <div class="form-group">
                        <label for="category-code-input">รหัสหัวข้อ (Code) : <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="category-code-input" name="code" class="form-control" placeholder="เช่น AC, RE, MY_CAT" required style="width:100%; padding:0.6rem; border-radius:var(--radius-sm); border:1px solid var(--border-color);">
                        <small style="color:var(--text-muted); display:block; margin-top:2px;">**ต้องเป็นภาษาอังกฤษตัวใหญ่ ตัวเลข หรือ _ เท่านั้น ห้ามซ้ำ**</small>
                    </div>
                    <div class="form-group">
                        <label for="category-type-select">รูปแบบการประเมิน (Evaluation Type) :</label>
                        <select id="category-type-select" name="category_type" style="width:100%; padding:0.6rem; border-radius:var(--radius-sm); border:1px solid var(--border-color); font-family:var(--font-official);">
                            <option value="risk">ประเมินความเสี่ยง (มีอย่างเหมาะสม / มีบางส่วน / ไม่มี)</option>
                            <option value="yesno">ประเมินแบบ ใช่ / ไม่ใช่</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label for="category-title-input">ชื่อหัวข้อ / มิติประเมิน (Title) : <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="category-title-input" name="title" required style="width:100%; padding:0.6rem; border-radius:var(--radius-sm); border:1px solid var(--border-color); font-family:var(--font-official);">
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label for="category-desc-input">คำอธิบายรายละเอียด / คำชี้แจงหัวข้อ :</label>
                    <textarea id="category-desc-input" name="description" rows="4" style="width:100%; padding:0.6rem; border-radius:var(--radius-sm); border:1px solid var(--border-color); font-family:var(--font-official);"></textarea>
                </div>

                <div class="form-group" style="margin-top: 1rem; width: 50%;">
                    <label for="category-sort-input">ลำดับการแสดงผล (Sort Order) :</label>
                    <input type="number" id="category-sort-input" name="sort_order" class="form-control" value="10" style="width:100%; padding:0.6rem; border-radius:var(--radius-sm); border:1px solid var(--border-color);">
                </div>

                <div style="margin-top: 1.5rem; text-align: right; display:flex; gap:0.5rem; justify-content:flex-end;">
                    <button type="button" class="btn btn-secondary" onclick="closeCategoryModal()">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Field/Criteria Modal -->
    <div id="field-modal" class="modal">
        <div class="modal-content" style="max-width: 700px;">
            <div class="modal-header">
                <h3 id="field-modal-title">เพิ่มเกณฑ์ประเมิน / ฟิลด์</h3>
                <button type="button" class="modal-close" onclick="closeFieldModal()"><i data-lucide="x"></i></button>
            </div>
            <form id="field-form" method="POST" action="admin_settings.php?action=manage_fields">
                <input type="hidden" id="field-sub-action" name="sub_action" value="save">
                <input type="hidden" id="field-id" name="id" value="0">
                <input type="hidden" id="field-step" name="step" value="1">
                
                <div class="form-row-grid">
                    <div class="form-group">
                        <label for="field-category">หมวดหมู่ (Category) :</label>
                        <input type="text" id="field-category" name="category" class="form-control" readonly style="width:100%; padding:0.6rem; background:#f1f5f9; border-radius:var(--radius-sm); border:1px solid var(--border-color); font-weight:bold;">
                    </div>
                    <div class="form-group">
                        <label for="field-code-input">รหัสฟิลด์/คำถาม (Code) : <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="field-code-input" name="field_code" class="form-control" placeholder="เช่น ac9, g8, info_newfield" required style="width:100%; padding:0.6rem; border-radius:var(--radius-sm); border:1px solid var(--border-color);">
                        <small style="color:var(--text-muted); display:block; margin-top:2px;">**รหัสนี้ห้ามซ้ำ และไม่สามารถแก้ไขได้ภายหลัง**</small>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label for="field-label-input">หัวข้อคำถาม / เกณฑ์ประเมิน (Label) : <span style="color:#ef4444;">*</span></label>
                    <textarea id="field-label-input" name="label" rows="3" required style="width:100%; padding:0.6rem; border-radius:var(--radius-sm); border:1px solid var(--border-color); font-family:var(--font-official);"></textarea>
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label for="field-desc-input">คำอธิบายรายละเอียด / Placeholder / หลักฐานที่จำเป็น :</label>
                    <textarea id="field-desc-input" name="description" rows="3" style="width:100%; padding:0.6rem; border-radius:var(--radius-sm); border:1px solid var(--border-color); font-family:var(--font-official);"></textarea>
                </div>

                <div class="form-row-grid" style="margin-top: 1rem;">
                    <div class="form-group">
                        <label for="field-type-select">ชนิดข้อมูล (Field Type) :</label>
                        <select id="field-type-select" name="field_type" style="width:100%; padding:0.6rem; border-radius:var(--radius-sm); border:1px solid var(--border-color); font-family:var(--font-official);">
                            <option value="textarea">กล่องข้อความหลายบรรทัด (Textarea)</option>
                            <option value="text">กล่องข้อความบรรทัดเดียว (Text Input)</option>
                            <option value="date">ช่องเลือกวันที่ (Date Picker)</option>
                            <option value="select">ดรอปดาวน์เลือกข้อมูล (Dropdown)</option>
                            <option value="radio">ปุ่มเลือกแบบ ใช่/ไม่ใช่ หรือ มี/ไม่มี (Radio Group)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="field-sort-input">ลำดับการแสดงผล (Sort Order) :</label>
                        <input type="number" id="field-sort-input" name="sort_order" class="form-control" value="10" style="width:100%; padding:0.6rem; border-radius:var(--radius-sm); border:1px solid var(--border-color);">
                    </div>
                </div>

                <div class="form-group">
                    <label class="checkbox-container">
                        <input type="checkbox" id="field-required-checkbox" name="is_required" value="1">
                        <span>จำเป็นต้องกรอกคำตอบ (Validate Required *)</span>
                    </label>
                </div>

                <div style="margin-top: 1.5rem; text-align: right; display:flex; gap:0.5rem; justify-content:flex-end;">
                    <button type="button" class="btn btn-secondary" onclick="closeFieldModal()">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึกเกณฑ์</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Init Lucide Icons
        lucide.createIcons();

        // 1. Tab switching logic
        const tabButtons = document.querySelectorAll(".admin-tab-btn");
        const panels = document.querySelectorAll(".admin-panel");

        tabButtons.forEach(btn => {
            btn.addEventListener("click", () => {
                const target = btn.dataset.target;
                
                tabButtons.forEach(b => b.classList.remove("active"));
                panels.forEach(p => p.classList.remove("active"));
                
                btn.classList.add("active");
                document.getElementById(target).classList.add("active");
            });
        });

        // Toast Helper
        function showToast(text, status = 'success') {
            const toast = document.getElementById("admin-toast");
            const icon = document.getElementById("toast-icon");
            const textEl = document.getElementById("toast-text");
            
            toast.className = `toast show ${status}`;
            textEl.innerText = text;
            icon.innerHTML = status === 'success' ? '<i data-lucide="check-circle"></i>' : '<i data-lucide="alert-triangle"></i>';
            lucide.createIcons();
            
            setTimeout(() => {
                toast.classList.remove("show");
            }, 3000);
        }

        // --- Agency Handler ---
        function openAgencyModal(id = null, name = '') {
            const modal = document.getElementById("agency-modal");
            const title = document.getElementById("agency-modal-title");
            const subAction = document.getElementById("agency-sub-action");
            const idInput = document.getElementById("agency-id");
            const nameInput = document.getElementById("agency-name-input");
            
            if (id) {
                title.innerText = "แก้ไขหน่วยงาน";
                subAction.value = "edit";
                idInput.value = id;
                nameInput.value = name;
            } else {
                title.innerText = "เพิ่มหน่วยงานใหม่";
                subAction.value = "add";
                idInput.value = "";
                nameInput.value = "";
            }
            modal.classList.add("open");
        }

        function closeAgencyModal() {
            document.getElementById("agency-modal").classList.remove("open");
        }

        document.getElementById("agency-form").addEventListener("submit", function(e) {
            e.preventDefault();
            const form = e.target;
            const data = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: data
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    showToast(res.message, 'success');
                    closeAgencyModal();
                    setTimeout(() => location.reload(), 800);
                } else {
                    showToast(res.message, 'error');
                }
            })
            .catch(err => showToast('เชื่อมต่อฐานข้อมูลล้มเหลว', 'error'));
        });

        function deleteAgency(id) {
            if (confirm("ยืนยันการลบหน่วยงานนี้?")) {
                const data = new FormData();
                data.append('sub_action', 'delete');
                data.append('id', id);
                
                fetch('admin_settings.php?action=manage_agencies', {
                    method: 'POST',
                    body: data
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        showToast(res.message, 'success');
                        document.getElementById(`agency-row-${id}`).remove();
                    } else {
                        showToast(res.message, 'error');
                    }
                })
                .catch(err => showToast('เชื่อมต่อฐานข้อมูลล้มเหลว', 'error'));
            }
        }

        // --- Metadata Save Handler ---
        document.getElementById("metadata-form").addEventListener("submit", function(e) {
            e.preventDefault();
            const form = e.target;
            const data = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: data
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    showToast(res.message, 'success');
                } else {
                    showToast(res.message, 'error');
                }
            })
            .catch(err => showToast('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error'));
        });

        // --- Field Form Handler ---
        function triggerAddFieldStep2() {
            const cat = document.getElementById("select-step2-cat").value;
            openFieldModal(2, cat);
        }

        function triggerAddFieldStep3() {
            const cat = document.getElementById("select-step3-cat").value;
            openFieldModal(3, cat);
        }

        function triggerAddFieldStep4() {
            const cat = document.getElementById("select-step4-cat").value;
            openFieldModal(4, cat);
        }

        function openFieldModal(step, category, id = 0, field_code = '', label = '', description = '', field_type = 'textarea', is_required = 0, sort_order = 10) {
            const modal = document.getElementById("field-modal");
            const title = document.getElementById("field-modal-title");
            const idInput = document.getElementById("field-id");
            const stepInput = document.getElementById("field-step");
            const catInput = document.getElementById("field-category");
            const codeInput = document.getElementById("field-code-input");
            const labelInput = document.getElementById("field-label-input");
            const descInput = document.getElementById("field-desc-input");
            const typeSelect = document.getElementById("field-type-select");
            const sortInput = document.getElementById("field-sort-input");
            const requiredChk = document.getElementById("field-required-checkbox");
            
            idInput.value = id;
            stepInput.value = step;
            catInput.value = category;
            codeInput.value = field_code;
            labelInput.value = label;
            descInput.value = description;
            typeSelect.value = field_type;
            sortInput.value = sort_order;
            requiredChk.checked = is_required == 1;

            const descLabel = document.querySelector('label[for="field-desc-input"]');
            if (step === 3) {
                if (descLabel) descLabel.innerHTML = 'ตัวเลือกคะแนนระดับ 1-4 (แยกบรรทัดละตัวเลือก) : <span style="color:#ef4444;">*</span>';
                descInput.placeholder = "1 ต่ำ : ...\n2 ปานกลาง : ...\n3 ดี : ...\n4 ดีมาก : ...";
                typeSelect.value = 'radio';
                typeSelect.style.pointerEvents = 'none';
                typeSelect.style.background = '#f1f5f9';
            } else {
                if (descLabel) descLabel.innerHTML = 'คำอธิบายรายละเอียด / Placeholder / หลักฐานที่จำเป็น :';
                descInput.placeholder = "";
                typeSelect.style.pointerEvents = 'auto';
                typeSelect.style.background = '#ffffff';
            }

            if (id > 0) {
                title.innerText = "แก้ไขข้อมูลเกณฑ์ประเมิน";
                codeInput.readOnly = true;
                codeInput.style.background = "#f1f5f9";
            } else {
                title.innerText = "เพิ่มเกณฑ์ประเมินใหม่";
                codeInput.readOnly = false;
                codeInput.style.background = "#ffffff";
            }
            modal.classList.add("open");
        }

        function closeFieldModal() {
            document.getElementById("field-modal").classList.remove("open");
        }

        document.getElementById("field-form").addEventListener("submit", function(e) {
            e.preventDefault();
            const form = e.target;
            const data = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: data
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    showToast(res.message, 'success');
                    closeFieldModal();
                    setTimeout(() => location.reload(), 800);
                } else {
                    showToast(res.message, 'error');
                }
            })
            .catch(err => showToast('เชื่อมต่อฐานข้อมูลล้มเหลว', 'error'));
        });

        function deleteField(id, rowElementId) {
            if (confirm("คุณแน่ใจว่าต้องการลบเกณฑ์ประเมินนี้? ฟิลด์นี้จะหยุดแสดงผลในฟอร์มประเมิน (ข้อมูลเดิมที่เก็บไว้ใน submissions จะไม่ถูกลบ)")) {
                const data = new FormData();
                data.append('sub_action', 'delete');
                data.append('id', id);
                
                fetch('admin_settings.php?action=manage_fields', {
                    method: 'POST',
                    body: data
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        showToast(res.message, 'success');
                        document.getElementById(rowElementId).remove();
                    } else {
                        showToast(res.message, 'error');
                    }
                })
                .catch(err => showToast('เชื่อมต่อฐานข้อมูลล้มเหลว', 'error'));
            }
        }

        // --- Category Handler ---
        function openCategoryModal(step, cat = null) {
            const modal = document.getElementById("category-modal");
            const title = document.getElementById("category-modal-title");
            const subAction = document.getElementById("category-sub-action");
            const idInput = document.getElementById("category-id");
            const stepInput = document.getElementById("category-step");
            const codeInput = document.getElementById("category-code-input");
            const titleInput = document.getElementById("category-title-input");
            const descInput = document.getElementById("category-desc-input");
            const typeSelect = document.getElementById("category-type-select");
            const sortInput = document.getElementById("category-sort-input");
            
            stepInput.value = step;
            
            if (cat) {
                title.innerText = "แก้ไขกลุ่มหัวข้อประเมิน";
                subAction.value = "save";
                idInput.value = cat.id;
                codeInput.value = cat.code;
                codeInput.readOnly = true;
                codeInput.style.background = "#f1f5f9";
                titleInput.value = cat.title;
                descInput.value = cat.description || '';
                typeSelect.value = cat.category_type || 'risk';
                sortInput.value = cat.sort_order || 10;
            } else {
                title.innerText = "เพิ่มกลุ่มหัวข้อใหม่";
                subAction.value = "save";
                idInput.value = 0;
                codeInput.value = "";
                codeInput.readOnly = false;
                codeInput.style.background = "#ffffff";
                titleInput.value = "";
                descInput.value = "";
                typeSelect.value = step == 2 ? "yesno" : "risk"; // Step 2 defaults to yesno, Step 4 to risk
                sortInput.value = 10;
            }
            modal.classList.add("open");
        }

        function closeCategoryModal() {
            document.getElementById("category-modal").classList.remove("open");
        }

        document.getElementById("category-form").addEventListener("submit", function(e) {
            e.preventDefault();
            const form = e.target;
            const data = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: data
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    showToast(res.message, 'success');
                    closeCategoryModal();
                    setTimeout(() => location.reload(), 800);
                } else {
                    showToast(res.message, 'error');
                }
            })
            .catch(err => showToast('เชื่อมต่อฐานข้อมูลล้มเหลว', 'error'));
        });

        function deleteCategory(id) {
            if (confirm("ยืนยันการลบกลุ่มหัวข้อประเมินนี้? (เกณฑ์ประเมินที่อยู่ในหัวข้อนี้จะยังคงอยู่ แต่อาจแสดงผลไม่ถูกต้องจนกว่าจะย้ายหัวข้อใหม่)")) {
                const data = new FormData();
                data.append('sub_action', 'delete');
                data.append('id', id);
                
                fetch('admin_settings.php?action=manage_categories', {
                    method: 'POST',
                    body: data
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        showToast(res.message, 'success');
                        document.getElementById(`cat-row-${id}`).remove();
                        setTimeout(() => location.reload(), 800); // Reload to update selects
                    } else {
                        showToast(res.message, 'error');
                    }
                })
                .catch(err => showToast('เชื่อมต่อฐานข้อมูลล้มเหลว', 'error'));
            }
        }
    </script>
</body>
</html>
<?php
// PHP helper to render dynamic fields table in the admin panel
function renderFieldsTable($fields, $step) {
    ?>
    <div class="table-responsive">
        <table class="crud-table">
            <thead>
                <tr>
                    <th style="width: 100px;">รหัส</th>
                    <th style="width: 120px;">หมวดหมู่</th>
                    <th>หัวข้อหลัก</th>
                    <th>คำอธิบายเพิ่มเติม</th>
                    <th style="width: 100px;">Required</th>
                    <th style="width: 70px;">ลำดับ</th>
                    <th style="width: 100px; text-align: center;">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $count = 0;
                foreach ($fields as $f): 
                    if ($f['step'] != $step) continue;
                    $count++;
                    $rowId = "field-row-{$f['id']}";
                ?>
                    <tr id="<?php echo $rowId; ?>">
                        <td class="font-bold text-uppercase"><?php echo htmlspecialchars($f['field_code']); ?></td>
                        <td><span class="badge-step badge-step-<?php echo $step; ?>"><?php echo htmlspecialchars($f['category']); ?></span></td>
                        <td style="max-width: 250px; font-weight: 500;"><?php echo htmlspecialchars($f['label']); ?></td>
                        <td style="max-width: 300px; color: var(--text-muted); font-size: 0.88rem;"><?php echo htmlspecialchars($f['description']); ?></td>
                        <td class="text-center font-bold" style="color: <?php echo $f['is_required'] ? '#ef4444' : '#64748b'; ?>;">
                            <?php echo $f['is_required'] ? 'ใช่' : 'ไม่'; ?>
                        </td>
                        <td class="text-center"><?php echo $f['sort_order']; ?></td>
                        <td class="text-center">
                            <div class="btn-action-group" style="justify-content: center;">
                                <button type="button" class="btn-mini btn-mini-edit" onclick="openFieldModal(<?php echo $step; ?>, '<?php echo htmlspecialchars($f['category'], ENT_QUOTES); ?>', <?php echo $f['id']; ?>, '<?php echo htmlspecialchars($f['field_code'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($f['label'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($f['description'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($f['field_type'], ENT_QUOTES); ?>', <?php echo $f['is_required']; ?>, <?php echo $f['sort_order']; ?>)" title="แก้ไข"><i data-lucide="pencil" style="width:14px; height:14px;"></i></button>
                                <button type="button" class="btn-mini btn-mini-delete" onclick="deleteField(<?php echo $f['id']; ?>, '<?php echo $rowId; ?>')" title="ลบ"><i data-lucide="trash-2" style="width:14px; height:14px;"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; 
                if ($count === 0): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted" style="padding: 2rem;">ไม่มีข้อมูลเกณฑ์สำหรับขั้นตอนนี้</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
?>
