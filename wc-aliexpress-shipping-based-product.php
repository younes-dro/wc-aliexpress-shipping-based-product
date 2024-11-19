<?php
/**
 * Plugin Name:     Shipment-Based Product for Ali2Woo
 * Plugin URI:      https://github.com/younes-dro/wc-aliexpress-shipping-based-product
 * Description:     Show / Hide products based on shipping methods available for the shipping country.
 * Author:          Younes DRO
 * Author URI:      https://github.com/younes-dro/
 * Text Domain:     wc-ali-products-based-shipment
 * Domain Path:     /languages
 * Version:         1.0.1
 *
 * WC requires at least: 3.7.0
 * WC tested up to: 7.1.0
 *
 * Copyright: © 2020 Younes DRO
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WC_Ali_Products_Shipment class.
 *
 * The main instance of the plugin.
 *
 * @since 1.0.0
 */
class WC_Ali_Products_Shipment {

	/**
	 * The Single instance of the class.
	 *
	 * @var obj WC_Ali_Products_Shipment object
	 */
	protected static $instance;

	/**
	 * Plugin Version.
	 *
	 * @var String
	 */
	public $version = '1.0.0';

	/**
	 * Plugin Name
	 *
	 * @var String
	 */
	public $plugin_name = 'WooCommerce Aliexpress - Sell Products Based Shipment';

	/**
	 * Instance of the WC_Ali_Dependencies class.
	 *
	 * Verify the requirements.
	 *
	 * @var obj WC_Ali_Dependencies object
	 */
	protected static $dependencies;

	/** @var array the admin notices to add */
	protected $notices = array();

	/**
	 * Check the dependencies that the plugin needs.
	 *
	 * @param obj dependencies
	 */
	public function __construct( WC_Ali_Dependencies $dependencies ) {

		self::$dependencies = $dependencies;

		register_activation_hook( __FILE__, array( $this, 'activation_check' ) );

		add_action( 'admin_init', array( $this, 'check_environment' ) );

		add_action( 'admin_init', array( $this, 'add_plugin_notices' ) );

		add_action( 'admin_notices', array( $this, 'admin_notices' ), 15 );

		add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );

		add_action( 'init', array( $this, 'load_textdomain' ) );

	}

	/**
	 * Gets the main WC_Ali_Products_Shipment instance.
	 *
	 * Ensures only one instance of WC_Ali_Products_Shipment is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @param Obj $dependencies Check the dependencies that the plugin needs
	 *
	 * @return WC_Ali_Products_Shipment instance
	 */
	public static function start( WC_Ali_Dependencies $dependencies ) {
		if ( null === self::$instance ) {
			self::$instance = new self( $dependencies );
		}

		return self::$instance;
	}

	/**
	 * Cloning is forbidden due to singleton pattern.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		$cloning_message = sprintf(
			esc_html__( 'You cannot clone instances of %s.', 'wc-ali-products-based-shipment' ),
			get_class( $this )
		);
		_doing_it_wrong( __FUNCTION__, $cloning_message, $this->version );
	}

	/**
	 * Unserializing instances is forbidden due to singleton pattern.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		$unserializing_message = sprintf(
			esc_html__( 'You cannot clone instances of %s.', 'wc-ali-products-based-shipment' ),
			get_class( $this )
		);
				_doing_it_wrong( __FUNCTION__, $unserializing_message, $this->version );
	}

	/**
	 * Checks the server environment and deactivates plugins as necessary.
	 *
	 * @since 1.0.0
	 */
	public function activation_check() {

		if ( ! self::$dependencies->check_php_version() ) {

			$this->deactivate_plugin();

			wp_die( $this->plugin_name . esc_html__( ' could not be activated. ', 'wc-ali-products-based-shipment' ) . self::$dependencies->get_php_notice() );

		}
	}

	/**
	 * Checks the environment on loading WordPress, just in case the environment changes after activation.
	 *
	 * @since 1.0.0
	 */
	public function check_environment() {

		if ( ! self::$dependencies->check_php_version() && is_plugin_active( plugin_basename( __FILE__ ) ) ) {

			$this->deactivate_plugin();
			$this->add_admin_notice(
				'bad_environment',
				'error',
				$this->plugin_name . esc_html__( ' has been deactivated. ', 'wc-ali-products-based-shipment' ) . self::$dependencies->get_php_notice()
			);
		}
	}

	/**
	 * Deactivate the plugin
	 *
	 * @since 1.0.0
	 */
	protected function deactivate_plugin() {

		deactivate_plugins( plugin_basename( __FILE__ ) );

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}

	/**
	 * Adds an admin notice to be displayed.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug message slug
	 * @param string $class CSS classes
	 * @param string $message notice message
	 */
	public function add_admin_notice( $slug, $class, $message ) {

		$this->notices[ $slug ] = array(
			'class'   => $class,
			'message' => $message,
		);
	}

	public function add_plugin_notices() {

		if ( ! self::$dependencies->check_wp_version() ) {

			$this->add_admin_notice( 'update_wordpress', 'error', self::$dependencies->get_wp_notice() );
		}

		if ( ! self::$dependencies->check_wc_version() ) {

			$this->add_admin_notice( 'update_woocommerce', 'error', self::$dependencies->get_wc_notice() );
		}

		if ( ! self::$dependencies->check_ali2woo() ) {

			$this->add_admin_notice( 'update_ali2woo', 'error', self::$dependencies->get_ali2woo_notice() );
		}

	}

	/**
	 * Displays any admin notices added with \WC_Ali_Products_Shipment::add_admin_notice()
	 *
	 * @since 1.0.0
	 */
	public function admin_notices() {

		foreach ( (array) $this->notices as $notice_key => $notice ) {

			echo "<div class='" . esc_attr( $notice['class'] ) . "'><p>";
			echo wp_kses(
				$notice['message'],
				array(
					'a'      => array(
						'href' => array(),
					),
					'strong' => array(),
				)
			);
			echo '</p></div>';
		}
	}

	/**
	 * Initializes the plugin.
	 *
	 * @since 1.0.0
	 */
	public function init_plugin() {

		if ( ! self::$dependencies->is_compatible() ) {

			return;

		}

		if ( ! is_admin() ) {
			add_action( 'wp', array( $this, 'frontend_includes' ) );

		}

		new WC_Ali_Settings();
	}

	/**
	 * Include template functions and hooks.
	 */
	public function frontend_includes() {

		new WC_Ali_Front();
	}
	/*
	-----------------------------------------------------------------------------------*/
	/*
	  Helper Functions                                                                 */
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Get the plugin url.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function plugin_url() {

		return untrailingslashit( plugins_url( '/', __FILE__ ) );

	}

	/**
	 * Get the plugin path.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function plugin_path() {

		return untrailingslashit( plugin_dir_path( __FILE__ ) );

	}

	/**
	 * Get the plugin base path name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function plugin_basename() {

		return plugin_basename( __FILE__ );

	}

	/**
	 * Register the built-in autoloader
	 *
	 * @codeCoverageIgnore
	 */
	public static function register_autoloader() {
		spl_autoload_register( array( 'WC_Ali_Products_Shipment', 'autoloader' ) );
	}

	/**
	 * Register autoloader.
	 *
	 * @param string $class Class name to load
	 */
	public static function autoloader( $class_name ) {

		$class = strtolower( str_replace( '_', '-', $class_name ) );
		$file  = plugin_dir_path( __FILE__ ) . '/includes/class-' . $class . '.php';
		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}

	public function load_textdomain() {
		load_plugin_textdomain( 'wc-ali-products-based-shipment', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
}

/**
 * Returns the main instance of WC_Ali_Products_Shipment.
 */
function WC_Ali_Products_Shipment() {

	WC_Ali_Products_Shipment::register_autoloader();
	return WC_Ali_Products_Shipment::start( new WC_Ali_Dependencies() );

}

WC_Ali_Products_Shipment();
