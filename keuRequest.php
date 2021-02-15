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

//Function
if (isset($_POST['approveRequest'])) {
    if (isset($_POST['deskripsi'])) {
        $keuangan->requestApprove($_POST['req-id'], $_POST['deskripsi']);
    } else {
        $keuangan->requestApprove($_POST['req-id']);
    }
}
if (isset($_POST['denyRequest'])) {
    $keuangan->requestDeny($_POST['req-id'], $_POST['deskripsi']);
}
if (isset($_POST['deleteRequest'])) {
    $keuangan->requestDelete($_POST['req-id']);
}
?>

<!-- <script src='assets\js\loading.js'></script> -->

<body onload="selectedMenu('keuReq')">
    <?php
    include 'layout/sidebar.php';
    ?>

    <!-- Content -->
    <div class="keuangan-dash content">
        <div class="grid-wrapper">
            <!-- Request Approval -->
            <div class="req-ok grid-card">
                <div class="card-info request-ok-title">
                    <span>Request Barang <i class="material-icons"> inventory_2 </i></span>
                </div>

                <div class="table">
                    <table id="table-req-ok">
                        <thead>
                            <tr>
                                <th style="width: 15vw !important">Nomor Request</th>
                                <th style="width: 10vw !important">Qty Barang</th>
                                <th style="width: 55vw !important">Nama Barang</th>
                                <th style="width: 15vw !important">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataRequest as $r) : ?>
                                <tr>
                                    <td style="text-align: center;"><?= $r['id_request'] ?></td>
                                    <!-- <td style="text-align: center;"><?= $r['id_barang'] ?></td> -->
                                    <td style="text-align: center;"><?= $r['qty_barang'] ?></td>
                                    <td><?= $r['nama_barang'] ?></td>
                                    <td style="text-align: center;">
                                        <button onclick="reqApprove('modal-approveRequest', this, 'table-req-ok', 'req-id')"><i class="material-icons">check</i></button>
                                        <button onclick="reqApprove('modal-denyRequest', this, 'table-req-ok', 'req-deny-id')"><i class="material-icons">cancel</i></button>
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
                                <th style="width: 10vw !important">Qty Barang</th>
                                <th style="width: 35vw !important">Nama Barang</th>
                                <th style="width: 35vw !important">Alasan</th>
                                <th style="width: 10vw !important">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataDitolak as $r) : ?>
                                <tr>
                                    <td style="text-align: center;"><?= $r['id_request'] ?></td>
                                    <!-- <td style="text-align: center;"><?= $r['id_barang'] ?></td> -->
                                    <td style="text-align: center;"><?= $r['qty_barang'] ?></td>
                                    <td><?= $r['nama_barang'] ?></td>
                                    <td><?= $r['deskripsi'] ?></td>
                                    <td style="text-align: center;">
                                        <button onclick="reqApprove('modal-approveRight', this, 'table-req-no', 'req-right-id')"><i class=" material-icons">check</i></button>
                                        <button onclick="reqApprove('modal-deleteRequest', this, 'table-req-no', 'req-delete-id')"><i class="material-icons">delete</i></button>
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
        <!-- Dialog Approve -->
        <div id="modal-approveRequest" class="confirmBarang modal-container">
            <span for="notes">Approval Request</span>
            <form name="approveRequest" method="POST">
                <input type="hidden" name="req-id" id="req-id">
                <label>Setujui request barang?</label>
                <button class="btn-ok" name="approveRequest">Setujui</button>
            </form>
            <button class="btn-no" onclick="closeModal('modal-approveRequest')">Batalkan</button>
        </div>

        <!-- Dialog Deny -->
        <div id="modal-denyRequest" class="denyRequest modal-container">
            <span for="notes">Approval Request</span>
            <form name="denyRequest" method="POST">
                <input type="hidden" name="req-id" id="req-deny-id">
                <label>Alasan menolak request?</label>
                <textarea type="text" name="deskripsi" id="deskripsi" placeholder="Maximal 500 karakter" maxlength="500" required="required"></textarea>
                <button class="btn-no" name="denyRequest">Tolak</button>
            </form>
            <button class="btn-ok" onclick="closeModal('modal-denyRequest')">Batalkan</button>
        </div>

        <!-- Dialog Approve -->
        <div id="modal-deleteRequest" class="confirmBarang modal-container">
            <span for="notes">Hapus Request</span>
            <form name="approveRight" method="POST">
                <input type="hidden" name="req-id" id="req-delete-id">
                <label>Hapus request barang?</label>
                <button class="btn-no" name="deleteRequest">Hapus</button>
            </form>
            <button class="btn-ok" onclick="closeModal('modal-deleteRequest')">Batalkan</button>
        </div>

        <!-- Dialog Deny -->
        <div id="modal-approveRight" class="approveRight modal-container">
            <span for="notes">Approval Request</span>
            <form name="deleteRequest" method="POST">
                <input type="hidden" name="req-id" id="req-right-id">
                <label>Alasan mengubah status?</label>
                <textarea type="text" name="deskripsi" id="deskripsi" placeholder="Maximal 500 karakter" maxlength="500" required="required"></textarea>
                <button class="btn-ok" name="approveRequest">Setujui</button>
            </form>
            <button class="btn-no" onclick="closeModal('modal-approveRight')">Batalkan</button>
        </div>
    </div>
</body>

<script>
</script>

<?php
include 'layout/footer.php';
?>