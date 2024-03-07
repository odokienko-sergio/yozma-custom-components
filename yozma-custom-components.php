<?php
/**
 * Plugin Name: Yozma Custom Components
 * Description: Adds components for "Discounted Products" and "Newsletter Subscription".
 * Version: 1.0
 * Author: Serhii Odokiienko
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Include the Yozma_NewsletterManager class
require_once plugin_dir_path( __FILE__ ) . 'classes/Yozma_NewsletterManager.php';

// Include shortcode functions
require_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes.php';

// Register admin menu using the Yozma_NewsletterManager class method
add_action( 'admin_menu', [
	'Yozma_NewsletterManager',
	'add_admin_menu',
] );

// Register activation hook using the Yozma_NewsletterManager class method
register_activation_hook( __FILE__, [
	'Yozma_NewsletterManager',
	'create_table_sub',
] );

// Register other hooks using the Yozma_NewsletterManager class methods
add_action( 'admin_post_nopriv_yozma_process_newsletter', [
	'Yozma_NewsletterManager',
	'newsletter_process',
] );
add_action( 'admin_post_yozma_process_newsletter', [
	'Yozma_NewsletterManager',
	'newsletter_process',
] );
