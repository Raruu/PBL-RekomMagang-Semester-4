// @ts-nocheck
import Chart from "chart.js/auto";

const initChart = () => {
    const chartPeminatanMahasiswa = new Chart(
        document.getElementById("chart-peminatan-mahasiswa"),
        {
            type: "line",
            data: {
                labels: [],
                datasets: [],
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
                            text: "Tahun",
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

    const setData = (labels, labelDatasets, data) => {
        chartPeminatanMahasiswa.data.labels = labels;

        chartPeminatanMahasiswa.data.datasets = labelDatasets.map((label) => ({
            label: label,
            data: [],
            pointBorderColor: "#fff",
            pointRadius: 5,
            pointHoverRadius: 7,
            borderWidth: 2,
            tension: 0.1,
            yAxisID: "y",
            fill: true,
        }));

        data.forEach((item, index) => {
            chartPeminatanMahasiswa.data.datasets[index].data = item;
        });

        chartPeminatanMahasiswa.update();
    };

    return { chart: chartPeminatanMahasiswa, setData: setData };
};

window.chartPeminatanMahasiswa = initChart();

document.addEventListener("DOMContentLoaded", () => {
    const updateStyle = () => {
        const chart = window.chartPeminatanMahasiswa.chart;
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
