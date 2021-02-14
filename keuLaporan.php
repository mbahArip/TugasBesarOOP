<?php
$pageName = 'Laporan Keuangan';
include 'layout/header.php';
require 'function/c.php';
require 'function/function.php';

session_start();

$sc->_checkSession('login');
$condition = password_verify('keuangan', $_COOKIE['session']) == false && password_verify('debug', $_COOKIE['session']) == false;
$sc->_checkRank($condition);

if (isset($_POST['range'])) {
    $range = $_POST['range'];

    $date = explode('-', $range);
    $month = $date[1];
    $year = $date[0];
    $gajiTime = 'last day of ' . date("F", mktime(0, 0, 0, $month, 10));
} else {
    $month = date('m');
    $year = date('Y');
}
$json = $keuangan->transactionDataToChart($month, $year);
$lapKeu = $vAdmin->showKeu($month, $year);
//Total Uang Masuk dari Database
$sumIn = intval(implode($vAdmin->sum('uangMasuk', 'lapkeuangan', $month, $year)));
//Total Uang Keluar dari Database
$sumOut = intval(implode($vAdmin->sum('uangKeluar', 'lapkeuangan', $month, $year)));
//Total Gaji Karyawan
$gajiBulanan = intval(implode($extra->getMonthlySalary()));
//Total
$totalUangKeluar = $sumOut + $gajiBulanan;
$totalKeuntungan = $sumIn - $totalUangKeluar;
?>

<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<script src='assets\js\app.js'></script>

<body onload="selectedMenu('keuLaporan')">
    <!-- Sidebar -->
    <?php
    include 'layout/sidebar.php';
    ?>


    <!-- Content -->
    <div class="admin-keuangan content">
        <div class="grid-wrapper">
            <!-- Chart Perbulan -->
            <div class="chart-bulan grid-card">
                <!-- Card Title -->
                <div class="card-info">
                    <span>Pendapatan bulan ini <i class="material-icons">insert_chart</i></span>
                    <form method="post" id="range">
                        <input type="month" name="range" min="2021-01" value="">
                        <button>Filter</button>
                    </form>
                </div>
                <!-- Chart -->
                <div id="adminKeuChartBulan" class="chartContainer"></div>

            </div>

            <!-- Table Keuangan -->
            <div class="table-keuangan grid-card">
                <!-- Card Title -->
                <div class="card-info">
                    <span>Detail keuangan<i class="material-icons">insert_chart</i></span>
                    <button type="button" onclick="printJS({
                        printable: 'print-keu',
                        type:'html',
                        header: 'Laporan Keuangan',
                        ignoreElements: ['print-ignore']
                    })">Print</button>
                </div>
                <!-- Table -->
                <div id='print-keu' class="table">
                    <table border='1' cellpadding='1' style="border-collapse: collapse;" id='table-keu'>
                        <thead>
                            <tr>
                                <th style="width: 10vw !important;">Tanggal</th>
                                <th style="width: 50vw !important;">Detail Transaksi</th>
                                <th>Uang Masuk</th>
                                <th>Uang Keluar</th>
                                <!-- <th id='print-ignore' style="width: 10vw !important;">Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lapKeu as $k) : ?>
                                <tr>
                                    <td style="text-align: center;"><?= $k['tanggal']; ?></td>
                                    <td><?= $k['keterangan']; ?></td>
                                    <td><?= 'IDR ' . $k['uangMasuk']; ?></td>
                                    <td><?= 'IDR ' . $k['uangKeluar']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td style="text-align: center;"><?= date("Y-m-j", strtotime("last day of " . $gajiTime)); ?></td>
                                <td>Gaji Karyawan</td>
                                <td>IDR 0</td>
                                <td><?= 'IDR ' . $gajiBulanan; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: end; font-weight: bold;">Total</td>
                                <td style="text-align: end; font-weight: bold;"><?= 'IDR ' . $sumIn; ?></td>
                                <td style="text-align: end; font-weight: bold;"><?= 'IDR ' . $totalUangKeluar; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: end; font-weight: bold;">Total Keuntungan (Uang Masuk - Uang Keluar)</td>
                                <td style="text-align: end; font-weight: bold;"><?= 'IDR ' . $totalKeuntungan; ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src='assets/js/chart.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/apexcharts'></script>
    <script>
        var jsonTest = <?= $json; ?>;
        var jsonTanggal = Object.keys(jsonTest);
        var jsonPendapatan = []
        for (var i = 0; i < jsonTanggal.length; i++) {
            jsonPendapatan.push(jsonTest[jsonTanggal[i]]['pemasukan']);
        }
        adminDash(jsonPendapatan, jsonTanggal, 'adminKeuChartBulan', 'Pendapatan dalam IDR');

        const monthControl = document.querySelector('input[type="month"]');
        monthControl.value = '<?= $year . '-' . $month ?>'
    </script>

</body>

<?php
include 'layout/footer.php';
?>