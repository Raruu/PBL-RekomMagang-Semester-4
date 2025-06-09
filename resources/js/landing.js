// @ts-nocheck
import * as coreui from "@coreui/coreui";
window.coreui = coreui;

document.addEventListener("DOMContentLoaded", () => {
    const carouselMitra = document.querySelector("#carouselMitra");
    if (carouselMitra) {
        carouselMitra.querySelector(".carousel-item").classList.add("active");
    }
});