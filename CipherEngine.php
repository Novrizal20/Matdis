<?php
class CipherEngine {
    private $alfabet;
    private $n;

    public function setHimpunanKarakter($pilihanKarakter) {
        if ($pilihanKarakter === "ALFANUMERIK") {
            $this->alfabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        } else {
            $this->alfabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        }
        $this->n = strlen($this->alfabet);
    }

    public function getJumlahKunci() {
        return $this->n - 1;
    }

    // Fungsi mencari Modulo Invers (Mencari nilai a^-1 untuk dekripsi)
    private function cariModInvers($a, $m) {
        $a = $a % $m;
        for ($x = 1; $x < $m; $x++) {
            if (($a * $x) % $m == 1) {
                return $x;
            }
        }
        return 1;
    }

    // Proses 4: Enkripsi Modular Affine -> C = (a * p + b) mod n
    public function enkripsi($pesan, $kunciA, $kunciB) {
        $pesan = trim($pesan);
        $hasil = "";
        
        for ($i = 0; $i < strlen($pesan); $i++) {
            $karakter = $pesan[$i];
            $p = strpos($this->alfabet, $karakter);

            if ($p !== false) {
                // Rumus Affine Modular
                $c = ($kunciA * $p + $kunciB) % $this->n;
                if ($c < 0) $c += $this->n;
                $hasil .= $this->alfabet[$c];
            } else {
                $hasil .= $karakter; 
            }
        }
        return $hasil;
    }

    // Proses 5: Dekripsi Modular Affine -> P = a^-1 * (c - b) mod n
    public function dekripsi($cipher, $kunciA, $kunciB) {
        $cipher = trim($cipher);
        $hasil = "";
        $aInvers = $this->cariModInvers($kunciA, $this->n);
        
        for ($i = 0; $i < strlen($cipher); $i++) {
            $karakter = $cipher[$i];
            $c = strpos($this->alfabet, $karakter);

            if ($c !== false) {
                // Rumus Invers Affine Modular
                $p = ($aInvers * ($c - $kunciB)) % $this->n;
                while ($p < 0) $p += $this->n;
                $hasil .= $this->alfabet[$p];
            } else {
                $hasil .= $karakter;
            }
        }
        return $hasil;
    }

    public function dapatkanTabelRelasi($kunciA, $kunciB) {
        $pemetaan = [];
        for ($i = 0; $i < $this->n; $i++) {
            $c = ($kunciA * $i + $kunciB) % $this->n;
            if ($c < 0) $c += $this->n;
            $pemetaan[$this->alfabet[$i]] = $this->alfabet[$c];
        }
        return $pemetaan;
    }
}