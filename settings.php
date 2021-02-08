<?php
$pageName = 'User Settings';
include 'layout/header.php';
require 'function/c.php';
require 'function/function.php';

session_start();

$sc->_checkSession('login');
$condition = password_verify('admin', $_COOKIE['session']) == false && password_verify('debug', $_COOKIE['session']) == false && password_verify('superAdmin', $_COOKIE['session']) == false && password_verify('keuangan', $_COOKIE['session']) == false && password_verify('gudang', $_COOKIE['session']) == false;
$sc->_checkRank($condition);
$error = null;

if (isset($_POST['settingUpdate'])) {
    $settings->updateData($_COOKIE['id'], $_POST['settingNama'], $_POST['settingEmail'], $_POST['settingAlamat'], $_POST['settingOldPass'], $_POST['settingNewPass'], $_POST['settingNewPass2'], $_POST['settingTelp']);
}
?>

<body onload="selectedMenu('userSetting')">
    <!-- Sidebar -->
    <?php
    include 'layout/sidebar.php';
    ?>

    <!-- Content -->
    <div class="user-setting content">
        <div class="grid-wrapper">
            <!-- User Setting -->
            <div class="setting grid-card">
                <!-- Setting Title -->
                <div class="card-info">
                    <span>User Settings <i class="material-icons">manage_accounts</i></span><br>
                </div>
                <?php if ($error == 'oldPass') : ?>
                    <label class="failed">Error! Password Lama tidak sama!</label>
                <?php elseif ($error == 'newPass') : ?>
                    <label class="failed">Error! Password Baru tidak sama!</label>
                <?php elseif ($error == 'null') : ?>
                    <label class="success">Data berhasil diubah!</label>
                <?php endif; ?>
                <!-- Setting Form -->
                <table>
                    <tbody>
                        <tr>
                            <!-- Form Upload Photo -->
                            <form action="upload" method="post" enctype="multipart/form-data">
                                <td class="settingPhoto">
                                    <h2>Ubah Photo</h2>
                                    <img src="<?= 'assets\avatar/' . $userData['avatar_karyawan']; ?>" alt=""><br>
                                    <input type="file" accept="image/*" id="settingUpload" name="image" required="required">
                                    <button class="btn-upload" name="imageUpdate">Upload</button>
                                </td>
                            </form>
                            <!-- Form Data -->
                            <form method="post">
                                <td class="inputLeft">
                                    <h2>Nama Lengkap</h2>
                                    <input type="text" id="settingNama" name="settingNama" readonly><br>
                                    <h2>Email</h2>
                                    <input type="text" id="settingEmail" name="settingEmail"><br><br>
                                    <h2>Alamat Lengkap</h2>
                                    <textarea id="settingAlamat" name="settingAlamat" rows="5"></textarea>
                                </td>
                                <td class="inputRight">
                                    <h2>Password Lama</h2>
                                    <input type="password" id="settingOldPass" name="settingOldPass" onfocusout="password()"><br>
                                    <h2>Password Baru</h2>
                                    <input type="password" id="settingNewPass" name="settingNewPass"><br>
                                    <h2>Konfirmasi</h2>
                                    <input type="password" id="settingNewPass2" name="settingNewPass2"><br>
                                    <h2>Nomor Telepon</h2>
                                    <input type="text" id="settingTelp" name="settingTelp">
                                </td>
                        </tr>
                    </tbody>
                </table>
                <button name="settingUpdate">Simpan</button>
                </form>
                <!-- Get data from database -->
                <script>
                    document.getElementById('settingNama').value = '<?= $userData['nama_karyawan']; ?>'
                    document.getElementById('settingEmail').value = '<?= $userData['email_karyawan']; ?>'
                    document.getElementById('settingAlamat').value = '<?= $userData['alamat_karyawan']; ?>'
                    document.getElementById('settingTelp').value = '<?= $userData['telp_karyawan']; ?>'

                    function password() {
                        if (document.getElementById('settingOldPass').value != '') {
                            document.getElementById('settingNewPass').setAttribute('required', 'required')
                            document.getElementById('settingNewPass2').setAttribute('required', 'required')
                        } else {
                            document.getElementById('settingNewPass').removeAttribute('required')
                            document.getElementById('settingNewPass2').removeAttribute('required')
                        }
                    }
                </script>
            </div>
        </div>
    </div>
</body>

<?php
include 'layout/footer.php';
?>