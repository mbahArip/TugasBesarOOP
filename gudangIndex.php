<?php
$pageName = 'Dashboard';
include 'layout/header.php';
require 'function/c.php';
require 'function/function.php';
// include 'layout/loading.php';

session_start();

$sc->_checkSession('login');
$condition = password_verify('gudang', $_COOKIE['session']) == false && password_verify('debug', $_COOKIE['session']) == false;
$sc->_checkRank($condition);

//Show Data
$dataNotes = $vAdmin->showNotes();
$dataEmpty = $vAdmin->showEmpty();
$dataRequest = $vAdmin->showRequest();

//Get Notes form
if (isset($_POST['notes'])) {
    $add->addNotes($_POST['notes'], $_COOKIE['id'], $_COOKIE['nama']);
}
//Update Notes
if (isset($_POST['edit-notes'])) {
    $edit->editNotes($_POST['edit-notes'], $_POST['edit-notesID']);
}
//Delete Notes
if (isset($_POST['delete-notesID'])) {
    $delete->deleteNotes($_POST['delete-notesID']);
}
?>

<!-- <script src='assets\js\loading.js'></script> -->

<body onload="selectedMenu('gudangDash')">
    <?php
    include 'layout/sidebar.php';
    ?>

    <!-- Content -->
    <div class="storage-dash content">
        <div class="grid-wrapper">
            <!-- Stok Kosong -->
            <div class="table-stok grid-card">
                <!-- Stok title -->
                <div class="card-info stok-title">
                    <span>Barang kosong <i class="material-icons"> inventory_2 </i></span>
                </div>

                <!-- Stok Content -->
                <div class="table">
                    <table id="table-stok">
                        <thead>
                            <tr>
                                <th style="width: 15vw !important;">ID Barang</th>
                                <th style="width: 70vw !important;">Nama Barang</th>
                                <th style="width: 10vw !important;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataEmpty as $e) : ?>
                                <tr>
                                    <td style="text-align: center;"><?= $e['id_barang'] ?></td>
                                    <td><?= $e['nama_barang'] ?></td>
                                    <td class="table-action">
                                        <!-- <button id="btn-req-barang" onclick="barangReq('modal-requestStock', this, 'table-barang')"><i class="delete material-icons"> inventory_2 </i></button> -->
                                        <form action="gudangRequest" method="post">
                                            <input type="hidden" name="id_barang" value="<?= $e['id_barang']; ?>">
                                            <input type="hidden" name="nama_barang" value="<?= $e['nama_barang']; ?>">
                                            <button id="btn-req-barang" name="requestStok"><i class="delete material-icons"> inventory_2 </i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Catatan -->
            <div class="table-note grid-card">
                <!-- Notes Title -->
                <div class="card-info notes-title">
                    <span>Catatan <i class="material-icons"> event_note </i></span>
                    <button id="btn-add-notes" class="title-button" onclick="openModal('modal-addNotes')"> <span>Tambah</span> <i class="material-icons"> add_box </i></button>
                </div>
                <!-- Notes Content -->
                <div class="table">
                    <table id="table-notes">
                        <thead>
                            <tr>
                                <th style="width: 10vw !important;">Tanggal</th>
                                <th colspan=2 style="width: 20vw !important">Pengirim</th>
                                <th style="width: 70vw !important;">Deskripsi</th>
                                <!-- If userRank = superAdmin or Higher / userID = senderID -->
                                <? $getUser['rank'] == 'superAdmin' || $getUser['rank'] == 'debug' || $getUser['userID'] == 'userID' ?>
                                <th style="width: 10vw !important;">Action</th>
                                <? //endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataNotes as $n) : ?>
                                <tr>
                                    <td style="display: none !important"><?= $n['id']; ?></td>
                                    <td style="text-align: center; font-weight: bold;"><?= $n['tanggal']; ?></td>
                                    <td style="width: 5vw !important; text-align: center"><?= $n['id_karyawan'] ?></td>
                                    <td style="width: 15vw !important"><?= $n['nama_karyawan']; ?></td>
                                    <td><?= $n['deskripsi']; ?></td>
                                    <td class="table-action">
                                        <?php if (password_verify('superAdmin', $_COOKIE['session']) || password_verify('debug', $_COOKIE['session']) || $_COOKIE['id'] == $n['id_karyawan']) : ?>
                                            <button id="btn-edit-notes" class="btn-edit-notes" onclick="openEdit('modal-editNotes', this, 'table-notes', 'edit-notes', 4)"><i class="add material-icons"> create </i></button>
                                            <button id="btn-delete-notes" class="btn-delete-notes" onclick="openDelete('modal-deleteNotes', this, 'table-notes')"><i class="delete material-icons"> remove_circle_outline </i></button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="status-req grid-card">
                <!-- Status Title -->
                <div class="card-info request-title">
                    <span>Status Request <i class="material-icons"> description </i></span>
                </div>
                <!-- Status Content -->
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
        <!-- Dialog Add Notes -->
        <div id="modal-addNotes" class="addNotes modal-container">
            <span for="notes">Tambah Catatan</span>
            <form name="add-notes" method="POST">
                <textarea type="text" name="notes" id="notes" placeholder="Maximal 500 karakter" maxlength="500" required="required"></textarea>
                <button class="btn-ok">Tambah</button>
            </form>
            <button class="btn-no" onclick="closeModal('modal-addNotes')">Batalkan</button>
        </div>

        <!-- Dialog Edit Notes -->
        <div id="modal-editNotes" class="editNotes modal-container">
            <span for="notes">Ubah Catatan</span>
            <form name="edit-notes" method="POST">
                <input type="hidden" name="edit-notesID" id="edit-notesID">
                <textarea type="text" name="edit-notes" id="edit-notes" placeholder="Maximal 500 karakter" maxlength="500" required="required"></textarea>
                <button class="btn-ok">Ubah</button>
            </form>
            <button class="btn-no" onclick="closeModal('modal-editNotes')">Batalkan</button>
        </div>

        <!-- Dialog Delete Notes -->
        <div id="modal-deleteNotes" class="deleteNotes modal-container">
            <span for="notes">Hapus Catatan</span>
            <form name="delete-notes" method="POST">
                <input type="hidden" name="delete-notesID" id="delete-notesID">
                <label>Apa anda yakin ingin menghapus catatan ini?</label>
                <button class="btn-no">Hapus</button>
            </form>
            <button class="btn-ok" onclick="closeModal('modal-deleteNotes')">Batalkan</button>
        </div>
    </div>
</body>

<?php
include 'layout/footer.php';
?>