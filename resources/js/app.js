import "./bootstrap";
// Simplebar
import "simplebar/dist/simplebar.min.js";

// CoreUI
import "@coreui/chartjs/dist/js/coreui-chartjs.js";
import "@coreui/utils/dist/umd/index.js";
import "./CoreUI/config.js";
import "./CoreUI/color-modes.js";
import * as coreui from "@coreui/coreui";
// @ts-ignore
window.coreui = coreui;

// CSRF token setup
import $ from "jquery";
import 'jquery-validation';
// @ts-ignore
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

import Swal from 'sweetalert2';
// @ts-ignore
window.Swal = Swal;
