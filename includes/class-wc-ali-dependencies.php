<?php

/**
 * Manages the dependencies that the Plugin needs to operate.
 * 
 * @author    Younes DRO
 * @copyright Copyright (c) 2020, Younes DRO
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Check the compatibility of the environment.
 * 
 * @class WC_Ali_Dependencies
 * @author Younes DRO <younesdro@gmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class WC_Ali_Dependencies {

    /** minimum PHP version required by this plugin */
    const MINIMUM_PHP_VERSION = '5.3';

    /** minimum WordPress version required by this plugin */
    const MINIMUM_WP_VERSION = '5.3.2';

    /** minimum WooCommerce version required by this plugin */
    const MINIMUM_WC_VERSION = '3.7.0';
    
    /** minimum Ali2Woo version required by this plugin */
    const MINIMUM_ALI2WOO_VERSION = '1.8.6';

    public function __construct() {
        
    }

    /**
     * Checks the PHP version.
     * 
     * @since 1.0.0
     * 
     * @return bool Return true if the PHP version is compatible.Otherwise, will return false.
     */
    public static function check_php_version() {

        return version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '>=' );
    }

    /**
     * Gets the message for display when PHP version is incompatible with this plugin.
     * 
     * @since 1.0.0
     * 
     * @return string Return an informative message.
     */
    public static function get_php_notice() {

        return sprintf(
                esc_html__( 'The minimum PHP version required for this plugin is %1$s. You are running %2$s.', 'wc-ali-products-based-shipment')
                , self::MINIMUM_PHP_VERSION, PHP_VERSION );
    }

    /**
     * Checks the WordPress version.
     * 
     * @since 1.0.0
     * 
     * @return bool Return true if the WordPress version is compatible.Otherwise, will return false.
     */
    public static function check_wp_version() {
        
        if ( ! self::MINIMUM_WP_VERSION ){
            return true;
        }

        return version_compare( get_bloginfo( 'version' ), self::MINIMUM_WP_VERSION, '>=' );
    }
    
    /**
     * Gets the message for display when WordPress version is incompatible with this plugin.
     * 
     * @return string Return an informative message.
     */
    public static function get_wp_notice(){
        
        return sprintf(
                esc_html__( '%s is not active, as it requires WordPress version %s or higher. Please %supdate WordPress &raquo;%s', 'wc-ali-products-based-shipment')
				,'<strong>' . WC_Ali_Products_Shipment()->plugin_name . '</strong>',
				self::MINIMUM_WP_VERSION,
				'<a href="' . esc_url( admin_url( 'update-core.php' ) ) . '">', '</a>'
			);
    }
    
    /**
     * Checks WooCommerce is installed, activated and compatible.
     * 
     * @since 1.0.0
     * 
     * @return bool Return true if the WooCommerce is installed , activated and the version is compatible.Otherwise, will return false.
     */
    public static function check_wc_version() {
        
        if ( ! self::MINIMUM_WC_VERSION ){
            return true;
        }
        
        return defined( 'WC_VERSION' ) && version_compare( WC_VERSION, self::MINIMUM_WC_VERSION, '>=' );
    }
    
    /**
     * Gets the message for display when WooCommerce version is not installed , not activated or incompatible with this plugin.
     * 
     * @return string Return an informative message.
     */
    public function get_wc_notice() {
        
        return sprintf(
                esc_html__( '%1$s, as it requires %2$sWooCommerce%3$s version %4$s or higher. Please %5$supdate%6$s or activate WooCommerce ', 'wc-ali-products-based-shipment')
				,'<strong>' . WC_Ali_Products_Shipment()->plugin_name . ' is inactive </strong>',
                                '<a href="' . esc_url ( 'https://wordpress.org/plugins/woocommerce/' ).'">', '</a>',
                                self::MINIMUM_WC_VERSION,
				'<a href="' . esc_url( admin_url( 'update-core.php' ) ) . '">', '</a>'
			);
    }
    
    /**
     * Checks Ali2woo is installed, activated and compatible.
     * 
     * @since 1.0.0
     * 
     * @return bool Return true if the Ali2woo is installed , activated and the version is compatible.Otherwise, will return false.
     */    
    public static function check_ali2woo() {
        
        if ( !self::MINIMUM_ALI2WOO_VERSION ){
            
            return true;
        }
        
        // Get ali2woo version 
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        $plugins_dir = dirname (dirname( plugin_dir_path( __FILE__ ) ) );
        $ali2woo_path = $plugins_dir .  '/ali2woo/ali2woo.php';
        $ali2woo_data = get_plugin_data($ali2woo_path);
        $ali2woo_version = $ali2woo_data['Version'];
        
      
        if ( in_array( 'ali2woo/ali2woo.php', apply_filters('active_plugins', get_option( 'active_plugins' ) ) ) 
                && version_compare( $ali2woo_version, self::MINIMUM_ALI2WOO_VERSION , '>=' ) ){
            
            return true;
            
        }  else {
            
            return false;
        }

    }

    /**
     * Gets the message for display when Ali2woo version is not installed , not activated or incompatible with this plugin.
     * 
     * @return string Return an informative message.
     */    
    public static function get_ali2woo_notice() {
        
        
        return sprintf(
                esc_html__( '%1$s,The %2$sAliexpress Dropship for Woocommerce%3$s must be active and at least version %4$s or higher. Please %5$supdate%6$s or activate Aliexpress Dropship for Woocommerce', 'wc-ali-products-based-shipment')
				,'<strong>' . WC_Ali_Products_Shipment()->plugin_name . ' is inactive </strong>',
                                '<a href="' . esc_url( 'https://ali2woo.com/' ) . '">', '</a>',
				self::MINIMUM_ALI2WOO_VERSION,
				'<a href="' . esc_url( admin_url( 'update-core.php' ) ) . '">', '</a>'
			);
        
    }

    /**
     * Determines if all the requirements are valid .
     * 
     * @since 1.0.0
     * 
     * @return bool
     */
    public function is_compatible( ) {
     
        return ( self::check_php_version() && self::check_wp_version() && self::check_wc_version() && self::check_ali2woo() );
    }
    
    /**
     * Check if an Ali2woo class is loaded.
     * 
     * @param string $class_name
     */
    public static function check_ali2woo_class( $class_name ) {
        
        if ( class_exists( $class_name ) ){
            return true;
        }
        
        return false;
    }
}
