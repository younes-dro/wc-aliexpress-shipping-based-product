<?php
/**
 * 
 * 
 */

defined( 'ABSPATH' ) || exit;


$dro_custom_message = get_option('dro_shipping_options');
?>

<p class="dro-simple-product-message"><?php esc_html_e( $dro_custom_message['variable_products'] , '');?></p>