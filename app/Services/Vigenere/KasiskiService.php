<?php

namespace App\Services\Vigenere;

class KasiskiService
{
    /**
     * Mencari panjang kunci dari ciphertext menggunakan metode Kasiski
     * @param string $ciphertext
     * @return array ['key_lengths' => array, 'details' => array]
     */
    public function analyze(string $ciphertext): array
    {
        // Bersihkan: hanya huruf, jadi uppercase
        $clean = preg_replace('/[^A-Z]/', '', strtoupper($ciphertext));
        $length = strlen($clean);
        
        if ($length < 10) {
            return [
                'key_lengths' => [],
                'details' => ['error' => 'Ciphertext terlalu pendek untuk analisis Kasiski (minimal 10 huruf).']
            ];
        }
        
        // Cari trigram (pola 3 huruf) yang berulang
        $minPatternLength = 3; // bisa 3 atau 4, kita pakai 3
        $patterns = [];
        
        for ($i = 0; $i <= $length - $minPatternLength; $i++) {
            $pattern = substr($clean, $i, $minPatternLength);
            if (!isset($patterns[$pattern])) {
                $patterns[$pattern] = [];
            }
            $patterns[$pattern][] = $i;
        }
        
        // Filter yang muncul minimal 2 kali
        $repeated = array_filter($patterns, function($positions) {
            return count($positions) >= 2;
        });
        
        // Hitung jarak antar kemunculan
        $distances = [];
        $details = [];
        
        foreach ($repeated as $pattern => $positions) {
            $posCount = count($positions);
            for ($j = 0; $j < $posCount - 1; $j++) {
                for ($k = $j + 1; $k < $posCount; $k++) {
                    $distance = $positions[$k] - $positions[$j];
                    $distances[] = $distance;
                    $details[] = [
                        'pattern' => $pattern,
                        'positions' => $positions,
                        'distance' => $distance
                    ];
                }
            }
        }
        
        // Jika tidak ada pola berulang
        if (empty($distances)) {
            return [
                'key_lengths' => [],
                'details' => ['message' => 'Tidak ditemukan pola berulang (trigram) yang signifikan. Coba ciphertext yang lebih panjang.']
            ];
        }
        
        // Cari faktor persekutuan dari semua jarak
        $commonFactors = $this->findCommonFactors($distances);
        
        // Ambil faktor yang paling sering muncul (biasanya 3-5 kandidat)
        arsort($commonFactors);
        $topFactors = array_slice($commonFactors, 0, 5, true);
        
        return [
            'key_lengths' => array_keys($topFactors),
            'details' => [
                'ciphertext_length' => $length,
                'repeated_patterns' => $repeated,
                'distances' => $distances,
                'factor_counts' => $topFactors,
                'raw_details' => $details
            ]
        ];
    }
    
    /**
     * Mencari faktor persekutuan dari semua jarak
     */
    private function findCommonFactors(array $distances): array
    {
        $factors = [];
        foreach ($distances as $d) {
            // Cari faktor dari d (2 sampai sqrt(d))
            for ($i = 2; $i <= sqrt($d); $i++) {
                if ($d % $i == 0) {
                    $factors[$i] = ($factors[$i] ?? 0) + 1;
                    $other = $d / $i;
                    if ($other != $i) {
                        $factors[$other] = ($factors[$other] ?? 0) + 1;
                    }
                }
            }
            // Faktor itu sendiri (jarak itu sendiri juga faktor)
            if ($d > 1) {
                $factors[$d] = ($factors[$d] ?? 0) + 1;
            }
        }
        return $factors;
    }
}