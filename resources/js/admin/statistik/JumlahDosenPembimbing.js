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
                    x1: {
                        position: "top",
                        labels: [],
                    },
                    y: {
                        stacked: false,
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

    const setData = (labels, dataDosens, dataMahasiswas) => {
        chartJumlahDosenPembimbing.data.labels = labels;
        chartJumlahDosenPembimbing.data.datasets[0].data = dataDosens;

        if (dataMahasiswas != null) {
            const dataset = {
                label: "Mahasiswa",
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 2,
                tension: 0.1,
                data: dataMahasiswas,
                yAxisID: "y",
                backgroundColor: "rgba(255, 99, 132, 0.5)",
                borderColor: "rgba(255, 99, 132, 1)",
            };
            if (chartJumlahDosenPembimbing.data.datasets.length < 2) {
                chartJumlahDosenPembimbing.data.datasets.push(dataset);
            } else {
                chartJumlahDosenPembimbing.data.datasets[1] = dataset;
            }

            const ratios = dataDosens.map((dosens, index) => {
                const dosen = dosens || 0;
                const mahasiswa = dataMahasiswas[index] || 0;
                const gcd = (a, b) => (b === 0 ? a : gcd(b, a % b));
                const g = gcd(dosen, mahasiswa);
                return [dosen / g, mahasiswa / g];
            });
            chartJumlahDosenPembimbing.options.scales.x1.labels = ratios.map(
                (ratio) => `${ratio[0]}:${ratio[1]}`
            );
        } else if (chartJumlahDosenPembimbing.data.datasets.length > 1) {
            chartJumlahDosenPembimbing.data.datasets.pop();
            chartJumlahDosenPembimbing.options.scales.x1.labels = [];
        }

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
