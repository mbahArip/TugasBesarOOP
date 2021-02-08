<?php
$pageName = 'Upload';
include 'layout/header.php';
require 'function/c.php';
require 'function/function.php';

//Randomize Name
$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
$files = $_FILES['image']['name'];
$extension = pathinfo($files, PATHINFO_EXTENSION);
$randomName = 'avatar-' . substr(str_shuffle($permitted_chars), 0, 15) . '.' . $extension;

//Metadata
$fileName = $randomName;
$tempName = $_FILES['image']['tmp_name'];

//Directory
$dir = 'assets\avatar';

//Move Files
$move = move_uploaded_file($tempName, $dir . '/' . $fileName);

if ($move) {
    $settings->uploadAvatar($_COOKIE['id'], $fileName);
} else {
    echo 'Upload Gagal!<br>Cek ulang files!';
    echo "<a href='settings'>Kembali</a>";
}
