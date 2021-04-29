<?php

/**
 * Plugin Name: P2Pet Shipping
 * Description: Custom Shipping Method for WooCommerce
 * Version: 1.0.0
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'WPINC' ) ) {

    die;

}

/*
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    function p2pet_shipping_method() {
        if ( ! class_exists( 'P2Pet_Shipping_Method' ) ) {
            class P2Pet_Shipping_Method extends WC_Shipping_Method {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct() {
                    $this->id                 = 'p2pet_shipping';
                    $this->method_title       = __( 'P2Pet Доставка', 'p2pet_shipping' );
                    $this->method_description = __( 'Custom Shipping Method for P2Pet', 'p2pet_shipping' );

                    // Availability & Countries
                    $this->availability = 'including';
                    $this->countries = array(
                        'RU', // Russian Federation
                    );

                    $this->init();

                    $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'P2Pet Доставка', 'p2pet_shipping' );
                }

                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init() {
                    // Load the settings API
                    $this->init_form_fields();
                    $this->init_settings();

                    // Save settings in admin if you have any defined
                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                }

                /**
                 * Define settings field for this shipping
                 * @return void
                 */
                function init_form_fields() {

                    $this->form_fields = array(

                        'enabled' => array(
                            'title' => __( 'Enable', 'p2pet_shipping' ),
                            'type' => 'checkbox',
                            'description' => __( 'Enable this shipping.', 'p2pet_shipping' ),
                            'default' => 'yes'
                        ),

                        'title' => array(
                            'title' => __( 'Title', 'p2pet_shipping' ),
                            'type' => 'text',
                            'description' => __( 'Title to be display on site', 'p2pet_shipping' ),
                            'default' => __( 'P2Pet Доставка', 'p2pet_shipping' )
                        ),

                        'weight' => array(
                            'title' => __( 'Weight (kg)', 'p2pet_shipping' ),
                            'type' => 'number',
                            'description' => __( 'Maximum allowed weight', 'p2pet_shipping' ),
                            'default' => 100
                        ),

                    );
                }

                /**
                 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping( $package ) {

                    $weight = 0;
                    $cost = 0;
                    //$country = $package["destination"]["country"];
                    $address = '';
                    if ($package['destination']['city'])
                        $address = $package['destination']['city'];
                    $response = wp_remote_post('https://unizoo.ru/service/export/dpd_tarif/?curl=Y&username=p2pet@ya.ru&password=6YJLH78v23SB');
                    $body = wp_remote_retrieve_body( $response );
                    foreach (json_decode($body) as $item) {
                        $locality = mb_strtolower($item->LocalityName);
                        if ( $locality == $address ) {
                            $result = $item;
                            $cost = $item->baseDeliveryPrice;
                            $costDop = $item->priceDop;
                            $costDop1 = $item->priceDop1;
                            $costDop2 = $item->priceDop2;
                            $costDop3 = $item->priceDop3;
                            $costDop4 = $item->priceDop4;

                            $subtotal = WC()->cart->get_subtotal();
                            if ($subtotal > 0 && $subtotal <= 1999)
                                $cost = $cost + $costDop;
                            elseif ($subtotal >= 2000 && $subtotal <= 3499)
                                $cost = $cost + $costDop1;
                            elseif ($subtotal >= 3500 && $subtotal <= 4999)
                                $cost = $cost + $costDop2;
                            elseif ($subtotal >= 5000 && $subtotal <= 7999)
                                $cost = $cost + $costDop3;
                            elseif ($subtotal >= 8000)
                                $cost = $cost + $costDop4;
                        }
                    }

                    foreach ( $package['contents'] as $item_id => $values )
                    {
                        $_product = $values['data'];
                        $weight = $weight + $_product->get_weight() * $values['quantity'];
                    }
                    if ($weight > 5) {
                        $add_weight = round($weight - 5);
                        $cost = $cost + $add_weight * 10;
                    }
                    if ($cost <= 0)
                        $cost = 0;
                    $rate = array(
                        'id' => $this->id,
                        'label' => $this->title,
                        'cost' => $cost
                    );

                    $this->add_rate( $rate );
                }
            }
        }
    }

    add_action( 'woocommerce_shipping_init', 'p2pet_shipping_method' );

    function add_p2pet_shipping_method( $methods ) {
        $methods[] = 'P2Pet_Shipping_Method';
        return $methods;
    }

    add_filter( 'woocommerce_shipping_methods', 'add_p2pet_shipping_method' );

    function p2pet_validate_order( $posted )   {

        $packages = WC()->shipping->get_packages();

        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

        if( is_array( $chosen_methods ) && in_array( 'p2pet_shipping', $chosen_methods ) ) {

            foreach ( $packages as $i => $package ) {

                if ( $chosen_methods[ $i ] != "p2pet_shipping" ) {

                    continue;

                }

                $P2Pet_Shipping_Method = new P2Pet_Shipping_Method();
                $weightLimit = (int) $P2Pet_Shipping_Method->settings['weight'];
                $weight = 0;

                foreach ( $package['contents'] as $item_id => $values )
                {
                    $_product = $values['data'];
                    $weight = $weight + $_product->get_weight() * $values['quantity'];
                }

                $weight = wc_get_weight( $weight, 'kg' );

                if( $weight > $weightLimit ) {

                    $message = sprintf( __( 'Sorry, %d kg exceeds the maximum weight of %d kg for %s', 'p2pet_shipping' ), $weight, $weightLimit, $P2Pet_Shipping_Method->title );

                    $messageType = "error";

                    if( ! wc_has_notice( $message, $messageType ) ) {

                        wc_add_notice( $message, $messageType );

                    }
                }
            }
        }
    }

    add_action( 'woocommerce_review_order_before_cart_contents', 'p2pet_validate_order' , 10 );
    add_action( 'woocommerce_after_checkout_validation', 'p2pet_validate_order' , 10 );

    function p2pet_pickuping_method() {
        if ( ! class_exists( 'P2Pet_Pickuping_Method' ) ) {
            class P2Pet_Pickuping_Method extends WC_Shipping_Method {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct() {
                    $this->id                 = 'p2pet_pickuping';
                    $this->method_title       = __( 'P2Pet Самовывоз', 'p2pet_pickuping' );
                    $this->method_description = __( 'Custom Pickuping Method for P2Pet', 'p2pet_pickuping' );

                    // Availability & Countries
                    $this->availability = 'including';
                    $this->countries = array(
                        'RU', // Russian Federation
                    );

                    $this->init();

                    $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'P2Pet Самовывоз', 'p2pet_pickuping' );
                }

                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init() {
                    // Load the settings API
                    $this->init_form_fields();
                    $this->init_settings();

                    // Save settings in admin if you have any defined
                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                }

                /**
                 * Define settings field for this shipping
                 * @return void
                 */
                function init_form_fields() {

                    $this->form_fields = array(

                        'enabled' => array(
                            'title' => __( 'Enable', 'p2pet_pickuping' ),
                            'type' => 'checkbox',
                            'description' => __( 'Enable this shipping.', 'p2pet_pickuping' ),
                            'default' => 'yes'
                        ),

                        'title' => array(
                            'title' => __( 'Title', 'p2pet_pickuping' ),
                            'type' => 'text',
                            'description' => __( 'Title to be display on site', 'p2pet_pickuping' ),
                            'default' => __( 'P2Pet Самовывоз', 'p2pet_pickuping' )
                        ),

                        'weight' => array(
                            'title' => __( 'Weight (kg)', 'p2pet_pickuping' ),
                            'type' => 'number',
                            'description' => __( 'Maximum allowed weight', 'p2pet_pickuping' ),
                            'default' => 100
                        ),

                    );
                }

                /**
                 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping( $package ) {

                    $weight = 0;
                    $cost = 0;
                    //$country = $package["destination"]["country"];
                    $subtotal = WC()->cart->get_subtotal();
                    foreach ( $package['contents'] as $item_id => $values )
                    {
                        $_product = $values['data'];
                        $weight = $weight + $_product->get_weight() * $values['quantity'];
                    }

                    $address = '';
                    if ($package['destination']['city'])
                        $address = $package['destination']['city'];
                    $response = wp_remote_post('https://unizoo.ru/service/export/dpd_tarif/?curl=Y&username=p2pet@ya.ru&password=6YJLH78v23SB');
                    $body = wp_remote_retrieve_body( $response );
                    foreach (json_decode($body) as $item) {
                        $locality = mb_strtolower($item->LocalityName);
                        if ($locality  == $address) {
                            $result = $item;
                            $cost = $item->basePickupPrice;
                            $costDop = $item->priceDop;
                            $costDop1 = $item->priceDop1;
                            $costDop2 = $item->priceDop2;
                            $costDop3 = $item->priceDop3;
                            $costDop4 = $item->priceDop4;

                            $subtotal = WC()->cart->get_subtotal();
                            if ($subtotal > 0 && $subtotal <= 1999)
                                $cost = $cost + $costDop;
                            elseif ($subtotal >= 2000 && $subtotal <= 3499)
                                $cost = $cost + $costDop1;
                            elseif ($subtotal >= 3500 && $subtotal <= 4999)
                                $cost = $cost + $costDop2;
                            elseif ($subtotal >= 5000 && $subtotal <= 7999)
                                $cost = $cost + $costDop3;
                            elseif ($subtotal >= 8000)
                                $cost = $cost + $costDop4;

                        }
                    }
                    if ($weight > 5) {
                        $add_weight = round($weight - 5);
                        $cost = $cost + $add_weight * 10;
                    }
                    if ($cost <= 0)
                        $cost = 0;
                    $rate = array(
                        'id' => $this->id,
                        'label' => $this->title,
                        'cost' => $cost
                    );

                    $this->add_rate( $rate );
                }
            }
        }
    }

    add_action( 'woocommerce_shipping_init', 'p2pet_pickuping_method' );

    function add_p2pet_pickuping_method( $methods ) {
        $methods[] = 'P2Pet_Pickuping_Method';
        return $methods;
    }

    add_filter( 'woocommerce_shipping_methods', 'add_p2pet_pickuping_method' );

    function p2pet_validate_pickup_order( $posted )   {

        $packages = WC()->shipping->get_packages();

        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

        if( is_array( $chosen_methods ) && in_array( 'p2pet_pickuping', $chosen_methods ) ) {

            foreach ( $packages as $i => $package ) {

                if ( $chosen_methods[ $i ] != "p2pet_pickuping" ) {

                    continue;

                }

                $P2Pet_Pickuping_Method = new P2Pet_Pickuping_Method();
                $weightLimit = (int) $P2Pet_Pickuping_Method->settings['weight'];
                $weight = 0;

                foreach ( $package['contents'] as $item_id => $values )
                {
                    $_product = $values['data'];
                    $weight = $weight + $_product->get_weight() * $values['quantity'];
                }

                $weight = wc_get_weight( $weight, 'kg' );

                if( $weight > $weightLimit ) {

                    $message = sprintf( __( 'Sorry, %d kg exceeds the maximum weight of %d kg for %s', 'p2pet_pickuping' ), $weight, $weightLimit, $P2Pet_Pickuping_Method->title );

                    $messageType = "error";

                    if( ! wc_has_notice( $message, $messageType ) ) {

                        wc_add_notice( $message, $messageType );

                    }
                }
            }
        }
    }

    add_action( 'woocommerce_review_order_before_cart_contents', 'p2pet_validate_pickup_order' , 10 );
    add_action( 'woocommerce_after_checkout_validation', 'p2pet_validate_pickup_order' , 10 );

}
