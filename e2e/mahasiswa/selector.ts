import { Page } from "@playwright/test";

export const SELECTORS = {
    btnTrueYesNo: "#btn-true-yes-no.btn-primary",
    lowonganWrapper: "#magangTable_wrapper",
    lowonganItem: `.card-body`,
    ajukanButton: '.btn:has-text("Ajukan Magang")',
    pengajuanButton: '.btn:has-text("Lihat Pengajuan")',
    infoCard: ".perusahaan_info_1",
    cardControl: "#card-control",
    fSearch: "#search",
    fShow: "#show-limit",
    fTipe: "#tipe-lowongan",
    fSort: "#sort-by",
    tagify: ".tagify",
    // fIndustri: ".tagify__input[placeholder~='Bidang Industri']",
    // fTag: ".tagify__input[placeholder~='Tag']",
    swalConfirm: ".swal2-confirm",
    swalTitle: ".swal2-title",
    swalHtml: ".swal2-html-container",

    // Pengajuan Form Selectors
    carousel: ".carousel",
    stepNextButton: "#btn-next",
    stepBackButton: "#btn-prev",
    step3Text: '#step-title span:has-text("Langkah 3:")',

    uploadCVLink: 'a:has-text("Dokumen")',
    uploadCVInput: 'input[type="file"][accept*=".pdf"]',
    uploadButton: 'button:has-text("Upload")',

    catatanInput: 'textarea[name="catatan_mahasiswa"]',
    uploadFileDokumenInput: 'input[id="dokumen_input[]"]',
    ajukanConfirmButton: "#modal-yes-no #btn-true-yes-no.btn-primary",

    // Pengajuan
    pengajuanWrapper: "#pengajuanTable_wrapper",
    tabSuratKetMagang: "#pengajuan-tabs a:has-text('Surat Keterangan Magang')",
    inputFileSertif: "#file_sertifikat",
    uploadFileSertifButton: "#upload-button",

    // Feedback Form
    tabFeedbackMagang: "#pengajuan-tabs a:has-text('Feedback Magang')",
    feedbackForm: "#form-feedback",
    ratingLabel: "#rating-label",
    emojiRating: ".emoji-rating",
    emojiActive: ".emoji.active",
    ratingInput: "#rating",
    ratingError: "#error-rating",
    komentarInput: "#komentar",
    komentarError: "#error-komentar",
    pengalamanBelajarInput: "#pengalaman_belajar",
    pengalamanBelajarError: "#error-pengalaman_belajar",
    kendalaInput: "#kendala",
    kendalaError: "#error-kendala",
    saranInput: "#saran",
    saranError: "#error-saran",
    submitButton: "#btn-submit",
    submitButtonText: "#btn-submit-text",
    submitButtonSpinner: "#btn-submit-spinner",

    // Log Aktivitas
    gotoLogAktivitas: "p:has-text('Log Aktivitas')",
    timeLineContent: ".timeline-content",
    timeLineAdd: ".btn-primary:has-text('Tambah Log')",
    timeLineModal: "#modal-edit",
    timeLineEdit: ".btn-outline-primary .fa-edit",

    formAktivitas: "#aktivitas",
    formkendala: "#kendala",
    formSolusi: "#solusi",
    formTanggalLog: "#tanggal_log",
    formJamKegiatan: "#jam_kegiatan",

    formAktivitasError: "#error-aktivitas",
};

export const getLowonganItem = ({
    page,
    prefix,
    sudahDiajukan = true,
}: {
    page: Page;
    prefix?: string;
    sudahDiajukan?: boolean;
}) => {
    const base = prefix
        ? page.locator(`${prefix} ${SELECTORS.lowonganItem}`)
        : page.locator(SELECTORS.lowonganItem);

    return sudahDiajukan
        ? base.filter({
              has: page.locator("span.badge.bg-warning", {
                  hasText: "Sudah Diajukan",
              }),
          })
        : base.filter({
              hasNot: page.locator("span.badge.bg-warning", {
                  hasText: "Sudah Diajukan",
              }),
          });
};

export const getEmojiByValue = (page: Page, value: 1 | 2 | 3 | 4 | 5) =>
    page.locator(`.emoji[data-value="${value}"]`);
