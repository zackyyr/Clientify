<?php
require_once "../../vendor/autoload.php"; 
require_once "../config/config.php"; // Pastikan config database di-include
use Mpdf\Mpdf;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek apakah ada ID invoice di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid invoice ID.");
}

$invoice_id = intval($_GET['id']);

// Ambil data invoice dari database
$query = $conn->prepare("SELECT invoices.*, users.username AS user_name, users.email AS user_email
                         FROM invoices 
                         LEFT JOIN users ON invoices.user_id = users.id
                         WHERE invoices.id = ?");
$query->bind_param("i", $invoice_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    die("Invoice not found.");
}

$invoiceData = $result->fetch_assoc();

// Konversi data ke format yang bisa dipakai di PDF
$services = explode(",", $invoiceData['services']); // Asumsi layanan dipisah koma
$total = $invoiceData['amount'];
$todayDate = date("Y-m-d");

$mpdf = new \Mpdf\Mpdf([
    'tempDir' => __DIR__ . '/../../tmp'
]);

// HTML untuk invoice
$html = '
<style>
    body { font-family: Arial, sans-serif; color: #333; margin: 20px; }
    .invoice-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .invoice-header .left { font-size: 14px; }
    .invoice-header .right img { width: 120px; }

    .divider { border-bottom: 2px solid #ddd; margin: 20px 0; }

    .info { display: flex; justify-content: space-between; font-size: 14px; }
    .info .box { width: 48%; }

    .service-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .service-table th, .service-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    .service-table th { background-color: #f4f4f4; }

    .summary { display: flex; justify-content: space-between; font-size: 14px; margin-top: 20px; }
    .summary .left { width: 48%; }
    .summary .right { width: 48%; text-align: right; }

    .total-section { text-align: right; font-size: 20px; font-weight: bold; background: #28a745; color: white; padding: 10px; margin-top: 20px; }
</style>

<div class="invoice-header">
    <div class="left">
        <strong>Invoice ID:</strong> ' . htmlspecialchars($invoiceData['invoice_number']) . '<br>
        <strong>Date:</strong> ' . $todayDate . '
    </div>

</div>

<div class="divider"></div>

<div class="info">
    <div class="box">
        <strong>Bill From:</strong><br>
        ' . htmlspecialchars($invoiceData['user_name']) . '<br>
        ' . htmlspecialchars($invoiceData['user_email']) . '
    </div>
    <div class="box">
        <strong>Bill To:</strong><br>
        ' . htmlspecialchars($invoiceData['name']) . '<br>
        ' . htmlspecialchars($invoiceData['contact']) . '
    </div>
</div>

<div class="divider"></div>

<table class="service-table">
    <tr>
        <th>Services</th>
        <th>Billing Date</th>
        <th>Due Date</th>
        <th>Tax</th>
        <th>Amount</th>
    </tr>';

foreach ($services as $service) {
    $html .= '
    <tr>
        <td>' . htmlspecialchars(trim($service)) . '</td>
        <td>' . htmlspecialchars($invoiceData['billing_date']) . '</td>
        <td>' . htmlspecialchars($invoiceData['due_date']) . '</td>
        <td>' . htmlspecialchars($invoiceData['currency']) . ' 0.00</td>
        <td>' . htmlspecialchars($invoiceData['currency']) . ' ' . number_format($invoiceData['amount'] / count($services), 2) . '</td>
    </tr>';
}

$html .= '
</table>

<div class="divider"></div>

<div class="summary">
    <div class="left">
        <strong>Terms and Conditions:</strong><br>
        -
    </div>
    <div class="right">
        <table>
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td>' . htmlspecialchars($invoiceData['currency']) . ' ' . number_format($total, 2) . '</td>
            </tr>
            <tr>
                <td><strong>Discount:</strong></td>
                <td>' . htmlspecialchars($invoiceData['currency']) . ' 0.00</td>
            </tr>
            <tr>
                <td><strong>Tax:</strong></td>
                <td>' . htmlspecialchars($invoiceData['currency']) . ' 0.00</td>
            </tr>
            <tr>
                <td><strong>Paid:</strong></td>
                <td>' . htmlspecialchars($invoiceData['currency']) . ' 0.00</td>
            </tr>
        </table>
    </div>
</div>

<div class="total-section">
    TOTAL: ' . htmlspecialchars($invoiceData['currency']) . ' ' . number_format($total, 2) . '
</div>
';

// Generate PDF
$mpdf->WriteHTML($html);
$mpdf->Output('invoice_' . $invoiceData['invoice_number'] . '.pdf', 'D'); // 'I' = Preview

?>
