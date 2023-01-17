<?php
/**
 * Plugin Name:       Unimarken Plugin
 * Plugin URI:        
 * Description:       
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Agencia Digital SP
 * Author URI:        
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        
 * Text Domain:       unimarkentd
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Name of this file with its URL
 * return /home/unimarken/www/wp-content/plugins/unimarken/unimarken.php
 */
if ( ! defined( 'unimarken_name_file_plugin' ) ) define( 'unimarken_name_file_plugin', __FILE__ );

/**
 * Name of this plugin folder and its URL
 * return https://unimarken.com/wp-content/plugins/unimarken/
 */
if ( ! defined( 'unimarken_url_plugin' ) ) define( 'unimarken_url_plugin', plugins_url( '/', __FILE__ ) );

/**
 * Name of this plugin folder and its DIR
 * return: /home/unimarken/www/wp-content/plugins/unimarken/
 */
if ( ! defined( 'unimarken_name_dir' ) ) define( 'unimarken_name_dir', plugin_dir_path( __FILE__ ) );

require_once 'lib/vendor/autoload.php';

require_once 'inc/enqueues_script.php';

require_once 'inc/functions.php';

require_once 'inc/cpt.php';

require_once 'inc/metaboxes.php';

require_once 'inc/shortcodes.php';

require_once 'inc/taxonomies.php';

require_once 'inc/ajax.php';

require_once 'woo/my_account.php';

require_once 'woo/deactivations.php';