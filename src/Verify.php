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
     * The License object
     * 
     * @var License $_license
     */
    private License $_license;

    /**
     * The request object
     */
    private Request $_request;

    /**
     * Construct the Appcheap Verify.
     *
     * @param Client $client The Appcheap Client.
     * 
     * @return void
     */
    public function __construct( Client $client )
    {
        $this->_request = new Request($client);
        $this->_license = $client->getLicenseInstance();
        
        add_action('schedule_license_callback', array($this, 'verify'));

        new Schedule($client);
    }

    /**
     * Create license page
     * 
     * @param array $params The params.
     * 
     * @return void
     */
    public function registerLicensePage($params)
    {
        $licensePage = new LicensePage($this, $params);
        $licensePage->register();
    }

    /**
     * Activate
     * 
     * @param string $license The license.
     * @param string $email   The email.
     * 
     * @return bool
     * 
     * @throws Exception
     */
    public function activate(string $license, string $email)
    {

        if (empty($license) || empty($email)) {
            throw new Exception('Error: License or email is empty');
        }

        $data = [
            'license' => $license,
            'email' => $email,
        ];

        $body = $this->_request->sendRequest('POST', 'activate', ['json' => $data]);

        // Check if request failed
        if ($body && isset($body['message'])) {
            throw new Exception($body['message']);
        }

        // Check license status
        if (isset($body['data']) && $this->_isActive($body['data'])) {
            return $this->_license->updateLicense($body);
        } else {
            throw new Exception('Error activate license!');
        }
    }

    /**
     * Get license infomation
     * 
     * @return array
     */
    public function getLicense()
    {
        return $this->_license->getLicense();
    }

    /**
     * Check if license is active
     * 
     * @param array $data The data.
     * 
     * @return bool
     */
    private function _isActive($data)
    {
        if (empty($data)) {
            return false;
        }
        return isset($data['status']) && $data['status'] == 'active';
    }
       
    /**
     *  Deactivate license
     * 
     * @return bool
     * 
     * @throws Exception
     */
    public function deactivate()
    {
        $license = $this->_license->getLicense();

        if (empty($license)) {
            throw new Exception('Error: License not found');
        }

        $data = [
            'license' => $license['license'],
        ];

        $body = $this->_request->sendRequest(
            'POST',
            'deactivate',
            ['json' => $data]
        );

        // Check if request failed
        if ($body && isset($body['message'])) {
            throw new Exception($body['message']);
        }

        // Check license status
        if ($body['status'] == 'inactive') {
            return $this->_license->deleteLicense();
        } else {
            throw new Exception('Error deactivate license!');
        }
    }

    /**
     * Verify license
     * 
     * @return bool
     * 
     * @throws Exception
     */
    public function verify()
    {
        $license = $this->_license->getLicense();

        if (empty($license)) {
            throw new Exception('Error: License not found');
        }

        if (empty($data)) {
            throw new Exception('Error: License not found');
        }

        $body = $this->_request->sendRequest(
            'POST',
            'verify',
            ['json' => $data]
        );

        // Check if request failed
        if ($body && isset($body['message'])) {
            throw new Exception($body['message']);
        }

        // Check license status
        if (isset($body['data']['status']) && $body['data']['status'] !== 'active') {
            return $this->_license->updateLicense($body);
        }
    }

    /**
     * Request check status
     * 
     * @return array
     */
    public function requestCheckStatus()
    {
        $data = $this->_license->getLicense();

        if (empty($data)) {
            return [
                'status' => 'inactive',
                'message' => 'License not found.',
            ];
        }

        if (empty($data) || !isset($data['expired'])) {
            return [
                'status' => 'inactive',
                'message' => 'License not found.',
            ];
        }

        $expired = strtotime($data['expired']);
        $now = time();

        $key = Helper::generateUniqueKey();

        if ($expired < $now) {
            return [
                'status' => 'inactive',
                'message' => 'License expired.',
            ];
        }
        
        $cache = new Cache($key);
        // Expire in 5 minutes
        $cache->set(new Status($data), '', 300);

        return [
            'key' => $key,
        ];
    }

    /**
     * Check status
     * 
     * @param string $key The key.
     * 
     * @return Status
     */
    public function checkStatus(string $key)
    {
        $cache = new Cache($key);
        return $cache;
    }
}
