<?php

/**
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Ali_Front' ) ) {

	class WC_Ali_Front {

		public function __construct() {

			if ( ! $this->product_has_shipping_method() ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'dro_enqueue_script' ) );
				add_action( 'woocommerce_single_product_summary', array( $this, 'removing_add_to_cart_button' ), 1 );
			}
		}

		public function removing_add_to_cart_button() {

			remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
			remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
			remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
			remove_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );

			add_action( 'woocommerce_simple_add_to_cart', array( $this, 'single_product_custom_button' ), 30 );
			add_action( 'woocommerce_variable_add_to_cart', array( $this, 'variable_product_custom_button' ), 30 );
			add_action( 'woocommerce_grouped_add_to_cart', array( $this, 'grouped_product_custom_button' ), 30 );
			add_action( 'woocommerce_external_add_to_cart', array( $this, 'external_product_custom_button' ), 30 );
		}

		public function single_product_custom_button() {

			wc_get_template(
				'single-product/add-to-cart/simple.php',
				array(),
				'',
				WC_Ali_Products_Shipment()->plugin_path() . '/templates/'
			);
		}

		public function variable_product_custom_button() {
			wc_get_template(
				'single-product/add-to-cart/variable.php',
				array(),
				'',
				WC_Ali_Products_Shipment()->plugin_path() . '/templates/'
			);
		}

		public function grouped_product_custom_button() {
			wc_get_template(
				'single-product/add-to-cart/grouped.php',
				array(),
				'',
				WC_Ali_Products_Shipment()->plugin_path() . '/templates/'
			);
		}

		public function external_product_custom_button() {
			wc_get_template(
				'single-product/add-to-cart/external.php',
				array(),
				'',
				WC_Ali_Products_Shipment()->plugin_path() . '/templates/'
			);
		}

		public function product_has_shipping_method() {

			if ( ! is_admin() ) {

				$ship_to          = WC()->customer->get_shipping_country();
				$ext              = new A2W_Woocommerce();
				$ext_id           = $ext->get_product_external_id( get_the_ID() );
				$shipping_loader  = new A2W_ShippingLoader();
				$shipping_data    = $shipping_loader->load( new A2W_ShippingMeta( get_the_ID(), $ext_id, $ship_to, '', '', '' ) );
				$shipping_methods = $shipping_data['data']['ways'];
				if ( empty( $shipping_methods ) ) {

					return false;
				}
			}

			return true;
		}

		public function dro_enqueue_script() {

			$the_product = wc_get_product( get_the_ID() );

			// If  we don't have the context Product we stop here .
			if ( ! $the_product ) {
				return;
			}
			// Make sure we are viewing a single product page.
			if ( is_product() ) {

				wp_register_style( 'dro-shipping-based-product-frontend', WC_Ali_Products_Shipment()->plugin_url() . '/assets/css/frontend.css', array(), WC_Ali_Products_Shipment()->version );
				wp_enqueue_style( 'dro-shipping-based-product-frontend' );
			}

		}

	}

}
