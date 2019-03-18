<?php
/**
 * Created by PhpStorm.
 * User: man2
 * Date: 21.12.2018
 * Time: 17:39
 */

class Woo_Material_Control_For_Products_Buying{

    public function __construct() {

        //add_action( 'carbon_fields_register_fields', array( $this, 'crb_attach_theme_options') );



        // add the action
        add_action( 'woocommerce_product_is_in_stock', array( $this, 'action_woocommerce_product_set_stock_status'), 10, 2 );


        // two did not work, the third earned,
        // when adding an order, change quantity of inventory
        //add_action( 'woocommerce_new_order', array( $this, 'woocommerce_order_status_completed'), 10, 1 );
        //add_action( 'woocommerce_resume_order', array( $this, 'woocommerce_order_status_completed'), 10, 1 );
        add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'woocommerce_order_status_completed'), 10, 1 );



//        add_action( 'init', array( $this, 'test'));
    }



    public function action_woocommerce_product_set_stock_status( $stock_status, $product ) {
    // обьект товара в котором вызван метод данный
    //var_dump($product);
        //id товара
    //var_dump( $product->get_id() );

        $stock_status = $this->get_stock_status_by_inventory( $stock_status, $product->get_id() );

        return $stock_status;
    }


    private function get_stock_status_by_inventory( $stock_status, $product_id ){

        $composition_of_products = carbon_get_post_meta( $product_id, 'composition_of_product' );

        if ( $composition_of_products ) {
            foreach ( $composition_of_products as $composition_of_product ) {

                $quantity_material_in_composition = $composition_of_product['quantity_material_in_composition'];

                $inventory_names= $composition_of_product['inventory_name'];
                $quantity_of_inventory_in_stock = carbon_get_term_meta($inventory_names[0]['id'], 'quantity_of_inventory_in_stock');

                // if one of the components is out of stock, then stock_status return to false
                if( $quantity_of_inventory_in_stock - $quantity_material_in_composition < 0){
                    return false;
                }

            }
        }


        return $stock_status;
    }

    private function set_inventory_quantity_after_buying( $product_id ){

        $composition_of_products = carbon_get_post_meta( $product_id, 'composition_of_product' );

        if ( $composition_of_products ) {
            foreach ( $composition_of_products as $composition_of_product ) {

                $quantity_material_in_composition = $composition_of_product['quantity_material_in_composition'];

                $inventory_names = $composition_of_product['inventory_name'];
                $quantity_of_inventory_in_stock = carbon_get_term_meta($inventory_names[0]['id'], 'quantity_of_inventory_in_stock');

                // if one of the components is out of stock, then stock_status return to false
                if( $quantity_of_inventory_in_stock - $quantity_material_in_composition >= 0){

                    $quantity_of_inventory_in_stock_after_buying = $quantity_of_inventory_in_stock - $quantity_material_in_composition;
                    carbon_set_term_meta( $inventory_names[0]['id'], 'quantity_of_inventory_in_stock', $quantity_of_inventory_in_stock_after_buying );

                }

            }
        }

    }

    public function woocommerce_order_status_completed( $order_id ) {
        // do action when user do order
        $order = wc_get_order( $order_id );
        $items = $order->get_items();

        foreach ( $items as $item ) {
        //    $product_name = $item->get_name();
            $product_id = $item->get_product_id();
            $product_variation_id = $item->get_variation_id();
        }

        $this->set_inventory_quantity_after_buying($product_id);

    }

    /*
    public function test(){

            $order_id = 625;

            $order = wc_get_order( $order_id );
            $items = $order->get_items();

            foreach ( $items as $item ) {
                //    $product_name = $item->get_name();
                $product_id = $item->get_product_id();
                $product_variation_id = $item->get_variation_id();
            }

            $this->set_inventory_quantity_after_buying($product_id);

    }
    */
}


$Woo_Material_Control_For_Products_Buying = new Woo_Material_Control_For_Products_Buying();



