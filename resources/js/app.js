import './bootstrap';

import Alpine from 'alpinejs';
import {
    CategoryScale,
    Chart,
    Filler,
    LineController,
    LineElement,
    LinearScale,
    PointElement,
    Tooltip,
} from 'chart.js';

Chart.register(
    CategoryScale,
    LineController,
    LineElement,
    LinearScale,
    PointElement,
    Tooltip,
    Filler,
);

window.Alpine = Alpine;

Alpine.start();

const weightChartElements = document.querySelectorAll('[data-weight-chart]');

weightChartElements.forEach((chartElement) => {
    const labels = JSON.parse(chartElement.dataset.labels ?? '[]');
    const values = JSON.parse(chartElement.dataset.values ?? '[]');

    if (labels.length > 1) {
        new Chart(chartElement, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Weight',
                        data: values,
                        borderColor: '#facc15',
                        backgroundColor: 'rgba(168, 85, 247, 0.16)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#facc15',
                        pointBorderColor: '#0f172a',
                    },
                ],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        displayColors: false,
                        backgroundColor: '#020617',
                        borderColor: 'rgba(250, 204, 21, 0.25)',
                        borderWidth: 1,
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        callbacks: {
                            label: (context) => `${context.parsed.y} kg`,
                        },
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#94a3b8',
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.08)',
                        },
                    },
                    y: {
                        ticks: {
                            color: '#94a3b8',
                            callback: (value) => `${value} kg`,
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.08)',
                        },
                    },
                },
            },
        });
    }
});
