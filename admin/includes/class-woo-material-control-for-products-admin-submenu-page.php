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
        $this->print_tabs();
        $this->page_title();
        $this->report_content();
    }

    private function get_active_tab(  $current = 'report'  ){
        if ( isset ( $_GET['tab'] ) ){
            return $_GET['tab'];
        }else{
            return 'report';
        }
    }
    private function print_tabs() {
        $current = $this->get_active_tab();
        $tabs = array( 'report' =>  __( 'Report about all inventory', 'woo-material-control-for-products' ), 'low_in_stock' =>  __( 'Low in stock', 'woo-material-control-for-products' ) );
        echo '<div id="icon-themes" class="icon32"><br></div>';
        echo '<h2 class="nav-tab-wrapper">';
        foreach( $tabs as $tab => $name ){
            $class = ( $tab == $current ) ? ' nav-tab-active' : '';
            echo "<a class='nav-tab$class' href='?page=material-control-inventory-report&tab=$tab'>$name</a>";

        }
        echo '</h2>';
    }

    private function page_title(){

        echo '<h1 class="inventory-report-title">'. __( 'Material control - Inventory report', 'woo-material-control-for-products' ) . '</h1>';

    }

    private function get_inventory_terms(){
        // Get all term ID's in a given taxonomy
        $taxonomy = 'inventory';
        $inventory_terms = get_terms( $taxonomy, array(
            'hide_empty' => 0,
            'fields' => 'ids'
        ) );
        return $inventory_terms;
    }

    private function report_table(){

        $inventory_terms = $this->get_inventory_terms();

        if( $inventory_terms ){
            echo '<table id="inventory-report-table" class="tablesorter">
                        <thead>
                        <tr>
                            <th>'. __( 'Name of Material', 'woo-material-control-for-products' ) .'</th>
                            <th>'. __( 'Quantity of Material', 'woo-material-control-for-products' ) .'</th>
                        </tr>
                        </thead>
                        <tbody>
            ';
                        foreach ( $inventory_terms as $inventory_item_id ){
                        $term_link = get_term_link($inventory_item_id);
                        $quantity_of_inventory_in_stock = carbon_get_term_meta( $inventory_item_id, 'quantity_of_inventory_in_stock' );
                        $quantity_of_inventory_for_low_in_stock = carbon_get_term_meta( $inventory_item_id, 'quantity_of_inventory_for_low_in_stock' );
                        if( $quantity_of_inventory_in_stock <= $quantity_of_inventory_for_low_in_stock ){
                            $low_in_stock_class = 'low_in_stock';
                        }else{
                            $low_in_stock_class = '';
                        }
                        echo '
                            <tr class="'.$low_in_stock_class.'">
                                <td><a href="'. $term_link .'" target="_blank">'. get_term( $inventory_item_id, 'inventory' )->name .'</a></td>
                                <td><a href="'. $term_link .'" target="_blank">'. $quantity_of_inventory_in_stock .'</a></td>
                            </tr>';

                        }
            echo '      </tbody>
                </table>';
        }
    }

    private function report_table_for_low_in_stock(){

        $inventory_terms = $this->get_inventory_terms();

        if( $inventory_terms ){
            echo '<table id="inventory-report-table" class="tablesorter">
                        <thead>
                        <tr>
                            <th>'. __( 'Name of Material', 'woo-material-control-for-products' ) .'</th>
                            <th>'. __( 'Quantity of Material', 'woo-material-control-for-products' ) .'</th>
                        </tr>
                        </thead>
                        <tbody>
            ';
            foreach ( $inventory_terms as $inventory_item_id ){
                $term_link = get_term_link($inventory_item_id);
                $quantity_of_inventory_in_stock = carbon_get_term_meta( $inventory_item_id, 'quantity_of_inventory_in_stock' );
                $quantity_of_inventory_for_low_in_stock = carbon_get_term_meta( $inventory_item_id, 'quantity_of_inventory_for_low_in_stock' );
                if( $quantity_of_inventory_in_stock >= $quantity_of_inventory_for_low_in_stock ){
                    continue;
                }
                echo '
                            <tr>
                                <td><a href="'. $term_link .'" target="_blank">'. get_term( $inventory_item_id, 'inventory' )->name .'</a></td>
                                <td><a href="'. $term_link .'" target="_blank">'. $quantity_of_inventory_in_stock .'</a></td>
                            </tr>';

            }
            echo '      </tbody>
                </table>';
        }
    }

    private function report_content(){
        $active_tab = $this->get_active_tab();

        if( $active_tab == 'low_in_stock' ){
            $this->report_table_for_low_in_stock();
        }else{
            $this->report_table();
        }
    }

}