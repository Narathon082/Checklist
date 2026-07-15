// MOC DQA Checklist Client Script

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('dqa-form');
    const btnSave = document.getElementById('btn-save');
    const btnPrint = document.getElementById('btn-print');
    const btnReset = document.getElementById('btn-reset');
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');

    // Load data from Server (api.php)
    async function loadData() {
        try {
            const response = await fetch('api.php');
            if (response.ok) {
                const data = await response.json();
                populateForm(data);
            } else {
                console.error('Failed to load data from server.');
                // Fallback to local storage draft if server fails
                loadLocalDraft();
            }
        } catch (error) {
            console.error('Error fetching data:', error);
            loadLocalDraft();
        }
    }

    // Populate form inputs with loaded data
    function populateForm(data) {
        if (!data) return;

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
    }

    // Load draft from LocalStorage
    function loadLocalDraft() {
        const draft = localStorage.getItem('dqa_checklist_draft');
        if (draft) {
            try {
                const data = JSON.parse(draft);
                populateForm(data);
                showToast('โหลดข้อมูลแบบร่างชั่วคราวจากเบราว์เซอร์แล้ว');
            } catch (e) {
                console.error('Error parsing local draft:', e);
            }
        }
    }

    // Serialize form data to a plain JavaScript object
    function getFormData() {
        const formData = new FormData(form);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });
        return data;
    }

    // Save data to Server (api.php)
    async function saveToServer() {
        const data = getFormData();
        
        try {
            const response = await fetch('api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                const result = await response.json();
                showToast(result.message || 'บันทึกข้อมูลเรียบร้อยแล้ว');
                // Clear local storage draft upon successful server save
                localStorage.removeItem('dqa_checklist_draft');
            } else {
                throw new Error('Server response not OK');
            }
        } catch (error) {
            console.error('Error saving data:', error);
            // Save locally if server save fails
            localStorage.setItem('dqa_checklist_draft', JSON.stringify(data));
            showToast('⚠️ ไม่สามารถบันทึกไปยังเซิร์ฟเวอร์ได้ ทำการบันทึกแบบร่างไว้ในเบราว์เซอร์แทน');
        }
    }

    // Auto-save draft locally and to server on input change
    let autoSaveTimeout;
    function setupAutoDraft() {
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            const eventType = (input.type === 'radio' || input.tagName === 'SELECT') ? 'change' : 'input';
            input.addEventListener(eventType, () => {
                const data = getFormData();
                localStorage.setItem('dqa_checklist_draft', JSON.stringify(data));
                
                // Debounce server save by 1.5 seconds to prevent spamming
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    saveToServer();
                }, 1500);
            });
        });
    }

    // Show custom toast notification
    function showToast(message) {
        toastMessage.textContent = message;
        toast.classList.remove('hide');
        
        setTimeout(() => {
            toast.classList.add('hide');
        }, 3000);
    }

    // Form validation before page navigation or print
    function validateCurrentPage() {
        if (!form) return true;
        
        const oldErrors = form.querySelectorAll('.input-error, .row-error, .card-error');
        oldErrors.forEach(el => {
            el.classList.remove('input-error');
            el.classList.remove('row-error');
            el.classList.remove('card-error');
        });

        let isValid = true;
        const errors = [];

        // 1. Validate Required Text / Select / Date Fields
        const requiredFields = [
            { id: 'info-title', name: 'ชื่อข้อมูล' },
            { id: 'metric-name', name: 'ชื่อตัวชี้วัดผลการประเมินคุณภาพข้อมูล' },
            { id: 'metric-source', name: 'แหล่งที่มาข้อมูล' },
            { id: 'info-agency', name: 'ชื่อหน่วยงานที่ดำเนินงาน' },
            { id: 'info-service', name: 'บริการ' },
            { id: 'info-head', name: 'หัวหน้า กอง/สำนัก/ฝ่าย/ศูนย์ และ/หรือ บริการ' },
            { id: 'eval-date', name: 'วันที่ประเมิน' },
            { id: 'control-date', name: 'วันที่ประเมินผลควบคุม (Page 5)' }
        ];

        requiredFields.forEach(field => {
            const el = document.getElementById(field.id);
            if (el && !el.value.trim()) {
                el.classList.add('input-error');
                isValid = false;
                if (!errors.includes(field.name)) {
                    errors.push(field.name);
                }
            }
        });

        // 2. Validate Radio Groups
        const radioGroups = {};
        const radios = form.querySelectorAll('input[type="radio"]');
        radios.forEach(radio => {
            radioGroups[radio.name] = true;
        });

        let unselectedCount = 0;
        Object.keys(radioGroups).forEach(name => {
            const checked = form.querySelector(`input[name="${name}"]:checked`);
            if (!checked) {
                isValid = false;
                unselectedCount++;
                const firstRadio = form.querySelector(`input[name="${name}"]`);
                if (firstRadio) {
                    const tr = firstRadio.closest('tr');
                    if (tr) {
                        tr.classList.add('row-error');
                    } else {
                        const card = firstRadio.closest('.self-assess-card');
                        if (card) {
                            card.classList.add('card-error');
                        }
                    }
                }
            }
        });

        if (unselectedCount > 0) {
            errors.push(`แบบประเมินยังตอบไม่ครบอีก ${unselectedCount} ข้อ (แถวหรือกล่องสีแดง)`);
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

    // Show beautiful custom modal alert
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
                    <div class="dqa-modal-title">ข้อมูลยังไม่ครบถ้วน</div>
                </div>
                <div class="dqa-modal-body">
                    <div class="dqa-modal-desc">กรุณากรอกข้อมูลทั่วไปและทำแบบประเมินให้ครบถ้วน:</div>
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

        if (window.lucide) {
            window.lucide.createIcons();
        }

        const closeBtn = overlay.querySelector('#dqa-modal-close-btn');
        if (closeBtn) {
            closeBtn.focus();
            closeBtn.addEventListener('click', () => {
                overlay.remove();
            });
        }

        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.remove();
            }
        });
    }

    // Show beautiful custom modal success
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
                    <div class="dqa-modal-title" style="color: #065f46;">ส่งข้อมูลสำเร็จแล้ว!</div>
                </div>
                <div class="dqa-modal-body" style="text-align: center; padding-top: 0; padding-bottom: 1.5rem;">
                    <p style="font-size: 1rem; font-weight: 600; color: var(--text-dark); margin-bottom: 0.5rem;">
                        บันทึกและส่งข้อมูลแบบตรวจประเมินคุณภาพข้อมูลเรียบร้อยแล้ว
                    </p>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0;">
                        ข้อมูลของคุณได้รับการบันทึกอย่างปลอดภัยลงระบบเซิร์ฟเวอร์เสร็จสมบูรณ์
                    </p>
                </div>
                <div class="dqa-modal-footer" style="justify-content: center; background-color: #ffffff;">
                    <a href="new.php" class="btn btn-primary" style="padding: 0.6rem 2rem; border-radius: 8px; text-decoration: none;">
                        กลับสู่หน้าแรก
                    </a>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);

        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    // Setup event listeners
    function setupEventListeners() {
        // Save button (if exists)
        if (btnSave) {
            btnSave.addEventListener('click', async (e) => {
                e.preventDefault();
                if (validateCurrentPage()) {
                    await saveToServer();
                }
            });
        }

        // Print button (if exists)
        if (btnPrint) {
            btnPrint.addEventListener('click', async (e) => {
                if (!validateCurrentPage()) {
                    e.preventDefault();
                } else {
                    await saveToServer();
                    window.print();
                }
            });
        }

        // Next Page button (if exists)
        const btnNext = document.getElementById('btn-next-page');
        if (btnNext) {
            btnNext.addEventListener('click', async (e) => {
                e.preventDefault();
                if (validateCurrentPage()) {
                    await saveToServer();
                    window.location.href = btnNext.getAttribute('href');
                }
            });
        }

        // Navigation links in footer (Back buttons)
        const navLinks = document.querySelectorAll('.form-navigation a');
        navLinks.forEach(link => {
            link.addEventListener('click', async (e) => {
                if (link.id === 'btn-next-page') return; // Handled separately above
                e.preventDefault();
                await saveToServer();
                window.location.href = link.getAttribute('href');
            });
        });

        // Reset button (if exists)
        if (btnReset) {
            btnReset.addEventListener('click', async () => {
                if (confirm('คุณต้องการล้างข้อมูลในฟอร์มทั้งหมดใช่หรือไม่? ข้อมูลประเมินที่เคยเซฟไว้ในระบบทั้งหมดจะหายไป')) {
                    form.reset();
                    localStorage.removeItem('dqa_checklist_draft');
                    try {
                        const response = await fetch('api.php?reset=1', { method: 'POST' });
                        if (response.ok) {
                            const result = await response.json();
                            showToast(result.message || 'ล้างข้อมูลทั้งหมดเรียบร้อยแล้ว');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }
                    } catch (e) {
                        console.error('Error resetting data:', e);
                    }
                }
            });
        }

        // Sync date text input helper if date picker value is modified (if exists)
        const evalDateInput = document.getElementById('eval-date');
        const evalDateTextInput = document.getElementById('eval-date-text');

        if (evalDateInput) {
            evalDateInput.addEventListener('change', (e) => {
                if (e.target.value) {
                    const dateParts = e.target.value.split('-'); // YYYY-MM-DD
                    if (dateParts.length === 3) {
                        const year = parseInt(dateParts[0]) + 543; // Buddhist Era
                        const month = dateParts[1];
                        const day = dateParts[2];
                        
                        // Simple conversion array
                        const monthsThai = [
                            'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
                            'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
                        ];
                        const monthText = monthsThai[parseInt(month) - 1];
                        
                        if (evalDateTextInput) {
                            evalDateTextInput.value = `${parseInt(day)} ${monthText} ${year}`;
                        }
                    }
                }
            });
        }

        // Form submit handler (specifically for Page 5)
        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (validateCurrentPage()) {
                    await saveToServer();
                    showCustomSuccessModal();
                }
            });
        }
    }

    // Init process
    setupEventListeners();
    loadData();
    setupAutoDraft();
    
    // Render Lucide Icons
    lucide.createIcons();
});
