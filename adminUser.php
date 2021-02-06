<?php
$pageName = 'User Management';
include 'layout/header.php';
require 'function/c.php';
require 'function/function.php';

session_start();

$sc->_checkSession('login');
$condition = password_verify('admin', $_COOKIE['session']) == false && password_verify('debug', $_COOKIE['session']) == false && password_verify('superAdmin', $_COOKIE['session']) == false;
$sc->_checkRank($condition);

$dataEmployee = $vAdmin->showEmployee();

//Search Query
if (isset($_POST['searchQuery'])) {
    $dataEmployee = $search->userSearch($_POST['searchQuery']);
}
//Add Query
if (isset($_POST['addUser'])) {
    $add->addUser($_POST['addNama'], $_POST['addEmail'], $_POST['addPosisi'], $_POST['addAlamat'], $_POST['addTelp']);
}
//Edit Query
if (isset($_POST['editUser'])) {
    $edit->editUser($_POST['editId'], $_POST['editNama'], $_POST['editEmail'], $_POST['editPosisi'], $_POST['editAlamat'], $_POST['editTelp']);
}
//Delete Query
if (isset($_POST['deleteUser'])) {
    $delete->deleteUser($_POST['deleteId']);
}
//Extra Query
if (isset($_POST['salaryUser'])) {
    $extra->salaryUser($_POST['salaryId'], $_POST['salaryNumber']);
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
                <label for="addNama">Nama Lengkap Karyawan: </label>
                <input type="text" name="addNama" id="nama" placeholder="">

                <label for="addEmail">Email Karyawan: </label>
                <input type="text" name="addEmail" id="email" placeholder="">

                <label for="addPosisi">Posisi Karyawan:</label>
                <select name="addPosisi" id="posisi">
                    <option value="admin">Admin</option>
                    <option value="keuangan">Keuangan</option>
                    <option value="gudang">Gudang</option>
                </select>

                <label for="addAlamat">Alamat Karyawan:</label>
                <input type="text" name="addAlamat" id="alamat" placeholder="">

                <label for="addTelp">Nomor Telepon Karyawan:</label>
                <input type="number" name="addTelp" id="telp" placeholder="">
                <br>
                <button class="btn-ok" name="addUser">Submit</button>
            </form>
            <button class="btn-no" onclick="closeModal('modal-addEmployee')">Batal</button>
        </div>

        <!-- Edit Employee -->
        <div id="modal-editEmployee" class="editEmployee modal-container">
            <span>Ubah Data Karyawan</span>
            <form method="post" autocomplete="off">
                <input type="hidden" name="editId" id="edit-id" value="">
                <label for="editNama">Nama Lengkap Karyawan: </label>
                <input type="text" name="editNama" id="edit-nama" placeholder="">

                <label for="editEmail">Email Karyawan: </label>
                <input type="text" name="editEmail" id="edit-email" placeholder="">

                <label for="editPosisi">Posisi Karyawan:</label>
                <select name="editPosisi" id="edit-posisi">
                    <option value="admin">Admin</option>
                    <option value="keuangan">Keuangan</option>
                    <option value="gudang">Gudang</option>
                </select>

                <label for="editAlamat">Alamat Karyawan:</label>
                <input type="text" name="editAlamat" id="edit-alamat" placeholder="">

                <label for="editTelp">Nomor Telepon Karyawan:</label>
                <input type="text" name="editTelp" id="edit-telp" placeholder="">
                <br>
                <button class="btn-ok" name="editUser">Submit</button>
            </form>
            <button class="btn-no" onclick="closeModal('modal-editEmployee')">Batal</button>
        </div>

        <!-- Delete Employee -->
        <div id="modal-deleteEmployee" class="deleteEmployee modal-container">
            <span>Hapus Data Karyawan?</span>
            <form method="post">
                <input type="hidden" name="deleteId" id="delete-id" value="">
                <label>Apa anda yakin ingin menghapus data karyawan?</label>
                <button class="btn-no" name="deleteUser">Hapus</button>
            </form>
            <button class="btn-ok" onclick="closeModal('modal-deleteEmployee')">Batal</button>
        </div>

        <!-- Salary Employee -->
        <div id="modal-salaryEmployee" class="salaryEmployee modal-container">
            <span>Ubah Gaji Karyawan</span>
            <form method="post" autocomplete="off">
                <input type="hidden" name="salaryId" id="salary-id" value="">
                <label for="salaryNumber">Gaji Karyawan: </label>
                <p class="currency">IDR</p>
                <input type="number" name="salaryNumber" id="salary-number" placeholder="">
                <br>
                <button class="btn-ok" name="salaryUser">Submit</button>
            </form>
            <button class="btn-no" onclick="closeModal('modal-salaryEmployee')">Batal</button>
        </div>
    </div>
</body>

<?php
include 'layout/footer.php';
?>