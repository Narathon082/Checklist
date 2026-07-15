<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="แบบตรวจประเมินคุณภาพข้อมูลทั้งหมด (DQA Checklist All-in-One)">
    <title>แบบตรวจประเมินคุณภาพ (DQA Checklist) - รวมทุกส่วน</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&family=Outfit:wght@500;700&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <link rel="stylesheet" href="assets/css/style.css?v=2">

    <style>
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
        }
        /* ตกแต่งปุ่มเพิ่มเติมนิดหน่อยให้กดง่ายขึ้น */
        .btn {
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="top-bar-accent"></div>

    <div class="container">
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
        </header>

        <div id="toast" class="toast hide">
            <span id="toast-message">บันทึกข้อมูลเรียบร้อยแล้ว</span>
        </div>

        <main class="document-paper">
            <form id="dqa-form" method="POST" action="api.php">
                
                <div id="step-1" class="form-step active">
                    <div class="paper-header">
                        <div class="official-title-bar">
                            <h2>(ร่าง) แบบตรวจประเมินคุณภาพข้อมูล (DQA Checklist)</h2>
                        </div>
                        <div class="instruction-box" style="margin-top: 2rem; border-left: 4px solid var(--moc-gold); line-height: 1.45; font-size: 0.95rem; text-align: left; background-color: var(--moc-gold-light); color: var(--text-dark);">
                            <strong>คำชี้แจง :</strong> การตรวจประเมินคุณภาพข้อมูล (DQA Checklist) นี้จัดทำขึ้นเพื่อแนะนำเครื่องมือสำหรับ ทีมผู้ประเมินคุณภาพข้อมูล เพื่อใช้ดำเนินการประเมินคุณภาพข้อมูลขององค์กรให้สมบูรณ์ ด้วยการใช้งานแบบตรวจประเมินคุณภาพข้อมูล (DQA Checklist) ซึ่งมีรายละเอียดที่จะช่วยให้การตรวจสอบกระบวนการเตรียมข้อมูลและคุณภาพข้อมูลใน 5 มิติ
                        </div>
                    </div>

                    <fieldset class="form-section">
                        <legend><i data-lucide="info"></i> ส่วนที่ 1: ข้อมูลทั่วไปของข้อมูลและหน่วยงาน</legend>
                        <div class="form-group">
                            <label for="info-title-s1">ชื่อข้อมูล :</label>
                            <textarea id="info-title-s1" name="info_title" rows="2" placeholder="กรอกชื่อข้อมูลหรือชื่อชุดข้อมูล..."></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="info-agency-s1">ชื่อหน่วยงานที่ดำเนินงาน :</label>
                                <select id="info-agency-s1" name="info_agency">
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

                    <fieldset class="form-section">
                        <legend><i data-lucide="users"></i> ส่วนที่ 3: แหล่งข้อมูลและการกำหนดมาตรฐาน</legend>
                        <div class="form-group">
                            <label for="source-partner">หน่วยงานเครือข่าย (Partner) / ผู้รับจ้าง (Vendor) ที่ให้ข้อมูล :</label>
                            <textarea id="source-partner" name="source_partner" rows="3" placeholder="..."></textarea>
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
                                <label for="metric-standard-detail">รายละเอียดมาตรฐาน :</label>
                                <textarea id="metric-standard-detail" name="metric_standard_detail" rows="1" placeholder="(โดย มาตรฐาน...)"></textarea>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="form-section">
                        <legend><i data-lucide="check-circle-2"></i> ส่วนที่ 4: กระบวนการประเมินและการอนุมัติ</legend>
                        <div class="form-group">
                            <label for="eval-method">วิธีการประเมินคุณภาพข้อมูล :</label>
                            <textarea id="eval-method" name="eval_method" rows="3" placeholder="อธิบายวิธีการประเมิน..."></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="eval-date">วันที่ประเมินคุณภาพข้อมูล :</label>
                                <input type="date" id="eval-date" name="eval_date" style="width: 100%; padding: 0.6rem 0.8rem; border: 1px solid var(--border-color); background-color: #fff;">
                            </div>
                            <div class="form-group col-6">
                                <label for="eval-team">ทีมผู้ประเมินคุณภาพข้อมูล :</label>
                                <input type="text" id="eval-team" name="eval_team" placeholder="ทีมบริการข้อมูล">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="eval-approver">ผู้อนุมัติการประเมินคุณภาพข้อมูล :</label>
                            <textarea id="eval-approver" name="eval_approver" rows="2" placeholder="หัวหน้าคณะทำงาน..."></textarea>
                        </div>
                    </fieldset>

                    <div class="form-navigation no-print">
                        <div></div>
                        <button type="button" class="btn btn-primary next-step-btn">หน้าถัดไป (มิติคุณภาพข้อมูล) <i data-lucide="chevron-right"></i></button>
                    </div>
                </div>


                <div id="step-2" class="form-step">
                    <div class="dimension-header-bar">
                        <h3>มิติคุณภาพข้อมูล</h3>
                    </div>

                    <div class="dimension-sub-bar">
                        <h4>ความถูกต้อง และสมบูรณ์ (Accuracy and Completeness)</h4>
                    </div>
                    <div class="dimension-desc-box">
                        ข้อมูลมีความถูกต้องแม่นยำสูง หรือถ้ามีความคลาดเคลื่อนอยู่บ้าง ควรที่จะสามารถควบคุมขนาดของความคลาดเคลื่อนให้มีน้อยที่สุด...
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
                                <?php
                                $ac_items = [
                                    "AC1" => "ข้อมูลมีความถูกต้องหรือไม่ (ข้อมูลไม่มีข้อผิดพลาดมีวิธีการที่ใช้ในการควบคุมข้อมูลนำเข้าและการควบคุมการประมวลผลที่ถูกต้องเชื่อถือได้...)",
                                    "AC2" => "ข้อมูลมีแหล่งที่มาที่น่าเชื่อถือหรือไม่ (มีการระบุแหล่งที่มา สามารถตรวจสอบได้ว่ามาจากแหล่งใด...)",
                                    "AC3" => "ข้อมูลที่เก็บรวบรวมมาได้จากประชากรหรือตัวอย่างมีสัดส่วนที่เพียงพอหรือไม่ และ/หรือข้อมูลที่เก็บรวบรวมมีตรงตามดัชนีชี้วัดความสำเร็จของงาน (KPI)หรือไม่",
                                    "AC4" => "ผลลัพธ์การรวบรวมข้อมูลอยู่ในช่วงค่าคะแนนที่เป็นไปได้หรือสมเหตุสมผลหรือไม่",
                                    "AC5" => "ระเบียบวิธีวิจัยที่ใช้ในการรวบรวมข้อมูลเหมาะสมถูกต้องหรือไม่...",
                                    "AC6" => "มีขั้นตอนแก้ไขความผิดพลาดของข้อมูลที่รับรู้ หรือลดข้อจำกัด/ความผิดพลาดในการสำเนา/นำเข้าข้อมูลหรือไม่",
                                    "AC7" => "มีการประเมินปัญหาการรวบรวมข้อมูลที่รับรู้อย่างเหมาะสมหรือไม่",
                                    "AC8" => "มีวิธีการ/เครื่องมือป้องกันรักษาความปลอดภัยของข้อมูลหรือไม่"
                                ];
                                foreach ($ac_items as $code => $text) {
                                    $lower_code = strtolower($code);
                                    echo '<tr>
                                        <td class="text-center font-bold">'.$code.'</td>
                                        <td class="text-justify font-medium">'.$text.'</td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="'.$lower_code.'_status" value="ใช่"><span class="table-checkmark"></span></label></td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="'.$lower_code.'_status" value="ไม่ใช่"><span class="table-checkmark"></span></label></td>
                                        <td><textarea name="'.$lower_code.'_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea></td>
                                    </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="dimension-sub-bar" style="margin-top: 3rem;">
                        <h4>ตรงตามความต้องการของผู้ใช้ (Relevancy)</h4>
                    </div>
                    <div class="dimension-desc-box">
                        ข้อมูลที่จัดทำขึ้นมาเป็นข้อมูลที่ผู้ใช้ต้องการ หรือเป็นข้อมูลที่จำเป็นต้องทราบ...
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
                                <?php
                                $re_items = [
                                    "RE1" => "ข้อมูลตรงตามความต้องการของผู้ใช้งานและตามวัตถุประสงค์ของการใช้งานหรือไม่...",
                                    "RE2" => "ต้นทุนในการทำให้ระดับความถูกต้องของข้อมูลเพิ่มสูงขึ้นมากกว่ามูลค่าของข้อมูลข่าวสารหรือไม่",
                                    "RE3" => "มีการกำหนดค่าส่วนเกินของความผิดพลาดที่รับได้สำหรับแผนงานการตัดสินใจหรือไม่",
                                    "RE4" => "มีวิธีการตรวจสอบข้อมูลที่ซ้ำกันหรือข้อมูลที่ขาดหายหรือไม่",
                                    "RE5" => "ชุดข้อมูลส่วนใหญ่เป็นชุดข้อมูลที่มีคุณค่าสูง (High Value Datasets) หรือไม่"
                                ];
                                foreach ($re_items as $code => $text) {
                                    $lower_code = strtolower($code);
                                    echo '<tr>
                                        <td class="text-center font-bold">'.$code.'</td>
                                        <td class="text-justify font-medium">'.$text.'</td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="'.$lower_code.'_status" value="ใช่"><span class="table-checkmark"></span></label></td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="'.$lower_code.'_status" value="ไม่ใช่"><span class="table-checkmark"></span></label></td>
                                        <td><textarea name="'.$lower_code.'_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea></td>
                                    </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="dimension-sub-bar" style="margin-top: 3rem;">
                        <h4>ความสอดคล้องกัน (Consistency)</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="assessment-table">
                            <tbody>
                                <?php
                                $co_items = [
                                    "CO1" => "มีรูปแบบการจัดเก็บข้อมูลที่สอดคล้องและเป็นมาตรฐานเดียวกันหรือไม่...",
                                    "CO2" => "หากใช้วิธีการจัดเก็บข้อมูลแบบเดียวกันเพื่อวัดผลในเรื่องเดียวกันในหลายครั้ง จะได้ผลลัพธ์ที่เหมือนกันหรือไม่",
                                    "CO3" => "มีเอกสารและแนวปฏิบัติในการจัดเก็บและวิเคราะห์ข้อมูลเพื่อสร้างความเชื่อมั่นแนวปฏิบัติเดียวกันหรือไม่",
                                    "CO4" => "มีความสอดคล้องกันในกระบวนการจัดเก็บข้อมูลที่ถูกใช้ระหว่างปี พื้นที่จัดเก็บ และแหล่งที่มาข้อมูลหรือไม่"
                                ];
                                foreach ($co_items as $code => $text) {
                                    $lower_code = strtolower($code);
                                    echo '<tr>
                                        <td class="text-center font-bold">'.$code.'</td>
                                        <td class="text-justify font-medium">'.$text.'</td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="'.$lower_code.'_status" value="ใช่"><span class="table-checkmark"></span></label></td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="'.$lower_code.'_status" value="ไม่ใช่"><span class="table-checkmark"></span></label></td>
                                        <td><textarea name="'.$lower_code.'_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea></td>
                                    </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="dimension-sub-bar" style="margin-top: 3rem;">
                        <h4>ความเป็นปัจจุบันและความพร้อมใช้ (Timeliness & Availability)</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="assessment-table">
                            <tbody>
                                <?php
                                $ti_items = [
                                    "TI1" => "ข้อมูลที่จัดหาได้มีความถี่เพียงพอต่อการแจ้งแผนงานในการตัดสินใจหรือไม่",
                                    "TI2" => "ข้อมูลที่ถูกนำมารายงานส่วนใหญ่ใช้ได้จริงและเป็นปัจจุบันหรือไม่",
                                    "TI3" => "ข้อมูลถูกนำมารายงานทันทีเท่าที่จะเป็นไปได้ภายหลังการจัดเก็บหรือไม่",
                                    "TI4" => "มีกำหนดตารางเวลาการจัดเก็บข้อมูลเป็นประจำหรือไม่",
                                    "TI5" => "ข้อมูลมีการจัดเก็บอย่างเหมาะสมและพร้อมใช้งานหรือไม่"
                                ];
                                foreach ($ti_items as $code => $text) {
                                    $lower_code = strtolower($code);
                                    echo '<tr>
                                        <td class="text-center font-bold">'.$code.'</td>
                                        <td class="text-justify font-medium">'.$text.'</td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="'.$lower_code.'_status" value="ใช่"><span class="table-checkmark"></span></label></td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="'.$lower_code.'_status" value="ไม่ใช่"><span class="table-checkmark"></span></label></td>
                                        <td><textarea name="'.$lower_code.'_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea></td>
                                    </tr>';
                                }
                                
                                $av_items = [
                                    "AV1" => "มีกระบวนการจัดทำข้อมูลที่สามารถอ่านได้ด้วยเครื่องคอมพิวเตอร์ (Machine Readable) หรือไม่",
                                    "AV2" => "มีการจัดทำและเผยแพร่คำอธิบายข้อมูล หรือ Metadata หรือไม่",
                                    "AV3" => "มีช่องทางการเผยแพร่ข้อมูลที่หลากหลายและสามารถเข้าถึงได้ง่ายหรือไม่",
                                    "AV4" => "มีกระบวนการ/แนวปฏิบัติในการขอข้อมูลแชร์ข้อมูล (ที่ไม่ใช่ข้อมูลสาธารณะ) หรือไม่"
                                ];
                                foreach ($av_items as $code => $text) {
                                    $lower_code = strtolower($code);
                                    echo '<tr>
                                        <td class="text-center font-bold">'.$code.'</td>
                                        <td class="text-justify font-medium">'.$text.'</td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="'.$lower_code.'_status" value="ใช่"><span class="table-checkmark"></span></label></td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="'.$lower_code.'_status" value="ไม่ใช่"><span class="table-checkmark"></span></label></td>
                                        <td><textarea name="'.$lower_code.'_comment" rows="2" placeholder="ระบุความเห็นเพิ่มเติม..."></textarea></td>
                                    </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-navigation no-print">
                        <button type="button" class="btn btn-secondary prev-step-btn"><i data-lucide="chevron-left"></i> ย้อนกลับ</button>
                        <button type="button" class="btn btn-primary next-step-btn">หน้าถัดไป (แบบประเมินตนเอง) <i data-lucide="chevron-right"></i></button>
                    </div>
                </div>


                <div id="step-3" class="form-step">
                    <div class="paper-header">
                        <div class="official-title-bar">
                            <h2>(ร่าง) แบบประเมินคุณภาพข้อมูลด้วยตนเอง (DQA Self-Assessment)</h2>
                        </div>
                        <div class="instruction-box" style="margin-top: 2rem; border-left: 4px solid var(--moc-gold); background-color: var(--moc-gold-light); color: var(--text-dark); padding: 1rem;">
                            <strong>คำชี้แจง :</strong> ระบบจะประมวลผลเป็นกราฟใยแมงมุม (Radar Graph) ด้านล่างหลังจากทำเครื่องหมายเลือกคะแนนครบถ้วน
                        </div>
                    </div>

                    <div class="dimension-sub-bar" style="margin-top: 2rem;">
                        <h4>1. ความถูกต้อง และสมบูรณ์ (Accuracy and Completeness)</h4>
                    </div>
                    
                    <div class="self-assess-card">
                        <div class="self-assess-header">1.1 มีแหล่งข้อมูลที่น่าเชื่อถือ</div>
                        <div class="self-assess-options">
                            <label class="self-assess-option"><input type="radio" name="sa_1_1" value="1"><span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ใช้ข้อมูลจากแหล่งอ้างอิงที่ไม่น่าเชื่อถือ ขาดหลักฐานเชิงประจักษ์</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_1_1" value="2"><span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> ใช้ข้อมูลจากแหล่งข้อมูลที่ไม่น่าเชื่อถือแต่มีเนื้อหาที่รับรองได้</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_1_1" value="3"><span class="self-assess-option-text"><strong>3 ดี :</strong> ใช้ข้อมูลจากแหล่งข้อมูลที่น่าเชื่อถือหรือมีแหล่งข้อมูลที่น่าเชื่อถือ</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_1_1" value="4"><span class="self-assess-option-text"><strong>4 ดีมาก :</strong> ใช้ข้อมูลจากแหล่งข้อมูลที่น่าเชื่อถือและถูกต้องตามหลักวิชาการ</span></label>
                        </div>
                    </div>
                    <div class="self-assess-card">
                        <div class="self-assess-header">1.2 มีกระบวนการหรือเครื่องมือตรวจสอบจุดผิดพลาดของข้อมูล</div>
                        <div class="self-assess-options">
                            <label class="self-assess-option"><input type="radio" name="sa_1_2" value="1"><span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ขาดกระบวนการหรือเครื่องมือตรวจสอบความถูกต้องของข้อมูล</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_1_2" value="2"><span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> มีกระบวนการตรวจสอบจุดผิดพลาดที่ไร้รูปแบบ และอาศัยบุคคล</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_1_2" value="3"><span class="self-assess-option-text"><strong>3 ดี :</strong> มีกระบวนการ เครื่องมือตรวจสอบความถูกต้องเป็นแบบแผน</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_1_2" value="4"><span class="self-assess-option-text"><strong>4 ดีมาก :</strong> มีระบบแบบแผน และแจ้งเตือนอัตโนมัติ</span></label>
                        </div>
                    </div>
                    <div class="self-assess-card">
                        <div class="self-assess-header">1.3 มีการตรวจสอบความครบถ้วนของข้อมูล</div>
                        <div class="self-assess-options">
                            <label class="self-assess-option"><input type="radio" name="sa_1_3" value="1"><span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ขาดกระบวนการตรวจทานความครบถ้วนของข้อมูล</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_1_3" value="2"><span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> ตรวจสอบโดยอาศัยการสังเกตด้วยบุคคล</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_1_3" value="3"><span class="self-assess-option-text"><strong>3 ดี :</strong> มีกระบวนการตรวจสอบด้วยเครื่องมืออัตโนมัติ</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_1_3" value="4"><span class="self-assess-option-text"><strong>4 ดีมาก :</strong> รับรองความครบถ้วนตั้งแต่ขั้นตอนเก็บรวบรวมจนบันทึกในระบบ</span></label>
                        </div>
                    </div>
                    <div class="self-assess-card">
                        <div class="self-assess-header">1.4 มีวิธีเก็บข้อมูลมีความเป็นกลาง น่าเชื่อถือ และไม่สร้างข้อมูลที่มีอคติ</div>
                        <div class="self-assess-options">
                            <label class="self-assess-option"><input type="radio" name="sa_1_4" value="1"><span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ขาดการกำหนดวิธีการเก็บข้อมูลด้วยกรอบมาตรฐานที่น่าเชื่อถือ</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_1_4" value="2"><span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> กำหนดกลุ่มตัวอย่างตามหลักของสถิติ หรือเครื่องมือพื้นฐาน</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_1_4" value="3"><span class="self-assess-option-text"><strong>3 ดี :</strong> อย่างใดอย่างหนึ่ง เช่น คุมสถิตลุ่มตัวอย่าง หรือ มีเครื่องมือแบบสอบถามที่มีมาตรฐานความเชื่อมั่น</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_1_4" value="4"><span class="self-assess-option-text"><strong>4 ดีมาก :</strong> ควบคุมกลุ่มตัวอย่างตามหลักสถิติ และ มีเครื่องมือทดสอบความเที่ยงตรงวิชาการครบครัน</span></label>
                        </div>
                    </div>
                    <div class="self-assess-card">
                        <div class="self-assess-header">1.5 มีการระบุคำนิยามและลักษณะข้อมูลที่ต้องการ</div>
                        <div class="self-assess-options">
                            <label class="self-assess-option"><input type="radio" name="sa_1_5" value="1"><span class="self-assess-option-text"><strong>1 ต่ำ :</strong> ขาดคำนิยามและลักษณะที่พึงประสงค์ชัดเจน</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_1_5" value="2"><span class="self-assess-option-text"><strong>2 ปานกลาง :</strong> มีคำนิยามแต่ขาดความชัดเจนและคลุมเครือ</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_1_5" value="3"><span class="self-assess-option-text"><strong>3 ดี :</strong> มีคำนิยามและมาตรฐานข้อมูลชัดเจน</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_1_5" value="4"><span class="self-assess-option-text"><strong>4 ดีมาก :</strong> มีมาตรฐานชัดเจน ครอบคลุมกรณีผิดปกติให้เก็บได้ถูกต้อง</span></label>
                        </div>
                    </div>

                    <div class="dimension-sub-bar" style="margin-top: 3rem;">
                        <h4>2. ความสอดคล้องกัน (Consistency)</h4>
                    </div>
                    <?php
                    $sa_co = [
                        "2_1" => "มีมาตรฐานข้อมูลเดียวกันในหน่วยงาน",
                        "2_2" => "มีการตรวจสอบรูปแบบข้อมูลชุดเดียวกัน",
                        "2_3" => "มีการตรวจสอบรูปแบบฟิลด์เชิงเทคนิค",
                        "2_4" => "ข้อมูลเชื่อมโยงและไม่ขัดแย้งกันระหว่างฝ่าย",
                        "2_5" => "มีกฎเกณฑ์การตรวจวัดสอดคล้องกันทั่วองค์กร",
                        "2_6" => "มีการกำหนดบทบาทและผู้รับผิดชอบข้อมูลชัดเจน"
                    ];
                    foreach($sa_co as $key => $title) {
                        echo '<div class="self-assess-card">
                            <div class="self-assess-header">2.'.$key.' '.$title.'</div>
                            <div class="self-assess-options">
                                <label class="self-assess-option"><input type="radio" name="sa_'.$key.'" value="1"><span class="self-assess-option-text">1 ต่ำ</span></label>
                                <label class="self-assess-option"><input type="radio" name="sa_'.$key.'" value="2"><span class="self-assess-option-text">2 ปานกลาง</span></label>
                                <label class="self-assess-option"><input type="radio" name="sa_'.$key.'" value="3"><span class="self-assess-option-text">3 ดี</span></label>
                                <label class="self-assess-option"><input type="radio" name="sa_'.$key.'" value="4"><span class="self-assess-option-text">4 ดีมาก</span></label>
                            </div>
                        </div>';
                    }
                    ?>

                    <div class="dimension-sub-bar" style="margin-top: 3rem;">
                        <h4>3. ตรงตามความต้องการของผู้ใช้ (Relevancy)</h4>
                    </div>
                    <div class="self-assess-card">
                        <div class="self-assess-header">3.1 ข้อมูลตรงความต้องการผู้ใช้</div>
                        <div class="self-assess-options">
                            <label class="self-assess-option"><input type="radio" name="sa_3_1" value="1"><span class="self-assess-option-text">1 ต่ำ</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_3_1" value="2"><span class="self-assess-option-text">2 ปานกลาง</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_3_1" value="3"><span class="self-assess-option-text">3 ดี</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_3_1" value="4"><span class="self-assess-option-text">4 ดีมาก</span></label>
                        </div>
                    </div>
                    <div class="self-assess-card">
                        <div class="self-assess-header">3.2 มีผลประเมินและนำไปปรับปรุง</div>
                        <div class="self-assess-options">
                            <label class="self-assess-option"><input type="radio" name="sa_3_2" value="1"><span class="self-assess-option-text">1 ต่ำ</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_3_2" value="2"><span class="self-assess-option-text">2 ปานกลาง</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_3_2" value="3"><span class="self-assess-option-text">3 ดี</span></label>
                            <label class="self-assess-option"><input type="radio" name="sa_3_2" value="4"><span class="self-assess-option-text">4 ดีมาก</span></label>
                        </div>
                    </div>

                    <div class="dimension-sub-bar" style="margin-top: 3rem;">
                        <h4>4. ความเป็นปัจจุบัน (Timeliness)</h4>
                    </div>
                    <?php
                    for($i=1; $i<=4; $i++) {
                        echo '<div class="self-assess-card">
                            <div class="self-assess-header">4.'.$i.' ประเด็นความเป็นปัจจุบันข้อที่ '.$i.'</div>
                            <div class="self-assess-options">
                                <label class="self-assess-option"><input type="radio" name="sa_4_'.$i.'" value="1"><span class="self-assess-option-text">1 ต่ำ</span></label>
                                <label class="self-assess-option"><input type="radio" name="sa_4_'.$i.'" value="2"><span class="self-assess-option-text">2 ปานกลาง</span></label>
                                <label class="self-assess-option"><input type="radio" name="sa_4_'.$i.'" value="3"><span class="self-assess-option-text">3 ดี</span></label>
                                <label class="self-assess-option"><input type="radio" name="sa_4_'.$i.'" value="4"><span class="self-assess-option-text">4 ดีมาก</span></label>
                            </div>
                        </div>';
                    }
                    ?>

                    <div class="dimension-sub-bar" style="margin-top: 3rem;">
                        <h4>5. ความพร้อมใช้ (Availability)</h4>
                    </div>
                    <?php
                    for($i=1; $i<=5; $i++) {
                        echo '<div class="self-assess-card">
                            <div class="self-assess-header">5.'.$i.' ประเด็นความพร้อมใช้งานข้อที่ '.$i.'</div>
                            <div class="self-assess-options">
                                <label class="self-assess-option"><input type="radio" name="sa_5_'.$i.'" value="1"><span class="self-assess-option-text">1 ต่ำ</span></label>
                                <label class="self-assess-option"><input type="radio" name="sa_5_'.$i.'" value="2"><span class="self-assess-option-text">2 ปานกลาง</span></label>
                                <label class="self-assess-option"><input type="radio" name="sa_5_'.$i.'" value="3"><span class="self-assess-option-text">3 ดี</span></label>
                                <label class="self-assess-option"><input type="radio" name="sa_5_'.$i.'" value="4"><span class="self-assess-option-text">4 ดีมาก</span></label>
                            </div>
                        </div>';
                    }
                    ?>

                    <div class="dimension-sub-bar" style="margin-top: 3rem;">
                        <h4>ส่วนที่ 2 การแสดงผลประเมินคุณภาพข้อมูลด้วยตัวเอง</h4>
                    </div>
                    <div id="radar-section" style="margin-top: 1.5rem; padding: 1.5rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 12px;">
                        <div style="display: flex; align-items: flex-start; gap: 2rem; flex-wrap: wrap;">
                            <div style="min-width: 140px; padding-top: 1.5rem;">
                                <p style="font-weight: 700; color: var(--moc-blue-deep); font-size: 0.9rem; margin-bottom: 1rem;">มิติคุณภาพของข้อมูล</p>
                                <div id="score-legend" style="display: flex; flex-direction: column; gap: 0.5rem;"></div>
                            </div>
                            <div style="flex: 1; min-width: 280px; max-width: 480px; position: relative;">
                                <canvas id="radarChart" height="400"></canvas>
                                <p id="radar-placeholder-msg" style="text-align:center; color:#94a3b8; font-size:0.85rem; margin-top:0.5rem;">กรุณาเลือกคะแนนด้านบนเพื่อคำนวณกราฟ</p>
                            </div>
                            <div style="min-width: 200px;">
                                <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
                                    <thead>
                                        <tr style="background: var(--moc-blue-deep); color: #fff;">
                                            <th style="padding: 0.5rem 0.75rem; text-align:left;">มิติ</th>
                                            <th style="padding: 0.5rem 0.75rem; text-align:center;">คะแนนเฉลี่ย</th>
                                        </tr>
                                    </thead>
                                    <tbody id="score-table-body">
                                        <tr><td colspan="2" style="text-align:center; padding:0.75rem; color:#94a3b8;">ยังไม่มีข้อมูล</td></tr>
                                    </tbody>
                                </table>
                                <div id="overall-score-box" style="margin-top: 1rem; padding: 0.75rem 1rem; background: var(--moc-gold-light); border-radius: 8px; border-left: 4px solid var(--moc-gold); display:none;">
                                    <span style="font-size: 0.85rem; color: var(--text-dark); font-weight: 600;">คะแนนรวมเฉลี่ย : </span>
                                    <span id="overall-score-value" style="font-size: 1.2rem; font-weight: 800; color: var(--moc-blue-deep);">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-navigation no-print">
                        <button type="button" class="btn btn-secondary prev-step-btn"><i data-lucide="chevron-left"></i> ย้อนกลับ</button>
                        <button type="button" class="btn btn-primary next-step-btn">หน้าถัดไป (แบบตรวจการควบคุมฯ) <i data-lucide="chevron-right"></i></button>
                    </div>
                </div>


                <div id="step-4" class="form-step">
                    <div class="paper-header">
                        <div class="official-title-bar">
                            <h2>แบบตรวจประเมินการควบคุมและติดตามคุณภาพข้อมูล<br>(Data Quality Monitoring and Control Checklist)</h2>
                        </div>
                    </div>

                    <fieldset class="form-section">
                        <legend><i data-lucide="info"></i> ข้อมูลเพิ่มเติมกระบวนการควบคุม</legend>
                        <div class="form-group">
                            <label for="info-service">บริการ :</label>
                            <textarea id="info-service" name="info_service" rows="2" placeholder="ข้อมูลสถิติการค้า"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="info-head">หัวหน้า กอง/สำนัก/ฝ่าย/ศูนย์ และ/หรือ บริการ :</label>
                            <textarea id="info-head" name="info_head" rows="2" placeholder="ผู้อำนวยการศูนย์..."></textarea>
                        </div>
                        <div class="form-group">
                            <label for="control-date">วันที่ประเมินผลควบคุม :</label>
                            <input type="date" id="control-date" name="control_date" style="width: 100%; padding: 0.6rem 0.8rem; border: 1px solid var(--border-color); background-color: #ffffff;">
                        </div>
                    </fieldset>

                    <fieldset class="form-section">
                        <legend><i data-lucide="shield-check"></i> รายการมาตรฐานและการควบคุมระดับความเสี่ยง</legend>
                        <div class="table-responsive">
                            <table class="assessment-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">รหัส</th>
                                        <th style="width: 45%;">มาตรฐานคุณภาพข้อมูล</th>
                                        <th style="text-align: center;">มีอย่างเหมาะสม<br><small>(เสี่ยงต่ำ)</small></th>
                                        <th style="text-align: center;">มีบางส่วน<br><small>(เสี่ยงกลาง)</small></th>
                                        <th style="text-align: center;">ไม่มี<br><small>(เสี่ยงสูง)</small></th>
                                        <th>หลักฐาน / ความเห็น</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center font-bold">G1</td>
                                        <td class="text-justify font-medium">เจ้าหน้าที่ระดับอาวุโสมีความรับผิดชอบเชิงกลยุทธ์ในภาพรวมสำหรับกำกับดูแลคุณภาพข้อมูลหรือไม่</td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="g1" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label></td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="g1" value="มีบางส่วน"><span class="table-checkmark"></span></label></td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="g1" value="ไม่มี"><span class="table-checkmark"></span></label></td>
                                        <td><textarea name="g1_evidence" rows="2" placeholder="ระบุหลักฐาน..."></textarea></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center font-bold">G2</td>
                                        <td class="text-justify font-medium">มีการสื่อสารข้อกำหนดการควบคุมคุณภาพข้อมูลให้ผู้เกี่ยวข้องตลอดกระบวนการทำงานหรือไม่</td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="g2" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label></td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="g2" value="มีบางส่วน"><span class="table-checkmark"></span></label></td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="g2" value="ไม่มี"><span class="table-checkmark"></span></label></td>
                                        <td><textarea name="g2_evidence" rows="2" placeholder="ระบุหลักฐาน..."></textarea></td>
                                    </tr>

                                    <tr>
                                        <td class="text-center font-bold">P1</td>
                                        <td class="text-justify font-medium">มีนโยบายและแนวปฏิบัติด้านข้อมูลที่เกี่ยวข้องกับการรวบรวม การบันทึก ตลอดวงจรชีวิตข้อมูลหรือไม่</td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="p1" value="มีอย่างเหมาะสม"><span class="table-checkmark"></span></label></td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="p1" value="มีบางส่วน"><span class="table-checkmark"></span></label></td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="p1" value="ไม่มี"><span class="table-checkmark"></span></label></td>
                                        <td><textarea name="p1_evidence" rows="2" placeholder="ระบุหลักฐาน..."></textarea></td>
                                    </tr>
                                    
                                    <tr style="background-color: var(--moc-gold-light);">
                                        <td class="text-center font-bold">R1</td>
                                        <td class="text-justify font-medium">มีการรวบรวมขอบเขตที่มีความเสี่ยงระดับปานกลาง และระดับสูง ไว้ในการบริหารจัดการความเสี่ยงของแผนการให้บริการในปัจจุบันหรือไม่</td>
                                        <td class="text-center"><label class="table-radio"><input type="radio" name="r1" value="ใช่"><span class="table-checkmark"></span></label></td>
                                        <td class="text-center" colspan="2"><label class="table-radio"><input type="radio" name="r1" value="ไม่ใช่"><span class="table-checkmark"></span></label></td>
                                        <td><textarea name="r1_evidence" rows="2" placeholder="ระบุ Action Plan หรือหลักฐาน..."></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>

                    <div class="form-navigation no-print">
                        <button type="button" class="btn btn-secondary prev-step-btn"><i data-lucide="chevron-left"></i> ย้อนกลับ</button>
                        <button type="submit" form="dqa-form" class="btn btn-primary" id="btn-submit-footer">
                            ส่งข้อมูลและสิ้นสุดการประเมิน <i data-lucide="send"></i>
                        </button>
                    </div>
                </div>

            </form>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // --- 1. ระบบควบคุมการสลับหน้า (Multi-step Form) ---
        const steps = document.querySelectorAll(".form-step");
        const nextBtns = document.querySelectorAll(".next-step-btn");
        const prevBtns = document.querySelectorAll(".prev-step-btn");
        let currentStep = 0;

        function showStep(index) {
            steps.forEach((step, idx) => {
                step.classList.toggle("active", idx === index);
            });
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        nextBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                if (currentStep < steps.length - 1) {
                    currentStep++;
                    showStep(currentStep);
                }
            });
        });

        prevBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                if (currentStep > 0) {
                    currentStep--;
                    showStep(currentStep);
                }
            });
        });


        // --- 2. ระบบคำนวณกราฟใยแมงมุม (Radar Chart Logic จากหน้า 4) ---
        const dimensions = [
            { label: 'ความถูกต้อง\nและสมบูรณ์', labelFull: 'ความถูกต้อง และสมบูรณ์', color: '#3b82f6', names: ['sa_1_1', 'sa_1_2', 'sa_1_3', 'sa_1_4', 'sa_1_5'] },
            { label: 'ความสอดคล้องกัน', labelFull: 'ความสอดคล้องกัน', color: '#f59e0b', names: ['sa_2_1', 'sa_2_2', 'sa_2_3', 'sa_2_4', 'sa_2_5', 'sa_2_6'] },
            { label: 'ตรงตามความ\nต้องการของผู้ใช้', labelFull: 'ตรงตามความต้องการของผู้ใช้', color: '#10b981', names: ['sa_3_1', 'sa_3_2'] },
            { label: 'ความเป็นปัจจุบัน', labelFull: 'ความเป็นปัจจุบัน', color: '#8b5cf6', names: ['sa_4_1', 'sa_4_2', 'sa_4_3', 'sa_4_4'] },
            { label: 'ความพร้อมใช้', labelFull: 'ความพร้อมใช้', color: '#ef4444', names: ['sa_5_1', 'sa_5_2', 'sa_5_3', 'sa_5_4', 'sa_5_5'] }
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
                    return `<tr style="background:${rowColors[i]};">
                        <td style="padding:0.45rem 0.75rem; font-weight:600;">${d.labelFull}</td>
                        <td style="padding:0.45rem 0.75rem; text-align:center;">
                            <span style="background:${clr}22; color:${clr}; font-weight:700; padding:0.15rem 0.5rem; border-radius:99px;">${display}</span>
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
            }

            const chartData = scores.map(s => s !== null ? parseFloat(s.toFixed(2)) : 0);

            if (!radarChart) {
                const ctx = document.getElementById('radarChart').getContext('2d');
                radarChart = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: [['ความถูกต้อง', 'และสมบูรณ์'], ['ตรงตามความต้องการ', 'ของผู้ใช้'], ['ความสอดคล้องกัน'], ['ความเป็นปัจจุบัน'], ['ความพร้อมใช้']],
                        datasets: [{
                            data: [chartData[0], chartData[2], chartData[1], chartData[3], chartData[4]],
                            backgroundColor: 'rgba(59, 130, 246, 0.15)',
                            borderColor: '#3b82f6',
                            borderWidth: 2.5
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: { r: { min: 0, max: 4, ticks: { stepSize: 1 } } },
                        plugins: { legend: { display: false } }
                    }
                });
            } else {
                radarChart.data.datasets[0].data = [chartData[0], chartData[2], chartData[1], chartData[3], chartData[4]];
                radarChart.update();
            }
        }

        document.addEventListener('change', function(e) {
            if (e.target && e.target.type === 'radio' && e.target.name.startsWith('sa_')) {
                updateRadar();
            }
        });
    });
    </script>

    <script src="assets/js/app.js"></script>
</body>
</html>