<?php
/**
 * Plugin Constants
 *
 * Define all constants used throughout the plugin.
 *
 * @package CodeInjectorElite
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin version
if ( ! defined( 'CIE_VERSION' ) ) {
	define( 'CIE_VERSION', '1.0.4' );
}

// Plugin paths
if ( ! defined( 'CIE_PLUGIN_FILE' ) ) {
	define( 'CIE_PLUGIN_FILE', dirname( __DIR__ ) . '/code-injector-elite.php' );
}

if ( ! defined( 'CIE_PLUGIN_DIR' ) ) {
	define( 'CIE_PLUGIN_DIR', dirname( __DIR__ ) );
}

if ( ! defined( 'CIE_PLUGIN_URL' ) ) {
	define( 'CIE_PLUGIN_URL', plugin_dir_url( CIE_PLUGIN_FILE ) );
}

if ( ! defined( 'CIE_INCLUDES_DIR' ) ) {
	define( 'CIE_INCLUDES_DIR', CIE_PLUGIN_DIR . '/includes' );
}

if ( ! defined( 'CIE_VIEWS_DIR' ) ) {
	define( 'CIE_VIEWS_DIR', CIE_PLUGIN_DIR . '/views' );
}

// Text domain
if ( ! defined( 'CIE_TEXT_DOMAIN' ) ) {
	define( 'CIE_TEXT_DOMAIN', 'code-injector-elite' );
}

// Settings and options
if ( ! defined( 'CIE_SETTINGS_GROUP' ) ) {
	define( 'CIE_SETTINGS_GROUP', 'cie-settings-group' );
}

if ( ! defined( 'CIE_SETTINGS_GROUP_ENABLE' ) ) {
	define( 'CIE_SETTINGS_GROUP_ENABLE', 'cie-settings-enable-group' );
}

if ( ! defined( 'CIE_SETTINGS_PAGE_SLUG' ) ) {
	define( 'CIE_SETTINGS_PAGE_SLUG', 'code-injector-elite-settings' );
}

if ( ! defined( 'CIE_SETTINGS_SECTION' ) ) {
	define( 'CIE_SETTINGS_SECTION', 'cie-main-section' );
}

if ( ! defined( 'CIE_SETTINGS_SECTION_ENABLE' ) ) {
	define( 'CIE_SETTINGS_SECTION_ENABLE', 'cie-enable-section' );
}

// Option names
if ( ! defined( 'CIE_OPTION_GLOBAL_HEADER' ) ) {
	define( 'CIE_OPTION_GLOBAL_HEADER', 'cie_global_header_code' );
}

if ( ! defined( 'CIE_OPTION_GLOBAL_FOOTER' ) ) {
	define( 'CIE_OPTION_GLOBAL_FOOTER', 'cie_global_footer_code' );
}

// Enable/disable options
if ( ! defined( 'CIE_OPTION_ENABLE_POSTS' ) ) {
	define( 'CIE_OPTION_ENABLE_POSTS', 'cie_enable_for_posts' );
}

if ( ! defined( 'CIE_OPTION_ENABLE_PAGES' ) ) {
	define( 'CIE_OPTION_ENABLE_PAGES', 'cie_enable_for_pages' );
}

// Meta keys
if ( ! defined( 'CIE_META_PAGE_HEADER' ) ) {
	define( 'CIE_META_PAGE_HEADER', 'cie_page_header_code' );
}

if ( ! defined( 'CIE_META_PAGE_FOOTER' ) ) {
	define( 'CIE_META_PAGE_FOOTER', 'cie_page_footer_code' );
}

// Meta box
if ( ! defined( 'CIE_META_BOX_ID' ) ) {
	define( 'CIE_META_BOX_ID', 'cie_meta_box' );
}

if ( ! defined( 'CIE_META_BOX_NONCE' ) ) {
	define( 'CIE_META_BOX_NONCE', 'cie_meta_box_nonce' );
}

if ( ! defined( 'CIE_META_BOX_NONCE_ACTION' ) ) {
	define( 'CIE_META_BOX_NONCE_ACTION', 'cie_save_meta_box_data' );
}

// CSS/JS handles
if ( ! defined( 'CIE_ADMIN_CSS_HANDLE' ) ) {
	define( 'CIE_ADMIN_CSS_HANDLE', 'cie-admin-css' );
}

if ( ! defined( 'CIE_SETTINGS_JS_HANDLE' ) ) {
	define( 'CIE_SETTINGS_JS_HANDLE', 'cie-settings-script' );
}

if ( ! defined( 'CIE_PAGE_EDIT_JS_HANDLE' ) ) {
	define( 'CIE_PAGE_EDIT_JS_HANDLE', 'cie-page-editor-script' );
}

if ( ! defined( 'CIE_MIGRATION_JS_HANDLE' ) ) {
	define( 'CIE_MIGRATION_JS_HANDLE', 'cie-migration-script' );
}

if ( ! defined( 'CIE_DATA_TOOLS_JS_HANDLE' ) ) {
	define( 'CIE_DATA_TOOLS_JS_HANDLE', 'cie-data-tools-script' );
}

// Batch processing
if ( ! defined( 'CIE_BATCH_SIZE' ) ) {
	define( 'CIE_BATCH_SIZE', 50 );
}

// Post type
if ( ! defined( 'CIE_POST_TYPE' ) ) {
	define( 'CIE_POST_TYPE', 'page' );
}
