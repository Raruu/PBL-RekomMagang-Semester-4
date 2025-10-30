import { test, expect, type Page } from "@playwright/test";
import { getUrlWithBase } from "../fixtures/constants";
import { ADMIN_SELECTORS } from "./selector";
import { loginAsAdmin, navigateToMagangKegiatan } from "./helpers";

test.describe("Manajemen Kegiatan Magang - Test Admin", () => {
    let page: Page;

    test.beforeEach(async ({ browser }) => {
        page = await browser.newPage();
        // Login as admin before each test
        await loginAsAdmin(page);
    });

    test.afterEach(async () => {
        await page.close();
    });

    test("TC_KGM_001 - [POSITIF] Admin melihat daftar pengajuan magang", async () => {
        // Navigate to Menu → Magang → Kegiatan
        await navigateToMagangKegiatan(page); // Verify table list of pengajuan magang is visible with status column
        const pengajuanTable = page
            .locator(ADMIN_SELECTORS.pengajuanTable)
            .or(page.locator("table"));
        await expect(pengajuanTable).toBeVisible({ timeout: 10000 });

        // Check if table has data rows
        const tableRows = pengajuanTable.locator(ADMIN_SELECTORS.tableRowKegiatan);
        await expect(tableRows.first()).toBeVisible({ timeout: 5000 });

        // Verify status column exists
        const statusColumn = pengajuanTable
            .locator(ADMIN_SELECTORS.statusColumn)
            .or(
                pengajuanTable.locator(
                    "th:has-text('Status'), td:has-text('Status')"
                )
            );
        await expect(statusColumn.first()).toBeVisible();
    });

    test("TC_KGM_002 - [POSITIF] Admin menyetujui pengajuan magang dengan memilih dosen pembimbing", async () => {
        // Navigate to pengajuan list
        await navigateToMagangKegiatan(page);

        // Wait for table to load
        const pengajuanTable = page
            .locator(ADMIN_SELECTORS.pengajuanTable)
            .or(page.locator("table"));
        await expect(pengajuanTable).toBeVisible({ timeout: 10000 });

        // Select first available row and click it to go to detail page
        const firstRow = pengajuanTable
            .locator(ADMIN_SELECTORS.tableRowKegiatan)
            .first();
        await expect(firstRow).toBeVisible();

        // Click the row to navigate to detail page
        await firstRow.click();

        // Wait for detail page to load
        await expect(page).toHaveURL(/\/admin\/magang\/kegiatan\/\d+\/detail/);

        // Now on detail page - choose status "Disetujui"
        const statusDropdown = page.locator("#status");
        await expect(statusDropdown).toBeVisible({ timeout: 5000 });
        await statusDropdown.selectOption("disetujui");

        // Select a lecturer from dropdown
        const dosenDropdown = page.locator("#dosen_id");
        await expect(dosenDropdown).toBeVisible();

        // Select first available dosen option (skip empty option)
        const dosenOptions = await dosenDropdown.locator("option").all();
        if (dosenOptions.length > 1) {
            await dosenDropdown.selectOption({ index: 1 });
        }

        // Click "Simpan" button
        const simpanButton = page.locator("#btn-submit");
        await expect(simpanButton).toBeEnabled();
        await simpanButton.click();

        // Modal konfirmasi should appear
        const modal = page.locator("#modal-submit-confirm");
        await expect(modal).toBeVisible({ timeout: 5000 });

        // Optional: Fill catatan in modal (optional for approval)
        const catatanField = page.locator("#modal_catatan");
        if (await catatanField.isVisible({ timeout: 2000 })) {
            await catatanField.fill(
                "Pengajuan disetujui dengan dosen pembimbing yang telah ditentukan."
            );
        }

        // Click confirm button in modal
        const confirmButton = page.locator("#btn-true-yes-no");
        await expect(confirmButton).toBeVisible();
        await confirmButton.click();

        // Verify success message appears (use more specific selector)
        const successMessage = page.locator(
            ".swal2-title:has-text('Berhasil')"
        );
        await expect(successMessage).toBeVisible({ timeout: 10000 });

        // Verify we're redirected back or page updates
        await page.waitForTimeout(2000);
    });

    test("TC_KGM_003 - [NEGATIF] Admin mencoba menyetujui pengajuan tanpa memilih dosen", async () => {
        // Navigate to pengajuan list
        await navigateToMagangKegiatan(page);

        // Wait for table to load
        const pengajuanTable = page
            .locator(ADMIN_SELECTORS.pengajuanTable)
            .or(page.locator("table"));
        await expect(pengajuanTable).toBeVisible({ timeout: 10000 });

        // Select first available row
        const firstRow = pengajuanTable
            .locator(ADMIN_SELECTORS.tableRowKegiatan)
            .first();
        await expect(firstRow).toBeVisible();

        // Click the row to navigate to detail page
        await firstRow.click();

        // Wait for detail page to load
        await expect(page).toHaveURL(/\/admin\/magang\/kegiatan\/\d+\/detail/);

        // Choose status "Disetujui" but leave dosen empty
        const statusDropdown = page.locator("#status");
        await expect(statusDropdown).toBeVisible({ timeout: 5000 });
        await statusDropdown.selectOption("disetujui");

        // Verify dosen dropdown exists but DO NOT select anything (leave it empty)
        const dosenDropdown = page.locator("#dosen_id");
        await expect(dosenDropdown).toBeVisible();
        // Skip selecting dosen - leave it empty to test validation

        // Verify submit button becomes disabled when dosen is not selected
        const simpanButton = page.locator("#btn-submit");
        await expect(simpanButton).toBeVisible();

        // The button should be disabled because dosen is required for approval
        await expect(simpanButton).toBeDisabled({ timeout: 5000 });

        // Verify we can't actually submit - button should remain disabled
        // This confirms the client-side validation is working correctly
    });

    test("TC_KGM_004 - [POSITIF] Admin menolak pengajuan dengan catatan wajib", async () => {
        // Navigate to pengajuan list
        await navigateToMagangKegiatan(page);

        // Wait for table to load
        const pengajuanTable = page
            .locator(ADMIN_SELECTORS.pengajuanTable)
            .or(page.locator("table"));
        await expect(pengajuanTable).toBeVisible({ timeout: 10000 });

        // Select first available row
        const firstRow = pengajuanTable
            .locator(ADMIN_SELECTORS.tableRowKegiatan)
            .first();
        await expect(firstRow).toBeVisible();

        // Click the row to navigate to detail page
        await firstRow.click();

        // Wait for detail page to load
        await expect(page).toHaveURL(/\/admin\/magang\/kegiatan\/\d+\/detail/);

        // Choose status "Ditolak"
        const statusDropdown = page.locator("#status");
        await expect(statusDropdown).toBeVisible({ timeout: 5000 });
        await statusDropdown.selectOption("ditolak");

        // Click "Simpan" button
        const simpanButton = page.locator("#btn-submit");
        await expect(simpanButton).toBeEnabled(); // Should be enabled for rejection even without dosen
        await simpanButton.click();

        // Modal konfirmasi should appear
        const modal = page.locator("#modal-submit-confirm");
        await expect(modal).toBeVisible({ timeout: 5000 });

        // Fill catatan (should be mandatory for rejection)
        const catatanField = page.locator("#modal_catatan");
        await expect(catatanField).toBeVisible({ timeout: 5000 });
        await catatanField.fill(
            "Pengajuan ditolak karena dokumen tidak lengkap."
        );

        // Click confirm button in modal
        const confirmButton = page.locator("#btn-true-yes-no");
        await expect(confirmButton).toBeVisible();
        await confirmButton.click();

        // Verify success popup displayed
        const successMessage = page.locator(
            ".swal2-title:has-text('Berhasil')"
        );
        await expect(successMessage).toBeVisible({ timeout: 10000 });

        // Wait for processing
        await page.waitForTimeout(2000);
    });

    test("TC_KGM_005 - [NEGATIF] Admin menolak tapi catatan tidak diisi", async () => {
        // Navigate to pengajuan list
        await navigateToMagangKegiatan(page);

        // Wait for table to load
        const pengajuanTable = page
            .locator(ADMIN_SELECTORS.pengajuanTable)
            .or(page.locator("table"));
        await expect(pengajuanTable).toBeVisible({ timeout: 10000 });

        // Select first available row
        const firstRow = pengajuanTable
            .locator(ADMIN_SELECTORS.tableRowKegiatan)
            .first();
        await expect(firstRow).toBeVisible();

        // Click the row to navigate to detail page
        await firstRow.click();

        // Wait for detail page to load
        await expect(page).toHaveURL(/\/admin\/magang\/kegiatan\/\d+\/detail/);

        // Choose status "Ditolak"
        const statusDropdown = page.locator("#status");
        await expect(statusDropdown).toBeVisible({ timeout: 5000 });
        await statusDropdown.selectOption("ditolak");

        // Click "Simpan" button without filling catatan
        const simpanButton = page.locator("#btn-submit");
        await expect(simpanButton).toBeEnabled();
        await simpanButton.click();

        // Modal konfirmasi should appear
        const modal = page.locator("#modal-submit-confirm");
        await expect(modal).toBeVisible({ timeout: 5000 });

        // Leave catatan field empty (should be mandatory for rejection)
        const catatanField = page.locator("#modal_catatan");
        await expect(catatanField).toBeVisible({ timeout: 5000 });
        await catatanField.clear(); // Ensure it's empty

        // Try to confirm without catatan
        const confirmButton = page.locator("#btn-true-yes-no");
        await expect(confirmButton).toBeVisible();
        await confirmButton.click();

        // Verify validation appears - system should show SweetAlert error
        const validationError = page.locator(".swal2-title:has-text('Gagal')");
        await expect(validationError).toBeVisible({ timeout: 5000 });

        // Check for validation message content
        const errorContent = page.locator(
            ".swal2-html-container, .swal2-content"
        );
        await expect(errorContent).toContainText(
            /catatan.*kosong|catatan.*wajib|required/i
        );

        // Close the error dialog
        const errorOkButton = page.locator(".swal2-confirm");
        if (await errorOkButton.isVisible({ timeout: 2000 })) {
            await errorOkButton.click();
        }

        // Verify data is NOT saved (modal should still be open)
        await expect(modal).toBeVisible({ timeout: 3000 });
    });
});
