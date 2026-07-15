<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="แบบตรวจประเมินคุณภาพข้อมูล (DQA Checklist) กระทรวงพาณิชย์ (MOC)">
    <title>แบบตรวจประเมินคุณภาพ (DQA Checklist)</title>
    
    <!-- Google Fonts - Sarabun for Thai Government official look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&family=Outfit:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=2">
</head>
<body>
    <!-- Top Decorative Line -->
    <div class="top-bar-accent"></div>

    <div class="container">
        <!-- Official Headings -->
        <header class="form-header no-print">
            <div class="logo-wrapper">
                <!-- Golden & Blue Seal Motif -->
                <div class="gov-seal">
                    <img src="assets/images/ops-logo.jpg" alt="OPS Logo" style="width:100%; height:100%; object-fit:contain;">
                </div>
                <div class="title-group">
                    <h1>แบบตรวจประเมินคุณภาพ (DQA Checklist) รายงานตรวจประเมิน</h1>
                    <span class="agency-tag">สำนักงานปลัดกระทรวงพาณิชย์</span>
                </div>
            </div>
            
        </header>

        <!-- Save Status Notification -->
        <div id="toast" class="toast hide">
            <span id="toast-message">บันทึกข้อมูลเรียบร้อยแล้ว</span>
        </div>

        <!-- Main Form Document -->
        <main class="document-paper">
            <!-- Header on Paper -->
            <div class="paper-header">
                <div class="official-title-bar">
                    <h2>(ร่าง) แบบตรวจประเมินคุณภาพข้อมูล (DQA Checklist)</h2>
                </div>
                
                <div class="instruction-box" style="margin-top: 2rem; border-left: 4px solid var(--moc-gold); line-height: 1.45; font-size: 0.95rem; text-align: left; background-color: var(--moc-gold-light); color: var(--text-dark);">
                    <strong>คำชี้แจง :</strong> การตรวจประเมินคุณภาพข้อมูล (DQA Checklist) นี้จัดทำขึ้นเพื่อแนะนำเครื่องมือสำหรับ ทีมผู้ประเมินคุณภาพข้อมูล เพื่อใช้ดำเนินการประเมินคุณภาพข้อมูลขององค์กรให้สมบูรณ์ ด้วยการใช้งานแบบตรวจประเมินคุณภาพข้อมูล (DQA Checklist) ซึ่งมีรายละเอียดที่จะช่วยให้การตรวจสอบกระบวนการเตรียมข้อมูลและคุณภาพข้อมูลใน 5 มิติ ได้แก่ ความถูกต้องและสมบูรณ์ (Accuracy and Completeness) ความสอดคล้องกัน (Consistency) ความเป็นปัจจุบัน (Timeliness) ตรงตามความต้องการของผู้ใช้ (Relevancy) ความพร้อมใช้ (Availability) ดังนี้
                </div>
            </div>

            <!-- Form Start -->
            <form id="dqa-form" method="POST" action="api.php">
                
                <!-- SECTION 1: ข้อมูลหน่วยงานและภารกิจ -->
                <fieldset class="form-section">
                    <legend><i data-lucide="info"></i> ส่วนที่ 1: ข้อมูลทั่วไปของข้อมูลและหน่วยงาน</legend>
                    
                    <div class="form-group">
                        <label for="info-title">ชื่อข้อมูล :</label>
                        <textarea id="info-title" name="info_title" rows="2" placeholder="กรอกชื่อข้อมูลหรือชื่อชุดข้อมูล..."></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="info-agency">ชื่อหน่วยงานที่ดำเนินงาน :</label>
                            <select id="info-agency" name="info_agency">
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

                <!-- SECTION 2: ตัวชี้วัดและโครงสร้างแผนงาน -->
                <fieldset class="form-section">
                    <legend><i data-lucide="trending-up"></i> ส่วนที่ 2: ตัวชี้วัดและเป้าหมาย</legend>

                    <div class="form-group">
                        <label for="metric-name">ชื่อตัวชี้วัดผลการประเมินคุณภาพข้อมูล :</label>
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
                        <label for="metric-source">แหล่งที่มาข้อมูล :</label>
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
                            <label for="metric-standard-detail">ตัวชี้วัดคุณภาพข้อมูลเป็นไปตามมาตรฐานหรือกำหนดเอง :</label>
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
                            <label for="eval-date">วันที่ประเมินคุณภาพข้อมูล :</label>
                            <input type="date" id="eval-date" name="eval_date" style="width: 100%; padding: 0.6rem 0.8rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color); background-color: #ffffff;">
                        </div>
                        
                        <div class="form-group col-6">
                            <label for="eval-team">ทีมผู้ประเมินคุณภาพข้อมูล :</label>
                            <input type="text" id="eval-team" name="eval_team" placeholder="ทีมบริการข้อมูล">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="eval-approver">ผู้อนุมัติการประเมินคุณภาพข้อมูล :</label>
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
            </form>

            <!-- Navigation Footer -->
            <div class="form-navigation no-print">
                <div></div> <!-- Spacer -->
                <a href="page3.php" class="btn btn-primary" id="btn-next-page">
                    หน้าถัดไป (มิติคุณภาพข้อมูล) <i data-lucide="chevron-right"></i>
                </a>
            </div>
        </main>
    </div>

    <!-- Custom Script -->
    <script src="assets/js/app.js"></script>
</body>
</html>
