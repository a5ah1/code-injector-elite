<?php
/**
 * Settings Management Class
 *
 * Handles plugin settings page and options.
 *
 * @package CodeInjectorElite
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings manager class
 */
class CIE_Settings {

	/**
	 * Constructor - setup hooks
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Add settings page to admin menu
	 */
	public function add_settings_page() {
		add_submenu_page(
			'options-general.php',
			__( 'Code Injector Elite Settings', CIE_TEXT_DOMAIN ),
			__( 'Code Injector Elite', CIE_TEXT_DOMAIN ),
			'manage_options',
			CIE_SETTINGS_PAGE_SLUG,
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register plugin settings
	 */
	public function register_settings() {
		// Register enable/disable settings
		register_setting(
			CIE_SETTINGS_GROUP_ENABLE,
			CIE_OPTION_ENABLE_POSTS,
			array(
				'type'              => 'boolean',
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
				'default'           => false,
			)
		);

		register_setting(
			CIE_SETTINGS_GROUP_ENABLE,
			CIE_OPTION_ENABLE_PAGES,
			array(
				'type'              => 'boolean',
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
				'default'           => true,
			)
		);

		// Register code settings
		register_setting(
			CIE_SETTINGS_GROUP,
			CIE_OPTION_GLOBAL_HEADER,
			array(
				'sanitize_callback' => array( $this, 'sanitize_code' ),
			)
		);

		register_setting(
			CIE_SETTINGS_GROUP,
			CIE_OPTION_GLOBAL_FOOTER,
			array(
				'sanitize_callback' => array( $this, 'sanitize_code' ),
			)
		);

		// Add enable/disable settings section
		add_settings_section(
			CIE_SETTINGS_SECTION_ENABLE,
			null,
			null,
			CIE_SETTINGS_PAGE_SLUG
		);

		add_settings_field(
			'enable-posts',
			__( 'Enable for Posts', CIE_TEXT_DOMAIN ),
			array( $this, 'render_enable_posts_field' ),
			CIE_SETTINGS_PAGE_SLUG,
			CIE_SETTINGS_SECTION_ENABLE
		);

		add_settings_field(
			'enable-pages',
			__( 'Enable for Pages', CIE_TEXT_DOMAIN ),
			array( $this, 'render_enable_pages_field' ),
			CIE_SETTINGS_PAGE_SLUG,
			CIE_SETTINGS_SECTION_ENABLE
		);

		// Add code settings section
		add_settings_section(
			CIE_SETTINGS_SECTION,
			null,
			null,
			CIE_SETTINGS_PAGE_SLUG
		);

		add_settings_field(
			'header-code',
			__( 'Header Code (Global)', CIE_TEXT_DOMAIN ),
			array( $this, 'render_header_code_field' ),
			CIE_SETTINGS_PAGE_SLUG,
			CIE_SETTINGS_SECTION
		);

		add_settings_field(
			'footer-code',
			__( 'Footer Code (Global)', CIE_TEXT_DOMAIN ),
			array( $this, 'render_footer_code_field' ),
			CIE_SETTINGS_PAGE_SLUG,
			CIE_SETTINGS_SECTION
		);
	}

	/**
	 * Sanitize checkbox input
	 *
	 * @param mixed $value Checkbox value.
	 * @return bool Sanitized boolean value.
	 */
	public function sanitize_checkbox( $value ) {
		return (bool) $value;
	}

	/**
	 * Sanitize code input
	 *
	 * Allows HTML/JS/CSS but strips PHP tags for security.
	 *
	 * @param string $code Code to sanitize.
	 * @return string Sanitized code.
	 */
	public function sanitize_code( $code ) {
		// Remove PHP tags for security
		$code = str_replace( array( '<?php', '<?', '?>' ), '', $code );
		return $code;
	}

	/**
	 * Render settings page
	 */
	public function render_settings_page() {
		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Load template
		include CIE_VIEWS_DIR . '/settings-page.php';
	}

	/**
	 * Render enable for posts field
	 */
	public function render_enable_posts_field() {
		$enabled = get_option( CIE_OPTION_ENABLE_POSTS, false );
		printf(
			'<label class="cie-checkbox-field"><input type="checkbox" name="%s" value="1" %s /> <span class="cie-checkbox-text">%s</span></label>',
			esc_attr( CIE_OPTION_ENABLE_POSTS ),
			checked( $enabled, true, false ),
			esc_html__( 'Show code injection fields on post edit screens and output saved code', CIE_TEXT_DOMAIN )
		);
	}

	/**
	 * Render enable for pages field
	 */
	public function render_enable_pages_field() {
		$enabled = get_option( CIE_OPTION_ENABLE_PAGES, true );
		printf(
			'<label class="cie-checkbox-field"><input type="checkbox" name="%s" value="1" %s /> <span class="cie-checkbox-text">%s</span></label>',
			esc_attr( CIE_OPTION_ENABLE_PAGES ),
			checked( $enabled, true, false ),
			esc_html__( 'Show code injection fields on page edit screens and output saved code', CIE_TEXT_DOMAIN )
		);
	}

	/**
	 * Render header code field
	 */
	public function render_header_code_field() {
		$header_code = get_option( CIE_OPTION_GLOBAL_HEADER, '' );
		$this->render_code_textarea( CIE_OPTION_GLOBAL_HEADER, $header_code );
	}

	/**
	 * Render footer code field
	 */
	public function render_footer_code_field() {
		$footer_code = get_option( CIE_OPTION_GLOBAL_FOOTER, '' );
		$this->render_code_textarea( CIE_OPTION_GLOBAL_FOOTER, $footer_code );
	}

	/**
	 * Render a code textarea field
	 *
	 * @param string $name Field name.
	 * @param string $value Field value.
	 */
	private function render_code_textarea( $name, $value ) {
		printf(
			'<textarea class="cie-textarea" name="%s" rows="7" cols="50">%s</textarea>',
			esc_attr( $name ),
			esc_textarea( $value )
		);
	}
}
