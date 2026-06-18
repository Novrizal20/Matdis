<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Kriptografi Dasar</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        select { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 15px; background: white; margin-bottom: 5px; }
    </style>
</head>
<body>

<div class="container">
    <h2>🔐 Aplikasi Pengamanan Data Sederhana</h2>
    <p>Sistem kriptografi modular yang memenuhi kriteria spesifikasi tugas proses.</p>
    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

    <form method="POST" action="proses.php">
        <div class="form-group">
            <label for="pesan">1. Pesan Asli (Plaintext):</label>
            <input type="text" id="pesan" name="pesan" required placeholder="Contoh: TUGAS24" autocomplete="off">
        </div>

        <div class="form-group">
            <label for="kunci">2. Kunci Pergeseran ($k$):</label>
            <input type="number" id="kunci" name="kunci" value="3" required>
        </div>

        <div class="form-group">
            <label for="metode">3. Metode Pengamanan yang Dipilih:</label>
            <select id="metode" name="metode">
                <option value="CAESAR">Caesar Cipher (Sandi Modular Sederhana)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="karakter">4. Karakter yang Diizinkan (Himpunan):</label>
            <select id="karakter" name="karakter">
                <option value="HURUF">Hanya Huruf Kapital (A-Z)</option>
                <option value="ALFANUMERIK">Alfanumerik (A-Z + Angka 0-9)</option>
            </select>
        </div>

        <button type="submit">Jalankan Proses Kriptografi</button>
    </form>
</div>

</body>
</html>