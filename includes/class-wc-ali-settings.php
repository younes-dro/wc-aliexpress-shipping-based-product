<?php
/**
 * Settings for the plugin
 * 
 * @author    Younes DRO
 * @copyright Copyright (c) 2020, Younes DRO
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( !class_exists( 'WC_Ali_Settings' ) ) {

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
         * The default message to display.
         * 
         * @var string 
         */
        private $default_message = 'This item cannot be delivered to the selected address. Please choose a different address.';

        /**
         * 
         * @param WC_Ali_Settings $wc_ali_settings Instance of WC_Ali_Settings class
         */
        public function __construct() {


            $this->option = get_option( 'dro_shipping_options' );
            add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
            add_action( 'admin_init', array( $this, 'register_settings_and_fields' ) );
        }

        public function add_submenu_page() {

            add_submenu_page( 'a2w_dashboard', esc_html__( 'Shipment-based products for Ali2Woo', 'wc-ali-products-based-shipment' ), esc_html__( 'Shipment-based products', 'wc-ali-products-based-shipment' ), 'manage_options', __FILE__, array( $this, 'wc_ali_settings_page' )
            );
        }

        public function register_settings_and_fields() {

            register_setting( 'dro_shipping_options', 'dro_shipping_options' );

            add_settings_section( 'dro_shipping_section', esc_html__( 'Single product pages (per product type)', 'wc-ali-products-based-shipment' ), array( $this, 'dro_shipping_section_cb' ), __FILE__);

            add_settings_field( 'single_product', esc_html__( 'Simple products', 'wc-ali-products-based-shipment' ), array( $this, 'simple_products' ), __FILE__, 'dro_shipping_section'
            );

            add_settings_field( 'variable_product', esc_html__( 'Variable products', 'wc-ali-products-based-shipment' ), array( $this, 'variable_products' ), __FILE__, 'dro_shipping_section'
            );
            add_settings_field( 'grouped_product', esc_html__( 'Grouped products', 'wc-ali-products-based-shipment' ), array( $this, 'grouped_products' ), __FILE__, 'dro_shipping_section'
            );
            add_settings_field( 'external_product', esc_html__( 'external products', 'wc-ali-products-based-shipment' ), array( $this, 'external_products' ), __FILE__, 'dro_shipping_section'
            );
        }

        public function dro_shipping_section_cb() {
            
        }

        public function simple_products() {
            
            $val  = ( $this->option_has_value( $this->option['simple_products'] ) ) ? $this->option['simple_products'] : $this->default_message;
            
            woocommerce_form_field( 'dro_shipping_options[simple_products]', array(
                'type' => 'text',
                'id' => 'simple_products',
                'class' => array( 'dro-simple-product-message' ),
                'placeholder' => esc_html__( $this->default_message ),
                'description' => esc_html__( 'Custom message shown on single product page of simple product type', 'wc-ali-products-based-shipment' )
                    ), esc_html__( $val ) );
        }

        public function variable_products() {
            
            $val  = ( $this->option_has_value( $this->option['variable_products'] ) ) ? $this->option['variable_products'] : $this->default_message;
            
            woocommerce_form_field( 'dro_shipping_options[variable_products]', array(
                'type' => 'text',
                'id' => 'variable_products',
                'desc_tip' => true,
                'placeholder' => esc_html__( $this->default_message ),
                'description' => esc_html__( 'Custom message shown on single product page of variable product type', 'wc-ali-products-based-shipment' )
                    ), esc_html__( $val ) );
        }

        public function grouped_products() {
            
            $val  = ( $this->option_has_value( $this->option['grouped_products'] ) ) ? $this->option['grouped_products'] : $this->default_message;            
            
            woocommerce_form_field( 'dro_shipping_options[grouped_products]', array(
                'type' => 'text',
                'id' => 'grouped_products',
                'desc_tip' => true,
                'placeholder' => esc_html__( $this->default_message ),
                'description' => esc_html__( 'Custom message shown on single product page of grouped product type', 'wc-ali-products-based-shipment' )
                    ), $val);
        }

        public function external_products() {
            
            $val  = ( $this->option_has_value( $this->option['external_products'] ) ) ? $this->option['external_products'] : $this->default_message;
            
            woocommerce_form_field( 'dro_shipping_options[external_products]', array(
                'type' => 'text',
                'id' => 'external_products',
                'desc_tip' => true,
                'placeholder' => esc_html__( $this->default_message ),
                'description' => esc_html__( 'Custom message shown on single product page of external product type', 'wc-ali-products-based-shipment' )
                    ), $val);
        }

        public function wc_ali_settings_page() {
            ?>
            <div class="wrap wc-ali-settings">
                <form name="dro" id="" method="post" action="options.php">
                    <h2><?php esc_html_e( 'Shipping-based product for ALi2Woo', 'wc-ali-products-based-shipment' ) ?></h2>
                    <div class="store_address-description">
                        <p><?php esc_html_e( 'Choose custom message to display when the product is not available for the customer shipping address', 'wc-ali-products-based-shipment' ) ?></p>
                    </div>
            <?php
            settings_fields( 'dro_shipping_options' );
            do_settings_sections(__FILE__);
            ?>
                    <p class="submit">
                        <input name="submit" type="submit" class="button-primary" value="<?php esc_html_e( 'Save changes', 'woocommerce' ) ?>" />
                    </p>
                </form>

            </div>
            <?php
        }
        
        public function option_has_value( $option ){
            
            $option = trim( $option );
            
            if ( isset ( $option ) && ! empty ( $option ) ){
                
                return $option;
                
            }else{
                
                return false;
            }
        }
    }

}
