export const ADMIN_SELECTORS = {
    // --- Navigasi ---
    menuMagang: "a.nav-link:has-text('Magang')",
    menuLowongan: "a.nav-link:has-text('Lowongan')",
    lokasiSelect: "#lokasi_id",

    // --- Halaman Index (index.blade.php) ---
    tambahLowonganButton: "#btn-create",
    refreshButton: "#btn-refresh",
    lowonganTable: "#lowonganMagangTable",
    tableRow: "#lowonganMagangTable tbody tr",
    tableNoData: ".dataTables_empty",
    editButton: ".edit-btn",
    deleteButton: ".delete-btn",
    viewButton: ".view-btn",
    toggleStatusButton: ".toggle-status-btn",

    // --- Halaman Create (create.blade.php) ---
    formLowongan: "#formLowongan",
    perusahaanSelect: "#perusahaan_id",
    judulLowonganInput: "#judul_lowongan",
    judulPosisiInput: "#judul_posisi",
    deskripsiTextarea: "#deskripsi",
    gajiInput: "#gaji",
    kuotaInput: "#kuota",
    tipeKerjaSelect: "#tipe_kerja_lowongan",
    batasPendaftaranInput: "#batas_pendaftaran",
    statusToggle: "#is_active",
    saveContinueButton: "#btn-save-continue",
    resetButton: "#btn-reset",
    backButton: ".btn-footer.kembali",

    // --- Halaman Lanjutan (lanjutan.blade.php) - SUDAH DIPERBAIKI ---
    formLanjutan: "#formLanjutan",
    minimumIpkInput: "#minimum_ipk",
    pengalamanToggle: "#pengalaman",
    dokumenPersyaratanTextarea: "#dokumen_persyaratan",
    deskripsiPersyaratanTextarea: "#deskripsi_persyaratan",
    keahlianContainer: "#keahlianContainer",
    keahlianItem: ".keahlian-item",
    keahlianSelect: ".keahlian-select", // Select untuk memilih keahlian
    tingkatKeahlianSelect: ".tingkat-select", // Select untuk tingkat kemampuan
    addKeahlianButton: "#addKeahlian",
    removeKeahlianButton: ".remove-keahlian", // Tombol hapus keahlian
    saveFinishButton: "#btn-save-finish",
    backToLowonganButton: "#btn-back", // Tombol kembali ke daftar lowongan
    resetLanjutanButton: "#btn-reset", // Tombol reset di halaman lanjutan

    // --- Modal Edit (dari index.blade.php) ---
    editLowonganModal: "#editLowonganModal",
    editModalForm: "#editLowonganForm",
    editModalPerusahaanSelect: "#edit_perusahaan_id",
    editModalJudulInput: "#edit_judul_lowongan",
    editModalPosisiInput: "#edit_judul_posisi",
    editModalDeskripsiTextarea: "#edit_deskripsi",
    editModalGajiInput: "#edit_gaji",
    editModalKuotaInput: "#edit_kuota",
    editModalTipeKerjaSelect: "#edit_tipe_kerja_lowongan",
    editModalBatasPendaftaranInput: "#edit_batas_pendaftaran",
    editModalStatusToggle: "#edit_is_active",
    editModalIpkInput: "#edit_minimum_ipk",
    editModalPengalamanToggle: "#edit_pengalaman",
    editModalDeskripsiPersyaratanTextarea: "#edit_deskripsi_persyaratan",
    editModalDokumenPersyaratanTextarea: "#edit_dokumen_persyaratan",

    // Keahlian di modal edit
    editModalKeahlianPemulaInput: "#keahlian-pemula",
    editModalKeahlianMenengahInput: "#keahlian-menengah",
    editModalKeahlianMahirInput: "#keahlian-mahir",
    editModalKeahlianAhliInput: "#keahlian-ahli",

    // Tombol Aksi Modal Edit
    editModalSaveButton: "#btn-true-yes-no",
    editModalCancelButton: "#btn-false-yes-no",

    // --- Modal Detail (dari index.blade.php) ---
    detailLowonganModal: "#modalDetailLowongan",
    detailJudulLowongan: "#detail-judul-lowongan",
    detailPosisi: "#detail-posisi",
    detailPerusahaan: "#detail-perusahaan",
    detailLokasi: "#detail-lokasi",
    detailTipeKerja: "#detail-tipe-kerja",
    detailBatasPendaftaran: "#detail-batas",
    detailGaji: "#detail-gaji",
    detailKuota: "#detail-kuota",
    detailStatus: "#detail-status",
    detailDeskripsi: "#detail-deskripsi",
    detailPersyaratan: "#detail-persyaratan",
    detailKeahlian: "#detail-keahlian",
    detailDokumenPersyaratan: "#detail-dokumen-persyaratan",
    showFeedbackButton: "#btn-show-feedback",

    // --- Modal Feedback ---
    feedbackModal: "#modalFeedbackMahasiswa",
    feedbackListContainer: "#feedback-list-container",
    feedbackDetailContainer: "#feedback-detail-container",
    backFeedbackButton: "#btn-back-feedback",

    // --- SweetAlert2 ---
    swalContainer: ".swal2-container",
    swalConfirm: ".swal2-confirm",
    swalCancel: ".swal2-cancel",
    swalTitle: ".swal2-title",
    swalHtml: ".swal2-html-container",
    swalIconError: ".swal2-icon.swal2-error",
    swalIconSuccess: ".swal2-icon.swal2-success",
    swalIconWarning: ".swal2-icon.swal2-warning",
    swalLoading: ".swal2-loading",

    // Navigation - Based on actual admin sidebar structure
    sidebar: "#sidebar",
    magangMenuToggle: "a.nav-link.nav-group-toggle",
    magangSubmenu: "ul.nav-group-items",
    kegiatanSubMenu: "a:has-text('Kegiatan')",
    periodeSubMenu: "a:has-text('Periode')",

    // Kegiatan Magang
    pengajuanTable: "[data-testid='pengajuan-table']",
    tableRowKegiatan: "tbody tr",
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
    savePeriodeButton: "[data-testid='save-button']",
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
