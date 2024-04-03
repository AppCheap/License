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

namespace Appcheap\Model;

/**
 * The PluginItem
 *
 * @category PHP
 * @package  Appcheap
 * @author   ngocdt <ngocdt@rnlab.io>
 * @license  Appcheap License
 * @link     https://github.com/AppCheap/license
 */
class PluginItem
{
    public $id;
    public $slug;
    public $plugin;
    public $new_version;
    public $url;
    public $package;
    public $icons;
    public $banners;
    public $banners_rtl;
    public $tested;
    public $requires_php;
    public $compatibility;

    /**
     * Construct the PluginItem.
     *
     * @param array $data The data of the plugin.
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->slug = $data['slug'];
        $this->plugin = $data['plugin'];
        $this->new_version = $data['new_version'];
        $this->url = $data['url'];
        $this->package = $data['package'];
        $this->icons = $data['icons'];
        $this->banners = isset($data['banners']) ? $data['banners'] : [];
        $this->banners_rtl = isset($data['banners_rtl']) ? $data['banners_rtl'] : [];
        $this->tested = $data['tested'];
        $this->requires_php = $data['requires_php'];
        $this->compatibility = isset($data['compatibility']) ? $data['compatibility'] : [];
    }

    /**
     * Convert the PluginItem to object.
     *
     * @return object
     */
    public function toObject()
    {
        return (object) [
            'id' => $this->id,
            'slug' => $this->slug,
            'plugin' => $this->plugin,
            'new_version' => $this->new_version,
            'url' => $this->url,
            'package' => $this->package,
            'icons' => $this->icons,
            'banners' => $this->banners,
            'banners_rtl' => $this->banners_rtl,
            'tested' => $this->tested,
            'requires_php' => $this->requires_php,
            'compatibility' => $this->compatibility,
        ];
    }

    /**
     * Check if the plugin has new version.
     *
     * @param string $old_version The old version.
     * 
     * @return boolean
     */
    public function hasNewVersion($old_version)
    {
        return version_compare($old_version, $this->new_version, '<');
    }
}
