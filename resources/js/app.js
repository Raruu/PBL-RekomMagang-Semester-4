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

// BTN SPINNER
const spinBtnSubmit = (
    target,
    spinnerId = "btn-submit-spinner",
    textId = "btn-submit-text"
) => {
    const btnSpiner = target.querySelector(`#${spinnerId}`);
    const btnSubmitText = target.querySelector(`#${textId}`);
    btnSpiner.closest("button").disabled = true;
    const nextSibling = btnSpiner.closest("button").nextElementSibling;
    if (nextSibling) nextSibling.disabled = true;
    const prevSibling = btnSpiner.closest("button").previousElementSibling;
    if (prevSibling) prevSibling.disabled = true;
    btnSubmitText.classList.add("d-none");
    btnSpiner.classList.remove("d-none");
};

const resetBtnSubmit = (
    target,
    spinnerId = "btn-submit-spinner",
    textId = "btn-submit-text"
) => {
    const btnSpiner = target.querySelector(`#${spinnerId}`);
    const btnSubmitText = target.querySelector(`#${textId}`);
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

// MEDIA QUERY
const mediaQuery = () => {
    const xsQuery = window.matchMedia("(max-width: 575.98px)");
    const smQuery = window.matchMedia(
        "(min-width: 576px) and (max-width: 767.98px)"
    );
    const mdQuery = window.matchMedia(
        "(min-width: 768px) and (max-width: 991.98px)"
    );
    const lgQuery = window.matchMedia(
        "(min-width: 992px) and (max-width: 1199.98px)"
    );
    const xlQuery = window.matchMedia(
        "(min-width: 1200px) and (max-width: 1399.98px)"
    );
    const xxlQuery = window.matchMedia("(min-width: 1400px)");

    const useMediaQuery = [];

    const checkMediaQueryResult = () => {
        if (xsQuery.matches) return "xs";
        if (smQuery.matches) return "sm";
        if (mdQuery.matches) return "md";
        if (lgQuery.matches) return "lg";
        if (xlQuery.matches) return "xl";
        if (xxlQuery.matches) return "2xl";
    };
    const onChange = () => {
        const result = checkMediaQueryResult();
        useMediaQuery.forEach((func) => {
            func(result);
        });
    };

    xsQuery.addEventListener("change", onChange);
    smQuery.addEventListener("change", onChange);
    mdQuery.addEventListener("change", onChange);
    lgQuery.addEventListener("change", onChange);
    xlQuery.addEventListener("change", onChange);
    xxlQuery.addEventListener("change", onChange);

    return {
        arr: useMediaQuery,
        change: onChange,
    };
};
window.useMediaQuery = mediaQuery();

const sanitizeString = (str) => {
    const doc = new DOMParser().parseFromString(
        str.replace(/<[^>]*>/g, ""),
        "text/html"
    );
    return doc.body.textContent || "";
};
window.sanitizeString = sanitizeString;

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

        // setTimeout(() => {
        //     const rect = sidebar.getBoundingClientRect();
        //     isMouseEnter = rect.width > 64;
        //     setLogo();
        // }, 700);

        setLogo();
    }
    document.body.style.opacity = "";
    window.useMediaQuery.change();
});
