export class PieChart {
    static create(chartId, labels, values, colors) {
        const data = {
            labels: labels,
            datasets: [
                {
                    data: values,
                    backgroundColor: colors,
                    hoverOffset: 4,
                },
            ],
        };
        return this.#createChart(chartId, data, values);
    }
    static createAllVisible(chartId, labels, values, colors) {
        const total = values.reduce((a, v) => a + v);
        const valuesInPercent = values.map((v) => Math.max((v / total) * 100, 1));
        const data = {
            labels: labels,
            datasets: [
                {
                    data: valuesInPercent,
                    backgroundColor: colors,
                    hoverOffset: 4,
                },
            ],
        };
        return this.#createChart(chartId, data, values);
    }

    static #createChart(chartId, data, values) {
        const legendId = `${chartId}-legend`;
        const ctx = document.getElementById(chartId).getContext("2d");
        const config = {
            type: "pie",
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutoutPercentage: 65,
                plugins: {
                    htmlLegend: {
                        containerID: legendId,
                    },
                    legend: {
                        display: false,
                        position: "right",
                        labels: {
                            generateLabels: (chart) => {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    const { labels: { pointStyle }, } = chart.legend.options;
                                    return data.labels.map((label, i) => {
                                        const meta = chart.getDatasetMeta(0);
                                        const style = meta.controller.getStyle(i);
                                        const dataSet = meta.controller.getDataset();
                                        return {
                                            text: `${label} (${dataSet.data[i]})`,
                                            fillStyle: style.backgroundColor,
                                            strokeStyle: style.borderColor,
                                            lineWidth: style.borderWidth,
                                            pointStyle: pointStyle,
                                            hidden: !chart.getDataVisibility(i),
                                            index: i,
                                        };
                                    });
                                }
                                return [];
                            },
                        },
                    },
                    datalabels: {
                        display: false,
                    },
                    tooltip: {
                        enabled: true,
                        mode: "nearest",
                        callbacks: {
                            label: function (tooltipItem) {
                                const value = values[tooltipItem.dataIndex];
                                const label = tooltipItem.label;
                                return `${label}: ${value}`;
                            },
                        },
                    },
                },
                elements: {
                    arc: {
                        borderWidth: 0,
                    },
                },
            },
            plugins: [this.#htmlLegendPlugin()],
        };
        return new Chart(ctx, config);
    }

    static #getOrCreateLegendList(id) {
        const legendContainer = document.getElementById(id);
        let listContainer = legendContainer.querySelector("ul");
        if (!listContainer) {
            listContainer = document.createElement("ul");
            listContainer.style.margin = 0;
            listContainer.style.padding = 0;

            legendContainer.appendChild(listContainer);
        }

        return listContainer;
    }

    static #htmlLegendPlugin() {
        return {
            id: "htmlLegend",
            afterUpdate(chart, args, options) {
                const ul = PieChart.#getOrCreateLegendList(options.containerID);

                // Remove old legend items
                while (ul.firstChild) {
                    ul.firstChild.remove();
                }

                // Reuse the built-in legendItems generator
                const items = chart.options.plugins.legend.labels.generateLabels(chart);
                items.forEach((item) => {
                    const li = document.createElement("li");
                    li.style.alignItems = "center";
                    li.style.cursor = "pointer";
                    li.style.display = "flex";
                    li.style.flexDirection = "row";
                    li.style.marginLeft = "10px";

                    li.onclick = () => {
                        const { type } = chart.config;
                        if (type === "pie" || type === "doughnut") {
                            // Pie and doughnut charts only have a single dataset and visibility is per item
                            chart.toggleDataVisibility(item.index);
                        } else {
                            chart.setDatasetVisibility(item.datasetIndex, !chart.isDatasetVisible(item.datasetIndex));
                        }
                        chart.update();
                    };

                    // Color box
                    const boxSpan = document.createElement("span");
                    boxSpan.style.background = item.fillStyle;
                    boxSpan.style.borderColor = item.strokeStyle;
                    boxSpan.style.borderWidth = item.lineWidth + "px";
                    boxSpan.style.display = "inline-block";
                    boxSpan.style.height = "20px";
                    boxSpan.style.marginRight = "10px";
                    boxSpan.style.width = "20px";

                    // Text
                    const textContainer = document.createElement("p");
                    textContainer.style.color = item.fontColor;
                    textContainer.style.margin = 0;
                    textContainer.style.padding = 0;
                    textContainer.style.textDecoration = item.hidden ? "line-through" : "";

                    const text = document.createTextNode(item.text);
                    textContainer.appendChild(text);

                    li.appendChild(boxSpan);
                    li.appendChild(textContainer);
                    ul.appendChild(li);
                });
            },
        };
    }
}
