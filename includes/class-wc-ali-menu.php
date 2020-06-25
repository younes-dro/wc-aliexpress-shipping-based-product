<?php

/**
 * 
 */
if (!class_exists('WC_Ali_Menu')) {

    class WC_Ali_Menu  {

        public function __construct() {

            add_action( 'admin_menu' , array( $this, 'add_submenu_page' ) );
            
        }

        public function add_submenu_page ( ){
            
            add_submenu_page ( 'a2w_dashboard' , esc_html__( 'Ali2woo - Products based shipment', 'wc-ali-products-based-shipment' ), esc_html__( 'Products based shipment', 'wc-ali-products-based-shipment' ) , 'manage_options' , __FILE__ , '');
        }

    }

}