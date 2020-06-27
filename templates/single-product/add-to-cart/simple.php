<?php
/**
 * 
 */

defined( 'ABSPATH' ) || exit;

$dro_custom_message = get_option('dro_shipping_options');
?>

<p><h3>Aliexpress : </h3><?php esc_html_e( $dro_custom_message['simple_products'] , '');?></p>


