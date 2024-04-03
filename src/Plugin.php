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
            $license   = $this->_client->getLicenseKey();
            $base_name = $this->_base_name;

            $data = $this->getUpdateInfo($license, $base_name);

            if (empty($data) || !isset($data['new_version'])) {
                return $transient;
            }

            $item        = new PluginItem($data);

            if ($item->hasNewVersion($this->_client->getPluginVersion()) ) {
                $transient->response[ $this->_base_name ] = $item->toObject();
            } else {
                $transient->no_update[ $this->_base_name ] = $item->toObject();
            }
        } catch ( Exception $e ) {
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
        try {
            $request = new Request($this->_client);
            $params = array(
                'license'   => $license,
                'base_name' => $base_name,
            );
            $data = $request->sendRequest('GET', 'plugin-update', ['query' => $params]);
            return $data;
        } catch ( Exception $e ) {
            throw new Exception($e->getMessage());
        }
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
        if ($action !== 'plugin_information') {
            return $res;
        }

        if (empty($args->slug) || $args->slug !== $this->_client->getPluginSlug()) {
            return $res;
        }

        try {
            $license   = $this->_client->getLicenseKey();
            $base_name = $this->_base_name;

            $data = $this->getUpdateInfo($license, $base_name);

            if (empty($data)) {
                return $res;
            }

            $item = new PluginItem($data);

            return $item->toObject(true);
        } catch ( Exception $e ) {
            return $res;
        }
    }

}
