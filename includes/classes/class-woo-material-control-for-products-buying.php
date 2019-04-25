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

        add_action('woocommerce_add_to_cart', array( $this, 'add_reservation_for_inventory_when_add_to_cart'), 10, 6 );
        add_action('woocommerce_remove_cart_item', array( $this, 'remove_reservation_for_inventory_when_add_to_cart'), 10, 1 );
        add_action('woocommerce_after_cart_item_quantity_update', array( $this, 'update_reservation_for_inventory_when_add_to_cart'), 10, 4 );
        //Нужно использовать, когда обновляется карта до нуля, єтот хук, вместо верхнего,
        //но єто не точно, я еще не проверяд woocommerce_before_cart_item_quantity_zero

    }



    public function action_woocommerce_product_set_stock_status( $stock_status, $product ) {
    // обьект товара в котором вызван метод данный
    //var_dump($product);
        //id товара
    //var_dump( $product->get_id() );

        $stock_status = $this->get_stock_status_by_inventory( $stock_status, $product->get_id() );

        return $stock_status;
    }

    /*
     * Получаю значение того, сколько материалов зарезервировано,
     * т.е. сколько необходимо материала для уже добавленых товаров
     **/
    private function get_quantity_of_inventory_in_reservation( $inventory_term_id ){
        if( isset( $_COOKIE['inventory_in_cart_' . $inventory_term_id ] ) ){
            return $_COOKIE['inventory_in_cart_' . $inventory_term_id ];
        }else {
            return 0;
        }
    }

    private function get_stock_status_by_inventory( $stock_status, $product_id ){

        $composition_of_products = carbon_get_post_meta( $product_id, 'composition_of_product' );

        if ( $composition_of_products ) {
            foreach ( $composition_of_products as $composition_of_product ) {

                $quantity_material_in_composition = $composition_of_product['quantity_material_in_composition'];

                $inventory_names= $composition_of_product['inventory_name'];

                // получию количество зарезервированного материала
                $quantity_of_inventory_in_reservation = $this->get_quantity_of_inventory_in_reservation($inventory_names[0]['id']);
                // отнимаю от количества запасов, кол-во зарезервированных запасов
                $quantity_of_inventory_in_stock = carbon_get_term_meta($inventory_names[0]['id'], 'quantity_of_inventory_in_stock') - $quantity_of_inventory_in_reservation;

                // if one of the components is out of stock, then stock_status return to false
                if( $quantity_of_inventory_in_stock < 0){
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

        //отнимаю количество запасов
        $this->set_inventory_quantity_after_buying($product_id);
        //удаляю резервацию запасов
        $this->remove_all_reservation_for_inventory_when_checkout_succes();
    }

    private function remove_all_reservation_for_inventory_when_checkout_succes(){

        $cookie_name = 'inventory_in_cart_';
        foreach ($_COOKIE as $name => $value) {
            if (stripos($name,$cookie_name) === 0) {
                wc_setcookie($name, $value,  time() - 60 * 60 * 24 * 2 );
                $cookies[] = $name;
            }
        }

    }

    public function add_reservation_for_inventory_when_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ){
        /*
         * If user add product to cart then inventory of material save to your cookies
         * and next product which constructed with that materials will consider material consumption
         */

        /*Check all user cart and setup inventory quantity to cookies */
        foreach( WC()->cart->get_cart() as $cart_item ){
            $product_id = $cart_item['product_id'];
            $product_quantity = $cart_item['quantity'];


            $composition_of_products = carbon_get_post_meta( $product_id, 'composition_of_product' );

            if ( $composition_of_products ) {
                foreach ( $composition_of_products as $composition_of_product ) {

                    $quantity_material_in_composition = $composition_of_product['quantity_material_in_composition'];

                    $inventory_names = $composition_of_product['inventory_name'];

                    // умножаю количество продуктов в корзине на количество материала для одного продукта
                    $inventory_quantity_reservation_for_item = $product_quantity * $quantity_material_in_composition;
                    /* если в корзине(запомнило в кукисах) уже зарезервирован этот материал,
                     * то суммирую зарезервированные ранее с зарезервированными сейчас материалами
                     */
                    if( isset($_COOKIE[ 'inventory_in_cart_' . $inventory_names[0]['id'] ]) ){
                        $inventory_quantity_reservation = $_COOKIE[ 'inventory_in_cart_' . $inventory_names[0]['id'] ] + $inventory_quantity_reservation_for_item;
                    }else{
                        $inventory_quantity_reservation = $inventory_quantity_reservation_for_item;
                    }
                    /* запоминаю добавленные в корзину резервы по материалам, связь по id терминов */
                    wc_setcookie('inventory_in_cart_' . $inventory_names[0]['id'], $inventory_quantity_reservation, time() + 60 * 60 * 24 * 2 );
                }
            }
        }


    }

    public function remove_reservation_for_inventory_when_add_to_cart( $cart_item_key ){

        $cart_item = WC()->cart->get_cart_item( $cart_item_key );
        $product_id = $cart_item['product_id'];
        $product_quantity = $cart_item['quantity'];



        $composition_of_products = carbon_get_post_meta( $product_id, 'composition_of_product' );

        if ( $composition_of_products ) {
            foreach ( $composition_of_products as $composition_of_product ) {

                $quantity_material_in_composition = $composition_of_product['quantity_material_in_composition'];

                $inventory_names = $composition_of_product['inventory_name'];

                // умножаю количество продуктов в корзине на количество материала для одного продукта
                $inventory_quantity_reservation_for_item = $product_quantity * $quantity_material_in_composition;
                /* если в корзине(запомнило в кукисах) уже зарезервирован этот материал,
                 * то суммирую зарезервированные ранее с зарезервированными сейчас материалами
                 */
                if( isset($_COOKIE[ 'inventory_in_cart_' . $inventory_names[0]['id'] ]) ){
                    $inventory_quantity_reservation = $_COOKIE[ 'inventory_in_cart_' . $inventory_names[0]['id'] ] - $inventory_quantity_reservation_for_item;

                    /* запоминаю добавленные в корзину резервы по материалам, связь по id терминов */
                    wc_setcookie('inventory_in_cart_' . $inventory_names[0]['id'], $inventory_quantity_reservation, time() + 60 * 60 * 24 * 2 );
                }
            }
        }
    }

    public function update_reservation_for_inventory_when_add_to_cart( $cart_item_key, $quantity, $old_quantity, $cart ){

        $cart_item = WC()->cart->get_cart_item( $cart_item_key );
        $product_id = $cart_item['product_id'];

        if($quantity > $old_quantity){
            $product_quantity = $quantity - $old_quantity;
        }else{
            $product_quantity = $old_quantity - $quantity;
        }


        $composition_of_products = carbon_get_post_meta( $product_id, 'composition_of_product' );

        if ( $composition_of_products ) {
            foreach ( $composition_of_products as $composition_of_product ) {

                $quantity_material_in_composition = $composition_of_product['quantity_material_in_composition'];

                $inventory_names = $composition_of_product['inventory_name'];

                // умножаю количество продуктов в корзине на количество материала для одного продукта
                $inventory_quantity_reservation_for_item = $product_quantity * $quantity_material_in_composition;
                /* если в корзине(запомнило в кукисах) уже зарезервирован этот материал,
                 * то суммирую зарезервированные ранее с зарезервированными сейчас материалами
                 */
                if( isset($_COOKIE[ 'inventory_in_cart_' . $inventory_names[0]['id'] ]) ){
                    if($quantity > $old_quantity) {
                        //Если увеличили кол-во продукта
                        $inventory_quantity_reservation = $_COOKIE['inventory_in_cart_' . $inventory_names[0]['id']] + $inventory_quantity_reservation_for_item;
                    }else{
                        //Если уменьшили кол-во продукта
                        $inventory_quantity_reservation = $_COOKIE['inventory_in_cart_' . $inventory_names[0]['id']] - $inventory_quantity_reservation_for_item;
                    }
                    /* запоминаю добавленные в корзину резервы по материалам, связь по id терминов */
                    wc_setcookie('inventory_in_cart_' . $inventory_names[0]['id'], $inventory_quantity_reservation, time() + 60 * 60 * 24 * 2 );
                }
            }
        }
    }
}


$Woo_Material_Control_For_Products_Buying = new Woo_Material_Control_For_Products_Buying();
