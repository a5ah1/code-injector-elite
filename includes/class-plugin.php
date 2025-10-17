<?php
/**
 * Main Plugin Class
 *
 * Bootstraps and coordinates all plugin functionality.
 *
 * @package CodeInjectorElite
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class using singleton pattern
 */
class CIE_Plugin {

	/**
	 * Single instance of the class
	 *
	 * @var CIE_Plugin
	 */
	private static $instance = null;

	/**
	 * Assets manager instance
	 *
	 * @var CIE_Assets
	 */
	public $assets;

	/**
	 * Settings manager instance
	 *
	 * @var CIE_Settings
	 */
	public $settings;

	/**
	 * Meta boxes manager instance
	 *
	 * @var CIE_Meta_Boxes
	 */
	public $meta_boxes;

	/**
	 * Frontend manager instance
	 *
	 * @var CIE_Frontend
	 */
	public $frontend;

	/**
	 * Migration manager instance
	 *
	 * @var CIE_Migration
	 */
	public $migration;

	/**
	 * Data tools manager instance
	 *
	 * @var CIE_Data_Tools
	 */
	public $data_tools;

	/**
	 * Get singleton instance
	 *
	 * @return CIE_Plugin
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private constructor to prevent direct instantiation
	 */
	private function __construct() {
		$this->load_dependencies();
		$this->init_components();
		$this->setup_hooks();
	}

	/**
	 * Load required files
	 */
	private function load_dependencies() {
		require_once CIE_INCLUDES_DIR . '/class-assets.php';
		require_once CIE_INCLUDES_DIR . '/class-settings.php';
		require_once CIE_INCLUDES_DIR . '/class-meta-boxes.php';
		require_once CIE_INCLUDES_DIR . '/class-frontend.php';
		require_once CIE_INCLUDES_DIR . '/class-migration.php';
		require_once CIE_INCLUDES_DIR . '/class-data-tools.php';
	}

	/**
	 * Initialize component classes
	 */
	private function init_components() {
		$this->assets     = new CIE_Assets();
		$this->settings   = new CIE_Settings();
		$this->meta_boxes = new CIE_Meta_Boxes();
		$this->frontend   = new CIE_Frontend();
		$this->migration  = new CIE_Migration();
		$this->data_tools = new CIE_Data_Tools();
	}

	/**
	 * Setup WordPress hooks
	 */
	private function setup_hooks() {
		// Add plugin action links
		add_filter(
			'plugin_action_links_' . plugin_basename( CIE_PLUGIN_FILE ),
			array( $this, 'add_settings_link' )
		);
	}

	/**
	 * Add settings link to plugin actions
	 *
	 * @param array $links Existing plugin action links.
	 * @return array Modified plugin action links.
	 */
	public function add_settings_link( $links ) {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'options-general.php?page=' . CIE_SETTINGS_PAGE_SLUG ) ),
			esc_html__( 'Settings', CIE_TEXT_DOMAIN )
		);

		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Prevent cloning of the instance
	 */
	private function __clone() {}

	/**
	 * Prevent unserializing of the instance
	 */
	public function __wakeup() {}
}
