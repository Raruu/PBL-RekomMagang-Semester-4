// @ts-nocheck
import Chart from "chart.js/auto";

const initSatisfactionPercentageChart = () => {
    const data = {
        labels: ["Puas", "Tidak Puas"],
        datasets: [
            {
                data: [0, 0],
                backgroundColor: ["#089cfc", "#dc3545"],
                borderWidth: 2,
                borderColor: "#fff",
            },
        ],
    };

    const chart = new Chart(document.getElementById("percent-puas-chart"), {
        type: "doughnut",
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    enabled: false,
                },
            },
            cutout: "70%",
        },
        plugins: [
            {
                id: "centerText",
                beforeDraw: function (chart) {
                    var width = chart.width,
                        height = chart.height,
                        ctx = chart.ctx;

                    ctx.restore();
                    var fontSize = (height / 144).toFixed(2);
                    ctx.font = fontSize + "em sans-serif";
                    ctx.textBaseline = "middle";

                    var total = data.datasets[0].data.reduce(
                        (sum, value) => sum + value,
                        0
                    );
                    var percentage =
                        ((data.datasets[0].data[0] / total) * 100).toFixed(1) +
                        "%";
                    var textX = Math.round(
                            (width - ctx.measureText(percentage).width) / 2
                        ),
                        textY = height / 2;

                    ctx.fillStyle = chart.options.centerTextColor || "#000000";
                    ctx.fillText(percentage, textX, textY);
                    ctx.save();
                },
            },
        ],
    });

    const setData = (avg) => {
        chart.data.datasets[0].data = avg == 0 ? [0, 0] : [avg, 100 - avg];
        chart.update();
    };

    return { chart: chart, setData: setData };
};

window.chartSatisfaction = initSatisfactionPercentageChart();

document.addEventListener("DOMContentLoaded", () => {
    const updateChartStyles = () => {
        const chart = window.chartSatisfaction.chart;
        chart.options.centerTextColor = getComputedStyle(
            document.documentElement
        ).getPropertyValue("--foreground");
        chart.update();
    };
    document.documentElement.addEventListener(
        "ColorSchemeChange",
        updateChartStyles
    );
    updateChartStyles();
});

const initActivityChart = () => {
    const chart = new Chart(document.getElementById("activityChart"), {
        type: "doughnut",
        data: {
            labels: ["Aktif", "Nonaktif"],
            datasets: [
                {
                    data: [0, 0],
                    backgroundColor: ["#28a745", "#dc3545"],
                    borderWidth: 3,
                    borderColor: "#fff",
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "bottom",
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                    },
                },
            },
            cutout: "60%",
        },
    });

    const setData = (data1 = 0, data2 = 0) => {
        chart.data.datasets[0].data = [data1, data2];
        chart.update();
    };

    return { chart: chart, setData: setData };
};

window.chartActivity = initActivityChart();

document.addEventListener("DOMContentLoaded", () => {
    const updateChartStyles = () => {
        const chart = window.chartActivity.chart;
        chart.options.plugins.legend.labels.color = getComputedStyle(
            document.documentElement
        ).getPropertyValue("--foreground");
        chart.update();
    };
    document.documentElement.addEventListener(
        "ColorSchemeChange",
        updateChartStyles
    );
    updateChartStyles();
});

const initVerificationChart = () => {
    const chart = new Chart(document.getElementById("verificationChart"), {
        type: "doughnut",
        data: {
            labels: [
                "Terverifikasi",
                "Menunggu Verifikasi",
                "Belum Terverifikasi",
            ],
            datasets: [
                {
                    data: [0, 0, 0],
                    backgroundColor: ["#17a2b8", "#ffc107", "#dc3545"],
                    borderWidth: 2,
                    borderColor: "#fff",
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "bottom",
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                    },
                },
            },
            cutout: "60%",
        },
    });

    const setData = ({ verified = 0, meminta_verif = 0, unverified = 0 }) => {
        chart.data.datasets[0].data = [verified, meminta_verif, unverified];
        chart.update();
    };

    return { chart: chart, setData: setData };
};

window.chartVerification = initVerificationChart();

document.addEventListener("DOMContentLoaded", () => {
    const updateChartStyles = () => {
        const chart = window.chartVerification.chart;
        chart.options.plugins.legend.labels.color = getComputedStyle(
            document.documentElement
        ).getPropertyValue("--foreground");
        chart.update();
    };
    document.documentElement.addEventListener(
        "ColorSchemeChange",
        updateChartStyles
    );
    updateChartStyles();
});
