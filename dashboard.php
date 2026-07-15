<?php
// dashboard.php
// Modern responsive dashboard for DQA Checklist submissions
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

// Handle Delete Action
$msg = '';
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    // If deleting the active draft, unset it from session
    if (isset($_SESSION['current_submission_id']) && $_SESSION['current_submission_id'] == $id) {
        unset($_SESSION['current_submission_id']);
    }
    $stmt = $conn->prepare("DELETE FROM submissions WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    // Redirect to prevent re-deletion on refresh
    header('Location: dashboard.php?msg=deleted');
    exit;
}

// Fetch stats
$totalQuery = $conn->query("SELECT COUNT(*) as count FROM submissions");
$totalCount = $totalQuery ? $totalQuery->fetch_assoc()['count'] : 0;

$subQuery = $conn->query("SELECT COUNT(*) as count FROM submissions WHERE status = 'submitted'");
$submittedCount = $subQuery ? $subQuery->fetch_assoc()['count'] : 0;

$draftQuery = $conn->query("SELECT COUNT(*) as count FROM submissions WHERE status = 'draft'");
$draftCount = $draftQuery ? $draftQuery->fetch_assoc()['count'] : 0;

// Handle search and filtering
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_agency = isset($_GET['agency']) ? trim($_GET['agency']) : '';

$sql = "SELECT id, info_title, info_agency, eval_date, status, updated_at FROM submissions WHERE 1=1";
$params = [];
$types = "";

if ($search !== '') {
    $sql .= " AND (info_title LIKE ? OR info_agency LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
}

if ($filter_agency !== '') {
    $sql .= " AND info_agency = ?";
    $params[] = $filter_agency;
    $types .= "s";
}

$sql .= " ORDER BY updated_at DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$submissions = $stmt->get_result();

// Get list of distinct agencies for the filter dropdown
$agenciesQuery = $conn->query("SELECT DISTINCT info_agency FROM submissions WHERE info_agency != '' ORDER BY info_agency ASC");
$agencies = [];
if ($agenciesQuery) {
    while ($row = $agenciesQuery->fetch_assoc()) {
        $agencies[] = $row['info_agency'];
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ด DQA Checklist - กระทรวงพาณิชย์</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&family=Outfit:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css?v=2">
    
    <style>
        /* Dashboard-specific Premium Styles */
        .dashboard-header {
            margin-bottom: 2rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-card);
            border-left: 5px solid var(--moc-blue-deep);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-paper);
        }
        
        .stat-card.submitted {
            border-left-color: #10b981; /* Green */
        }
        
        .stat-card.draft {
            border-left-color: #f59e0b; /* Amber */
        }
        
        .stat-info h3 {
            font-size: 0.85rem;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .stat-val {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            font-family: var(--font-heading);
            line-height: 1;
        }
        
        .stat-icon-box {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--moc-blue-light);
            color: var(--moc-blue-deep);
        }
        
        .stat-card.submitted .stat-icon-box {
            background-color: #d1fae5;
            color: #10b981;
        }
        
        .stat-card.draft .stat-icon-box {
            background-color: #fef3c7;
            color: #f59e0b;
        }
        
        /* Filter Controls */
        .controls-card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-card);
        }
        
        .filter-form {
            display: grid;
            grid-template-columns: 2fr 1fr auto auto;
            gap: 1rem;
            align-items: flex-end;
        }
        
        @media (max-width: 768px) {
            .filter-form {
                grid-template-columns: 1fr;
            }
        }
        
        /* Table enhancements */
        .table-responsive {
            overflow-x: auto;
            background: #ffffff;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-paper);
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
        }
        
        .db-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        
        .db-table th {
            background-color: var(--moc-blue-light);
            color: var(--moc-blue-deep);
            padding: 1rem 1.25rem;
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
        }
        
        .db-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }
        
        .db-table tr:last-child td {
            border-bottom: none;
        }
        
        .db-table tr:hover td {
            background-color: #f8fafc;
        }
        
        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge.badge-submitted {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge.badge-draft {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        /* Actions */
        .btn-action-group {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
            background: #ffffff;
            color: var(--text-muted);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .btn-action:hover {
            color: var(--moc-blue-deep);
            border-color: var(--moc-blue-deep);
            background-color: var(--moc-blue-light);
        }
        
        .btn-action.btn-delete:hover {
            color: #ef4444;
            border-color: #fca5a5;
            background-color: #fef2f2;
        }
        
        /* Message Banner */
        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            padding: 1rem 1.5rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            border-left: 4px solid #10b981;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
            color: var(--text-muted);
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--border-color);
        }
    </style>
</head>
<body>
    <div class="top-bar-accent"></div>

    <div class="container">
        <!-- Header -->
        <header class="form-header no-print">
            <div class="logo-wrapper">
                <div class="gov-seal">
                    <img src="ops-logo.jpg" alt="OPS Logo" style="width:100%; height:100%; object-fit:contain;">
                </div>
                <div class="title-group">
                    <h1>ระบบแดชบอร์ด DQA Checklist</h1>
                    <span class="agency-tag">ระบบติดตามและจัดการแบบตรวจประเมินคุณภาพข้อมูล</span>
                </div>
            </div>
            <div>
                <a href="new.php" class="btn btn-primary">
                    <i data-lucide="plus-circle"></i> ทำแบบประเมินใหม่
                </a>
            </div>
        </header>

        <!-- Notification Banner -->
        <?php if (!empty($msg) || (isset($_GET['msg']) && $_GET['msg'] === 'deleted')): ?>
            <div class="alert-success">
                <i data-lucide="check-circle"></i>
                <span><?php echo !empty($msg) ? htmlspecialchars($msg) : 'ลบรายการตรวจประเมินเรียบร้อยแล้ว'; ?></span>
            </div>
        <?php endif; ?>

        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h3>แบบประเมินทั้งหมด</h3>
                    <div class="stat-val"><?php echo $totalCount; ?></div>
                </div>
                <div class="stat-icon-box">
                    <i data-lucide="files"></i>
                </div>
            </div>
            
            <div class="stat-card submitted">
                <div class="stat-info">
                    <h3>ส่งข้อมูลแล้ว</h3>
                    <div class="stat-val"><?php echo $submittedCount; ?></div>
                </div>
                <div class="stat-icon-box">
                    <i data-lucide="check-circle-2"></i>
                </div>
            </div>
            
            <div class="stat-card draft">
                <div class="stat-info">
                    <h3>ฉบับร่าง (Draft)</h3>
                    <div class="stat-val"><?php echo $draftCount; ?></div>
                </div>
                <div class="stat-icon-box">
                    <i data-lucide="edit-3"></i>
                </div>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="controls-card">
            <form action="dashboard.php" method="GET" class="filter-form">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="search" class="font-bold" style="display: block; margin-bottom: 0.4rem; font-size: 0.9rem;">ค้นหาหัวข้อ</label>
                    <input type="text" id="search" name="search" placeholder="พิมพ์ชื่อข้อมูลหรือหน่วยงาน..." value="<?php echo htmlspecialchars($search); ?>" style="width:100%; padding:0.6rem 0.8rem; border-radius:var(--radius-sm); border:1px solid var(--border-color);">
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="agency" class="font-bold" style="display: block; margin-bottom: 0.4rem; font-size: 0.9rem;">หน่วยงาน</label>
                    <select id="agency" name="agency" style="width:100%; padding:0.6rem 0.8rem; border-radius:var(--radius-sm); border:1px solid var(--border-color); background:white;">
                        <option value="">ทั้งหมด</option>
                        <?php foreach ($agencies as $agency): ?>
                            <option value="<?php echo htmlspecialchars($agency); ?>" <?php if ($filter_agency === $agency) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($agency); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.5rem;">
                    <i data-lucide="search"></i> ค้นหา
                </button>
                
                <?php if ($search !== '' || $filter_agency !== ''): ?>
                    <a href="dashboard.php" class="btn btn-secondary" style="padding: 0.65rem 1.5rem; text-decoration:none; display:inline-flex; align-items:center;">
                        ล้างค่า
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Submissions Table -->
        <div class="table-responsive">
            <?php if ($submissions->num_rows > 0): ?>
                <table class="db-table">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ชื่องานประเมิน / ชุดข้อมูล</th>
                            <th>หน่วยงาน</th>
                            <th>วันที่ประเมิน</th>
                            <th>สถานะ</th>
                            <th>อัปเดตล่าสุด</th>
                            <th class="text-center" style="width: 120px;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $num = 1;
                        while ($row = $submissions->fetch_assoc()): 
                            // Display evaluation date formatted
                            $dateStr = formatChecklistDate($row['eval_date']);
                            
                            // Format updated time
                            $updateTime = date('d/m/y H:i', strtotime($row['updated_at']));
                        ?>
                            <tr>
                                <td><?php echo $num++; ?></td>
                                <td class="font-semibold" style="max-width: 250px; word-wrap: break-word;">
                                    <?php echo htmlspecialchars($row['info_title'] ?: '(ไม่ได้ระบุชื่อข้อมูล)'); ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['info_agency'] ?: '-'); ?></td>
                                <td><?php echo $dateStr; ?></td>
                                <td>
                                    <?php if ($row['status'] === 'submitted'): ?>
                                        <span class="badge badge-submitted">
                                            <i data-lucide="check" style="width: 12px; height: 12px;"></i> ส่งแล้ว
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-draft">
                                            <i data-lucide="edit-2" style="width: 12px; height: 12px;"></i> ฉบับร่าง
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="font-size: 0.85rem; color: var(--text-muted);"><?php echo $updateTime; ?></td>
                                <td>
                                    <div class="btn-action-group" style="justify-content: center;">
                                        <a href="view.php?id=<?php echo $row['id']; ?>" class="btn-action" title="ดูรายละเอียดการตอบทั้งหมด" style="color: var(--moc-blue-deep); border-color: var(--moc-blue-deep); background-color: var(--moc-blue-light);">
                                            <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                                        </a>
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-action" title="แก้ไขรายงาน">
                                            <i data-lucide="edit-3" style="width: 16px; height: 16px;"></i>
                                        </a>
                                        <a href="dashboard.php?action=delete&id=<?php echo $row['id']; ?>" class="btn-action btn-delete" title="ลบ" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบรายการประเมินนี้ถาวร?')">
                                            <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <i data-lucide="file-warning"></i>
                    <h3>ไม่พบข้อมูลรายการประเมิน</h3>
                    <p style="margin-top: 0.5rem; font-size: 0.9rem;">กรุณากดปุ่ม "ทำแบบประเมินใหม่" เพื่อเริ่มกรอกใบประเมินแรกของคุณ</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Render Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
