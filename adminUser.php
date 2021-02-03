<?php
include 'layout/header.php';
require 'function/c.php';

session_start();

$sc = new sessionCookie();
$vAdmin = new vAdmin();
$cAdmin = new cAdmin();

$sc->_checkSession('login');
$condition = password_verify('admin', $_COOKIE['session']) == false && password_verify('debug', $_COOKIE['session']) == false && password_verify('superAdmin', $_COOKIE['session']) == false;
$sc->_checkRank($condition);

$dataEmployee = $vAdmin->showEmployee();

if (isset($_POST['searchQuery'])) {
    $dataEmployee = $cAdmin->searchQuery($_POST['searchQuery']);
}
if (isset($_POST['addEmployee'])) {
    $cAdmin->newEmployee($_POST['nama'], $_POST['email'], $_POST['posisi'], $_POST['alamat'], $_POST['telp']);
}
if (isset($_POST['editEmployee'])) {
    $cAdmin->editEmployee($_POST['edit-id'], $_POST['edit-nama'], $_POST['edit-email'], $_POST['edit-posisi'], $_POST['edit-alamat'], $_POST['edit-telp']);
}
if (isset($_POST['deleteEmployee'])) {
    $cAdmin->deleteEmployee($_POST['delete-id']);
}
if (isset($_POST['salaryEmployee'])) {
    $cAdmin->salaryEmployee($_POST['salary-id'], $_POST['salary-number']);
}
?>

<script src='assets\js\app.js'></script>

<body onload="selectedMenu('adminUser')">
    <!-- Sidebar -->
    <?php
    include 'layout/sidebar.php';
    ?>

    <!-- Content -->
    <div class="admin-user content">
        <div class="grid-wrapper">
            <!-- Employee List -->
            <div class="employee-list grid-card">
                <!-- Employee Title -->
                <div class="card-info">
                    <div class="search">
                        <form method='POST'>
                            <input type="text" placeholder="Cari karyawan" name="searchQuery" id="searchQuery" required="required" autocomplete="off">
                            <button class="btn-search">Cari</button>
                        </form>
                    </div>
                    <button onclick="openModal('modal-addEmployee')"> <span>Tambah Karyawan</span> <i class="material-icons"> person_add </i></button>
                </div>
                <!-- Employee Table -->
                <div class="table">
                    <table id="table-employee">
                        <thead>
                            <tr>
                                <th style="width: 5vw !important;">ID Karyawan</th>
                                <th style="width: 25vw !important;">Nama Karyawan</th>
                                <th style="width: 7vw !important;">Posisi</th>
                                <th style="width: 15vw !important;">Email</th>
                                <th style="width: 30vw !important;">Alamat</th>
                                <th>Telepon</th>
                                <th>Gaji</th>
                                <th style="width: 12vw !important;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataEmployee as $e) : ?>
                                <tr>
                                    <td style="text-align: center;"><?= $e['id_karyawan']; ?></td>
                                    <td><?= $e['nama_karyawan']; ?></td>
                                    <td style="text-align: center; text-transform: capitalize;"><?= $e['rank_karyawan']; ?></td>
                                    <td><?= $e['email_karyawan']; ?></td>
                                    <td><?= $e['alamat_karyawan']; ?></td>
                                    <td><?= $e['telp_karyawan']; ?></td>
                                    <td style="text-align: center;"><?= 'IDR ' . $e['gaji_karyawan']; ?></td>
                                    <td class="table-action">
                                        <button id="btn-edit-employee" onclick="employeeEdit('modal-editEmployee', this, 'table-employee')"><i class="add material-icons"> create </i></button>
                                        <button id="btn-delete-employee" onclick="employeeDelete('modal-deleteEmployee', this, 'table-employee')"><i class="delete material-icons"> remove_circle_outline </i></button>
                                        <button id="btn-gaji-employee" onclick="employeeSalary('modal-salaryEmployee', this, 'table-employee')"><i class="delete material-icons"> payments </i></button>
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
        <!-- Add Employee -->
        <div id="modal-addEmployee" class="addEmployee modal-container">
            <span>Tambah Karyawan Baru</span>
            <form method="post" autocomplete="off">
                <label for="nama">Nama Lengkap Karyawan: </label>
                <input type="text" name="nama" id="nama" placeholder="">

                <label for="email">Email Karyawan: </label>
                <input type="text" name="email" id="email" placeholder="">

                <label for="posisi">Posisi Karyawan:</label>
                <select name="posisi" id="posisi">
                    <option value="admin">Admin</option>
                    <option value="keuangan">Keuangan</option>
                    <option value="gudang">Gudang</option>
                </select>

                <label for="alamat">Alamat Karyawan:</label>
                <input type="text" name="alamat" id="alamat" placeholder="">

                <label for="telp">Nomor Telepon Karyawan:</label>
                <input type="number" name="telp" id="telp" placeholder="">
                <br>
                <button class="btn-ok" name="addEmployee">Submit</button>
            </form>
            <button class="btn-no" onclick="closeModal('modal-addEmployee')">Batal</button>
        </div>

        <!-- Edit Employee -->
        <div id="modal-editEmployee" class="editEmployee modal-container">
            <span>Ubah Data Karyawan</span>
            <form method="post" autocomplete="off">
                <input type="hidden" name="edit-id" id="edit-id" value="">
                <label for="edit-nama">Nama Lengkap Karyawan: </label>
                <input type="text" name="edit-nama" id="edit-nama" placeholder="">

                <label for="edit-email">Email Karyawan: </label>
                <input type="text" name="edit-email" id="edit-email" placeholder="">

                <label for="edit-posisi">Posisi Karyawan:</label>
                <select name="edit-posisi" id="edit-posisi">
                    <option value="admin">Admin</option>
                    <option value="keuangan">Keuangan</option>
                    <option value="gudang">Gudang</option>
                </select>

                <label for="edit-alamat">Alamat Karyawan:</label>
                <input type="text" name="edit-alamat" id="edit-alamat" placeholder="">

                <label for="edit-telp">Nomor Telepon Karyawan:</label>
                <input type="text" name="edit-telp" id="edit-telp" placeholder="">
                <br>
                <button class="btn-ok" name="editEmployee">Submit</button>
            </form>
            <button class="btn-no" onclick="closeModal('modal-editEmployee')">Batal</button>
        </div>

        <!-- Delete Employee -->
        <div id="modal-deleteEmployee" class="deleteEmployee modal-container">
            <span>Hapus Data Karyawan?</span>
            <form method="post">
                <input type="hidden" name="delete-id" id="delete-id" value="">
                <label>Apa anda yakin ingin menghapus data karyawan?</label>
                <button class="btn-no" name="deleteEmployee">Hapus</button>
            </form>
            <button class="btn-ok" onclick="closeModal('modal-deleteEmployee')">Batal</button>
        </div>

        <!-- Salary Employee -->
        <div id="modal-salaryEmployee" class="salaryEmployee modal-container">
            <span>Ubah Gaji Karyawan</span>
            <form method="post" autocomplete="off">
                <input type="hidden" name="salary-id" id="salary-id" value="">
                <label for="gaji">Gaji Karyawan: </label>
                <p class="currency">IDR</p>
                <input type="number" name="salary-number" id="salary-number" placeholder="">
                <br>
                <button class="btn-ok" name="salaryEmployee">Submit</button>
            </form>
            <button class="btn-no" onclick="closeModal('modal-salaryEmployee')">Batal</button>
        </div>
    </div>
</body>

<?php
include 'layout/footer.php';
?>