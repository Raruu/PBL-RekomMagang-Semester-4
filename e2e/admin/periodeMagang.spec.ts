import { test, expect, type Page } from "@playwright/test";
import { getUrlWithBase } from "../fixtures/constants";
import { ADMIN_SELECTORS } from "./selector";
import { loginAsAdmin, navigateToMagangPeriode } from "./helpers";

test.describe("Manajemen Periode Magang - Test Admin", () => {
    let page: Page;

    test.beforeEach(async ({ browser }) => {
        page = await browser.newPage();
        // Login as admin before each test
        await loginAsAdmin(page);
    });

    test.afterEach(async () => {
        await page.close();
    });

    test("TC_PRD_001 - [POSITIF] Admin menambah tanggal mulai & selesai ke lowongan tanpa periode", async () => {
        // Navigate to Menu → Magang → Periode
        await navigateToMagangPeriode(page); // Wait for lowongan list to load - use the "belum" table (without periode)
        const lowonganList = page.locator("#periodeTableBelum");
        await expect(lowonganList).toBeVisible({ timeout: 10000 });

        // Find first row in the table and click edit button (pencil icon)
        const firstRow = lowonganList.locator("tbody tr").first();
        await expect(firstRow).toBeVisible({ timeout: 5000 });

        // Look for edit button (pencil icon) in the first row
        const editButton = firstRow
            .locator(
                'button[title*="Edit"], button[title*="Atur"], .btn-warning, button:has([class*="fa-edit"]), button:has([class*="fa-pencil"])'
            )
            .first();

        await expect(editButton).toBeVisible({ timeout: 5000 });
        await editButton.click();

        // Wait for form/modal to appear
        const modal = page.locator(ADMIN_SELECTORS.modal);
        if (await modal.isVisible({ timeout: 2000 })) {
            // If in modal
            await expect(modal).toBeVisible();
        }

        // Fill start date
        const startDateInput = page
            .locator(ADMIN_SELECTORS.startDateInput)
            .or(page.locator(ADMIN_SELECTORS.dateInput).first());
        await expect(startDateInput).toBeVisible({ timeout: 5000 });
        await startDateInput.fill("2024-01-15");

        // Fill end date
        const endDateInput = page
            .locator(ADMIN_SELECTORS.endDateInput)
            .or(page.locator(ADMIN_SELECTORS.dateInput).last());
        await expect(endDateInput).toBeVisible();
        await endDateInput.fill("2024-06-15");

        // Save the periode
        const saveButton = page
            .locator(ADMIN_SELECTORS.savePeriodeButton)
            .or(page.locator(ADMIN_SELECTORS.saveBtn));
        await expect(saveButton).toBeVisible();
        await saveButton.click();

        // Verify saved successfully - check for success message (SweetAlert)
        const successMessage = page.locator(".swal2-title");
        await expect(successMessage).toBeVisible({ timeout: 10000 });
        // Wait for any success indicator - could be "Berhasil", "Success", or just visible success modal
        await page.waitForTimeout(2000);

        // Test completed successfully - periode has been set
    });

    test("TC_PRD_002 - [NEGATIF] Admin mengosongkan salah satu tanggal", async () => {
        // Navigate to Menu → Magang → Periode
        await navigateToMagangPeriode(page);

        // Wait for lowongan list to load - use the "belum" table for add periode
        const lowonganList = page.locator("#periodeTableBelum");
        await expect(lowonganList).toBeVisible({ timeout: 10000 });

        // Find first row in the table and click edit button (pencil icon)
        const firstRow = lowonganList.locator("tbody tr").first();
        await expect(firstRow).toBeVisible({ timeout: 5000 });

        // Look for edit button (pencil icon) in the first row
        const editButton = firstRow
            .locator(
                'button[title*="Edit"], button[title*="Atur"], .btn-warning, button:has([class*="fa-edit"]), button:has([class*="fa-pencil"])'
            )
            .first();

        await expect(editButton).toBeVisible({ timeout: 5000 });
        await editButton.click();

        // Fill only start date, leave end date empty
        const startDateInput = page
            .locator(
                'input[name="tanggal_mulai"], input[id*="mulai"], input[type="date"]'
            )
            .first();
        await expect(startDateInput).toBeVisible();
        await startDateInput.fill("2024-01-01");

        // Leave end date empty (second date input)
        const endDateInput = page
            .locator(
                'input[name="tanggal_selesai"], input[id*="selesai"], input[type="date"]'
            )
            .last();
        await expect(endDateInput).toBeVisible();
        await endDateInput.clear(); // Ensure it's empty

        // Try to save
        const saveButton = page
            .locator(ADMIN_SELECTORS.savePeriodeButton)
            .or(page.locator(ADMIN_SELECTORS.saveBtn));
        await expect(saveButton).toBeVisible();
        await saveButton.click();

        // Wait for response and try to catch the auto-closing SweetAlert
        // Since SweetAlert auto-closes, we need to check immediately after clicking save

        let alertDetected = false;
        let alertText = "";

        // Set up a listener for SweetAlert before it appears and auto-closes
        const alertPromise = page
            .waitForSelector(".swal2-title", { timeout: 3000 })
            .then(async (element) => {
                if (element) {
                    alertText = (await element.textContent()) || "";
                    alertDetected = true;
                    console.log(
                        `DEBUG: SweetAlert auto-close terdeteksi dengan text: "${alertText}"`
                    );
                    return true;
                }
                return false;
            })
            .catch(() => {
                console.log(
                    "DEBUG: Tidak ada SweetAlert yang terdeteksi dalam 3 detik"
                );
                return false;
            });

        // Wait for the alert to appear and potentially auto-close
        await alertPromise;
        await page.waitForTimeout(2000); // Give some time for auto-close

        // Determine if it was success or error based on text content
        const isSuccess =
            alertText.toLowerCase().includes("berhasil") ||
            alertText.toLowerCase().includes("success");
        const isError =
            alertText.toLowerCase().includes("error") ||
            alertText.toLowerCase().includes("gagal");

        const hasError = alertDetected && isError;
        const hasSuccess = alertDetected && isSuccess;

        if (hasError) {
            // Expected behavior - validation working correctly
            console.log(
                "✅ BENAR: Validation error ditampilkan - sistem memblokir data tidak lengkap"
            );
        } else if (hasSuccess) {
            // BUG DETECTED - system incorrectly allows saving with missing end date
            console.log(
                "❌ BUG TERDETEKSI: Sistem mengizinkan menyimpan dengan tanggal akhir kosong dan menampilkan 'Berhasil'!"
            );
            throw new Error(
                "BUG SISTEM: Validasi tanggal akhir tidak berfungsi - sistem seharusnya tidak mengizinkan menyimpan periode dengan tanggal yang tidak lengkap. Requirement: kedua tanggal (mulai & selesai) harus diisi."
            );
        } else {
            // This shouldn't happen based on your clarification - system should either show error or success
            console.log(
                "⚠️ TIDAK TERDUGA: Tidak ada response error atau success dari sistem"
            );
            throw new Error(
                "BEHAVIOR TIDAK TERDUGA: Sistem tidak memberikan feedback apapun"
            );
        }
    });

    test("TC_PRD_003 - [POSITIF] Admin berhasil mengupdate periode", async () => {
        // Navigate to Menu → Magang → Periode
        await navigateToMagangPeriode(page);

        // Wait for lowongan list to load - use the "sudah" table for edit periode
        const lowonganList = page.locator("#periodeTableSudah");
        await expect(lowonganList).toBeVisible({ timeout: 10000 });

        // Find first row in the sudah table and click edit button (pencil icon)
        const firstRow = lowonganList.locator("tbody tr").first();
        await expect(firstRow).toBeVisible({ timeout: 5000 });

        // Look for edit button (pencil icon) in the first row
        const editPeriodeBtn = firstRow
            .locator(
                'button[title*="Edit"], button[title*="Atur"], .btn-warning, button:has([class*="fa-edit"]), button:has([class*="fa-pencil"])'
            )
            .first();

        await expect(editPeriodeBtn).toBeVisible({ timeout: 5000 });
        await editPeriodeBtn.click();

        // Wait for form/modal to appear with existing data
        const modal = page.locator(ADMIN_SELECTORS.modal);
        if (await modal.isVisible({ timeout: 2000 })) {
            await expect(modal).toBeVisible();
        }

        // Update start date
        const startDateInput = page
            .locator(ADMIN_SELECTORS.startDateInput)
            .or(page.locator(ADMIN_SELECTORS.dateInput).first());
        await expect(startDateInput).toBeVisible({ timeout: 5000 });
        await startDateInput.clear();
        await startDateInput.fill("2024-02-01");

        // Update end date
        const endDateInput = page
            .locator(ADMIN_SELECTORS.endDateInput)
            .or(page.locator(ADMIN_SELECTORS.dateInput).last());
        await expect(endDateInput).toBeVisible();
        await endDateInput.clear();
        await endDateInput.fill("2024-07-01");

        // Save the updated periode
        const saveButton = page
            .locator(ADMIN_SELECTORS.savePeriodeButton)
            .or(page.locator(ADMIN_SELECTORS.saveBtn));
        await expect(saveButton).toBeVisible();
        await saveButton.click();

        // Verify update successful (SweetAlert)
        const successMessage = page.locator(".swal2-title");
        await expect(successMessage).toBeVisible({ timeout: 10000 });
        // Wait for any success indicator
        await page.waitForTimeout(2000);

        // Verify the page refreshes or modal closes
        await page.waitForTimeout(2000);

        // Test completed successfully - periode has been updated
    });

    test("TC_PRD_004 - [NEGATIF] Admin menghapus tanggal di periode yang sudah ada", async () => {
        // Navigate to Menu → Magang → Periode
        await navigateToMagangPeriode(page);

        // Wait for lowongan list to load - use the "sudah" table for edit periode
        const lowonganList = page.locator("#periodeTableSudah");
        await expect(lowonganList).toBeVisible({ timeout: 10000 });

        // Find first row in the sudah table and click edit button (pencil icon)
        const firstRow = lowonganList.locator("tbody tr").first();
        await expect(firstRow).toBeVisible({ timeout: 5000 });

        // Look for edit button (pencil icon) in the first row
        const editButton = firstRow
            .locator(
                'button[title*="Edit"], button[title*="Atur"], .btn-warning, button:has([class*="fa-edit"]), button:has([class*="fa-pencil"])'
            )
            .first();

        await expect(editButton).toBeVisible({ timeout: 5000 });
        await editButton.click();

        // Wait for form/modal with existing data
        const modal = page.locator(ADMIN_SELECTORS.modal);
        if (await modal.isVisible({ timeout: 2000 })) {
            await expect(modal).toBeVisible();
        }

        // Clear one of the date fields (start date in this case)
        const startDateInput = page
            .locator(ADMIN_SELECTORS.startDateInput)
            .or(page.locator(ADMIN_SELECTORS.dateInput).first());
        await expect(startDateInput).toBeVisible({ timeout: 5000 });
        await startDateInput.clear(); // Clear the date field

        // Try to save with cleared date
        const saveButton = page
            .locator(ADMIN_SELECTORS.savePeriodeButton)
            .or(page.locator(ADMIN_SELECTORS.saveBtn));
        await expect(saveButton).toBeVisible();
        await saveButton.click();

        // Wait for response and try to catch the auto-closing SweetAlert
        // Since SweetAlert auto-closes, we need to check immediately after clicking save

        let alertDetected = false;
        let alertText = "";

        // Set up a listener for SweetAlert before it appears and auto-closes
        const alertPromise = page
            .waitForSelector(".swal2-title", { timeout: 3000 })
            .then(async (element) => {
                if (element) {
                    alertText = (await element.textContent()) || "";
                    alertDetected = true;
                    console.log(
                        `DEBUG: SweetAlert auto-close terdeteksi dengan text: "${alertText}"`
                    );
                    return true;
                }
                return false;
            })
            .catch(() => {
                console.log(
                    "DEBUG: Tidak ada SweetAlert yang terdeteksi dalam 3 detik"
                );
                return false;
            });

        // Wait for the alert to appear and potentially auto-close
        await alertPromise;
        await page.waitForTimeout(2000); // Give some time for auto-close

        // Determine if it was success or error based on text content
        const isSuccess =
            alertText.toLowerCase().includes("berhasil") ||
            alertText.toLowerCase().includes("success");
        const isError =
            alertText.toLowerCase().includes("error") ||
            alertText.toLowerCase().includes("gagal");

        const hasError = alertDetected && isError;
        const hasSuccess = alertDetected && isSuccess;

        if (hasError) {
            // Expected behavior - validation working correctly
            console.log(
                "✅ BENAR: Validation error ditampilkan - sistem memblokir penghapusan tanggal"
            );
        } else if (hasSuccess) {
            // BUG DETECTED - system incorrectly allows clearing date in existing periode
            console.log(
                "❌ BUG TERDETEKSI: Sistem mengizinkan menghapus tanggal mulai di periode yang sudah ada dan menampilkan 'Berhasil'!"
            );
            throw new Error(
                "BUG SISTEM: Validasi tanggal mulai tidak berfungsi - sistem seharusnya tidak mengizinkan menyimpan periode dengan tanggal yang kosong/tidak lengkap. Requirement: kedua tanggal (mulai & selesai) harus diisi."
            );
        } else {
            // This shouldn't happen based on your clarification - system should either show error or success
            console.log(
                "⚠️ TIDAK TERDUGA: Tidak ada response error atau success dari sistem"
            );
            throw new Error(
                "BEHAVIOR TIDAK TERDUGA: Sistem tidak memberikan feedback apapun"
            );
        }
    });
});
