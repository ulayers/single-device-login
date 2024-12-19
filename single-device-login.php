<?php
/**
 *
 * @link              https://ulayers.com/
 *
 * @wordpress-plugin
 * Plugin Name:       Single Device Login
 * Plugin URI:        https://ulayers.com/
 * Description:       Restricts user logins to a single device, enhancing security and preventing unauthorized account sharing.
 * Version:           1.0.0
 * Author:            LAYERS
 * Author URI:        https://ulayers.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sdl-trans
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
    die;
}

if (!function_exists('is_plugin_active')) {
    require_once ABSPATH . '/wp-admin/includes/plugin.php';
}

require_once plugin_dir_path(__FILE__) . 'includes/class-single-device-login.php';

new Single_Device_Login();