<?php
include 'layout/header.php';
require 'function/c.php';
require 'function/v.php';

session_start();

$sc = new sessionCookie();

$sc->_checkSession('login');
$condition = password_verify('admin', $_COOKIE['session']) == false && password_verify('debug', $_COOKIE['session']) == false && password_verify('superAdmin', $_COOKIE['session']) == false && password_verify('keuangan', $_COOKIE['session']) == false && password_verify('gudang', $_COOKIE['session']) == false;
$sc->_checkRank($condition);
?>

<script src='assets\js\app.js'></script>

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
                <!-- Setting Form -->
                <table>
                    <tbody>
                        <tr>
                            <!-- Form Upload Photo -->
                            <form>
                                <td class="settingPhoto">
                                    <h2>Ubah Photo</h2>
                                    <img src="assets\avatar\ava.png" alt=""><br>
                                    <input type="file" accept="image/*" id="settingUpload" name="settingUpload" required="required">
                                    <button class="btn-upload">Upload</button>

                                </td>
                            </form>
                            <!-- Form Data -->
                            <form>
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
                                    <input type="password" id="settingOldPass" name="settingOldPass"><br>
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
                <button>Simpan</button>
                </form>
                <!-- Get data from database -->
            </div>
        </div>
    </div>
</body>

<?php
include 'layout/footer.php';
?>