<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Aplikasi Kriptografi Sandi Modular</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    min-height:100vh;
    background:linear-gradient(135deg,#4f46e5,#7c3aed);
    display:flex;
    justify-content:center;
    align-items:center;
    padding:30px;
}

.container{
    width:100%;
    max-width:800px;
    background:rgba(255,255,255,0.95);
    backdrop-filter:blur(12px);
    border-radius:24px;
    padding:35px;
    box-shadow:0 20px 40px rgba(0,0,0,0.15);
}

.header{
    text-align:center;
    margin-bottom:25px;
}

.header h2{
    color:#4f46e5;
    font-size:32px;
    margin-bottom:10px;
}

.header p{
    color:#6b7280;
    line-height:1.6;
}

.form-group{
    margin-bottom:20px;
}

label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
    color:#374151;
}

input,
select{
    width:100%;
    padding:14px;
    border:2px solid #e5e7eb;
    border-radius:12px;
    font-size:15px;
    transition:.3s;
}

input:focus,
select:focus{
    outline:none;
    border-color:#4f46e5;
    box-shadow:0 0 0 4px rgba(79,70,229,.15);
}

.note{
    display:block;
    margin-top:6px;
    font-size:12px;
    color:#ef4444;
}

button{
    width:100%;
    border:none;
    padding:16px;
    border-radius:12px;
    background:linear-gradient(135deg,#4f46e5,#7c3aed);
    color:white;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    transform:translateY(-3px);
    box-shadow:0 12px 24px rgba(79,70,229,.3);
}

hr{
    border:none;
    border-top:1px solid #e5e7eb;
    margin:25px 0;
}

.info-box{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(150px,1fr));
    gap:15px;
    margin-bottom:25px;
}

.card{
    background:#f8fafc;
    border-radius:14px;
    padding:15px;
    text-align:center;
}

.card h4{
    color:#4f46e5;
    margin-bottom:5px;
}

.card p{
    font-size:13px;
    color:#64748b;
}

@media(max-width:768px){
    .container{
        padding:25px;
    }

    .header h2{
        font-size:24px;
    }
}
</style>
</head>

<body>

<div class="container">

    <div class="header">
        <h2>🔐 Modular Cipher Security</h2>
        <p>
            Sistem Pengamanan Data Menggunakan Metode
            <strong>Affine Cipher (Sandi Modular)</strong>
        </p>
    </div>

    <div class="info-box">
        <div class="card">
            <h4>🔒 Aman</h4>
            <p>Enkripsi menggunakan Affine Cipher</p>
        </div>

        <div class="card">
            <h4>⚡ Cepat</h4>
            <p>Proses enkripsi instan</p>
        </div>

        <div class="card">
            <h4>📚 Edukatif</h4>
            <p>Penerapan matematika diskrit</p>
        </div>
    </div>

    <hr>

    <form method="POST" action="proses.php">

        <div class="form-group">
            <label for="pesan">
                1. Pesan Asli (Plaintext)
            </label>
            <input
                type="text"
                id="pesan"
                name="pesan"
                required
                autocomplete="off"
                placeholder="Contoh: KAMPUS24">
        </div>

        <div class="form-group">
            <label for="kunci_a">
                2a. Kunci Pengali Modular (a)
            </label>

            <select id="kunci_a" name="kunci_a">
                <option value="3">3</option>
                <option value="5">5</option>
                <option value="7">7</option>
                <option value="11">11</option>
            </select>

            <span class="note">
                *Wajib relatif prima dengan jumlah karakter himpunan
            </span>
        </div>

        <div class="form-group">
            <label for="kunci_b">
                2b. Kunci Pergeseran Modular (b)
            </label>

            <input
                type="number"
                id="kunci_b"
                name="kunci_b"
                value="3"
                required>
        </div>

        <div class="form-group">
            <label for="metode">
                3. Metode Pengamanan
            </label>

            <select id="metode" name="metode">
                <option value="AFFINE">
                    Sandi Modular Tingkat Lanjut (Affine Cipher)
                </option>
            </select>
        </div>

        <div class="form-group">
            <label for="karakter">
                4. Karakter yang Diizinkan
            </label>

            <select id="karakter" name="karakter">
                <option value="HURUF">
                    Hanya Huruf Kapital (A-Z)
                </option>

                <option value="ALFANUMERIK">
                    Alfanumerik (A-Z + 0-9)
                </option>
            </select>
        </div>

        <button type="submit">
            🚀 Jalankan Proses Sandi Modular
        </button>

    </form>

</div>

</body>
</html>