<?php

namespace App\Services\Vigenere;

class CoreService
{
    public function encrypt(string $plaintext, string $key): array
    {
        return $this->process($plaintext, $key, 'encrypt');
    }

    public function decrypt(string $ciphertext, string $key): array
    {
        return $this->process($ciphertext, $key, 'decrypt');
    }

    private function process(string $text, string $key, string $mode): array
    {
        $text = strtoupper($text);
        $key = strtoupper($key);
        $keyChars = str_split($key);
        $keyIndex = 0;
        $result = '';
        $steps = [];

        foreach (str_split($text) as $char) {
            if (ctype_alpha($char)) {
                $p = ord($char) - ord('A');
                $k = ord($keyChars[$keyIndex % count($keyChars)]) - ord('A');

                if ($mode === 'encrypt') {
                    $c = ($p + $k) % 26;
                    $step = "$char($p) + {$keyChars[$keyIndex % count($keyChars)]}($k) = " . ($p+$k) . " mod 26 = $c → " . chr($c + ord('A'));
                    $result .= chr($c + ord('A'));
                } else {
                    $c = ($p - $k) % 26;
                    if ($c < 0) $c += 26;
                    $step = "$char($p) - {$keyChars[$keyIndex % count($keyChars)]}($k) = " . ($p-$k) . " mod 26 = $c → " . chr($c + ord('A'));
                    $result .= chr($c + ord('A'));
                }
                $steps[] = $step;
                $keyIndex++;
            } else {
                $result .= $char;
                $steps[] = "Karakter '$char' bukan huruf, tetap: $char";
            }
        }

        return [
            'result' => $result,
            'steps' => implode("\n", $steps)
        ];
    }
}