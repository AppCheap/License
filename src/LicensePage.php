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
class LicensePage
{

    /**
     * The parent slug
     * 
     * @var string $parent_slug
     */
    public $parent_slug = 'options-general.php';

    /**
     * The page title
     * 
     * @var string $page_title
     */
    public $page_title = 'License';

    /**
     * The menu title
     * 
     * @var string $menu_title
     */
    public $menu_title = 'License';

    /**
     * The menu slug
     * 
     * @var string $menu_slug
     */
    public $menu_slug = 'appcheap-license';

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
     * Run
     * 
     * @return void
     */
    public function register()
    {
        add_action('admin_menu', [$this, 'addSubMenu']);
    }

    /**
     * Add sub menu
     * 
     * @return void
     */
    public function addSubMenu()
    {
        add_submenu_page(
            $this->parent_slug,
            $this->page_title,
            $this->menu_title,
            'manage_options',
            $this->menu_slug,
            [$this, 'render']
        );
    }

    /**
     * Render
     * 
     * @return void
     */
    public function render()
    {

        $data = $this->_verify->getLicense();
        $message = '';

        $license_key = $data['license'] ?? '';
        $license_email = $data['email'] ?? '';

        // Check if form is submitted
        if (isset($_POST['license_key']) && isset($_POST['license_email'])) {
            $license_key = sanitize_text_field($_POST['license_key']);
            $license_email = sanitize_text_field($_POST['license_email']);

            try {
                $this->_verify->activate($license_key, $license_email);
                $message = '<div class="notice notice-success"><p>License activated successfully.</p></div>';
            } catch (Exception $e) {
                $message = '<div class="notice notice-error"><p>'. esc_html($e->getMessage()) .'</p></div>';
            }
        }

        echo '<div class="wrap">';
        echo '<h2>Activate License</h2>';

        // Show error/success message
        if ($message) {
            echo $message;
        } else {
            echo '<p>Please enter your license key and email to activate your license.</p>';
        }

        echo '<form method="POST">';
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th scope="row"><label for="license_key">License Key</label></th>';
        echo '<td><input type="text" name="license_key" id="license_key" class="regular-text" value="'.$license_key.'" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th scope="row"><label for="license_email">Email</label></th>';
        echo '<td><input type="text" name="license_email" id="license_email" class="regular-text" value="'.$license_email.'" /></td>';
        echo '</tr>';
        echo '</table>';
        echo '<input type="submit" value="Activate" class="button button-primary">';

        echo '</form>';
        echo '</div>';
    }
}
