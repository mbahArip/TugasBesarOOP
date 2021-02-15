<?php
$pageName = 'Login';
require 'layout/header.php';
require 'function/c.php';

$db = new database();
$login = new functionLogin($db);

session_start();

//Cek Session
if (isset($_SESSION['login'])) {
    header("Location: index");
    exit;
}

//Login
if (isset($_POST['login'])) {
    $id = $_POST['loginUser'];
    $pass = $_POST['loginPass'];

    $login->logIn($id, $pass);
    $error = true;
}

//Generator
if (isset($_POST['string'])) {
    // $hash = password_hash($_POST['string'], PASSWORD_DEFAULT);
    // echo $hash;
    for ($i = 0; $i < 10; $i++) {
        // $hash = password_hash($_POST['string'], PASSWORD_DEFAULT);
        // echo $hash . "<br>";
    }
}

?>
<!-- BCRYPT Generator -->
<!-- <form name='generator' method='post'>
    <input type="text" name="string" id="string">
    <button type="submit">Generate</button>
</form> -->

<body>

    <div class="login-wrapper">
        <img src="assets\logo.svg">
        <form name="login" class="login" method="POST">
            <span>ID Karyawan</span>
            <br>
            <input type="text" name="loginUser" id="loginUser" placeholder="ID Karyawan" autocomplete="off" required="required" maxlength="10">
            <br>
            <span>Password</span>
            <br>
            <input type="password" name="loginPass" id="loginPass" placeholder="Password User" autocomplete="off" required="required">
            <br>
            <?php if (isset($error)) : ?>
                <label>Login Error! Mohon periksa kembali ID dan Password!</label>
            <?php endif; ?>
            <br>
            <button type="submit" name="login">Login</button><br>
            <p>Hubungi Admin jika lupa password</p>
        </form>
    </div>

</body>

</html>