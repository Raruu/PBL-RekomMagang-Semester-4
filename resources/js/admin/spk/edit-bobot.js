// @ts-nocheck
import Chart from "chart.js/auto";

const initChart = () => {
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

const run = () => {
    const bobotChart = initChart();
    const bobotInput = document.querySelector(".bobot_input");
    const inputIpk = document.querySelector("#bobot_ipk");
    const inputSkill = document.querySelector("#bobot_skill");
    const inputPengalaman = document.querySelector("#bobot_pengalaman");
    const inputJarak = document.querySelector("#bobot_jarak");
    const inputPosisi = document.querySelector("#bobot_posisi");

    const sliders = [
        inputIpk,
        inputSkill,
        inputPengalaman,
        inputJarak,
        inputPosisi,
    ];

    const numberInputs = [
        inputIpk.nextElementSibling,
        inputSkill.nextElementSibling,
        inputPengalaman.nextElementSibling,
        inputJarak.nextElementSibling,
        inputPosisi.nextElementSibling,
    ];

    bobotChart.data.datasets[0].data = [
        parseFloat(inputIpk.value),
        parseFloat(inputSkill.value),
        parseFloat(inputPengalaman.value),
        parseFloat(inputJarak.value),
        parseFloat(inputPosisi.value),
    ];
    bobotChart.update();

    const btnSubmit = document.querySelector("#btn-submit");

    bobotInput.querySelectorAll("input").forEach((input) => {
        input.addEventListener("input", () => {
            if (input.type === "number") {
                sliders.forEach((slider, index) => {
                    slider.value = numberInputs[index].value;
                });
            }

            const inputValue = [
                parseFloat(inputIpk.value),
                parseFloat(inputSkill.value),
                parseFloat(inputPengalaman.value),
                parseFloat(inputJarak.value),
                parseFloat(inputPosisi.value),
            ];

            const sum = inputValue.reduce((prev, curr) => prev + curr, 0.0);
            btnSubmit.disabled = sum > 1.0;

            if (input.type === "range") {
                numberInputs.forEach((numberInput, index) => {
                    numberInput.value = inputValue[index];
                });
            }

            bobotChart.data.datasets[0].data = inputValue;
            bobotChart.update();
        });
    });

    const normalize = () => {
        const inputValue = [
            parseFloat(inputIpk.value),
            parseFloat(inputSkill.value),
            parseFloat(inputPengalaman.value),
            parseFloat(inputJarak.value),
            parseFloat(inputPosisi.value),
        ];

        const sum = inputValue.reduce(
            (prev, curr) => parseFloat(parseFloat(prev + curr).toPrecision(5)),
            0.0
        );

        const normalizedInputValue = inputValue.map(
            (value) => parseFloat(value).toPrecision(5) / sum
        );

        btnSubmit.disabled =
            normalizedInputValue.reduce((prev, curr) => prev + curr, 0.0) > 1.0;

        sliders.forEach((slider, index) => {
            slider.value = normalizedInputValue[index];
        });
        numberInputs.forEach((numberInput, index) => {
            numberInput.value = normalizedInputValue[index];
        });

        bobotChart.data.datasets[0].data = normalizedInputValue;
        bobotChart.update();
        return normalizedInputValue;
    };

    return {
        bobotChart: bobotChart,
        normalize: async () => {
            normalize();
            return normalize();
        },
    };
};
window.editBobot = run();

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
    };
    document.documentElement.addEventListener("ColorSchemeChange", () => {
        updateStyle();
    });
    updateStyle();
});
