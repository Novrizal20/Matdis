<?php
class CipherEngine {
    private $alfabet;
    private $n;

    // Proses 1: Tentukan himpunan karakter berdasarkan input user
    public function setHimpunanKarakter($pilihanKarakter) {
        if ($pilihanKarakter === "ALFANUMERIK") {
            $this->alfabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        } else {
            // Hanya berisi huruf kapital A-Z (huruf kecil tidak terdaftar di sini)
            $this->alfabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        }
        $this->n = strlen($this->alfabet);
    }

    public function getJumlahKunci() {
        return $this->n - 1;
    }

    // Proses 4: Enkripsi pesan (Strict: huruf kecil tidak akan diproses)
    public function enkripsi($pesan, $kunci) {
        $pesan = trim($pesan); 
        $hasil = "";
        
        for ($i = 0; $i < strlen($pesan); $i++) {
            $karakter = $pesan[$i];
            $p = strpos($this->alfabet, $karakter);

            // Logic: Hanya diproses jika karakter ada di dalam himpunan aktif
            if ($p !== false) {
                $c = ($p + $kunci) % $this->n;
                if ($c < 0) $c += $this->n;
                $hasil .= $this->alfabet[$c];
            } else {
                // Jika tidak ada di himpunan (seperti huruf kecil), abaikan pergeseran
                $hasil .= $karakter; 
            }
        }
        return $hasil;
    }

    // Proses 5: Dekripsi pesan kembali
    public function dekripsi($cipher, $kunci) {
        $cipher = trim($cipher); 
        $hasil = "";
        
        for ($i = 0; $i < strlen($cipher); $i++) {
            $karakter = $cipher[$i];
            $c = strpos($this->alfabet, $karakter);

            if ($c !== false) {
                $p = ($c - $kunci) % $this->n;
                if ($p < 0) $p += $this->n;
                $hasil .= $this->alfabet[$p];
            } else {
                $hasil .= $karakter;
            }
        }
        return $hasil;
    }

    public function dapatkanTabelRelasi($kunci) {
        $pemetaan = [];
        for ($i = 0; $i < $this->n; $i++) {
            $c = ($i + $kunci) % $this->n;
            if ($c < 0) $c += $this->n;
            $pemetaan[$this->alfabet[$i]] = $this->alfabet[$c];
        }
        return $pemetaan;
    }
}