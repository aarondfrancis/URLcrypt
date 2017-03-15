<?php

namespace UrlCrypt;

class UrlCryptTest extends \PHPUnit_Framework_TestCase
{
    public function testArbitraryEncode()
    {
        // monte carlo-ish tests
        // test 300 strings of random characters for each length between 1 and 30.
        for ($i = 1; $i < 31; $i++) {
            for ($n = 0; $n < 300; $n++) {
                $str = "";
                for ($z = 0; $z < $i; $z++) {
                    $str .= chr(rand(0, 255));
                }
                $this->deo($str);
            }
        }
    }

    public function testEmptyString()
    {
        $this->deo('');
    }

    public function testDefinedEncode()
    {
        $this->assertEquals("mnAhk6tlp2qg2yldn8xcc", UrlCrypt::encode("chunky bacon!"));
    }

    public function testDefinedDecode()
    {
        $this->assertEquals("chunky bacon!", UrlCrypt::decode("mnAhk6tlp2qg2yldn8xcc"));
    }

    public function testNoKey()
    {
        $this->setExpectedException('Exception');
        UrlCrypt::encrypt("aaron", null);
    }

    public function testEncryption()
    {
        $key = "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3";
        $encrypted = UrlCrypt::encrypt('aaron', $key);
        $decrypted = UrlCrypt::decrypt($encrypted, $key);

        $this->assertEquals('aaron', $decrypted);
    }

    // decoded equals original
    private function deo($str)
    {
        $this->assertEquals($str, UrlCrypt::decode(UrlCrypt::encode($str)));
    }
}
