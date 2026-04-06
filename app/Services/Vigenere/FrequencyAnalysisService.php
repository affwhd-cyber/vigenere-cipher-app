<?php

namespace App\Services\Vigenere;

class FrequencyAnalysisService
{
    // Frekuensi huruf bahasa Inggris (dalam persen, sebagai bobot)
    private $englishFreq = [
        'E' => 12.7, 'T' => 9.1, 'A' => 8.2, 'O' => 7.5, 'I' => 7.0,
        'N' => 6.7, 'S' => 6.3, 'H' => 6.1, 'R' => 6.0, 'D' => 4.3,
        'L' => 4.0, 'C' => 2.8, 'U' => 2.8, 'M' => 2.4, 'W' => 2.4,
        'F' => 2.2, 'G' => 2.0, 'Y' => 2.0, 'P' => 1.9, 'B' => 1.5,
        'V' => 1.0, 'K' => 0.8, 'J' => 0.2, 'X' => 0.2, 'Q' => 0.1, 'Z' => 0.1
    ];

    /**
     * Menganalisis ciphertext berdasarkan panjang kunci yang diketahui
     * @param string $ciphertext
     * @param int $keyLength
     * @return array ['key' => string, 'plaintext' => string, 'details' => array]
     */
    public function analyze(string $ciphertext, int $keyLength): array
    {
        // Bersihkan hanya huruf A-Z
        $clean = preg_replace('/[^A-Z]/', '', strtoupper($ciphertext));
        $len = strlen($clean);
        if ($len == 0) return ['error' => 'Ciphertext kosong.'];
        if ($keyLength < 1) return ['error' => 'Panjang kunci harus positif.'];

        // Bagi ciphertext ke dalam keyLength kelompok
        $groups = [];
        for ($i = 0; $i < $keyLength; $i++) {
            $groups[$i] = '';
            for ($j = $i; $j < $len; $j += $keyLength) {
                $groups[$i] .= $clean[$j];
            }
        }

        // Untuk setiap kelompok, cari pergeseran terbaik (0-25) berdasarkan frekuensi
        $keyShifts = [];
        $details = [];
        foreach ($groups as $idx => $group) {
            $bestShift = $this->findBestShift($group);
            $keyShifts[$idx] = $bestShift;
            $details['group_' . ($idx+1)] = [
                'text' => $group,
                'best_shift' => $bestShift,
                'best_shift_letter' => chr($bestShift + ord('A')),
                'decrypted' => $this->shiftString($group, -$bestShift)
            ];
        }

        // Kunci dalam huruf
        $key = '';
        foreach ($keyShifts as $shift) {
            $key .= chr($shift + ord('A'));
        }

        // Dekripsi seluruh ciphertext dengan kunci yang ditemukan
        $coreService = new CoreService();
        $decryptResult = $coreService->decrypt($clean, $key);
        $plaintext = $decryptResult['result'];

        return [
            'key' => $key,
            'plaintext' => $plaintext,
            'key_length' => $keyLength,
            'details' => $details,
            'steps' => $decryptResult['steps']
        ];
    }

    /**
     * Mencari pergeseran terbaik untuk sebuah string dengan membandingkan distribusi frekuensi
     */
    private function findBestShift(string $text): int
    {
        $len = strlen($text);
        if ($len == 0) return 0;

        // Hitung frekuensi huruf dalam teks
        $freq = array_fill(0, 26, 0);
        for ($i = 0; $i < $len; $i++) {
            $idx = ord($text[$i]) - ord('A');
            $freq[$idx]++;
        }
        // Normalisasi ke persen
        $freqPercent = array_map(function($count) use ($len) {
            return ($count / $len) * 100;
        }, $freq);

        // Untuk setiap pergeseran 0-25, hitung similarity (dot product) dengan frekuensi English
        $bestShift = 0;
        $bestScore = -INF;
        for ($shift = 0; $shift < 26; $shift++) {
            $score = 0;
            // Bandingkan frekuensi teks yang digeser dengan frekuensi English
            // English freq index 0 untuk A, 1 untuk B, ... setelah digeser, huruf asli dengan shift s akan cocok ke huruf English yang berbeda
            // Rumus: huruf asli (idx) -> setelah dekripsi dengan shift s menjadi huruf (idx - s) mod 26
            // Maka kita bandingkan freqPercent[idx] dengan englishFreq[(idx - s) mod 26]
            foreach ($this->englishFreq as $letter => $engPercent) {
                $engIdx = ord($letter) - ord('A');
                // Untuk shift s, huruf asli idx akan menjadi (idx - s) mod 26. Agar cocok dengan english di posisi engIdx, maka idx - s ≡ engIdx (mod 26) -> idx ≡ engIdx + s (mod 26)
                // Jadi kita jumlahkan: freqPercent[(engIdx + s) % 26] * engPercent
                $idx = ($engIdx + $shift) % 26;
                $score += $freqPercent[$idx] * $engPercent;
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestShift = $shift;
            }
        }
        return $bestShift;
    }

    /**
     * Menggeser string (Caesar shift) dengan nilai tertentu (positif = enkripsi, negatif = dekripsi)
     */
    private function shiftString(string $text, int $shift): string
    {
        $result = '';
        $shift = (($shift % 26) + 26) % 26; // pastikan positif
        for ($i = 0; $i < strlen($text); $i++) {
            $ch = $text[$i];
            if (ctype_alpha($ch)) {
                $base = ord('A');
                $new = (ord($ch) - $base + $shift) % 26;
                $result .= chr($new + $base);
            } else {
                $result .= $ch;
            }
        }
        return $result;
    }
}