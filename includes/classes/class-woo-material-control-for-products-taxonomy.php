<?php
/**
 * Created by PhpStorm.
 * User: man2
 * Date: 21.12.2018
 * Time: 13:14
 */

class Woo_Material_Control_For_Products_Taxonomy{


    public function __construct() {

        add_action( 'init', array( $this, 'custom_taxonomy_Item') );

    }



    public function custom_taxonomy_Item()  {


        $labels = array(
            'name'                       => __( 'Inventories', 'woo-material-control-for-products' ),
            'singular_name'              => __( 'Inventory', 'woo-material-control-for-products' ),
            'menu_name'                  => __( 'Inventory', 'woo-material-control-for-products' ),
            'all_items'                  => __( 'All Inventory', 'woo-material-control-for-products' ),
            'parent_item'                => __( 'Parent Inventory', 'woo-material-control-for-products' ),
            'parent_item_colon'          => __( 'Parent Inventory:', 'woo-material-control-for-products' ),
            'new_item_name'              => __( 'New Inventory Name', 'woo-material-control-for-products' ),
            'add_new_item'               => __( 'Add New Inventory', 'woo-material-control-for-products' ),
            'edit_item'                  => __( 'Edit Inventory', 'woo-material-control-for-products' ),
            'update_item'                => __( 'Update Inventory', 'woo-material-control-for-products' ),
            'separate_items_with_commas' => __( 'Separate Inventory with commas', 'woo-material-control-for-products' ),
            'search_items'               => __( 'Search Inventory', 'woo-material-control-for-products' ),
            'add_or_remove_items'        => __( 'Add or remove Inventory', 'woo-material-control-for-products' ),
            'choose_from_most_used'      => __( 'Choose from the most used Inventory', 'woo-material-control-for-products' ),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => false,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => false,
        );
        register_taxonomy( 'inventory', 'product', $args );
        register_taxonomy_for_object_type( 'inventory', 'product' );


    }


}


$Woo_Material_Control_For_Products_Taxonomy = new Woo_Material_Control_For_Products_Taxonomy();