import { test as base, expect, type Page } from "@playwright/test";
import {
    BASE_URL,
    getUrlWithBase,
    MAHASISWA_CREDENTIALS,
} from "../fixtures/constants";
import { AUTH_SELECTORS } from "../auth/selector";
import { MHS_LAYOUT_SELECTORS } from "../fixtures/mhsLayoutSelectors";
import { getLowonganItem, SELECTORS } from "./selector";

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

/* ---------------------------------------------------------
 * TEST CASES: Mahasiswa - Pengajuan Magang
 * --------------------------------------------------------- */
test.describe("Mahasiswa - Pengajuan Magang", () => {
    test("TC_MH001_001 - Verifikasi proses rekomendasi magang", async ({
        pageWithLogin,
    }) => {
        const page = pageWithLogin;
        await page.click(MHS_LAYOUT_SELECTORS.lowongan);
        await expect(page).toHaveURL(getUrlWithBase("/mahasiswa/magang"));
        await expect(page.locator(SELECTORS.lowonganWrapper)).toBeVisible();
    });

    test("TC_MH002_001 - Verifikasi detail magang - belum mengajukan", async ({
        pageWithLogin,
    }) => {
        const page = pageWithLogin;
        await page.click(MHS_LAYOUT_SELECTORS.lowongan);
        const firstItem = page
            .locator(`${SELECTORS.lowonganWrapper} ${getLowonganItem(false)}`)
            .first();
        await firstItem.waitFor({ state: "visible", timeout: 10000 });

        await Promise.all([
            page.waitForURL(/lowongan.*detail|detail.*lowongan/i),
            firstItem.click(),
        ]);

        const infoCard = page.locator(SELECTORS.infoCard);
        await infoCard.waitFor({ state: "visible", timeout: 10000 });
        await expect(infoCard.locator(SELECTORS.ajukanButton)).toBeVisible();
    });
});
