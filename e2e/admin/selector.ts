export const ADMIN_SELECTORS = {
    // Navigation - Based on actual admin sidebar structure
    sidebar: "#sidebar",
    magangMenuToggle: "a.nav-link.nav-group-toggle",
    magangSubmenu: "ul.nav-group-items",
    kegiatanSubMenu: "a:has-text('Kegiatan')",
    periodeSubMenu: "a:has-text('Periode')",

    // Kegiatan Magang
    pengajuanTable: "[data-testid='pengajuan-table']",
    tableRow: "tbody tr",
    statusColumn: "[data-testid='status-column']",
    actionButton: "[data-testid='action-button']",
    statusDropdown: "[data-testid='status-dropdown']",
    dosenDropdown: "[data-testid='dosen-dropdown']",
    catatanTextarea: "[data-testid='catatan-textarea']",
    kirimButton: "[data-testid='kirim-button']",
    confirmButton: "[data-testid='confirm-button']",
    successMessage: ".alert-success, .swal2-success",
    errorMessage: ".alert-danger, .swal2-error",
    validationError: ".invalid-feedback, .text-danger",

    // Periode Magang
    lowonganList: "[data-testid='lowongan-list']",
    addPeriodeButton: "[data-testid='add-periode-button']",
    editPeriodeButton: "[data-testid='edit-periode-button']",
    startDateInput: "[data-testid='start-date']",
    endDateInput: "[data-testid='end-date']",
    saveButton: "[data-testid='save-button']",
    cancelButton: "[data-testid='cancel-button']",

    // General UI elements
    submitButton: "button[type='submit']",
    modal: ".modal",
    modalBody: ".modal-body",
    modalFooter: ".modal-footer",
    closeModal:
        ".modal-header .btn-close, .modal-header button[data-bs-dismiss]",

    // Alternative selectors (fallback if data-testid not available)
    statusSelect: "select[name='status']",
    dosenSelect: "select[name='dosen_id']",
    catatanField: "textarea[name='catatan']",
    submitBtn: "button:has-text('Kirim')",
    confirmBtn: "button:has-text('Ya')",
    dateInput: "input[type='date']",
    saveBtn: "button:has-text('Simpan')",
};
