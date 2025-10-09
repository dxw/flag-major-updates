<?php

/**
 *
 * @package     WordPressPlugin
 * @author      dxw
 * @copyright   2020
 * @license     MIT
 *
 * @wordpress-plugin
 * Plugin Name: Flag Major Updates
 * Plugin URI: https://github.com/dxw/wordpress-plugin
 * Description: Optionally mark post updates as "major", and store the date of the last major update to a post.
 * Author: dxw
 * Version: 0.1.0
 */

$registrar = require __DIR__.'/src/load.php';
$registrar->register();
