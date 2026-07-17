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
                // Find the category code and step to clean up fields
                $catQuery = $conn->query("SELECT code, step FROM `form_categories` WHERE id = $id");
                if ($catQuery && $catRow = $catQuery->fetch_assoc()) {
                    $catCode = $catRow['code'];
                    $catStep = intval($catRow['step']);
                    
                    $conn->begin_transaction();
                    try {
                        // Delete matching fields under this category
                        // Note: If the category step is 2, it might have fields in step 2 AND step 3 (self-assessment reuses step 2 categories!)
                        if ($catStep === 2) {
                            $stmtFields = $conn->prepare("DELETE FROM `form_fields` WHERE category = ? AND step IN (2, 3)");
                        } else {
                            $stmtFields = $conn->prepare("DELETE FROM `form_fields` WHERE category = ? AND step = ?");
                        }
                        
                        if ($stmtFields) {
                            if ($catStep === 2) {
                                $stmtFields->bind_param("s", $catCode);
                            } else {
                                $stmtFields->bind_param("si", $catCode, $catStep);
                            }
                            $stmtFields->execute();
                            $stmtFields->close();
                        }
                        
                        // Delete the category itself
                        $stmtCat = $conn->prepare("DELETE FROM `form_categories` WHERE id = ?");
                        if ($stmtCat) {
                            $stmtCat->bind_param("i", $id);
                            $stmtCat->execute();
                            $stmtCat->close();
                        }
                        
                        $conn->commit();
                        $response = ['status' => 'success', 'message' => 'ลบหัวข้อประเมินและเกณฑ์คำถามทั้งหมดภายใต้หัวข้อนี้สำเร็จ'];
                    } catch (Exception $e) {
                        $conn->rollback();
                        $response = ['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการลบ: ' . $e->getMessage()];
                    }
                } else {
                    $response = ['status' => 'error', 'message' => 'ไม่พบข้อมูลหัวข้อที่ต้องการลบ'];
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
$fieldsRes = $conn->query("
    SELECT f.*, c.sort_order as cat_sort 
    FROM `form_fields` f 
    LEFT JOIN `form_categories` c ON f.category = c.code AND c.step = (CASE WHEN f.step = 3 THEN 2 ELSE f.step END)
    ORDER BY f.step ASC, IFNULL(cat_sort, 999) ASC, f.sort_order ASC, f.id ASC
");
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
    <link rel="stylesheet" href="assets/css/admin.css?v=1">
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
            <button type="button" class="admin-tab-btn" data-target="panel-metadata"><i data-lucide="settings"></i> ตั้งค่าทั่วไป</button>
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
                                        <button type="button" class="btn-mini btn-mini-delete" data-name="<?php echo htmlspecialchars($a['name'], ENT_QUOTES); ?>" onclick="deleteAgency(this, <?php echo $a['id']; ?>)" title="ลบ"><i data-lucide="trash-2" style="width:14px; height:14px;"></i></button>
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
            <form class="metadata-form" method="POST" action="admin_settings.php?action=update_metadata">
                <h3 style="color: var(--moc-blue-deep); margin-bottom: 1.5rem;"><i data-lucide="settings"></i> แก้ไขตั้งค่าทั่วไปของระบบ</h3>
                
                <?php foreach ($metadata as $key => $meta): 
                    // Only show general category settings in this panel
                    if ($meta['category'] !== 'general') continue;
                ?>
                    <div class="meta-form-group">
                        <label for="meta-<?php echo $key; ?>"><?php echo htmlspecialchars($meta['label']); ?></label>
                        <textarea id="meta-<?php echo $key; ?>" name="settings[<?php echo $key; ?>]" rows="2"><?php echo htmlspecialchars($meta['setting_value']); ?></textarea>
                    </div>
                <?php endforeach; ?>
                
                <div style="margin-top: 1.5rem; text-align: right;">
                    <button type="submit" class="btn btn-primary"><i data-lucide="save"></i> บันทึกการตั้งค่า</button>
                </div>
            </form>
        </section>

        <!-- PANEL 3: Step 1 Fields -->
        <section id="panel-step1" class="admin-panel">
            <!-- กลุ่มหัวข้อขั้นตอนที่ 1 (Step 1 Categories) -->
            <div style="margin-bottom: 2rem; border-bottom: 2px solid var(--moc-gold-light); padding-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3 style="color: var(--moc-blue-deep); margin: 0;"><i data-lucide="folder"></i> กลุ่มส่วนหัวข้อขั้นตอนที่ 1 (Categories)</h3>
                    <button type="button" class="btn btn-primary" onclick="openCategoryModal(1)"><i data-lucide="plus-circle"></i> เพิ่มส่วนใหม่</button>
                </div>
                <div class="table-responsive">
                    <table class="crud-table" style="font-size: 0.85rem;">
                        <thead>
                            <tr>
                                <th style="width: 80px;">รหัส</th>
                                <th>ชื่อส่วน (Title)</th>
                                <th>คำอธิบาย (Description)</th>
                                <th style="width: 80px; text-align: center;">ลำดับ</th>
                                <th style="width: 100px; text-align: center;">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $step1CatsExist = false;
                            foreach ($categories as $cat): 
                                if (intval($cat['step']) !== 1) continue;
                                $step1CatsExist = true;
                            ?>
                                <tr id="cat-row-<?php echo $cat['id']; ?>">
                                    <td class="font-bold text-center"><?php echo htmlspecialchars($cat['code']); ?></td>
                                    <td class="cat-title" style="font-weight: 600; color: var(--moc-blue-deep);"><?php echo htmlspecialchars($cat['title']); ?></td>
                                    <td class="cat-desc" style="color: var(--text-muted); font-size: 0.82rem; line-height: 1.4;"><?php echo htmlspecialchars($cat['description']); ?></td>
                                    <td class="cat-order text-center"><?php echo $cat['sort_order']; ?></td>
                                    <td class="text-center">
                                        <div class="btn-action-group" style="justify-content: center;">
                                            <button type="button" class="btn-mini btn-mini-edit" onclick='openCategoryModal(1, <?php echo json_encode($cat, JSON_UNESCAPED_UNICODE); ?>)' title="แก้ไข"><i data-lucide="pencil" style="width:12px; height:12px;"></i></button>
                                            <button type="button" class="btn-mini btn-mini-delete" data-code="<?php echo htmlspecialchars($cat['code'], ENT_QUOTES); ?>" data-title="<?php echo htmlspecialchars($cat['title'], ENT_QUOTES); ?>" onclick="deleteCategory(this, <?php echo $cat['id']; ?>)" title="ลบ"><i data-lucide="trash-2" style="width:12px; height:12px;"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; 
                            if (!$step1CatsExist): ?>
                                <tr>
                                    <td colspan="5" class="text-center" style="color: var(--text-muted); font-style: italic; padding: 1.5rem;">ยังไม่มีการแบ่งส่วนในขั้นตอนนี้</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- รายการฟิลด์ขั้นตอนที่ 1 -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; margin-top: 1rem;">
                <h3 style="color: var(--moc-blue-deep); margin: 0;"><i data-lucide="align-left"></i> ฟิลด์ข้อความทั่วไปหน้า 1 (Fields)</h3>
                <button type="button" class="btn btn-primary" onclick="openFieldModal(1)"><i data-lucide="plus-circle"></i> เพิ่มฟิลด์ใหม่</button>
            </div>
            <?php renderFieldsTable($fields, 1); ?>
            <?php renderStepMetadataForm($metadata, 1); ?>
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
                                            <button type="button" class="btn-mini btn-mini-delete" data-code="<?php echo htmlspecialchars($cat['code'], ENT_QUOTES); ?>" data-title="<?php echo htmlspecialchars($cat['title'], ENT_QUOTES); ?>" onclick="deleteCategory(this, <?php echo $cat['id']; ?>)" title="ลบ"><i data-lucide="trash-2" style="width:12px; height:12px;"></i></button>
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
                <button type="button" class="btn btn-primary" onclick="openFieldModal(2)"><i data-lucide="plus-circle"></i> เพิ่มเกณฑ์ประเมิน</button>
            </div>
            <?php renderFieldsTable($fields, 2); ?>
            <?php renderStepMetadataForm($metadata, 2); ?>
        </section>

        <!-- PANEL 6: Step 3 Self-Assessment -->
        <section id="panel-step3" class="admin-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="color: var(--moc-blue-deep); margin: 0;"><i data-lucide="user-check"></i> รายการเกณฑ์ตรวจประเมินตนเองขั้นตอนที่ 3 (Fields)</h3>
                <button type="button" class="btn btn-primary" onclick="openFieldModal(3)"><i data-lucide="plus-circle"></i> เพิ่มเกณฑ์ประเมินตนเอง</button>
            </div>
            <?php renderFieldsTable($fields, 3); ?>
            <?php renderStepMetadataForm($metadata, 3); ?>
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
                                            <button type="button" class="btn-mini btn-mini-delete" data-code="<?php echo htmlspecialchars($cat['code'], ENT_QUOTES); ?>" data-title="<?php echo htmlspecialchars($cat['title'], ENT_QUOTES); ?>" onclick="deleteCategory(this, <?php echo $cat['id']; ?>)" title="ลบ"><i data-lucide="trash-2" style="width:12px; height:12px;"></i></button>
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
                <button type="button" class="btn btn-primary" onclick="openFieldModal(4)"><i data-lucide="plus-circle"></i> เพิ่มเกณฑ์ควบคุม</button>
            </div>
            <?php renderFieldsTable($fields, 4); ?>
            <?php renderStepMetadataForm($metadata, 4); ?>
        </section>
    </div>

    <!-- Confirm Modal -->
    <div id="confirm-modal" class="modal">
        <div class="modal-content confirm-modal-content" style="max-width: 540px;">
            <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem; display: flex; align-items: center; gap: 1rem;">
                <div class="confirm-icon-container" id="confirm-modal-icon-bg">
                    <i id="confirm-modal-icon" data-lucide="trash-2" style="width: 22px; height: 22px;"></i>
                </div>
                <div>
                    <h3 id="confirm-modal-title" style="margin: 0; font-size: 1.2rem; font-weight: 700; color: #0f172a;">ยืนยันการทำรายการ</h3>
                    <p id="confirm-modal-subtitle" style="margin: 3px 0 0 0; font-size: 0.82rem; color: #64748b; font-weight: 400;">โปรดตรวจสอบความถูกต้องและอ่านหมายเหตุก่อนดำเนินการ</p>
                </div>
                <button type="button" class="modal-close" onclick="closeConfirmModal()" style="margin-left: auto; align-self: flex-start;"><i data-lucide="x"></i></button>
            </div>
            <div class="modal-body" style="padding: 0.5rem 0;">
                <p id="confirm-modal-message" style="font-weight: 600; font-size: 1.05rem; margin-top: 0.5rem; margin-bottom: 0.75rem; color: #1e293b;"></p>
                
                <div id="confirm-modal-details" class="confirm-details-box">
                    <!-- Dynamically populated details -->
                </div>
                
                <div id="confirm-modal-warning-wrapper" class="confirm-warning-box" style="display: none;">
                    <i data-lucide="alert-circle" style="width: 16px; height: 16px; display: inline-block; vertical-align: text-bottom; margin-right: 4px;"></i>
                    <span id="confirm-modal-warning"></span>
                </div>
            </div>
            <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid #f1f5f9; padding-top: 1rem; margin-top: 0.5rem;">
                <button type="button" class="btn btn-secondary" style="padding: 0.5rem 1.5rem; font-size: 0.9rem;" onclick="closeConfirmModal()">ยกเลิก</button>
                <button type="button" id="confirm-modal-submit-btn" class="btn btn-danger btn-confirm-danger" style="padding: 0.5rem 1.5rem; font-size: 0.9rem;">ยืนยันการลบ</button>
            </div>
        </div>
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
                        <select id="field-category" name="category" class="form-control" style="width:100%; padding:0.6rem; border-radius:var(--radius-sm); border:1px solid var(--border-color); font-weight:bold;">
                            <!-- Populated dynamically based on current step -->
                        </select>
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

        // Restore active tab from localStorage if exists
        const activeTab = localStorage.getItem("adminActiveTab");
        if (activeTab) {
            const activeBtn = document.querySelector(`.admin-tab-btn[data-target="${activeTab}"]`);
            if (activeBtn) {
                tabButtons.forEach(b => b.classList.remove("active"));
                panels.forEach(p => p.classList.remove("active"));
                activeBtn.classList.add("active");
                const targetPanel = document.getElementById(activeTab);
                if (targetPanel) targetPanel.classList.add("active");
            }
        }

        tabButtons.forEach(btn => {
            btn.addEventListener("click", () => {
                const target = btn.dataset.target;
                
                tabButtons.forEach(b => b.classList.remove("active"));
                panels.forEach(p => p.classList.remove("active"));
                
                btn.classList.add("active");
                document.getElementById(target).classList.add("active");
                
                // Save active tab to localStorage
                localStorage.setItem("adminActiveTab", target);
            });
        });

        // Restore scroll position from localStorage if exists
        window.addEventListener("load", () => {
            const scrollPos = localStorage.getItem("adminScrollPosition");
            if (scrollPos) {
                window.scrollTo(0, parseInt(scrollPos));
                localStorage.removeItem("adminScrollPosition");
            }
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
                    setTimeout(() => {
                        localStorage.setItem("adminScrollPosition", window.scrollY);
                        location.reload();
                    }, 800);
                } else {
                    showToast(res.message, 'error');
                }
            })
            .catch(err => showToast('เชื่อมต่อฐานข้อมูลล้มเหลว', 'error'));
        });

        // --- Custom Confirm Modal Logic ---
        let confirmActionCallback = null;

        function showConfirmModal(options) {
            const isDanger = options.btnClass && options.btnClass.includes('btn-danger');
            const iconBg = document.getElementById("confirm-modal-icon-bg");
            
            // Set styles dynamically
            if (isDanger) {
                iconBg.className = 'confirm-icon-container';
                iconBg.style.backgroundColor = '#fee2e2';
                iconBg.style.color = '#ef4444';
                iconBg.style.boxShadow = '0 0 0 4px rgba(239, 68, 68, 0.1)';
                iconBg.style.animation = 'pulse-red 2s infinite';
                iconBg.innerHTML = `<i id="confirm-modal-icon" data-lucide="${options.icon || 'trash-2'}" style="width: 22px; height: 22px;"></i>`;
            } else {
                iconBg.className = 'confirm-icon-container';
                iconBg.style.backgroundColor = '#fef3c7';
                iconBg.style.color = '#d97706';
                iconBg.style.boxShadow = '0 0 0 4px rgba(217, 119, 6, 0.1)';
                iconBg.style.animation = 'pulse-amber 2s infinite';
                iconBg.innerHTML = `<i id="confirm-modal-icon" data-lucide="${options.icon || 'alert-triangle'}" style="width: 22px; height: 22px;"></i>`;
            }
            
            document.getElementById("confirm-modal-title").innerText = options.title || 'ยืนยันการทำรายการ';
            document.getElementById("confirm-modal-message").innerText = options.message || 'คุณแน่ใจหรือไม่ที่จะทำรายการนี้?';
            
            const detailsContainer = document.getElementById("confirm-modal-details");
            detailsContainer.innerHTML = '';
            if (options.details && options.details.length > 0) {
                options.details.forEach(d => {
                    const row = document.createElement('div');
                    row.style.marginBottom = '0.4rem';
                    row.innerHTML = `<span style="font-weight: 700; color: var(--moc-blue-deep);">${d.label}:</span> ${d.value}`;
                    detailsContainer.appendChild(row);
                });
                detailsContainer.style.display = 'block';
            } else {
                detailsContainer.style.display = 'none';
            }
            
            const warningWrapper = document.getElementById("confirm-modal-warning-wrapper");
            const warningEl = document.getElementById("confirm-modal-warning");
            if (options.warning) {
                warningEl.innerText = options.warning;
                warningWrapper.style.display = 'block';
            } else {
                warningWrapper.style.display = 'none';
            }
            
            const submitBtn = document.getElementById("confirm-modal-submit-btn");
            submitBtn.className = `btn ${options.btnClass || 'btn-danger'} ${isDanger ? 'btn-confirm-danger' : ''}`;
            submitBtn.innerText = options.btnText || 'ยืนยัน';
            
            confirmActionCallback = options.onConfirm;
            
            document.getElementById("confirm-modal").classList.add("open");
            document.getElementById("confirm-modal").classList.add("active");
            if (window.lucide) lucide.createIcons();
        }

        function closeConfirmModal() {
            const modal = document.getElementById("confirm-modal");
            modal.classList.remove("open");
            modal.classList.remove("active");
            confirmActionCallback = null;
        }

        document.getElementById("confirm-modal-submit-btn").addEventListener("click", function() {
            if (confirmActionCallback) {
                confirmActionCallback();
            }
            closeConfirmModal();
        });

        function deleteAgency(btn, id) {
            const row = document.getElementById(`agency-row-${id}`);
            const name = btn.getAttribute('data-name');
            
            showConfirmModal({
                title: 'ยืนยันการลบหน่วยงาน',
                message: 'คุณต้องการลบหน่วยงานนี้ใช่หรือไม่?',
                icon: 'building-2',
                iconColor: '#ef4444',
                btnClass: 'btn-danger',
                btnText: 'ยืนยันการลบ',
                details: [
                    { label: 'ชื่อหน่วยงาน', value: name }
                ],
                warning: '* หมายเหตุ: รายชื่อหน่วยงานจะถูกนำออกจากการลงทะเบียน แต่ข้อมูล/สถิติประวัติการทำรายการเดิมจะถูกเก็บบันทึกไว้ในระบบเพื่อความปลอดภัย',
                onConfirm: function() {
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
                            row.remove();
                        } else {
                            showToast(res.message, 'error');
                        }
                    })
                    .catch(err => showToast('เชื่อมต่อฐานข้อมูลล้มเหลว', 'error'));
                }
            });
        }

        // --- Metadata Save Handler ---
        document.addEventListener("submit", function(e) {
            const formEl = e.target;
            if (formEl && formEl.classList.contains("metadata-form")) {
                e.preventDefault();
                const data = new FormData(formEl);
                
                fetch(formEl.action, {
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
            }
        });

        // --- Category options by step mapping ---
        const stepCategories = {
            1: [
                <?php foreach ($categories as $cat): ?>
                    <?php if (intval($cat['step']) === 1): ?>
                        { code: <?php echo json_encode($cat['code']); ?>, title: <?php echo json_encode($cat['title'] . ' (' . $cat['code'] . ')'); ?> },
                    <?php endif; ?>
                <?php endforeach; ?>
            ],
            2: [
                <?php foreach ($categories as $cat): ?>
                    <?php if (intval($cat['step']) === 2): ?>
                        { code: <?php echo json_encode($cat['code']); ?>, title: <?php echo json_encode($cat['title'] . ' (' . $cat['code'] . ')'); ?> },
                    <?php endif; ?>
                <?php endforeach; ?>
            ],
            3: [
                <?php foreach ($categories as $cat): ?>
                    <?php if (intval($cat['step']) === 2): ?>
                        { code: <?php echo json_encode($cat['code']); ?>, title: <?php echo json_encode($cat['title'] . ' (' . $cat['code'] . ')'); ?> },
                    <?php endif; ?>
                <?php endforeach; ?>
            ],
            4: [
                <?php foreach ($categories as $cat): ?>
                    <?php if (intval($cat['step']) === 4): ?>
                        { code: <?php echo json_encode($cat['code']); ?>, title: <?php echo json_encode($cat['title'] . ' (' . $cat['code'] . ')'); ?> },
                    <?php endif; ?>
                <?php endforeach; ?>
            ]
        };

        // --- Field Edit Helper ---
        function editField(btn) {
            const f = JSON.parse(btn.getAttribute('data-field'));
            openFieldModal(
                parseInt(f.step), 
                f.category, 
                parseInt(f.id), 
                f.field_code, 
                f.label, 
                f.description, 
                f.field_type, 
                parseInt(f.is_required), 
                parseInt(f.sort_order)
            );
        }

        // --- Field Form Handler ---
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

            // Populate categories dynamically based on current step
            catInput.innerHTML = '';
            const cats = stepCategories[step] || [];
            cats.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.code;
                opt.textContent = c.title;
                catInput.appendChild(opt);
            });
            
            if (category) {
                catInput.value = category;
            } else if (cats.length > 0) {
                catInput.value = cats[0].code;
            }

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
                
                if (step === 3) {
                    descInput.value = "1 ต่ำ : \n2 ปานกลาง : \n3 ดี : \n4 ดีมาก : ";
                }
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
                    setTimeout(() => {
                        localStorage.setItem("adminScrollPosition", window.scrollY);
                        location.reload();
                    }, 800);
                } else {
                    showToast(res.message, 'error');
                }
            })
            .catch(err => showToast('เชื่อมต่อฐานข้อมูลล้มเหลว', 'error'));
        });

        function deleteField(btn, id, rowElementId) {
            const row = document.getElementById(rowElementId);
            const code = btn.getAttribute('data-code');
            const label = btn.getAttribute('data-label');
            
            showConfirmModal({
                title: 'ยืนยันการลบเกณฑ์ประเมิน',
                message: 'คุณต้องการลบเกณฑ์ประเมินนี้ใช่หรือไม่?',
                icon: 'trash-2',
                iconColor: '#ef4444',
                btnClass: 'btn-danger',
                btnText: 'ยืนยันการลบ',
                details: [
                    { label: 'รหัสเกณฑ์ (Code)', value: code },
                    { label: 'หัวข้อเกณฑ์ประเมิน', value: label }
                ],
                warning: '* หมายเหตุ: ฟิลด์นี้จะหยุดแสดงผลในฟอร์มประเมิน (ข้อมูลเดิมที่เก็บไว้ในตาราง submissions จะไม่ถูกลบออก)',
                onConfirm: function() {
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
                            row.remove();
                        } else {
                            showToast(res.message, 'error');
                        }
                    })
                    .catch(err => showToast('เชื่อมต่อฐานข้อมูลล้มเหลว', 'error'));
                }
            });
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
            
            const typeGroup = typeSelect.closest('.form-group');
            if (step === 1) {
                if (typeGroup) typeGroup.style.display = 'none';
            } else {
                if (typeGroup) typeGroup.style.display = 'block';
            }
            
            if (cat) {
                title.innerText = step === 1 ? "แก้ไขกลุ่มส่วนฟิลด์" : "แก้ไขกลุ่มหัวข้อประเมิน";
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
                title.innerText = step === 1 ? "เพิ่มส่วนใหม่" : "เพิ่มกลุ่มหัวข้อใหม่";
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
                    setTimeout(() => {
                        localStorage.setItem("adminScrollPosition", window.scrollY);
                        location.reload();
                    }, 800);
                } else {
                    showToast(res.message, 'error');
                }
            })
            .catch(err => showToast('เชื่อมต่อฐานข้อมูลล้มเหลว', 'error'));
        });

        function deleteCategory(btn, id) {
            const row = document.getElementById(`cat-row-${id}`);
            const code = btn.getAttribute('data-code');
            const title = btn.getAttribute('data-title');
            
            showConfirmModal({
                title: 'ยืนยันการลบกลุ่มหัวข้อประเมิน',
                message: 'คุณต้องการลบกลุ่มหัวข้อประเมินนี้ใช่หรือไม่?',
                icon: 'folder-x',
                iconColor: '#ef4444',
                btnClass: 'btn-danger',
                btnText: 'ยืนยันการลบ',
                details: [
                    { label: 'รหัสกลุ่มหัวข้อ (Code)', value: code },
                    { label: 'ชื่อกลุ่มหัวข้อประเมิน', value: title }
                ],
                warning: '* คำเตือน: เกณฑ์คำถามประเมินทั้งหมด (Fields) ที่จัดอยู่ในหัวข้อประเมินนี้ จะถูกลบออกจากระบบอย่างถาวรโดยอัตโนมัติ เพื่อป้องกันปัญหาข้อมูลตกค้างในภายหลัง',
                onConfirm: function() {
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
                            row.remove();
                            setTimeout(() => {
                                localStorage.setItem("adminScrollPosition", window.scrollY);
                                location.reload();
                            }, 800); // Reload to update selects
                        } else {
                            showToast(res.message, 'error');
                        }
                    })
                    .catch(err => showToast('เชื่อมต่อฐานข้อมูลล้มเหลว', 'error'));
                }
            });
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
                    <th>หัวข้อหลัก (คำถาม / เกณฑ์ประเมิน)</th>
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
                        <td style="max-width: 450px; font-weight: 500; line-height: 1.4;">
                            <div style="font-weight: 600; color: var(--moc-blue-deep);"><?php echo htmlspecialchars($f['label']); ?></div>
                            <?php if (!empty($f['description'])): ?>
                                <div style="font-size: 0.82rem; color: var(--text-muted); font-weight: 400; margin-top: 4px; background: #f8fafc; padding: 6px 10px; border-left: 3px solid #cbd5e1; border-radius: 4px; white-space: pre-line; line-height: 1.45;">
                                    <?php echo htmlspecialchars($f['description']); ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="text-center font-bold" style="color: <?php echo $f['is_required'] ? '#ef4444' : '#64748b'; ?>;">
                            <?php echo $f['is_required'] ? 'ใช่' : 'ไม่'; ?>
                        </td>
                        <td class="text-center"><?php echo $f['sort_order']; ?></td>
                        <td class="text-center">
                            <div class="btn-action-group" style="justify-content: center;">
                                <button type="button" class="btn-mini btn-mini-edit" data-field="<?php echo htmlspecialchars(json_encode($f, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8'); ?>" onclick="editField(this)" title="แก้ไข"><i data-lucide="pencil" style="width:14px; height:14px;"></i></button>
                                <button type="button" class="btn-mini btn-mini-delete" data-code="<?php echo htmlspecialchars($f['field_code'], ENT_QUOTES); ?>" data-label="<?php echo htmlspecialchars($f['label'], ENT_QUOTES); ?>" onclick="deleteField(this, <?php echo $f['id']; ?>, '<?php echo $rowId; ?>')" title="ลบ"><i data-lucide="trash-2" style="width:14px; height:14px;"></i></button>
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


<?php
// PHP helper to render metadata instruction/remark form in the admin panel
function renderStepMetadataForm($metadata, $step) {
    $inst_title_key = "step{$step}_instruction_title";
    $inst_text_key = "step{$step}_instruction_text";
    $remark_title_key = "step{$step}_remark_title";
    $remark_text_key = "step{$step}_remark_text";

    $inst_title = $metadata[$inst_title_key]['setting_value'] ?? '';
    $inst_text = $metadata[$inst_text_key]['setting_value'] ?? '';
    $remark_title = $metadata[$remark_title_key]['setting_value'] ?? '';
    $remark_text = $metadata[$remark_text_key]['setting_value'] ?? '';
    ?>
    <div style="margin-top: 2.5rem; background: #f8fafc; border: 1px solid var(--border-color); padding: 1.5rem; border-radius: var(--radius-md); box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <h4 style="color: var(--moc-blue-deep); margin-top: 0; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.5rem; font-size: 1.05rem;">
            <i data-lucide="file-text" style="width: 20px; height: 20px;"></i> แก้ไขคำชี้แจงและหมายเหตุประจำขั้นตอนที่ <?php echo $step; ?>
        </h4>
        <form class="metadata-form" method="POST" action="admin_settings.php?action=update_metadata">
            <div class="form-row-grid">
                <div class="meta-form-group">
                    <label for="meta-<?php echo $inst_title_key; ?>" style="font-weight: 600; font-size: 0.88rem; margin-bottom: 0.4rem; display: block;">หัวข้อคำชี้แจง (Instruction Title)</label>
                    <input type="text" id="meta-<?php echo $inst_title_key; ?>" name="settings[<?php echo $inst_title_key; ?>]" value="<?php echo htmlspecialchars($inst_title); ?>" style="width:100%; padding:0.6rem 0.8rem; border:1px solid var(--border-color); border-radius:var(--radius-sm); font-size: 0.9rem;">
                </div>
                <div class="meta-form-group">
                    <label for="meta-<?php echo $remark_title_key; ?>" style="font-weight: 600; font-size: 0.88rem; margin-bottom: 0.4rem; display: block;">หัวข้อหมายเหตุ (Remark Title)</label>
                    <input type="text" id="meta-<?php echo $remark_title_key; ?>" name="settings[<?php echo $remark_title_key; ?>]" value="<?php echo htmlspecialchars($remark_title); ?>" style="width:100%; padding:0.6rem 0.8rem; border:1px solid var(--border-color); border-radius:var(--radius-sm); font-size: 0.9rem;">
                </div>
            </div>
            
            <div class="form-row-grid" style="margin-top: 1rem;">
                <div class="meta-form-group">
                    <label for="meta-<?php echo $inst_text_key; ?>" style="font-weight: 600; font-size: 0.88rem; margin-bottom: 0.4rem; display: block;">เนื้อหาคำชี้แจง (Instruction Text - รองรับ HTML)</label>
                    <textarea id="meta-<?php echo $inst_text_key; ?>" name="settings[<?php echo $inst_text_key; ?>]" rows="4" style="width:100%; padding:0.6rem 0.8rem; border:1px solid var(--border-color); border-radius:var(--radius-sm); font-family: inherit; font-size: 0.9rem; resize: vertical;"><?php echo htmlspecialchars($inst_text); ?></textarea>
                </div>
                <div class="meta-form-group">
                    <label for="meta-<?php echo $remark_text_key; ?>" style="font-weight: 600; font-size: 0.88rem; margin-bottom: 0.4rem; display: block;">เนื้อหาหมายเหตุ (Remark Text - รองรับ HTML)</label>
                    <textarea id="meta-<?php echo $remark_text_key; ?>" name="settings[<?php echo $remark_text_key; ?>]" rows="4" style="width:100%; padding:0.6rem 0.8rem; border:1px solid var(--border-color); border-radius:var(--radius-sm); font-family: inherit; font-size: 0.9rem; resize: vertical;"><?php echo htmlspecialchars($remark_text); ?></textarea>
                </div>
            </div>
            
            <div style="margin-top: 1.2rem; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.5rem; display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.88rem;">
                    <i data-lucide="save" style="width: 16px; height: 16px;"></i> บันทึกข้อมูลของหน้านี้
                </button>
            </div>
        </form>
    </div>
    <?php
}
?>
