<?php
/**
 * Meta Boxes Management Class
 *
 * Handles page-specific custom code meta boxes.
 *
 * @package CodeInjectorElite
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Meta boxes manager class
 */
class CIE_Meta_Boxes {

	/**
	 * Constructor - setup hooks
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_box_data' ) );
	}

	/**
	 * Add meta boxes to post/page edit screens
	 */
	public function add_meta_boxes() {
		// Check if enabled for posts
		$enable_posts = get_option( CIE_OPTION_ENABLE_POSTS, false );
		if ( $enable_posts ) {
			add_meta_box(
				CIE_META_BOX_ID,
				__( 'Code Injector Elite', CIE_TEXT_DOMAIN ),
				array( $this, 'render_meta_box' ),
				'post',
				'normal',
				'default'
			);
		}

		// Check if enabled for pages
		$enable_pages = get_option( CIE_OPTION_ENABLE_PAGES, true );
		if ( $enable_pages ) {
			add_meta_box(
				CIE_META_BOX_ID,
				__( 'Code Injector Elite', CIE_TEXT_DOMAIN ),
				array( $this, 'render_meta_box' ),
				'page',
				'normal',
				'default'
			);
		}
	}

	/**
	 * Render meta box content
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function render_meta_box( $post ) {
		// Get current values
		$page_header_code = get_post_meta( $post->ID, CIE_META_PAGE_HEADER, true );
		$page_footer_code = get_post_meta( $post->ID, CIE_META_PAGE_FOOTER, true );

		// Add nonce for security
		wp_nonce_field( CIE_META_BOX_NONCE_ACTION, CIE_META_BOX_NONCE );

		// Load template
		include CIE_VIEWS_DIR . '/meta-box.php';
	}

	/**
	 * Save meta box data
	 *
	 * @param int $post_id Post ID.
	 */
	public function save_meta_box_data( $post_id ) {
		// Check nonce
		if ( ! isset( $_POST[ CIE_META_BOX_NONCE ] ) ||
		     ! wp_verify_nonce( $_POST[ CIE_META_BOX_NONCE ], CIE_META_BOX_NONCE_ACTION ) ) {
			return;
		}

		// Check user permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check for autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Save header code
		if ( isset( $_POST[ CIE_META_PAGE_HEADER ] ) ) {
			$header_code = $this->sanitize_code( $_POST[ CIE_META_PAGE_HEADER ] );
			update_post_meta( $post_id, CIE_META_PAGE_HEADER, $header_code );
		}

		// Save footer code
		if ( isset( $_POST[ CIE_META_PAGE_FOOTER ] ) ) {
			$footer_code = $this->sanitize_code( $_POST[ CIE_META_PAGE_FOOTER ] );
			update_post_meta( $post_id, CIE_META_PAGE_FOOTER, $footer_code );
		}
	}

	/**
	 * Sanitize code input
	 *
	 * Allows HTML/JS/CSS but strips PHP tags for security.
	 *
	 * @param string $code Code to sanitize.
	 * @return string Sanitized code.
	 */
	private function sanitize_code( $code ) {
		// Remove PHP tags for security
		$code = str_replace( array( '<?php', '<?', '?>' ), '', $code );
		return $code;
	}

	/**
	 * Render a code textarea field (used in template)
	 *
	 * @param string $name Field name.
	 * @param string $id Field ID.
	 * @param string $value Field value.
	 * @param string $class Field CSS class.
	 */
	public function render_textarea( $name, $id, $value, $class = '' ) {
		printf(
			'<textarea id="%s" name="%s" class="%s">%s</textarea>',
			esc_attr( $id ),
			esc_attr( $name ),
			esc_attr( $class ),
			esc_textarea( $value )
		);
	}
}
