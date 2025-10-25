// Custom Dashboard Charts Script
// This script overrides the default sales chart with dynamic data from the backend

(function () {
    'use strict';

    // Override the SalesChart initialization
    window.addEventListener('load', function () {

        // Small delay to ensure argon.js has run first
        setTimeout(function () {

            var $chart = $('#chart-sales');

            if (!$chart.length) {
                return;
            }

            // Get the existing chart instance if any
            var existingChart = $chart.data('chart');
            if (existingChart) {
                existingChart.destroy();
            }

            // Get the month button to extract initial data
            var $monthButton = $('[data-target="#chart-sales"]').first();
            var monthlyData = $monthButton.data('update');

            // Generate month labels (last 9 months)
            var monthLabels = [];
            var currentDate = new Date();

            for (var i = 8; i >= 0; i--) {
                var date = new Date(currentDate.getFullYear(), currentDate.getMonth() - i, 1);
                var monthName = date.toLocaleString('en-US', { month: 'short' });
                monthLabels.push(monthName);
            }

            // Create the sales chart with dynamic data
            var salesChart = new Chart($chart, {
                type: 'line',
                options: {
                    scales: {
                        yAxes: [{
                            gridLines: {
                                color: Charts.colors.gray[900],
                                zeroLineColor: Charts.colors.gray[900]
                            },
                            ticks: {
                                callback: function (value) {
                                    if (!(value % 10)) {
                                        return '$' + value;
                                    }
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function (item, data) {
                                var label = data.datasets[item.datasetIndex].label || '';
                                var yLabel = item.yLabel;
                                var content = '';

                                if (data.datasets.length > 1) {
                                    content += '<span class="popover-body-label mr-auto">' + label + '</span>';
                                }

                                content += '<span class="popover-body-value">$' + yLabel.toFixed(2) + '</span>';
                                return content;
                            }
                        }
                    }
                },
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Sales',
                        data: monthlyData && monthlyData.data && monthlyData.data.datasets && monthlyData.data.datasets[0]
                            ? monthlyData.data.datasets[0].data
                            : [0, 0, 0, 0, 0, 0, 0, 0, 0]
                    }]
                }
            });

            // Save to jQuery object
            $chart.data('chart', salesChart);

            // Update the chart when buttons are clicked
            $('[data-target="#chart-sales"]').on('click', function (e) {
                e.preventDefault();

                var $this = $(this);
                var updateData = $this.data('update');

                if (!updateData || !updateData.data || !updateData.data.datasets) {
                    console.log('No update data found');
                    return;
                }

                console.log('Updating chart with data:', updateData.data.datasets[0].data);

                // Check if this is Week or Month button
                var buttonText = $this.find('.d-none.d-md-block').text().trim();
                var isWeekView = buttonText === 'Week';

                // Update labels based on view type
                if (isWeekView) {
                    // Generate week labels (last 9 weeks)
                    var weekLabels = [];
                    for (var i = 8; i >= 0; i--) {
                        weekLabels.push('Week ' + (9 - i));
                    }
                    salesChart.data.labels = weekLabels;
                } else {
                    // Use month labels
                    salesChart.data.labels = monthLabels;
                }

                // Update the chart data
                salesChart.data.datasets[0].data = updateData.data.datasets[0].data;

                // Update active state
                $('[data-target="#chart-sales"]').find('a').removeClass('active');
                $this.find('a').addClass('active');

                // Update the chart
                salesChart.update();
            });

        }, 100); // 100ms delay
    });

    // Override the OrdersChart initialization
    window.addEventListener('load', function () {

        // Small delay to ensure argon.js has run first
        setTimeout(function () {

            var $ordersChart = $('#chart-orders');

            if (!$ordersChart.length) {
                return;
            }

            // Get the existing chart instance if any
            var existingOrdersChart = $ordersChart.data('chart');
            if (existingOrdersChart) {
                existingOrdersChart.destroy();
            }

            // Get orders data from data attribute
            var ordersData = $ordersChart.data('orders');

            // Generate month labels (last 6 months)
            var orderMonthLabels = [];
            var currentDate = new Date();

            for (var i = 5; i >= 0; i--) {
                var date = new Date(currentDate.getFullYear(), currentDate.getMonth() - i, 1);
                var monthName = date.toLocaleString('en-US', { month: 'short' });
                orderMonthLabels.push(monthName);
            }

            // Create the orders chart with dynamic data
            var ordersChart = new Chart($ordersChart, {
                type: 'bar',
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                callback: function (value) {
                                    if (!(value % 10)) {
                                        return value;
                                    }
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function (item, data) {
                                var label = data.datasets[item.datasetIndex].label || '';
                                var yLabel = item.yLabel;
                                var content = '';

                                if (data.datasets.length > 1) {
                                    content += '<span class="popover-body-label mr-auto">' + label + '</span>';
                                }

                                content += '<span class="popover-body-value">' + yLabel + ' orders</span>';

                                return content;
                            }
                        }
                    }
                },
                data: {
                    labels: orderMonthLabels,
                    datasets: [{
                        label: 'Orders',
                        data: ordersData || [0, 0, 0, 0, 0, 0]
                    }]
                }
            });

            // Save to jQuery object
            $ordersChart.data('chart', ordersChart);

        }, 150); // 150ms delay (slightly after sales chart)
    });

})();

