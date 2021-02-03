<?php

if (password_verify('admin', $_COOKIE['session']) == true || password_verify('debug', $_COOKIE['session']) == true || password_verify('superAdmin', $_COOKIE['session']) == true) {
    header('Location: adminIndex');
    exit;
} else if (password_verify('keuangan', $_COOKIE['session']) == true) {
    header('Location: keuIndex');
    exit;
} else if (password_verify('gudang', $_COOKIE['session']) == true) {
    header('Location: gudangIndex');
    exit;
} else {
    header('Location: logout');
    exit;
}
