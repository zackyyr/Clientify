<?php
session_start();
require_once "../config/config.php";
require_once "../controllers/auth_guard.php"; 

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan user sudah login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$filter = isset($_POST['filter']) ? $_POST['filter'] : 'this_month';

// Tentukan rentang tanggal berdasarkan filter
if ($filter === "this_month") {
    $dateCondition = "AND MONTH(billing_date) = MONTH(CURDATE()) AND YEAR(billing_date) = YEAR(CURDATE())";
    $lastDateCondition = "AND MONTH(billing_date) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(billing_date) = YEAR(CURDATE())";
} elseif ($filter === "last_month") {
    $dateCondition = "AND MONTH(billing_date) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(billing_date) = YEAR(CURDATE())";
    $lastDateCondition = "AND MONTH(billing_date) = MONTH(CURDATE() - INTERVAL 2 MONTH) AND YEAR(billing_date) = YEAR(CURDATE())";
} else { // this_year
    $dateCondition = "AND YEAR(billing_date) = YEAR(CURDATE())";
    $lastDateCondition = "AND YEAR(billing_date) = YEAR(CURDATE() - INTERVAL 1 YEAR)";
}

// Function buat hitung jumlah invoice berdasarkan filter
function getCount($conn, $user_id, $status, $dateCondition) {
    $count = 0;
    $query = $conn->prepare("SELECT COUNT(*) FROM invoices WHERE user_id = ? $dateCondition" . ($status ? " AND status = ?" : ""));
    
    if ($status) {
        $query->bind_param("is", $user_id, $status);
    } else {
        $query->bind_param("i", $user_id);
    }

    $query->execute();
    $query->bind_result($count);
    $query->fetch();
    $query->close();

    return $count;
}

// Hitung jumlah invoice berdasarkan filter saat ini
$createdInvoices = getCount($conn, $user_id, null, $dateCondition);
$pendingInvoices = getCount($conn, $user_id, 'pending', $dateCondition);
$paidInvoices = getCount($conn, $user_id, 'paid', $dateCondition);
$overdueInvoices = getCount($conn, $user_id, 'overdue', $dateCondition);

// Hitung jumlah invoice periode sebelumnya buat perbandingan
$lastCreatedInvoices = getCount($conn, $user_id, null, $lastDateCondition);
$lastPendingInvoices = getCount($conn, $user_id, 'pending', $lastDateCondition);
$lastPaidInvoices = getCount($conn, $user_id, 'paid', $lastDateCondition);
$lastOverdueInvoices = getCount($conn, $user_id, 'overdue', $lastDateCondition);

// Function buat hitung persentase perubahan
function calculatePercentage($current, $previous) {
    if ($previous == 0) return $current > 0 ? 100 : 0;
    return round((($current - $previous) / $previous) * 100, 2);
}

// Format response buat dikirim ke frontend (JavaScript)
$response = [
    "created" => $createdInvoices,
    "pending" => $pendingInvoices,
    "paid" => $paidInvoices,
    "overdue" => $overdueInvoices,
    "created_percentage" => calculatePercentage($createdInvoices, $lastCreatedInvoices),
    "pending_percentage" => calculatePercentage($pendingInvoices, $lastPendingInvoices),
    "paid_percentage" => calculatePercentage($paidInvoices, $lastPaidInvoices),
    "overdue_percentage" => calculatePercentage($overdueInvoices, $lastOverdueInvoices),
];

echo json_encode($response);
?>
