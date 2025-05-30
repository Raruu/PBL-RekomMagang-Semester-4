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

// Notification tooltip
const notifications = () => {
    const notificationTooltip = document.querySelector("#notification-tooltip");
    if (notificationTooltip) {
        notificationTooltip.addEventListener("transitionend", () => {
            if (notificationTooltip.style.opacity === "1") {
                notificationTooltip.style.pointerEvents = "all";
                const handleClickOutside = (event) => {
                    if (!notificationTooltip.contains(event.target)) {
                        notificationTooltip.style.opacity = "";
                        document.removeEventListener(
                            "click",
                            handleClickOutside
                        );
                    }
                };
                document.addEventListener("click", handleClickOutside);
            } else {
                notificationTooltip.style.display = "none";
                notificationTooltip.style.pointerEvents = "";
            }
        });

        document.querySelector("#notification-tooltip-button").onclick = () => {
            if (notificationTooltip.style.opacity === "1") {
                notificationTooltip.style.opacity = "";
                return;
            }
            notificationTooltip.style.display = "block";
            setTimeout(() => {
                notificationTooltip.style.opacity = "1";
            }, 1);
        };

        const markRead = async (id, link) => {
            const url = new URL(`/notifikasi/read/${id}`, document.baseURI);
            return $.ajax({
                url: url,
                type: "PATCH",
            });
        };

        const markReadAll = async () => {
            const url = new URL("/notifikasi/readall", document.baseURI);
            return $.ajax({
                url: url,
                type: "PATCH",
            });
        };

        const fetchUnread = async () => {
            const url = new URL("/notifikasi/getunread", document.baseURI);
            try {
                const response = await fetch(url, {
                    method: "GET",
                    headers: { Accept: "application/json" },
                });
                const data = await response.json();
                return data.data;
            } catch (error) {
                console.error("Error fetching tooltip notification:", error);
            }
        };

        return {
            markRead: markRead,
            markReadAll: markReadAll,
            fetchUnread: fetchUnread,
        };
    }
};
window.notifications = notifications();

const spinBtnSubmit = (target) => {
    const btnSpiner = target.querySelector("#btn-submit-spinner");
    const btnSubmitText = target.querySelector("#btn-submit-text");
    btnSpiner.closest("button").disabled = true;
    const nextSibling = btnSpiner.closest("button").nextElementSibling;
    if (nextSibling) nextSibling.disabled = true;
    const prevSibling = btnSpiner.closest("button").previousElementSibling;
    if (prevSibling) prevSibling.disabled = true;
    btnSubmitText.classList.add("d-none");
    btnSpiner.classList.remove("d-none");
};

const resetBtnSubmit = (target) => {
    const btnSpiner = target.querySelector("#btn-submit-spinner");
    const btnSubmitText = target.querySelector("#btn-submit-text");
    btnSubmitText.classList.remove("d-none");
    btnSpiner.classList.add("d-none");
    btnSpiner.closest("button").disabled = false;
    const nextSibling = btnSpiner.closest("button").nextElementSibling;
    if (nextSibling) nextSibling.disabled = false;
    const prevSibling = btnSpiner.closest("button").previousElementSibling;
    if (prevSibling) prevSibling.disabled = false;
};
window.btnSpinerFuncs = {
    spinBtnSubmit: spinBtnSubmit,
    resetBtnSubmit: resetBtnSubmit,
};

document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");
    if (sidebar) {
        if (localStorage.getItem("sidebar-narrow-unfoldable") === "true") {
            sidebar.classList.add("sidebar-narrow-unfoldable");
        }
        sidebar.style.transition = "";
        sidebar.nextElementSibling.style.transition = "";

        const rLogo = sidebar.querySelector("img");
        const rText = sidebar.querySelector("h4");
        let isMouseEnter = false;
        const setLogo = () => {
            if (
                isMouseEnter ||
                !sidebar.classList.contains("sidebar-narrow-unfoldable") ||
                sidebar.classList.contains("show")
            ) {
                rLogo.style.left = "-40px";
                rText.style.opacity = "1";
            } else {
                rLogo.style.left = "0px";
                rText.style.opacity = "0";
            }
        };
        sidebar.addEventListener("mouseover", () => {
            if (!sidebar.classList.contains("sidebar-narrow-unfoldable"))
                return;
            isMouseEnter = true;
            setLogo();
        });
        sidebar.addEventListener("mouseout", () => {
            if (!sidebar.classList.contains("sidebar-narrow-unfoldable"))
                return;
            isMouseEnter = false;
            setLogo();
        });
        setLogo();
    }
    document.body.style.opacity = "";
});
