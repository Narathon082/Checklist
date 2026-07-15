<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="แบบตรวจประเมินการควบคุมและติดตามคุณภาพข้อมูล (Data Quality Monitoring and Control Checklist) - DQA Checklist">
    <title>แบบตรวจประเมินการควบคุมและติดตามคุณภาพข้อมูล(Data Quality Monitoring and Control Checklist)</title>

    <!-- Google Fonts - Sarabun -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&family=Outfit:wght@500;700&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <!-- Top Decorative Line -->
    <div class="top-bar-accent"></div>

    <div class="container">
        <!-- Official Headings -->
        <header class="form-header no-print">
            <div class="logo-wrapper">
                <div class="gov-seal">
                    <img src="ops-logo.jpg" alt="OPS Logo" style="width:100%; height:100%; object-fit:contain;">
                </div>
                <div class="title-group">
                    <h1>แบบตรวจประเมินการควบคุมและติดตามคุณภาพข้อมูล<br>(Data Quality Monitoring and Control Checklist)</h1>
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

            <!-- Form Start -->
            <form id="dqa-form" method="POST" action="api.php">

                <!-- SECTION 1: ข้อมูลทั่วไป -->
                <fieldset class="form-section">
                    <legend><i data-lucide="info"></i> ข้อมูลทั่วไป</legend>

                    <div class="form-group">
                        <label for="info-title" class="text-bold">ชื่อข้อมูล : </label>
                        <textarea id="info-title" name="info_title" rows="2" placeholder="กรอกชื่อข้อมูลหรือชื่อชุดข้อมูล..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="info-agency" class="text-bold">ชื่อหน่วยงานที่ดำเนินงาน :</label>
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

                    <div class="form-group">
                        <label for="info-service" class="text-bold">บริการ :</label>
                        <textarea id="info-service" name="info_service" rows="2" placeholder="ข้อมูลสถิติการค้า"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="info-head" class="text-bold">หัวหน้า กอง/สำนัก/ฝ่าย/ศูนย์ และ/หรือ บริการ :</label>
                        <textarea id="info-head" name="info_head" rows="2" placeholder="ผู้อำนวยการศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="control-date" class="text-bold">วันที่ประเมินผลควบคุม:</label>
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
                    <legend><i data-lucide="book-open"></i> ด้านการพัฒนานโยบายและแนวปฏิบัติด้านข้อมูล</legend>

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

            </form>

            <!-- Navigation Footer -->
            <div class="form-navigation no-print">
                <a href="page4.php" class="btn btn-secondary">
                    <i data-lucide="chevron-left"></i> ย้อนกลับ: แบบประเมินตนเอง
                </a>
                <button type="submit" form="dqa-form" class="btn btn-primary" id="btn-submit-footer">
                    ส่งข้อมูลและสิ้นสุดการประเมิน <i data-lucide="send"></i>
                </button>
            </div>
        </main>
    </div>

    <!-- Custom Script -->
    <script src="app.js"></script>
</body>
</html>
