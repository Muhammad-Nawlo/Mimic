<?php
namespace App\Traits;
trait EncryptDecryptTrait{
    private $encrypt_method="DES-CBC";
    private  $key= "%)#@M@#&";
    private  $iv='%&*^=#@<';

    public function encrypt($str)
    {
        $data = openssl_encrypt($str,"DES-CBC",$this->key,OPENSSL_RAW_DATA,$this->iv);
        $data = strtoupper(bin2hex($data));

        return $data;

    }
    function decrypt($str)
    {
        return openssl_decrypt (hex2bin($str), 'DES-CBC', $this->key, OPENSSL_RAW_DATA,$this->iv);

    }
}
