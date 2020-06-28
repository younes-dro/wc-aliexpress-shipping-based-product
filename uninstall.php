<?php
/**
 * Uninstall plugin
 */


if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete option from options table
delete_option( 'dro_shipping_options' );