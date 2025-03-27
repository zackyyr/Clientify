<?php
session_start();
require_once "../config/config.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Adding leads
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $company = $_POST['company'];
    $email = $_POST['email'];
    $status = $_POST['status'];
    $source = $_POST['source'];
    $location = $_POST['location'];
    $user_id = $_POST['user_id']; // Ambil user_id dari form

    $stmt = $conn->prepare("INSERT INTO leads (name, position, company, email, status, source, location, user_id) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssi", $name, $position, $company, $email, $status, $source, $location, $user_id);
    
    // Eksekusi statement
    if ($stmt->execute()) {
        header("Location: ../models/lead-management.php");
        exit();
    } else {
        die("Execute failed: " . $stmt->error);
    }
    // Tutup statement
    $stmt->close();
}


// Edit Leads
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    $company = $_POST['company'];
    $email = $_POST['email'];
    $status = $_POST['status'];
    $source = $_POST['source'];
    $location = $_POST['location'];

    $stmt = $conn->prepare("UPDATE leads SET name=?, position=?, company=?, email=?, status=?, source=?, location=? WHERE id=?");
    $stmt->bind_param("sssssssi", $name, $position, $company, $email, $status, $source, $location, $id);
    
    // Eksekusi statement
    if ($stmt->execute()) {
        header("Location: ../models/lead-management.php");
    } else {
        die("Execute failed: " . $stmt->error);
    }
    exit();
    
    // Tutup statement
    $stmt->close();
}

// Delete Leads
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM leads WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: ../models/lead-management.php");
        exit();
    } else {
        die("Execute failed: " . $stmt->error);
    }
    $stmt->close();
}

?>