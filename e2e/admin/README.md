# Admin E2E Test Runner

This directory contains Playwright E2E tests for the Admin role in the Laravel internship management system.

## Test Files

### 1. kegiatanMagang.spec.ts

Tests for **Manajemen Kegiatan Magang - Test Admin** module:

-   ✅ `TC_KGM_001` - [POSITIF] Admin melihat daftar pengajuan magang
-   ✅ `TC_KGM_002` - [POSITIF] Admin menyetujui pengajuan magang dengan memilih dosen pembimbing
-   ✅ `TC_KGM_003` - [NEGATIF] Admin mencoba menyetujui pengajuan tanpa memilih dosen (validation working)
-   ✅ `TC_KGM_004` - [POSITIF] Admin menolak pengajuan dengan catatan wajib
-   ✅ `TC_KGM_005` - [NEGATIF] Admin menolak tapi catatan tidak diisi (validation working)

### 2. periodeMagang.spec.ts

Tests for **Manajemen Periode Magang - Test Admin** module:

-   ✅ `TC_PRD_001` - [POSITIF] Admin menambah tanggal mulai & selesai ke lowongan tanpa periode
-   ❌ `TC_PRD_002` - [NEGATIF] Admin mengosongkan salah satu tanggal (BUG DETECTED: sistem mengizinkan data invalid)
-   ✅ `TC_PRD_003` - [POSITIF] Admin berhasil mengupdate periode
-   ❌ `TC_PRD_004` - [NEGATIF] Admin menghapus tanggal di periode yang sudah ada (BUG DETECTED: sistem mengizinkan data invalid)

## Running Tests

### Run all admin tests:

```bash
npx playwright test e2e/admin/ --reporter=list
```

### Run specific test file:

```bash
npx playwright test e2e/admin/kegiatanMagang.spec.ts --reporter=list
npx playwright test e2e/admin/periodeMagang.spec.ts --reporter=list
```

### Run specific test case:

```bash
npx playwright test e2e/admin/kegiatanMagang.spec.ts -g "TC_KGM_001" --reporter=list
npx playwright test e2e/admin/periodeMagang.spec.ts -g "TC_PRD_002" --reporter=list
```

### Run with debug mode:

```bash
npx playwright test e2e/admin/kegiatanMagang.spec.ts -g "TC_KGM_002" --debug
```

### Run with maximum failures limit:

```bash
npx playwright test e2e/admin/ --reporter=list --max-failures=3
```

## Test Configuration

-   **Base URL**: `http://127.0.0.1:8000`
-   **Admin Credentials**: Loaded from `.env` file (`ADMIN_EMAIL`, `ADMIN_PASSWORD`)
-   **Browser**: Chromium with headed mode (headless: false)
-   **Login**: Each test automatically logs in as admin before execution
-   **Language**: Test names and error messages in Bahasa Indonesia

## Test Features

-   ✅ Automatic admin login before each test
-   ✅ Robust navigation using specific table and form selectors
-   ✅ Click table rows → detail page interaction workflow
-   ✅ SweetAlert detection including auto-closing alerts
-   ✅ Comprehensive positive and negative test scenarios
-   ✅ Real-time bug detection for validation failures
-   ✅ Form validation testing (disabled buttons, required fields)
-   ✅ Proper cleanup after each test

## Test Results Summary

### ✅ KEGIATAN MAGANG - ALL TESTS PASSING (5/5)

All kegiatan magang tests are working correctly with proper validation behavior.

### 📊 PERIODE MAGANG - MIXED RESULTS (2 PASS, 2 FAIL)

-   **Positive tests (TC_PRD_001, TC_PRD_003)**: ✅ PASSING
-   **Negative tests (TC_PRD_002, TC_PRD_004)**: ❌ FAILING (By Design)

### 🐛 Detected System Bugs

**TC_PRD_002 & TC_PRD_004** successfully detect validation bugs:

-   System incorrectly allows saving periods with incomplete date fields
-   Shows "Berhasil" (Success) alert for invalid data
-   **Expected**: System should reject incomplete data with error message
-   **Actual**: System accepts invalid data and shows success

## Technical Implementation

-   **Auto-closing SweetAlert Detection**: Uses `waitForSelector` with promise handling
-   **Table Navigation**: Clicks table rows to navigate to detail pages
-   **Form Interactions**: Uses specific selectors (#status, #dosen_id, #btn-submit)
-   **Validation Testing**: Tests both client-side and server-side validation
-   **Bug Documentation**: Failing tests provide clear bug reports in Bahasa Indonesia

## Notes

-   Negative test cases **intentionally FAIL** to document system validation bugs
-   All test names and error messages are in Bahasa Indonesia
-   Tests include detailed debug output for troubleshooting
-   Comprehensive coverage of CRUD operations and edge cases
