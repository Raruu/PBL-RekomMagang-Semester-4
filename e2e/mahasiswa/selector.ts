import { Page } from "@playwright/test";

export const SELECTORS = {
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

    // Pengajuan Form Selectors
    carousel: ".carousel",
    stepNextButton: '#btn-next',
    stepBackButton: '#btn-prev',
    step3Text: '#step-title span:has-text("Langkah 3:")',
    
    uploadCVLink: 'a:has-text("Dokumen")',
    uploadCVInput: 'input[type="file"][accept*=".pdf"]',
    uploadButton: 'button:has-text("Upload")',

    catatanInput: 'textarea[name="catatan"]',
    uploadFileDokumenInput: 'input[id="dokumen_input[]"]',
    confirmButton: '#modal-yes-no #btn-true-yes-no.btn-primary',
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
