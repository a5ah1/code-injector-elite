<?php
/**
 * Frontend Management Class
 *
 * Handles code injection on the website frontend.
 *
 * @package CodeInjectorElite
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Frontend manager class
 */
class CIE_Frontend {

	/**
	 * Constructor - setup hooks
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'inject_header_code' ) );
		add_action( 'wp_footer', array( $this, 'inject_footer_code' ) );
	}

	/**
	 * Inject code into wp_head
	 */
	public function inject_header_code() {
		// Output global header code
		$this->output_code( CIE_OPTION_GLOBAL_HEADER );

		// Output post/page-specific header code if enabled
		$this->output_singular_code( CIE_META_PAGE_HEADER );
	}

	/**
	 * Inject code into wp_footer
	 */
	public function inject_footer_code() {
		// Output global footer code
		$this->output_code( CIE_OPTION_GLOBAL_FOOTER );

		// Output post/page-specific footer code if enabled
		$this->output_singular_code( CIE_META_PAGE_FOOTER );
	}

	/**
	 * Output post/page-specific code if enabled for that post type
	 *
	 * @param string $meta_key Meta key to retrieve.
	 */
	private function output_singular_code( $meta_key ) {
		// Check if we're on a single post or page
		if ( is_singular( 'post' ) ) {
			// Check if enabled for posts
			$enable_posts = get_option( CIE_OPTION_ENABLE_POSTS, false );
			if ( $enable_posts ) {
				$post_id = get_queried_object_id();
				$this->output_page_code( $post_id, $meta_key );
			}
		} elseif ( is_singular( 'page' ) ) {
			// Check if enabled for pages
			$enable_pages = get_option( CIE_OPTION_ENABLE_PAGES, true );
			if ( $enable_pages ) {
				$post_id = get_queried_object_id();
				$this->output_page_code( $post_id, $meta_key );
			}
		}
	}

	/**
	 * Output global code from options
	 *
	 * @param string $option_name Option name to retrieve.
	 */
	private function output_code( $option_name ) {
		$code = get_option( $option_name, '' );

		if ( ! empty( $code ) ) {
			echo $code . "\n";
		}
	}

	/**
	 * Output page-specific code from post meta
	 *
	 * @param int    $post_id Post ID.
	 * @param string $meta_key Meta key to retrieve.
	 */
	private function output_page_code( $post_id, $meta_key ) {
		$code = get_post_meta( $post_id, $meta_key, true );

		if ( ! empty( $code ) ) {
			echo trim( $code ) . "\n";
		}
	}
}
