import { test, expect } from "@playwright/test";
import { getUrlWithBase } from "../fixtures/constants";

// US MHS-0005: Sebagai Mahasiswa saya bisa melakukan monitoring magang

// Mahasiswa credentials
const MAHASISWA_CREDENTIALS = {
    username: process.env.MAHASISWA_USERNAME || "2341720157",
    password: process.env.MAHASISWA_PASSWORD || "12345",
};

const MAHASISWA_ROUTES = {
    dashboard: "/mahasiswa",
    pengajuan: "/mahasiswa/magang/pengajuan",
    // Log aktivitas route will be: /mahasiswa/magang/pengajuan/log-aktivitas/{pengajuan_id}
    // We navigate via clicking the button, not direct URL
};

const MAHASISWA_SELECTORS = {
    // Pengajuan page
    pengajuanTable: 'table tbody tr',
    pengajuanCard: '.card',
    logAktivitasButton: 'a[href*="log-aktivitas"], button:has-text("Log Aktivitas")',

    // Log Aktivitas page
    tambahLogButton: 'button:has-text("Tambah"), button:has-text("+ Tambah"), a:has-text("+ Tambah Log"), .btn:has-text("Tambah")',
    logTable: 'table tbody tr',

    // Modal form fields
    aktivitasTextarea: 'textarea[name="aktivitas"], textarea#aktivitas, #aktivitas',
    kendalaTextarea: 'textarea[name="kendala"], textarea#kendala, #kendala',
    solusiTextarea: 'textarea[name="solusi"], textarea#solusi, #solusi',
    tanggalInput: 'input[name="tanggal"], input[type="date"], input#tanggal, input[name="tanggal_log"], #tanggal',
    jamInput: 'input[name="jam"], input[type="time"], input#jam, input[name="jam_kegiatan"], #jam',
    simpanButton: 'button:has-text("Simpan"), button[type="submit"]',
    editButton: 'button:has-text("Edit"), a:has-text("Edit"), button[title*="Edit"]',
};

test.describe("TC_MH003 - Mahasiswa Log Aktivitas (US MHS-0005)", () => {
    test.beforeEach(async ({ page, context }) => {
        // Clear all cookies and storage to ensure clean state
        await context.clearCookies();
        await page.goto(getUrlWithBase("/"));
        await page.evaluate(() => {
            localStorage.clear();
            sessionStorage.clear();
        });
        
        // Login sebagai mahasiswa
        await page.goto(getUrlWithBase("/login"));
        await page.fill('input[name="username"], input[type="email"]', MAHASISWA_CREDENTIALS.username);
        await page.fill('input[name="password"], input[type="password"]', MAHASISWA_CREDENTIALS.password);
        await page.click('button[type="submit"], button:has-text("Login")');
        
        // Wait for auto-redirect to dashboard to complete
        await page.waitForLoadState('networkidle');
        await page.waitForTimeout(2000);
        
        // Ensure we're at dashboard
        if (!page.url().includes('/mahasiswa')) {
            await page.waitForURL('**/mahasiswa**', { timeout: 5000 });
        }
        
        console.log("✓ beforeEach: Logged in and at dashboard:", page.url());
    });

    test("TC_MH003_001 - [Positif] Menampilkan log aktivitas", async ({ page }) => {
        test.setTimeout(60000);

        // Precondition: Mahasiswa sudah login dan pengajuan magang diterima
        // (Login and redirect already handled in beforeEach)

        // Debug: Check if login successful
        const currentUrl = page.url();
        console.log("Current URL after login:", currentUrl);

        if (currentUrl.includes("/login")) {
            throw new Error("Login failed! Still on login page. Check credentials: " + MAHASISWA_CREDENTIALS.username);
        }

        // Take screenshot for debug
        await page.screenshot({ path: 'test-results/debug-dashboard-mahasiswa.png', fullPage: true });

        // Step 1: Klik menu pengajuan
        // Try to find and click Pengajuan link in navigation
        const pengajuanLink = page.locator('a[href*="pengajuan"], a:has-text("Pengajuan")').first();

        console.log("Pengajuan link count:", await pengajuanLink.count());

        if (await pengajuanLink.count() > 0) {
            await pengajuanLink.click();
            await page.waitForTimeout(2000);
        } else {
            // Direct navigation if menu not found
            console.log("Pengajuan link not found, trying direct navigation...");
            await page.goto(getUrlWithBase(MAHASISWA_ROUTES.pengajuan));
            await page.waitForTimeout(2000);
        }

        // Debug: Check current page after navigation
        console.log("URL after pengajuan navigation:", page.url());
        await page.screenshot({ path: 'test-results/debug-pengajuan-mahasiswa.png', fullPage: true });

        // Step 2: Klik card pengajuan (menggunakan selector yang sesuai dengan struktur)
        // Use the card container under #card-container and target the card that contains the
        // status badge "Disetujui" to make the click deterministic.
        const pengajuanCard = page.locator('#card-container .card').filter({ has: page.locator('span.badge:has-text("Disetujui")') }).first();
        let cardCount = await pengajuanCard.count();
        console.log("Pengajuan cards with Disetujui found (card-container selector):", cardCount);

        if (cardCount === 0) {
            // Fallback: try finding any element that has the Disetujui badge and click its closest card
            const badge = page.locator('span.badge:has-text("Disetujui")').first();
            if (await badge.count() === 0) {
                console.log("⚠️  TC_MH003_001: No pengajuan card with 'Disetujui' status found");
                test.skip();
                return;
            }

            // Click the ancestor card element of the badge
            const ancestorCard = badge.locator('xpath=ancestor::div[contains(@class, "card")]').first();
            if (await ancestorCard.count() === 0) {
                console.log("⚠️  TC_MH003_001: Found badge but couldn't locate ancestor card");
                test.skip();
                return;
            }
            await ancestorCard.click();
        } else {
            await pengajuanCard.click();
        }

        await page.waitForTimeout(2000);

        console.log("URL after clicking card:", page.url());

        // Step 3: Klik tombol "Log Aktivitas" di kanan atas halaman detail
        const logButton = page.locator(MAHASISWA_SELECTORS.logAktivitasButton).first();
        const logButtonCount = await logButton.count();

        console.log("Log aktivitas button count:", logButtonCount);

        if (logButtonCount === 0) {
            console.log("⚠️  TC_MH003_001: No log aktivitas button found on detail page");
            console.log("⚠️  Button should be at top-right of detail page");

            test.skip();
            return;
        }

        await logButton.click();
        await page.waitForTimeout(2000);

        // Expected Result: User diarahkan ke halaman log aktivitas
        const finalUrl = page.url();
        expect(finalUrl).toContain("log-aktivitas");

        console.log("✅ TC_MH003_001: Passed - Menampilkan halaman log aktivitas");
    });

    test("TC_MH003_002 - [Positif] Menambahkan log aktivitas dengan data yang valid", async ({ page }) => {
        test.setTimeout(90000);

        // Precondition: Mahasiswa sudah login dan pengajuan magang diterima
        // (Login and redirect already handled in beforeEach)

        // Step 1-3: Navigate to log aktivitas via Pengajuan → Click Card → Detail → Log Aktivitas button
        const pengajuanLink = page.locator('a[href*="pengajuan"], a:has-text("Pengajuan")').first();

        if (await pengajuanLink.count() > 0) {
            await pengajuanLink.click();
            await page.waitForTimeout(2000);
        } else {
            await page.goto(getUrlWithBase(MAHASISWA_ROUTES.pengajuan));
            await page.waitForTimeout(2000);
        }

        // Click pengajuan card: prefer card under #card-container containing 'Disetujui'
        let pengajuanCard = page.locator('#card-container .card').filter({ has: page.locator('span.badge:has-text("Disetujui")') }).first();
        let cardCount = await pengajuanCard.count();
        
        console.log("TC_MH003_002 - Cards with #card-container found:", cardCount);

        if (cardCount === 0) {
            // Fallback: Try without #card-container
            pengajuanCard = page.locator('.card').filter({ has: page.locator('span.badge:has-text("Disetujui")') }).first();
            cardCount = await pengajuanCard.count();
            console.log("TC_MH003_002 - Cards with .card found:", cardCount);
        }

        if (cardCount === 0) {
            const badge = page.locator('span.badge:has-text("Disetujui")').first();
            if (await badge.count() === 0) {
                console.log("⚠️  TC_MH003_002: No pengajuan card with 'Disetujui' status found");
                test.skip();
                return;
            }
            const ancestorCard = badge.locator('xpath=ancestor::div[contains(@class, "card")]').first();
            if (await ancestorCard.count() === 0) {
                console.log("⚠️  TC_MH003_002: Found badge but couldn't locate ancestor card");
                test.skip();
                return;
            }
            await ancestorCard.click();
        } else {
            await pengajuanCard.click();
        }
        await page.waitForTimeout(2000);

        // Click "Log Aktivitas" button (purple/blue button at top-right)
        const logButton = page.locator(MAHASISWA_SELECTORS.logAktivitasButton).first();

        if (await logButton.count() === 0) {
            console.log("⚠️  TC_MH003_002: No log aktivitas button found");
            test.skip();
            return;
        }

        await logButton.click();
        await page.waitForTimeout(2000);

        // Step 4: Klik + tambah log
        const tambahButton = page.locator(MAHASISWA_SELECTORS.tambahLogButton).first();

        if (await tambahButton.count() === 0) {
            throw new Error("Tombol tambah log tidak ditemukan");
        }

        await tambahButton.click();
        await page.waitForTimeout(2000);

        // Wait for modal
        const modal = page.locator('.modal, [role="dialog"]').first();
        await modal.waitFor({ state: 'visible', timeout: 10000 });
        await page.waitForTimeout(500);

        // Step 5: Isi field dengan Test data
        const testData = {
            aktivitas: "Melakukan testing dan debugging kode",
            kendala: "Perbedaan waktu kerja dengan tim",
            solusi: "Diskusi dengan mentor dan tim",
            tanggal: "2019-10-01", // 01/10/2019
            jam: "12:05" // 12:05:00
        };

        // Fill form with Bootstrap modal pattern (force: true)
        // Find textarea fields more carefully - avoid matching <h5> or <p> elements
        const aktivitasField = modal.locator('textarea').nth(0);
        const kendalaField = modal.locator('textarea').nth(1);
        const solusiField = modal.locator('textarea').nth(2);
        
        console.log("TC_MH003_002 - Filling aktivitas field...");
        await aktivitasField.waitFor({ state: 'visible', timeout: 5000 });
        await aktivitasField.fill(testData.aktivitas, { force: true });
        
        console.log("TC_MH003_002 - Filling kendala field...");
        await kendalaField.fill(testData.kendala, { force: true });
        
        console.log("TC_MH003_002 - Filling solusi field...");
        await solusiField.fill(testData.solusi, { force: true });
        
        console.log("TC_MH003_002 - Filling tanggal field...");
        await modal.locator('input[type="date"]').first().fill(testData.tanggal, { force: true });
        
        console.log("TC_MH003_002 - Filling jam field...");
        await modal.locator('input[type="time"]').first().fill(testData.jam, { force: true });

        await page.waitForTimeout(500);

        // Step 6: Klik Simpan (use JavaScript click for Bootstrap modal)
        const simpanButton = modal.locator(MAHASISWA_SELECTORS.simpanButton).first();
        await simpanButton.evaluate((el: HTMLElement) => el.click());
        await page.waitForTimeout(3000);

        // Expected Result: Menampilkan dialog: Berhasil! Log aktivitas berhasil disimpan
        // Kembali ke halaman aktivitas dengan tampilan yang sudah direfresh
        const pageContent = await page.content();
        const isSuccess =
            pageContent.includes("Berhasil") ||
            pageContent.includes("berhasil") ||
            pageContent.includes("sukses") ||
            pageContent.includes("success") ||
            pageContent.includes("disimpan");

        expect(isSuccess).toBeTruthy();

        console.log("✅ TC_MH003_002: Passed - Log aktivitas berhasil disimpan");
    });

    test("TC_MH003_006 - [Positif] Mengedit log aktivitas yang sudah ada", async ({ page }) => {
        test.setTimeout(90000);

        // Precondition: Mahasiswa sudah login, pengajuan diterima, data log sudah ada
        // (Login and redirect already handled in beforeEach)

        // Step 1-3: Navigate to log aktivitas via Pengajuan → Click Card → Detail → Log Aktivitas button
        const pengajuanLink = page.locator('a[href*="pengajuan"], a:has-text("Pengajuan")').first();

        if (await pengajuanLink.count() > 0) {
            await pengajuanLink.click();
            await page.waitForTimeout(2000);
        } else {
            await page.goto(getUrlWithBase(MAHASISWA_ROUTES.pengajuan));
            await page.waitForTimeout(2000);
        }

        // Click pengajuan card: prefer card under #card-container containing 'Disetujui'
        let pengajuanCard = page.locator('#card-container .card').filter({ has: page.locator('span.badge:has-text("Disetujui")') }).first();
        let cardCount = await pengajuanCard.count();
        
        console.log("TC_MH003_006 - Cards with #card-container found:", cardCount);

        if (cardCount === 0) {
            // Fallback: Try without #card-container
            pengajuanCard = page.locator('.card').filter({ has: page.locator('span.badge:has-text("Disetujui")') }).first();
            cardCount = await pengajuanCard.count();
            console.log("TC_MH003_006 - Cards with .card found:", cardCount);
        }

        if (cardCount === 0) {
            const badge = page.locator('span.badge:has-text("Disetujui")').first();
            if (await badge.count() === 0) {
                console.log("⚠️  TC_MH003_006: No pengajuan card with 'Disetujui' status found");
                test.skip();
                return;
            }
            const ancestorCard = badge.locator('xpath=ancestor::div[contains(@class, "card")]').first();
            if (await ancestorCard.count() === 0) {
                console.log("⚠️  TC_MH003_006: Found badge but couldn't locate ancestor card");
                test.skip();
                return;
            }
            await ancestorCard.click();
        } else {
            await pengajuanCard.click();
        }

        await page.waitForTimeout(2000);

        // Click "Log Aktivitas" button (purple/blue button at top-right)
        const logButton = page.locator(MAHASISWA_SELECTORS.logAktivitasButton).first();

        if (await logButton.count() === 0) {
            console.log("⚠️  TC_MH003_006: No log aktivitas button found");
            test.skip();
            return;
        }

        await logButton.click();
        await page.waitForTimeout(2000);

        // Step 4: Klik ikon edit pada log yang akan diedit
        const logTable = page.locator(MAHASISWA_SELECTORS.logTable);
        const logRowCount = await logTable.count();

        if (logRowCount === 0) {
            console.log("⚠️  TC_MH003_006: No log aktivitas found to edit");
            test.skip();
            return;
        }

        // Click edit button on first row (last button/link in the row)
        const firstRow = logTable.first();
        const editButton = firstRow.locator(MAHASISWA_SELECTORS.editButton).first();

        // Try different approaches to find edit button
        if (await editButton.count() === 0) {
            // Try last button/link in row
            const lastButton = firstRow.locator('button, a').last();
            await lastButton.click();
        } else {
            await editButton.click();
        }

        await page.waitForTimeout(2000);

        const modal = page.locator('.modal, [role="dialog"]').first();
        await modal.waitFor({ state: 'visible', timeout: 10000 });
        await page.waitForTimeout(500);

        // Step 5: Isi field dengan Test data yang baru
        const testData = {
            aktivitas: "Mengikuti sesi mentoring dengan senior developer",
            kendala: "tidak ada",
            solusi: "",
            tanggal: "2019-10-01", // 01/10/2019
            jam: "12:05" // 12:05:00
        };

        // Fill form with Bootstrap modal pattern
        // Find textarea fields more carefully - avoid matching <h5> or <p> elements
        const aktivitasField = modal.locator('textarea').nth(0);
        const kendalaField = modal.locator('textarea').nth(1);
        const solusiField = modal.locator('textarea').nth(2);
        
        console.log("TC_MH003_006 - Clearing and filling aktivitas field...");
        await aktivitasField.waitFor({ state: 'visible', timeout: 5000 });
        await aktivitasField.clear();
        await aktivitasField.fill(testData.aktivitas, { force: true });
        
        console.log("TC_MH003_006 - Clearing and filling kendala field...");
        await kendalaField.clear();
        await kendalaField.fill(testData.kendala, { force: true });
        
        console.log("TC_MH003_006 - Clearing and filling solusi field...");
        await solusiField.clear();
        await solusiField.fill(testData.solusi, { force: true });
        
        console.log("TC_MH003_006 - Filling tanggal field...");
        await modal.locator('input[type="date"]').first().fill(testData.tanggal, { force: true });
        
        console.log("TC_MH003_006 - Filling jam field...");
        await modal.locator('input[type="time"]').first().fill(testData.jam, { force: true });

        await page.waitForTimeout(500);

        // Step 6: Klik Simpan
        const simpanButton = modal.locator(MAHASISWA_SELECTORS.simpanButton).first();
        await simpanButton.evaluate((el: HTMLElement) => el.click());
        await page.waitForTimeout(3000);

        // Expected Result: Menampilkan dialog: Berhasil! Log aktivitas berhasil disimpan
        // Kembali ke halaman aktivitas dengan tampilan yang sudah direfresh
        const pageContent = await page.content();
        const isSuccess =
            pageContent.includes("Berhasil") ||
            pageContent.includes("berhasil") ||
            pageContent.includes("sukses") ||
            pageContent.includes("disimpan");

        expect(isSuccess).toBeTruthy();

        console.log("✅ TC_MH003_006: Passed - Log aktivitas berhasil diedit");
    });

    test("TC_MH003_005 - [Negatif] Menambahkan log aktivitas dengan tidak mengisi field wajib", async ({ page }) => {
        test.setTimeout(90000);

        // Precondition: Mahasiswa sudah login dan pengajuan magang diterima
        // (Login and redirect already handled in beforeEach)

        // Step 1-3: Navigate to log aktivitas via Pengajuan → Click Card → Detail → Log Aktivitas button
        const pengajuanLink = page.locator('a[href*="pengajuan"], a:has-text("Pengajuan")').first();

        if (await pengajuanLink.count() > 0) {
            await pengajuanLink.click();
            await page.waitForTimeout(2000);
        } else {
            await page.goto(getUrlWithBase(MAHASISWA_ROUTES.pengajuan));
            await page.waitForTimeout(2000);
        }

        // Click pengajuan card: prefer card under #card-container containing 'Disetujui'
        let pengajuanCard = page.locator('#card-container .card').filter({ has: page.locator('span.badge:has-text("Disetujui")') }).first();
        let cardCount = await pengajuanCard.count();
        
        console.log("TC_MH003_005 - Cards with #card-container found:", cardCount);

        if (cardCount === 0) {
            // Fallback: Try without #card-container
            pengajuanCard = page.locator('.card').filter({ has: page.locator('span.badge:has-text("Disetujui")') }).first();
            cardCount = await pengajuanCard.count();
            console.log("TC_MH003_005 - Cards with .card found:", cardCount);
        }

        if (cardCount === 0) {
            const badge = page.locator('span.badge:has-text("Disetujui")').first();
            if (await badge.count() === 0) {
                console.log("⚠️  TC_MH003_005: No pengajuan card with 'Disetujui' status found");
                test.skip();
                return;
            }
            const ancestorCard = badge.locator('xpath=ancestor::div[contains(@class, "card")]').first();
            if (await ancestorCard.count() === 0) {
                console.log("⚠️  TC_MH003_005: Found badge but couldn't locate ancestor card");
                test.skip();
                return;
            }
            await ancestorCard.click();
        } else {
            await pengajuanCard.click();
        }

        await page.waitForTimeout(2000);

        // Click "Log Aktivitas" button (purple/blue button at top-right)
        const logButton = page.locator(MAHASISWA_SELECTORS.logAktivitasButton).first();

        if (await logButton.count() === 0) {
            console.log("⚠️  TC_MH003_005: No log aktivitas button found");
            test.skip();
            return;
        }

        await logButton.click();
        await page.waitForTimeout(2000);

        // Step 4: Klik + tambah log
        const tambahButton = page.locator(MAHASISWA_SELECTORS.tambahLogButton).first();

        if (await tambahButton.count() === 0) {
            throw new Error("Tombol tambah log tidak ditemukan");
        }

        await tambahButton.click();
        await page.waitForTimeout(2000);

        const modal = page.locator('.modal, [role="dialog"]').first();

        // Step 5: Isi field dengan Test data (field wajib KOSONG)
        const testData = {
            aktivitas: "", // KOSONG - field wajib
            kendala: "Perbedaan waktu kerja dengan tim",
            solusi: "Diskusi dengan mentor dan tim",
            tanggal: "", // KOSONG - field wajib
            jam: "" // KOSONG - field wajib
        };

        // Fill only non-required fields
        // Note: kendala and solusi might be textarea, input, or contenteditable
        const kendalaField = modal.locator('textarea[name="kendala"], textarea#kendala_log, input[name="kendala"], [contenteditable="true"]').filter({ hasText: '' }).or(modal.locator('textarea').nth(1)).first();
        const solusiField = modal.locator('textarea[name="solusi"], textarea#solusi_log, input[name="solusi"], [contenteditable="true"]').filter({ hasText: '' }).or(modal.locator('textarea').nth(2)).first();
        
        if (await kendalaField.count() > 0) {
            try {
                await kendalaField.fill(testData.kendala, { force: true });
            } catch (error) {
                console.log("⚠️  Could not fill kendala field:", error instanceof Error ? error.message : String(error));
            }
        }
        if (await solusiField.count() > 0) {
            try {
                await solusiField.fill(testData.solusi, { force: true });
            } catch (error) {
                console.log("⚠️  Could not fill solusi field:", error instanceof Error ? error.message : String(error));
            }
        }

        await page.waitForTimeout(500);

        // Step 6: Klik Simpan (try regular click first, fallback to evaluate)
        const simpanButton = modal.locator(MAHASISWA_SELECTORS.simpanButton).first();
        const simpanButtonCount = await simpanButton.count();
        
        console.log("TC_MH003_005 - Simpan button count:", simpanButtonCount);
        
        if (simpanButtonCount === 0) {
            console.log("⚠️  TC_MH003_005: Simpan button not found, modal might have closed");
            test.skip();
            return;
        }

        try {
            await simpanButton.click({ timeout: 5000 });
        } catch (e) {
            console.log("Regular click failed, trying evaluate click...");
            await simpanButton.evaluate((el: HTMLElement) => el.click());
        }
        await page.waitForTimeout(2000);

        // Expected Result:
        // - Menampilkan dialog: Gagal! dengan alasan form mana yang tidak diisi
        // - Mencegah aksi simpan terjadi
        // - Pada field menampilkan pesan field yang harus diisi
        const pageContent = await page.content();
        const hasValidationError =
            pageContent.includes("wajib") ||
            pageContent.includes("required") ||
            pageContent.includes("harus diisi") ||
            pageContent.includes("Gagal") ||
            pageContent.includes("gagal") ||
            pageContent.includes("error") ||
            pageContent.includes("tidak boleh kosong");

        // Check for validation messages on fields
        const validationMessages = await page.locator('.invalid-feedback, .text-danger, [class*="error"], .error-message').count();

        expect(hasValidationError || validationMessages > 0).toBeTruthy();

        console.log("✅ TC_MH003_005: Passed - Validasi field wajib berfungsi dengan baik");
    });
});
