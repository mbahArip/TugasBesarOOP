<?php
$pageName = 'Request Barang';
include 'layout/header.php';
require 'function/c.php';
require 'function/function.php';
// include 'layout/loading.php';

session_start();

$sc->_checkSession('login');
$condition = password_verify('keuangan', $_COOKIE['session']) == false && password_verify('debug', $_COOKIE['session']) == false;
$sc->_checkRank($condition);

//Show Data
$dataRequest = $vAdmin->showReqKeu();
$dataDitolak = $vAdmin->showDitolak();

?>

<!-- <script src='assets\js\loading.js'></script> -->

<body onload="selectedMenu('gudangReq')">
    <?php
    include 'layout/sidebar.php';
    ?>

    <!-- Content -->
    <div class="keuangan-dash content">
        <div class="grid-wrapper">
            <!-- Request Approval -->
            <div class="req-ok grid-card">
                <div class="card-info request-ok-title">
                    <span>Request Barang <i class="material-icons"> error </i></span>
                </div>

                <div class="table">
                    <table id="table-req-ok">
                        <thead>
                            <tr>
                                <th style="width: 15vw !important">Nomor Request</th>
                                <th style="width: 10vw !important">ID Barang</th>
                                <th style="width: 55vw !important">Nama Barang</th>
                                <th style="width: 15vw !important">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataRequest as $r) : ?>
                                <tr>
                                    <td style="text-align: center;"><?= $r['id_request'] ?></td>
                                    <td style="text-align: center;"><?= $r['id_barang'] ?></td>
                                    <td><?= $r['nama_barang'] ?></td>
                                    <td style="text-align: center;">
                                        <form method="post">
                                            <button><i class="material-icons">check</i></button>
                                            <button><i class="material-icons">cancel</i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Request Ditolak -->
            <div class="req-no grid-card">
                <div class="card-info request-no-title">
                    <span>Request Ditolak <i class="material-icons"> error </i></span>
                </div>

                <div class="table">
                    <table id="table-req-no">
                        <thead>
                            <tr>
                                <th style="width: 15vw !important">Nomor Request</th>
                                <th style="width: 10vw !important">ID Barang</th>
                                <th style="width: 35vw !important">Nama Barang</th>
                                <th style="width: 35vw !important">Alasan</th>
                                <th style="width: 10vw !important">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataDitolak as $r) : ?>
                                <tr>
                                    <td style="text-align: center;"><?= $r['id_request'] ?></td>
                                    <td style="text-align: center;"><?= $r['id_barang'] ?></td>
                                    <td><?= $r['nama_barang'] ?></td>
                                    <td><?= $r['deskripsi'] ?></td>
                                    <td style="text-align: center;">
                                        <form method="post">
                                            <button><i class="material-icons">check</i></button>
                                            <button><i class="material-icons">delete</i></button>
                                        </form>
                                    </td>
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