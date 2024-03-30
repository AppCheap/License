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
use Appcheap\Plugin;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Client as HttpClient;

/**
 * The PluginTest
 *
 * @category PHP
 * @package  Appcheap
 * @author   ngocdt <ngocdt@rnlab.io>
 * @license  Appcheap License
 * @link     https://github.com/AppCheap/license
 */



class PluginTest extends TestCase
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
        $plugin = new Plugin($client, 'test');
        $this->assertInstanceOf(Plugin::class, $plugin);
    }

    /**
     * Test getUpdateInfo
     * 
     * @return void
     */
    public function testGetUpdateInfo()
    {
        $client = new Client(
            [
                'identify' => 'test',
                'base_uri' => 'http://test.com'
            ]
        );

        $expected = [
            'new_version' => '1.0'
        ];

        // Mock getHttpClient
        $http = createHttpClientMock(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($expected)
        );

        $client->setHttpClient($http);

        $plugin = new Plugin($client, 'test');

        $data = $plugin->getUpdateInfo('test', 'base');
    
        $this->assertEquals($expected, $data);
    }
}

if (!function_exists('add_filter')) {
    /**
     * Mock add_filter function
     * 
     * @param string $tag             
     * @param string $function_to_add 
     * @param int    $priority      
     * @param int    $accepted_args  
     * 
     * @return void
     */
    function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1)
    {
    }
}


/**
 * Create Http Client Mock
 * 
 * @param int   $status  The status
 * @param array $headers The headers
 * @param mixed $body    The body
 * 
 * @return ClientInterface
 */
function createHttpClientMock($status, $headers = [], $body = null)
{
    $response = new Response($status, $headers, $body);
    $mock = new MockHandler([$response]);
    $handler = HandlerStack::create($mock);
    return new HttpClient(['handler' => $handler]);
}
