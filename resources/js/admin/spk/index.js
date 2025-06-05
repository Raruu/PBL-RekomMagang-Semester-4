// @ts-nocheck
import Chart from "chart.js/auto";

const initBobotChart = () => {
    const _initBobotChart = () => {
        const ctx = document.querySelector("#bobot-chart");
        const data = {
            labels: ["IPK", "Skill", "Pengalaman", "Jarak", "Posisi"],
            datasets: [
                {
                    data: [50, 50, 50, 50, 50],
                },
            ],
        };
        return new Chart(ctx, {
            type: "pie",
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "bottom",
                        labels: {
                            color: getComputedStyle(
                                document.documentElement
                            ).getPropertyValue("--foreground"),
                        },
                    },
                    title: {
                        display: true,
                        text: "Bobot SPK",
                        color: getComputedStyle(
                            document.documentElement
                        ).getPropertyValue("--foreground"),
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let label = context.label || "";
                                if (label) {
                                    label += ": ";
                                }
                                const currentValue = context.raw;
                                const total = context.dataset.data.reduce(
                                    (sum, value) => sum + value,
                                    0
                                );
                                const percentage = (
                                    (currentValue / total) *
                                    100
                                ).toFixed(2);

                                return label + percentage + "%";
                            },
                        },
                    },
                },
            },
        });
    };
    const bobotChart = _initBobotChart();
    const bobotInput = document.querySelector(".bobot_input");
    const inputIpk = document.querySelector("#bobot_ipk");
    const inputSkill = document.querySelector("#bobot_skill");
    const inputPengalaman = document.querySelector("#bobot_pengalaman");
    const inputJarak = document.querySelector("#bobot_jarak");
    const inputPosisi = document.querySelector("#bobot_posisi");

    bobotChart.data.datasets[0].data = [
        parseFloat(inputIpk.value),
        parseFloat(inputSkill.value),
        parseFloat(inputPengalaman.value),
        parseFloat(inputJarak.value),
        parseFloat(inputPosisi.value),
    ];
    bobotChart.update();

    return {
        bobotChart: bobotChart,
    };
};
window.editBobot = initBobotChart();

const initPercentageChart = () => {
    const _initPercentageChart = () => {
        const data = {
            labels: ["Puas", "Tidak Puas"],
            datasets: [
                {
                    data: [0, 0],
                },
            ],
        };

        return new Chart(document.querySelector("#percent-puas-chart"), {
            type: "doughnut",
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        enabled: false,
                    },
                },
            },
            plugins: [
                {
                    id: "text",
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
                            ((data.datasets[0].data[0] / total) * 100).toFixed(
                                1
                            ) + "%";
                        var textX = Math.round(
                                (width - ctx.measureText(percentage).width) / 2
                            ),
                            textY = height / 2;

                        ctx.fillStyle =
                            chart.options.centerTextColor || "#000000";
                        ctx.fillText(percentage, textX, textY);
                        ctx.save();
                    },
                },
            ],
        });
    };
    const percentageChart = _initPercentageChart();

    const setData = (avg) => {
        percentageChart.data.datasets[0].data =
            avg == 0 ? [0, 0] : [avg, 100 - avg];
        percentageChart.update();
    };
    return { percentageChart: percentageChart, setData: setData };
};
window.percentageChart = initPercentageChart();

document.addEventListener("DOMContentLoaded", () => {
    const updateStyle = () => {
        const bobotChart = window.editBobot.bobotChart;
        bobotChart.options.plugins.title.color = getComputedStyle(
            document.documentElement
        ).getPropertyValue("--foreground");
        bobotChart.options.plugins.legend.labels.color = getComputedStyle(
            document.documentElement
        ).getPropertyValue("--foreground");
        bobotChart.update();

        const percentageChart = window.percentageChart.percentageChart;
        percentageChart.options.centerTextColor = getComputedStyle(
            document.documentElement
        ).getPropertyValue("--foreground");
        percentageChart.update();
    };
    document.documentElement.addEventListener("ColorSchemeChange", () => {
        updateStyle();
    });
    updateStyle();
});
