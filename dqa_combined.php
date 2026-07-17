<?php 
require_once 'db.php'; 

// Load form config metadata
$meta = [];
$metaRes = $conn->query("SELECT * FROM `form_config_metadata`");
if ($metaRes) {
    while ($r = $metaRes->fetch_assoc()) {
        $meta[$r['setting_key']] = $r['setting_value'];
    }
}
$sys_title = $meta['general_title'] ?? 'แบบตรวจประเมินคุณภาพ (DQA Checklist) - รวมทุกขั้นตอน';
$sys_agency = $meta['general_agency_title'] ?? 'สำนักงานปลัดกระทรวงพาณิชย์';

// Load form categories grouped by step
$form_categories = [];
$catRes = $conn->query("SELECT * FROM `form_categories` ORDER BY step, sort_order ASC, id ASC");
if ($catRes) {
    while ($r = $catRes->fetch_assoc()) {
        $form_categories[intval($r['step'])][] = $r;
    }
}

// Load form fields
$step1_fields = [];
$step2_fields = [];
$step3_fields = [];
$step4_fields = [];

$required_fields_by_step = [
    0 => [], // Step 1
    1 => [], // Step 2
    2 => [], // Step 3
    3 => []  // Step 4
];

$fieldsRes = $conn->query("SELECT * FROM `form_fields` WHERE `status` = 'active' ORDER BY `sort_order` ASC, `id` ASC");
if ($fieldsRes) {
    while ($r = $fieldsRes->fetch_assoc()) {
        $step = intval($r['step']);
        $cat = $r['category'];
        if ($step === 1) {
            $step1_fields[$r['field_code']] = $r;
        } elseif ($step === 2) {
            $step2_fields[$cat][] = $r;
        } elseif ($step === 3) {
            $step3_fields[strtolower($cat)][] = $r;
        } elseif ($step === 4) {
            $step4_fields[$cat][] = $r;
        }
        
        // Save required fields
        if (intval($r['is_required']) === 1) {
            $step_idx = $step - 1;
            if ($step_idx >= 0 && $step_idx <= 3) {
                $required_fields_by_step[$step_idx][] = [
                    'code' => $r['field_code'],
                    'label' => $r['label'],
                    'type' => $r['field_type'],
                    'category' => $r['category']
                ];
            }
        }
    }
}
// Rendering helpers for Step 1
function renderStep1Field($field) {
    if (!$field) return;
    $code = $field['field_code'];
    $label = htmlspecialchars($field['label']);
    $desc = htmlspecialchars($field['description']);
    $required = $field['is_required'] ? 'required' : '';
    $star = $field['is_required'] ? ' <span style="color:#ef4444;">*</span>' : '';
    
    echo '<div class="form-group" id="group-' . $code . '">';
    echo '<label for="' . $code . '">' . $label . $star . '</label>';
    
    if ($field['field_type'] === 'select') {
        if ($code === 'info_agency') {
            global $conn;
            echo '<select id="' . $code . '" name="' . $code . '" ' . $required . '>';
            echo '<option value="">' . htmlspecialchars($desc) . '</option>';
            $agenciesQuery = $conn->query("SELECT name FROM agencies ORDER BY id ASC");
            if ($agenciesQuery && $agenciesQuery->num_rows > 0) {
                while ($agencyRow = $agenciesQuery->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($agencyRow['name']) . '">' . htmlspecialchars($agencyRow['name']) . '</option>';
                }
            }
            echo '</select>';
        } elseif ($code === 'metric_standard_type') {
            echo '<select id="' . $code . '" name="' . $code . '" ' . $required . '>';
            echo '<option value="ตัวชี้วัดที่เป็นมาตรฐานสากล">ตัวชี้วัดที่เป็นมาตรฐานสากล</option>';
            echo '<option value="ตัวชี้วัดที่กำหนดเอง">ตัวชี้วัดที่กำหนดเอง</option>';
            echo '</select>';
        } else {
            echo '<select id="' . $code . '" name="' . $code . '" ' . $required . '>';
            echo '<option value="">-- เลือก --</option>';
            echo '</select>';
        }
    } elseif ($field['field_type'] === 'date') {
        echo '<input type="date" id="' . $code . '" name="' . $code . '" ' . $required . ' style="width: 100%; padding: 0.6rem 0.8rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color); background-color: #ffffff;">';
    } elseif ($field['field_type'] === 'text') {
        echo '<input type="text" id="' . $code . '" name="' . $code . '" placeholder="' . htmlspecialchars($desc) . '" ' . $required . '>';
    } else {
        echo '<textarea id="' . $code . '" name="' . $code . '" rows="2" placeholder="' . htmlspecialchars($desc) . '" ' . $required . '></textarea>';
    }
    echo '</div>';
}

function renderStep1FieldRaw($field) {
    if (!$field) return;
    $code = $field['field_code'];
    $label = htmlspecialchars($field['label']);
    $desc = htmlspecialchars($field['description']);
    $required = $field['is_required'] ? 'required' : '';
    $star = $field['is_required'] ? ' <span style="color:#ef4444;">*</span>' : '';
    
    echo '<label for="' . $code . '">' . $label . $star . '</label>';
    
    if ($field['field_type'] === 'select') {
        if ($code === 'info_agency') {
            global $conn;
            echo '<select id="' . $code . '" name="' . $code . '" ' . $required . '>';
            echo '<option value="">' . htmlspecialchars($desc) . '</option>';
            $agenciesQuery = $conn->query("SELECT name FROM agencies ORDER BY id ASC");
            if ($agenciesQuery && $agenciesQuery->num_rows > 0) {
                while ($agencyRow = $agenciesQuery->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($agencyRow['name']) . '">' . htmlspecialchars($agencyRow['name']) . '</option>';
                }
            }
            echo '</select>';
        } elseif ($code === 'metric_standard_type') {
            echo '<select id="' . $code . '" name="' . $code . '" ' . $required . '>';
            echo '<option value="ตัวชี้วัดที่เป็นมาตรฐานสากล">ตัวชี้วัดที่เป็นมาตรฐานสากล</option>';
            echo '<option value="ตัวชี้วัดที่กำหนดเอง">ตัวชี้วัดที่กำหนดเอง</option>';
            echo '</select>';
        } else {
            echo '<select id="' . $code . '" name="' . $code . '" ' . $required . '>';
            echo '<option value="">-- เลือก --</option>';
            echo '</select>';
        }
    } elseif ($field['field_type'] === 'date') {
        echo '<input type="date" id="' . $code . '" name="' . $code . '" ' . $required . ' style="width: 100%; padding: 0.6rem 0.8rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color); background-color: #ffffff;">';
    } elseif ($field['field_type'] === 'text') {
        echo '<input type="text" id="' . $code . '" name="' . $code . '" placeholder="' . htmlspecialchars($desc) . '" ' . $required . '>';
    } else {
        echo '<textarea id="' . $code . '" name="' . $code . '" rows="1" placeholder="' . htmlspecialchars($desc) . '" ' . $required . '></textarea>';
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="แบบตรวจประเมินคุณภาพข้อมูลทั้งหมด (DQA Checklist All-in-One)">
    <title><?php echo htmlspecialchars($sys_title); ?></title>
    
    <!-- Google Fonts - Sarabun & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&family=Outfit:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Original V2 CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=2">

    <!-- Custom CSS for Multi-step Form & Stepper has been moved to assets/css/style.css -->
</head>
<body>
    <div class="top-bar-accent"></div>

    <div class="container">
        <!-- Official Headings -->
        <header class="form-header no-print">
            <div class="logo-wrapper">
                <div class="gov-seal">
                    <img src="assets/images/ops-logo.jpg" alt="OPS Logo" style="width:100%; height:100%; object-fit:contain;">
                </div>
                <div class="title-group">
                    <h1><?php echo htmlspecialchars($sys_title); ?></h1>
                    <span class="agency-tag"><?php echo htmlspecialchars($sys_agency); ?></span>
                </div>
            </div>
            <div class="actions-wrapper">
                <button type="button" class="btn btn-secondary" id="btn-print"><i data-lucide="printer"></i> พิมพ์เอกสาร</button>
                <button type="button" class="btn btn-danger-outline" id="btn-reset"><i data-lucide="trash-2"></i> ล้างข้อมูล</button>
            </div>
        </header>

        <!-- Dynamic Stepper -->
        <div class="stepper-container no-print">
            <div class="stepper">
                <div class="stepper-line">
                    <div class="stepper-line-progress" id="stepper-progress"></div>
                </div>
                <div class="stepper-step active" data-step="0">
                    <div class="stepper-circle">1</div>
                    <div class="stepper-label">ข้อมูลทั่วไป</div>
                </div>
                <div class="stepper-step" data-step="1">
                    <div class="stepper-circle">2</div>
                    <div class="stepper-label">มิติคุณภาพข้อมูล</div>
                </div>
                <div class="stepper-step" data-step="2">
                    <div class="stepper-circle">3</div>
                    <div class="stepper-label">ประเมินตนเอง</div>
                </div>
                <div class="stepper-step" data-step="3">
                    <div class="stepper-circle">4</div>
                    <div class="stepper-label">การควบคุมติดตาม</div>
                </div>
            </div>
        </div>

        <!-- Save Status Notification Toast -->
        <div id="toast" class="toast hide">
            <span id="toast-message">บันทึกข้อมูลเรียบร้อยแล้ว</span>
        </div>

        <!-- Main Form Paper -->
        <main class="document-paper">
            <form id="dqa-form" method="POST" action="api.php" novalidate>
                
                <!-- ================= STEP 1: index.php ================= -->
                <div id="step-1" class="form-step active">
                    <div class="paper-header">
                        <?php if (!empty($meta['step1_instruction_title']) || !empty($meta['step1_instruction_text'])): ?>
                            <?php if (!empty($meta['step1_instruction_title'])): ?>
                                <div class="official-title-bar">
                                    <h2><?php echo htmlspecialchars($meta['step1_instruction_title']); ?></h2>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($meta['step1_instruction_text'])): ?>
                                <div class="instruction-box" style="margin-top: 2rem; border-left: 4px solid var(--moc-gold); line-height: 1.45; font-size: 0.95rem; text-align: left; background-color: var(--moc-gold-light); color: var(--text-dark);">
                                    <?php echo ($meta['step1_instruction_text']); ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <!-- SECTION 1: ข้อมูลหน่วยงานและภารกิจ -->
                    <fieldset class="form-section">
                        <legend><i data-lucide="info"></i> ส่วนที่ 1: ข้อมูลทั่วไปของข้อมูลและหน่วยงาน</legend>
                        
                        <?php renderStep1Field($step1_fields['info_title'] ?? null); ?>

                        <div class="form-row">
                            <div class="form-group col-6">
                                <?php renderStep1FieldRaw($step1_fields['info_agency'] ?? null); ?>
                            </div>
                            
                            <div class="form-group col-6">
                                <?php renderStep1FieldRaw($step1_fields['info_mission'] ?? null); ?>
                            </div>
                        </div>

                        <?php 
                        foreach ($step1_fields as $code => $f) {
                            if (!in_array($code, ['info_title', 'info_agency', 'info_mission']) && ($f['category'] === 'INFO_GENERAL' || $f['category'] === 'info_general')) {
                                renderStep1Field($f);
                            }
                        }
                        ?>
                    </fieldset>

                    <!-- SECTION 2: ตัวชี้วัดและเป้าหมาย -->
                    <fieldset class="form-section">
                        <legend><i data-lucide="trending-up"></i> ส่วนที่ 2: ตัวชี้วัดและเป้าหมาย</legend>

                        <?php 
                        renderStep1Field($step1_fields['metric_name'] ?? null);
                        renderStep1Field($step1_fields['metric_link'] ?? null);
                        renderStep1Field($step1_fields['metric_result'] ?? null);
                        renderStep1Field($step1_fields['metric_source'] ?? null);

                        foreach ($step1_fields as $code => $f) {
                            if (!in_array($code, ['metric_name', 'metric_link', 'metric_result', 'metric_source']) && $f['category'] === 'INFO_METRIC') {
                                renderStep1Field($f);
                            }
                        }
                        ?>
                    </fieldset>

                    <!-- SECTION 3: เครือข่ายการดำเนินงานและมาตรฐาน -->
                    <fieldset class="form-section">
                        <legend><i data-lucide="users"></i> ส่วนที่ 3: แหล่งข้อมูลและการกำหนดมาตรฐาน</legend>

                        <?php 
                        renderStep1Field($step1_fields['source_partner'] ?? null);
                        renderStep1Field($step1_fields['source_period'] ?? null);
                        ?>

                        <div class="form-row">
                            <div class="form-group col-6">
                                <?php renderStep1FieldRaw($step1_fields['metric_standard_type'] ?? null); ?>
                            </div>
                            
                            <div class="form-group col-6">
                                <?php renderStep1FieldRaw($step1_fields['metric_standard_detail'] ?? null); ?>
                            </div>
                        </div>

                        <?php 
                        foreach ($step1_fields as $code => $f) {
                            if (!in_array($code, ['source_partner', 'source_period', 'metric_standard_type', 'metric_standard_detail']) && $f['category'] === 'INFO_SOURCE') {
                                renderStep1Field($f);
                            }
                        }
                        ?>
                    </fieldset>

                    <!-- SECTION 4: การประเมินผลและอนุมัติ -->
                    <fieldset class="form-section">
                        <legend><i data-lucide="check-circle-2"></i> ส่วนที่ 4: กระบวนการประเมินและการอนุมัติ</legend>

                        <?php renderStep1Field($step1_fields['eval_method'] ?? null); ?>

                        <div class="form-row">
                            <div class="form-group col-6">
                                <?php renderStep1FieldRaw($step1_fields['eval_date'] ?? null); ?>
                            </div>
                            
                            <div class="form-group col-6">
                                <?php renderStep1FieldRaw($step1_fields['eval_team'] ?? null); ?>
                            </div>
                        </div>

                        <?php renderStep1Field($step1_fields['eval_approver'] ?? null); ?>

                        <?php 
                        foreach ($step1_fields as $code => $f) {
                            if (!in_array($code, ['eval_method', 'eval_date', 'eval_team', 'eval_approver']) && $f['category'] === 'INFO_EVAL') {
                                renderStep1Field($f);
                            }
                        }
                        ?>
                    </fieldset>

                    <!-- RENDER ANY CUSTOM SECTIONS & FIELDS IN STEP 1 -->
                    <?php
                    $standard_step1_categories = ['INFO_GENERAL', 'INFO_METRIC', 'INFO_SOURCE', 'INFO_EVAL', 'info_general'];
                    $step1_cats_db = $form_categories[1] ?? [];
                    foreach ($step1_cats_db as $cat_data) {
                        $cat_code = $cat_data['code'];
                        if (in_array($cat_code, $standard_step1_categories)) continue; // Already rendered standard sections
                        
                        $cat_fields = [];
                        foreach ($step1_fields as $code => $f) {
                            if ($f['category'] === $cat_code) {
                                $cat_fields[] = $f;
                            }
                        }
                        if (empty($cat_fields)) continue;
                        ?>
                        <fieldset class="form-section">
                            <legend><i data-lucide="plus-circle"></i> <?php echo htmlspecialchars($cat_data['title']); ?></legend>
                            <?php if (!empty($cat_data['description'])): ?>
                                <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.25rem;">
                                    <?php echo htmlspecialchars($cat_data['description']); ?>
                                </div>
                            <?php endif; ?>
                            <?php 
                            foreach ($cat_fields as $f) {
                                renderStep1Field($f);
                            }
                            ?>
                        </fieldset>
                        <?php
                    }
                    ?>

                    <!-- FALLBACK FOR ANY OTHER CUSTOM FIELDS WITHOUT A REGISTERED CATEGORY -->
                    <?php
                    $standard_step1_codes = [
                        'info_title', 'info_agency', 'info_mission', 'metric_name', 'metric_link', 
                        'metric_result', 'metric_source', 'source_partner', 'source_period', 
                        'metric_standard_type', 'metric_standard_detail', 'eval_method', 
                        'eval_date', 'eval_team', 'eval_approver'
                    ];
                    $registered_step1_categories = [];
                    foreach ($step1_cats_db as $c) {
                        $registered_step1_categories[] = $c['code'];
                    }
                    $registered_step1_categories[] = 'info_general'; // default fallback
                    
                    $has_unregistered = false;
                    foreach ($step1_fields as $code => $f) {
                        if (!in_array($code, $standard_step1_codes) && !in_array($f['category'], $registered_step1_categories)) {
                            if (!$has_unregistered) {
                                echo '<fieldset class="form-section"><legend><i data-lucide="plus-circle"></i> ข้อมูลเพิ่มเติมอื่น ๆ</legend>';
                                $has_unregistered = true;
                            }
                            renderStep1Field($f);
                        }
                    }
                    if ($has_unregistered) {
                        echo '</fieldset>';
                    }
                    ?>

                    <!-- REMARKS SECTION -->
                    <?php if (!empty($meta['step1_remark_title']) || !empty($meta['step1_remark_text'])): ?>
                        <div class="remarks-container">
                            <?php if (!empty($meta['step1_remark_title'])): ?>
                                <div class="remarks-header">
                                    <?php echo htmlspecialchars($meta['step1_remark_title']); ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($meta['step1_remark_text'])): ?>
                                <div class="remarks-body">
                                    <?php echo ($meta['step1_remark_text']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-navigation no-print" style="margin-top: 2rem;">
                        <div></div>
                        <button type="button" class="btn btn-primary btn-next-step">หน้าถัดไป (มิติคุณภาพข้อมูล) <i data-lucide="chevron-right"></i></button>
                    </div>
                </div>


                <!-- ================= STEP 2: page3.php ================= -->
                <div id="step-2" class="form-step">
                    <!-- Field: ชื่อข้อมูล -->
                    <div class="form-group mb-4" style="margin-top: 1rem;">
                        <label for="info-title-display-s2" class="text-bold">ชื่อข้อมูล :</label>
                        <textarea id="info-title-display-s2" rows="2" placeholder="กรอกชื่อข้อมูลหรือชื่อชุดข้อมูล..." readonly style="background-color: #f1f5f9; cursor: not-allowed;"></textarea>
                    </div>

                    <!-- Dimension Header Title -->
                    <div class="dimension-header-bar">
                        <h3>มิติคุณภาพข้อมูล</h3>
                    </div>

                    <?php if (!empty($meta['step2_instruction_title']) || !empty($meta['step2_instruction_text'])): ?>
                        <div class="paper-header" style="margin-top: 1.5rem;">
                            <?php if (!empty($meta['step2_instruction_title'])): ?>
                                <div class="official-title-bar">
                                    <h2><?php echo htmlspecialchars($meta['step2_instruction_title']); ?></h2>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($meta['step2_instruction_text'])): ?>
                                <div class="instruction-box" style="margin-top: 1.5rem; border-left: 4px solid var(--moc-gold); line-height: 1.45; font-size: 0.95rem; text-align: left; background-color: var(--moc-gold-light); color: var(--text-dark);">
                                    <?php echo ($meta['step2_instruction_text']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php
                    $step2_cats_db = $form_categories[2] ?? [];
                    foreach ($step2_cats_db as $cat_data) {
                        $cat = $cat_data['code'];
                        $cat_fields = $step2_fields[$cat] ?? [];
                        if (empty($cat_fields)) continue;
                        
                        $title = $cat_data['title'];
                        $desc_val = $cat_data['description'];
                        ?>
                        <!-- Sub-Header: <?php echo htmlspecialchars($cat); ?> -->
                        <div class="dimension-sub-bar" style="margin-top: 3rem;">
                            <h4><?php echo htmlspecialchars($title); ?></h4>
                        </div>

                        <?php if (!empty($desc_val)): ?>
                            <div class="dimension-desc-box">
                                <?php echo htmlspecialchars($desc_val); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Assessment Table -->
                        <div class="table-responsive">
                            <table class="assessment-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px; text-align: center;">รหัส</th>
                                        <th style="width: 45%;">เกณฑ์การประเมินคุณภาพข้อมูล</th>
                                        <th style="width: 60px; text-align: center;">ใช่</th>
                                        <th style="width: 60px; text-align: center;">ไม่ใช่</th>
                                        <th style="width: 45%; min-width: 300px;">ความเห็น / ข้อเสนอแนะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cat_fields as $f): 
                                        $code = $f['field_code'];
                                        $label = htmlspecialchars($f['label']);
                                        $desc = htmlspecialchars($f['description']);
                                        $full_text = $label . (!empty($desc) ? " ($desc)" : "");
                                    ?>
                                        <tr>
                                            <td class="text-center font-bold"><?php echo strtoupper($code); ?></td>
                                            <td class="text-justify font-medium"><?php echo $full_text; ?></td>
                                            <td class="text-center">
                                                <label class="table-radio">
                                                    <input type="radio" name="<?php echo $code; ?>_status" value="ใช่">
                                                    <span class="table-checkmark"></span>
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <label class="table-radio">
                                                    <input type="radio" name="<?php echo $code; ?>_status" value="ไม่ใช่">
                                                    <span class="table-checkmark"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <textarea name="<?php echo $code; ?>_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                    }
                    ?>

                    <!-- REMARKS SECTION -->
                    <?php if (!empty($meta['step2_remark_title']) || !empty($meta['step2_remark_text'])): ?>
                        <div class="remarks-container" style="margin-top: 2rem;">
                            <?php if (!empty($meta['step2_remark_title'])): ?>
                                <div class="remarks-header">
                                    <?php echo htmlspecialchars($meta['step2_remark_title']); ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($meta['step2_remark_text'])): ?>
                                <div class="remarks-body">
                                    <?php echo ($meta['step2_remark_text']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-navigation no-print" style="margin-top: 2rem;">
                        <button type="button" class="btn btn-secondary btn-prev-step"><i data-lucide="chevron-left"></i> ย้อนกลับ</button>
                        <button type="button" class="btn btn-primary btn-next-step">หน้าถัดไป (แบบประเมินตนเอง) <i data-lucide="chevron-right"></i></button>
                    </div>
                </div>


                <!-- ================= STEP 3: page4.php ================= -->
                <div id="step-3" class="form-step">
                    <!-- Field: ชื่อข้อมูล -->
                    <div class="form-group mb-4" style="margin-top: 1rem;">
                        <label for="info-title-display-s3" class="text-bold">ชื่อข้อมูล :</label>
                        <textarea id="info-title-display-s3" rows="2" placeholder="กรอกชื่อข้อมูลหรือชื่อชุดข้อมูล..." readonly style="background-color: #f1f5f9; cursor: not-allowed;"></textarea>
                    </div>

                    <?php if (!empty($meta['step3_instruction_title']) || !empty($meta['step3_instruction_text'])): ?>
                        <div class="paper-header" style="margin-top: 1.5rem;">
                            <?php if (!empty($meta['step3_instruction_title'])): ?>
                                <div class="official-title-bar">
                                    <h2><?php echo htmlspecialchars($meta['step3_instruction_title']); ?></h2>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($meta['step3_instruction_text'])): ?>
                                <div class="instruction-box" style="margin-top: 1.5rem; border-left: 4px solid var(--moc-gold); line-height: 1.45; font-size: 0.95rem; text-align: left; background-color: var(--moc-gold-light); color: var(--text-dark);">
                                    <?php echo ($meta['step3_instruction_text']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div style="margin-top: 1.5rem;">
                        <?php 
                        $step2_cats = $form_categories[2] ?? [];
                        $dim_index = 0;
                        foreach ($step2_cats as $cat): 
                            $cat_code = strtolower($cat['code']);
                            $cat_fields = $step3_fields[$cat_code] ?? [];
                            if (empty($cat_fields)) continue;
                            $dim_index++;
                        ?>
                            <div class="dimension-sub-bar" style="margin-top: 3rem;">
                                <h4><?php echo $dim_index; ?>. <?php echo htmlspecialchars($cat['title']); ?></h4>
                            </div>

                            <?php if (!empty($cat['description'])): ?>
                                <div class="dimension-desc-box" style="margin-bottom: 1.5rem;">
                                    <?php echo htmlspecialchars($cat['description']); ?>
                                </div>
                            <?php endif; ?>

                            <?php foreach ($cat_fields as $f): 
                                $code = htmlspecialchars($f['field_code']);
                                $label = htmlspecialchars($f['label']);
                                $desc = $f['description'];
                                
                                // Split level options by newline
                                $options = explode("\n", str_replace("\r", "", $desc));
                            ?>
                                <!-- Card: <?php echo $code; ?> -->
                                <div class="self-assess-card">
                                    <div class="self-assess-header">
                                        <?php echo $label; ?>
                                    </div>
                                    <div class="self-assess-options">
                                        <?php 
                                        foreach ($options as $idx => $opt): 
                                            $opt = trim($opt);
                                            if (empty($opt)) continue;
                                            $val = $idx + 1;
                                        ?>
                                            <label class="self-assess-option">
                                                <input type="radio" name="<?php echo $code; ?>" value="<?php echo $val; ?>">
                                                <span class="self-assess-option-text"><?php echo htmlspecialchars($opt); ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>

                    <!-- ===== ส่วนที่ 2: แสดงผลประเมินคุณภาพข้อมูลด้วย Radar Graph ===== -->
                    <div class="dimension-sub-bar" style="margin-top: 3rem;">
                        <h4>ส่วนที่ 2 การแสดงผลประเมินคุณภาพข้อมูลด้วยตัวเอง</h4>
                    </div>

                    <div id="radar-section" style="margin-top: 1.5rem; padding: 1.5rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 12px;">
                        <div style="display: flex; align-items: flex-start; gap: 2rem; flex-wrap: wrap;">
                            <!-- Label Left -->
                            <div style="min-width: 140px; padding-top: 1.5rem;">
                                <p style="font-weight: 700; color: var(--moc-blue-deep); font-size: 0.9rem; margin-bottom: 1rem;">มิติคุณภาพของข้อมูล</p>
                                <div id="score-legend" style="display: flex; flex-direction: column; gap: 0.5rem;"></div>
                            </div>

                            <!-- Radar Chart -->
                            <div style="flex: 1; min-width: 280px; max-width: 480px; position: relative;">
                                <div style="height: 400px; position: relative;">
                                    <canvas id="radarChart"></canvas>
                                </div>
                                <p id="radar-placeholder-msg" style="text-align:center; color:#94a3b8; font-size:0.85rem; margin-top:0.5rem;">
                                    กรุณาเลือกคะแนนในส่วนที่ 1 เพื่อแสดงกราฟ
                                </p>
                            </div>

                            <!-- Score Table -->
                            <div style="min-width: 200px;">
                                <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
                                    <thead>
                                        <tr style="background: var(--moc-blue-deep); color: #fff;">
                                            <th style="padding: 0.5rem 0.75rem; text-align:left; border-radius: 6px 0 0 0;">มิติ</th>
                                            <th style="padding: 0.5rem 0.75rem; text-align:center; border-radius: 0 6px 0 0;">คะแนนเฉลี่ย</th>
                                        </tr>
                                    </thead>
                                    <tbody id="score-table-body">
                                        <tr><td colspan="2" style="text-align:center; padding:0.75rem; color:#94a3b8;">ยังไม่มีข้อมูล</td></tr>
                                    </tbody>
                                </table>
                                <div id="overall-score-box" style="margin-top: 1rem; padding: 0.75rem 1rem; background: var(--moc-gold-light); border-radius: 8px; border-left: 4px solid var(--moc-gold); display:none;">
                                    <span style="font-size: 0.85rem; color: var(--text-dark); font-weight: 600;">คะแนนรวมเฉลี่ย : </span>
                                    <span id="overall-score-value" style="font-size: 1.2rem; font-weight: 800; color: var(--moc-blue-deep);">-</span>
                                    <span style="font-size: 0.8rem; color: var(--text-muted);"> / 4.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- REMARKS SECTION -->
                    <?php if (!empty($meta['step3_remark_title']) || !empty($meta['step3_remark_text'])): ?>
                        <div class="remarks-container" style="margin-top: 2rem;">
                            <?php if (!empty($meta['step3_remark_title'])): ?>
                                <div class="remarks-header">
                                    <?php echo htmlspecialchars($meta['step3_remark_title']); ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($meta['step3_remark_text'])): ?>
                                <div class="remarks-body">
                                    <?php echo ($meta['step3_remark_text']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-navigation no-print" style="margin-top: 2rem;">
                        <button type="button" class="btn btn-secondary btn-prev-step"><i data-lucide="chevron-left"></i> ย้อนกลับ</button>
                        <button type="button" class="btn btn-primary btn-next-step">หน้าถัดไป (แบบตรวจการควบคุมฯ) <i data-lucide="chevron-right"></i></button>
                    </div>
                </div>


                <!-- ================= STEP 4: page5.php ================= -->
                <div id="step-4" class="form-step">
                    <div class="paper-header">
                        <?php if (!empty($meta['step4_instruction_title']) || !empty($meta['step4_instruction_text'])): ?>
                            <?php if (!empty($meta['step4_instruction_title'])): ?>
                                <div class="official-title-bar">
                                    <h2><?php echo htmlspecialchars($meta['step4_instruction_title']); ?></h2>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($meta['step4_instruction_text'])): ?>
                                <div class="instruction-box" style="margin-top: 2rem; border-left: 4px solid var(--moc-gold); line-height: 1.45; font-size: 0.95rem; text-align: left; background-color: var(--moc-gold-light); color: var(--text-dark);">
                                    <?php echo ($meta['step4_instruction_text']); ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <fieldset class="form-section">
                        <legend><i data-lucide="info"></i> ข้อมูลทั่วไป</legend>
                        
                        <div class="form-group">
                            <label for="info-title-display-s4" class="text-bold">ชื่อข้อมูล :</label>
                            <textarea id="info-title-display-s4" rows="2" placeholder="กรอกชื่อข้อมูลหรือชื่อชุดข้อมูล..." readonly style="background-color: #f1f5f9; cursor: not-allowed;"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="info-agency-display-s4" class="text-bold">ชื่อหน่วยงานที่ดำเนินงาน :</label>
                            <input type="text" id="info-agency-display-s4" readonly style="background-color: #f1f5f9; cursor: not-allowed;">
                        </div>

                        <?php
                        $s4_general_fields = $step4_fields['INFO_GENERAL_S4'] ?? [];
                        foreach ($s4_general_fields as $f) {
                            renderStep1Field($f);
                        }
                        ?>
                    </fieldset>

                    <?php
                    $step4_cats_db = $form_categories[4] ?? [];
                    foreach ($step4_cats_db as $cat_data) {
                        $cat = $cat_data['code'];
                        if ($cat === 'INFO_GENERAL_S4') continue;
                        $cat_fields = $step4_fields[$cat] ?? [];
                        if (empty($cat_fields)) continue;
                        
                        $title = $cat_data['title'];
                        $type = $cat_data['category_type'] ?? 'risk';
                        ?>
                        <!-- SECTION: <?php echo htmlspecialchars($cat); ?> -->
                        <fieldset class="form-section">
                            <legend><i data-lucide="shield-check"></i> <?php echo htmlspecialchars($title); ?></legend>

                            <div class="table-responsive">
                                <table class="assessment-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 60px; text-align: center;">รหัส</th>
                                            <th style="width: 45%;">
                                                <?php echo ($type === 'risk') ? 'มาตรฐานคุณภาพข้อมูล<br>(Data Quality Standards)' : htmlspecialchars($title); ?>
                                            </th>
                                            <?php if ($type === 'risk'): ?>
                                                <th style="width: 100px; text-align: center;">มีอย่าง<br>เหมาะสม<br><small style="font-weight:400; color:#10b981;">(ความเสี่ยงต่ำ)</small></th>
                                                <th style="width: 100px; text-align: center;">มีบางส่วน<br><small style="font-weight:400; color:#f59e0b;">(ความเสี่ยงปานกลาง)</small></th>
                                                <th style="width: 100px; text-align: center;">ไม่มี<br><small style="font-weight:400; color:#ef4444;">(ความเสี่ยงสูง)</small></th>
                                            <?php else: ?>
                                                <th style="width: 150px; text-align: center;">ใช่</th>
                                                <th style="width: 150px; text-align: center;">ไม่ใช่</th>
                                            <?php endif; ?>
                                            <th style="min-width: 250px;">หลักฐาน / ความเห็น</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cat_fields as $f): 
                                            $code = $f['field_code'];
                                            $label = htmlspecialchars($f['label']);
                                            $desc = htmlspecialchars($f['description']);
                                            $full_text = $label . (!empty($desc) ? " ($desc)" : "");
                                        ?>
                                            <tr>
                                                <td class="text-center font-bold"><?php echo strtoupper($code); ?></td>
                                                <td class="text-justify font-medium"><?php echo $full_text; ?></td>
                                                
                                                <?php if ($type === 'risk'): ?>
                                                    <td class="text-center">
                                                        <label class="table-radio"><input type="radio" name="<?php echo $code; ?>" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                                    </td>
                                                    <td class="text-center">
                                                        <label class="table-radio"><input type="radio" name="<?php echo $code; ?>" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                                    </td>
                                                    <td class="text-center">
                                                        <label class="table-radio"><input type="radio" name="<?php echo $code; ?>" value="ไม่มี"><span class="table-checkmark"></span></label>
                                                    </td>
                                                <?php else: ?>
                                                    <td class="text-center">
                                                        <label class="table-radio"><input type="radio" name="<?php echo $code; ?>" value="ใช่"><span class="table-checkmark"></span></label>
                                                    </td>
                                                    <td class="text-center">
                                                        <label class="table-radio"><input type="radio" name="<?php echo $code; ?>" value="ไม่ใช่"><span class="table-checkmark"></span></label>
                                                    </td>
                                                <?php endif; ?>
                                                
                                                <td><textarea name="<?php echo $code; ?>_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </fieldset>
                        <?php
                    }
                    ?>

                    <!-- REMARKS SECTION -->
                    <?php if (!empty($meta['step4_remark_title']) || !empty($meta['step4_remark_text'])): ?>
                        <div class="remarks-container" style="margin-top: 2rem;">
                            <?php if (!empty($meta['step4_remark_title'])): ?>
                                <div class="remarks-header">
                                    <?php echo htmlspecialchars($meta['step4_remark_title']); ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($meta['step4_remark_text'])): ?>
                                <div class="remarks-body">
                                    <?php echo ($meta['step4_remark_text']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-navigation no-print" style="margin-top: 2rem;">
                        <button type="button" class="btn btn-secondary btn-prev-step"><i data-lucide="chevron-left"></i> ย้อนกลับ</button>
                        <button type="submit" class="btn btn-primary" id="btn-submit-form">
                            ส่งข้อมูลและสิ้นสุดการประเมิน <i data-lucide="send"></i>
                        </button>
                    </div>
                </div></form>
        </main>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- Custom Script for Multi-step, Validation, AutoSave, Radar Chart -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('dqa-form');
        const steps = document.querySelectorAll(".form-step");
        const nextBtns = document.querySelectorAll(".btn-next-step");
        const prevBtns = document.querySelectorAll(".btn-prev-step");
        const stepperSteps = document.querySelectorAll(".stepper-step");
        const stepperProgress = document.getElementById("stepper-progress");
        
        let currentStep = 0;
        const requiredFieldsByStep = <?php echo json_encode($required_fields_by_step, JSON_UNESCAPED_UNICODE); ?>;

        // --- 0. ระบบประสานข้อมูล "ชื่อข้อมูล" และ "หน่วยงาน" ไปยังหน้าต่างๆ ---
        const infoTitle = document.getElementById('info_title');
        const infoAgency = document.getElementById('info_agency');
        
        const displayTitles = [
            document.getElementById('info-title-display-s2'),
            document.getElementById('info-title-display-s3'),
            document.getElementById('info-title-display-s4')
        ];
        const displayAgencyS4 = document.getElementById('info-agency-display-s4');

        function syncInfoFields() {
            const titleVal = infoTitle ? infoTitle.value : '';
            const agencyVal = infoAgency ? infoAgency.value : '';
            
            displayTitles.forEach(el => {
                if (el) el.value = titleVal;
            });
            if (displayAgencyS4) displayAgencyS4.value = agencyVal;
        }

        if (infoTitle) {
            infoTitle.addEventListener('input', syncInfoFields);
        }
        if (infoAgency) {
            infoAgency.addEventListener('change', syncInfoFields);
        }

        // --- 1. ระบบควบคุมการสลับหน้า (Stepper & Multi-step Form) ---
        function updateStepper() {
            stepperSteps.forEach((step, idx) => {
                step.classList.remove("active", "completed");
                if (idx < currentStep) {
                    step.classList.add("completed");
                } else if (idx === currentStep) {
                    step.classList.add("active");
                }
            });
            const progressPercent = (currentStep / (steps.length - 1)) * 100;
            if (stepperProgress) {
                stepperProgress.style.width = progressPercent + "%";
            }
        }

        function showStep(index) {
            steps.forEach((step, idx) => {
                step.classList.toggle("active", idx === index);
            });
            currentStep = index;
            updateStepper();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        nextBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                if (validateStep(currentStep)) {
                    saveToServer(); // บันทึกข้อมูลไปยังเซิร์ฟเวอร์ทันทีก่อนขยับไปหน้าถัดไป
                    if (currentStep < steps.length - 1) {
                        showStep(currentStep + 1);
                    }
                }
            });
        });

        prevBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                saveToServer();
                if (currentStep > 0) {
                    showStep(currentStep - 1);
                }
            });
        });

        // คุมให้คลิกที่ Stepper เพื่อสลับไปหน้าที่เคยกรอกและผ่านการตรวจสอบแล้วได้
        stepperSteps.forEach((step, idx) => {
            step.addEventListener("click", () => {
                if (idx < currentStep) {
                    showStep(idx);
                } else if (idx > currentStep) {
                    let canGo = true;
                    for (let s = currentStep; s < idx; s++) {
                        if (!validateStep(s)) {
                            canGo = false;
                            break;
                        }
                    }
                    if (canGo) {
                        showStep(idx);
                    }
                }
            });
        });


        // --- 2. ระบบคำนวณกราฟใยแมงมุม (Radar Chart Logic) ---
        const dimensions = [
            {
                label: 'ความถูกต้อง\nและสมบูรณ์',
                labelFull: 'ความถูกต้อง และสมบูรณ์\n(Accuracy and Completeness)',
                color: '#3b82f6',
                names: ['sa_1_1', 'sa_1_2', 'sa_1_3', 'sa_1_4', 'sa_1_5']
            },
            {
                label: 'ความสอดคล้องกัน',
                labelFull: 'ความสอดคล้องกัน (Consistency)',
                color: '#f59e0b',
                names: ['sa_2_1', 'sa_2_2', 'sa_2_3', 'sa_2_4', 'sa_2_5', 'sa_2_6']
            },
            {
                label: 'ตรงตามความ\nต้องการของผู้ใช้',
                labelFull: 'ตรงตามความต้องการของผู้ใช้ (Relevancy)',
                color: '#10b981',
                names: ['sa_3_1', 'sa_3_2']
            },
            {
                label: 'ความเป็นปัจจุบัน',
                labelFull: 'ความเป็นปัจจุบัน (Timeliness)',
                color: '#8b5cf6',
                names: ['sa_4_1', 'sa_4_2', 'sa_4_3', 'sa_4_4']
            },
            {
                label: 'ความพร้อมใช้',
                labelFull: 'ความพร้อมใช้ (Availability)',
                color: '#ef4444',
                names: ['sa_5_1', 'sa_5_2', 'sa_5_3', 'sa_5_4', 'sa_5_5']
            }
        ];

        let radarChart = null;

        function getCheckedValue(radioName) {
            const el = document.querySelector(`input[name="${radioName}"]:checked`);
            return el ? parseFloat(el.value) : null;
        }

        function calcDimensionScore(names) {
            const vals = names.map(n => getCheckedValue(n)).filter(v => v !== null);
            if (vals.length === 0) return null;
            return vals.reduce((a, b) => a + b, 0) / vals.length;
        }

        function getScoreColor(score) {
            if (score === null) return '#cbd5e1';
            if (score >= 3.5) return '#10b981';
            if (score >= 2.5) return '#3b82f6';
            if (score >= 1.5) return '#f59e0b';
            return '#ef4444';
        }

        function getScoreLabel(score) {
            if (score === null) return '-';
            if (score >= 3.5) return 'ดีมาก';
            if (score >= 2.5) return 'ดี';
            if (score >= 1.5) return 'ปานกลาง';
            return 'ต่ำ';
        }

        function updateRadar() {
            const scores = dimensions.map(d => calcDimensionScore(d.names));
            const hasData = scores.some(s => s !== null);

            const msg = document.getElementById('radar-placeholder-msg');
            if (msg) msg.style.display = hasData ? 'none' : 'block';

            const tbody = document.getElementById('score-table-body');
            if (tbody) {
                const rowColors = ['#eff6ff', '#fffbeb', '#ecfdf5', '#f5f3ff', '#fef2f2'];
                tbody.innerHTML = dimensions.map((d, i) => {
                    const sc = scores[i];
                    const display = sc !== null ? sc.toFixed(2) : '-';
                    const clr = getScoreColor(sc);
                    const lbl = getScoreLabel(sc);
                    return `<tr style="background:${rowColors[i]};">
                        <td style="padding:0.45rem 0.75rem; border-bottom:1px solid #e2e8f0; color:var(--moc-blue-deep); font-weight:600; font-size:0.82rem;">${d.labelFull.split('\n')[0]}</td>
                        <td style="padding:0.45rem 0.75rem; border-bottom:1px solid #e2e8f0; text-align:center;">
                            <span style="background:${clr}22; color:${clr}; font-weight:700; padding:0.15rem 0.5rem; border-radius:99px; font-size:0.85rem;">${display}</span>
                            ${sc !== null ? `<span style="font-size:0.75rem; color:#64748b; display:block;">${lbl}</span>` : ''}
                        </td>
                    </tr>`;
                }).join('');
            }

            const validScores = scores.filter(s => s !== null);
            const overallBox = document.getElementById('overall-score-box');
            const overallVal = document.getElementById('overall-score-value');
            if (validScores.length > 0 && overallBox && overallVal) {
                const overall = validScores.reduce((a, b) => a + b, 0) / validScores.length;
                overallVal.textContent = overall.toFixed(2);
                overallVal.style.color = getScoreColor(overall);
                overallBox.style.display = 'block';
            } else if (overallBox) {
                overallBox.style.display = 'none';
            }

            const legend = document.getElementById('score-legend');
            if (legend) {
                legend.innerHTML = dimensions.map((d, i) => {
                    const sc = scores[i];
                    const display = sc !== null ? sc.toFixed(2) : '-';
                    return `<div style="display:flex; align-items:center; gap:0.4rem; font-size:0.8rem; color:var(--text-dark);">
                        <span style="width:12px; height:12px; border-radius:50%; background:${d.color}; flex-shrink:0;"></span>
                        <span>${d.label.replace(/\n/g, ' ')}</span>
                        <span style="font-weight:700; color:${getScoreColor(sc)}; margin-left:auto;">${display}</span>
                    </div>`;
                }).join('');
            }

            const chartData = scores.map(s => s !== null ? parseFloat(s.toFixed(2)) : 0);

            if (!radarChart) {
                const ctx = document.getElementById('radarChart').getContext('2d');
                radarChart = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: [
                            ['ความถูกต้อง', 'และสมบูรณ์', '(Accuracy and', 'Completeness)'],
                            ['ตรงตามความต้องการ', 'ของผู้ใช้', '(Relevancy)'],
                            ['ความสอดคล้องกัน', '(Consistency)'],
                            ['ความเป็นปัจจุบัน', '(Timeliness)'],
                            ['ความพร้อมใช้', '(Availability)']
                        ],
                        datasets: [{
                            label: 'คะแนนประเมิน',
                            data: [chartData[0], chartData[2], chartData[1], chartData[3], chartData[4]],
                            backgroundColor: 'rgba(59, 130, 246, 0.15)',
                            borderColor: '#3b82f6',
                            borderWidth: 2.5,
                            pointBackgroundColor: dimensions.map(d => d.color),
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 500 },
                        scales: {
                            r: {
                                min: 0,
                                max: 4,
                                ticks: {
                                    stepSize: 1,
                                    font: { size: 10, family: 'Sarabun' },
                                    color: '#64748b',
                                    backdropColor: 'transparent'
                                },
                                pointLabels: {
                                    font: { size: 10, family: 'Sarabun' },
                                    color: '#1e40af'
                                },
                                grid: { color: 'rgba(100,116,139,0.2)' },
                                angleLines: { color: 'rgba(100,116,139,0.3)' }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: ctx => ` คะแนน: ${ctx.parsed.r.toFixed(2)} / 4.00`
                                },
                                titleFont: { family: 'Sarabun' },
                                bodyFont: { family: 'Sarabun' }
                            }
                        }
                    }
                });
            } else {
                radarChart.data.datasets[0].data = [chartData[0], chartData[2], chartData[1], chartData[3], chartData[4]];
                radarChart.update();
            }
        }

        // --- 3. ระบบโหลดและบันทึกข้อมูล (Autosave & Load Data) ---
        async function loadData() {
            try {
                const response = await fetch('api.php');
                if (response.ok) {
                    const data = await response.json();
                    populateForm(data);
                    setTimeout(updateRadar, 300);
                } else {
                    loadLocalDraft();
                }
            } catch (error) {
                console.error('Error fetching data:', error);
                loadLocalDraft();
            }
        }

        function populateForm(data) {
            if (!data || Object.keys(data).length === 0) return;
            Object.keys(data).forEach(key => {
                const elements = document.getElementsByName(key);
                if (elements.length > 0) {
                    if (elements[0].type === 'radio') {
                        elements.forEach(radio => {
                            if (radio.value === data[key]) {
                                radio.checked = true;
                            }
                        });
                    } else {
                        elements[0].value = data[key];
                    }
                }
            });
            syncInfoFields();
        }

        function loadLocalDraft() {
            const draft = localStorage.getItem('dqa_checklist_draft');
            if (draft) {
                try {
                    const data = JSON.parse(draft);
                    populateForm(data);
                    syncInfoFields();
                    showToast('โหลดข้อมูลแบบร่างชั่วคราวจากเบราว์เซอร์แล้ว');
                    setTimeout(updateRadar, 300);
                } catch (e) {
                    console.error('Error parsing local draft:', e);
                }
            }
        }

        function getFormData() {
            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });
            return data;
        }

        async function saveToServer() {
            const data = getFormData();
            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                if (response.ok) {
                    const result = await response.json();
                    showToast(result.message || 'บันทึกความคืบหน้าสำเร็จ');
                    localStorage.removeItem('dqa_checklist_draft');
                } else {
                    throw new Error('Server error');
                }
            } catch (error) {
                localStorage.setItem('dqa_checklist_draft', JSON.stringify(data));
                showToast('⚠️ ไม่สามารถบันทึกไปยังฐานข้อมูลได้ บันทึกร่างในเครื่องชั่วคราว');
            }
        }

        let autoSaveTimeout;
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            const eventType = (input.type === 'radio' || input.tagName === 'SELECT') ? 'change' : 'input';
            input.addEventListener(eventType, () => {
                if (input.name.startsWith('sa_')) {
                    updateRadar();
                }
                
                const data = getFormData();
                localStorage.setItem('dqa_checklist_draft', JSON.stringify(data));

                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    saveToServer();
                }, 1500);
            });
        });

        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            if (toast && toastMessage) {
                toastMessage.textContent = message;
                toast.classList.remove('hide');
                setTimeout(() => {
                    toast.classList.add('hide');
                }, 3000);
            }
        }


        // --- 4. ระบบตรวจสอบความถูกต้องแบบสเต็ป (Step-by-Step Validation) ---
        function validateStep(stepIndex) {
            const oldErrors = form.querySelectorAll('.input-error, .row-error, .card-error');
            oldErrors.forEach(el => {
                el.classList.remove('input-error', 'row-error', 'card-error');
            });

            let isValid = true;
            const errors = [];

            // Get dynamic list of required fields for this step
            const reqs = requiredFieldsByStep[stepIndex] || [];
            let unselectedCount = 0;

            reqs.forEach(field => {
                const code = field.code;
                const label = field.label;
                
                // Determine validation behavior based on step index (Step 2, 3, 4 evaluation fields are always radios in HTML)
                let isRadio = false;
                let inputName = code;

                if (stepIndex === 1) { // Step 2 (Index 1) is always radios with _status suffix
                    isRadio = true;
                    inputName = code + '_status';
                } else if (stepIndex === 2) { // Step 3 (Index 2) is always radios
                    isRadio = true;
                } else if (stepIndex === 3) { // Step 4 (Index 3)
                    if (field.category !== 'INFO_GENERAL_S4' && field.category !== 'info_general_s4') {
                        isRadio = true;
                    }
                }

                if (isRadio) {
                    const checked = form.querySelector(`input[name="${inputName}"]:checked`);
                    if (!checked) {
                        isValid = false;
                        unselectedCount++;
                        
                        // Add validation styling error indicators
                        const firstRadio = form.querySelector(`input[name="${inputName}"]`);
                        if (firstRadio) {
                            if (stepIndex === 2) { // Step 3 cards
                                const card = firstRadio.closest('.self-assess-card');
                                if (card) card.classList.add('card-error');
                            } else { // Tables
                                const tr = firstRadio.closest('tr');
                                if (tr) tr.classList.add('row-error');
                            }
                        }
                    }
                } else {
                    // For standard inputs (text, textarea, select, date)
                    const el = document.getElementById(code);
                    if (el && !el.value.trim()) {
                        el.classList.add('input-error');
                        isValid = false;
                        errors.push(label);
                    }
                }
            });

            if (unselectedCount > 0) {
                if (stepIndex === 1) {
                    errors.push(`เกณฑ์ประเมินมิติคุณภาพข้อมูลยังเลือกไม่ครบ ${unselectedCount} หัวข้อ`);
                } else if (stepIndex === 2) {
                    errors.push(`แบบประเมินตนเองระดับคุณภาพยังเลือกไม่ครบ ${unselectedCount} หัวข้อ`);
                } else if (stepIndex === 3) {
                    errors.push(`เกณฑ์ประเมินการควบคุมติดตามคุณภาพยังเลือกไม่ครบ ${unselectedCount} หัวข้อ`);
                } else {
                    errors.push(`คำถามประเมินยังไม่ได้เลือกตอบอีก ${unselectedCount} ข้อ`);
                }
            }

            if (!isValid) {
                showCustomValidationModal(errors);
                const firstError = form.querySelector('.input-error, .row-error, .card-error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
            return isValid;
        }

        function showCustomValidationModal(errors) {
            const existing = document.querySelector('.dqa-modal-overlay');
            if (existing) existing.remove();

            const overlay = document.createElement('div');
            overlay.className = 'dqa-modal-overlay';
            overlay.innerHTML = `
                <div class="dqa-modal-card">
                    <div class="dqa-modal-header">
                        <div class="dqa-modal-icon">
                            <i data-lucide="alert-triangle"></i>
                        </div>
                        <div class="dqa-modal-title">ข้อมูลยังไม่ครบถ้วนในขั้นตอนนี้</div>
                    </div>
                    <div class="dqa-modal-body">
                        <div class="dqa-modal-desc">กรุณาทำแบบประเมินและกรอกฟิลด์ข้อมูลบังคับต่อไปนี้ให้ครบถ้วน:</div>
                        <ul class="dqa-modal-list">
                            ${errors.map(err => `<li>${err}</li>`).join('')}
                        </ul>
                    </div>
                    <div class="dqa-modal-footer">
                        <button class="dqa-modal-btn" id="dqa-modal-close-btn">ตกลง</button>
                    </div>
                </div>
            `;
            document.body.appendChild(overlay);
            lucide.createIcons();

            const closeBtn = overlay.querySelector('#dqa-modal-close-btn');
            if (closeBtn) {
                closeBtn.focus();
                closeBtn.addEventListener('click', () => overlay.remove());
            }
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) overlay.remove();
            });
        }

        function showCustomSuccessModal() {
            const existing = document.querySelector('.dqa-modal-overlay');
            if (existing) existing.remove();

            const overlay = document.createElement('div');
            overlay.className = 'dqa-modal-overlay';
            overlay.innerHTML = `
                <div class="dqa-modal-card" style="border-top: 4px solid #10b981;">
                    <div class="dqa-modal-header" style="border-bottom: none;">
                        <div class="dqa-modal-icon" style="background-color: #d1fae5; color: #10b981;">
                            <i data-lucide="check-circle"></i>
                        </div>
                        <div class="dqa-modal-title" style="color: #065f46;">ส่งข้อมูลการประเมินเรียบร้อยแล้ว!</div>
                    </div>
                    <div class="dqa-modal-body" style="text-align: center; padding-bottom: 1.5rem;">
                        <p style="font-weight: 600; color: var(--text-dark); margin-bottom: 0.5rem;">
                            แบบตรวจประเมินคุณภาพข้อมูล DQA Checklist ของหน่วยงานคุณ ได้รับการบันทึกสำเร็จเสร็จสมบูรณ์
                        </p>
                    </div>
                    <div class="dqa-modal-footer" style="justify-content: center; background-color: #ffffff;">
                        <a href="dashboard.php" class="btn btn-primary" style="padding: 0.6rem 2rem; border-radius: 8px; text-decoration: none;">
                            ไปที่หน้าแดชบอร์ด
                        </a>
                    </div>
                </div>
            `;
            document.body.appendChild(overlay);
            lucide.createIcons();
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (validateStep(currentStep)) {
                await saveToServer();
                showCustomSuccessModal();
            }
        });


        // --- 5. ล้างข้อมูล และสั่งพิมพ์ ---
        const btnPrint = document.getElementById('btn-print');
        if (btnPrint) {
            btnPrint.addEventListener('click', async () => {
                let allValid = true;
                for (let i = 0; i < steps.length; i++) {
                    if (!validateStep(i)) {
                        showStep(i);
                        allValid = false;
                        break;
                    }
                }
                if (allValid) {
                    await saveToServer();
                    window.print();
                }
            });
        }

        const btnReset = document.getElementById('btn-reset');
        if (btnReset) {
            btnReset.addEventListener('click', async () => {
                if (confirm('คุณต้องการล้างข้อมูลในแบบฟอร์มทั้งหมดใช่หรือไม่? ข้อมูลการประเมินปัจจุบันที่เซฟบนเซิร์ฟเวอร์จะถูกรีเซ็ต')) {
                    form.reset();
                    localStorage.removeItem('dqa_checklist_draft');
                    try {
                        const response = await fetch('api.php?reset=1', { method: 'POST' });
                        if (response.ok) {
                            showToast('รีเซ็ตข้อมูลและล้างข้อมูลแบบร่างเรียบร้อยแล้ว');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }
                    } catch (e) {
                        console.error('Error resetting:', e);
                    }
                }
            });
        }


        // --- 6. เริ่มกระบวนการทำงาน ---
        loadData();
        lucide.createIcons();
    });
    </script>
</body>
</html>
