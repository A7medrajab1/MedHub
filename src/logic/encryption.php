<?php 
$key = "Fs@R#WFWRF*FE4r3XDDDDfucksoftware";
$cipher = "AES-256-CBC";
// $ivlen = openssl_cipher_iv_length($cipher);
$iv = "1234567891051234";
$tag = '';

function encrypt(string $text):string {
    
    return openssl_encrypt($text, $GLOBALS ['cipher'], $GLOBALS ['key'], 0 , $GLOBALS ['iv'], $GLOBALS['tag']);
}


function decrypt(string $text):string{
    
    return openssl_decrypt($text, $GLOBALS ['cipher'], $GLOBALS ['key'], 0 , $GLOBALS ['iv'], $GLOBALS['tag']);
}


function encrypt_arr($data):array {
    $encryptedData = array();
    foreach ($data as $key => $value) {
        $encryptedData[$key] = encrypt($value);
    }
    return $encryptedData;
}

function decrypt_arr($encryptedData):array {
    $decryptedData = array();
    foreach ($encryptedData as $key => $value) {
        $decryptedData[$key] = decrypt($value);
    }
    return $decryptedData;
}


// echo encrypt('123');
// echo decrypt('ahmniab11@gmail.com');


