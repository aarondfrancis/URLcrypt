<?php

/**
 * URLCrypt
 *
 * PHP library to securely encode and decode short pieces of arbitrary binary data in URLs.
 *
 * (c) Guillermo Gonzalez
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code.
 */

namespace Atrapalo\UrlCrypt;

/**
 * Class UrlCrypt
 * @package Atrapalo\UrlCrypt
 */
class UrlCrypt
{
    /** @var string */
    public $table = "1bcd2fgh3jklmn4pqrstAvwxyz567890";
    /** @var int */
    private $ivSize = 16;
    /** @var string */
    private $opensslMode = 'AES-256-OFB';

    /**
     * UrlCrypt constructor.
     * @param string $table
     */
    public function __construct($table = null)
    {
        if (!is_null($table) && $table != '') {
            $this->table = $table;
        }
    }

    /**
     * @param string $string
     * @return string
     */
    public function encode($string)
    {
        $table = str_split($this->table, 1);
        $size = strlen($string) * 8 / 5;
        $stringArray = str_split($string, 1);

        $message = "";
        foreach ($stringArray as $char) {
            $message .= str_pad(decbin(ord($char)), 8, "0", STR_PAD_LEFT);
        }

        $message = str_pad($message, ceil(strlen($message) / 5) * 5, "0", STR_PAD_RIGHT);

        $encodeString = "";
        for ($i = 0; $i < $size; $i++) {
            $encodeString .= $table[bindec(substr($message, $i * 5, 5))];
        }

        return $encodeString;
    }

    /**
     * @param string $string
     * @return string
     */
    public function decode($string)
    {
        $table = str_split($this->table, 1);
        $size = strlen($string) * 5 / 8;
        $stringArray = str_split($string, 1);

        $message = "";
        foreach ($stringArray as $char) {
            $message .= str_pad(decbin(array_search($char, $table)), 5, "0", STR_PAD_LEFT);
        }

        $originalString = '';
        for ($i = 0; $i < floor($size); $i++) {
            $originalString .= chr(bindec(substr($message, $i * 8, 8)));
        }

        return $originalString;
    }

    /**
     * @param string $string
     * @param string $key
     * @return string
     * @throws \Exception
     */
    public function encrypt($string, $key)
    {
        $key = $this->prepareKey($key);
        $iv = openssl_random_pseudo_bytes($this->ivSize);
        $cipherText = openssl_encrypt($string, $this->opensslMode, $key, 0, $iv);
        $cipherText = $iv . $cipherText;

        return $this->encode($cipherText);
    }

    /**
     * @param string $string
     * @param string $key
     * @return string
     * @throws \Exception
     */
    public function decrypt($string, $key)
    {
        $key = $this->prepareKey($key);
        $string = $this->decode($string);

        $ivDec = substr($string, 0, $this->ivSize);
        $string = substr($string, $this->ivSize);
        $string = openssl_decrypt($string, $this->opensslMode, $key, 0, $ivDec);

        return $string;
    }

    /**
     * @param $key
     * @return string
     * @throws \Exception
     */
    private function prepareKey($key)
    {
        if (is_null($key) || $key == "") {
            throw new \Exception('No key provided.');
        }

        if (in_array(strlen($key), [32, 48, 64]) && $this->isHexString($key)) {
            return pack('H*', $key);
        } elseif (in_array(strlen($key), [16, 24, 32])) {
            return $key;
        } else {
            return md5($key);
        }
    }

    /**
     * @param $string
     * @return bool
     */
    private function isHexString($string)
    {
        return (preg_match('/^[0-9a-f]+$/i', $string) === 1);
    }
}
