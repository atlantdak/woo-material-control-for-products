<?php

/**
 * @link              #
 * @since             1.0.0
 * @package           Woo_Material_Control_For_Products
 *
 * @wordpress-plugin
 * Plugin Name:       Woo material control for products
 * Plugin URI:        https://github.com/atlantdak/woo-material-control-for-products
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Kishkin Dmitriy (atlantdak)
 * Author URI:        https://github.com/atlantdak
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-material-control-for-products
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WMCFP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-material-control-for-products-activator.php
 */
function activate_woo_material_control_for_products() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-material-control-for-products-activator.php';
	Woo_Material_Control_For_Products_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-material-control-for-products-deactivator.php
 */
function deactivate_woo_material_control_for_products() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-material-control-for-products-deactivator.php';
	Woo_Material_Control_For_Products_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woo_material_control_for_products' );
register_deactivation_hook( __FILE__, 'deactivate_woo_material_control_for_products' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-material-control-for-products.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woo_material_control_for_products() {

	$plugin = new Woo_Material_Control_For_Products();
	$plugin->run();

}
run_woo_material_control_for_products();
