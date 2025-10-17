<?php
/**
 * Migration Management Class
 *
 * Handles detection and migration of legacy data fields.
 *
 * @package CodeInjectorElite
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Migration manager class
 */
class CIE_Migration {

	/**
	 * Legacy field names
	 */
	const LEGACY_GLOBAL_HEADER = 'attr_global_header_code';
	const LEGACY_GLOBAL_FOOTER = 'attr_global_footer_code';
	const LEGACY_PAGE_HEADER   = 'attr_page_header_code';
	const LEGACY_PAGE_FOOTER   = 'attr_page_footer_code';

	/**
	 * Constructor - setup hooks
	 */
	public function __construct() {
		add_action( 'wp_ajax_cie_detect_legacy', array( $this, 'ajax_detect_legacy' ) );
		add_action( 'wp_ajax_cie_migrate_legacy', array( $this, 'ajax_migrate_legacy' ) );
	}

	/**
	 * AJAX handler for detecting legacy data
	 */
	public function ajax_detect_legacy() {
		// Verify nonce
		check_ajax_referer( 'cie_migration_nonce', 'nonce' );

		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
		}

		// Get type from request
		$type = isset( $_POST['type'] ) ? sanitize_key( $_POST['type'] ) : '';

		switch ( $type ) {
			case 'global':
				$result = $this->detect_global_legacy();
				break;
			case 'post':
				$result = $this->detect_post_legacy();
				break;
			case 'page':
				$result = $this->detect_page_legacy();
				break;
			default:
				wp_send_json_error( array( 'message' => 'Invalid type' ) );
				return;
		}

		wp_send_json_success( $result );
	}

	/**
	 * AJAX handler for migrating legacy data
	 */
	public function ajax_migrate_legacy() {
		// Verify nonce
		check_ajax_referer( 'cie_migration_nonce', 'nonce' );

		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
		}

		// Get type from request
		$type = isset( $_POST['type'] ) ? sanitize_key( $_POST['type'] ) : '';

		switch ( $type ) {
			case 'global':
				$result = $this->migrate_global_legacy();
				break;
			case 'post':
				$result = $this->migrate_post_legacy();
				break;
			case 'page':
				$result = $this->migrate_page_legacy();
				break;
			default:
				wp_send_json_error( array( 'message' => 'Invalid type' ) );
				return;
		}

		if ( $result['success'] ) {
			wp_send_json_success( array( 'message' => $result['message'] ) );
		} else {
			wp_send_json_error( array( 'message' => $result['message'] ) );
		}
	}

	/**
	 * Detect legacy global options
	 *
	 * @return array Detection results
	 */
	private function detect_global_legacy() {
		$header = get_option( self::LEGACY_GLOBAL_HEADER, '' );
		$footer = get_option( self::LEGACY_GLOBAL_FOOTER, '' );

		$found_items = array();

		if ( ! empty( $header ) ) {
			$found_items['header'] = true;
		}

		if ( ! empty( $footer ) ) {
			$found_items['footer'] = true;
		}

		return array(
			'found' => count( $found_items ),
			'items' => $found_items,
		);
	}

	/**
	 * Detect legacy post meta
	 *
	 * @return array Detection results
	 */
	private function detect_post_legacy() {
		return $this->detect_post_type_legacy( 'post' );
	}

	/**
	 * Detect legacy page meta
	 *
	 * @return array Detection results
	 */
	private function detect_page_legacy() {
		return $this->detect_post_type_legacy( 'page' );
	}

	/**
	 * Detect legacy meta for a post type
	 *
	 * @param string $post_type Post type to check
	 * @return array Detection results
	 */
	private function detect_post_type_legacy( $post_type ) {
		global $wpdb;

		// Query posts with legacy meta fields
		$query = $wpdb->prepare(
			"SELECT DISTINCT p.ID, p.post_title, pm.meta_key
			FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
			WHERE p.post_type = %s
			AND pm.meta_key IN (%s, %s)
			AND pm.meta_value != ''
			ORDER BY p.ID ASC",
			$post_type,
			self::LEGACY_PAGE_HEADER,
			self::LEGACY_PAGE_FOOTER
		);

		$results = $wpdb->get_results( $query );

		// Group by post ID
		$items = array();
		foreach ( $results as $row ) {
			if ( ! isset( $items[ $row->ID ] ) ) {
				$items[ $row->ID ] = array(
					'id'     => $row->ID,
					'title'  => $row->post_title,
					'fields' => array(),
				);
			}
			$items[ $row->ID ]['fields'][] = $row->meta_key;
		}

		return array(
			'found' => count( $items ),
			'items' => array_values( $items ),
		);
	}

	/**
	 * Migrate global legacy data
	 *
	 * @return array Migration result
	 */
	private function migrate_global_legacy() {
		$migrated = 0;

		// Migrate header
		$header = get_option( self::LEGACY_GLOBAL_HEADER, '' );
		if ( ! empty( $header ) ) {
			update_option( CIE_OPTION_GLOBAL_HEADER, $header );
			delete_option( self::LEGACY_GLOBAL_HEADER );
			$migrated++;
		}

		// Migrate footer
		$footer = get_option( self::LEGACY_GLOBAL_FOOTER, '' );
		if ( ! empty( $footer ) ) {
			update_option( CIE_OPTION_GLOBAL_FOOTER, $footer );
			delete_option( self::LEGACY_GLOBAL_FOOTER );
			$migrated++;
		}

		if ( $migrated > 0 ) {
			return array(
				'success' => true,
				'message' => sprintf( 'Successfully migrated %d global option(s).', $migrated ),
			);
		}

		return array(
			'success' => false,
			'message' => 'No legacy data found to migrate.',
		);
	}

	/**
	 * Migrate post legacy data
	 *
	 * @return array Migration result
	 */
	private function migrate_post_legacy() {
		return $this->migrate_post_type_legacy( 'post' );
	}

	/**
	 * Migrate page legacy data
	 *
	 * @return array Migration result
	 */
	private function migrate_page_legacy() {
		return $this->migrate_post_type_legacy( 'page' );
	}

	/**
	 * Migrate legacy meta for a post type
	 *
	 * @param string $post_type Post type to migrate
	 * @return array Migration result
	 */
	private function migrate_post_type_legacy( $post_type ) {
		global $wpdb;

		// Find all posts with legacy meta
		$query = $wpdb->prepare(
			"SELECT DISTINCT p.ID
			FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
			WHERE p.post_type = %s
			AND pm.meta_key IN (%s, %s)
			AND pm.meta_value != ''",
			$post_type,
			self::LEGACY_PAGE_HEADER,
			self::LEGACY_PAGE_FOOTER
		);

		$post_ids = $wpdb->get_col( $query );

		if ( empty( $post_ids ) ) {
			return array(
				'success' => false,
				'message' => 'No legacy data found to migrate.',
			);
		}

		$migrated = 0;

		foreach ( $post_ids as $post_id ) {
			// Migrate header
			$header = get_post_meta( $post_id, self::LEGACY_PAGE_HEADER, true );
			if ( ! empty( $header ) ) {
				update_post_meta( $post_id, CIE_META_PAGE_HEADER, $header );
				delete_post_meta( $post_id, self::LEGACY_PAGE_HEADER );
			}

			// Migrate footer
			$footer = get_post_meta( $post_id, self::LEGACY_PAGE_FOOTER, true );
			if ( ! empty( $footer ) ) {
				update_post_meta( $post_id, CIE_META_PAGE_FOOTER, $footer );
				delete_post_meta( $post_id, self::LEGACY_PAGE_FOOTER );
			}

			$migrated++;
		}

		return array(
			'success' => true,
			'message' => sprintf( 'Successfully migrated %d %s(s).', $migrated, $post_type ),
		);
	}
}
