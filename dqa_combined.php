<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="แบบตรวจประเมินคุณภาพข้อมูลทั้งหมด (DQA Checklist All-in-One)">
    <title>แบบตรวจประเมินคุณภาพ (DQA Checklist) - รวมทุกขั้นตอน</title>
    
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
                    <h1>แบบตรวจประเมินคุณภาพ (DQA Checklist)</h1>
                    <span class="agency-tag">สำนักงานปลัดกระทรวงพาณิชย์</span>
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
            <form id="dqa-form" method="POST" action="api.php">
                
                <!-- ================= STEP 1: index.php ================= -->
                <div id="step-1" class="form-step active">
                    <div class="paper-header">
                        <div class="official-title-bar">
                            <h2>(ร่าง) แบบตรวจประเมินคุณภาพข้อมูล (DQA Checklist)</h2>
                        </div>
                        
                        <div class="instruction-box" style="margin-top: 2rem; border-left: 4px solid var(--moc-gold); line-height: 1.45; font-size: 0.95rem; text-align: left; background-color: var(--moc-gold-light); color: var(--text-dark);">
                            <strong>คำชี้แจง :</strong> การตรวจประเมินคุณภาพข้อมูล (DQA Checklist) นี้จัดทำขึ้นเพื่อแนะนำเครื่องมือสำหรับ ทีมผู้ประเมินคุณภาพข้อมูล เพื่อใช้ดำเนินการประเมินคุณภาพข้อมูลขององค์กรให้สมบูรณ์ ด้วยการใช้งานแบบตรวจประเมินคุณภาพข้อมูล (DQA Checklist) ซึ่งมีรายละเอียดที่จะช่วยให้การตรวจสอบกระบวนการเตรียมข้อมูลและคุณภาพข้อมูลใน 5 มิติ ได้แก่ ความถูกต้องและสมบูรณ์ (Accuracy and Completeness) ความสอดคล้องกัน (Consistency) ความเป็นปัจจุบัน (Timeliness) ตรงตามความต้องการของผู้ใช้ (Relevancy) ความพร้อมใช้ (Availability) ดังนี้
                        </div>
                    </div>

                    <!-- SECTION 1: ข้อมูลหน่วยงานและภารกิจ -->
                    <fieldset class="form-section">
                        <legend><i data-lucide="info"></i> ส่วนที่ 1: ข้อมูลทั่วไปของข้อมูลและหน่วยงาน</legend>
                        
                        <div class="form-group">
                            <label for="info-title">ชื่อข้อมูล : <span style="color:#ef4444;">*</span></label>
                            <textarea id="info-title" name="info_title" rows="2" placeholder="กรอกชื่อข้อมูลหรือชื่อชุดข้อมูล..."></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="info-agency">ชื่อหน่วยงานที่ดำเนินงาน : <span style="color:#ef4444;">*</span></label>
                                <select id="info-agency" name="info_agency">
                                    <option value="">-- เลือกหน่วยงาน --</option>
                                    <?php
                                    $agenciesQuery = $conn->query("SELECT name FROM agencies ORDER BY id ASC");
                                    if ($agenciesQuery && $agenciesQuery->num_rows > 0) {
                                        while ($agencyRow = $agenciesQuery->fetch_assoc()) {
                                            echo '<option value="' . htmlspecialchars($agencyRow['name']) . '">' . htmlspecialchars($agencyRow['name']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="form-group col-6">
                                <label for="info-mission">ภารกิจ :</label>
                                <textarea id="info-mission" name="info_mission" rows="1" placeholder="ภารกิจด้าน"></textarea>
                            </div>
                        </div>
                    </fieldset>

                    <!-- SECTION 2: ตัวชี้วัดและเป้าหมาย -->
                    <fieldset class="form-section">
                        <legend><i data-lucide="trending-up"></i> ส่วนที่ 2: ตัวชี้วัดและเป้าหมาย</legend>

                        <div class="form-group">
                            <label for="metric-name">ชื่อตัวชี้วัดผลการประเมินคุณภาพข้อมูล : <span style="color:#ef4444;">*</span></label>
                            <textarea id="metric-name" name="metric_name" rows="2" placeholder="[ตัวชี้วัดสามารถอ้างอิงจากตัวชี้วัดผลการดำเนินงาน]"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="metric-link">ลิงก์การเชื่อมโยงโครงสร้างของแผนงานที่เป็นมาตรฐาน :</label>
                            <textarea id="metric-link" name="metric_link" rows="2" placeholder="หากมี (เช่น ขอบเขตของแผนงาน องค์ประกอบ เป็นต้น)"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="metric-result">ผลลัพธ์ของการวัดผลตัวชี้วัด :</label>
                            <textarea id="metric-result" name="metric_result" rows="2" placeholder="[สำหรับภายในหน่วยงานเท่านั้น] (เช่น ระบุวัตถุประสงค์การพัฒนา ผลลัพธ์ขั้นกลาง หรือวัตถุประสงค์โครงการ เป็นต้น)"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="metric-source">แหล่งที่มาข้อมูล : <span style="color:#ef4444;">*</span></label>
                            <textarea id="metric-source" name="metric_source" rows="2" placeholder="[ข้อมูลสามารถอ้างอิงจากตัวชี้วัดผลการดำเนินงานตามเอกสารนี้โดยตรง]"></textarea>
                        </div>
                    </fieldset>

                    <!-- SECTION 3: เครือข่ายการดำเนินงานและมาตรฐาน -->
                    <fieldset class="form-section">
                        <legend><i data-lucide="users"></i> ส่วนที่ 3: แหล่งข้อมูลและการกำหนดมาตรฐาน</legend>

                        <div class="form-group">
                            <label for="source-partner">หน่วยงานเครือข่าย (Partner) / ผู้รับจ้าง (Vendor) ที่ให้ข้อมูล :</label>
                            <textarea id="source-partner" name="source_partner" rows="3" placeholder="[ข้อเสนอแนะสำหรับจัดทำ checklist นี้ให้ครบถ้วนจากหน่วยงานเครือข่ายที่สนับสนุนข้อมูลตามตัวชี้วัด ควรระบุไว้สัญญา/ความร่วมมือว่าเป็นความรับผิดชอบสำคัญในการสร้างความเชื่อมั่นในคุณภาพข้อมูลของผู้รับจ้างรายย่อยหรือผู้รับทุน]"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="source-period">ระยะเวลาของข้อมูลนำเสนอในรายงาน :</label>
                            <textarea id="source-period" name="source_period" rows="2" placeholder="รายวัน เดือน ปี"></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="metric-standard-type">ตัวชี้วัดคุณภาพข้อมูลเป็นไปตามมาตรฐานหรือกำหนดเอง :</label>
                                <select id="metric-standard-type" name="metric_standard_type">
                                    <option value="ตัวชี้วัดที่เป็นมาตรฐานสากล">ตัวชี้วัดที่เป็นมาตรฐานสากล</option>
                                    <option value="ตัวชี้วัดที่กำหนดเอง">ตัวชี้วัดที่กำหนดเอง</option>
                                </select>
                            </div>
                            
                            <div class="form-group col-6">
                                <label for="metric-standard-detail">ตัวชี้วัดที่กำหนดเองโดย :</label>
                                <textarea id="metric-standard-detail" name="metric_standard_detail" rows="1" placeholder="(โดย มาตรฐาน... หรือ ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร)"></textarea>
                            </div>
                        </div>
                    </fieldset>

                    <!-- SECTION 4: การประเมินผลและอนุมัติ -->
                    <fieldset class="form-section">
                        <legend><i data-lucide="check-circle-2"></i> ส่วนที่ 4: กระบวนการประเมินและการอนุมัติ</legend>

                        <div class="form-group">
                            <label for="eval-method">วิธีการประเมินคุณภาพข้อมูล :</label>
                            <textarea id="eval-method" name="eval_method" rows="3" placeholder="[อธิบายหรือแนบเอกสารที่เกี่ยวกับวิธีการและกระบวนการในการประเมินตัวชี้วัดคุณภาพของข้อมูล เช่น ทบทวนกระบวนการเก็บรวบรวมข้อมูลและเอกสาร สัมภาษณ์ผู้รับผิดชอบในการวิเคราะห์ข้อมูล และตรวจสอบตัวอย่างข้อมูลที่ผิดพลาด เป็นต้น]"></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="eval-date">วันที่ประเมินคุณภาพข้อมูล : <span style="color:#ef4444;">*</span></label>
                                <input type="date" id="eval-date" name="eval_date" style="width: 100%; padding: 0.6rem 0.8rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color); background-color: #ffffff;">
                            </div>
                            
                            <div class="form-group col-6">
                                <label for="eval-team">ทีมผู้ประเมินคุณภาพข้อมูล : <span style="color:#ef4444;">*</span></label>
                                <input type="text" id="eval-team" name="eval_team" placeholder="ทีมบริการข้อมูล">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="eval-approver">ผู้อนุมัติการประเมินคุณภาพข้อมูล : <span style="color:#ef4444;">*</span></label>
                            <textarea id="eval-approver" name="eval_approver" rows="2" placeholder="หัวหน้าคณะทำงานบริการข้อมูลของสำนักงานปลัดกระทรวงพาณิชย์"></textarea>
                        </div>
                    </fieldset>

                    <!-- REMARKS SECTION -->
                    <div class="remarks-container">
                        <div class="remarks-header">
                            หมายเหตุ
                        </div>
                        <div class="remarks-body">
                            <p class="remarks-intro"><strong>หน้าที่กระทรวงพาณิชย์</strong> มีอำนาจหน้าที่เกี่ยวกับการค้า ธุรกิจบริการ ทรัพย์สินทางปัญญา และราชการอื่นตามที่มีกฎหมายกำหนดให้เป็นอำนาจหน้าที่ ของกระทรวงพาณิชย์ หรือส่วนราชการที่สังกัดกระทรวงพาณิชย์</p>
                            
                            <div class="remarks-section-title">บทบาทหลัก</div>
                            
                            <div class="remarks-subsection">
                                <span class="remarks-subsection-title">ภารกิจด้านในประเทศ :</span>
                                <ol class="remarks-list">
                                    <li>การดูแลราคาสินค้าเกษตรและรายได้เกษตรกร</li>
                                    <li>ดูแลผู้บริโภคภายใต้กรอบกฎหมายของกระทรวงพาณิชย์</li>
                                    <li>ส่งเสริมและพัฒนาธุรกิจการค้า ทั้งการค้าสินค้าและธุรกิจบริการ</li>
                                    <li>คุ้มครองด้านทรัพย์สินทางปัญญา</li>
                                </ol>
                            </div>
                            
                            <div class="remarks-subsection">
                                <span class="remarks-subsection-title">ภารกิจด้านต่างประเทศ :</span>
                                <ol class="remarks-list">
                                    <li>เจรจาการค้าระหว่างประเทศ ซึ่งประกอบด้วยการเจรจาภายใต้กรอบ WTO FTA อนุภูมิภาค ภูมิภาค ฯลฯ</li>
                                    <li>จัดระเบียบและบริหารการนำเข้าส่งออก รวมทั้งการขายข้าวรัฐต่อรัฐ การค้ามันสำปะหลัง สินค้าข้อตกลงต่างๆ</li>
                                    <li>แก้ไขปัญหาและรักษาผลประโยชน์ทางการค้า เช่น การดูแลเรื่อง GSP การเก็บภาษีตอบโต้การทุ่มตลาด</li>
                                    <li>ส่งเสริมและเร่งรัดการส่งออก</li>
                                </ol>
                            </div>
                        </div>
                    </div>

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

                    <!-- Sub-Header: Accuracy and Completeness -->
                    <div class="dimension-sub-bar">
                        <h4>ความถูกต้อง และสมบูรณ์ (Accuracy and Completeness)</h4>
                    </div>

                    <div class="dimension-desc-box">
                        ข้อมูลมีความถูกต้องแม่นยำสูง หรือถ้ามีความคลาดเคลื่อนอยู่บ้าง ควรที่จะสามารถควบคุมขนาดของความคลาดเคลื่อนให้มีน้อยที่สุด และมีการตรวจสอบค่าความคลาดเคลื่อนของข้อมูลในส่วนต่าง ๆ ในทุกขั้นตอน ข้อมูลควรแสดงผลลัพธ์ที่คาดหวังไว้อย่างชัดเจนและเพียงพอ และควรถูกกำหนดโดยแหล่งที่มาดั้งเดิมของข้อมูล รวมทั้งข้อมูลที่จัดเตรียมควรมีความครบถ้วนตรงตามคุณลักษณะของข้อมูลที่คาดหวังและองค์ประกอบข้อมูลที่จำเป็นทั้งหมดที่ถูกจัดเก็บในระบบฐานข้อมูล
                    </div>

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
                                <!-- AC1 -->
                                <tr>
                                    <td class="text-center font-bold">AC1</td>
                                    <td class="text-justify font-medium">ข้อมูลมีความถูกต้องหรือไม่ (ข้อมูลไม่มีข้อผิดพลาดมีวิธีการที่ใช้ในการควบคุมข้อมูลนำเข้าและการควบคุมการประมวลผลที่ถูกต้องเชื่อถือได้ และข้อมูลที่จะนำไปใช้งานต้องผ่านการตรวจสอบว่าถูกต้อง ครบถ้วน และสมบูรณ์ เช่น มีการตรวจสอบอัตราความครบถ้วนในการกรอกข้อมูล โดยพิจารณาเฉพาะแถวข้อมูลแถวและฟิลด์ของข้อมูลที่มีความจำเป็นเท่านั้น)</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ac1_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ac1_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="ac1_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- AC2 -->
                                <tr>
                                    <td class="text-center font-bold">AC2</td>
                                    <td class="text-justify font-medium">ข้อมูลมีแหล่งที่มาที่น่าเชื่อถือหรือไม่ (มีการระบุแหล่งที่มา สามารถตรวจสอบได้ว่ามาจากแหล่งใด แหล่งที่มาข้อมูลต้องได้รับการรับรองจากหน่วยงาน/สถาบันที่น่าเชื่อถือ และมีการเผยแพร่หรือแลกเปลี่ยนเชื่อมโยงจากหน่วยงานที่มีการจดทะเบียนและมีตัวตนอยู่จริง)</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ac2_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ac2_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="ac2_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- AC3 -->
                                <tr>
                                    <td class="text-center font-bold">AC3</td>
                                    <td class="text-justify font-medium">ข้อมูลที่เก็บรวบรวมมาได้จากประชากรหรือตัวอย่างมีสัดส่วนที่เพียงพอหรือไม่ และ/หรือข้อมูลที่เก็บรวบรวมมีตรงตามดัชนีชี้วัดความสำเร็จของงาน (KPI) หรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ac3_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ac3_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="ac3_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- AC4 -->
                                <tr>
                                    <td class="text-center font-bold">AC4</td>
                                    <td class="text-justify font-medium">ผลลัพธ์การรวบรวมข้อมูลอยู่ในช่วงค่าคะแนนที่เป็นไปได้หรือสมเหตุสมผลหรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ac4_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ac4_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="ac4_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- AC5 -->
                                <tr>
                                    <td class="text-center font-bold">AC5</td>
                                    <td class="text-justify font-medium">ระเบียบวิธีวิจัยที่ใช้ในการรวบรวมข้อมูลเหมาะสมถูกต้องหรือไม่ และมีการรับประกันวิธีการ/เครื่องมือที่ใช้ในการรวบรวมข้อมูลมีความละเอียดหรือแม่นยำเพียงพอที่จะบันทึกการเปลี่ยนแปลงที่คาดไว้หรือไม่ มีความเป็นกลางหรือไม่ไม่ได้ให้เกิดระบบที่มีอคติของข้อมูล (เช่น มีความสอดคล้องกัน การนับจำนวนที่สูงหรือต่ำเกินไป เป็นต้น) หรือสมเหตุสมผลหรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ac5_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ac5_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="ac5_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- AC6 -->
                                <tr>
                                    <td class="text-center font-bold">AC6</td>
                                    <td class="text-justify font-medium">มีขั้นตอนแก้ไขความผิดพลาดของข้อมูลที่รับรู้ (เช่น ความผิดพลาดของข้อมูลมีค่าน้อยกว่าที่คาดการณ์หรือไม่ และมีการรายงานค่าความผิดพลาดของข้อมูลหรือไม่) หรือลดข้อจำกัด/ความผิดพลาดในการสำเนา/นำเข้าข้อมูลหรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ac6_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ac6_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="ac6_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- AC7 -->
                                <tr>
                                    <td class="text-center font-bold">AC7</td>
                                    <td class="text-justify font-medium">มีการประเมินปัญหาการรวบรวมข้อมูลที่รับรู้อย่างเหมาะสมหรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ac7_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ac7_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="ac7_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- AC8 -->
                                <tr>
                                    <td class="text-center font-bold">AC8</td>
                                    <td class="text-justify font-medium">มีวิธีการ/เครื่องมือป้องกันรักษาความปลอดภัยของข้อมูลหรือไม่ (เช่น มีขั้นตอนหรือมาตรการป้องกันเพื่อลดความเสี่ยงอคติหรือข้อผิดพลาดในการบันทึกข้อมูล และมีการรักษาความปลอดภัยที่เหมาะสมเพื่อป้องกันการเปลี่ยนแปลงข้อมูลโดยไม่ได้รับอนุญาต)</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ac8_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ac8_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="ac8_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Sub-Header: Relevancy -->
                    <div class="dimension-sub-bar" style="margin-top: 3rem;">
                        <h4>ตรงตามความต้องการของผู้ใช้ (Relevancy)</h4>
                    </div>

                    <!-- Instruction Details -->
                    <div class="dimension-desc-box">
                        ข้อมูลที่จัดทำขึ้นมาเป็นข้อมูลที่ผู้ใช้ต้องการ หรือเป็นข้อมูลที่จำเป็นต้องทราบ มีมุมมองและความละเอียดเพียงพอต่อนำไปใช้งาน ข้อมูลสามารถนำไปประยุกต์ใช้และเป็นประโยชน์สำหรับการดำเนินงาน/ภารกิจของหน่วยงาน และข้อมูลมีรายละเอียดในระดับเพียงพอที่จะอนุญาตให้ใช้เป็นข้อมูลประกอบการตัดสินใจในการบริหารจัดการ
                    </div>

                    <!-- Assessment Table for Relevancy -->
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
                                <!-- RE1 -->
                                <tr>
                                    <td class="text-center font-bold">RE1</td>
                                    <td class="text-justify font-medium">ข้อมูลตรงตามความต้องการของผู้ใช้งานและตามวัตถุประสงค์ของการใช้งานหรือไม่ (มีการสำรวจความต้องการใช้งาน/ความพึงพอใจของผู้ใช้งานข้อมูล เพื่อประเมินความต้องการของผู้ใช้งานและนำไปปรับปรุงคุณภาพข้อมูลได้ตรงตามความต้องการใช้งาน)</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="re1_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="re1_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="re1_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- RE2 -->
                                <tr>
                                    <td class="text-center font-bold">RE2</td>
                                    <td class="text-justify font-medium">ต้นทุนในการทำให้ระดับความถูกต้องของข้อมูลเพิ่มสูงขึ้นมากกว่ามูลค่าของข้อมูลข่าวสารที่เพิ่มขึ้นจากการใช้ประโยชน์ข้อมูลหรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="re2_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="re2_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="re2_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- RE3 -->
                                <tr>
                                    <td class="text-center font-bold">RE3</td>
                                    <td class="text-justify font-medium">มีการกำหนดค่าส่วนเกิน of ความผิดพลาดที่รับได้สำหรับแผนงานการตัดสินใจ/ประมวลผลหรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="re3_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="re3_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="re3_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- RE4 -->
                                <tr>
                                    <td class="text-center font-bold">RE4</td>
                                    <td class="text-justify font-medium">มีวิธีการตรวจสอบข้อมูลที่ซ้ำกันหรือข้อมูลที่ขาดหายหรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="re4_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="re4_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="re4_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- RE5 -->
                                <tr>
                                    <td class="text-center font-bold">RE5</td>
                                    <td class="text-justify font-medium">ชุดข้อมูลส่วนใหญ่เป็นชุดข้อมูลที่มีคุณค่าสูง (High Value Datasets) หรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="re5_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="re5_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="re5_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Sub-Header: Consistency -->
                    <div class="dimension-sub-bar" style="margin-top: 3rem;">
                        <h4>ความสอดคล้องกัน (Consistency)</h4>
                    </div>

                    <!-- Instruction Details -->
                    <div class="dimension-desc-box">
                        ข้อมูลมีความสอดคล้องต่อเนื่องในเชิงการจัดเก็บ จัดทำ และเผยแพร่ (ข้อมูลควรสะท้อนถึงกระบวนการจัดเก็บข้อมูลและวิธีการวิเคราะห์ที่เสถียรและมีสอดคล้องกันอย่างช่วงเวลา) รวมทั้งความสามารถในการนำไปเปรียบเทียบกับข้อมูลเดียวกันในอดีต และข้อมูลอื่นในช่วงเวลาเดียวกันได้อย่างกว้างขวางและสอดคล้อง โดยความสอดคล้องนี้จะเกิดจากการใช้แนวคิด การจัดหมวดหมู่ การคัดเลือกประชากรและวิธีการจัดทำด้วยวิธีทางสถิติที่เป็นมาตรฐาน
                    </div>

                    <!-- Assessment Table for Consistency -->
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
                                <!-- CO1 -->
                                <tr>
                                    <td class="text-center font-bold">CO1</td>
                                    <td class="text-justify font-medium">มีรูปแบบการจัดเก็บข้อมูลที่สอดคล้องและเป็นมาตรฐานเดียวกันหรือไม่ (ทั้งภายในชุดข้อมูลและฟิลด์ข้อมูลเดียวกัน มีข้อมูลที่เป็นรูปแบบเดียวกัน เช่น ฟิลด์ A มีแต่ข้อมูลตัวเลข จะต้องไม่มีอักษรหรือสัญลักษณ์พิเศษในฟิลด์นี้ เป็นต้น และมีการจัดทำข้อมูลตามมาตรฐานเดียวกัน อาทิ การกำหนดกรอบแนวคิด คำนิยาม หน่วยนับ หรือการจำแนกระยะเวลาจัดเก็บ หรือเผยแพร่)</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="co1_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="co1_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="co1_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- CO2 -->
                                <tr>
                                    <td class="text-center font-bold">CO2</td>
                                    <td class="text-justify font-medium">หากใช้วิธีการจัดเก็บข้อมูลแบบเดียวกันเพื่อวัดผล/สังเกตการณ์ในเรื่องเดียวกันในหลายครั้ง จะได้ผลลัพธ์ที่เหมือนกันในแต่ละครั้งหรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="co2_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="co2_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="co2_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- CO3 -->
                                <tr>
                                    <td class="text-center font-bold">CO3</td>
                                    <td class="text-justify font-medium">มีเอกสารและแนวปฏิบัติในการจัดเก็บและวิเคราะห์ข้อมูล และถูกนำไปใช้เพื่อสร้างความเชื่อมั่นว่าเป็นไปตามแนวปฏิบัติเดียวกันในแต่ละครั้งหรือไม่ และมีเอกสารสำหรับการทบทวนการจัดเก็บข้อมูลและการดูแลรักษาเป็นระยะ ๆ หรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="co3_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="co3_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="co3_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- CO4 -->
                                <tr>
                                    <td class="text-center font-bold">CO4</td>
                                    <td class="text-justify font-medium">มีความสอดคล้องกันในกระบวนการจัดเก็บข้อมูลที่ถูกใช้ระหว่างปี พื้นที่จัดเก็บ และแหล่งที่มาของข้อมูล หรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="co4_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="co4_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="co4_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- SECTION: Timeliness -->
                    <div class="dimension-sub-bar" style="margin-top: 3rem;">
                        <h4>ความเป็นปัจจุบัน (Timeliness)</h4>
                    </div>

                    <div class="dimension-desc-box">
                        ความทันเวลาต่อการใช้งานของข้อมูล ไม่ว่าจะเป็นการนำไปใช้ต่อในแง่การประมวลผลหรือการเผยแพร่ข้อมูล ความทันเวลาอ้างอิงจากความล่าช้าของข้อมูลซึ่งวัดได้หลายลักษณะขึ้นอยู่กับประเภทของข้อมูล เช่น วัดจากระยะเวลาที่ได้รับข้อมูลจนถึงเวลาที่ข้อมูลพร้อมใช้งาน วัดจากระยะเวลาที่กำหนดของการเผยแพร่กับเวลาที่สามารถเผยแพร่ได้จริง
                    </div>

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
                                <!-- TI1 -->
                                <tr>
                                    <td class="text-center font-bold">TI1</td>
                                    <td class="text-justify font-medium">ข้อมูลที่จัดหาได้มีความถี่เพียงพอต่อการแจ้งแผนงานในการตัดสินใจ บริหารจัดการหรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ti1_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ti1_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="ti1_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- TI2 -->
                                <tr>
                                    <td class="text-center font-bold">TI2</td>
                                    <td class="text-justify font-medium">ข้อมูลที่ถูกนำมารายงานส่วนใหญ่ใช้ได้จริงและเป็นปัจจุบันหรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ti2_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ti2_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="ti2_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- TI3 -->
                                <tr>
                                    <td class="text-center font-bold">TI3</td>
                                    <td class="text-justify font-medium">ข้อมูลถูกนำมารายงานทันทีเท่าที่จะเป็นไปได้ภายหลังการจัดเก็บข้อมูลหรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ti3_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ti3_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="ti3_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- TI4 -->
                                <tr>
                                    <td class="text-center font-bold">TI4</td>
                                    <td class="text-justify font-medium">มีกำหนดตารางเวลาการจัดเก็บข้อมูลเป็นประจำเพื่อตอบสนองต่อความต้องการของแผนงานการบริหารจัดการหรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ti4_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ti4_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="ti4_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- TI5 -->
                                <tr>
                                    <td class="text-center font-bold">TI5</td>
                                    <td class="text-justify font-medium">ข้อมูลมีการจัดเก็บอย่างเหมาะสมและพร้อมใช้งานหรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ti5_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="ti5_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="ti5_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- SECTION: Availability -->
                    <div class="dimension-sub-bar" style="margin-top: 3rem;">
                        <h4>ความพร้อมใช้ (Availability)</h4>
                    </div>

                    <div class="dimension-desc-box">
                        ข้อมูลควรเข้าถึงได้ง่าย สามารถใช้งานได้จริง และสามารถใช้งานได้ตลอดเวลา
                    </div>

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
                                <!-- AV1 -->
                                <tr>
                                    <td class="text-center font-bold">AV1</td>
                                    <td class="text-justify font-medium">มีกระบวนการจัดทำข้อมูลที่สามารถอ่านได้ด้วยเครื่องคอมพิวเตอร์ (Machine Readable) และที่สามารถนำไปใช้งานต่อได้ง่ายหรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="av1_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="av1_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="av1_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- AV2 -->
                                <tr>
                                    <td class="text-center font-bold">AV2</td>
                                    <td class="text-justify font-medium">มีการจัดทำและเผยแพร่คำอธิบายข้อมูล หรือ Metadata สำหรับชุดข้อมูล ของหน่วยงานหรือไม่</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="av2_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="av2_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="av2_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- AV3 -->
                                <tr>
                                    <td class="text-center font-bold">AV3</td>
                                    <td class="text-justify font-medium">มีช่องทางการเผยแพร่ข้อมูลที่หลากหลายและสามารถเข้าถึงได้ง่ายหรือไม่ (มีระบบเทคโนโลยีสารสนเทศที่ทันสมัยและเหมาะสม และแพลตฟอร์มสื่อสังคมออนไลน์ต่าง ๆ ที่เป็นช่องทางในการเผยแพร่และสื่อสาร หรือ มีเว็บไซต์นำเสนอชุดข้อมูลตามมาตรฐานข้อมูลเปิดและมีการปรับปรุงสม่ำเสมอ)</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="av3_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="av3_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="av3_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                                <!-- AV4 -->
                                <tr>
                                    <td class="text-center font-bold">AV4</td>
                                    <td class="text-justify font-medium">มีกระบวนการ/แนวปฏิบัติในการขอข้อมูลแชร์ข้อมูล (ที่ไม่ใช่ข้อมูลสาธารณะ) ของหน่วยงานที่ประกาศให้ผู้ขอใช้ข้อมูลหรือไม่ (เช่น มีศูนย์บริการข้อมูล หรือ มีเจ้าหน้าที่ให้ความช่วยเหลือในการขอข้อมูล)</td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="av4_status" value="ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="table-radio">
                                            <input type="radio" name="av4_status" value="ไม่ใช่">
                                            <span class="table-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea name="av4_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

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

                    <div class="paper-header">
                        <div class="official-title-bar">
                            <h2>(ร่าง) แบบประเมินคุณภาพข้อมูลด้วยตนเอง (DQA Self-Assessment)</h2>
                        </div>
                        <div class="instruction-box" style="margin-top: 2rem; border-left: 4px solid var(--moc-gold); line-height: 1.45; font-size: 0.95rem; text-align: left; background-color: var(--moc-gold-light); color: var(--text-dark);">
                            <strong>คำชี้แจง :</strong> แบบประเมินคุณภาพข้อมูลด้วยตนเอง มีวัตถุประสงค์ให้หน่วยงานภาครัฐใช้สำหรับประเมินคุณภาพข้อมูลภายในหน่วยงานผ่านเกณฑ์คุณภาพข้อมูลทั้ง 5 มิติ ได้แก่ ความถูกต้องและสมบูรณ์ ความสอดคล้องกัน ความเป็นปัจจุบัน ตรงตามความต้องการของผู้ใช้ และความพร้อมใช้ โดยเป็นการประเมินตนเอง (Self-assessment) เบื้องต้นเพื่อให้ทราบว่าข้อมูลภายในหน่วยงานมีคุณภาพมากน้อยเพียงใด และควรปรับปรุงหรือพัฒนาในมิติใดบ้างเพื่อให้ข้อมูลมีคุณภาพ สามารถนำไปใช้ประโยชน์เพื่อเพิ่มประสิทธิภาพในการทำงาน เพิ่มคุณค่าในการให้บริการ และต่อยอดการพัฒนาของประเทศในมิติต่าง ๆ ได้ ในการใช้งาน เจ้าของข้อมูล (Data Owner) ควรพิจารณาข้อมูลภาพรวมของหน่วยงาน ทำความเข้าใจเกณฑ์และคำอธิบาย และทำการประเมินคุณภาพข้อมูล โดยกรอกค่าคะแนนในแต่ละมิติของตัวชี้วัด (Indicators) จากนั้นระบบจะประมวลผลตามเกณฑ์ประเมินคุณภาพข้อมูลในแต่ละมิติ และจะแสดงผลในรูปแบบ Radar Graph และจัดพิมพ์แบบประเมินส่งให้ทีมผู้ประเมินเพื่อใช้ประกอบการตรวจประเมินคุณภาพข้อมูล
                        </div>
                    </div>

                    <!-- SECTION: self-assessment options -->
                    <div class="dimension-sub-bar" style="margin-top: 3rem;">
                        <h4>ส่วนที่ 1 เกณฑ์และคำอธิบาย</h4>
                    </div>

                    <div style="margin-top: 1.5rem;">
                        <div style="background-color: #dbeafe; color: var(--moc-blue-deep); padding: 0.75rem 1rem; border-radius: var(--radius-sm); font-weight: 700; margin-bottom: 1.5rem;">
                            1. ความถูกต้อง และสมบูรณ์ (Accuracy and Completeness)
                        </div>

                        <!-- Card 1.1 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                1.1 มีแหล่งข้อมูลที่น่าเชื่อถือ
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_1" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ใช้ข้อมูลจากแหล่งอ้างอิงที่ไม่น่าเชื่อถือ ขาดแหล่งอ้างอิงข้อมูล หรือเป็นความคิดเห็นจากบุคคลโดยขาดหลักฐานเชิงประจักษ์</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_1" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> ใช้ข้อมูลจากแหล่งข้อมูลที่ไม่น่าเชื่อถือแต่มีเนื้อหาที่รับรองโดยผู้เชี่ยวชาญเฉพาะด้านได้</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_1" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> ใช้ข้อมูลจากแหล่งข้อมูลที่น่าเชื่อถือหรือมีแหล่งข้อมูลที่น่าเชื่อถือ</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_1" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> ใช้ข้อมูลจากแหล่งข้อมูลที่น่าเชื่อถือและถูกต้องตามหลักวิชาการ</span>
                                </label>
                            </div>
                        </div>

                        <!-- Card 1.2 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                1.2 มีกระบวนการหรือเครื่องมือตรวจสอบจุดผิดพลาดของข้อมูล
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_2" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ขาดกระบวนการหรือเครื่องมือตรวจสอบความถูกต้องของข้อมูล</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_2" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> มีกระบวนการตรวจสอบจุดผิดพลาดที่ไร้รูปแบบ และอาศัยจากการคาดการณ์ อนุมาน โดยบุคคล</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_2" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> มีกระบวนการ เครื่องมือตรวจสอบความถูกต้องเป็นแบบแผน</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_2" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> มีกระบวนการ เครื่องมือตรวจสอบความถูกต้องเป็นแบบแผน และแจ้งเตือนอัตโนมัติ</span>
                                </label>
                            </div>
                        </div>

                        <!-- Card 1.3 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                1.3 มีการตรวจสอบความครบถ้วนของข้อมูล
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_3" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ขาดกระบวนการตรวจทานความครบถ้วนของข้อมูล</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_3" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> มีกระบวนการตรวจสอบความครบถ้วนโดยอาศัยการสังเกตด้วยบุคคล</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_3" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> มีกระบวนการตรวจสอบความครบถ้วน ด้วยเครื่องมืออัตโนมัติ</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_3" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> มีการรับรองว่าข้อมูล มีความครบถ้วนสมบูรณ์ตั้งแต่ขั้นตอนการเก็บรวบรวมจนถึงการจัดเก็บลงในระบบ</span>
                                </label>
                            </div>
                        </div>

                        <!-- Card 1.4 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                1.4 มีวิธีเก็บข้อมูลมีความเป็นกลาง น่าเชื่อถือ และไม่สร้างข้อมูลที่มีอคติ
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_4" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ขาดการกำหนดวิธีการเก็บข้อมูลด้วยกรอบมาตรฐานที่น่าเชื่อถือ หรือลดความอคติ</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_4" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> มีการกำหนดกลุ่มตัวอย่างการเก็บข้อมูลตามหลักของสถิติ หรือมีการใช้เครื่องมือพื้นฐาน</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_4" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> <strong>อย่างใดอย่างหนึ่ง</strong> มีการควบคุมการเก็บรวบรวมจากกลุ่มตัวอย่างที่กำหนดตามหลักสถิติ เช่น เพศ ความเชื่อ ความชอบ เป็นต้น หรือ มีเครื่องมือการเก็บที่เป็นมาตรฐาน แบบสอบถามที่ทดสอบความเชื่อมั่น เที่ยงตรงตามหลักวิชาการแล้ว</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_4" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> มีการควบคุมการเก็บรวบรวมจากกลุ่มตัวอย่างที่กำหนดตามหลักสถิติทุกประการ เช่น เพศ ความเชื่อ ความชอบ เป็นต้น และ มีเครื่องมือการเก็บที่เป็นมาตรฐาน แบบสอบถามที่ทดสอบความเชื่อมั่น เที่ยงตรงตามหลักวิชาการแล้ว</span>
                                </label>
                            </div>
                        </div>

                        <!-- Card 1.5 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                1.5 มีการระบุคำนิยามและลักษณะข้อมูลที่ต้องการ
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_5" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ขาดคำนิยามของข้อมูล ลักษณะของข้อมูลที่พึงประสงค์ และวิธีการเก็บข้อมูลที่ชัดเจน</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_5" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> มีคำนิยามของข้อมูล แต่ขาดความชัดเจน คลุมเครือ และไร้รูปแบบที่เป็นมาตรฐาน</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_5" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> มีคำนิยามของข้อมูลและมาตรฐานของข้อมูลที่ต้องการ ชัดเจน</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_1_5" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> มีคำนิยามของข้อมูลและมีมาตรฐานที่ชัดเจน รวมทั้งครอบคลุม กรณีผิดปกติ ให้ผู้เก็บข้อมูลสามารถเก็บข้อมูลได้ถูกต้อง</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: Consistency -->
                    <div class="dimension-sub-bar" style="margin-top: 3rem;">
                        <h4>2. ความสอดคล้องกัน (Consistency)</h4>
                    </div>

                    <div style="margin-top: 1.5rem;">
                        <!-- Card 2.1 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                2.1 มีการเก็บข้อมูลภายใต้มาตรฐานข้อมูลเดียวกัน หรือมาตรฐานข้อมูลที่สอดคล้องกันทำให้สามารถใช้ประโยชน์ข้อมูลร่วมกันได้
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_1" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> การเก็บข้อมูลในหน่วยงานมีมาตรฐานการเก็บข้อมูลแตกต่างกัน และใช้งานข้อมูลร่วมกันไม่ได้</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_1" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> การเก็บข้อมูลในหน่วยงานอยู่ในรูปแบบที่แตกต่างกัน แต่สามารถอ้างอิงจัดชุดข้อมูลและใช้ร่วมกันได้</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_1" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> การเก็บข้อมูลในหน่วยงานอยู่ในรูปแบบที่แตกต่างกัน แต่สามารถอ้างอิงและใช้ร่วมกันได้</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_1" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> การเก็บข้อมูลในหน่วยงานมีมาตรฐานการเก็บแบบเดียวกัน และใช้งานร่วมกันได้</span>
                                </label>
                            </div>
                        </div>

                        <!-- Card 2.2 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                2.2 มีการตรวจสอบรูปแบบข้อมูลภายในชุดข้อมูลเดียวกัน
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_2" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ขาดกระบวนการตรวจสอบรูปแบบ (Format) ข้อมูลในชุดข้อมูลเดียวกัน</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_2" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> มีกระบวนการตรวจสอบรูปแบบข้อมูลโดยอาศัยบุคคลหรือผู้ใช้งานข้อมูล</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_2" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> มีกระบวนการตรวจสอบรูปแบบข้อมูลด้วยระบบคอมพิวเตอร์ โดยมีอาศัยบุคคลเป็นผู้ตรวจสอบ</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_2" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> มีขั้นตอนหรือเครื่องมือที่แจ้งเตือนผู้ใช้ข้อมูลและผู้เก็บข้อมูลโดยอัตโนมัติเมื่อมีการเก็บข้อมูลผิดจากรูปแบบที่กำหนด</span>
                                </label>
                            </div>
                        </div>

                        <!-- Card 2.3 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                2.3 มีการตรวจสอบรูปแบบข้อมูลภายในชุดข้อมูลเดียวกัน
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_3" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ขาดกระบวนการตรวจสอบรูปแบบ (Format) ข้อมูลในชุดข้อมูลเดียวกัน</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_3" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> มีกระบวนการตรวจสอบรูปแบบข้อมูลโดยอาศัยบุคคลหรือผู้ใช้งานข้อมูล</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_3" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> มีกระบวนการตรวจสอบรูปแบบข้อมูลด้วยระบบคอมพิวเตอร์ โดยมีอาศัยบุคคลเป็นผู้ตรวจสอบ</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_3" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> มีขั้นตอนหรือเครื่องมือที่แจ้งเตือนผู้ใช้ข้อมูลและผู้เก็บข้อมูลโดยอัตโนมัติเมื่อมีการเก็บข้อมูลผิดจากรูปแบบที่กำหนด</span>
                                </label>
                            </div>
                        </div>

                        <!-- Card 2.4 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                2.4 ข้อมูลมีความเชื่อมโยงและไม่ขัดแย้งกัน
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_4" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> หน่วยงานภายใต้สังกัดต่างคนต่างเก็บรวบรวมข้อมูล ไม่สามารถใช้ข้อมูลร่วมกันได้</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_4" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> มีข้อตกลงร่วมกันภายในฝ่าย เพื่อกำหนดรูปแบบมาตรฐานข้อมูลให้สามารถทำงานร่วมกันได้</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_4" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> มีข้อตกลงร่วมกันในหน่วยงาน เรื่องรูปแบบมาตรฐานข้อมูล และกระบวนการที่จัดเก็บข้อมูล เป็นนโยบายให้เกิดความร่วมมือทั้งหน่วยงาน</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_4" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> มีข้อตกลงร่วมกันในหน่วยงาน เรื่องรูปแบบมาตรฐานข้อมูล และกระบวนการที่จัดเก็บข้อมูล รวมถึงกำหนดเป็นระเบียบบังคับใช้ทั้งหน่วยงาน</span>
                                </label>
                            </div>
                        </div>

                        <!-- Card 2.5 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                2.5 มีการใช้กฎ วิธีการตรวจวัดที่สอดคล้องกันทั้งหน่วยงาน รวมถึงหน่วยงานภายนอก
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_5" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> หน่วยงานภายใต้สังกัดต่างคนต่างเก็บข้อมูล ไม่สามารถใช้ข้อมูลร่วมกันได้</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_5" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> ข้อตกลงร่วมกันเฉพาะฝ่ายเพื่อกำหนดวิธีการเก็บข้อมูลร่วมกัน</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_5" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> มีข้อตกลงร่วมกันในหน่วยงาน เรื่องวิธีการเก็บข้อมูลเพื่อให้เป็นมาตรฐานเดียวกัน</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_5" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> มีข้อตกลงร่วมกันในหน่วยงาน เรื่องวิธีการเก็บข้อมูลเพื่อให้เป็นมาตรฐานเดียวกัน และมีการปรับปรุงมาตรฐานการเก็บข้อมูลตามวิสัยทัศน์และความต้องการข้อมูล</span>
                                </label>
                            </div>
                        </div>

                        <!-- Card 2.6 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                2.6 มีการกำหนดบทบาทและผู้รับผิดชอบข้อมูล
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_6" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ขาดการกำหนดบทบาทและขอบเขตของผู้ดูแลข้อมูลอย่างชัดเจน และยังไม่มีการมอบหมายให้หน่วยงานดูแลข้อมูลที่เกี่ยวข้อง</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_6" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> <strong>อย่างใดอย่างหนึ่ง</strong> 1. มีการกำหนดบทบาทและขอบเขตของผู้ดูแลข้อมูลอย่างชัดเจนแต่ไม่มีการมอบหมายหน่วยงานให้ปฏิบัติหน้าที่ หรือ 2. มีการมอบหมายให้หน่วยงานดูแล รักษา จัดเก็บข้อมูล แต่ไม่มีการกำหนดบทบาทและขอบเขตที่ชัดเจน</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_6" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> มีการมอบหมายบทบาทและขอบเขตของผู้รับผิดชอบเก็บข้อมูลและผู้ดูแลข้อมูลอย่างชัดเจน โดยครอบคลุมภารกิจของหน่วยงาน</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_2_6" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> มีการมอบหมายบทบาทและขอบเขตของผู้รับผิดชอบเก็บข้อมูลและผู้ดูแลข้อมูลอย่างชัดเจน ครอบคลุมภารกิจของหน่วยงาน และครอบคลุมถึงความต้องการข้อมูลของเหตุสุดวิสัยที่เกิดขึ้น</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: Relevancy -->
                    <div class="dimension-sub-bar" style="margin-top: 3rem;">
                        <h4>3. ตรงตามความต้องการของผู้ใช้ (Relevancy)</h4>
                    </div>

                    <div style="margin-top: 1.5rem;">
                        <!-- Card 3.1 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                3.1 ข้อมูลตรงตามความต้องการของผู้ใช้งานและตามวัตถุประสงค์ของการใช้งาน
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_3_1" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ข้อมูลได้รับการประเมินความพึงพอใจจากผู้ใช้งานข้อมูลอยู่ในระดับต่ำ</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_3_1" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> ข้อมูลได้รับการประเมินความพึงพอใจจากผู้ใช้งานข้อมูลในระดับปานกลาง</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_3_1" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> ข้อมูลได้รับการประเมินความพึงพอใจจากผู้ใช้งานข้อมูลในระดับดี</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_3_1" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> ข้อมูลได้รับการประเมินความพึงพอใจจากผู้ใช้งานข้อมูลในระดับดีมาก</span>
                                </label>
                            </div>
                        </div>

                        <!-- Card 3.2 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                3.2 มีผลประเมินความพึงพอใจของผู้ใช้ และมีการปรับปรุงคุณภาพให้ตรงตามความต้องการของผู้ใช้
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_3_2" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ไม่มีการประเมินความพึงพอใจของผู้ใช้งานข้อมูล</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_3_2" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> มีการประเมินความพึงพอใจ แต่ผู้ใช้งานข้อมูลยังไม่สามารถใช้งานได้ตามความต้องการ</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_3_2" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> มีการประเมินความพึงพอใจ และผู้ใช้งานสามารถใช้งานได้ตรงตามความต้องการ</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_3_2" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> มีการประเมินความพึงพอใจ และผู้ใช้งานสามารถใช้งานข้อมูลได้ตามความต้องการและมีการปรับปรุงคุณภาพข้อมูลตามผลการประเมินความพึงพอใจ</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4: Timeliness -->
                    <div class="dimension-sub-bar" style="margin-top: 3rem;">
                        <h4>4. ความเป็นปัจจุบัน (Timeliness)</h4>
                    </div>

                    <div style="margin-top: 1.5rem;">
                        <!-- Card 4.1 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                4.1 ข้อมูลมีการเผยแพร่ ส่งต่อตรงเวลา
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_4_1" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> มีการเก็บข้อมูลไม่มีการเผยแพร่ หรือส่งต่อไปยังแหล่งจัดเก็บข้อมูล หรือใช้เวลาส่งข้อมูลมากกว่า 14 วัน</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_4_1" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> มีการส่งต่อข้อมูลหลังจากจัดเก็บไปยังฐานข้อมูล หรือเผยแพร่ข้อมูลภายในเวลา 7-14 วัน หลังจากเก็บข้อมูล</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_4_1" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> มีการส่งต่อข้อมูลหลังจากจัดเก็บไปยังฐานข้อมูล หรือเผยแพร่ข้อมูลภายในเวลา 1-7 วัน หลังจากเก็บข้อมูล</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_4_1" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> มีการส่งต่อข้อมูลหลังจากจัดเก็บไปยังฐานข้อมูล หรือเผยแพร่ข้อมูลทันที (Real time streaming)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Card 4.2 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                4.2 ข้อมูลมีความเป็นปัจจุบัน
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_4_2" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ข้อมูลที่ใช้หรือเก็บรวบรวมมีอายุข้อมูลมากกว่า 15 ปี</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_4_2" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> ข้อมูลที่ใช้หรือเก็บรวบรวมมีอายุข้อมูล 5-15 ปี</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_4_2" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> ข้อมูลที่ใช้หรือเก็บรวบรวมมีอายุข้อมูล 1-5 ปี</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_4_2" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> ข้อมูลที่ใช้หรือเก็บรวบรวมต้องเป็นปัจจุบันในวันนั้น หรือมีอายุข้อมูลไม่เกิน 1 ปี</span>
                                </label>
                            </div>
                        </div>

                        <!-- Card 4.3 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                4.3 ข้อมูลมีการเผยแพร่ข้อมูลในเวลาที่เหมาะสม
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_4_3" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ข้อมูลมีการเผยแพร่หลังจากเกิดเหตุการณ์เกินกว่า 2 สัปดาห์ หรือล่าช้ากว่าปฏิทินการเผยแพร่ข้อมูลมากกว่า 1 เดือน</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_4_3" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> ข้อมูลมีการเผยแพร่หลังจากเกิดเหตุการณ์อย่างน้อยภายใน 7-14 วัน หรือล่าช้ากว่าปฏิทินการเผยแพร่ข้อมูลภายในเวลา 1 เดือน</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_4_3" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> ข้อมูลมีการเผยแพร่หลังจากเกิดเหตุการณ์อย่างน้อยภายใน 3-7 วัน หรือล่าช้ากว่าปฏิทินการเผยแพร่ข้อมูลภายในเวลา 1 สัปดาห์</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_4_3" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> ข้อมูลมีการเผยแพร่หลังจากเกิดเหตุการณ์อย่างน้อยภายใน 1-3 วัน หรือตรงตามปฏิทินการเผยแพร่ข้อมูล</span>
                                </label>
                            </div>
                        </div>

                        <!-- Card 4.4 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                4.4 มีการจัดทำปฏิทินเผยแพร่ข้อมูล
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_4_4" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ขาดกระบวนการวางแผนดำเนินงานและปฏิบัติในการเผยแพร่ข้อมูลไม่สอดคล้องกับขั้นตอนการทำงาน</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_4_4" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> มีการกำหนดปฏิทินการเผยแพร่ข้อมูลโดยใช้กรอบเวลาดำเนินการแบบประมาณการ</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_4_4" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> มีกระบวนการกำหนดแผนดำเนินการเก็บข้อมูล ประมวลผลและวางกำหนดเวลาเพื่อเผยแพร่ข้อมูลได้อย่างเหมาะสม</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_4_4" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> มีการกำหนดแผนดำเนินการเก็บข้อมูล ประมวลผลและวางกำหนดเวลาเพื่อเผยแพร่ข้อมูลได้อย่างเหมาะสมกับสถานการณ์และทรัพยากรที่มี</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 5: Availability -->
                    <div class="dimension-sub-bar" style="margin-top: 3rem;">
                        <h4>5. ความพร้อมใช้ (Availability)</h4>
                    </div>

                    <div style="margin-top: 1.5rem;">
                        <!-- Card 5.1 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                5.1 ข้อมูลถูกจัดในรูปแบบที่พร้อมนำไปใช้งาน และเหมาะสมกับผู้ใช้งาน
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_1" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ข้อมูลอยู่ในรูปแบบที่ไม่พร้อมใช้งานหรือประมวลผลต่อด้วยโปรแกรมคอมพิวเตอร์</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_1" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> ข้อมูลอยู่ในรูปแบบที่พร้อมอ่านค่าได้ด้วยคอมพิวเตอร์แต่ไม่พร้อมนำไปประมวลผล จะต้องจัดรูปแบบให้เหมาะสมกับโปรแกรมประมวลผลและวัตถุประสงค์การใช้งาน</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_1" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> ข้อมูลอยู่ในรูปแบบ (Format) ที่พร้อมนำเข้าโปรแกรมประมวลผล แต่ผู้ใช้ข้อมูลต้องจัดรูปแบบข้อมูลให้ตรงกับวัตถุประสงค์</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_1" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> ข้อมูลอยู่ในรูปแบบ (Format) ที่พร้อมใช้งานหรือนำไปประมวลผลด้วยโปรแกรมคอมพิวเตอร์ได้ทันที</span>
                                </label>
                            </div>
                        </div>

                        <!-- Card 5.2 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                5.2 มีการเผยแพร่ข้อมูลที่เหมาะสมและสามารถเข้าถึงได้ โดยผู้ใช้สามารถเข้าถึงข้อมูลได้สะดวกตามสิทธิที่เหมาะสม
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_2" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ผู้ใช้งานข้อมูลต้องทำเรื่องขอใช้ข้อมูลเปิด หรือ ขาดการเผยแพร่ข้อมูล</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_2" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> ช่องทางการเผยแพร่ขาดโครงสร้างการจัดเก็บข้อมูลและขาดระบบสารบัญเพื่อเข้าถึงข้อมูล</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_2" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> มีช่องทางการเผยแพร่ข้อมูลที่เหมาะสมกับชนิด ประเภท ขนาด และลำดับชั้นความลับ แต่ช่องทางการเก็บเป็นอุปสรรคในการเข้าถึงข้อมูล</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_2" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> มีช่องทางการเผยแพร่ข้อมูลที่เหมาะสมกับชนิด ประเภท ขนาด ลำดับชั้นความลับ รวมถึงสิทธิ์การเข้าถึงข้อมูลที่เหมาะสม</span>
                                </label>
                            </div>
                        </div>

                        <!-- Card 5.3 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                5.3 ข้อมูลสามารถอ่านด้วยโปรแกรมคอมพิวเตอร์ได้
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_3" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ข้อมูลที่จัดเก็บในรูปแบบที่คอมพิวเตอร์ไม่สามารถประมวลผลหรืออ่านค่าได้</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_3" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> ข้อมูลที่จัดเก็บไม่สามารถประมวลผลได้ด้วยโปรแกรมคอมพิวเตอร์ หรือให้ผู้นำไปประมวลผลต่อได้ เช่น PDF JPEG PNG เป็นต้น</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_3" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> ข้อมูลที่จัดเก็บสามารถประมวลผลได้ด้วยคอมพิวเตอร์ แต่อยู่ในรูปแบบที่ไม่พร้อมใช้งาน เช่น Text Docx CSV Xlsx เป็นต้น</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_3" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> ข้อมูลที่จัดเก็บสามารถประมวลผลได้ด้วยคอมพิวเตอร์ และพร้อมนำไปใช้งานได้อย่างครอบคลุมวัตถุประสงค์</span>
                                </label>
                            </div>
                        </div>

                        <!-- Card 5.4 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                5.4 มีคำอธิบายข้อมูลที่ชัดเจน
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_4" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ไม่มีคำอธิบายข้อมูลประกอบชุดข้อมูล นิยาม และหน่วยวัดที่ชัดเจน</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_4" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> มีคำนิยามข้อมูลและหน่วยวัดของข้อมูล แต่ขาดคำอธิบาย (Metadata) ประกอบชุดข้อมูล</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_4" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> มีกระบวนการ ใส่ข้อมูลคำอธิบายข้อมูล (Metadata) ได้อย่างน้อย 50% ของข้อมูล ประเภท ระเบียบ</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_4" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> มีคำอธิบายข้อมูล (Metadata) ครบถ้วนและกรอกครบถ้วนสมบูรณ์ทั้งหมดตามเกณฑ์ที่กำหนด</span>
                                </label>
                            </div>
                        </div>

                        <!-- Card 5.5 -->
                        <div class="self-assess-card">
                            <div class="self-assess-header">
                                5.5 มีคำอธิบายขั้นตอนการขอข้อมูลที่ไม่เผยแพร่
                            </div>
                            <div class="self-assess-options">
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_5" value="1">
                                    <span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ไม่มีคำอธิบาย หรือเอกสารอธิบายขั้นตอนการขอข้อมูลที่ไม่เผยแพร่</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_5" value="2">
                                    <span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> ต้องประสานงานขอขั้นตอนการขอข้อมูลจากเจ้าหน้าที่ประจำสำนักงาน หรือมีเอกสารเผยแพร่ขั้นตอน ยากต่อการเข้าถึง</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_5" value="3">
                                    <span class="self-assess-option-text"><strong>3 ดี :</strong> มีคำอธิบายขั้นตอนการขอรับข้อมูลเป็นเอกสาร หรือประกาศในช่องทางการเผยแพร่ข้อมูล</span>
                                </label>
                                <label class="self-assess-option">
                                    <input type="radio" name="sa_5_5" value="4">
                                    <span class="self-assess-option-text"><strong>4 ดีมาก :</strong> มีคำอธิบายขั้นตอนการขอข้อมูลที่ไม่เผยแพร่ในช่องทางที่เผยแพร่ที่ชัดเจน หรือมีมาตรการส่งมอบข้อมูลแก่ผู้ใช้ข้อมูลเพื่อรักษาความลับ</span>
                                </label>
                            </div>
                        </div>
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

                    <div class="form-navigation no-print" style="margin-top: 2rem;">
                        <button type="button" class="btn btn-secondary btn-prev-step"><i data-lucide="chevron-left"></i> ย้อนกลับ</button>
                        <button type="button" class="btn btn-primary btn-next-step">หน้าถัดไป (แบบตรวจการควบคุมฯ) <i data-lucide="chevron-right"></i></button>
                    </div>
                </div>


                <!-- ================= STEP 4: page5.php ================= -->
                <div id="step-4" class="form-step">
                    <div class="paper-header">
                        <div class="official-title-bar">
                            <h2>แบบตรวจประเมินการควบคุมและติดตามคุณภาพข้อมูล<br>(Data Quality Monitoring and Control Checklist)</h2>
                        </div>
                        
                        <!-- Instruction Box -->
                        <div class="instruction-box" style="margin-top: 2rem; border-left: 4px solid var(--moc-gold); line-height: 1.45; font-size: 0.95rem; text-align: left; background-color: var(--moc-gold-light); color: var(--text-dark);">
                            <strong>คำชี้แจง:</strong> 
                            <ol style="margin-top: 0.5rem; padding-left: 1.25rem; display: flex; flex-direction: column; gap: 0.5rem;">
                                <li>ระหว่างการประเมินคุณภาพข้อมูลประจำปี ทีมผู้ประเมินคุณภาพข้อมูลจะทำการตรวจสอบและวิเคราะห์หลักฐานที่เกี่ยวข้อง เพื่อจัดการประสิทธิภาพในการดำเนินงาน โดยเฉพาะการจัดการด้านการติดตามและตรวจสอบประสิทธิภาพ รวมทั้งการจัดการเพื่อรับรองคุณภาพข้อมูลให้เป็นไปตามที่กำหนด</li>
                                <li>หัวหน้าแต่ละ กอง/สำนัก/ฝ่าย/ศูนย์ ควรจัดทำรายการตรวจสอบคุณภาพข้อมูลในขอบเขตงานที่รับผิดชอบให้แล้วเสร็จ โดยสามารถตรวจสอบตามแบบตรวจประเมินคุณภาพข้อมูลตาม Template 3.</li>
                                <li>แบบตรวจประเมินนี้จะเป็นการรายงานผลสรุปของทีมผู้ประเมินคุณภาพโดยตรงสำหรับการจัดการเพื่อประกันความคุ้มค่าของการจัดสรรงบประมาณและเพื่อการตัดสินใจใช้ทรัพยากรข้อมูล มีวัตถุประสงค์เพื่อสนับสนุนการจัดเตรียมข้อมูลหลักฐานในขอบเขตของการจัดการคุณภาพข้อมูลสำหรับแจ้งให้รับทราบและใช้งานกันทั่วทั้งหน่วยงาน</li>
                                <li>แบบตรวจประเมินนี้จัดทำสำหรับข้อมูลกระบวนการจัดการคุณภาพ/กลยุทธ์ด้านคุณภาพข้อมูลขององค์กรเพื่อกำหนดเป็นมาตรฐาน โดยประเมินระดับความสำเร็จเปรียบเทียบแต่ละรายการตรวจประเมินคุณภาพข้อมูล โดยให้เลือกคำตอบ 1 ใน 3 ตัวเลือก ได้แก่ "มีอย่างเหมาะสม" "มีบางส่วน" และ "ไม่มี" ซึ่งแต่ละตัวเลือกจะเชื่อมโยงกับระดับความเสี่ยง ได้แก่ "ความเสี่ยงต่ำ" "ความเสี่ยงปานกลาง" และ "ความเสี่ยงสูง" ซึ่งแบบตรวจประเมินนี้จะให้ความสำคัญกับกระบวนการที่มีความเสี่ยงปานกลาง หรือความเสี่ยงสูงที่ต้องได้รับการจัดการ/ลดความเสี่ยง</li>
                                <li>การตรวจประเมินนี้ควรมีหลักฐานอ้างอิงในแนวคอลัมน์สุดท้าย เพื่อสนับสนุนการเลือกตัวเลือกนั้น ๆ โดยเฉพาะในส่วนพบว่ามีความเสี่ยงปานกลาง หรือ ความเสี่ยงสูง (มีบางส่วน หรือ ไม่มี) พร้อมทั้งระบุรายละเอียดแผนปฏิบัติงาน (Action Plan) เพื่อลดความเสี่ยงดังกล่าว รวมถึงกำหนดระยะเวลาเป้าหมายที่เหมาะสมเพื่อให้สามารถบรรลุเป้าหมายสูงสุดคือ "ความเสี่ยงต่ำ"</li>
                                <li>ภายหลังจากบูรณาการกระบวนการทำงาน/บริการที่ข้อมูลไม่มีคุณภาพข้อมูล (หรือ มีความเสี่ยงสูง) และมีคุณภาพข้อมูลบ้างส่วน (หรือ มีความเสี่ยงปานกลาง) ทีมผู้ประเมินคุณภาพข้อมูลและ/หรือคณะประเมินด้านประสิทธิภาพขององค์กรจะดำเนินการร่วมกับ กอง/สำนัก/ฝ่าย/ศูนย์ ที่ได้ระบุแผนปฏิบัติงาน (Action Plan) เพื่อลดความเสี่ยง โดยการดำเนินงานตามแผนปฏิบัติจะมีการตรวจสอบจากทีมผู้ประเมินคุณภาพข้อมูล/คณะกรรมการตรวจสอบและรับรองอย่างน้อยทุก 6 เดือน</li>
                            </ol>
                        </div>
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

                        <div class="form-group">
                            <label for="info-service" class="text-bold">บริการ : <span style="color:#ef4444;">*</span></label>
                            <textarea id="info-service" name="info_service" rows="2" placeholder="ข้อมูลสถิติการค้า"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="info-head" class="text-bold">หัวหน้า กอง/สำนัก/ฝ่าย/ศูนย์ และ/หรือ บริการ : <span style="color:#ef4444;">*</span></label>
                            <textarea id="info-head" name="info_head" rows="2" placeholder="ผู้อำนวยการศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="control-date" class="text-bold">วันที่ประเมินผลควบคุม : <span style="color:#ef4444;">*</span></label>
                            <input type="date" id="control-date" name="control_date" style="width: 100%; padding: 0.6rem 0.8rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color); background-color: #ffffff;">
                        </div>
                    </fieldset>

                    <!-- SECTION 2: ด้านการปรับปรุงการจัดทำธรรมาภิบาลและการจัดการคุณภาพข้อมูล -->
                    <fieldset class="form-section">
                        <legend><i data-lucide="shield-check"></i> ด้านการปรับปรุงการจัดทำธรรมาภิบาลและการจัดการคุณภาพข้อมูล และบทบาทความรับผิดชอบด้านคุณภาพข้อมูล</legend>

                        <div class="table-responsive">
                            <table class="assessment-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px; text-align: center;">รหัส</th>
                                        <th style="width: 45%;">มาตรฐานคุณภาพข้อมูล<br>(Data Quality Standards)</th>
                                        <th style="width: 100px; text-align: center;">มีอย่าง<br>เหมาะสม<br><small style="font-weight:400; color:#10b981;">(ความเสี่ยงต่ำ)</small></th>
                                        <th style="width: 100px; text-align: center;">มีบางส่วน<br><small style="font-weight:400; color:#f59e0b;">(ความเสี่ยงปานกลาง)</small></th>
                                        <th style="width: 100px; text-align: center;">ไม่มี<br><small style="font-weight:400; color:#ef4444;">(ความเสี่ยงสูง)</small></th>
                                        <th style="min-width: 250px;">หลักฐาน / ความเห็น</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- G1 -->
                                    <tr>
                                        <td class="text-center font-bold">G1</td>
                                        <td class="text-justify font-medium">เจ้าหน้าที่ระดับอาวุโสมีความรับผิดชอบเชิงกลยุทธ์ในภาพรวมสำหรับกำกับดูแลคุณภาพข้อมูล โดยไม่มีการมอบหมายผู้รับผิดชอบแทน หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g1" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g1" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g1" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="g1_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- G2 -->
                                    <tr>
                                        <td class="text-center font-bold">G2</td>
                                        <td class="text-justify font-medium">มีการสื่อสารข้อกำหนดในการควบคุมคุณภาพข้อมูลให้ผู้มีส่วนเกี่ยวข้องตลอดกระบวนการทำงาน/บริการอย่างชัดเจน และมีการเน้นย้ำว่าเป็นความรับผิดชอบของบุคลากรทุกคนในองค์กรในการควบคุมคุณภาพของข้อมูล หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g2" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g2" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g2" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="g2_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- G3 -->
                                    <tr>
                                        <td class="text-center font-bold">G3</td>
                                        <td class="text-justify font-medium">มีการกำหนดความรับผิดชอบสำหรับคุณภาพข้อมูลในกระบวนการทำงาน/บริการที่มีขอบเขตเฉพาะเจาะจงอย่างชัดเจนและเป็นทางการ และเป็นส่วนหนึ่งของระบบการประเมินสำหรับผู้ที่ถูกกำหนดให้มีบทบาทและรับผิดชอบในการควบคุมคุณภาพข้อมูลนั้น หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g3" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g3" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g3" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="g3_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- G4 -->
                                    <tr>
                                        <td class="text-center font-bold">G4</td>
                                        <td class="text-justify font-medium">มีกรอบการติดตามและตรวจสอบคุณภาพข้อมูลที่เหมาะสม โดยมีการตรวจสอบอย่างละเอียดเข้มงวดด้วยผู้มีหน้าที่กำกับดูแลข้อมูล และโปรแกรมที่ใช้ในการตรวจสอบต้องมีความเสี่ยงที่เหมาะสม</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g4" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g4" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g4" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="g4_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- G5 -->
                                    <tr>
                                        <td class="text-center font-bold">G5</td>
                                        <td class="text-justify font-medium">คุณภาพข้อมูลได้ถูกรวมไว้ในการจัดการความเสี่ยง ซึ่งมีการประเมินความเสี่ยงที่เกี่ยวข้องกับความไม่น่าเชื่อถือ หรือความไม่ถูกต้องของข้อมูลอยู่เป็นประจำ หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g5" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g5" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g5" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="g5_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- G6 -->
                                    <tr>
                                        <td class="text-center font-bold">G6</td>
                                        <td class="text-justify font-medium">มีการแก้ไขปัญหาในการบริการ อันเนื่องมาจากการตรวจสอบคุณภาพข้อมูลทั้งภายในและภายนอกหน่วยงานก่อนหน้า หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g6" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g6" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g6" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="g6_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- G7 -->
                                    <tr>
                                        <td class="text-center font-bold">G7</td>
                                        <td class="text-justify font-medium">กรณีที่มีการทำงานร่วมกัน มีการทำข้อตกลงร่วมกันที่ครอบคลุมถึงคุณภาพข้อมูลกับหน่วยงานภาคีการทำงาน หรือไม่ (ตัวอย่างเช่น ในรูปแบบ/ฟอร์มของหลักเกณฑ์การแบ่งปันข้อมูล คำชี้แจง หรือข้อตกลงระดับการบริการ เป็นต้น)</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g7" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g7" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="g7" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="g7_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>

                    <!-- SECTION 3: ด้านการพัฒนานโยบายและแนวปฏิบัติด้านข้อมูล -->
                    <fieldset class="form-section">
                        <legend><i data-lucide="book-open"></i> ด้านการพัฒนานโยบายและแนวปฏิบ้านข้อมูล</legend>

                        <div class="table-responsive">
                            <table class="assessment-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px; text-align: center;">รหัส</th>
                                        <th style="width: 45%;">มาตรฐานคุณภาพข้อมูล<br>(Data Quality Standards)</th>
                                        <th style="width: 100px; text-align: center;">มีอย่าง<br>เหมาะสม<br><small style="font-weight:400; color:#10b981;">(ความเสี่ยงต่ำ)</small></th>
                                        <th style="width: 100px; text-align: center;">มีบางส่วน<br><small style="font-weight:400; color:#f59e0b;">(ความเสี่ยงปานกลาง)</small></th>
                                        <th style="width: 100px; text-align: center;">ไม่มี<br><small style="font-weight:400; color:#ef4444;">(ความเสี่ยงสูง)</small></th>
                                        <th style="min-width: 250px;">หลักฐาน / ความเห็น</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- P1 -->
                                    <tr>
                                        <td class="text-center font-bold">P1</td>
                                        <td class="text-justify font-medium">มีนโยบายและแนวปฏิบัติด้านข้อมูลที่เกี่ยวข้องกับการรวบรวมข้อมูล การบันทึก การวิเคราะห์ และการรายงานข้อมูล ตลอดวงจรชีวิตของข้อมูล โดยครอบคลุมทุกขอบเขตภารกิจ/กระบวนการทำงาน ที่ครบถ้วนและเป็นปัจจุบัน หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p1" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p1" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p1" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="p1_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- P2 -->
                                    <tr>
                                        <td class="text-center font-bold">P2</td>
                                        <td class="text-justify font-medium">นโยบายและแนวปฏิบัติด้านข้อมูลช่วยสนับสนุนกระบวนการปฏิบัติงานในปัจจุบัน และเป็นแนวทางดำเนินงานสำหรับบุคลากรของหน่วยงาน หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p2" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p2" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p2" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="p2_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- P3 -->
                                    <tr>
                                        <td class="text-center font-bold">P3</td>
                                        <td class="text-justify font-medium">นโยบายและแนวปฏิบัติที่หน่วยงานกำหนดสอดคล้องและเป็นไปตามมาตรฐานและหลักเกณฑ์ที่เกี่ยวข้อง/ที่มีอยู่ในระดับประเทศ ตลอดจนแนวทางการปฏิบัติในระดับหน่วยงาน/ระดับพื้นที่ หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p3" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p3" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p3" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="p3_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- P4 -->
                                    <tr>
                                        <td class="text-center font-bold">P4</td>
                                        <td class="text-justify font-medium">มีการทบทวนนโยบายและแนวปฏิบัติด้านข้อมูลทุกปีตามและปรับปรุงให้เป็นปัจจุบันตามความจำเป็น หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p4" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p4" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p4" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="p4_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- P5 -->
                                    <tr>
                                        <td class="text-center font-bold">P5</td>
                                        <td class="text-justify font-medium">บุคลากรทุกคนสามารถเข้าถึงนโยบาย แนวปฏิบัติ และคำแนะนำด้านคุณภาพข้อมูล โดยมีระบบสารสนเทศที่สามารถรองรับและสนับสนุนการเข้าถึงข้อมูลดังกล่าวได้ หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p5" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p5" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p5" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="p5_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- P6 -->
                                    <tr>
                                        <td class="text-center font-bold">P6</td>
                                        <td class="text-justify font-medium">มีการนำนโยบาย แนวปฏิบัติ และคำแนะนำด้านคุณภาพข้อมูลไปปฏิบัติอย่างสม่ำเสมอและทั่วถึง รวมถึงมีกลไกติดตามการปฏิบัติตามนโยบายและแนวปฏิบัติ รวมถึงการรายงานผลการดำเนินการอย่างเป็นทางการต่อผู้บริหารระดับสูง หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p6" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p6" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p6" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="p6_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- P7 -->
                                    <tr>
                                        <td class="text-center font-bold">P7</td>
                                        <td class="text-justify font-medium">กรณีที่เกิดความผิดพลาดในการปฏิบัติตามนโยบายและแนวปฏิบัติของหน่วยงาน และมาตรฐานในระดับประเทศ หรือไม่มีประสิทธิภาพการดำเนินงานตามเป้าหมายด้านคุณภาพข้อมูล มีการตรวจสอบและดำเนินการแก้ไขปัญหาที่เกิดขึ้น หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p7" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p7" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="p7" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="p7_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>

                    <!-- SECTION 4: ด้านการปรับปรุงระบบและกระบวนการเพื่อสร้างความเชื่อมั่นของคุณภาพข้อมูล -->
                    <fieldset class="form-section">
                        <legend><i data-lucide="cpu"></i> ด้านการปรับปรุงระบบและกระบวนการเพื่อสร้างความเชื่อมั่นของคุณภาพข้อมูล</legend>

                        <div class="table-responsive">
                            <table class="assessment-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px; text-align: center;">รหัส</th>
                                        <th style="width: 45%;">มาตรฐานคุณภาพข้อมูล<br>(Data Quality Standards)</th>
                                        <th style="width: 100px; text-align: center;">มีอย่าง<br>เหมาะสม<br><small style="font-weight:400; color:#10b981;">(ความเสี่ยงต่ำ)</small></th>
                                        <th style="width: 100px; text-align: center;">มีบางส่วน<br><small style="font-weight:400; color:#f59e0b;">(ความเสี่ยงปานกลาง)</small></th>
                                        <th style="width: 100px; text-align: center;">ไม่มี<br><small style="font-weight:400; color:#ef4444;">(ความเสี่ยงสูง)</small></th>
                                        <th style="min-width: 250px;">หลักฐาน / ความเห็น</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- S1 -->
                                    <tr>
                                        <td class="text-center font-bold">S1</td>
                                        <td class="text-justify font-medium">มีระบบและกระบวนการที่เหมาะสมในการเก็บรวบรวม การบันทึก การวิเคราะห์ และการรายงานข้อมูล ซึ่งเน้นรักษาความปลอดภัยของข้อมูลให้มีความถูกต้องแม่นยำและสมบูรณ์ ความสอดคล้องกัน มีความเป็นปัจจุบัน/ทันต่อการใช้งาน ตรงตามความต้องการของผู้ใช้ และมีความพร้อมใช้ หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s1" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s1" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s1" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="s1_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- S2 -->
                                    <tr>
                                        <td class="text-center font-bold">S2</td>
                                        <td class="text-justify font-medium">มีระบบและกระบวนการทำงานเป็นไปตามหลักการที่ถูกต้องตั้งแต่วินาทีแรกเริ่ม แทนที่จะใช้กระบวนการแก้ไข การทำข้อมูลให้มีความสมบูรณ์ (data cleansing) หรือ การจัดการข้อมูลอย่างครอบคลุมเพื่อสร้างข้อมูลที่จำเป็น</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s2" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s2" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s2" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="s2_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- S3 -->
                                    <tr>
                                        <td class="text-center font-bold">S3</td>
                                        <td class="text-justify font-medium">การเตรียมการในการจัดเก็บ การบันทึก การรวบรวมและการรายงานข้อมูล ได้รวมไว้ในการวางแผนดำเนินการและกระบวนการจัดการเชิงโครงสร้างเพื่อสนับสนุนการทำงานของบุคลากรในแต่ละวัน</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s3" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s3" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s3" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="s3_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- S4 -->
                                    <tr>
                                        <td class="text-center font-bold">S4</td>
                                        <td class="text-justify font-medium">ระบบสารสนเทศมีการควบคุมภายในเพื่อลดความผิดพลาดที่เกิดจากบุคคล หรือจากการจัดการและป้องกันความผิดพลาดที่เกิดจากการป้อนข้อมูล ข้อมูลสูญหาย หรือการเปลี่ยนแปลงข้อมูลที่ไม่ได้รับอนุญาต โดยการควบคุมดังกล่าวได้รับการตรวจสอบอย่างน้อยปีละครั้งเพื่อมั่นใจว่าการทำงานมีประสิทธิภาพ หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s4" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s4" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s4" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="s4_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- S5 -->
                                    <tr>
                                        <td class="text-center font-bold">S5</td>
                                        <td class="text-justify font-medium">มีการสนับสนุนสำหรับบุคลากรในทุกด้านทั้งการเก็บรวบรวมข้อมูล การบันทึก การวิเคราะห์ และการรายงานข้อมูล หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s5" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s5" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s5" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="s5_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- S6 -->
                                    <tr>
                                        <td class="text-center font-bold">S6</td>
                                        <td class="text-justify font-medium">ข้อมูลต้องได้รับการตรวจสอบและทบทวนการจัดการจาก กอง/สำนัก/ฝ่าย/ศูนย์ ก่อนนำไปรายงานต่อผู้บริหารระดับสูง</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s6" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s6" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s6" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="s6_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- S7 -->
                                    <tr>
                                        <td class="text-center font-bold">S7</td>
                                        <td class="text-justify font-medium">มีข้อกำหนดด้านคุณภาพอย่างเป็นทางการซึ่งถูกนำไปใช้สำหรับผู้ใช้บริการด้านข้อมูลบุคคลที่สาม (3rd party data) ทั้งหมด (ตัวอย่างเช่น ในรูปแบบ/ฟอร์มของหลักเกณฑ์การแบ่งปันข้อมูล คำชี้แจง หรือข้อตกลงระดับการบริการ เป็นต้น) หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s7" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s7" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s7" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="s7_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- S8 -->
                                    <tr>
                                        <td class="text-center font-bold">S8</td>
                                        <td class="text-justify font-medium">มีการเตรียมการด้านการรักษาความปลอดภัยของระบบสารสนเทศทั้งหมดอย่างเหมาะสม และมีการกำกับติดตามเป็นประจำ หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s8" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s8" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s8" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="s8_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- S9 -->
                                    <tr>
                                        <td class="text-center font-bold">S9</td>
                                        <td class="text-justify font-medium">มีการวางแผนความต่อเนื่องทางธุรกิจ (business continuity plan) เพื่อให้ความคุ้มครอง/ป้องกันสำหรับการบันทึกและข้อมูลที่มีความสำคัญต่อการทำงานอย่างต่อเนื่องในการบริการของหน่วยงาน หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s9" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s9" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="s9" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="s9_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>

                    <!-- SECTION 5: ด้านการพัฒนาความรู้ ทักษะ และความสามารถของบุคลากร -->
                    <fieldset class="form-section">
                        <legend><i data-lucide="graduation-cap"></i> ด้านการพัฒนาความรู้ ทักษะ และความสามารถของบุคลากรเพื่อรักษาคุณภาพข้อมูลให้ดียิ่งขึ้น</legend>

                        <div class="table-responsive">
                            <table class="assessment-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px; text-align: center;">รหัส</th>
                                        <th style="width: 45%;">มาตรฐานคุณภาพข้อมูล<br>(Data Quality Standards)</th>
                                        <th style="width: 100px; text-align: center;">มีอย่าง<br>เหมาะสม<br><small style="font-weight:400; color:#10b981;">(ความเสี่ยงต่ำ)</small></th>
                                        <th style="width: 100px; text-align: center;">มีบางส่วน<br><small style="font-weight:400; color:#f59e0b;">(ความเสี่ยงปานกลาง)</small></th>
                                        <th style="width: 100px; text-align: center;">ไม่มี<br><small style="font-weight:400; color:#ef4444;">(ความเสี่ยงสูง)</small></th>
                                        <th style="min-width: 250px;">หลักฐาน / ความเห็น</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- E1 -->
                                    <tr>
                                        <td class="text-center font-bold">E1</td>
                                        <td class="text-justify font-medium">มีการกำหนดบทบาทหน้าที่และความรับผิดชอบที่เกี่ยวข้องกับการควบคุมคุณภาพข้อมูลและจัดทำเป็นเอกสารอย่างชัดเจน และถูกรวมเข้ากับภารกิจงานที่ต้องทำได้อย่างเหมาะสม หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="e1" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="e1" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="e1" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="e1_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- E2 -->
                                    <tr>
                                        <td class="text-center font-bold">E2</td>
                                        <td class="text-justify font-medium">มีการกำหนดมาตรฐานคุณภาพข้อมูล และบุคลากรได้รับการประเมินตามมาตรฐานที่กำหนด หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="e2" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="e2" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="e2" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="e2_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- E3 -->
                                    <tr>
                                        <td class="text-center font-bold">E3</td>
                                        <td class="text-justify font-medium">มีการอบรมและคัดเลือกบุคลากรที่ให้บริการด้วยทักษะที่จำเป็นเพื่อสนับสนุนกิจกรรมในแต่ละวันที่เกี่ยวข้องกับการเก็บรวบรวม การบันทึก การวิเคราะห์ และรายงานข้อมูลที่ถูกต้องแม่นยำและสมบูรณ์ ความสอดคล้องกัน มีความเป็นปัจจุบัน/ทันต่อการใช้งาน ตรงตามความต้องการของผู้ใช้ และมีความพร้อมใช้ หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="e3" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="e3" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="e3" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="e3_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- E4 -->
                                    <tr>
                                        <td class="text-center font-bold">E4</td>
                                        <td class="text-justify font-medium">มีโปรแกรมการฝึกอบรมอย่างต่อเนื่องและเป็นทางการ ในประเด็นและความต้องการด้านคุณภาพข้อมูล โดยออกแบบได้เหมาะสมกับความต้องการที่หลากหลายของบุคลากรที่เกี่ยวข้องทั้งหมด หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="e4" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="e4" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="e4" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="e4_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>

                    <!-- SECTION 6: ด้านการปรับปรุงการควบคุมด้านการรายงานผล และการใช้ข้อมูล -->
                    <fieldset class="form-section">
                        <legend><i data-lucide="bar-chart-3"></i> ด้านการปรับปรุงการควบคุมด้านการรายงานผล และการใช้ข้อมูล</legend>

                        <div class="table-responsive">
                            <table class="assessment-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px; text-align: center;">รหัส</th>
                                        <th style="width: 45%;">มาตรฐานคุณภาพข้อมูล<br>(Data Quality Standards)</th>
                                        <th style="width: 100px; text-align: center;">มีอย่าง<br>เหมาะสม<br><small style="font-weight:400; color:#10b981;">(ความเสี่ยงต่ำ)</small></th>
                                        <th style="width: 100px; text-align: center;">มีบางส่วน<br><small style="font-weight:400; color:#f59e0b;">(ความเสี่ยงปานกลาง)</small></th>
                                        <th style="width: 100px; text-align: center;">ไม่มี<br><small style="font-weight:400; color:#ef4444;">(ความเสี่ยงสูง)</small></th>
                                        <th style="min-width: 250px;">หลักฐาน / ความเห็น</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- D1 -->
                                    <tr>
                                        <td class="text-center font-bold">D1</td>
                                        <td class="text-justify font-medium">ข้อมูลที่ใช้สำหรับการรายงานผล ได้รับการกำกับดูแลและถูกใช้ในการบริหารจัดการของหน่วยงาน โดยอย่างน้อยที่สุดข้อมูลที่รายงานและวิธีการใช้ถูกป้อนกลับไปยังผู้สร้างข้อมูลดังกล่าว เพื่อเสริมสร้างความเข้าใจในบทบาทหน้าที่และความสำคัญข้อมูลให้กว้างขวางขึ้น หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="d1" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="d1" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="d1" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="d1_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- D2 -->
                                    <tr>
                                        <td class="text-center font-bold">D2</td>
                                        <td class="text-justify font-medium">มีการควบคุมเพื่อสนับสนุนความถูกต้องแม่นยำในการรายงานข้อมูล (ยกตัวอย่างเช่น การตรวจสอบความถูกต้อง ความสอดคล้องกัน และความถูกต้องแม่นยำของข้อมูลหลัก) ในกรณีที่มีการถ่ายโอนรายงานข้อมูลที่จำเป็นจากระบบปฏิบัติการเพื่อวิเคราะห์เพิ่มเติม มีการดำเนินการตรวจสอบย้อนกลับและเก็บหลักฐานไว้ หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="d2" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="d2" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="d2" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="d2_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- D4 -->
                                    <tr>
                                        <td class="text-center font-bold">D4</td>
                                        <td class="text-justify font-medium">ข้อมูลที่ถูกใช้เพื่อการรายงานต่อหน่วยงานภายนอก อยู่ภายใต้การตรวจสอบอย่างเข้มงวด และได้รับการอนุมัติจากผู้บริหารระดับสูง หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="d4" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="d4" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="d4" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="d4_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                    <!-- D5 -->
                                    <tr>
                                        <td class="text-center font-bold">D5</td>
                                        <td class="text-justify font-medium">การส่งคืนข้อมูลทั้งหมด ถูกจัดเตรียมและจัดส่งตามระยะเวลาที่กำหนด รวมถึงมีการสนับสนุนแนวทางการตรวจสอบอย่างชัดเจนและครบถ้วน หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="d5" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="d5" value="มีบางส่วน"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="d5" value="ไม่มี"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="d5_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>

                    <!-- SECTION 7: การวางแผนการให้บริการ (Service Planning) -->
                    <fieldset class="form-section">
                        <legend><i data-lucide="calendar"></i> การวางแผนการให้บริการ (Service Planning)</legend>

                        <div class="table-responsive">
                            <table class="assessment-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px; text-align: center;">รหัส</th>
                                        <th style="width: 45%;">การวางแผนการให้บริการ (Service Planning)</th>
                                        <th style="width: 150px; text-align: center;">ใช่</th>
                                        <th style="width: 150px; text-align: center;">ไม่ใช่</th>
                                        <th style="min-width: 250px;">หลักฐาน / ความเห็น</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- R1 -->
                                    <tr>
                                        <td class="text-center font-bold">R1</td>
                                        <td class="text-justify font-medium">มีการรวบรวมขอบเขตที่มีความเสี่ยงระดับปานกลาง และระดับสูง ไว้ในการบริหารจัดการความเสี่ยงของแผนการให้บริการในปัจจุบันของหน่วยงาน หรือไม่</td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="r1" value="ใช่"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td class="text-center">
                                            <label class="table-radio"><input type="radio" name="r1" value="ไม่ใช่"><span class="table-checkmark"></span></label>
                                        </td>
                                        <td><textarea name="r1_evidence" rows="2" placeholder="ระบุหลักฐานหรือความเห็น..."></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>

                    <div class="form-navigation no-print" style="margin-top: 2rem;">
                        <button type="button" class="btn btn-secondary btn-prev-step"><i data-lucide="chevron-left"></i> ย้อนกลับ</button>
                        <button type="submit" class="btn btn-primary" id="btn-submit-form">
                            ส่งข้อมูลและสิ้นสุดการประเมิน <i data-lucide="send"></i>
                        </button>
                    </div>
                </div>

            </form>
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

        // --- 0. ระบบประสานข้อมูล "ชื่อข้อมูล" และ "หน่วยงาน" ไปยังหน้าต่างๆ ---
        const infoTitle = document.getElementById('info-title');
        const infoAgency = document.getElementById('info-agency');
        
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

            if (stepIndex === 0) {
                const reqs = [
                    { id: 'info-title', name: 'ชื่อข้อมูล' },
                    { id: 'info-agency', name: 'ชื่อหน่วยงานที่ดำเนินงาน' },
                    { id: 'metric-name', name: 'ชื่อตัวชี้วัดผลการประเมินคุณภาพข้อมูล' },
                    { id: 'metric-source', name: 'แหล่งที่มาข้อมูล' },
                    { id: 'eval-date', name: 'วันที่ประเมินคุณภาพข้อมูล' },
                    { id: 'eval-team', name: 'ทีมผู้ประเมินคุณภาพข้อมูล' },
                    { id: 'eval-approver', name: 'ผู้อนุมัติการประเมินคุณภาพข้อมูล' }
                ];
                reqs.forEach(field => {
                    const el = document.getElementById(field.id);
                    if (el && !el.value.trim()) {
                        el.classList.add('input-error');
                        isValid = false;
                        if (!errors.includes(field.name)) errors.push(field.name);
                    }
                });
            } else if (stepIndex === 1) {
                const checkRadios = [
                    'ac1_status', 'ac2_status', 'ac3_status', 'ac4_status', 'ac5_status', 'ac6_status', 'ac7_status', 'ac8_status',
                    're1_status', 're2_status', 're3_status', 're4_status', 're5_status',
                    'co1_status', 'co2_status', 'co3_status', 'co4_status',
                    'ti1_status', 'ti2_status', 'ti3_status', 'ti4_status', 'ti5_status',
                    'av1_status', 'av2_status', 'av3_status', 'av4_status'
                ];
                let unselected = 0;
                checkRadios.forEach(name => {
                    const checked = form.querySelector(`input[name="${name}"]:checked`);
                    if (!checked) {
                        isValid = false;
                        unselected++;
                        const firstRadio = form.querySelector(`input[name="${name}"]`);
                        if (firstRadio) {
                            const tr = firstRadio.closest('tr');
                            if (tr) tr.classList.add('row-error');
                        }
                    }
                });
                if (unselected > 0) {
                    errors.push(`เกณฑ์ประเมินมิติคุณภาพข้อมูลยังเลือกไม่ครบ ${unselected} หัวข้อ`);
                }
            } else if (stepIndex === 2) {
                const selfCheckRadios = [
                    'sa_1_1', 'sa_1_2', 'sa_1_3', 'sa_1_4', 'sa_1_5',
                    'sa_2_1', 'sa_2_2', 'sa_2_3', 'sa_2_4', 'sa_2_5', 'sa_2_6',
                    'sa_3_1', 'sa_3_2',
                    'sa_4_1', 'sa_4_2', 'sa_4_3', 'sa_4_4',
                    'sa_5_1', 'sa_5_2', 'sa_5_3', 'sa_5_4', 'sa_5_5'
                ];
                let unselected = 0;
                selfCheckRadios.forEach(name => {
                    const checked = form.querySelector(`input[name="${name}"]:checked`);
                    if (!checked) {
                        isValid = false;
                        unselected++;
                        const firstRadio = form.querySelector(`input[name="${name}"]`);
                        if (firstRadio) {
                            const card = firstRadio.closest('.self-assess-card');
                            if (card) card.classList.add('card-error');
                        }
                    }
                });
                if (unselected > 0) {
                    errors.push(`แบบประเมินตนเองระดับคุณภาพยังเลือกไม่ครบ ${unselected} หัวข้อ`);
                }
            } else if (stepIndex === 3) {
                const reqs = [
                    { id: 'info-service', name: 'บริการ' },
                    { id: 'info-head', name: 'หัวหน้า กอง/สำนัก/ฝ่าย/ศูนย์' },
                    { id: 'control-date', name: 'วันที่ประเมินผลควบคุม' }
                ];
                reqs.forEach(field => {
                    const el = document.getElementById(field.id);
                    if (el && !el.value.trim()) {
                        el.classList.add('input-error');
                        isValid = false;
                        if (!errors.includes(field.name)) errors.push(field.name);
                    }
                });

                const controlRadios = [
                    'g1', 'g2', 'g3', 'g4', 'g5', 'g6', 'g7',
                    'p1', 'p2', 'p3', 'p4', 'p5', 'p6', 'p7',
                    's1', 's2', 's3', 's4', 's5', 's6', 's7', 's8', 's9',
                    'e1', 'e2', 'e3', 'e4',
                    'd1', 'd2', 'd4', 'd5', 'r1'
                ];
                let unselected = 0;
                controlRadios.forEach(name => {
                    const checked = form.querySelector(`input[name="${name}"]:checked`);
                    if (!checked) {
                        isValid = false;
                        unselected++;
                        const firstRadio = form.querySelector(`input[name="${name}"]`);
                        if (firstRadio) {
                            const tr = firstRadio.closest('tr');
                            if (tr) tr.classList.add('row-error');
                        }
                    }
                });
                if (unselected > 0) {
                    errors.push(`เกณฑ์ประเมินการควบคุมติดตามคุณภาพยังเลือกไม่ครบ ${unselected} หัวข้อ`);
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
