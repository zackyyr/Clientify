<?php
session_start();
require_once "../config/config.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Menambahkan Invoice
if (isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $services = trim($_POST['services']);
    $currency = $_POST['currency'];  // Ambil currency dari form
    $amount = str_replace(',', '', $_POST['amount']); // Hapus pemisah ribuan
    $billing_date = $_POST['billing_date'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];
    $user_id = $_POST['user_id'];
    $lead_id = isset($_POST['lead_id']) ? $_POST['lead_id'] : NULL; // Jika tidak ada, biarkan NULL

    // Generate invoice number
    $datePart = date("Ymd");
    $randomNumber = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
    $invoice_number = "INV-{$datePart}-{$randomNumber}";

    // Validasi apakah semua data sudah terisi
    if (empty($name) || empty($contact) || empty($services) || empty($amount) || empty($billing_date) || empty($due_date) || empty($status) || empty($user_id)) {
        die("All fields are required!");
    }

    // Query INSERT yang sudah diperbaiki
    $stmt = $conn->prepare("INSERT INTO invoices (lead_id, name, contact, user_id, services, invoice_number, currency, amount, billing_date, due_date, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississsdsss", $lead_id, $name, $contact, $user_id, $services, $invoice_number, $currency, $amount, $billing_date, $due_date, $status);

    if ($stmt->execute()) {
        header("Location: ../models/invoicing.php?success=1");
        exit();
    } else {
        die("Execute failed: " . $stmt->error);
    }

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