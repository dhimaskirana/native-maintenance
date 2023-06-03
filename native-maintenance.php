<?php
/*
 * Plugin Name:       Native Maintenance
 * Plugin URI:        https://www.dhimaskirana/native-maintenance/
 * Description:       A super simple maintenance mode plugin using native maintenance mode WordPress.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Dhimas Kirana
 * Author URI:        https://www.dhimaskirana.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       native-maintenance
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('NATIVE_MAINTENANCE')) {

    class NATIVE_MAINTENANCE {

        var $plugin_version = '1.0.0';
        var $plugin_url;
        var $plugin_path;

        function __construct() {
            define('NATIVE_MAINTENANCE_VERSION', $this->plugin_version);
            define('NATIVE_MAINTENANCE_SITE_URL', site_url());
            define('NATIVE_MAINTENANCE_URL', $this->plugin_url());
            define('NATIVE_MAINTENANCE_PATH', $this->plugin_path());
            $this->plugin_includes();
        }

        function plugin_includes() {
            add_action('plugins_loaded', array($this, 'plugins_loaded_handler'));
            add_action('template_redirect', array($this, 'maintenance_page'));
        }

        function plugins_loaded_handler() {
            load_plugin_textdomain('native-maintenance', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        }

        function plugin_url() {
            if ($this->plugin_url) return $this->plugin_url;
            return $this->plugin_url = plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__));
        }

        function plugin_path() {
            if ($this->plugin_path) return $this->plugin_path;
            return $this->plugin_path = untrailingslashit(plugin_dir_path(__FILE__));
        }

        function is_valid_page() {
            return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
        }

        function maintenance_message() {
            return sprintf(
                /* translators: 1: Home url 2: Name of blog */
                __('<h1>Website Under Maintenance</h1><br /><a href="%1$s">%2$s</a> is currently undergoing scheduled maintenance.<br />We have got some exciting updates lined up for you. We will be back online!<br /><br />Thank you.', 'native-maintenance'),
                get_home_url(),
                get_bloginfo('name')
            );
        }

        function maintenance_title_page() {
            return __('Website Under Maintenance', 'native-maintenance');
        }

        function maintenance_args() {
            return array(
                'response' => 503,
                'back_link' => true
            );
        }

        function maintenance_page() {
            if (!is_admin() && !$this->is_valid_page()) {
                wp_die($this->maintenance_message(), $this->maintenance_title_page(), $this->maintenance_args());
            }
        }
    }

    $plugin = new NATIVE_MAINTENANCE();
}
