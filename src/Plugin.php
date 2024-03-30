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

use Appcheap\Client;
use Appcheap\Model\PluginItem;
use GuzzleHttp\Exception\ClientException;

/**
 * The Appcheap Client
 *
 * @category PHP
 * @package  Appcheap
 * @author   ngocdt <ngocdt@rnlab.io>
 * @license  Appcheap License
 * @link     https://github.com/AppCheap/license
 */
class Plugin
{


    /**
     * The Appcheap Client
     *
     * @var Client $_client
     */
    private Client $_client;

    /**
     * The base name of the plugin
     *
     * @var string $_base_name
     */
    private string $_base_name;

    /**
     * Construct the Appcheap Plugin.
     *
     * @param Client $client    The Appcheap Client.
     * @param string $base_name The base name of the plugin.
     */
    public function __construct( Client $client, string $base_name )
    {
        $this->_client    = $client;
        $this->_base_name = $base_name;
        $this->run();
    }

    /**
     * Run WordPress Filter and Action
     * 
     * @return void
     */
    public function run()
    {
        add_filter('pre_set_site_transient_update_plugins', array( $this, 'updatePlugins' ));
        add_filter('plugins_api', array( $this, 'pluginsApi' ), 10, 3);
    }

    /**
     * Pre Set Site Transient Update Plugins
     *
     * @param mixed $transient The transient.
     *
     * @return mixed
     */
    public function updatePlugins( $transient )
    {

        if (empty($transient->checked) ) {
            return $transient;
        }

        try {
            // Check for updates
            $license   = 'your_license_key';
            $base_name = $this->_base_name;

            $data = $this->getUpdateInfo($license, $base_name);

            $item        = new PluginItem($data);
            $old_version = $transient->checked[ $this->_base_name ];

            if ($item->hasNewVersion($old_version) ) {
                $transient->response[ $this->_base_name ] = $item->toObject();
            } else {
                $transient->no_update[ $this->_base_name ] = $item->toObject();
            }
        } catch ( Exception $e ) {
            error_log($e->getMessage());
            return $transient;
        }

        return $transient;
    }

    /**
     * Check Update
     *
     * @param string $license   The license.
     * @param string $base_name The base name.
     *
     * @return array
     *
     * @throws HttpException
     */
    public function getUpdateInfo( string $license, string $base_name )
    {

        $http = $this->_client->getHttpClient();

        $params = array(
            'license'   => $license,
            'base_name' => $base_name,
        );

        try {
            $response = $http->request('GET', 'plugin/update', $params);
        } catch ( ClientException $e ) {
            throw new Exception("Error: Call API failed");
        }

        // Validate the response
        if ($response->getStatusCode() !== 200 ) {
            throw new Exception('Error: ' . $response->getStatusCode());
        }

        $data = json_decode($response->getBody(), true);

        // Validate data
        if (empty($data) ) {
            throw new Exception('Error: Invalid data');
        }

        // Validate version
        if (empty($data['new_version']) ) {
            throw new Exception('Error: Invalid version');
        }

        return $data;
    }

    /**
     * Pre Update Data
     *
     * @param array $data The data.
     *
     * @return object
     */
    public function preUpdateData( $data )
    {
        return (object) $data;
    }

    /**
     * Plugins API
     *
     * @param mixed  $res    The response.
     * @param string $action The action.
     * @param object $args   The args.
     *
     * @return mixed
     */
    public function pluginsApi( $res, $action, $args )
    {
        return $res;
    }

}
