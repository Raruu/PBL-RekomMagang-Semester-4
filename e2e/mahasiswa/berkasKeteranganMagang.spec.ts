import { Page, test as base, expect } from "@playwright/test";
import { AUTH_SELECTORS } from "../auth/selector";
import {
    FILE_PATHS,
    getUrlWithBase,
    MAHASISWA_CREDENTIALS,
} from "../fixtures/constants";
import { MHS_LAYOUT_SELECTORS } from "../fixtures/mhsLayoutSelectors";
import { SELECTORS, getLowonganItem, getEmojiByValue } from "./selector";

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

        await use(page);
    },
});

test.describe("MHS-0006 - Sebagai Mahasiswa saya bisa melakukan tahapan terakhir dari magang (upload berkas keterangan magang)", () => {
    // MVP saja
    test("TC_MH005_001 - Upload dengan file yang valid", async ({
        pageWithLogin,
    }) => {
        const page = pageWithLogin;
        await page.click(MHS_LAYOUT_SELECTORS.pengajuan);
        await page.locator(SELECTORS.fSearch).fill("#3");

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

        await page.locator(SELECTORS.tabSuratKetMagang).click();
        const inputSertif = page.locator(SELECTORS.inputFileSertif);
        await expect(inputSertif).toBeVisible();

        await inputSertif.setInputFiles(FILE_PATHS.KETERANGAN_MAGANG);
        const uploadButton = page.locator(SELECTORS.uploadFileSertifButton);
        await expect(uploadButton).toBeEnabled();
        await uploadButton.click();
        await expect(page.locator(SELECTORS.swalConfirm)).toBeVisible();
    });

    test("TC_MH004_002 - [Negatif] Mengisi feedback magang dengan field wajib yang tidak diisi", async ({
        pageWithLogin,
    }) => {
        const page = pageWithLogin;
        await page.click(MHS_LAYOUT_SELECTORS.pengajuan);
        await page.locator(SELECTORS.fSearch).fill("#3");

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

        await page.locator(SELECTORS.tabFeedbackMagang).click();
        await expect(page.locator(SELECTORS.feedbackForm)).toBeVisible();
        await new Promise((resolve) => setTimeout(resolve, 1000));
        await page.locator(SELECTORS.submitButton).click();
        await page.waitForResponse((res) => res.status() === 422);
        await expect(page.locator(SELECTORS.komentarError)).toBeVisible({
            timeout: 10000,
        });
    });

    test("TC_MH004_004 - [Positif] Menyelesaikan status magang dengan cara melengkapi feedback magang", async ({
        pageWithLogin,
    }) => {
        const page = pageWithLogin;
        await page.click(MHS_LAYOUT_SELECTORS.pengajuan);
        await page.locator(SELECTORS.fSearch).fill("#3");

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

        await page.locator(SELECTORS.tabFeedbackMagang).click();
        await expect(page.locator(SELECTORS.feedbackForm)).toBeVisible();

        await getEmojiByValue(page, 5).click();
        await expect(page.locator(SELECTORS.ratingLabel)).toHaveText(
            /Rating:\s*5/
        );
        await page.locator(SELECTORS.ratingInput).fill("5");
        await page.fill(SELECTORS.komentarInput, "Ceo-nya ramah bintang 5");
        await page.fill(
            SELECTORS.pengalamanBelajarInput,
            "Memberikan pengalaman yang bagus"
        );
        await page.fill(SELECTORS.kendalaInput, "Tidak ada");
        await page.fill(SELECTORS.saranInput, "Tempat parkirnya diperluas");
        await new Promise((resolve) => setTimeout(resolve, 1000));
        await page.locator(SELECTORS.submitButton).click();
        await page.waitForLoadState("networkidle");
        await expect(page.locator(SELECTORS.swalConfirm)).toBeVisible({
            timeout: 10000,
        });
    });
});
