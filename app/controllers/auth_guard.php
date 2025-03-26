<?php
session_start();

// Cek jika user sudah login dan mencoba mengakses login atau register, redirect ke lead-management.php
if (isset($_SESSION['user_id']) && (basename($_SERVER['PHP_SELF']) == "login.php" || basename($_SERVER['PHP_SELF']) == "register.php")) {
    header("Location: lead-management.php");
    exit();
}

// Cek jika user belum login dan mencoba mengakses halaman selain login/register, redirect ke login.php
if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) != "login.php" && basename($_SERVER['PHP_SELF']) != "register.php") {
    header("Location: login.php");
    exit();
}
?>
