<?php
/**
 * Created by PhpStorm.
 * User: man2
 * Date: 21.12.2018
 * Time: 12:15
 */




class Woo_Material_Control_For_Products_Actions {

    public function __construct() {

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/class-woo-material-control-for-products-fields.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/class-woo-material-control-for-products-taxonomy.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/class-woo-material-control-for-products-buying.php';

    }

}


$Woo_Material_Control_For_Products_Actions = new Woo_Material_Control_For_Products_Actions();