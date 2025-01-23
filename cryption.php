<?php 
    define('ckey', 'simplekey123456');
    
    function encrypt($email) {
        $iv = random_bytes(openssl_cipher_iv_length('AES-128-CBC'));
        $encrypted = openssl_encrypt($email, 'AES-128-CBC', ckey, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    function decrypt($encryptedData) {
        list($encrypted, $iv) = explode('::', base64_decode($encryptedData), 2);
        return openssl_decrypt($encrypted, 'AES-128-CBC', ckey, 0, $iv);
    }
?>