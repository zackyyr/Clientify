<?php
// Konfigurasi Database
$host = "localhost"; // Ubah jika database ada di server lain
$user = "root"; // Ganti dengan username database
$password = ""; // Ganti dengan password database
$database = "clientify"; // Ganti dengan nama database

// Membuat koneksi ke MySQL
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengatur charset ke UTF-8 (opsional, untuk mencegah error encoding)
$conn->set_charset("utf8");


?>
