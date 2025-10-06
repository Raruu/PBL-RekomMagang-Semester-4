// @ts-nocheck
import Chart from "chart.js/auto";

const initChart = () => {
    const chartMagangVsTidak = new Chart(
        document.getElementById("chart-magang-vs-tidak"),
        {
            type: "line",
            data: {
                labels: [],
                datasets: [
                    {
                        label: "Mendapatkan Magang",
                        backgroundColor: "rgba(54, 162, 235, 1)",
                        borderColor: "rgba(54, 162, 235, 1)",
                        pointBackgroundColor: "blue",
                        pointBorderColor: "#fff",
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        borderWidth: 2,
                        tension: 0.1,
                        data: [],
                        yAxisID: "y",
                    },
                    {
                        label: "Tidak Mendapatkan Magang",
                        backgroundColor: "red",
                        borderColor: "rgba(255, 99, 132, 1)",
                        pointBackgroundColor: "red",
                        pointBorderColor: "#fff",
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
                            text: "Tahun Angkatan",
                        },
                    },
                    y: {
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

    const setData = (labels, data1, data2) => {
        chartMagangVsTidak.data.labels = labels;
        chartMagangVsTidak.data.datasets[0].data = data1;
        chartMagangVsTidak.data.datasets[1].data = data2;
        chartMagangVsTidak.update();
    };

    return { chart: chartMagangVsTidak, setData: setData };
};

window.chartMagangVsTidak = initChart();

document.addEventListener("DOMContentLoaded", () => {
    const updateStyle = () => {
        const chart = window.chartMagangVsTidak.chart;
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
