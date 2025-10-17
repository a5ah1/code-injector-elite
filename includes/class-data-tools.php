<?php
/**
 * Data Tools Management Class
 *
 * Handles usage reporting and bulk data deletion.
 *
 * @package CodeInjectorElite
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Data tools manager class
 */
class CIE_Data_Tools {

	/**
	 * Constructor - setup hooks
	 */
	public function __construct() {
		add_action( 'wp_ajax_cie_usage_report', array( $this, 'ajax_usage_report' ) );
		add_action( 'wp_ajax_cie_delete_count', array( $this, 'ajax_delete_count' ) );
		add_action( 'wp_ajax_cie_delete_batch', array( $this, 'ajax_delete_batch' ) );
		add_action( 'wp_ajax_cie_delete_global', array( $this, 'ajax_delete_global' ) );
	}

	/**
	 * AJAX handler for usage reporting
	 */
	public function ajax_usage_report() {
		// Verify nonce
		check_ajax_referer( 'cie_data_tools_nonce', 'nonce' );

		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
		}

		// Get type from request
		$type = isset( $_POST['type'] ) ? sanitize_key( $_POST['type'] ) : '';

		switch ( $type ) {
			case 'post':
				$result = $this->get_usage_report( 'post' );
				break;
			case 'page':
				$result = $this->get_usage_report( 'page' );
				break;
			default:
				wp_send_json_error( array( 'message' => 'Invalid type' ) );
				return;
		}

		wp_send_json_success( $result );
	}

	/**
	 * AJAX handler for getting delete count
	 */
	public function ajax_delete_count() {
		// Verify nonce
		check_ajax_referer( 'cie_data_tools_nonce', 'nonce' );

		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
		}

		// Get type from request
		$type = isset( $_POST['type'] ) ? sanitize_key( $_POST['type'] ) : '';

		$count = 0;
		switch ( $type ) {
			case 'post':
			case 'page':
				$count = $this->get_post_type_count( $type );
				break;
			default:
				wp_send_json_error( array( 'message' => 'Invalid type' ) );
				return;
		}

		wp_send_json_success( array( 'count' => $count ) );
	}

	/**
	 * AJAX handler for batch deletion
	 */
	public function ajax_delete_batch() {
		// Verify nonce
		check_ajax_referer( 'cie_data_tools_nonce', 'nonce' );

		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
		}

		// Get parameters
		$type   = isset( $_POST['type'] ) ? sanitize_key( $_POST['type'] ) : '';
		$offset = isset( $_POST['offset'] ) ? intval( $_POST['offset'] ) : 0;
		$limit  = isset( $_POST['limit'] ) ? intval( $_POST['limit'] ) : CIE_BATCH_SIZE;

		// Ensure limit doesn't exceed maximum
		$limit = min( $limit, CIE_BATCH_SIZE );

		$deleted = 0;
		switch ( $type ) {
			case 'post':
			case 'page':
				$deleted = $this->delete_post_type_batch( $type, $offset, $limit );
				break;
			default:
				wp_send_json_error( array( 'message' => 'Invalid type' ) );
				return;
		}

		wp_send_json_success( array( 'deleted' => $deleted ) );
	}

	/**
	 * AJAX handler for deleting global settings
	 */
	public function ajax_delete_global() {
		// Verify nonce
		check_ajax_referer( 'cie_data_tools_nonce', 'nonce' );

		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
		}

		$deleted = 0;

		if ( get_option( CIE_OPTION_GLOBAL_HEADER, '' ) !== '' ) {
			delete_option( CIE_OPTION_GLOBAL_HEADER );
			$deleted++;
		}

		if ( get_option( CIE_OPTION_GLOBAL_FOOTER, '' ) !== '' ) {
			delete_option( CIE_OPTION_GLOBAL_FOOTER );
			$deleted++;
		}

		if ( $deleted > 0 ) {
			wp_send_json_success( array(
				'message' => sprintf( 'Successfully deleted %d global option(s).', $deleted ),
			) );
		} else {
			wp_send_json_error( array( 'message' => 'No global data found to delete.' ) );
		}
	}

	/**
	 * Get usage report for a post type
	 *
	 * @param string $post_type Post type to check
	 * @return array Usage report data
	 */
	private function get_usage_report( $post_type ) {
		global $wpdb;

		// Query posts with current meta fields
		$query = $wpdb->prepare(
			"SELECT DISTINCT p.ID, p.post_title, pm.meta_key
			FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
			WHERE p.post_type = %s
			AND pm.meta_key IN (%s, %s)
			AND pm.meta_value != ''
			ORDER BY p.ID ASC",
			$post_type,
			CIE_META_PAGE_HEADER,
			CIE_META_PAGE_FOOTER
		);

		$results = $wpdb->get_results( $query );

		// Group by post ID
		$items       = array();
		$header_count = 0;
		$footer_count = 0;
		$both_count   = 0;

		foreach ( $results as $row ) {
			if ( ! isset( $items[ $row->ID ] ) ) {
				$items[ $row->ID ] = array(
					'id'     => $row->ID,
					'title'  => $row->post_title,
					'fields' => array(),
					'edit_url' => get_edit_post_link( $row->ID ),
				);
			}

			if ( $row->meta_key === CIE_META_PAGE_HEADER ) {
				$items[ $row->ID ]['fields'][] = 'Header';
			} elseif ( $row->meta_key === CIE_META_PAGE_FOOTER ) {
				$items[ $row->ID ]['fields'][] = 'Footer';
			}
		}

		// Calculate statistics
		foreach ( $items as $item ) {
			$has_header = in_array( 'Header', $item['fields'], true );
			$has_footer = in_array( 'Footer', $item['fields'], true );

			if ( $has_header && $has_footer ) {
				$both_count++;
			} elseif ( $has_header ) {
				$header_count++;
			} elseif ( $has_footer ) {
				$footer_count++;
			}
		}

		return array(
			'found'        => count( $items ),
			'items'        => array_values( $items ),
			'header_count' => $header_count,
			'footer_count' => $footer_count,
			'both_count'   => $both_count,
		);
	}

	/**
	 * Get count of posts/pages with code injection data
	 *
	 * @param string $post_type Post type to check
	 * @return int Count of items
	 */
	private function get_post_type_count( $post_type ) {
		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT COUNT(DISTINCT p.ID)
			FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
			WHERE p.post_type = %s
			AND pm.meta_key IN (%s, %s)
			AND pm.meta_value != ''",
			$post_type,
			CIE_META_PAGE_HEADER,
			CIE_META_PAGE_FOOTER
		);

		return (int) $wpdb->get_var( $query );
	}

	/**
	 * Delete a batch of post metadata
	 *
	 * @param string $post_type Post type to delete from
	 * @param int    $offset Offset for batch
	 * @param int    $limit Batch size
	 * @return int Number of items deleted
	 */
	private function delete_post_type_batch( $post_type, $offset, $limit ) {
		global $wpdb;

		// Get batch of post IDs
		$query = $wpdb->prepare(
			"SELECT DISTINCT p.ID
			FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
			WHERE p.post_type = %s
			AND pm.meta_key IN (%s, %s)
			AND pm.meta_value != ''
			ORDER BY p.ID ASC
			LIMIT %d OFFSET %d",
			$post_type,
			CIE_META_PAGE_HEADER,
			CIE_META_PAGE_FOOTER,
			$limit,
			$offset
		);

		$post_ids = $wpdb->get_col( $query );

		if ( empty( $post_ids ) ) {
			return 0;
		}

		// Delete metadata for this batch
		foreach ( $post_ids as $post_id ) {
			delete_post_meta( $post_id, CIE_META_PAGE_HEADER );
			delete_post_meta( $post_id, CIE_META_PAGE_FOOTER );
		}

		return count( $post_ids );
	}
}
