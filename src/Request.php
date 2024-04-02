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

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

/**
 * The Appcheap Request
 *
 * @category PHP
 * @package  Appcheap
 * @author   ngocdt <ngocdt@rnlab.io>
 * @license  Appcheap License
 * @link     https://github.com/AppCheap/license
 */
class Request
{
    /**
     * The Appcheap Client
     *
     * @var Client $_client
     */
    private Client $_client;

    /**
     * Construct the Appcheap Verify.
     *
     * @param Client $client The Appcheap Client.
     * 
     * @return void
     */
    public function __construct( Client $client )
    {
        $this->_client = $client;
    }

    /**
     * Send request to Appcheap server
     * 
     * @param string $method The method.
     * @param string $url    The url.
     * @param array  $data   The data.
     * 
     * @return array
     */
    public function sendRequest($method, $url, $data)
    {
        try {
            $http = $this->_client->getHttpClient();
            $response = $http->request($method, $url, $data);
            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
            return [
                'error' => $e->getMessage(),
            ];
        } catch (ConnectException $e) {
            return ['error' => 'Connection error.'];
        }
        return [
            'error' => 'Unknown error.',
        ];
    }
    
}
