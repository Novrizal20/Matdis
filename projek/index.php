<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kriptografi Modular - Kelompok 4</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body{
            min-height: 100vh;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.97);
            max-width: 550px;
            width: 100%;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        h2 {
            color: #4f46e5;
            font-size: 28px;
            margin-bottom: 5px;
            text-align: center;
        }

        .subtitle {
            color: #718096;
            font-size: 14px;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 14px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            background: #f8fafc;
            outline: none;
            transition: 0.3s;
        }

        input:focus, select:focus {
            border-color: #4f46e5;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .note {
            font-size: 12px;
            color: #ef4444;
            font-style: italic;
            margin-top: 6px;
            display: block;
        }

        button {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        button:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Kelompok 4</h2>
    <p class="subtitle">Cipher Modulo dengan Analisis Frekuensi</p>
    
    <form action="proses.php" method="POST">
        <div class="form-group">
            <label for="pesan">1. Masukkan Pesan Asli (Plaintext):</label>
            <input type="text" id="pesan" name="pesan" required placeholder="Contoh: KAMPUS24" style="text-transform: uppercase;" autocomplete="off">
        </div>

        <div class="form-group">
            <label for="kunci_a">2. Kunci Pengali Modular (a):</label>
            <select id="kunci_a" name="kunci_a">
                <option value="5">5 (Sangat Direkomendasikan untuk Alfanumerik)</option>
                <option value="7">7</option>
                <option value="11">11</option>
                <option value="17">17</option>
                <option value="3">3 (Hanya untuk opsi Hanya Huruf Kapital)</option>
            </select>
            <span class="note">*Pilih angka yang relatif prima (FPB = 1) dengan ukuran semesta karakter</span>
        </div>

        <div class="form-group">
            <label for="kunci_b">3. Kunci Pergeseran Modular (b):</label>
            <input type="number" id="kunci_b" name="kunci_b" value="3" required min="1">
        </div>

        <div class="form-group">
            <label for="karakter">4. Batasan Ruang Karakter (Himpunan Semesta):</label>
            <select id="karakter" name="karakter">
                <option value="ALFANUMERIK">Alfanumerik / Huruf + Angka (n=36)</option>
                <option value="HURUF">Hanya Huruf Kapital A-Z (n=26)</option>
            </select>
        </div>

        <input type="hidden" name="metode" value="AFFINE">
        <button type="submit">Proses & Analisis Frekuensi</button>
    </form>
</div>

</body>
</html>