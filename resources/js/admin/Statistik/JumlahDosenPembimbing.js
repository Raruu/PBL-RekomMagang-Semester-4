// @ts-nocheck
import Chart from "chart.js/auto";

const initChart = () => {
    const chartJumlahDosenPembimbing = new Chart(
        document.getElementById("chart-jumlah-dosen-pembimbing"),
        {
            type: "bar",
            data: {
                labels: [],
                datasets: [
                    {
                        label: "Dosen Pembimbing",
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        borderWidth: 2,
                        tension: 0.1,
                        data: [],
                        yAxisID: "y",
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: "index",
                    intersect: false,
                },
                plugins: {
                    tooltip: {
                        mode: "index",
                        intersect: false,
                    },
                    legend: {
                        position: "top",
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: "Program Studi",
                        },
                    },
                    y: {
                        stacked: true,
                        type: "linear",
                        display: true,
                        position: "left",
                        title: {
                            display: true,
                            text: "Jumlah",
                        },
                    },
                },
            },
        }
    );

    const setData = (labels, data) => {
        chartJumlahDosenPembimbing.data.labels = labels;
        chartJumlahDosenPembimbing.data.datasets[0].data = data;

        chartJumlahDosenPembimbing.update();
    };

    return { chart: chartJumlahDosenPembimbing, setData: setData };
};

window.chartJumlahDosenPembimbing = initChart();

document.addEventListener("DOMContentLoaded", () => {
    const updateStyle = () => {
        const chart = window.chartJumlahDosenPembimbing.chart;
        chart.options.plugins.title.color = getComputedStyle(
            document.documentElement
        ).getPropertyValue("--foreground");
        chart.options.plugins.legend.labels.color = getComputedStyle(
            document.documentElement
        ).getPropertyValue("--foreground");
        chart.options.scales.x.ticks.color = getComputedStyle(
            document.documentElement
        ).getPropertyValue("--foreground");
        chart.options.scales.x.title.color = getComputedStyle(
            document.documentElement
        ).getPropertyValue("--foreground");
        chart.options.scales.x.grid.color = getComputedStyle(
            document.documentElement
        ).getPropertyValue("--foreground-quarter");
        chart.options.scales.y.ticks.color = getComputedStyle(
            document.documentElement
        ).getPropertyValue("--foreground");
        chart.options.scales.y.title.color = getComputedStyle(
            document.documentElement
        ).getPropertyValue("--foreground");
        chart.options.scales.y.grid.color = getComputedStyle(
            document.documentElement
        ).getPropertyValue("--foreground-quarter");

        chart.update();
    };
    document.documentElement.addEventListener("ColorSchemeChange", () => {
        updateStyle();
    });
    updateStyle();
});
