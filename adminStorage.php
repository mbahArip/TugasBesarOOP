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
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 10vw !important;">ID Barang</th>
                                <th style="width: 35vw !important;">Nama Barang</th>
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
                                        <button id="btn-edit-barang"><i class="add material-icons"> create </i></button>
                                        <button id="btn-delete-barang"><i class="delete material-icons"> remove_circle_outline </i></button>
                                        <?php if (password_verify('superAdmin', $_COOKIE['session']) == true) : ?>
                                            <button id="btn-req-barang"><i class="delete material-icons"> inventory_2 </i></button>
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
            <form>
                <label for="id">ID Barang: </label>
                <input type="text" id="id" placeholder="">

                <label for="nama">Nama Barang: </label>
                <input type="text" id="nama" placeholder="">

                <label for="harga">Harga Satuan:</label>
                <input type="number" id="harga" placeholder="">

                <label for="stok">Stok Barang:</label>
                <input type="text" id="stok" placeholder="">
                <br>
                <button class="btn-ok">Submit</button>
            </form>
            <button class="btn-no" onclick="closeModal('modal-addBarang')">Batal</button>
        </div>

        <!-- Edit Employee -->
        <div id="modal-editBarang" class="editBarang modal-container">
            <span>Ubah Data Barang</span>
            <form>
                <label for="edit-id">ID Barang: </label>
                <input type="text" id="edit-id" placeholder="">

                <label for="edit-nama">Nama Barang: </label>
                <input type="text" id="edit-nama" placeholder="">

                <label for="edit-harga">Harga Satuan:</label>
                <input type="number" id="edit-harga" placeholder="">

                <label for="edit-stok">Stok Barang:</label>
                <input type="text" id="edit-stok" placeholder="">
                <br>
                <button class="btn-ok">Submit</button>
            </form>
            <button class="btn-no" onclick="closeModal('modal-editBarang')">Batal</button>
        </div>

        <!-- Delete Employee -->
        <div id=" modal-deleteBarang" class="deleteBarang modal-container">
            <span>Hapus Data Barang?</span>
            <form>
                <label>Apa anda yakin ingin menghapus data barang?</label>
                <button class="btn-no">Hapus</button>
            </form>
            <button class="btn-ok" onclick="closeModal('modal-deleteBarang')">Batal</button>
        </div>

        <!-- Salary Employee -->
        <div id="modal-requestStock" class="requestStock modal-container">
            <span>Request Stok</span>
            <form>
                <label for="req-id">ID Barang: </label>
                <input type="text" id="req-id" placeholder="">

                <label for="req-qty">Request Banyak Stok: </label>
                <input type="number" id="req-qty" placeholder="">
                <br>
                <button class="btn-ok">Submit</button>
            </form>
            <button class="btn-no" onclick="closeModal('modal-requestStock')">Batal</button>
        </div>
    </div>
</body>

<?php
include 'layout/footer.php';
?>