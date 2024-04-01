<?php

/**
 * Copyright 2024 Appcheap
 * Licensed under the Appcheap License, Version 1.0,
 * https://appcheap.io/license
 *
 * PHP version 7.4
 *
 * @category PHP
 * @package  Appcheap
 * @author   ngocdt <ngocdt@rnlab.io>
 * @license  Appcheap License
 * @link     https://github.com/AppCheap/license
 */

namespace Appcheap;

/**
 * The Appcheap Obfuscate
 *
 * @category PHP
 * @package  Appcheap
 * @author   ngocdt <ngocdt@rnlab.io>
 * @license  Appcheap License
 * @link     https://github.com/AppCheap/license
 */
class Obfuscate
{

    /**
     * Encode the data
     * 
     * @param array $data The data.
     * 
     * @return string
     */
    public static function encode($data)
    {
        $method = $data['method'];
        $key = $data['key'];

        // Generate an initialization vector
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));

        // Encrypt the license key
        $encrypted = openssl_encrypt(serialize($data['data']), $method, $key, 0, $iv);

        return base64_encode($encrypted . '::' . $iv . '::' . $method . '::' . $key);
    }

    /**
     * Decode the data
     * 
     * @param string $data The data.
     * 
     * @return mixed
     */
    public static function decode($data)
    {
        // Separate the encrypted data
        list(
            $encrypted,
            $iv,
            $method,
            $key
        ) = explode('::', base64_decode($data), 4);
        return unserialize(openssl_decrypt($encrypted, $method, $key, 0, $iv));
    }

}
