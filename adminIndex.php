<?php
$pageName = 'Dashboard';
include 'layout/header.php';
require 'function/c.php';
require 'function/function.php';
// include 'layout/loading.php';

session_start();

$sc->_checkSession('login');
$condition = password_verify('admin', $_COOKIE['session']) == false && password_verify('debug', $_COOKIE['session']) == false && password_verify('superAdmin', $_COOKIE['session']) == false;
$sc->_checkRank($condition);

//Show Data
$dataNewEmployee = $vAdmin->showNewEmployee();
$dataNotes = $vAdmin->showNotes();

//Chart Data
$month = date('m');
$year = date('Y');
$dataChart = $keuangan->transactionDataToChart($month, $year);

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

<script src='assets\js\app.js'></script>
<!-- <script src='assets\js\loading.js'></script> -->
<script src="assets\js\chart.js"></script>

<body onload="selectedMenu('adminDash')">
    <?php
    include 'layout/sidebar.php';
    ?>

    <!-- Content -->
    <div class="admin-dash content">
        <div class="grid-wrapper">
            <!-- Karyawan -->
            <div class="table-karyawan grid-card">
                <!-- Employee Title -->
                <div class="card-info">
                    <span>Karyawan Baru <i class="material-icons">people</i></span>
                </div>
                <!-- Employee Table -->
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 5vw !important;">ID Karyawan</th>
                                <th style="width: 25vw !important;">Nama Karyawan</th>
                                <th style="width: 15vw !important;">Posisi Karyawan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataNewEmployee as $data) : ?>
                                <tr>
                                    <td style="font-weight: bold; text-align: center; "><?= $data['id_karyawan']; ?></td>
                                    <td><?= $data['nama_karyawan']; ?></td>
                                    <td style="text-transform: capitalize; text-align: center;"><?= $data['rank_karyawan']; ?></td>
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

            <!-- Grafik Penjualan -->
            <div class="grafik-penjualan grid-card">
                <!-- Grafik Title -->
                <div class="card-info">
                    <span>Pendapatan bulan ini <i class="material-icons">insert_chart</i></span>
                </div>
                <!-- Chart -->
                <div id="adminDashboardChart" class="chartContainer"></div>
                <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                <script>
                    var jsonTest = <?= $dataChart; ?>;
                    var jsonTanggal = Object.keys(jsonTest);
                    var jsonPendapatan = []
                    for (var i = 0; i < jsonTanggal.length; i++) {
                        jsonPendapatan.push(jsonTest[jsonTanggal[i]]['pemasukan']);
                    }
                    adminDash(jsonPendapatan, jsonTanggal, 'adminDashboardChart', 'Pendapatan dalam IDR');
                </script>
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
    <!-- Script -->
    <script type='text/javascript'>
    </script>
</body>

<?php
include 'layout/footer.php';
?>