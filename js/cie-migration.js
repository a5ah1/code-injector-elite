/**
 * Migration Tool JavaScript
 *
 * Handles AJAX detection and migration of legacy data.
 *
 * @package CodeInjectorElite
 */

(function($) {
	'use strict';

	/**
	 * Initialize migration tool
	 */
	function init() {
		// Handle detect button clicks
		$('.cie-detect-legacy').on('click', function() {
			const $button = $(this);
			const type = $button.data('type');
			detectLegacyData(type, $button);
		});

		// Handle migrate button clicks (dynamically created)
		$(document).on('click', '.cie-migrate-btn', function() {
			const $button = $(this);
			const type = $button.data('type');
			migrateLegacyData(type, $button);
		});
	}

	/**
	 * Detect legacy data via AJAX
	 *
	 * @param {string} type - Type of data to detect (global, post, page)
	 * @param {jQuery} $button - Button element
	 */
	function detectLegacyData(type, $button) {
		const $results = $('#cie-' + type + '-results');

		// Disable button and show loading
		$button.prop('disabled', true);
		$button.append('<span class="cie-spinner"></span>');

		// Clear previous results
		$results.removeClass('show success error warning').html('');

		// Make AJAX request
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'cie_detect_legacy',
				type: type,
				nonce: cieSettings.nonce
			},
			success: function(response) {
				if (response.success) {
					displayResults(response.data, type, $results);
				} else {
					showError($results, response.data.message || 'An error occurred');
				}
			},
			error: function() {
				showError($results, 'Failed to communicate with server');
			},
			complete: function() {
				// Re-enable button and remove spinner
				$button.prop('disabled', false);
				$button.find('.cie-spinner').remove();
			}
		});
	}

	/**
	 * Migrate legacy data via AJAX
	 *
	 * @param {string} type - Type of data to migrate
	 * @param {jQuery} $button - Button element
	 */
	function migrateLegacyData(type, $button) {
		if (!confirm('Are you sure you want to migrate this data? This will copy the legacy data to the new fields and delete the old fields.')) {
			return;
		}

		const $results = $button.closest('.cie-migration-results');

		// Disable button and show loading
		$button.prop('disabled', true);
		$button.append('<span class="cie-spinner"></span>');

		// Make AJAX request
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'cie_migrate_legacy',
				type: type,
				nonce: cieSettings.nonce
			},
			success: function(response) {
				if (response.success) {
					$results.removeClass('warning').addClass('success');
					$results.html('<p><strong>Success!</strong> ' + response.data.message + '</p>');
				} else {
					showError($results, response.data.message || 'Migration failed');
				}
			},
			error: function() {
				showError($results, 'Failed to communicate with server');
			},
			complete: function() {
				$button.prop('disabled', false);
				$button.find('.cie-spinner').remove();
			}
		});
	}

	/**
	 * Display detection results
	 *
	 * @param {Object} data - Result data
	 * @param {string} type - Type of data
	 * @param {jQuery} $results - Results container
	 */
	function displayResults(data, type, $results) {
		if (data.found === 0) {
			$results.addClass('show success').html('<p><strong>No legacy data found.</strong> All data is using the new field names.</p>');
			return;
		}

		// Show warning with found items
		$results.addClass('show warning');

		let html = '<p><strong>Found ' + data.found + ' item(s) with legacy data:</strong></p>';

		if (type === 'global') {
			html += '<ul>';
			if (data.items.header) {
				html += '<li>Global Header Code (attr_global_header_code)</li>';
			}
			if (data.items.footer) {
				html += '<li>Global Footer Code (attr_global_footer_code)</li>';
			}
			html += '</ul>';
			html += '<button type="button" class="button button-primary cie-migrate-btn" data-type="' + type + '">Migrate Global Data</button>';
		} else {
			// Posts/Pages - show table
			html += '<table class="cie-migration-table">';
			html += '<thead><tr><th>ID</th><th>Title</th><th>Legacy Fields</th></tr></thead>';
			html += '<tbody>';

			data.items.forEach(function(item) {
				html += '<tr>';
				html += '<td>' + item.id + '</td>';
				html += '<td>' + item.title + '</td>';
				html += '<td>' + item.fields.join(', ') + '</td>';
				html += '</tr>';
			});

			html += '</tbody></table>';
			html += '<button type="button" class="button button-primary cie-migrate-btn" data-type="' + type + '">Migrate All ' + data.found + ' Item(s)</button>';
		}

		$results.html(html);
	}

	/**
	 * Show error message
	 *
	 * @param {jQuery} $results - Results container
	 * @param {string} message - Error message
	 */
	function showError($results, message) {
		$results.addClass('show error').html('<p><strong>Error:</strong> ' + message + '</p>');
	}

	// Initialize when document is ready
	$(document).ready(init);

})(jQuery);
