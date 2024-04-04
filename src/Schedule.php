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

use Error;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

/**
 * The Appcheap Schedule
 *
 * @category PHP
 * @package  Appcheap
 * @author   ngocdt <ngocdt@rnlab.io>
 * @license  Appcheap License
 * @link     https://github.com/AppCheap/license
 */
class Schedule
{

    /**
     * Hook event name
     * 
     * @var string $_hookEvent
     */
    private string $_hookEvent;

    /**
     * Construct the Appcheap Schedule.
     *
     * @param Client $client The Appcheap Client.
     * 
     * @return void
     */
    public function __construct( Client $client )
    {
        $this->_hookEvent = $client->getPluginSlug() . '_license_check_schedule';

        add_action('init', array($this, 'scheduleLicenseCheck'));
        register_deactivation_hook($client->getPluginFile(), array($this, 'deactivate'));
    }

    /**
     * Schedule license check
     * 
     * @return void
     */
    public function scheduleLicenseCheck()
    {
        if (false === as_has_scheduled_action($this->_hookEvent) ) {
            as_schedule_recurring_action(time(), DAY_IN_SECONDS, 'schedule_license_callback', array(), '', true);
        }
    }

    /**
     * Deactivate the plugin
     * 
     * @return void
     */
    public function deactivate()
    {
        as_unschedule_action($this->_hookEvent);
    }
}
