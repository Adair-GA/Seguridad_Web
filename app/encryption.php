<?php

    namespace encryption;

    function encrypt(string $plaintext, string $filesPath){
        //$key should have been previously generated in a cryptographically safe way, like openssl_random_pseudo_bytes
        $keyPath=$filesPath."key.pem";
        $ivPath=$filesPath."iv.txt";
        $cipher = "aes-256-cbc";
        $key = file_get_contents($keyPath);
        $iv = file_get_contents($ivPath);
        $iv = base64_decode($iv);
        $output = openssl_encrypt($plaintext, $cipher, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }
    
    function decrypt(string $ciphertext, string $filesPath){
        
        $keyPath=$filesPath."key.pem";
        $ivPath=$filesPath."iv.txt";
        $cipher = "aes-256-cbc";
        $key = file_get_contents($keyPath);
        $iv = file_get_contents($ivPath);
        $iv = base64_decode($iv);
    
        $output = openssl_decrypt(base64_decode($ciphertext), $cipher, $key, 0, $iv);
    
        return $output;
    }

?>