<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'CipherEngine.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

// 1. Menangkap Parameter Input
$pesanAsli = $_POST['pesan'];
$kunci = intval($_POST['kunci']);
$metodeTerpilih = $_POST['metode'];
$karakterDiizinkan = $_POST['karakter'];

// 2. LOGIKA VALIDASI KETAT (Pendekatan 2)
$inputValid = true;
$pesanError = "";

if ($karakterDiizinkan === "HURUF") {
    // Regex: Hanya menerima huruf kapital A-Z dan spasi. Huruf kecil/angka akan ditolak.
    if (!preg_match('/^[A-Z\s]+$/', $pesanAsli)) {
        $inputValid = false;
        $pesanError = "Input tidak valid! Kriteria yang dipilih adalah <strong>'Hanya Huruf Kapital (A-Z)'</strong>, tetapi pesan kamu mengandung huruf kecil, angka, atau simbol.";
    }
} else if ($karakterDiizinkan === "ALFANUMERIK") {
    // Regex: Hanya menerima huruf kapital A-Z, angka 0-9, dan spasi. Huruf kecil akan ditolak.
    if (!preg_match('/^[A-Z0-9\s]+$/', $pesanAsli)) {
        $inputValid = false;
        $pesanError = "Input tidak valid! Kriteria yang dipilih adalah <strong>'Alfanumerik (A-Z + Angka)'</strong>, tetapi pesan kamu mengandung huruf kecil atau simbol ilegal.";
    }
}

// Jika valid, jalankan Engine Kriptografi
if ($inputValid) {
    $engine = new CipherEngine();
    $engine->setHimpunanKarakter($karakterDiizinkan);

    // Eksekusi Enkripsi & Dekripsi
    $cipherText = $engine->enkripsi($pesanAsli, $kunci);
    $plainTextKembali = $engine->dekripsi($cipherText, $kunci);
    $tabelRelasi = $engine->dapatkanTabelRelasi($kunci);
    $jumlahKunci = $engine->getJumlahKunci();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Output Sistem Kriptografi Dasar</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .output-section { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .output-title { font-size: 16px; font-weight: bold; color: #2d3748; margin-top: 0; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 2px solid #edf2f7; }
        .badge-method { background: #3182ce; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .flowchart-box { background: #1a202c; color: #a0aec0; padding: 15px; font-family: 'Courier New', Courier, monospace; font-size: 13px; border-radius: 6px; line-height: 1.5; overflow-x: auto; }
        .flowchart-box .highlight { color: #f6e05e; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px; }
        .info-table th, .info-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .info-table th { background-color: #f8f9fa; }
        .error-box { background-color: #fff5f5; border-left: 5px solid #e53e3e; color: #c53030; padding: 20px; border-radius: 6px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container">
    <a href="index.php" class="btn-back">⬅️ Kembali (Input Parameter)</a>
    
    <h2 style="margin-bottom: 5px;">🖥️ Dashboard Output Sistem</h2>
    <p style="color: #718096; margin-top: 0; margin-bottom: 25px;">Status Logika Validasi: 
        <span class="badge-method" style="background-color: <?php echo $inputValid ? '#38a169' : '#e53e3e'; ?>;">
            <?php echo $inputValid ? 'VALID (PROSES DILANJUTKAN)' : 'REJECTED (PROSES DIHENTIKAN)'; ?>
        </span>
    </p>

    <?php if (!$inputValid): ?>
        <div class="error-box">
            <h3 style="margin-top: 0; color: #9b2c2c; border: none; padding: 0;">🛑 Gagal Memproses Data!</h3>
            <p><?php echo $pesanError; ?></p>
            <p style="font-size: 13px; margin-bottom: 0; font-style: italic;">Sistem menolak memproses karena karakter input berada di luar batasan Domain Himpunan Semesta yang dipilih.</p>
        </div>

    <?php else: ?>
        <table class="info-table">
            <tr>
                <th>Parameter Input</th>
                <th>Nilai / Konfigurasi</th>
            </tr>
            <tr>
                <td><strong>1. Pesan Asli</strong></td>
                <td><code><?php echo htmlspecialchars($pesanAsli); ?></code></td>
            </tr>
            <tr>
                <td><strong>2. Kunci Enkripsi</strong></td>
                <td><code><?php echo $kunci; ?></code></td>
            </tr>
            <tr>
                <td><strong>3. Metode Pengamanan</strong></td>
                <td><code>Caesar Cipher (Sandi Modular Sederhana)</code></td>
            </tr>
            <tr>
                <td><strong>4. Karakter Diizinkan</strong></td>
                <td><code><?php echo $karakterDiizinkan === "ALFANUMERIK" ? "Alfanumerik (n=36)" : "Hanya Huruf Kapital (n=26)"; ?></code></td>
            </tr>
        </table>

        <div class="output-section">
            <div class="output-title">1 & 2. Hasil Eksekusi Pesan (Ciphertext & Dekripsi)</div>
            <div class="result-box" style="background-color: #fffaf0; border-left-color: #dd6b20; margin-top: 0;">
                <p style="margin: 5px 0;"><strong>[INPUT] Pesan Asli (Plaintext):</strong> <code style="font-size: 15px;"><?php echo htmlspecialchars($pesanAsli); ?></code></p>
                <hr style="border: 0; border-top: 1px solid #feebc8; margin: 10px 0;">
                <p style="margin: 5px 0;"><strong>[OUTPUT 1] Pesan Terenkripsi (Ciphertext):</strong> <code style="color: #dd6b20; font-weight: bold; font-size: 16px;"><?php echo htmlspecialchars($cipherText); ?></code></p>
                <p style="margin: 5px 0;"><strong>[OUTPUT 2] Pesan Hasil Dekripsi:</strong> <code style="color: #38a169; font-weight: bold; font-size: 16px;"><?php echo htmlspecialchars($plainTextKembali); ?></code></p>
            </div>
        </div>

        <div class="output-section">
            <div class="output-title">3. Tabel Pemetaan Huruf (Relasi Fungsi Bergeser)</div>
            <p style="font-size: 13px; color: #718096; margin-top: -5px; margin-bottom: 10px;">Memetakan elemen himpunan asli ke elemen terenkripsi dengan pergeseran kunci $k = <?php echo $kunci; ?>$ :</p>
            <div class="table-relasi">
Asli  : <?php foreach(array_keys($tabelRelasi) as $asli) echo $asli . " "; ?><br>
Cipher: <?php foreach(array_values($tabelRelasi) as $cipher) echo $cipher . " "; ?>
            </div>
        </div>

        <div class="output-section">
            <div class="output-title">4. Diagram Alur / Proses Logika Sistem</div>
            <div class="flowchart-box">
[START: Ambil Teks Input] <br>
&nbsp;&nbsp;&nbsp;│<br>
&nbsp;&nbsp;&nbsp;▼<br>
[Validasi Aturan Himpunan Semesta (Regex)] ➔ <span class="highlight">STATUS: PASSED</span><br>
&nbsp;&nbsp;&nbsp;│<br>
&nbsp;&nbsp;&nbsp;▼<br>
[Definisikan Semesta Karakter ($n = <?php echo ($jumlahKunci + 1); ?>$)]<br>
&nbsp;&nbsp;&nbsp;│<br>
&nbsp;&nbsp;&nbsp;├──► <span class="highlight">PROSES ENKRIPSI</span><br>
&nbsp;&nbsp;&nbsp;│&nbsp;&nbsp;&nbsp;&nbsp;Loop Tiap Huruf ➔ Indeks Posisi ($p$)<br>
&nbsp;&nbsp;&nbsp;│&nbsp;&nbsp;&nbsp;&nbsp;Hitung Modulo   ➔ <span class="highlight">$C = (p + <?php echo $kunci; ?>) \bmod <?php echo ($jumlahKunci + 1); ?>$</span><br>
&nbsp;&nbsp;&nbsp;│&nbsp;&nbsp;&nbsp;&nbsp;Hasil Keluaran  ➔ <strong><?php echo htmlspecialchars($cipherText); ?></strong><br>
&nbsp;&nbsp;&nbsp;│<br>
&nbsp;&nbsp;&nbsp;└──► <span class="highlight">PROSES DEKRIPSI (INVERS)</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Loop Tiap Huruf ➔ Indeks Posisi ($c$)<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hitung Modulo   ➔ <span class="highlight">$P = (c - <?php echo $kunci; ?>) \bmod <?php echo ($jumlahKunci + 1); ?>$</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hasil Keluaran  ➔ <strong><?php echo htmlspecialchars($plainTextKembali); ?></strong><br>
&nbsp;&nbsp;&nbsp;│<br>
&nbsp;&nbsp;&nbsp;▼<br>
[END: Selesai Tampilkan Hasil]
        </div>
    </div>
    <?php endif; ?>

</div>

</body>
</html>