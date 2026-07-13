<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="มิติคุณภาพข้อมูล (Accuracy and Completeness) - DQA Checklist">
    <title>แบบตรวจประเมินคุณภาพ (DQA Checklist) (3)</title>
    
    <!-- Google Fonts - Sarabun -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&family=Outfit:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Top Decorative Line -->
    <div class="top-bar-accent"></div>

    <div class="container">
        <!-- Official Headings -->
        <header class="form-header no-print">
            <div class="logo-wrapper">
                <div class="gov-seal">
                    <i data-lucide="award"></i>
                </div>
                <div class="title-group">
                    <h1>แบบตรวจประเมินคุณภาพ (DQA Checklist)</h1>
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
            <!-- Form Start -->
            <form id="dqa-form" method="POST" action="api.php">
                
                <!-- Field: ชื่อข้อมูล -->
                <div class="form-group mb-4">
                    <label for="info-title" class="text-bold">ชื่อข้อมูล :</label>
                    <textarea id="info-title" name="info_title" rows="2" placeholder="กรอกชื่อข้อมูลหรือชื่อชุดข้อมูล..."></textarea>
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
                                <td class="text-justify font-medium">มีการกำหนดค่าส่วนเกินของความผิดพลาดที่รับได้สำหรับแผนงานการตัดสินใจ/ประมวลผลหรือไม่</td>
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
                            <!-- RE5* -->
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
            </form>

            <!-- Navigation Footer -->
            <div class="form-navigation no-print">
                <a href="index.php" class="btn btn-secondary">
                    <i data-lucide="chevron-left"></i> ย้อนกลับ: รายงานตรวจประเมิน
                </a>
                <a href="page4.php" class="btn btn-primary" id="btn-next-page">
                    หน้าถัดไป (แบบประเมินตนเอง) <i data-lucide="chevron-right"></i>
                </a>
            </div>
        </main>
    </div>

    <!-- Custom Script -->
    <script src="app.js"></script>
</body>
</html>
