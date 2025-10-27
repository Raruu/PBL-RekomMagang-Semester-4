# 🚀 Quick Start Guide - Testing Dosen

Panduan cepat untuk menjalankan end-to-end testing modul Dosen.

## ⚡ Setup Cepat (5 Menit)

### 1. Install Dependencies

```bash
npm install
npx playwright install chromium
```

### 2. Buat File .env

Copy `.env.example` ke `.env` dan isi dengan kredensial testing:

```bash
copy .env.example .env
```

Edit file `.env`:

```env
BASE_URL=http://localhost:8000
DOSEN_USERNAME=dosen123
DOSEN_PASSWORD=password123
```

### 3. Jalankan Laravel Server

Buka terminal baru dan jalankan:

```bash
php artisan serve
```

### 4. Jalankan Test

```bash
# Jalankan semua test dosen
npx playwright test e2e/dosen

# Atau jalankan dengan UI mode
npx playwright test e2e/dosen --ui
```

## 📊 File Test yang Tersedia

| File                         | Deskripsi                       | Test Cases     |
| ---------------------------- | ------------------------------- | -------------- |
| `dashboard.spec.ts`          | Test dashboard dosen            | 5 test cases   |
| `mahasiswaBimbingan.spec.ts` | Test daftar mahasiswa bimbingan | 4 test cases   |
| `logAktivitas.spec.ts`       | Test log aktivitas & feedback   | 5 test cases   |
| `profile.spec.ts`            | Test profile & change password  | 8 test cases   |
| `notifikasi.spec.ts`         | Test notifikasi & navigation    | 10 test cases  |
| `integration.spec.ts`        | Test end-to-end workflow        | 10 test cases  |
| `auth.spec.ts`               | Test authentication & security  | 20+ test cases |

**Total:** 60+ test cases

## 🎯 Test Cases Penting

### Login & Authentication

```bash
npx playwright test e2e/dosen/auth.spec.ts
```

Test kredensial valid/invalid, akses tanpa login, dll.

### Dashboard

```bash
npx playwright test e2e/dosen/dashboard.spec.ts
```

Test tampilan dashboard, statistik, dan navigasi.

### Mahasiswa Bimbingan

```bash
npx playwright test e2e/dosen/mahasiswaBimbingan.spec.ts
```

Test daftar mahasiswa, detail, dan log aktivitas.

### Complete Workflow

```bash
npx playwright test e2e/dosen/integration.spec.ts
```

Test complete user journey dari login sampai logout.

## 🐛 Debug Mode

Jika test gagal, jalankan dengan debug mode:

```bash
npx playwright test e2e/dosen/dashboard.spec.ts --debug
```

## 📸 Generate Report

```bash
# Jalankan test dan generate report
npx playwright test e2e/dosen

# Lihat report
npx playwright show-report
```

## ⚠️ Troubleshooting Cepat

### Test Timeout

**Problem:** Test gagal karena timeout

**Fix:**

```bash
# Pastikan server Laravel running
php artisan serve

# Check BASE_URL di .env
BASE_URL=http://localhost:8000
```

### Login Gagal

**Problem:** Kredensial tidak valid

**Fix:**

1. Periksa username & password di `.env`
2. Pastikan user dosen ada di database:

```bash
php artisan tinker
>>> User::where('role', 'dosen')->first()
```

### Element Not Found

**Problem:** Selector tidak menemukan element

**Fix:**
Gunakan debug mode untuk inspect element:

```bash
npx playwright test --debug
```

## 💡 Tips

1. **Jalankan test satu per satu** saat development:

    ```bash
    npx playwright test e2e/dosen/dashboard.spec.ts
    ```

2. **Gunakan headed mode** untuk melihat browser:

    ```bash
    npx playwright test e2e/dosen --headed
    ```

3. **Filter test** dengan name:

    ```bash
    npx playwright test -g "dashboard"
    ```

4. **Parallel execution** (default):
    ```bash
    npx playwright test e2e/dosen --workers=4
    ```

## 📝 Struktur Test

Setiap test file mengikuti struktur:

```typescript
test.describe("Module - Feature", () => {
    test("TC_ID - Description", async ({ pageWithLogin }) => {
        // Test implementation
    });
});
```

## 🔄 CI/CD Integration

Untuk GitHub Actions, tambahkan:

```yaml
- name: Run Playwright Tests
  run: npx playwright test e2e/dosen
```

## 📚 Resources

-   [Playwright Docs](https://playwright.dev)
-   [README.md lengkap](./README.md)
-   [Selector Guide](./selector.ts)

## 🆘 Butuh Bantuan?

1. Baca [README.md](./README.md) untuk dokumentasi lengkap
2. Check troubleshooting section
3. Run dengan `--debug` flag
4. Lihat test output dan error message

---

**Happy Testing! 🎉**
