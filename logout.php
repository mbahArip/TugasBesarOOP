<?php
session_start();
unset($_SESSION['login']);
session_destroy();
setcookie('session', '', time() - 3600);
setcookie('id', '', time() - 3600);
setcookie('nama', '', time() - 3600);
setcookie('debug', '', time() - 3600);

header('Location: login');
exit;
