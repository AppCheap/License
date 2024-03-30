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

use PHPUnit\Framework\TestCase;
use Appcheap\Model\PluginItem;

/**
 * The ClientTest
 *
 * @category PHP
 * @package  Appcheap
 * @author   ngocdt <ngocdt@rnlab.io>
 * @license  Appcheap License
 * @link     https://github.com/AppCheap/license
 */
class PluginItemTest extends TestCase
{
    /**
     * Test toObject
     *
     * @return void
     */
    public function testToObject()
    {
        $data = [
            'id' => 1,
            'slug' => 'test',
            'plugin' => 'test',
            'new_version' => '1.0.0',
            'url' => 'http://test.com',
            'package' => 'test',
            'icons' => ['test'],
            'banners' => ['test'],
            'banners_rtl' => ['test'],
            'tested' => '5.6',
            'requires_php' => '7.0',
            'compatibility' => ['5.6', '7.0']
        ];
        $pluginItem = new PluginItem($data);
        $this->assertEquals((object) $data, $pluginItem->toObject());
    }

    /**
     * Test hasNewVersion
     *
     * @return void
     */
    public function testHasNewVersion()
    {
        $data = [
            'id' => 1,
            'slug' => 'test',
            'plugin' => 'test',
            'new_version' => '1.0.1',
            'url' => 'http://test.com',
            'package' => 'test',
            'icons' => ['test'],
            'banners' => ['test'],
            'banners_rtl' => ['test'],
            'tested' => '5.6',
            'requires_php' => '7.0',
            'compatibility' => ['5.6', '7.0']
        ];
        $pluginItem = new PluginItem($data);
        $this->assertTrue($pluginItem->hasNewVersion('1.0.0'));
        $this->assertFalse($pluginItem->hasNewVersion('1.0.1'));
    }
}
