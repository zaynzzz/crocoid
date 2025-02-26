<?php
include 'function.php'; // Pastikan file ini ada

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $channel = isset($_POST['channel']) ? trim($_POST['channel']) : '';
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
    $phoneNumber = isset($_POST['phoneNumber']) ? trim($_POST['phoneNumber']) : '';
    $projectName = isset($_POST['projectName']) ? trim($_POST['projectName']) : '';

    // Validasi input
    if (empty($channel) || empty($amount) || empty($phoneNumber) || empty($projectName)) {
        echo "<p style='color: red;'>Error: Data tidak lengkap. Pastikan semua field diisi.</p>";
        exit;
    }

    if ($amount <= 0) {
        echo "<p style='color: red;'>Error: Nominal harus lebih besar dari 0.</p>";
        exit;
    }

    // Panggil fungsi pembayaran e-wallet
    $response = createEwalletPayment($channel, $amount, $phoneNumber, $projectName);

    // Tampilkan respons
    handleEwalletResponse($response);
} else {
    echo "<p style='color: red;'>Error: Metode request tidak valid.</p>";
}
?>