export const SELECTORS = {
    lowonganWrapper: "#magangTable_wrapper",
    lowonganItem: `.card-body`,
    ajukanButton: '.btn:has-text("Ajukan Magang")',
    infoCard: ".perusahaan_info_1",
};

export const getLowonganItem = (sudahDiajukan = true) => {
    const base = SELECTORS.lowonganItem;

    return sudahDiajukan
        ? `${base} span.badge.bg-warning:has-text("Sudah Diajukan")`
        : `${base}:not(:has(> span.badge.bg-warning:has-text("Sudah Diajukan")))`;
};
