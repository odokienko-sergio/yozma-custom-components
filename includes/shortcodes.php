<?php
/**
 * Enqueue styles and create a newsletter subscription form.
 */
function yozma_newsletter_shortcode() {
	wp_enqueue_style( 'yozma-styles', plugin_dir_url( __FILE__ ) . '../assets/yozma-styles.css' );

	ob_start();
	?>
	<div class="yozma-newsletter">
		<div class="yozma-newsletter-left">
			<h2 class="yozma-newsletter-left-title">Subscribe to Our Newsletter</h2>
			<p class="yozma-newsletter-left-subtitle">Stay updated with the latest deals and more!</p>
		</div>
		<div class="yozma-newsletter-right">
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="yozma_process_newsletter">
				<label for="email">Email Address:</label>
				<input type="email" id="email" name="email" placeholder="Your email address" required>
				<br>
				<input type="checkbox" id="agree" name="agree" required>
				<label for="agree">I agree to the terms and conditions</label>
				<br>
				<input type="submit" value="Subscribe">
				<?php wp_nonce_field( 'yozma_newsletter' ); ?>
			</form>
		</div>
	</div>
	<?php
	$output = ob_get_clean();

	return $output;
}

add_shortcode( 'yozma_newsletter', 'yozma_newsletter_shortcode' );

/**
 * Retrieve products with discounts and display them.
 */
function yozma_products_discount_shortcode() {
	$args  = array(
		'post_type'      => 'product',
		'posts_per_page' => - 1,
		'meta_query'     => array(
			'relation' => 'AND',
			array(
				'key'     => '_sale_price',
				'value'   => 0,
				'compare' => '>',
				'type'    => 'NUMERIC',
			),
		),
	);
	$query = new WP_Query( $args );

	ob_start();
	if ( $query->have_posts() ) {
		echo '<div class="yozma-products-discount">';
		while ( $query->have_posts() ) {
			$query->the_post();
			$image_url         = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			$regular_price     = get_post_meta( get_the_ID(), '_regular_price', true );
			$sale_price        = get_post_meta( get_the_ID(), '_sale_price', true );
			$short_description = get_post_meta( get_the_ID(), '_short_description', true );
			?>
			<div class="product-card">
				<?php if ( $image_url ) : ?>
					<img src="<?php echo esc_url( $image_url[0] ); ?>" alt="Product Image">
				<?php endif; ?>
				<p>Regular Price: <?php echo esc_html( $regular_price ); ?></p>
				<p>Sale Price: <?php echo esc_html( $sale_price ); ?></p>
				<p>Short Description: <?php echo esc_html( $short_description ); ?></p>
			</div>
			<?php
		}
		echo '</div>';
	} else {
		echo '<p>No discounted products found.</p>';
	}
	wp_reset_postdata();

	return ob_get_clean();
}

add_shortcode( 'yozma_products_discount', 'yozma_products_discount_shortcode' );
