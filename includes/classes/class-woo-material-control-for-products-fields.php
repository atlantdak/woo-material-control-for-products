<?php
/**
 * Created by PhpStorm.
 * User: man2
 * Date: 21.12.2018
 * Time: 12:59
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class Woo_Material_Control_For_Products_Fields{

    public function __construct() {

        add_action( 'carbon_fields_register_fields', array( $this, 'crb_attach_theme_options') );



        /*
         *нужно удалить need DEL
         add_action( 'carbon_fields_fields_registered', array( $this, 'test_func') );
        */
    }

    public function crb_attach_theme_options(){



        Container::make( 'post_meta', __( 'Quantity of Inventory', 'woo-material-control-for-products' ) )
            ->show_on_post_type( 'product' )// отобразим контейнер только на страницах (post_type=page)
            ->add_fields( array(
                Field::make( 'complex', 'composition_of_product', __( 'Composition Of Product', 'woo-material-control-for-products' ))
                    ->add_fields( array(
                        /*
                            Field::make("select", "crb_adv_side", "Расположение блока с рекламой")
                                ->add_options(array(
                                    'top' => 'Перед контентом',
                                    'sidebar' => 'В сайдбаре',
                                    'bottom' => 'После контента',
                                )),*/
                            Field::make('association', 'inventory_name', __( 'Name of Material', 'woo-material-control-for-products' ) )
                                ->set_max( 1 )
                                ->set_width( 33 )
                                ->set_types(array(
                                    array(
                                        'type' => 'term',
                                        'taxonomy' => 'inventory',
                                    ),
                                )),
                            Field::make( 'text', 'quantity_material_in_composition', __( 'Quantity of Material', 'woo-material-control-for-products' ) )
                                ->set_width( 33 )
                                ->set_attribute( 'placeholder', __( 'ml, gram, piece', 'woo-material-control-for-products' ) )
                                ->set_attribute( 'pattern', '[0-9]+' )
                                ->help_text('<p><i>'. __( 'quantity material in composition (ml, gram, piece)', 'woo-material-control-for-products' ) .'</i></p>'),
                        )
                    ),
            ) );

        Container::make( 'term_meta', __( 'Quantity in stock', 'woo-material-control-for-products' ) )
            ->show_on_taxonomy( 'inventory' ) // По умолчанию, можно не писать
            ->add_fields( array(
                Field::make( 'text', 'quantity_of_inventory_in_stock', __( 'Quantity of Inventory', 'woo-material-control-for-products' ) )
                ->set_attribute( 'placeholder', __(  'ml, gram, piece', 'woo-material-control-for-products' ) )
                ->set_attribute( 'pattern', '[0-9]+' )
                    ->help_text('<p><i>'. __( 'in stock (ml, gram, piece)', 'woo-material-control-for-products' ) .'</i></p>'),
            ) )
            ->add_fields( array(
                Field::make( 'text', 'quantity_of_inventory_for_low_in_stock', __( 'Quantity of Inventory for low in stock', 'woo-material-control-for-products' ) )
                    ->set_attribute( 'placeholder', __(  'ml, gram, piece', 'woo-material-control-for-products' ) )
                    ->set_attribute( 'pattern', '[0-9]+' )
                    ->help_text('<p><i>'. __( 'for low in stock (ml, gram, piece)', 'woo-material-control-for-products' ) .'</i></p>'),
            ) );


    }

    public function test_func(){


        $composition_of_products = carbon_get_post_meta( 379, 'composition_of_product',  __( 'Composition Of Product', 'woo-material-control-for-products' ) );

        if ( $composition_of_products ) {
            foreach ( $composition_of_products as $composition_of_product ) {

                $quantity_material_in_composition = $composition_of_product['quantity_material_in_composition'];
                $inventory_names= $composition_of_product['inventory_name'];

                // число, сколько едениц опции нужно для создания товара
                var_dump($quantity_material_in_composition);
                // число, сколько едениц всего доступно
                var_dump( carbon_get_term_meta($inventory_names[0]['id'], 'quantity_of_inventory_in_stock') );

            }
        }

    }


}


$Woo_Material_Control_For_Products_Fields = new Woo_Material_Control_For_Products_Fields();