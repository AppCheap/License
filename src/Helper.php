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
 * The Appcheap Helper
 *
 * @category PHP
 * @package  Appcheap
 * @author   ngocdt <ngocdt@rnlab.io>
 * @license  Appcheap License
 * @link     https://github.com/AppCheap/license
 */
class Helper
{

    /**
     * Get site key
     * 
     * @return string
     */
    public static function getSiteKey()
    {
        return md5(get_site_url());
    }

    /**
     * Generate unique key
     * 
     * @return string
     */
    public static function generateUniqueKey()
    {
        return md5(uniqid());
    }
}
