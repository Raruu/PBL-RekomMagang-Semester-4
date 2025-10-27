import * as dotenv from "dotenv";
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

export const FILE_PATHS = {};

export const getUrlWithBase = (url: string) => BASE_URL + url;
