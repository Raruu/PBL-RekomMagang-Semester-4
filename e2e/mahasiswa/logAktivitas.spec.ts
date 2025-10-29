import { test as base, expect, Page } from "@playwright/test";
import { getUrlWithBase, MAHASISWA_CREDENTIALS } from "../fixtures/constants";
import { AUTH_SELECTORS } from "../auth/selector";
import { MHS_LAYOUT_SELECTORS } from "../fixtures/mhsLayoutSelectors";
import { getLowonganItem, SELECTORS } from "./selector";

// US MHS-0005: Sebagai Mahasiswa saya bisa melakukan monitoring magang

const test = base.extend<{ pageWithLogin: Page }>({
    pageWithLogin: async ({ page }, use) => {
        await page.goto(getUrlWithBase("/login"));
        await page.waitForSelector(AUTH_SELECTORS.usernameInput, {
            state: "visible",
        });
        await page.waitForSelector(AUTH_SELECTORS.passwordInput, {
            state: "visible",
        });

        const usernameInput = page.locator(AUTH_SELECTORS.usernameInput);
        const passwordInput = page.locator(AUTH_SELECTORS.passwordInput);

        await usernameInput.click();
        await usernameInput.fill(MAHASISWA_CREDENTIALS.username);

        await passwordInput.click();
        await passwordInput.fill(MAHASISWA_CREDENTIALS.password);

        await Promise.all([
            page.waitForURL(getUrlWithBase("/mahasiswa")),
            page.click(AUTH_SELECTORS.submitButton),
        ]);

        await page.click(MHS_LAYOUT_SELECTORS.pengajuan);
        await page.locator(SELECTORS.fSearch).fill("#4");

        const firstItem = getLowonganItem({
            page,
            prefix: SELECTORS.pengajuanWrapper,
            sudahDiajukan: false,
        }).first();
        await firstItem.waitFor({ state: "visible", timeout: 30000 });

        await Promise.all([
            page.waitForURL(
                /mahasiswa\/magang\/pengajuan\/\d+\/detail\?backable=true/
            ),
            firstItem.click(),
        ]);

        await use(page);
    },
});

test.describe("TC_MH003 - Menampilkan log aktivitas", () => {
    test("TC_MH003_001 - [Positif] Menampilkan log aktivitas", async ({
        pageWithLogin,
    }) => {
        const page = pageWithLogin;
        await page.locator(SELECTORS.gotoLogAktivitas).click();
        await page.waitForURL(
            /mahasiswa\/magang\/pengajuan\/log-aktivitas\/\d+/
        );
        await expect(
            page.locator(SELECTORS.timeLineContent).first()
        ).toBeVisible();
    });

    test("TC_MH003_002 - [Positif] Menambahkan log aktivitas dengan data yang valid", async ({
        pageWithLogin,
    }) => {
        const page = pageWithLogin;
        await page.locator(SELECTORS.gotoLogAktivitas).click();
        await page.waitForURL(
            /mahasiswa\/magang\/pengajuan\/log-aktivitas\/\d+/
        );

        const btnAdd = page.locator(SELECTORS.timeLineAdd);
        await expect(btnAdd).toBeVisible();
        await btnAdd.click();

        const modal = page.locator(SELECTORS.timeLineModal);
        await expect(modal).toBeVisible();

        const formAktivitas = page.locator(
            `${SELECTORS.timeLineModal} ${SELECTORS.formAktivitas}`
        );
        const formkendala = page.locator(
            `${SELECTORS.timeLineModal} ${SELECTORS.formkendala}`
        );
        const formSolusi = page.locator(
            `${SELECTORS.timeLineModal} ${SELECTORS.formSolusi}`
        );
        const formTanggalLog = page.locator(
            `${SELECTORS.timeLineModal} ${SELECTORS.formTanggalLog}`
        );
        const formJamKegiatan = page.locator(
            `${SELECTORS.timeLineModal} ${SELECTORS.formJamKegiatan}`
        );
        await formAktivitas.fill("Melakukan testing dan debugging kode");
        await formkendala.fill("Perbedaan waktu kerja dengan tim");
        await formSolusi.fill("Diskusi dengan mentor dan tim");
        await formTanggalLog.fill("2019-10-01");
        await formJamKegiatan.fill("12:05:00");

        await new Promise((resolve) => setTimeout(resolve, 1000));
        await page.locator(SELECTORS.btnTrueYesNo).click();
        await page.waitForLoadState("networkidle");
        await expect(page.locator(SELECTORS.swalConfirm)).toBeVisible({
            timeout: 10000,
        });
    });

    test("TC_MH003_006 - [Positif] Mengedit log aktivitas yang sudah ada", async ({
        pageWithLogin,
    }) => {
        const page = pageWithLogin;
        await page.locator(SELECTORS.gotoLogAktivitas).click();
        await page.waitForURL(
            /mahasiswa\/magang\/pengajuan\/log-aktivitas\/\d+/
        );

        const btnEdit = page.locator(SELECTORS.timeLineEdit).first();
        await expect(btnEdit).toBeVisible();
        await btnEdit.click();

        const modal = page.locator(SELECTORS.timeLineModal);
        await expect(modal).toBeVisible();

        const formAktivitas = page.locator(
            `${SELECTORS.timeLineModal} ${SELECTORS.formAktivitas}`
        );
        const formkendala = page.locator(
            `${SELECTORS.timeLineModal} ${SELECTORS.formkendala}`
        );
        const formSolusi = page.locator(
            `${SELECTORS.timeLineModal} ${SELECTORS.formSolusi}`
        );
        const formTanggalLog = page.locator(
            `${SELECTORS.timeLineModal} ${SELECTORS.formTanggalLog}`
        );
        const formJamKegiatan = page.locator(
            `${SELECTORS.timeLineModal} ${SELECTORS.formJamKegiatan}`
        );
        await formAktivitas.fill(
            "Aktivitas: Mengikuti sesi mentoring dengan senior developer"
        );
        await formkendala.fill("Kendala: tidak ada");
        await formSolusi.fill("");
        await formTanggalLog.fill("2019-10-01");
        await formJamKegiatan.fill("12:05:00");

        await new Promise((resolve) => setTimeout(resolve, 1000));
        await page.locator(SELECTORS.btnTrueYesNo).click();
        await page.waitForLoadState("networkidle");
        await expect(page.locator(SELECTORS.swalConfirm)).toBeVisible({
            timeout: 10000,
        });
    });

    test("TC_MH003_005 - [Negatif] Menambahkan log aktivitas dengan tidak mengisi field yang bersifat wajib ", async ({
        pageWithLogin,
    }) => {
        const page = pageWithLogin;
        await page.locator(SELECTORS.gotoLogAktivitas).click();
        await page.waitForURL(
            /mahasiswa\/magang\/pengajuan\/log-aktivitas\/\d+/
        );

        const btnAdd = page.locator(SELECTORS.timeLineAdd);
        await expect(btnAdd).toBeVisible();
        await btnAdd.click();

        const modal = page.locator(SELECTORS.timeLineModal);
        await expect(modal).toBeVisible();

        const formAktivitas = page.locator(
            `${SELECTORS.timeLineModal} ${SELECTORS.formAktivitas}`
        );
        const formkendala = page.locator(
            `${SELECTORS.timeLineModal} ${SELECTORS.formkendala}`
        );
        const formSolusi = page.locator(
            `${SELECTORS.timeLineModal} ${SELECTORS.formSolusi}`
        );
        const formTanggalLog = page.locator(
            `${SELECTORS.timeLineModal} ${SELECTORS.formTanggalLog}`
        );
        const formJamKegiatan = page.locator(
            `${SELECTORS.timeLineModal} ${SELECTORS.formJamKegiatan}`
        );
        await formAktivitas.fill("");
        await formkendala.fill("Perbedaan waktu kerja dengan tim");
        await formSolusi.fill("Diskusi dengan mentor dan tim");
        await formTanggalLog.fill("");
        await formJamKegiatan.fill("");

        await new Promise((resolve) => setTimeout(resolve, 1000));
        await page.locator(SELECTORS.btnTrueYesNo).click();
        await new Promise((resolve) => setTimeout(resolve, 1000));
        await expect(page.locator(SELECTORS.formAktivitasError)).toBeVisible();
    });
});
