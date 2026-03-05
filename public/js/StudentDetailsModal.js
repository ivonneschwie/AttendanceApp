class StudentDetailsModal {
    constructor(modalId, students) {
        this.modal = document.getElementById(modalId);
        this.overlay = document.getElementById('student-modal-overlay');
        this.closeButton = document.getElementById('close-student-modal-button');
        this.studentNameEl = document.getElementById('student-name');
        this.studentIdEl = document.getElementById('student-id');
        this.qrcodeContainer = document.getElementById('qrcode-container');
        this.students = students;

        this.close = this.close.bind(this);
        this.overlay.addEventListener('click', this.close);
        this.closeButton.addEventListener('click', this.close);
    }

    open(studentUid) {
        const student = this.students[studentUid];
        if (!student) {
            console.error('Student not found:', studentUid);
            return;
        }

        this.studentNameEl.textContent = `${student.firstName} ${student.lastName}`;
        this.studentIdEl.textContent = student.schoolId;

        this.qrcodeContainer.innerHTML = '';
        QRCode.toCanvas(studentUid, { errorCorrectionLevel: 'H', width: 320 }, (error, canvas) => {
            if (error) {
                console.error(error);
                this.qrcodeContainer.textContent = 'Could not generate QR code.';
                return;
            }
            this.qrcodeContainer.appendChild(canvas);
        });

        this.modal.classList.remove('hidden');
    }

    close() {
        this.modal.classList.add('hidden');
    }

    initializeTriggers(triggerClass) {
        document.querySelectorAll(triggerClass).forEach(entry => {
            entry.addEventListener('click', (e) => {
                if (e.target.closest('form')) {
                    return;
                }
                const studentUid = entry.dataset.studentUid;
                this.open(studentUid);
            });
        });
    }
}
