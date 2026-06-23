<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'CipherEngine.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

// AMBIL DATA MURNI (Tanpa paksaan strtoupper agar huruf kecil tetap terbaca kecil)
$pesanAsli = $_POST['pesan']; 
$kunciA = intval($_POST['kunci_a']);
$kunciB = intval($_POST['kunci_b']);
$metodeTerpilih = $_POST['metode'];
$karakterDiizinkan = $_POST['karakter'];

// --- LOGIKA VALIDASI SEMESTA KETAT ---
$inputValid = true;
$pesanError = "";

if ($karakterDiizinkan === "HURUF") {
    // Validasi: Wajib Huruf Kapital A-Z dan Spasi saja
    if (!preg_match('/^[A-Z\s]+$/', $pesanAsli)) {
        $inputValid = false;
        $pesanError = "Input ditolak! Kriteria semesta adalah <strong>'Hanya Huruf Kapital (A-Z)'</strong>. Teks input kamu kedapatan mengandung huruf kecil, angka, atau simbol ilegal.";
    }
} else if ($karakterDiizinkan === "ALFANUMERIK") {
    // Validasi: Wajib Huruf Kapital A-Z, Angka 0-9, dan Spasi saja
    if (!preg_match('/^[A-Z0-9\s]+$/', $pesanAsli)) {
        $inputValid = false;
        $pesanError = "Input ditolak! Kriteria semesta adalah <strong>'Alfanumerik'</strong>. Teks input kamu kedapatan mengandung huruf kecil atau simbol ilegal.";
    }
}

if ($inputValid) {
    $engine = new CipherEngine();
    $engine->setHimpunanKarakter($karakterDiizinkan);

    $cipherText = $engine->enkripsi($pesanAsli, $kunciA, $kunciB);
    $plainTextKembali = $engine->dekripsi($cipherText, $kunciA, $kunciB);
    $tabelRelasi = $engine->dapatkanTabelRelasi($kunciA, $kunciB);
    $jumlahKunci = $engine->getJumlahKunci();

    // --- HITUNG ANALISIS FREKUENSI ---
    $analisisFrekuensi = [];
    $totalKarakterTanpaSpasi = 0;
    $totalKarakterMurni = strlen($cipherText);
    
    for ($i = 0; $i < $totalKarakterMurni; $i++) {
        $char = $cipherText[$i];
        if ($char !== " ") {
            if (!isset($analisisFrekuensi[$char])) {
                $analisisFrekuensi[$char] = 0;
            }
            $analisisFrekuensi[$char]++;
            $totalKarakterTanpaSpasi++;
        }
    }
    arsort($analisisFrekuensi);
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

        *{ margin:0; padding:0; box-sizing:border-box; font-family:'Poppins',sans-serif; }
        body{ min-height:100vh; background:linear-gradient(135deg,#4f46e5,#7c3aed); padding:30px; }
        .container{ max-width:1100px; margin:auto; background:rgba(255,255,255,.97); border-radius:24px; padding:35px; box-shadow:0 20px 40px rgba(0,0,0,.15); }
        .btn-back{ display:inline-block; padding:12px 20px; background:#f3f4f6; color:#374151; text-decoration:none; border-radius:12px; font-weight:600; margin-bottom:20px; transition:.3s; }
        .btn-back:hover{ background:#e5e7eb; }
        h2{ color:#4f46e5; font-size:32px; margin-bottom:10px; }
        .badge-method{ padding:8px 14px; border-radius:999px; font-size:12px; font-weight:600; color:white; }
        .info-table{ width:100%; border-collapse:collapse; margin-bottom:25px; overflow:hidden; border-radius:15px; }
        .info-table th{ background:#4f46e5; color:white; padding:15px; }
        .info-table td{ padding:15px; border:1px solid #e5e7eb; }
        .info-table tr:nth-child(even){ background:#f8fafc; }
        .output-section{ background:white; padding:25px; border-radius:18px; margin-bottom:25px; box-shadow:0 5px 20px rgba(0,0,0,.05); }
        .output-title{ font-size:18px; font-weight:700; color:#4f46e5; margin-bottom:15px; }
        .result-box{ background:#f8fafc; padding:20px; border-left:6px solid #4f46e5; border-radius:12px; }
        .result-box code{ display:block; margin-top:8px; padding:12px; background:white; border-radius:10px; font-size:18px; font-weight:600; }
        .table-relasi{ background:linear-gradient(135deg,#4f46e5,#7c3aed); color:white; padding:20px; border-radius:15px; font-family:Consolas,monospace; overflow-x:auto; line-height:2; }
        .flowchart-box{ background:#111827; color:#e5e7eb; padding:25px; border-radius:15px; font-family:Consolas,monospace; line-height:1.8; overflow-x:auto; }
        .highlight{ color:#fbbf24; font-weight:bold; }
        .error-box{ background:#fef2f2; border-left:6px solid #ef4444; padding:20px; border-radius:12px; color:#991b1b; }
        .freq-bar-wrapper { display: flex; align-items: center; gap: 10px; }
        .freq-bar { height: 16px; background: linear-gradient(90deg, #4f46e5, #7c3aed); border-radius: 4px; transition: width 0.5s ease-in-out; }
        .freq-percent { font-size: 13px; font-weight: 600; color: #4b5563; }
    </style>
</head>
<body>

<div class="container">
    <a href="index.php" class="btn-back">⬅️ Kembali (Input Parameter)</a>
    
    <h2>🖥️ Dashboard Output Sistem - Kelompok 4</h2>
    <p style="color: #718096; margin-top: 0; margin-bottom: 25px;">Status Logika Validasi: 
        <span class="badge-method" style="background-color: <?php echo $inputValid ? '#38a169' : '#e53e3e'; ?>;">
            <?php echo $inputValid ? 'VALID (PROSES DILANJUTKAN)' : 'REJECTED (PROSES DIHENTIKAN)'; ?>
        </span>
    </p>

    <?php if (!$inputValid): ?>
        <div class="error-box">
            <h3 style="margin-top: 0; color: #9b2c2c; border: none; padding: 0; margin-bottom: 10px;">🛑 Gagal Memproses Data!</h3>
            <p><?php echo $pesanError; ?></p>
            <div style="background: white; border-radius: 8px; padding: 15px; margin: 15px 0; border: 1px solid #fca5a5;">
                <p style="margin: 0; font-size: 14px; color: #4b5563;">
                    Teks input asli yang kamu masukkan: <strong style="font-size: 16px; color: #ef4444; font-family: monospace;"><?php echo htmlspecialchars($pesanAsli); ?></strong>
                </p>
            </div>
            <p style="font-size: 13px; margin-bottom: 0; font-style: italic;">Sistem menolak memproses kalkulasi modular karena karakter input berada di luar batasan Domain Himpunan Semesta.</p>
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
            <div class="output-title">📊 3. Hasil Analisis Frekuensi Karakter (Kriptanalisis Statistik)</div>
            <p style="font-size: 13px; color: #718096; margin-top: -5px; margin-bottom: 15px;">Persentase distribusi kemunculan huruf pada teks rahasia (*Ciphertext*) untuk mengukur pola statistik acak:</p>
            
            <table class="info-table" style="margin-bottom: 0;">
                <tr style="background-color: #edf2f7;">
                    <th style="width: 15%; background: #7c3aed;">Karakter</th>
                    <th style="width: 25%; background: #7c3aed;">Frekuensi Kemunculan</th>
                    <th style="background: #7c3aed;">Grafik Batang Distribusi Statistik</th>
                </tr>
                <?php 
                if (empty($analisisFrekuensi)) {
                    echo "<tr><td colspan='3' style='text-align:center; color:#718096;'>Tidak ada karakter.</td></tr>";
                } else {
                    foreach ($analisisFrekuensi as $char => $count): 
                        $persen = round(($count / $totalKarakterTanpaSpasi) * 100, 1);
                ?>
                <tr>
                    <td><strong style="font-size: 16px; color: #4f46e5;">'<?php echo $char; ?>'</strong></td>
                    <td><?php echo $count; ?> kali muncul</td>
                    <td>
                        <div class="freq-bar-wrapper">
                            <div class="freq-bar" style="width: <?php echo ($persen * 4); ?>px; max-width: 85%;"></div>
                            <span class="freq-percent"><?php echo $persen; ?>%</span>
                        </div>
                    </td>
                </tr>
                <?php 
                    endforeach; 
                }
                ?>
            </table>
        </div>

        <div class="output-section">
            <div class="output-title">4. Tabel Pemetaan Huruf (Relasi Fungsi Kongruensi)</div>
            <p style="font-size: 13px; color: #718096; margin-top: -5px; margin-bottom: 10px;">Pemetaan satu-ke-satu menggunakan fungsi kongruensi modular:</p>
            <div class="table-relasi">
                Asli  : <?php foreach(array_keys($tabelRelasi) as $asli) echo $asli . " "; ?><br>
                Cipher: <?php foreach(array_values($tabelRelasi) as $cipher) echo $cipher . " "; ?>
            </div>
        </div>

        <div class="output-section">
            <div class="output-title">5. Diagram Alur / Proses Aritmatika Modular + Analisis Statistik</div>
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
&nbsp;&nbsp;&nbsp;│&nbsp;&nbsp;&nbsp;&nbsp;Hasil Keluaran Teks Sandi ➔ <strong><?php echo htmlspecialchars($cipherText); ?></strong><br>
&nbsp;&nbsp;&nbsp;│<br>
&nbsp;&nbsp;&nbsp;├──► <span class="highlight">ANALISIS STATISTIK FREKUENSI</span><br>
&nbsp;&nbsp;&nbsp;│&nbsp;&nbsp;&nbsp;&nbsp;Menghitung sebaran probabilitas karakter pada Ciphertext murni.<br>
&nbsp;&nbsp;&nbsp;│<br>
&nbsp;&nbsp;&nbsp;└──► <span class="highlight">PROSES DEKRIPSI INVERS</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cari Modulo Invers (a⁻¹)<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hitung Fungsi Balikan ➔ <span class="highlight">P = a⁻¹ × (c - <?php echo $kunciB; ?>) mod <?php echo ($jumlahKunci + 1); ?></span><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hasil Keluaran Teks Asli ➔ <strong><?php echo htmlspecialchars($plainTextKembali); ?></strong><br>
&nbsp;&nbsp;&nbsp;│<br>
&nbsp;&nbsp;&nbsp;▼<br>
[END: Selesai]
            </div>
        </div>
    <?php endif; ?>

</div>

</body>
</html>