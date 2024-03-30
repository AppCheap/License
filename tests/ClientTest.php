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
use Appcheap\Client;
use GuzzleHttp\ClientInterface;

/**
 * The ClientTest
 *
 * @category PHP
 * @package  Appcheap
 * @author   ngocdt <ngocdt@rnlab.io>
 * @license  Appcheap License
 * @link     https://github.com/AppCheap/license
 */
class ClientTest extends TestCase
{
    /**
     * Test construct
     * 
     * @return void
     */
    public function testConstruct()
    {
        $client = new Client(
            [
                'identify' => 'test',
                'base_uri' => 'http://test.com'
            ]
        );
        $this->assertInstanceOf(Client::class, $client);
    }

    /**
     * Test get Http Client
     * 
     * @return void
     */
    public function testGetHttpClient()
    {
        $client = new Client(
            [
                'identify' => 'test',
                'base_uri' => 'http://test.com'
            ]
        );
        $http = $client->getHttpClient();
        $this->assertInstanceOf(ClientInterface::class, $http);        
    }

    /**
     * Test get Http Client Base Uri
     * 
     * @return void
     */
    public function testGetHttpClientBaseUri()
    {
        $client = new Client(
            [
                'identify' => 'test',
                'base_uri' => 'http://test.com'
            ]
        );
        $http = $client->getHttpClient();

        // Check if has getConfig method
        if (method_exists($http, 'getConfig')) {
            $config = $http->getConfig();
            $this->assertIsArray($config);
            $this->assertArrayHasKey('base_uri', $config);
            $this->assertEquals('http://test.com', $config['base_uri']);
        }
    }

    /**
     * Test get Http Client Identify
     * 
     * @return void
     */
    public function testGetHttpClientIdentify()
    {
        $client = new Client(
            [
                'identify' => 'test',
                'base_uri' => 'http://test.com'
            ]
        );
        $http = $client->getHttpClient();

        // Check if has getConfig method
        if (method_exists($http, 'getConfig')) {
            $config = $http->getConfig();
            $this->assertIsArray($config);
            $this->assertArrayHasKey('headers', $config);
            $this->assertArrayHasKey('X-Identify', $config['headers']);
            $this->assertEquals('test', $config['headers']['X-Identify']);
        }
    }

    /**
     * Test get Config
     * 
     * @return void
     */
    public function testGetConfig()
    {
        $client = new Client(
            [
                'identify' => 'test',
                'base_uri' => 'http://test.com'
            ]
        );
        $config = $client->getConfig();
        $this->assertIsArray($config);
        $this->assertArrayHasKey('identify', $config);
        $this->assertArrayHasKey('base_uri', $config);
        $this->assertEquals('test', $config['identify']);
        $this->assertEquals('http://test.com', $config['base_uri']);
    }

    /**
     * Test get default config
     * 
     * @return void
     */
    public function testGetDefaultConfig()
    {
        $client = new Client();
        $config = $client->getConfig();
        $this->assertIsArray($config);
        $this->assertArrayHasKey('identify', $config);
        $this->assertArrayHasKey('base_uri', $config);
        $this->assertEquals('app-builder', $config['identify']);
        $this->assertEquals(
            'https://verify.appcheap.io/api/v1',
            $config['base_uri']
        );
    }
}
