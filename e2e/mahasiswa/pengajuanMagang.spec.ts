import {
    test as base,
    expect,
    type Page,
    type Locator,
} from "@playwright/test";
import {
    BASE_URL,
    FILE_PATHS,
    getUrlWithBase,
    MAHASISWA_CREDENTIALS,
} from "../fixtures/constants";
import { AUTH_SELECTORS } from "../auth/selector";
import { MHS_LAYOUT_SELECTORS } from "../fixtures/mhsLayoutSelectors";
import { getLowonganItem, SELECTORS } from "./selector";
import { getTagifyItem } from "../fixtures/utils";

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

test.describe("MHS-0004 - Sebagai Mahasiswa saya bisa mengajukan magang", () => {
    // MVP saja
    test("TC_MH001_001 - [Positif] Menampilkan daftar rekomendasi magang", async ({
        pageWithLogin,
    }) => {
        const page = pageWithLogin;
        await page.click(MHS_LAYOUT_SELECTORS.lowongan);
        await expect(page).toHaveURL(getUrlWithBase("/mahasiswa/magang"));
        await expect(page.locator(SELECTORS.lowonganWrapper)).toBeVisible();
    });

    test("TC_MH002_001 - [Positif] Melihat detail informasi, saat mahasiswa belum mengajukan ke lowongan tersebut", async ({
        pageWithLogin,
    }) => {
        const page = pageWithLogin;
        await page.click(MHS_LAYOUT_SELECTORS.lowongan);
        const firstItem = getLowonganItem({
            page: page,
            prefix: SELECTORS.lowonganWrapper,
            sudahDiajukan: false,
        }).first();
        await firstItem.waitFor({ state: "visible", timeout: 10000 });

        await Promise.all([
            page.waitForURL(/lowongan.*detail|detail.*lowongan/i),
            firstItem.click(),
        ]);

        const infoCard = page.locator(SELECTORS.infoCard);
        await infoCard.waitFor({ state: "visible", timeout: 10000 });
        await expect(infoCard.locator(SELECTORS.ajukanButton)).toBeVisible();
    });

    test("TC_MH002_002 - [Positif] Melihat detail informasi, saat mahasiswa sudah mengajukan ke lowongan tersebut", async ({
        pageWithLogin,
    }) => {
        const page = pageWithLogin;
        await page.click(MHS_LAYOUT_SELECTORS.lowongan);

        await page.locator(SELECTORS.fShow).selectOption("100");
        await page.locator(SELECTORS.fTipe).selectOption("Semua");

        const firstItem = getLowonganItem({
            page: page,
            prefix: SELECTORS.lowonganWrapper,
            sudahDiajukan: true,
        }).first();
        await firstItem.waitFor({ state: "visible", timeout: 50000 });

        await Promise.all([
            page.waitForURL(/lowongan.*detail|detail.*lowongan/i),
            firstItem.click(),
        ]);

        const infoCard = page.locator(SELECTORS.infoCard);
        await infoCard.waitFor({ state: "visible", timeout: 10000 });
        await expect(infoCard.locator(SELECTORS.pengajuanButton)).toBeVisible();
    });

    test("TC_MH001F_007 - [Positif] Memfilter lowongan magang dengan multiple field", async ({
        pageWithLogin,
    }) => {
        const page = pageWithLogin;
        await page.click(MHS_LAYOUT_SELECTORS.lowongan);
        await expect(page).toHaveURL(getUrlWithBase("/mahasiswa/magang"));
        await expect(page.locator(SELECTORS.cardControl)).toBeVisible();

        await page.locator(SELECTORS.fSearch).fill("(test)");
        await page.locator(SELECTORS.fShow).selectOption("10");
        await page.locator(SELECTORS.fTipe).selectOption("Remote");
        await page.locator(SELECTORS.fSort).selectOption("Judul (A-Z)");

        await page.locator(SELECTORS.tagify).first().click();
        await page.locator(getTagifyItem("Big Data")).click();
        await page.locator(SELECTORS.tagify).nth(1).click();
        await page.locator(getTagifyItem("Python")).click();
        // await page.locator(SELECTORS.tagify).nth(1).click();
        // await page.locator(getTagifyItem("Data Visualization")).click();
    });

    test("TC_MH002_008  - [Positif] Mengajukan magang dengan keadaan mahasiswa belum upload cv pada profil", async ({
        pageWithLogin,
    }) => {
        const page = pageWithLogin;
        await page.click(MHS_LAYOUT_SELECTORS.lowongan);
        await expect(page).toHaveURL(getUrlWithBase("/mahasiswa/magang"));
        await expect(page.locator(SELECTORS.cardControl)).toBeVisible();

        await page.locator(SELECTORS.fSearch).fill("(test)");
        await page.locator(SELECTORS.fTipe).selectOption("Semua");

        const firstItem = getLowonganItem({
            page: page,
            prefix: SELECTORS.lowonganWrapper,
            sudahDiajukan: false,
        }).first();
        await firstItem.waitFor({ state: "visible", timeout: 30000 });

        await Promise.all([
            page.waitForURL(/lowongan.*detail|detail.*lowongan/i),
            firstItem.click(),
        ]);

        const infoCard = page.locator(SELECTORS.infoCard);
        await infoCard.waitFor({ state: "visible", timeout: 10000 });
        await Promise.all([
            infoCard.locator(SELECTORS.ajukanButton).click(),
            // Wait for any lowongan id (\d+) followed by /ajukan
            page.waitForURL(/\/mahasiswa\/magang\/lowongan\/\d+\/ajukan/),
        ]);

        await expect(page.locator(SELECTORS.carousel).first()).toBeVisible();
        const nextBtn = page.locator(`${SELECTORS.stepNextButton}:visible`);
        await nextBtn.click();

        const linkToCv = page
            .locator(`${SELECTORS.carousel} ${SELECTORS.uploadCVLink}`)
            .first();

        if (await linkToCv.isVisible()) {
            await expect(nextBtn).toBeDisabled();

            await Promise.all([
                linkToCv.click(),
                page.waitForURL(
                    /\/mahasiswa\/dokumen\?on_complete=.*\/mahasiswa\/magang\/lowongan\/\d+\/ajukan/
                ),
            ]);

            await page
                .locator(SELECTORS.uploadCVInput)
                .setInputFiles(FILE_PATHS.CV);

            await page.locator(SELECTORS.uploadButton).click();

            const swalBtn = page.locator(SELECTORS.swalConfirm).first();
            await expect(swalBtn).toBeVisible();
            await Promise.all([
                swalBtn.click(),
                page.waitForURL(
                    /\/mahasiswa\/magang\/lowongan\/\d+\/ajukan\?page=2/
                ),
            ]);
        }

        // page.on("console", (msg) => {
        //     console.log(`BROWSER LOG: ${msg.type()} - ${msg.text()}`);
        // });
        await page
            .locator(SELECTORS.catatanInput)
            .fill("Saya sangat berminat dengan magang ini");
        const uploadInput = page
            .locator(SELECTORS.uploadFileDokumenInput)
            .first();
        // Set data-documentName attribute safely within the element's context
        await uploadInput.evaluate((el) =>
            el.setAttribute("data-documentName", "KTP")
        );
        await uploadInput.setInputFiles(FILE_PATHS.KTP);
        await uploadInput.evaluate((el) =>
            el.setAttribute(
                "data-documentName",
                "Surat Keterangan Mahasiswa Aktif"
            )
        );
        await uploadInput.setInputFiles(FILE_PATHS.SKK);
        await uploadInput.evaluate((el) => {
            // console.log(document.querySelector("#file-input-group")!.children.length);
            return el.setAttribute("data-documentName", "Transkrip Nilai");
        });
        await uploadInput.setInputFiles(FILE_PATHS.TRANSKRIP_NILAI);

        await new Promise((resolve) => setTimeout(resolve, 1000));
        await nextBtn.click();
        await new Promise((resolve) => setTimeout(resolve, 1000));
        await nextBtn.click();

        await expect(
            page.locator(SELECTORS.ajukanConfirmButton).first()
        ).toBeVisible();
        await page.locator(SELECTORS.ajukanConfirmButton).click();
        await page.waitForLoadState("networkidle");
        await expect(page.locator(SELECTORS.swalConfirm)).toBeVisible({
            timeout: 60000,
        });
    });
});
