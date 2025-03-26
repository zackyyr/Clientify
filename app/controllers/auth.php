<?php
session_start();
require_once "../config/config.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['register'])) {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $_SESSION['status'] = ['type' => 'warning', 'message' => 'Passwords do not match!'];
        header("Location: ../models/register.php");
        exit();
    }

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    $check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        $_SESSION['status'] = ['type' => 'warning', 'message' => 'Email is already registered!'];
        header("Location: ../models/register.php"); // Kembali ke register jika email sudah ada
        exit();
    } else {
        $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $username, $password_hashed);

        if ($stmt->execute()) {
            $_SESSION['status'] = ['type' => 'success', 'message' => 'Account created successfully! Please login.'];
            header("Location: ../models/login.php"); // Arahkan ke login jika sukses
        } else {
            $_SESSION['status'] = ['type' => 'danger', 'message' => 'Something went wrong, please try again.'];
            header("Location: ../models/register.php"); // Kembali ke register jika gagal
        }

        $stmt->close();
    }

    $check_email->close();
    $conn->close();
    exit();
}

// Login
if (isset($_POST['login'])) { 
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Ambil user berdasarkan email
    $stmt = $conn->prepare("SELECT id, email, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) { 
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) { 
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            header("Location: ../models/lead-management.php"); // Redirect setelah login sukses
            exit();
        } else {
            $_SESSION['status'] = ['type' => 'danger', 'message' => 'Incorrect password!'];
        }
    } else {
        $_SESSION['status'] = ['type' => 'warning', 'message' => 'Account not found!'];
    }

    $stmt->close();
    $conn->close();
    header("Location: ../models/login.php"); // Redirect kembali ke login
    exit();
}
