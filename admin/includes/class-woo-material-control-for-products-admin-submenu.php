<?php
/**
 * Creates the submenu item for the plugin.
 *
 * @package Custom_Admin_Settings
 */

/**
 * Creates the submenu item for the plugin.
 *
 * Registers a new menu item in Woocommerce item and uses the dependency passed into
 * the constructor in order to display the page corresponding to this menu item.
 * */

class Woo_Material_Control_For_Products_Admin_Submenu{


    /**
     * A reference the class responsible for rendering the submenu page.
     *
     * @var    Submenu_Page
     * @access private
     */
    private $submenu_page;

    /**
     * Initializes all of the partial classes.
     *
     * @param Submenu_Page $submenu_page A reference to the class that renders the
     *                                                                   page for the plugin.
     */
    public function __construct( $submenu_page ) {
        $this->submenu_page = $submenu_page;
    }

    /**
     * Adds a submenu for this plugin to the 'Tools' menu.
     */
    public function init() {
        add_action( 'admin_menu', array( $this, 'add_options_page' ) );
    }

    /**
     * Creates the submenu item and calls on the Submenu Page object to render
     * the actual contents of the page.
     */
    public function add_options_page() {

        add_submenu_page(
            'woocommerce',
            __( 'Material control - Inventory report', 'woo-material-control-for-products' ),
            __( 'Inventory report', 'woo-material-control-for-products' ),
            'manage_options',
            'material-control-inventory-report',
            array( $this->submenu_page, 'render' )
        );
    }


}