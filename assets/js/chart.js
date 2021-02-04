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

function adminDash(json, tanggal, chartContainer, seriesDetail) {
    var data = {
        chart: {
            id: 'adminDash',
            type: 'area',
            height: '75%'
        },
        series: [{
            name: seriesDetail,
            data: json
        }],
        xaxis: {
            categories: tanggal
        },
        theme: {
            mode: 'light'
        }
    };

    var drawChart = new ApexCharts(document.getElementById(chartContainer), data);

    drawChart.render();
}
