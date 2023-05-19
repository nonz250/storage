<?php
declare(strict_types=1);

namespace Tests;

use Exception;

final class StringTestHelper
{
    private const DEFAULT_LENGTH = 30;

    private const ALPHA_NUM_CHARS = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

    private const MB4_CHARS = ['あ', 'い', 'う', 'え', 'お', '😀', '😃', '😄', '😁', '😆'];

    public static function random(int $length = self::DEFAULT_LENGTH, array $chars = []): string
    {
        try {
            $result = '';
            $baseChars = count($chars) ? $chars : self::ALPHA_NUM_CHARS;

            for ($i = 0; $i < $length; $i++) {
                $result .= $baseChars[random_int(0, count($baseChars) - 1)];
            }
        } catch (Exception $e) {
            exit($e->getMessage());
        }
        return $result;
    }

    public static function randomByMb4(int $length = self::DEFAULT_LENGTH, array $chars = []): string
    {
        return self::random($length, count($chars) ? $chars : self::MB4_CHARS);
    }

    public static function randomByHex(int $length = self::DEFAULT_LENGTH): string
    {
        try {
            return bin2hex(random_bytes((int)($length / 2)));
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public static function randomEmail(int $length = self::DEFAULT_LENGTH): string
    {
        $at = '@';
        $dot = '.';
        $host = 'localhost';
        $necessaryLength = $length - mb_strlen($at) - mb_strlen($dot);
        $userNameLength = $necessaryLength % 2 === 1 ? ($necessaryLength - 1) / 2 : $necessaryLength / 2;
        $domainLength = $necessaryLength - $userNameLength - mb_strlen($host);
        $userName = self::random($userNameLength);
        $domain = self::random($domainLength);
        return $userName . $at . $domain . $dot . $host;
    }

    public static function randomFast(int $length = self::DEFAULT_LENGTH): string
    {
        try {
            return mb_substr(bin2hex(random_bytes($length)), 0, $length);
        } catch (Exception $e) {
            return '';
        }
    }

    public static function randomImageString(int $width = 1, int $height = 1): string
    {
        ob_start();
        $gd = imagecreate($width, $height);
        imagejpeg($gd);
        $content = ob_get_clean();
        return preg_replace('#^data:image/\w+;base64,#i', '', $content);
    }
}
