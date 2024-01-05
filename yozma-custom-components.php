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

// Include the NewsletterManager class
require_once plugin_dir_path( __FILE__ ) . 'classes/NewsletterManager.php';

// Include shortcode functions
require_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes.php';

// Register admin menu using the NewsletterManager class method
add_action( 'admin_menu', [
	'NewsletterManager',
	'add_admin_menu',
] );

// Register activation hook using the NewsletterManager class method
register_activation_hook( __FILE__, [
	'NewsletterManager',
	'create_table_sub',
] );

// Register other hooks using the NewsletterManager class methods
add_action( 'admin_post_nopriv_yozma_process_newsletter', [
	'NewsletterManager',
	'newsletter_process',
] );
add_action( 'admin_post_yozma_process_newsletter', [
	'NewsletterManager',
	'newsletter_process',
] );
