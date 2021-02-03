<?php
include 'layout/header.php';
require 'function/c.php';
require 'function/v.php';

session_start();

$sc = new sessionCookie();

$sc->_checkSession('login');
$condition = password_verify('admin', $_COOKIE['session']) == false && password_verify('debug', $_COOKIE['session']) == false && password_verify('superAdmin', $_COOKIE['session']) == false;
$sc->_checkRank($condition);
?>

<script src='assets\js\app.js'></script>

<body onload="selectedMenu('adminLapkeu')">
    <!-- Sidebar -->
    <?php
    include 'layout/sidebar.php';
    ?>


</body>

<?php
include 'layout/footer.php';
?>