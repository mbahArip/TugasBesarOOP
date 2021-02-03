<?php
include 'layout/header.php';
// include 'layout/loading.php';
require 'function/c.php';
require 'function/v.php';

session_start();

//Cek session
if ($_SESSION['login'] === NULL) {
    header("Location: login");
    exit;
}

//CekRank
if (password_verify('gudang', $_COOKIE['session']) == false) {
    header("Location: logout");
    exit;
}

$arrayChart = array(
    '2.8', '1.7', '2.4', '2.2', '1.5', '2.1', '1.8', '2.7', '2.2', '3.4'
);
?>

<script src='assets\js\app.js'></script>
<!-- <script src='assets\js\loading.js'></script> -->
<script src="assets\js\chart.js"></script>

<body onload="selectedMenu('gudangDash')">
    <?php
    include 'layout/sidebar.php';
    ?>
    <div class="admin-dash content">
        <div class="grid-wrapper">
            <div class="table-karyawan grid-card">
                <div class="card-info">
                    <span>Karyawan Baru <i class="material-icons">people</i></span>
                </div>
                <div class="table">
                    <table>
                        <tr>
                            <th>ID Karyawan</th>
                            <th>Nama Karyawan</th>
                            <th>Posisi Karyawan</th>
                        </tr>
                        <tr>
                            <td>00000000</td>
                            <td>Placeholder Nama</td>
                            <td>Placeholder Posisi</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="table-note grid-card">
                <div class="card-info">
                    <span>Catatan <i class="material-icons">event_note</i></span>
                </div>
                <div class="table">
                    <table>
                        <tr>
                            <th>Tanggal</th>
                            <th style="width: 15%">Deskripsi</th>
                        </tr>
                        <tr>
                            <td style="text-align: center;">00/00/0000</td>
                            <td style="width: 15%">Placeholder Deskripsi</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="grafik-penjualan grid-card">
                <div class="card-info">
                    <span>Pendapatan bulan ini <i class="material-icons">insert_chart</i></span>
                </div>
                <div id="chartContainer"></div>
                <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                <script>
                    var json = <?= json_encode($arrayChart) ?>;
                    adminDash(json, 2020);
                </script>
            </div>
        </div>
    </div>
</body>

<?php
include 'layout/footer.php';
?>