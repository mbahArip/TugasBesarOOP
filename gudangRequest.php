<?php
$pageName = 'Request Barang';
include 'layout/header.php';
require 'function/c.php';
require 'function/function.php';
// include 'layout/loading.php';

session_start();

$sc->_checkSession('login');
$condition = password_verify('gudang', $_COOKIE['session']) == false && password_verify('debug', $_COOKIE['session']) == false;
$sc->_checkRank($condition);

//Show Data
$dataRequest = $vAdmin->showRequest();
$dataConfirm = $vAdmin->showConfirm();

//Add to database
if (isset($_POST['add-request'])) {
    $add->addRequest($_POST['req-id'], $_POST['req-nama'], $_POST['req-qty']);
}
if (isset($_POST['confirm-reqID'])) {
    $qty = $extra->confirmRequest($_POST['confirm-reqID']);
    echo $qty;
}
?>

<!-- <script src='assets\js\loading.js'></script> -->

<body onload="selectedMenu('gudangReq')">
    <?php
    include 'layout/sidebar.php';
    ?>

    <!-- Content -->
    <div class="storage-dash content">
        <div class="grid-wrapper">
            <!-- Request Barang -->
            <div class="req-item grid-card">
                <div class="card-info request-title">
                    <span>Request Barang <i class="material-icons"> inventory_2 </i></span>
                </div>
                <div class="request-form">
                    <form method="post" autocomplete="off">
                        <label for="req-id">ID Barang</label>
                        <input type="text" name="req-id" id="req-id" placeholder="Gunakan request stok di page Gudang" readonly>
                        <label for="req-nama">Nama Barang</label>
                        <input type="text" name="req-nama" id="req-nama" placeholder="Gunakan request stok di page Gudang" readonly>
                        <label for="req-qty">Qty Barang</label>
                        <input type="text" name="req-qty" id="req-qty" placeholder="Qty barang" required="required">
                        <button class="btn-ok" name="add-request">Tambah Request</button>
                    </form>
                    <button class="btn-no" onclick="location.href = 'gudangIndex';">Kembali</button>
                </div>
            </div>

            <!-- Konfirmasi Barang -->
            <div class="confirm-item grid-card">
                <div class="card-info confirm-title">
                    <span>Konfirmasi Barang <i class="material-icons"> assignment_turned_in </i></span>
                </div>
                <div class="table">
                    <table id="confirm-table">
                        <thead>
                            <tr>
                                <th style="width: 15vw !important;">Nomor Request</th>
                                <th style="width: 15vw !important;">ID Barang</th>
                                <th style="width: 70vw !important;">Nama Barang</th>
                                <th style="width: 10vw !important;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataConfirm as $c) : ?>
                                <tr>
                                    <td style="text-align: center;"><?= $c['id_request'] ?></td>
                                    <td style="text-align: center;"><?= $c['id_barang'] ?></td>
                                    <td><?= $c['nama_barang'] ?></td>
                                    <td class="table-action">
                                        <button class="btn-confirm" onclick="barangConfirm('modal-confirmBarang', this, 'confirm-table')">Konfirmasi</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Status Request -->
            <div class="status-req grid-card">
                <div class="card-info request-title">
                    <span>Status Request <i class="material-icons"> description </i></span>
                </div>

                <div class="table">
                    <table id="table-status">
                        <thead>
                            <tr>
                                <th style="width: 15vw !important">Nomor Request</th>
                                <th style="width: 10vw !important">ID Barang</th>
                                <th style="width: 55vw !important">Nama Barang</th>
                                <th style="width: 15vw !important">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataRequest as $r) : ?>
                                <tr>
                                    <td style="text-align: center;"><?= $r['id_request'] ?></td>
                                    <td style="text-align: center;"><?= $r['id_barang'] ?></td>
                                    <td><?= $r['nama_barang'] ?></td>
                                    <?php if ($r['status'] == 0) : ?>
                                        <td style="text-align: center">Menunggu Approval</td>
                                    <?php elseif ($r['status'] == 1) : ?>
                                        <td style="text-align: center">Barang telah dipesan</td>
                                    <?php elseif ($r['status'] == 2) : ?>
                                        <td style="text-align: center">Request ditolak<br>Mohon hubungi bagian keuangan</td>
                                    <?php elseif ($r['status'] == 3) : ?>
                                        <td style="text-align: center">Konfirmasi Tiba</td>
                                    <?php elseif ($r['status'] == 4) : ?>
                                        <td style="text-align: center">Request Komplit</td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <script src="assets\js\modal.js"></script>
    <div class="modal">
        <!-- Dialog Delete Notes -->
        <div id="modal-confirmBarang" class="confirmBarang modal-container">
            <span for="notes">Konfirmasi Barang</span>
            <form name="confirm-barang" method="POST">
                <input type="hidden" name="confirm-reqID" id="confirm-reqID">
                <label>Pastikan barang yang diterima telah sesuai!</label>
                <button class="btn-ok">Konfirmasi</button>
            </form>
            <button class="btn-no" onclick="closeModal('modal-confirmBarang')">Batalkan</button>
        </div>
    </div>
</body>

<script>
    <?php if (isset($_POST['requestStok'])) : ?>
        const idBarang = document.getElementById('req-id');
        const namaBarang = document.getElementById('req-nama');

        idBarang.value = '<?= $_POST['id_barang']; ?>';
        namaBarang.value = '<?= $_POST['nama_barang']; ?>';
    <?php endif; ?>

    function resetQty() {
        const qtyBarang = document.getElementById('req-qty');
        qtyBarang.value = '';
    }
</script>

<?php
include 'layout/footer.php';
?>