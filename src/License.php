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
 * The Appcheap License
 *
 * @category PHP
 * @package  Appcheap
 * @author   ngocdt <ngocdt@rnlab.io>
 * @license  Appcheap License
 * @link     https://github.com/AppCheap/license
 */
class License
{

    /**
     * The Appcheap Client
     *
     * @var Client $_client
     */
    private Client $_client;

    /**
     * The license store
     * 
     * @var Store $_licenseStore
     */
    private Store $_licenseStore;

    /**
     * Construct the Appcheap Plugin.
     *
     * @param Client $client The Appcheap Client.
     */
    public function __construct( Client $client)
    {
        $this->_client = $client;
        $this->_licenseStore = new Store($client->getKey());
    }

     /**
      * Get license
      * 
      * @return array
      */
    public function getLicense()
    {
        $license = $this->_licenseStore->get();

        if (empty($license)) {
            return [];
        }

        return Obfuscate::decode($license);
    }

    /**
     * Check if license status is active only use for library check
     * 
     * @return bool
     */
    public function isActive()
    {

        $data = $this->getLicense();

        if (empty($data) || !is_array($data) || !isset($data['status'])) {
            return false;
        }
        return isset($data['status']) && $data['status'] == 'active';
    }
}
