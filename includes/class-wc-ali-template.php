<?php

/**
 * 
 */
if ( ! class_exists( 'WC_Ali_Template' ) ) {

    class WC_Ali_Template  {

        public function __construct() {

//            add_filter( 'woocommerce_product_add_to_cart_text' , array ( $this , 'wc_ali_custom_product_add_to_cart' ) );
            add_filter( 'woocommerce_loop_add_to_cart_link', array( $this , 'replace_default_button' ) );
            
        }
        public function wc_ali_custom_product_add_to_cart ( $text ){
            global $product;
            
            if ( ! isset ( $product) || !is_object( $product ) ) 
                return $text;
            
            return __('Custom text','woocommerce');
        }
        public function replace_default_button(){
            return '<button>Text a Dealer</button>';
        }        
    }

}