// @ts-nocheck
import "./bootstrap";
// Simplebar
import "simplebar/dist/simplebar.min.js";

// CoreUI
import "@coreui/chartjs/dist/js/coreui-chartjs.js";
import "@coreui/utils/dist/umd/index.js";
import "./CoreUI/config.js";
import "./CoreUI/color-modes.js";
import * as coreui from "@coreui/coreui";
window.coreui = coreui;

// CSRF token setup
import $ from "jquery";
import "jquery-validation";

window.$ = $;
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

// DataTables
import "datatables.net-bs5";
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";

const header = document.querySelector("header.header");
document.addEventListener("scroll", () => {
    if (header) {
        header.classList.toggle(
            "shadow-sm",
            document.documentElement.scrollTop > 0
        );
    }
});

import Swal from "sweetalert2";

window.Swal = Swal;

const setStateSidebar = () => {
    const sidebar = document.getElementById("sidebar");
    localStorage.setItem(
        "sidebar-narrow-unfoldable",
        sidebar.classList.contains("sidebar-narrow-unfoldable").toString()
    );
};

window.setStateSidebar = setStateSidebar;

document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");
    if (sidebar) {
        if (localStorage.getItem("sidebar-narrow-unfoldable") === "true") {
            sidebar.classList.add("sidebar-narrow-unfoldable");
        }
        sidebar.style.transition = "";
        sidebar.nextElementSibling.style.transition = "";
    }
    document.body.style.opacity = "";
});
