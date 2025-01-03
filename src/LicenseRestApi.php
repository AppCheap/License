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
 * The Appcheap LicensePage
 *
 * @category PHP
 * @package  Appcheap
 * @author   ngocdt <ngocdt@rnlab.io>
 * @license  Appcheap License
 * @link     https://github.com/AppCheap/license
 */
class LicenseRestApi
{

    /**
     * The namespace
     * 
     * @var string $namespace
     */
    public $namespace = 'appcheap';

    /**
     * The rest base
     * 
     * @var string $rest_base
     */
    public $rest_base = 'license';

    /**
     * The version
     * 
     * @var string $version
     */
    public $version = 'v1';

    /**
     * The verify
     * 
     * @var Verify $_verify
     */
    private Verify $_verify;

    /**
     * Construct the Appcheap Store.
     * 
     * @param Verify $verify The verify.
     * @param array  $params The params.
     * 
     * @return void
     */
    public function __construct(Verify $verify, array $params = [])
    {
        foreach ($params as $key => $value) {
            $this->$key = $value;
        }

        $this->_verify = $verify;
    }

    /**
     * Register
     * 
     * @return void
     */
    public function register()
    {
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    /**
     * Run
     * 
     * @return void
     */
    public function register_routes()
    {
        register_rest_route(
            $this->namespace . '/' . $this->version,
            '/' . $this->rest_base,
            array(
                'methods'  => 'POST',
                'callback' => array($this, 'verify'),
                'permission_callback' => array($this, 'verify_permission'),
                'args'     => array(
                    'type' => array(
                        'required' => true,
                        'type'     => 'string',
                        'enum'     => array('activate', 'deactivate'),
                    ),
                    'license' => array(
                        'required' => true,
                        'type'     => 'string',
                    ),
                    'email' => array(
                        'required' => true,
                        'type'     => 'string',
                    ),
                ),
            )
        );
    }

    /**
     * Verify
     * 
     * @param \WP_REST_Request $request The request.
     * 
     * @return void
     */
    public function verify(\WP_REST_Request $request)
    {
        $license = $request->get_param('license');
        $email = $request->get_param('email');
        $type = $request->get_param('type');

        if (empty($license) || empty($email)) {
            return new \WP_Error('license_email_empty', 'License or email is empty', array('status' => 400));
        }

        $status = 200;
        try {
            if ($type === 'activate') {
                $response =  $this->_verify->activate($license, $email);
            } else {
                $response =  $this->_verify->deactivate($license, $email);
            }
        } catch (Exception $e) {
            $status = 404;
            $response = array(
                "status"       => false,
                "message"      => $e -> getMessage(),
            );
        }

        return new \WP_REST_Response($response, $status);
    }

    /**
     * Verify permission
     * 
     * @param \WP_REST_Request $request The request.
     * 
     * @return void
     */
    public function verify_permission(\WP_REST_Request $request)
    {
        return current_user_can('manage_options');
    }
}
