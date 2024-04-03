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

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client as HttpClient;

/**
 * The Appcheap Client
 *
 * @category PHP
 * @package  Appcheap
 * @author   ngocdt <ngocdt@rnlab.io>
 * @license  Appcheap License
 * @link     https://github.com/AppCheap/license
 */
class Client
{

    /**
     * The version of the library.
     */
    const VER = "1.0";

    /**
     * The HTTP client
     * 
     * @var ClientInterface $_http
     */
    private $_http;

    /**
     * The configuration options
     * 
     * @var array $config
     */
    private $_config;


     /**
      * Construct the Appcheap Client.
      *
      * @param array $config {
      *
      * @type string $identify The identify of the product.
      * @type string $base_uri The url of the api.
      *
      *  } 
      */
    public function __construct(array $config = [])
    {
        $this->_config = array_merge(
            [
            'identify' => 'app-builder',
            'base_uri' => 'https://verify.appcheap.io/api/v1',
            'plugin_file' => '',
            ], $config
        );

    }

    /**
     * Get the configuration options.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Get the HTTP client.
     *
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        if (null === $this->_http) {
            $this->_http = $this->createDefaultHttpClient();
        }

        return $this->_http;
    }

    /**
     * Create the default HTTP client.
     *
     * @return ClientInterface
     */
    protected function createDefaultHttpClient()
    {
        return new HttpClient(
            [
            'base_uri' => $this->_config['base_uri'],
            'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Identify' => $this->_config['identify'],
                    'User-Agent' => 'Appcheap/' . self::VER . ' ('. get_site_url() .')'
                ],
            ]
        );
    }

    /**
     * Set the HTTP client.
     * 
     * @param ClientInterface $http The HTTP client.
     * 
     * @return void
     */
    public function setHttpClient(ClientInterface $http)
    {
        $this->_http = $http;
    }

    /**
     * Get a string containing the version of the library.
     *
     * @return string
     */
    public function getLibraryVersion()
    {
        return self::VER;
    }

     /**
      * Get key
      * 
      * @return string
      */
    public function getKey()
    {
        $config = $this->_config;
        
        if (isset($config['identify'])) {
            return $config['identify'] . '-license';
        }

        return 'app-builder-license';
    }

    /**
     * Get the License object.
     * 
     * @return License
     */
    public function getLicense()
    {
        return new License($this);
    }

    /**
     * Get license key
     * 
     * @return string
     */
    public function getLicenseKey()
    {
        $license = $this->getLicense()->getLicense();
        return $license['license'] ?? '';
    }

    /**
     * Get the plugin file path.
     * 
     * @return string
     */
    public function getPLuginFile()
    {
        return $this->_config['plugin_file'];
    }

    /**
     * Get the plugin version.
     * 
     * @return string
     */
    public function getPluginVersion()
    {
        $plugin_file = $this->getPLuginFile();
        if (empty($plugin_file)) {
            return '';
        }

        $plugin_data = get_file_data($plugin_file, ['Version' => 'Version']);
        return $plugin_data['Version'];
    }

    /**
     * Get the plugin slug.
     * 
     * @return string
     */
    public function getPluginSlug()
    {
        $plugin_file = $this->getPLuginFile();
        if (empty($plugin_file)) {
            return '';
        }

        $plugin_data = get_file_data($plugin_file, ['Text Domain' => 'Text Domain']);
        return $plugin_data['Text Domain'];
    }
}
