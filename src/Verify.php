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
 * The Appcheap Verify
 *
 * @category PHP
 * @package  Appcheap
 * @author   ngocdt <ngocdt@rnlab.io>
 * @license  Appcheap License
 * @link     https://github.com/AppCheap/license
 */
class Verify
{

    /**
     * The Appcheap Client
     *
     * @var Client $_client
     */
    private Client $_client;

    /**
     * The key of the license
     * 
     * @var string $key
     */
    private string $_key;

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
        $this->_key = $client->getConfig()['identify'] . '-license';
    }

    /**
     * Activate
     * 
     * @param string $license The license.
     * @param string $email   The email.
     * 
     * @return void
     * 
     * @throws Exception
     */
    public function activate(string $license, string $email)
    {

        $http = $this->_client->getHttpClient();

        $data = [
            'license' => $license,
            'email' => $email,
        ];

        try {
            $response = $http->request('POST', '/activate', ['json' => $data]);
        } catch (ClientException $e) {
            throw new Exception('Client error!');
        } catch (ConnectException $e) {
            throw new Exception('Connect error!');
        } catch (Exception $e) {
            throw new Exception('Unknow error!');
        }

        $body = json_decode($response->getBody()->getContents(), true);

        if ($body['status'] == 'success') {
            return $this->_storeLicense($body);
        }
        
        throw new Exception('Error activate license!');
    }
        
    public function deactivate()
    {
     
        $license = $this->getLicense();

        if (empty($license)) {
            return false;
        }

        $http = $this->_client->getHttpClient();

        try {
            $response = $http->request('POST', '/deactivate', ['json' => $license]);
        } catch (ClientException $e) {
            throw new Exception('Client error!');
        } catch (ConnectException $e) {
            throw new Exception('Connect error!');
        } catch (Exception $e) {
            throw new Exception('Unknow error!');
        }

        $body = json_decode($response->getBody()->getContents(), true);

        if ($body['status'] == 'success') {
            return $this->_removeLicense($body);
        }

        throw new Exception('Error deactive license!');
    }

    /**
     * Store License
     * 
     * @param array $data The data.
     * 
     * @return bool
     */
    private function _storeLicense($data)
    {
        return update_option($this->_key, $data);
    }

    /**
     * Get License
     * 
     * @return array
     */
    public function getLicense()
    {
        return get_option($this->_key);
    }

    /** 
     * Remove License
     * 
     * @return bool
     */
    private function _removeLicense()
    {
        return delete_option($this->_key);
    }
}
