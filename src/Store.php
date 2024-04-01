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
 * The Appcheap Store
 *
 * @category PHP
 * @package  Appcheap
 * @author   ngocdt <ngocdt@rnlab.io>
 * @license  Appcheap License
 * @link     https://github.com/AppCheap/license
 */
class Store
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
     * @param array $data The data.
     * 
     * @return bool
     */
    public function update($data)
    {
        return update_option($this->_key, $data);
    }

    /**
     * Get data
     * 
     * @return array
     */
    public function get()
    {
        return get_option($this->_key);
    }

    /** 
     * Delete data
     * 
     * @return bool
     */
    public function delete()
    {
        return delete_option($this->_key);
    }
}
