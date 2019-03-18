<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Woo_Material_Control_For_Products
 * @subpackage Woo_Material_Control_For_Products/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woo_Material_Control_For_Products
 * @subpackage Woo_Material_Control_For_Products/includes
 * @author     Kishkin Dmitriy (atlantdak) <atlantdak@mail.ru>
 */
class Woo_Material_Control_For_Products_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woo-material-control-for-products',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);


		/** закомментировал, потому что пока-что
        load_plugin_textdomain( 'carbon-fields',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/carbon-fields/vendor/htmlburger/carbon-fields/languages/'
        );
        */
    }



}
