import { test as base, expect, type Page } from "@playwright/test";
import dotenv from "dotenv";
import { AUTH_SELECTORS } from "../auth/selector";

dotenv.config();

const BASE_URL = process.env.BASE_URL!;
const USERNAME = process.env.MAHASISWA_USERNAME!;
const PASSWORD = process.env.MAHASISWA_PASSWORD!;

// FIXTURE LOGIN
const test = base.extend<{ pageWithLogin: Page }>({
  pageWithLogin: async ({ page }, use) => {
    await page.goto(`${BASE_URL}/login`);
    await page.waitForSelector(AUTH_SELECTORS.usernameInput, { state: "visible" });
    
    await page.fill(AUTH_SELECTORS.usernameInput, USERNAME.trim());
    await page.fill(AUTH_SELECTORS.passwordInput, PASSWORD.trim());

    await Promise.all([
      page.waitForURL(/dashboard|mahasiswa/i),
      page.click(AUTH_SELECTORS.submitButton),
    ]);

    await use(page);
  },
});

async function pilihSkill(page: Page, id: string, skill: string) {
    const wrapper = page.locator(`#${id}`).locator('..');
    const input = wrapper.locator('tags').getByRole('textbox');
    const dropdown = page.locator('.tagify__dropdown');
    await input.scrollIntoViewIfNeeded();
    await input.click();
    await input.fill('');
    await input.type(skill, { delay: 50 }); 

    // Dropdown Muncul
    await dropdown.waitFor({ state: 'visible', timeout: 5000 });

    // Locator Opsi Spesifik
    const option = dropdown.locator('.tagify__dropdown__item', { hasText: skill }).first();
    
    // Opsi
    try {
        await option.click({ timeout: 5000 });
        await dropdown.waitFor({ state: 'hidden', timeout: 3000 });
        
    } catch (e) {
        throw new Error(`Skill "${skill}" not found in Tagify suggestions or failed to click (Timeout).`);
    }
}

//  TEST CASE

test.describe("UAT Mahasiswa - Edit Profil & Preferensi Magang", () => {
  test("TC_MHS_001 - [Positif] Mahasiswa mengisi informasi pribadi dan preferensi magang", async ({ pageWithLogin }) => {
    const page = pageWithLogin;
    await page.locator('a.nav-group-toggle:has-text("Akun")').click();
    await page.locator('a.nav-link:has-text("Profil")').click();
    await expect(page).toHaveURL(`${BASE_URL}/mahasiswa/profile`);

    await page.locator('a.btn.btn-primary:has-text("Edit Profil")').click();
    await expect(page).toHaveURL(`${BASE_URL}/mahasiswa/profile/edit`);

    // Isi informasi pribadi
    await page.waitForSelector('input[name="email"]', { state: "visible" });
    await page.fill('input[name="nomor_telepon"]', "08123456789");

    // Pemilihan lokasi
    await page.locator('button[onclick^="alamatPickLocation"]').click();
  
    const modalLokasi = page.locator('.modal.show');
    await modalLokasi.waitFor({ state: "visible", timeout: 15000 });
    await modalLokasi.locator('[id^="map"]').waitFor({ state: "visible", timeout: 15000 });
    await page.mouse.click(400, 300);

    // tombol Simpan di modal Ambil Lokasi
    const simpanButtonModal = modalLokasi.locator('button:has-text("Simpan")');
    await simpanButtonModal.waitFor({ state: "visible", timeout: 10000 });
    await simpanButtonModal.click();

    // modal tertutup
    await modalLokasi.waitFor({ state: "hidden", timeout: 15000 }); 
    await page.waitForTimeout(1500); 

    // Preferensi Magang
    await page.locator('a#collapsePreferensi').click(); 
    
    // tab Preferensi Magang muncul
    await page.waitForSelector('input[name="posisi_preferensi"]', { state: "visible" });
    await page.locator('button[onclick^="preferensiPickLocation"]').click();
        const modalLokasiPreferensi = page.locator('.modal.show');
    await modalLokasiPreferensi.waitFor({ state: "visible", timeout: 15000 }); 

    // map 
    await modalLokasiPreferensi.locator('[id^="map"]').waitFor({ state: "visible", timeout: 15000 });
    await page.mouse.click(400, 300);

    // tombol Simpan di modal Ambil Lokasi
    const simpanButtonModalPreferensi = modalLokasiPreferensi.locator('button:has-text("Simpan")'); // Variabel baru
    await simpanButtonModalPreferensi.waitFor({ state: "visible", timeout: 10000 });
    await simpanButtonModalPreferensi.click();
    // modal tertutup
    await modalLokasiPreferensi.waitFor({ state: "hidden", timeout: 15000 }); 
    await page.waitForTimeout(1500);
    // Isi Preferensi Magang
    await page.fill('input[name="posisi_preferensi"]', "Data Analyst, UI/UX Designer");
    await page.locator('h4:has-text("Preferensi Magang")').scrollIntoViewIfNeeded();
    // Pilih tipe kerja
    await page.waitForSelector('select#tipe_kerja_preferensi', { state: 'visible', timeout: 10000 });
    await page.selectOption('#tipe_kerja_preferensi', { label: 'Onsite (Work in Office)' });

   // Tentukan tombol Simpan
   const simpanButtonUtama = page.getByRole('button', { name: 'Simpan' }).and(page.locator('[type="submit"]'));
   await simpanButtonUtama.scrollIntoViewIfNeeded();
   await simpanButtonUtama.waitFor({ state: 'visible', timeout: 5000 });
   // Klik tombol Simpan utama
   await simpanButtonUtama.click();
   // notifikasi berhasil
   await expect(page.getByRole('heading', { name: 'Berhasil!' })).toBeVisible({ timeout: 10000 });
  });
});

test.describe("UAT Mahasiswa - Edit Profil: Keahlian", () => {
  test("TC_MHS_002 - [Positif] Mahasiswa mengisi keahlian", async ({ pageWithLogin }) => {
    const page = pageWithLogin;
    await page.locator('a.nav-group-toggle:has-text("Akun")').click();
    await page.locator('a.nav-link:has-text("Profil")').click();
    await expect(page).toHaveURL(`${BASE_URL}/mahasiswa/profile`);
    await page.locator('a.btn.btn-primary:has-text("Edit Profil")').click();
    await expect(page).toHaveURL(`${BASE_URL}/mahasiswa/profile/edit`);
    await page.waitForLoadState("networkidle");
    // tab Keahlian
    const tabKeahlian = page.locator('a#collapseSkill.nav-link');
    await tabKeahlian.click();
    const collapseSkill = page.locator('div#collapseSkill.collapse');
    await expect(collapseSkill).toHaveClass(/show/, { timeout: 15000 });

    const menengahTags = page.locator('#keahlian-menengah ~ tags tag x');
      while (await menengahTags.count() > 0) {
        await menengahTags.first().click();
        await expect(menengahTags.first()).not.toBeVisible({ timeout: 2000 });
      }
    await pilihSkill(page, "keahlian-ahli", "SQL Server");
    await pilihSkill(page, "keahlian-mahir", "Unity");
    await pilihSkill(page, "keahlian-menengah", "MongoDB");
    await pilihSkill(page, "keahlian-pemula", "Java");
    //simpan
    const simpanButton = page.getByRole('button', { name: 'Simpan' });
    await simpanButton.scrollIntoViewIfNeeded();
    await simpanButton.click();
    await page.waitForLoadState('networkidle');
    // berhasil
    await expect(page.getByRole('heading', { name: 'Berhasil!' })).toBeVisible({ timeout: 15000 });
  });
});