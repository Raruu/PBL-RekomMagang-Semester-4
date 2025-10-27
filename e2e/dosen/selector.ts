export const DOSEN_SELECTORS = {
    // Navigation & Layout
    navDashboard: 'a.nav-link:has-text("Dashboard")',
    navMahasiswaBimbingan: 'a.nav-link:has-text("Mahasiswa Bimbingan")',
    navProfile: 'a.nav-link:has-text("Profile")',
    navNotifikasi: 'a.nav-link:has-text("Notifikasi")',

    // Dashboard Elements
    dashboardTitle: "h1, h2, h3",
    statsCard: ".card",

    // Mahasiswa Bimbingan List
    mahasiswaList: '.table, [data-testid="mahasiswa-list"]',
    mahasiswaTable: "table tbody tr",
    mahasiswaDetailButton: 'a:has-text("Detail"), button:has-text("Detail")',
    mahasiswaLogAktivitasButton:
        'a:has-text("Log Aktivitas"), button:has-text("Log Aktivitas")',

    // Mahasiswa Detail
    detailCard: ".card",
    mahasiswaName: '[data-testid="mahasiswa-name"], .mahasiswa-name',
    mahasiswaNIM: '[data-testid="mahasiswa-nim"], .mahasiswa-nim',
    perusahaanInfo: '[data-testid="perusahaan-info"], .perusahaan-info',
    dosenPembimbingInfo: '[data-testid="dosen-pembimbing"]',

    // Log Aktivitas
    logAktivitasTable: "table",
    logAktivitasRow: "table tbody tr",
    feedbackTextarea: 'textarea[name="feedback"], textarea#feedback',
    feedbackButton: 'button:has-text("Simpan"), button[type="submit"]',
    deleteFeedbackButton: 'button:has-text("Hapus")',
    exportExcelButton: 'a:has-text("Export"), button:has-text("Export")',

    // Profile Page
    profileForm: "form",
    profileName: 'input[name="nama"], input#nama',
    profileNIP: 'input[name="nip"], input#nip',
    profileEmail: 'input[name="email"], input#email',
    profilePhone: 'input[name="no_hp"], input#no_hp',
    editProfileButton: 'a:has-text("Edit"), button:has-text("Edit")',
    saveProfileButton: 'button:has-text("Simpan"), button[type="submit"]',

    // Password Change
    currentPasswordInput:
        'input[name="current_password"], input#current_password',
    newPasswordInput: 'input[name="new_password"], input#new_password',
    confirmPasswordInput:
        'input[name="new_password_confirmation"], input#confirm_password',
    changePasswordButton:
        'button:has-text("Ubah Password"), button[type="submit"]',

    // Notifications
    notificationList: '.notification-list, [data-testid="notification-list"]',
    notificationItem: ".notification-item",
    markAsReadButton: 'button:has-text("Tandai Dibaca")',

    // Common Elements
    submitButton: 'button[type="submit"]',
    cancelButton: 'button:has-text("Batal"), a:has-text("Batal")',
    successMessage: ".alert-success, .swal2-success",
    errorMessage: ".alert-danger, .swal2-error",
    loadingSpinner: ".spinner-border, .loading",
};

export const getMahasiswaRow = (index: number) =>
    `${DOSEN_SELECTORS.mahasiswaTable}:nth-child(${index + 1})`;

export const getLogAktivitasRow = (index: number) =>
    `${DOSEN_SELECTORS.logAktivitasRow}:nth-child(${index + 1})`;

export const DOSEN_ROUTES = {
    dashboard: "/dosen",
    notifikasi: "/dosen/notifikasi",
    mahasiswaBimbingan: "/dosen/mahasiswabimbingan",
    profile: "/dosen/profile",
    editProfile: "/dosen/profile/edit",
};
