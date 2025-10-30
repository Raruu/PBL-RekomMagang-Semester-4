// constants/admin.routes.ts
// (Simpan ini di folder baru, misal 'tests/e2e/constants')

export const BASE_URL = 'http://localhost:8000'; // Ganti dengan URL dev kamu

export const ADMIN_ROUTES = {
    login: `${BASE_URL}/login`,
    dashboard: `${BASE_URL}/admin/dashboard`,
    
    // Lowongan
    lowonganIndex: `${BASE_URL}/admin/magang/lowongan`,
    lowonganCreate: `${BASE_URL}/admin/magang/lowongan/create`,
    lowonganLanjutan: (id: string | number) => `${BASE_URL}/admin/magang/lowongan/${id}/lanjutan`,
    
    // API (Hanya untuk referensi, karena kita tidak mocking)
    // API_DATATABLES: `${BASE_URL}/api/admin/magang/lowongan/data`,
    // API_STORE_STEP1: `${BASE_URL}/admin/magang/lowongan/store`,
    // API_STORE_STEP2: (id: string | number) => `${BASE_URL}/admin/magang/lowongan/${id}/lanjutan/store`,
    // API_UPDATE: (id: string | number) => `${BASE_URL}/api/admin/magang/lowongan/${id}`,
    // API_DELETE: (id: string | number) => `${BASE_URL}/api/admin/magang/lowongan/${id}`,
};