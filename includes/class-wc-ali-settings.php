<?php

/**
 * Settings for the plugin
 * 
 * @author    Younes DRO
 * @copyright Copyright (c) 2020, Younes DRO
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists ( 'WC_Ali_Settings' ) ) {

    /**
     * Save the Settings.
     * 
     * @class WC_Ali_Settings
     * @author Younes DRO <younesdro@gmail.com>
     * @version 1.0.0
     * @since 1.0.0
     */
    class WC_Ali_Settings {

        /**
         * Instance of WC_AliSettings class.
         *
         * @var obj 
         */
       

        public $option;
        /**
         * 
         * @param WC_Ali_Settings $wc_ali_settings Instance of WC_Ali_Settings class
         */
        public function __construct( ) {

            
            $this->option= get_option('dro_shipping_options');
            add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
            add_action( 'admin_init' , array( $this , 'register_settings_and_fields' ) );
            
        }

        public function add_submenu_page() {

            add_submenu_page( 'a2w_dashboard', esc_html__( 'Shipment-based products for Ali2Woo' , 'wc-ali-products-based-shipment' ), esc_html__( 'Shipment-based products' , 'wc-ali-products-based-shipment' ), 'manage_options' , __FILE__ , array( $this, 'wc_ali_settings_page' )
            );
            
        }
        
        public function register_settings_and_fields (){
            
            register_setting ( 'dro_shipping_options' , 'dro_shipping_options' );
            
            add_settings_section ( 'dro_shipping_section' , esc_html__( 'Main Settings' , 'wc-ali-products-based-shipment' ) , 
                    array ( $this , 'dro_shipping_section_cb'), __FILE__ );
            
            add_settings_field ( 'single_product' , esc_html__( 'Single Product', 'wc-ali-products-based-shipment' ),
                    array( $this , 'single_product' ) , __FILE__ , 'dro_shipping_section'  
                    );
        }     
        
        public function dro_shipping_section_cb (){
            
        }
        public function single_product( ) {
            echo "<input type='text' name='dro_shipping_options[single_product]' value='{$this->option['single_product']}' />";
        }       

        public function wc_ali_settings_page() {
            
            ?>
            <pre>
                <?php var_dump($this->option); ?>
            </pre>
            <div class="wrap wc-ali-settings">
                <h2>Shipment-based products for Ali2Woo</h2>
            <form name="dro" id="" method="post" action="options.php">
            
               <?php 
               settings_fields( 'dro_shipping_options' ); 
                do_settings_sections( __FILE__); 
                ?>
            
                <p class="submit">
                    <input name="submit" type="submit" class="button-primary" value="<?php esc_html_e('Save changes','woocommerce') ?>" />
                </p>
            </form>
            
           </div>
<?php
        }

    }
}
