<?php

class NewsletterManager {
	/**
	 * Add an admin menu page for newsletter subscriptions.
	 */
	public static function add_admin_menu() {
		add_menu_page(
			'Newsletter Subscriptions',            // Page title
			'Newsletter Subs',                     // Menu title
			'manage_options',                      // Capability
			'my-gutenberg-plugin-newsletter-subs', // Menu slug
			[
				self::class,
				'newsletter_subs_page',
			],                                     // Callback function for displaying the page
			'dashicons-email-alt',                 // Icon
			6                           // Position in the menu
		);
	}

	/**
	 * Create a table for newsletter subscriptions if it doesn't exist.
	 */
	public static function create_table_sub() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'sub_newsletter';

		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            email varchar(255) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}

	/**
	 * Display the subscribers for the newsletter.
	 */
	public static function newsletter_subs_page() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'sub_newsletter';
		$subscribers = $wpdb->get_results( "SELECT id, email FROM $table_name" );

		echo '<div class="wrap"><h1>Newsletter Subscribers</h1>';
		echo '<table class="wp-list-table widefat fixed striped">';
		echo '<thead><tr><th>ID</th><th>Email</th></tr></thead>';
		echo '<tbody>';
		foreach ( $subscribers as $subscriber ) {
			echo "<tr><td>{$subscriber->id}</td><td>{$subscriber->email}</td></tr>";
		}
		echo '</tbody></table></div>';
	}

	/**
	 * Process the newsletter subscription form submission.
	 */
	public static function newsletter_process() {
		// Verify the security nonce
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'yozma_newsletter' ) ) {
			wp_die( 'Invalid security token. Action halted.' );
		}

		// Sanitize and retrieve the submitted email address
		$email = sanitize_email( $_POST['email'] );

		// Insert the sanitized email into the subscription table
		global $wpdb;
		$table_name = $wpdb->prefix . 'sub_newsletter';
		$wpdb->insert( $table_name, array( 'email' => $email ) );

		// Redirect to the referring page after submission
		wp_redirect( wp_get_referer() );
		exit;
	}
}
