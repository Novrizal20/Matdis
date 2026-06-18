<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Kriptografi Sandi Modular</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        select { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 15px; background: white; margin-bottom: 5px; }
        .note { font-size: 12px; color: #e53e3e; font-style: italic; display: block; margin-top: 2px; }
    </style>
</head>
<body>

<div class="container">
    <h2>🔐 Aplikasi Pengamanan Data Sederhana</h2>
    <p>Sistem kriptografi menggunakan metode <strong>Sandi Modular Affine (Affine Cipher)</strong>.</p>
    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

    <form method="POST" action="proses.php">
        <div class="form-group">
            <label for="pesan">1. Pesan Asli (Plaintext):</label>
            <input type="text" id="pesan" name="pesan" required placeholder="Contoh: KAMPUS24" autocomplete="off">
        </div>

        <div class="form-group">
            <label for="kunci_a">2a. Kunci Pengali Modular ($a$):</label>
            <select id="kunci_a" name="kunci_a">
                <option value="3">3</option>
                <option value="5">5</option>
                <option value="7">7</option>
                <option value="11">11</option>
            </select>
            <span class="note">*Wajib angka ganjil yang relatif prima dengan jumlah karakter</span>
        </div>

        <div class="form-group">
            <label for="kunci_b">2b. Kunci Pergeseran Modular ($b$):</label>
            <input type="number" id="kunci_b" name="kunci_b" value="3" required>
        </div>

        <div class="form-group">
            <label for="metode">3. Metode Pengamanan:</label>
            <select id="metode" name="metode">
                <option value="AFFINE">Sandi Modular Sederhana Tingkat Lanjut (Affine)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="karakter">4. Karakter yang Diizinkan (Himpunan Semesta):</label>
            <select id="karakter" name="karakter">
                <option value="HURUF">Hanya Huruf Kapital (A-Z)</option>
                <option value="ALFANUMERIK">Alfanumerik (A-Z + Angka 0-9)</option>
            </select>
        </div>

        <button type="submit">Jalankan Proses Sandi Modular</button>
    </form>
</div>

</body>
</html>