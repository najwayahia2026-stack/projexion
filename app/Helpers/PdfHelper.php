<?php

namespace App\Helpers;

class PdfHelper
{
    /**
     * عكس تسلسل النص العربي لعرضه صحيحاً في DomPDF
     * DomPDF لا يدعم RTL ويظهر النص معكوساً - بعكسه يظهر صحيحاً
     */
    public static function reverseRtl(string $text): string
    {
        if (empty($text)) {
            return $text;
        }

        preg_match_all('/./us', $text, $chars);
        return implode('', array_reverse($chars[0]));
    }

    /**
     * عكس النص مع الحفاظ على الأرقام والإنجليزي في مكانها
     * يعكس فقط التسلسلات التي تحتوي حروفاً عربية
     */
    public static function reverseArabicSegments(string $text): string
    {
        return preg_replace_callback(
            '/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}\s]+/u',
            function ($m) {
                return self::reverseRtl($m[0]);
            },
            $text
        );
    }

    /**
     * معالجة HTML لعكس النصوص العربية لعرض صحيح في DomPDF
     */
    public static function processHtmlForRtl(string $html): string
    {
        return preg_replace_callback(
            '/>([^<]+)</us',
            function ($matches) {
                $content = $matches[1];
                if (preg_match('/[\x{0600}-\x{06FF}]/u', $content)) {
                    return '>' . self::reverseArabicSegments($content) . '<';
                }
                return $matches[0];
            },
            $html
        );
    }
}
