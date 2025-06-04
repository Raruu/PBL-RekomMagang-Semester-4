<?php

namespace App\Services;

use DOMDocument;

final class Utils
{
    public static function sanitizeString(?string $str)
    {
        if (!$str) return "";
        $str = preg_replace('/<[^>]*>/', '', $str);
        $doc = new DOMDocument();
        $doc->loadHTML(mb_convert_encoding($str, 'HTML-ENTITIES', "UTF-8"));
        return $doc->textContent;
    }
}
