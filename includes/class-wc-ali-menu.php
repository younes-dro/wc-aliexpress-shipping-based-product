<?php

/**
 * Add submenu to ali2woo.
 * 
 * @author    Younes DRO
 * @copyright Copyright (c) 2020, Younes DRO
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists ( 'WC_Ali_Menu' ) ) {

    /**
     * Display the Settings.
     * 
     * @class WC_Ali_Menu
     * @author Younes DRO <younesdro@gmail.com>
     * @version 1.0.0
     * @since 1.0.0
     */
    class WC_Ali_Menu {

        /**
         * Instance of WC_AliSettings class.
         *
         * @var obj 
         */
        private $wc_ali_settings;

        /**
         * 
         * @param WC_Ali_Settings $wc_ali_settings Instance of WC_Ali_Settings class
         */
        public function __construct( WC_Ali_Settings $wc_ali_settings ) {

            $this->wc_ali_settings = $wc_ali_settings;
            add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
        }

        public function add_submenu_page() {

            add_submenu_page( 'a2w_dashboard', esc_html__( 'Aliexpress - Products-based shipment' , 'wc-ali-products-based-shipment' ), esc_html__( 'Products-based shipment' , 'wc-ali-products-based-shipment' ), 'manage_options' , __FILE__ , array( $this, 'wc_ali_settings_page' )
            );
            
        }

        public function wc_ali_settings_page() {

            $html_wrapper = '<div class="wrap wc-ali-settings">';
            $html_wrapper .= $this->wc_ali_settings->title;
            $html_wrapper .='</div>';

            echo $html_wrapper;
        }

    }

}