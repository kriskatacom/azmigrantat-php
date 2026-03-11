<?php

namespace App\Services;

class TranslateService
{
    public static function google(string $text, string $targetLang, string $sourceLang = 'auto'): string
    {
        if (empty($text)) return '';

        $targetLang = strtolower($targetLang);

        $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl="
            . $sourceLang . "&tl=" . $targetLang . "&dt=t&q=" . urlencode($text);

        $response = file_get_contents($url);
        $result = json_decode($response, true);

        if (isset($result[0][0][0])) {
            return $result[0][0][0];
        }

        return $text;
    }
}
