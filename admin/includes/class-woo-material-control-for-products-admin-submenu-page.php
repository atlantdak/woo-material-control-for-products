<?php
/**
 * Created by PhpStorm.
 * User: man2
 * Date: 22.12.2018
 * Time: 09:32
 */

class Woo_Material_Control_For_Products_Admin_Submenu_Page{


    /**
     * This function renders the contents of the page associated with the Submenu
     * that invokes the render method. In the context of this plugin, this is the
     * Submenu class.
     */
    public function render() {

        $this->page_title();
        $this->report_table();
    }

    private function page_title(){

        echo '<h1>'. __( 'Material control - Inventory report', 'woo-material-control-for-products' ) . '</h1>';

    }

    private function report_table(){

// Get all term ID's in a given taxonomy
        $taxonomy = 'inventory';
        $inventory_terms = get_terms( $taxonomy, array(
            'hide_empty' => 0,
            'fields' => 'ids'
        ) );

// Use the new tax_query WP_Query argument (as of 3.1)
        $inventory_query = new WP_Query( array(
            'tax_query' => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field' => 'id',
                    'terms' => $inventory_terms,
                ),
            ),
        ) );
//var_dump($inventory_query);
        if ( $inventory_query ) {
            foreach ($inventory_query as $inventory) {

               var_dump($inventory['tax_query']);

            }
        }


    }



}