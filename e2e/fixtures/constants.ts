import * as dotenv from "dotenv";
import * as path from "path";
dotenv.config();

export const BASE_URL = process.env.BASE_URL || "";

export const MAHASISWA_CREDENTIALS = {
    username: process.env.MAHASISWA_USERNAME || "",
    password: process.env.MAHASISWA_PASSWORD || "",
};

export const DOSEN_CREDENTIALS = {
    username: process.env.DOSEN_USERNAME || "",
    password: process.env.DOSEN_PASSWORD || "",
};

export const ADMIN_CREDENTIALS = {
    username: process.env.ADMIN_USERNAME || "",
    password: process.env.ADMIN_PASSWORD || "",
};

export const FILE_PATHS = {
    CV: path.resolve(process.cwd(), "e2e", "document", "CV.pdf"),
    KETERANGAN_MAGANG: path.resolve(
        process.cwd(),
        "e2e",
        "document",
        "KETERANGAN_MAGANG.pdf"
    ),
    KTP: path.resolve(process.cwd(), "e2e", "document", "KTP.pdf"),
    SKK: path.resolve(
        process.cwd(),
        "e2e",
        "document",
        "skk-2341720157-83SsA.pdf"
    ),
    TRANSKRIP_NILAI: path.resolve(
        process.cwd(),
        "e2e",
        "document",
        "TRANSKRIP NILAI.pdf"
    ),
    BUKAN_PDF: path.resolve(
        process.cwd(),
        "e2e",
        "document",
        "Negatif",
        "BUKAN_PDF.png"
    ),
    PDF_5MB: path.resolve(
        process.cwd(),
        "e2e",
        "document",
        "Negatif",
        "PDF_5MB.pdf"
    ),
};

export const getUrlWithBase = (url: string) => BASE_URL + url;
