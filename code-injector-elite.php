<?php
/**
 * Code Injector Elite
 *
 * @package           CodeInjectorElite
 * @author            a5ah1
 * @copyright         2025 a5ah1
 * @license           MIT
 *
 * @wordpress-plugin
 * Plugin Name:       Code Injector Elite
 * Plugin URI:        https://github.com/a5ah1/code-injector-elite
 * Description:       Professional code injection plugin for WordPress. Inject custom HTML, JavaScript, and CSS into page headers and footers with precision and control.
 * Version:           1.0.4
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            a5ah1
 * Author URI:        https://github.com/a5ah1/code-injector-elite
 * Text Domain:       code-injector-elite
 * License:           MIT
 * License URI:       https://opensource.org/license/mit
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load constants
require_once plugin_dir_path( __FILE__ ) . 'includes/constants.php';

// Load main plugin class
require_once CIE_INCLUDES_DIR . '/class-plugin.php';

// Load Plugin Update Checker
require_once plugin_dir_path( __FILE__ ) . 'vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

/**
 * Initialize the plugin
 */
function code_injector_elite_init() {
	CIE_Plugin::get_instance();
}
add_action( 'plugins_loaded', 'code_injector_elite_init' );

/**
 * Initialize automatic updates from GitHub
 */
function code_injector_elite_updates_init() {
	$update_checker = PucFactory::buildUpdateChecker(
		'https://github.com/a5ah1/code-injector-elite/',
		__FILE__,
		'code-injector-elite'
	);

	// Optional: Set the branch that contains the stable release
	$update_checker->setBranch( 'master' );

	// Optional: If you're using a private repository, specify an access token
	// $update_checker->setAuthentication( 'your-token-here' );
}
add_action( 'plugins_loaded', 'code_injector_elite_updates_init' );

/**
 * Plugin activation hook
 *
 * Set default option values on activation.
 */
function code_injector_elite_activate() {
	// Set default value for enable posts (disabled by default)
	if ( false === get_option( CIE_OPTION_ENABLE_POSTS ) ) {
		add_option( CIE_OPTION_ENABLE_POSTS, false );
	}

	// Set default value for enable pages (enabled by default)
	if ( false === get_option( CIE_OPTION_ENABLE_PAGES ) ) {
		add_option( CIE_OPTION_ENABLE_PAGES, true );
	}
}
register_activation_hook( __FILE__, 'code_injector_elite_activate' );
