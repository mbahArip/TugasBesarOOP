var date = new Date();
var month = date.getMonth();
date.setDate(1);
var all_days = [];
var month_name = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];
while (date.getMonth() == month) {
    var d = date.getDate().toString() + ' ' + month_name[month] + ' ' + date.getFullYear().toString();
    all_days.push(d);
    date.setDate(date.getDate() + 1);
}

function adminDash(json, chartContainer) {
    var data = {
        chart: {
            id: 'adminDash',
            type: 'area',
            height: '75%'
        },
        series: [{
            name: 'Pendapatan dalam juta',
            data: json
        }],
        xaxis: {
            categories: all_days
        },
        theme: {
            mode: 'light'
        }
    };

    var drawChart = new ApexCharts(document.getElementById(chartContainer), data);

    drawChart.render();
}
