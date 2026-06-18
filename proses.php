<?php
// Mengaktifkan pelacak error penuh agar jika ada kendala langsung kelihatan di browser
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'CipherEngine.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$pesanAsli = $_POST['pesan'];
$kunciA = intval($_POST['kunci_a']);
$kunciB = intval($_POST['kunci_b']);
$metodeTerpilih = $_POST['metode'];
$karakterDiizinkan = $_POST['karakter'];

// --- LOGIKA VALIDASI KETAT (PENDEKATAN 2) ---
$inputValid = true;
$pesanError = "";

if ($karakterDiizinkan === "HURUF") {
    if (!preg_match('/^[A-Z\s]+$/', $pesanAsli)) {
        $inputValid = false;
        $pesanError = "Input ditolak! Kriteria semesta adalah <strong>'Hanya Huruf Kapital (A-Z)'</strong>. Teks input kamu kedapatan mengandung huruf kecil, angka, atau simbol.";
    }
} else if ($karakterDiizinkan === "ALFANUMERIK") {
    if (!preg_match('/^[A-Z0-9\s]+$/', $pesanAsli)) {
        $inputValid = false;
        $pesanError = "Input ditolak! Kriteria semesta adalah <strong>'Alfanumerik'</strong>. Teks input kamu kedapatan mengandung huruf kecil atau simbol ilegal.";
    }
}

if ($inputValid) {
    $engine = new CipherEngine();
    $engine->setHimpunanKarakter($karakterDiizinkan);

    // Proses Eksekusi Inti Aritmatika Modular Affine
    $cipherText = $engine->enkripsi($pesanAsli, $kunciA, $kunciB);
    $plainTextKembali = $engine->dekripsi($cipherText, $kunciA, $kunciB);
    $tabelRelasi = $engine->dapatkanTabelRelasi($kunciA, $kunciB);
    $jumlahKunci = $engine->getJumlahKunci();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Output Sistem Kriptografi Modular</title>
    <link rel="stylesheet" href="css/style.css">
   <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
min-height:100vh;
background:linear-gradient(135deg,#4f46e5,#7c3aed);
padding:30px;
}

.container{
max-width:1100px;
margin:auto;
background:rgba(255,255,255,.97);
border-radius:24px;
padding:35px;
box-shadow:0 20px 40px rgba(0,0,0,.15);
}

.btn-back{
display:inline-block;
padding:12px 20px;
background:#f3f4f6;
color:#374151;
text-decoration:none;
border-radius:12px;
font-weight:600;
margin-bottom:20px;
transition:.3s;
}

.btn-back:hover{
background:#e5e7eb;
}

h2{
color:#4f46e5;
font-size:32px;
margin-bottom:10px;
}

.badge-method{
padding:8px 14px;
border-radius:999px;
font-size:12px;
font-weight:600;
color:white;
}

.info-table{
width:100%;
border-collapse:collapse;
margin-bottom:25px;
overflow:hidden;
border-radius:15px;
}

.info-table th{
background:#4f46e5;
color:white;
padding:15px;
}

.info-table td{
padding:15px;
border:1px solid #e5e7eb;
}

.info-table tr:nth-child(even){
background:#f8fafc;
}

.output-section{
background:white;
padding:25px;
border-radius:18px;
margin-bottom:25px;
box-shadow:0 5px 20px rgba(0,0,0,.05);
}

.output-title{
font-size:18px;
font-weight:700;
color:#4f46e5;
margin-bottom:15px;
}

.result-box{
background:#f8fafc;
padding:20px;
border-left:6px solid #4f46e5;
border-radius:12px;
}

.result-box code{
display:block;
margin-top:8px;
padding:12px;
background:white;
border-radius:10px;
font-size:18px;
font-weight:600;
}

.table-relasi{
background:linear-gradient(135deg,#4f46e5,#7c3aed);
color:white;
padding:20px;
border-radius:15px;
font-family:Consolas,monospace;
overflow-x:auto;
line-height:2;
}

.flowchart-box{
background:#111827;
color:#e5e7eb;
padding:25px;
border-radius:15px;
font-family:Consolas,monospace;
line-height:1.8;
overflow-x:auto;
}

.highlight{
color:#fbbf24;
font-weight:bold;
}

.error-box{
background:#fef2f2;
border-left:6px solid #ef4444;
padding:20px;
border-radius:12px;
color:#991b1b;
}

.stats{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:15px;
margin:25px 0;
}

.stat-card{
background:linear-gradient(135deg,#4f46e5,#7c3aed);
color:white;
padding:20px;
border-radius:16px;
text-align:center;
}

.stat-card h3{
font-size:30px;
margin-top:10px;
}
</style>
</head>
<body>

<div class="container">
    <a href="index.php" class="btn-back">⬅️ Kembali (Input Parameter)</a>
    
    <h2>🖥️ Dashboard Output Sistem</h2>
    <p style="color: #718096; margin-top: 0; margin-bottom: 25px;">Status Logika Validasi: 
        <span class="badge-method" style="background-color: <?php echo $inputValid ? '#38a169' : '#e53e3e'; ?>;">
            <?php echo $inputValid ? 'VALID (PROSES DILANJUTKAN)' : 'REJECTED (PROSES DIHENTIKAN)'; ?>
        </span>
    </p>

    <?php if (!$inputValid): ?>
        <div class="error-box">
            <h3 style="margin-top: 0; color: #9b2c2c; border: none; padding: 0;">🛑 Gagal Memproses Data!</h3>
            <p><?php echo $pesanError; ?></p>
            <p style="font-size: 13px; margin-bottom: 0; font-style: italic;">Sistem menolak memproses karena karakter input berada di luar batasan Domain Himpunan Semesta.</p>
        </div>

    <?php else: ?>
        <table class="info-table">
            <tr>
                <th>Parameter Input</th>
                <th>Nilai / Konfigurasi Modular</th>
            </tr>
            <tr>
                <td><strong>Pesan Asli</strong></td>
                <td><code><?php echo htmlspecialchars($pesanAsli); ?></code></td>
            </tr>
            <tr>
                <td><strong>Kunci Kombinasi (a, b)</strong></td>
                <td>Pengali (a): <code><?php echo $kunciA; ?></code>, Pergeseran (b): <code><?php echo $kunciB; ?></code></td>
            </tr>
            <tr>
                <td><strong>Karakter Diizinkan</strong></td>
                <td><code><?php echo $karakterDiizinkan === "ALFANUMERIK" ? "Alfanumerik (n=36)" : "Hanya Huruf Kapital (n=26)"; ?></code></td>
            </tr>
        </table>

        <div class="output-section">
            <div class="output-title">1 & 2. Hasil Eksekusi Pesan (Ciphertext & Dekripsi)</div>
            <div class="result-box" style="background-color: #fffaf0; border-left-color: #2b6cb0; margin-top: 0;">
                <p style="margin: 5px 0;"><strong>[OUTPUT 1] Pesan Terenkripsi (Ciphertext):</strong> <code style="color: #2b6cb0; font-weight: bold; font-size: 16px;"><?php echo htmlspecialchars($cipherText); ?></code></p>
                <p style="margin: 5px 0;"><strong>[OUTPUT 2] Pesan Hasil Dekripsi:</strong> <code style="color: #38a169; font-weight: bold; font-size: 16px;"><?php echo htmlspecialchars($plainTextKembali); ?></code></p>
            </div>
        </div>

        <div class="output-section">
            <div class="output-title">3. Tabel Pemetaan Huruf (Relasi Fungsi Kongruensi)</div>
            <p style="font-size: 13px; color: #718096; margin-top: -5px; margin-bottom: 10px;">Pemetaan satu-ke-satu menggunakan fungsi kongruensi modular:</p>
            <div class="table-relasi" style="background-color: #2b6cb0;">
                Asli  : <?php foreach(array_keys($tabelRelasi) as $asli) echo $asli . " "; ?><br>
                Cipher: <?php foreach(array_values($tabelRelasi) as $cipher) echo $cipher . " "; ?>
            </div>
        </div>

        <div class="output-section">
            <div class="output-title">4. Diagram Alur / Proses Aritmatika Modular</div>
            <div class="flowchart-box">
[START: Ambil Teks Input Kunci a & b] <br>
&nbsp;&nbsp;&nbsp;│<br>
&nbsp;&nbsp;&nbsp;▼<br>
[Validasi Aturan Himpunan Semesta (Regex)] ➔ STATUS: PASSED<br>
&nbsp;&nbsp;&nbsp;│<br>
&nbsp;&nbsp;&nbsp;▼<br>
[Definisikan Ukuran Semesta n = <?php echo ($jumlahKunci + 1); ?>]<br>
&nbsp;&nbsp;&nbsp;│<br>
&nbsp;&nbsp;&nbsp;├──► <span class="highlight">PROSES ENKRIPSI MODULAR</span><br>
&nbsp;&nbsp;&nbsp;│&nbsp;&nbsp;&nbsp;&nbsp;Hitung Fungsi Posisi ➔ <span class="highlight">C = (<?php echo $kunciA; ?> × p + <?php echo $kunciB; ?>) mod <?php echo ($jumlahKunci + 1); ?></span><br>
&nbsp;&nbsp;&nbsp;│&nbsp;&nbsp;&nbsp;&nbsp;Hasil Keluaran ➔ <strong><?php echo htmlspecialchars($cipherText); ?></strong><br>
&nbsp;&nbsp;&nbsp;│<br>
&nbsp;&nbsp;&nbsp;└──► <span class="highlight">PROSES DEKRIPSI INVERS</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cari Modulo Invers (a⁻¹)<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hitung Fungsi Balikan ➔ <span class="highlight">P = a⁻¹ × (c - <?php echo $kunciB; ?>) mod <?php echo ($jumlahKunci + 1); ?></span><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hasil Keluaran ➔ <strong><?php echo htmlspecialchars($plainTextKembali); ?></strong><br>
&nbsp;&nbsp;&nbsp;│<br>
&nbsp;&nbsp;&nbsp;▼<br>
[END: Selesai]
            </div>
        </div>
    <?php endif; ?>

</div>

</body>
</html>