(function () {
    'use strict';

    var data = window.dashboardChartData || {};
    var chartInstances = [];

    if (typeof Highcharts === 'undefined') {
        return;
    }

    function isMobile() {
        return window.innerWidth < 768;
    }

    function emptyChart(containerId, message) {
        var el = document.getElementById(containerId);
        if (el) {
            el.innerHTML = '<p class="text-muted text-center py-8 py-md-10 mb-0">' + message + '</p>';
        }
    }

    function hasBarData(series) {
        return series && series.categories && series.categories.length > 0;
    }

    function chartHeight(containerId, defaultHeight) {
        var el = document.getElementById(containerId);
        if (!el) {
            return defaultHeight;
        }
        if (window.innerWidth < 576) {
            return 240;
        }
        if (window.innerWidth < 992) {
            return 280;
        }
        return defaultHeight;
    }

    var responsiveRules = [
        {
            condition: { maxWidth: 576 },
            chartOptions: {
                legend: { enabled: true, align: 'center', verticalAlign: 'bottom', layout: 'horizontal' },
                plotOptions: {
                    pie: {
                        dataLabels: { enabled: false },
                        showInLegend: true,
                    },
                    column: {
                        dataLabels: { enabled: false },
                    },
                    series: {
                        dataLabels: { enabled: false },
                    },
                },
                xAxis: {
                    labels: {
                        rotation: -45,
                        style: { fontSize: '10px' },
                    },
                },
            },
        },
        {
            condition: { maxWidth: 991 },
            chartOptions: {
                plotOptions: {
                    pie: {
                        dataLabels: {
                            style: { fontSize: '10px' },
                        },
                    },
                },
                xAxis: {
                    labels: {
                        style: { fontSize: '11px' },
                    },
                },
            },
        },
    ];

    function baseChartOptions(containerId, defaultHeight, extra) {
        var height = defaultHeight || 320;
        var opts = {
            chart: {
                height: chartHeight(containerId, height),
                spacing: [10, 10, 15, 10],
                style: { fontFamily: 'Calibri, Poppins, sans-serif' },
            },
            title: { text: null },
            credits: { enabled: false },
            exporting: { enabled: window.innerWidth >= 576 },
            responsive: { rules: responsiveRules },
        };
        if (extra && typeof extra === 'object') {
            Object.keys(extra).forEach(function (key) {
                opts[key] = extra[key];
            });
        }
        return opts;
    }

    function createChart(containerId, options) {
        var el = document.getElementById(containerId);
        if (!el) {
            return null;
        }
        var chart = Highcharts.chart(containerId, options);
        chartInstances.push(chart);
        return chart;
    }

    function renderPie(containerId, seriesData, seriesName) {
        if (!document.getElementById(containerId)) {
            return;
        }
        if (!seriesData || !seriesData.length) {
            emptyChart(containerId, 'No data yet.');
            return;
        }
        createChart(containerId, Highcharts.merge(baseChartOptions(containerId), {
            chart: { type: 'pie' },
            tooltip: { pointFormat: '<b>{point.y}</b> ({point.percentage:.1f}%)' },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    innerSize: isMobile() ? '45%' : '52%',
                    dataLabels: {
                        enabled: !isMobile(),
                        format: '<b>{point.name}</b>: {point.y}',
                        style: { fontSize: '11px' },
                    },
                    showInLegend: isMobile(),
                },
            },
            series: [{ name: seriesName, colorByPoint: true, data: seriesData }],
        }));
    }

    function renderColumnBar(containerId, categories, seriesList, horizontal) {
        if (!document.getElementById(containerId)) {
            return;
        }
        if (!categories || !categories.length) {
            emptyChart(containerId, 'No data yet.');
            return;
        }
        var useHorizontal = horizontal && !isMobile();
        createChart(containerId, Highcharts.merge(baseChartOptions(containerId), {
            chart: { type: 'column', inverted: useHorizontal },
            xAxis: {
                categories: categories,
                crosshair: true,
                labels: {
                    style: { fontSize: isMobile() ? '10px' : '12px' },
                },
            },
            yAxis: { title: { text: null }, min: 0, allowDecimals: false },
            tooltip: { shared: true },
            legend: { enabled: seriesList.length > 1 },
            plotOptions: {
                column: { borderRadius: 4, pointPadding: 0.1 },
            },
            series: seriesList,
        }));
    }

    renderPie('chart-applications-status', data.applications_by_status || [], 'Applications');
    renderPie('chart-office-student-status', data.office_student_status || [], 'Students');

    var monthlyStudents = data.monthly_students || { categories: [], data: [] };
    if (document.getElementById('chart-monthly-students')) {
        if (!hasBarData(monthlyStudents)) {
            emptyChart('chart-monthly-students', 'No student data yet.');
        } else {
            createChart('chart-monthly-students', Highcharts.merge(baseChartOptions('chart-monthly-students'), {
                chart: { type: 'areaspline' },
                xAxis: { categories: monthlyStudents.categories, crosshair: true },
                yAxis: { title: { text: 'Students' }, min: 0, allowDecimals: false },
                tooltip: { shared: true },
                plotOptions: { areaspline: { fillOpacity: 0.15, marker: { radius: 4 } } },
                series: [{ name: 'New Students', data: monthlyStudents.data, color: '#3699ff' }],
            }));
        }
    }

    var intake = data.students_by_intake || { categories: [], data: [] };
    renderColumnBar('chart-students-by-intake', intake.categories, [{
        name: 'Students',
        data: intake.data,
        color: '#8950fc',
    }]);

    var attemptOutcomes = data.intake_attempt_outcomes || { categories: [], data: [] };
    renderColumnBar('chart-intake-attempt-outcomes', attemptOutcomes.categories, [{
        name: 'Attempts',
        data: attemptOutcomes.data,
        color: '#f64e60',
    }]);

    var byBranch = data.students_by_branch || { categories: [], data: [] };
    renderColumnBar('chart-students-by-branch', byBranch.categories, [{
        name: 'Students',
        data: byBranch.data,
        color: '#1bc5bd',
    }], true);

    var counselors = data.counselor_wise_students || { categories: [], data: [] };
    renderColumnBar('chart-counselor-wise-students', counselors.categories, [{
        name: 'Students',
        data: counselors.data,
        color: '#ffa800',
    }], true);

    var monthlyRevenue = data.monthly_revenue || { categories: [], data: [] };
    if (document.getElementById('chart-monthly-revenue')) {
        createChart('chart-monthly-revenue', Highcharts.merge(baseChartOptions('chart-monthly-revenue', 340), {
            chart: { type: 'column' },
            xAxis: { categories: monthlyRevenue.categories, crosshair: true },
            yAxis: { title: { text: 'Paid amount' }, min: 0 },
            tooltip: { shared: true, valueDecimals: 2 },
            plotOptions: { column: { borderRadius: 4, pointPadding: 0.12 } },
            series: [{ name: 'Revenue (Paid)', data: monthlyRevenue.data, color: '#1bc5bd' }],
        }));
    }

    var paidVsDue = data.paid_vs_due || { categories: [], paid: [], due: [] };
    if (document.getElementById('chart-paid-vs-due')) {
        createChart('chart-paid-vs-due', Highcharts.merge(baseChartOptions('chart-paid-vs-due'), {
            chart: { type: 'column' },
            xAxis: { categories: paidVsDue.categories, crosshair: true },
            yAxis: { title: { text: 'Amount' }, min: 0 },
            tooltip: { shared: true, valueDecimals: 2 },
            legend: { enabled: true },
            plotOptions: { column: { stacking: 'normal', borderRadius: 3 } },
            series: [
                { name: 'Paid', data: paidVsDue.paid, color: '#1bc5bd' },
                { name: 'Due', data: paidVsDue.due, color: '#f64e60' },
            ],
        }));
    }

    var invoiceCount = data.monthly_invoice_count || { categories: [], data: [] };
    renderColumnBar('chart-monthly-invoice-count', invoiceCount.categories, [{
        name: 'Invoices',
        data: invoiceCount.data,
        color: '#3699ff',
    }]);

    var incomeExpense = data.income_vs_expense || { categories: [], income: [], expense: [] };
    if (document.getElementById('chart-income-vs-expense')) {
        createChart('chart-income-vs-expense', Highcharts.merge(baseChartOptions('chart-income-vs-expense', 340), {
            chart: { type: 'column' },
            xAxis: { categories: incomeExpense.categories, crosshair: true },
            yAxis: { title: { text: 'Amount' }, min: 0 },
            tooltip: { shared: true, valueDecimals: 2 },
            legend: { enabled: true },
            plotOptions: { column: { borderRadius: 4, pointPadding: 0.1 } },
            series: [
                { name: 'Income', data: incomeExpense.income, color: '#1bc5bd' },
                { name: 'Expense', data: incomeExpense.expense, color: '#f64e60' },
            ],
        }));
    }

    var resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            chartInstances.forEach(function (chart) {
                if (chart && chart.reflow) {
                    chart.reflow();
                }
            });
        }, 150);
    });
})();
