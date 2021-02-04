<?php
include 'layout/header.php';
require 'function/c.php';

session_start();

$sc = new sessionCookie();

$sc->_checkSession('login');
$condition = password_verify('admin', $_COOKIE['session']) == false && password_verify('debug', $_COOKIE['session']) == false && password_verify('superAdmin', $_COOKIE['session']) == false;
$sc->_checkRank($condition);

$arrayHari = array(rand(10, 100), rand(10, 100));
$arrayBulan = array(
    rand(10, 100), rand(10, 100), rand(10, 100), rand(10, 100), rand(10, 100),
    rand(10, 100), rand(10, 100), rand(10, 100), rand(10, 100), rand(10, 100),
    rand(10, 100), rand(10, 100), rand(10, 100), rand(10, 100), rand(10, 100),
    rand(10, 100), rand(10, 100), rand(10, 100), rand(10, 100), rand(10, 100),
    rand(10, 100), rand(10, 100), rand(10, 100), rand(10, 100), rand(10, 100),
    rand(10, 100), rand(10, 100), rand(10, 100), rand(10, 100), rand(10, 100)
);

$cAdmin = new cAdmin();
$vAdmin = new vAdmin();

$json = $cAdmin->processData();
$lapKeu = $vAdmin->showKeu();
$sumIn = mysqli_fetch_assoc($vAdmin->sum('uangMasuk', 'lapkeuangan'));
$sumOut = mysqli_fetch_assoc($vAdmin->sum('uangKeluar', 'lapkeuangan'));
?>

<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<script src='assets\js\app.js'></script>

<body onload="selectedMenu('adminLapkeu')">
    <!-- Sidebar -->
    <?php
    include 'layout/sidebar.php';
    ?>

    <!-- Content -->
    <div class="admin-keuangan content">
        <div class="grid-wrapper">
            <!-- Chart Perhari -->
            <!-- <div class="chart-hari grid-card"> -->
            <!-- Card Title -->
            <!-- <div class="card-info">
                    <span>Pendapatan hari ini <i class="material-icons">insert_chart</i></span>
                </div> -->
            <!-- Chart -->
            <!-- <div id="adminKeuChartHari" class="chartContainer"></div> -->
            <!-- </div> -->

            <!-- Chart Perbulan -->
            <div class="chart-bulan grid-card">
                <!-- Card Title -->
                <div class="card-info">
                    <span>Pendapatan bulan ini <i class="material-icons">insert_chart</i></span>
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
                                <td colspan="2" style="text-align: end; font-weight: bold;">Total</td>
                                <td style="text-align: end; font-weight: bold;"><?= 'IDR ' . implode($sumIn); ?></td>
                                <td style="text-align: end; font-weight: bold;"><?= 'IDR ' . implode($sumOut); ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: end; font-weight: bold;">Total Keuntungan (Uang Masuk - Uang Keluar)</td>
                                <td style="text-align: end; font-weight: bold;"><?php $sum = implode($sumIn) - implode($sumOut);
                                                                                echo  'IDR ' . $sum; ?></td>
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
    </script>

</body>

<?php
include 'layout/footer.php';
?>