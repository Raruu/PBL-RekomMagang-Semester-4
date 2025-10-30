import { Page } from "@playwright/test";
import { AUTH_SELECTORS } from "../auth/selector";
import { ADMIN_CREDENTIALS, getUrlWithBase } from "../fixtures/constants";

export async function loginAsAdmin(page: Page): Promise<void> {
    await page.goto(getUrlWithBase("/login"));

    // Wait for login form to be visible
    await page.waitForSelector(AUTH_SELECTORS.usernameInput, {
        state: "visible",
    });
    await page.waitForSelector(AUTH_SELECTORS.passwordInput, {
        state: "visible",
    });

    // Fill login credentials
    const usernameInput = page.locator(AUTH_SELECTORS.usernameInput);
    const passwordInput = page.locator(AUTH_SELECTORS.passwordInput);

    await usernameInput.click();
    await usernameInput.fill(ADMIN_CREDENTIALS.username);

    await passwordInput.click();
    await passwordInput.fill(ADMIN_CREDENTIALS.password);

    // Submit and wait for redirect to admin dashboard
    await Promise.all([
        page.waitForURL(getUrlWithBase("/admin")),
        page.click(AUTH_SELECTORS.submitButton),
    ]);
}

export async function navigateToMagangKegiatan(page: Page): Promise<void> {
    // Click the Magang menu toggle to expand it
    const magangToggle = page
        .locator("a.nav-link.nav-group-toggle")
        .filter({ hasText: "Magang" });
    await magangToggle.first().click();
    await page.waitForTimeout(1000);

    // Then click the Kegiatan submenu
    const kegiatanLink = page.locator("a").filter({ hasText: "Kegiatan" });
    await kegiatanLink.first().click();

    // Wait for page to load
    await page.waitForURL(/\/admin\/magang\/kegiatan/);
}

export async function navigateToMagangPeriode(page: Page): Promise<void> {
    // Click the Magang menu toggle to expand it
    const magangToggle = page
        .locator("a.nav-link.nav-group-toggle")
        .filter({ hasText: "Magang" });
    await magangToggle.first().click();
    await page.waitForTimeout(1000);

    // Then click the Periode submenu
    const periodeLink = page.locator("a").filter({ hasText: "Periode" });
    await periodeLink.first().click();

    // Wait for page to load
    await page.waitForURL(/\/admin\/magang\/periode/);
}
