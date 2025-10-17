<?php
/**
 * Assets Management Class
 *
 * Handles enqueuing of CSS and JavaScript files.
 *
 * @package CodeInjectorElite
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Assets manager class
 */
class CIE_Assets {

	/**
	 * Constructor - setup hooks
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	/**
	 * Enqueue admin assets
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_admin_assets( $hook ) {
		// Enqueue assets for settings page
		if ( $this->is_settings_page( $hook ) ) {
			$this->enqueue_settings_page_assets();
		}

		// Enqueue assets for page edit screens
		if ( $this->is_page_edit_screen( $hook ) ) {
			$this->enqueue_page_edit_assets();
		}
	}

	/**
	 * Check if current page is the plugin settings page
	 *
	 * @param string $hook Current admin page hook.
	 * @return bool
	 */
	private function is_settings_page( $hook ) {
		return false !== strpos( $hook, CIE_SETTINGS_PAGE_SLUG );
	}

	/**
	 * Check if current screen is a page edit screen
	 *
	 * @param string $hook Current admin page hook.
	 * @return bool
	 */
	private function is_page_edit_screen( $hook ) {
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return false;
		}

		$screen = get_current_screen();
		return $screen && CIE_POST_TYPE === $screen->post_type;
	}

	/**
	 * Enqueue assets for settings page
	 */
	private function enqueue_settings_page_assets() {
		// Enqueue custom CSS
		wp_enqueue_style(
			CIE_ADMIN_CSS_HANDLE,
			CIE_PLUGIN_URL . 'css/code-injector-elite.css',
			array(),
			CIE_VERSION
		);

		// Enqueue CodeMirror
		$cm_settings = wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
		wp_enqueue_style( 'wp-codemirror' );

		// Enqueue settings page JavaScript
		wp_enqueue_script(
			CIE_SETTINGS_JS_HANDLE,
			CIE_PLUGIN_URL . 'js/cie-settings-page.js',
			array( 'jquery', 'wp-codemirror' ),
			CIE_VERSION,
			true
		);

		// Pass CodeMirror settings to JavaScript
		wp_localize_script(
			CIE_SETTINGS_JS_HANDLE,
			'codeEditorSettings',
			$cm_settings
		);

		// Enqueue migration script
		wp_enqueue_script(
			CIE_MIGRATION_JS_HANDLE,
			CIE_PLUGIN_URL . 'js/cie-migration.js',
			array( 'jquery' ),
			CIE_VERSION,
			true
		);

		// Pass settings to migration script
		wp_localize_script(
			CIE_MIGRATION_JS_HANDLE,
			'cieSettings',
			array(
				'nonce' => wp_create_nonce( 'cie_migration_nonce' ),
			)
		);

		// Enqueue data tools script
		wp_enqueue_script(
			CIE_DATA_TOOLS_JS_HANDLE,
			CIE_PLUGIN_URL . 'js/cie-data-tools.js',
			array( 'jquery' ),
			CIE_VERSION,
			true
		);

		// Pass settings to data tools script
		wp_localize_script(
			CIE_DATA_TOOLS_JS_HANDLE,
			'cieDataTools',
			array(
				'nonce' => wp_create_nonce( 'cie_data_tools_nonce' ),
			)
		);
	}

	/**
	 * Enqueue assets for page edit screens
	 */
	private function enqueue_page_edit_assets() {
		// Enqueue custom CSS (includes textarea container styles)
		wp_enqueue_style(
			CIE_ADMIN_CSS_HANDLE,
			CIE_PLUGIN_URL . 'css/code-injector-elite.css',
			array(),
			CIE_VERSION
		);

		// Enqueue CodeMirror
		$cm_settings = wp_enqueue_code_editor( array( 'type' => 'text/html' ) );

		if ( $cm_settings ) {
			wp_enqueue_script( 'wp-theme-plugin-editor' );
			wp_enqueue_style( 'wp-codemirror' );
		}

		// Enqueue page edit JavaScript
		wp_enqueue_script(
			CIE_PAGE_EDIT_JS_HANDLE,
			CIE_PLUGIN_URL . 'js/cie-page-editor.js',
			array( 'jquery', 'wp-theme-plugin-editor' ),
			CIE_VERSION,
			true
		);

		// Pass CodeMirror settings to JavaScript
		wp_localize_script(
			CIE_PAGE_EDIT_JS_HANDLE,
			'codeEditorSettings',
			$cm_settings
		);
	}

	/**
	 * Get CodeMirror settings
	 *
	 * @return array CodeMirror settings.
	 */
	public function get_code_editor_settings() {
		return wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
	}
}
