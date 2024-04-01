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

use PHPUnit\Framework\TestCase;
use Appcheap\Obfuscate;

/**
 * The ObfuscateTest
 *
 * @category PHP
 * @package  Appcheap
 * @author   ngocdt <ngocdt@rnlab.io>
 * @license  Appcheap License
 * @link     https://github.com/AppCheap/license
 */
class ObfuscateTest extends TestCase
{
    /**
     * Test deobfuscate
     * 
     * @return void
     */
    public function testDeobfuscate()
    {
        $data = [
            'method' => 'aes-192-ofb',
            'key' => '12345',
            'data' => [
                'name' => 'ngocdt',
                'email' => 'ngocdt@rnlab.io'
            ]
        ];

        $encoded = Obfuscate::encode($data);
        $expected = Obfuscate::decode($encoded);
        $this->assertEquals($data['data'], $expected);
    }

    /**
     * Test deobfuscate
     * 
     * @return void
     */
    public function testDeobfuscate2()
    {
        $data = [
            'method' => 'aes-256-cbc-hmac-sha256',
            'key' => 'abc123!#$%',
            'data' => 'Appcheap'
        ];

        $encoded = Obfuscate::encode($data);
        $expected = Obfuscate::decode($encoded);
        $this->assertEquals($data['data'], $expected);
    }
}
