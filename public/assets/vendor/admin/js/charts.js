(function($) {

    "use strict";

    let dashboardUsersChart = $('#dashboard-users-chart');
    if (dashboardUsersChart.length) {
        window.Chart && new Chart(dashboardUsersChart, {
            type: 'line',
            data: {
                labels: chartsConfig.users.labels,
                datasets: [{
                    label: chartsConfig.users.title,
                    data: chartsConfig.users.data,
                    fill: false,
                    pointBackgroundColor: config.colors.primary_color,
                    borderColor: config.colors.primary_color,
                    borderWidth: 2,
                    lineTension: .10,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                scales: {
                    y: {
                        suggestedMax: chartsConfig.users.max,
                    }
                }
            }
        });
    }

    let dashboardTicketsChart = $('#dashboard-tickets-chart');
    if (dashboardTicketsChart.length) {
        window.Chart && new Chart(dashboardTicketsChart, {
            type: 'bar',
            data: {
                labels: chartsConfig.tickets.labels,
                datasets: [{
                    label: chartsConfig.tickets.title,
                    data: chartsConfig.tickets.data,
                    fill: false,
                    backgroundColor: config.colors.primary_color,
                    borderColor: config.colors.primary_color,
                    borderWidth: 2,
                    lineTension: .10,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                scales: {
                    y: {
                        suggestedMax: chartsConfig.tickets.max,
                    }
                }
            }
        });
    }

})(jQuery);