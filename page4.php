<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="แบบประเมินคุณภาพข้อมูลด้วยตนเอง (DQA Self-Assessment) - DQA Checklist">
    <title>แบบประเมินคุณภาพข้อมูลด้วยตนเอง (DQA Self-Assessment) (1)</title>
    
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
                    <h1>แบบประเมินคุณภาพข้อมูลด้วยตนเอง (DQA Self-Assessment)</h1>
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
                    <h2>(ร่าง) แบบประเมินคุณภาพข้อมูลด้วยตนเอง (DQA Self-Assessment)</h2>
                </div>
            </div>

            <!-- Form Start -->
            <form id="dqa-form" method="POST" action="api.php">
                <!-- Field: ชื่อข้อมูล -->
                <div class="form-group mb-4">
                    <label for="info-title" class="text-bold">ชื่อข้อมูล :</label>
                    <textarea id="info-title" name="info_title" rows="2" placeholder="กรอกชื่อข้อมูลหรือชื่อชุดข้อมูล..."></textarea>
                </div>

                <!-- Instruction Box -->
                <div class="instruction-box" style="margin-top: 2rem; border-left: 4px solid var(--moc-gold); line-height: 1.45; font-size: 0.95rem; text-align: left; background-color: var(--moc-gold-light); color: var(--text-dark);">
                    <strong>คำชี้แจง :</strong> แบบประเมินคุณภาพข้อมูลด้วยตนเอง มีวัตถุประสงค์ให้หน่วยงานภาครัฐใช้สำหรับประเมินคุณภาพข้อมูลภายในหน่วยงานผ่านเกณฑ์คุณภาพข้อมูลทั้ง 5 มิติ ได้แก่ ความถูกต้องและสมบูรณ์ ความสอดคล้องกัน ความเป็นปัจจุบัน ตรงตามความต้องการของผู้ใช้ และความพร้อมใช้ โดยเป็นการประเมินตนเอง (Self-assessment) เบื้องต้นเพื่อให้ทราบว่าข้อมูลภายในหน่วยงานมีคุณภาพมากน้อยเพียงใด และควรปรับปรุงหรือพัฒนาในมิติใดบ้างเพื่อให้ข้อมูลมีคุณภาพ สามารถนำไปใช้ประโยชน์เพื่อเพิ่มประสิทธิภาพในการทำงาน เพิ่มคุณค่าในการให้บริการ และต่อยอดการพัฒนาของประเทศในมิติต่าง ๆ ได้ ในการใช้งาน เจ้าของข้อมูล (Data Owner) ควรพิจารณาข้อมูลภาพรวมของหน่วยงาน ทำความเข้าใจเกณฑ์และคำอธิบาย และทำการประเมินคุณภาพข้อมูล โดยกรอกค่าคะแนนในแต่ละมิติของตัวชี้วัด (Indicators) จากนั้นระบบจะประมวลผลตามเกณฑ์ประเมินคุณภาพข้อมูลในแต่ละมิติ และจะแสดงผลในรูปแบบ Radar Graph และจัดพิมพ์แบบประเมินส่งให้ทีมผู้ประเมินเพื่อใช้ประกอบการตรวจประเมินคุณภาพข้อมูล
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
            </form>

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
                        <canvas id="radarChart" height="400"></canvas>
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

            <!-- Navigation Footer -->
            <div class="form-navigation no-print">
                <a href="page3.php" class="btn btn-secondary">
                    <i data-lucide="chevron-left"></i> ย้อนกลับ: มิติคุณภาพข้อมูล
                </a>
                <a href="page5.php" class="btn btn-primary" id="btn-next-page">
                    หน้าถัดไป (แบบตรวจการควบคุมฯ) <i data-lucide="chevron-right"></i>
                </a>
            </div>
        </main>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- Radar Chart Logic -->
    <script>
    (function () {
        // Define the 5 dimensions and their indicators (radio name patterns)
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
                names: ['sa_2_1', 'sa_2_2', 'sa_2_3', 'sa_2_4', 'sa_2_5']
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

            // Update placeholder msg
            const msg = document.getElementById('radar-placeholder-msg');
            if (msg) msg.style.display = hasData ? 'none' : 'block';

            // Update score table
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

            // Overall score
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

            // Update score legend
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

            // Chart data - fill null as 0 for chart
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
                // Order: Accuracy, Relevancy, Consistency, Timeliness, Availability
                radarChart.data.datasets[0].data = [chartData[0], chartData[2], chartData[1], chartData[3], chartData[4]];
                radarChart.update();
            }
        }

        // Listen for changes on all radio buttons
        document.addEventListener('change', function(e) {
            if (e.target && e.target.type === 'radio' && e.target.name && e.target.name.startsWith('sa_')) {
                updateRadar();
            }
        });

        // Init after data is loaded (wait for app.js populateForm)
        window.addEventListener('load', function() {
            setTimeout(updateRadar, 600);
        });
    })();
    </script>

    <!-- Custom Script -->
    <script src="app.js"></script>
</body>
</html>
