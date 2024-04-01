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
 * The Appcheap Cache
 *
 * @category PHP
 * @package  Appcheap
 * @author   ngocdt <ngocdt@rnlab.io>
 * @license  Appcheap License
 * @link     https://github.com/AppCheap/license
 */
class Cache
{

    /**
     * The key of the license
     * 
     * @var string $key
     */
    private string $_key;

    /**
     * Construct the Appcheap Store.
     *
     * @param string $key The stie key.
     * 
     * @return void
     */
    public function __construct( String $key )
    {
        $this->_key = $key;
    }

    /**
     * Create/Update data
     * 
     * @param mixed  $data   The data.
     * @param string $group  The group.
     * @param int    $expire The expire.
     * 
     * @return bool
     */
    public function set($data, $group = '', $expire = 0)
    {
        return wp_cache_set($this->_key, $data, $group, $expire);
    }

    /**
     * Get data
     * 
     * @param string $group The group.
     * 
     * @return mixed
     */
    public function get($group = '')
    {
        return wp_cache_get($this->_key, $group);
    }
}
