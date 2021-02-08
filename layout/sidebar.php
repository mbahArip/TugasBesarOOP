<div class="sidebar">
    <!-- Check Debug Mode -->
    <?php if (isset($_COOKIE['debug'])) : ?>
        <?php
        if (isset($_GET['debugRole'])) {
            $sc = new sessionCookie;
            if ($_GET['debugRole'] == 'superAdmin') {
                $hashAdmin = password_hash('superAdmin', PASSWORD_DEFAULT);
                $sc->createCookies('session', $hashAdmin, 365);
                header('Location: adminIndex');
                exit;
            } elseif ($_GET['debugRole'] == 'Admin') {
                $hashAdmin = password_hash('admin', PASSWORD_DEFAULT);
                $sc->createCookies('session', $hashAdmin, 365);
                header('Location: adminIndex');
                exit;
            } elseif ($_GET['debugRole'] == 'Keuangan') {
                $hashAdmin = password_hash('keuangan', PASSWORD_DEFAULT);
                $sc->createCookies('session', $hashAdmin, 365);
                header('Location: keuIndex');
                exit;
            } elseif ($_GET['debugRole'] == 'Gudang') {
                $hashAdmin = password_hash('gudang', PASSWORD_DEFAULT);
                $sc->createCookies('session', $hashAdmin, 365);
                header('Location: gudangIndex');
                exit;
            } elseif ($_GET['debugRole'] == 'reset') {
                $hashAdmin = password_hash('debug', PASSWORD_DEFAULT);
                $sc->createCookies('session', $hashAdmin, 365);
                header('Location: adminIndex');
                exit;
            }
        }

        ?>

        <div class="debug">
            <form method="GET">
                <button value="superAdmin" name="debugRole">Super Admin</button>
                <button value="Admin" name="debugRole">Admin</button><br>
                <button value="Keuangan" name="debugRole">Keuangan</button>
                <button value="Gudang" name="debugRole">Gudang</button><br>
                <button value="reset" name="debugRole">Reset</button>
            </form>
        </div>
    <?php endif; ?>

    <!-- User Info -->
    <div class="user-info">
        <?php
        $userData = $settings->getData($_COOKIE['id']);
        ?>
        <!-- Avatar -->
        <img src="<?= 'assets\avatar/' . $userData['avatar_karyawan']; ?>" alt=""><br>

        <!-- Info -->
        <label class="nama"><?= $_COOKIE['nama']; ?></label><br>
        <label class="info"><?= $_COOKIE['id']; ?></label><br>
        <?php if (password_verify('superAdmin', $_COOKIE['session']) == true) : ?>
            <label class="info">Super Admin</label>
        <?php elseif (password_verify('admin', $_COOKIE['session']) == true) : ?>
            <label class="info">Admin</label>
        <?php elseif (password_verify('debug', $_COOKIE['session']) == true) : ?>
            <label class="info">Debug</label>
        <?php elseif (password_verify('keuangan', $_COOKIE['session']) == true) : ?>
            <label class="info">Keuangan</label>
        <?php elseif (password_verify('gudang', $_COOKIE['session']) == true) : ?>
            <label class="info">Gudang</label>
        <?php endif; ?>
    </div>

    <br>
    <hr><br>

    <!-- Menu -->
    <ul class="menu">
        <?php if (password_verify('admin', $_COOKIE['session']) == true || password_verify('debug', $_COOKIE['session']) == true || password_verify('superAdmin', $_COOKIE['session']) == true) : ?>
            <li><a id='adminDash' href="adminIndex"> <i class="material-icons"> dashboard </i> Dashboard</a></li>
            <li><a id='adminUser' href="adminUser"> <i class="material-icons"> people </i> User Management</a></li>
            <li><a id='adminLapkeu' href="adminKeuangan"> <i class="material-icons"> request_quote </i> Keuangan</a></li>
            <li><a id='adminGudang' href="adminStorage"> <i class="material-icons"> storage </i> Gudang</a></li>
        <?php elseif (password_verify('keuangan', $_COOKIE['session']) == true) : ?>
            <li><a id='keuDash' href="keuIndex"> <i class="material-icons"> dashboard </i> Dashboard</a></li>
            <li><a id='keuLaporan' href="keuLaporan"> <i class="material-icons"> request_quote </i> Laporan Keuangan</a></li>
            <li><a id='keuReq' href="keuRequest"> <i class="material-icons"> people </i> Permintaan Barang</a></li>
        <?php elseif (password_verify('gudang', $_COOKIE['session']) == true) : ?>
            <li><a id='gudangDash' href="gudangIndex"> <i class="material-icons"> dashboard </i> Dashboard</a></li>
            <li><a id='gudangStorage' href="gudangStorage"> <i class="material-icons"> storage </i> Gudang</a></li>
            <li><a id='gudangReq' href="gudangRequest"> <i class="material-icons"> request_quote </i> Permintaan Barang</a></li>
        <?php endif; ?>
    </ul>

    <br>
    <hr><br>

    <!-- Setting -->
    <ul class="setting">
        <li><a id='userSetting' href="settings"> <i class="material-icons"> settings </i> Settings</a></li>
        <li><a onclick='logout()'> <i class="material-icons"> exit_to_app </i> Logout</a></li>
    </ul>

    <br>
    <hr><br>

    <!-- Footer -->
    <div class="web">
        <a href="https://github.com/mbahArip" target="_blank"><img src="assets\github.svg"></a>
        <a href="https://indomaret.co.id" target="_blank"><img src="assets\web.svg"></a>
    </div>
    <br>
    <label class="pt">PT Indomarco Prismata</label>
</div>

<!-- Hide Sidebar -->
<div class="hide-sidebar">
    <i class="material-icons" id="sidebar-arrow-back"> arrow_back_ios </i>
</div>

<!-- Show Sidebar -->
<div class="show-sidebar">
    <i class="material-icons" id="sidebar-arrow-forward"> arrow_forward_ios </i>
</div>

<script src="assets\js\sidebar.js"></script>