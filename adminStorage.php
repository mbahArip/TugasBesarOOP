<?php
include 'layout/header.php';
require 'function/c.php';

session_start();

$sc = new sessionCookie();
$cAdmin = new cAdmin();
$vAdmin = new vAdmin();

$sc->_checkSession('login');
$condition = password_verify('admin', $_COOKIE['session']) == false && password_verify('debug', $_COOKIE['session']) == false && password_verify('superAdmin', $_COOKIE['session']) == false;
$sc->_checkRank($condition);

$dataBarang = $vAdmin->showStorage();

if (isset($_POST['searchQuery'])) {
    $dataBarang = $cAdmin->searchGudang($_POST['searchQuery']);
}
if (isset($_POST['addBarang'])) {
    $dataBarang = $cAdmin->newBarang($_POST['id-barang'], $_POST['nama-barang'], $_POST['harga-barang'], $_POST['stok-barang']);
}
if (isset($_POST['editBarang'])) {
    $dataBarang = $cAdmin->editBarang($_POST['edit-id'], $_POST['edit-nama'], $_POST['edit-harga'], $_POST['edit-stok']);
}
if (isset($_POST['deleteBarang'])) {
    $dataBarang = $cAdmin->deleteBarang($_POST['delete-id']);
}
if (isset($_POST['requestBarang'])) {
    $dataBarang = $cAdmin->reqBarang($_POST['req-id'], $_POST['req-nama'], $_POST['req-harga'], $_POST['req-qty'], $_POST['req-desc']);
}
?>

<script src='assets\js\app.js'></script>

<body onload="selectedMenu('adminGudang')">
    <!-- Sidebar -->
    <?php
    include 'layout/sidebar.php';
    ?>

    <!-- Content -->
    <div class="admin-gudang content">
        <div class="grid-wrapper">
            <!-- Gudang List -->
            <div class="storage-list grid-card">
                <!-- Gudang Title -->
                <div class="card-info">
                    <div class="search">
                        <form method="post">
                            <input type="text" placeholder="Cari barang" name="searchQuery" id="searchQuery" required="required" autocomplete="off">
                            <button class="btn-search">Cari</button>
                        </form>
                    </div>
                    <button onclick="openModal('modal-addBarang')"> <span>Tambah Barang</span> <i class="material-icons"> add_box </i></button>
                </div>
                <!-- Gudang Table -->
                <div class="table">
                    <table id="table-barang">
                        <thead>
                            <tr>
                                <th style="width: 7vw !important;">ID Barang</th>
                                <th style="width: 45vw !important;">Nama Barang</th>
                                <th>Harga Satuan</th>
                                <th>Stok Barang</th>
                                <th style="width: 10vw !important;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataBarang as $b) : ?>
                                <tr>
                                    <td><?= $b['id_barang']; ?></td>
                                    <td><?= $b['nama_barang']; ?></td>
                                    <td><?= $b['harga_barang']; ?></td>
                                    <td><?= $b['stok_barang']; ?></td>
                                    <td class="table-action">
                                        <button id="btn-edit-barang" onclick="barangEdit('modal-editBarang', this, 'table-barang')"><i class="add material-icons"> create </i></button>
                                        <button id="btn-delete-barang" onclick="barangDelete('modal-deleteBarang', this, 'table-barang')"><i class="delete material-icons"> remove_circle_outline </i></button>
                                        <?php if (password_verify('superAdmin', $_COOKIE['session']) == true) : ?>
                                            <?php if ($b['stok_barang'] == 0) : ?>
                                                <button id="btn-req-barang" onclick="barangReq('modal-requestStock', this, 'table-barang')"><i class="delete material-icons"> inventory_2 </i></button>
                                            <?php else : ?>
                                                <button id="btn-req-barang" onclick="barangReq('modal-requestStock', this, 'table-barang')"><i class="delete material-icons disabled"> inventory_2 </i></button>
                                            <?php endif; ?>
                                        <?php endif; ?>
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
    <script src="assets/js/modal.js"></script>
    <div class="modal">
        <!-- Add Employee -->
        <div id="modal-addBarang" class="addBarang modal-container">
            <span>Tambah Barang</span>
            <form method="post" autocomplete="off">
                <label for="id-barang">ID Barang: </label>
                <input type="text" name="id-barang" id="id-barang" placeholder="">

                <label for="nama-barang">Nama Barang: </label>
                <input type="text" name="nama-barang" id="nama-barang" placeholder="">

                <label for="harga-barang">Harga Satuan:</label>
                <input type="number" name="harga-barang" id="harga-barang" placeholder="">

                <label for="stok-barang">Stok Barang:</label>
                <input type="text" name="stok-barang" id="stok-barang" placeholder="">
                <br>
                <button class="btn-ok" name="addBarang">Submit</button>
            </form>
            <button class="btn-no" onclick="closeModal('modal-addBarang')">Batal</button>
        </div>

        <!-- Edit Employee -->
        <div id="modal-editBarang" class="editBarang modal-container">
            <span>Ubah Data Barang</span>
            <form method="post" autocomplete="off">
                <!-- <label for="edit-id">ID Barang: </label> -->
                <input type="hidden" name="edit-id" id="edit-id" placeholder="">

                <label for="edit-nama">Nama Barang: </label>
                <input type="text" name="edit-nama" id="edit-nama" placeholder="">

                <label for="edit-harga">Harga Satuan:</label>
                <input type="number" name="edit-harga" id="edit-harga" placeholder="">

                <label for="edit-stok">Stok Barang:</label>
                <input type="text" name="edit-stok" id="edit-stok" placeholder="">
                <br>
                <button class="btn-ok" name="editBarang">Submit</button>
            </form>
            <button class="btn-no" onclick="closeModal('modal-editBarang')">Batal</button>
        </div>

        <!-- Delete Employee -->
        <div id="modal-deleteBarang" class="deleteBarang modal-container">
            <span>Hapus Data Barang?</span>
            <form method="post">
                <input type="hidden" name="delete-id" id="delete-id">
                <label>Apa anda yakin ingin menghapus data barang?</label>
                <button class="btn-no" name="deleteBarang">Hapus</button>
            </form>
            <button class="btn-ok" onclick="closeModal('modal-deleteBarang')">Batal</button>
        </div>

        <!-- Salary Employee -->
        <div id="modal-requestStock" class="requestStock modal-container">
            <span>Request Stok</span>
            <form method="post" autocomplete="off">
                <!-- <label for="req-id">ID Barang: </label> -->
                <input type="hidden" name="req-id" id="req-id" placeholder="">
                <input type="hidden" name="req-nama" id="req-nama">
                <input type="hidden" name="req-harga" id="req-harga">

                <label for="req-qty">Request Banyak Stok: </label>
                <input type="number" name="req-qty" id="req-qty" placeholder="0">

                <label for="req-desc">Keterangan: </label>
                <input type="text" name="req-desc" id="req-desc" placeholder="Masukkan Keterangan">
                <br>
                <button class="btn-ok" name="requestBarang">Submit</button>
            </form>
            <button class="btn-no" onclick="closeModal('modal-requestStock')">Batal</button>
        </div>
    </div>
</body>

<?php
include 'layout/footer.php';
?>