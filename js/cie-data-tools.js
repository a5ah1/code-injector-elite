/**
 * Data Tools JavaScript
 *
 * Handles usage reporting and bulk data deletion with batch processing.
 *
 * @package CodeInjectorElite
 */

(function($) {
	'use strict';

	/**
	 * Initialize data tools
	 */
	function init() {
		// Usage report buttons
		$('.cie-usage-report-btn').on('click', function() {
			const $button = $(this);
			const type = $button.data('type');
			runUsageReport(type, $button);
		});

		// Delete global button
		$('.cie-delete-global-btn').on('click', function() {
			const $button = $(this);
			deleteGlobalData($button);
		});

		// Check count buttons
		$('.cie-check-count-btn').on('click', function() {
			const $button = $(this);
			const type = $button.data('type');
			checkDeleteCount(type, $button);
		});

		// Delete batch buttons (dynamically created)
		$(document).on('click', '.cie-delete-all-btn', function() {
			const $button = $(this);
			const type = $button.data('type');
			const count = $button.data('count');
			deleteAllData(type, count, $button);
		});
	}

	/**
	 * Run usage report
	 *
	 * @param {string} type - Type of data (post/page)
	 * @param {jQuery} $button - Button element
	 */
	function runUsageReport(type, $button) {
		const $results = $('#cie-usage-' + type + '-results');

		// Disable button and show loading
		$button.prop('disabled', true);
		$button.append('<span class="cie-spinner"></span>');

		// Clear previous results
		$results.removeClass('show success warning').html('');

		// Make AJAX request
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'cie_usage_report',
				type: type,
				nonce: cieDataTools.nonce
			},
			success: function(response) {
				if (response.success) {
					displayUsageReport(response.data, type, $results);
				} else {
					showError($results, response.data.message || 'An error occurred');
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
	 * Display usage report results
	 *
	 * @param {Object} data - Report data
	 * @param {string} type - Type of data
	 * @param {jQuery} $results - Results container
	 */
	function displayUsageReport(data, type, $results) {
		if (data.found === 0) {
			$results.addClass('show success').html('<p><strong>No ' + type + 's found with code injection data.</strong></p>');
			return;
		}

		$results.addClass('show warning');

		let html = '<p><strong>Found ' + data.found + ' ' + type + '(s) with code:</strong></p>';
		html += '<p class="cie-stats">';
		html += data.header_count + ' with header only, ';
		html += data.footer_count + ' with footer only, ';
		html += data.both_count + ' with both';
		html += '</p>';

		// Display table
		html += '<table class="cie-migration-table">';
		html += '<thead><tr><th>ID</th><th>Title</th><th>Fields</th><th>Actions</th></tr></thead>';
		html += '<tbody>';

		data.items.forEach(function(item) {
			html += '<tr>';
			html += '<td>' + item.id + '</td>';
			html += '<td>' + item.title + '</td>';
			html += '<td>' + item.fields.join(', ') + '</td>';
			html += '<td><a href="' + item.edit_url + '" target="_blank">Edit</a></td>';
			html += '</tr>';
		});

		html += '</tbody></table>';

		$results.html(html);
	}

	/**
	 * Check count for deletion
	 *
	 * @param {string} type - Type of data
	 * @param {jQuery} $button - Button element
	 */
	function checkDeleteCount(type, $button) {
		const $results = $('#cie-delete-' + type + '-results');

		// Disable button and show loading
		$button.prop('disabled', true);
		$button.append('<span class="cie-spinner"></span>');

		// Clear previous results
		$results.removeClass('show success warning error').html('');

		// Make AJAX request
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'cie_delete_count',
				type: type,
				nonce: cieDataTools.nonce
			},
			success: function(response) {
				if (response.success) {
					displayDeleteCount(response.data.count, type, $results);
				} else {
					showError($results, response.data.message || 'An error occurred');
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
	 * Display delete count
	 *
	 * @param {number} count - Number of items
	 * @param {string} type - Type of data
	 * @param {jQuery} $results - Results container
	 */
	function displayDeleteCount(count, type, $results) {
		if (count === 0) {
			$results.addClass('show success').html('<p><strong>No ' + type + 's found with code injection data.</strong></p>');
			return;
		}

		$results.addClass('show warning');

		let html = '<div class="cie-danger-zone">';
		html += '<p><strong>⚠️ Warning: Destructive Action</strong></p>';
		html += '<p>Found <strong>' + count + ' ' + type + '(s)</strong> with code injection data.</p>';
		html += '<p>This action will permanently delete all code injection data from these ' + type + 's. This cannot be undone!</p>';
		html += '<button type="button" class="button button-primary cie-delete-all-btn" data-type="' + type + '" data-count="' + count + '">';
		html += 'Delete All ' + count + ' Item(s)';
		html += '</button>';
		html += '</div>';

		$results.html(html);
	}

	/**
	 * Delete global data
	 *
	 * @param {jQuery} $button - Button element
	 */
	function deleteGlobalData($button) {
		if (!confirm('Are you sure you want to delete all global code? This action cannot be undone!')) {
			return;
		}

		const $results = $('#cie-delete-global-results');

		// Disable button and show loading
		$button.prop('disabled', true);
		$button.append('<span class="cie-spinner"></span>');

		// Make AJAX request
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'cie_delete_global',
				nonce: cieDataTools.nonce
			},
			success: function(response) {
				if (response.success) {
					$results.removeClass('warning error').addClass('show success');
					$results.html('<p><strong>Success!</strong> ' + response.data.message + '</p>');
				} else {
					showError($results, response.data.message || 'Deletion failed');
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
	 * Delete all data in batches
	 *
	 * @param {string} type - Type of data
	 * @param {number} totalCount - Total items to delete
	 * @param {jQuery} $button - Button element
	 */
	async function deleteAllData(type, totalCount, $button) {
		// Double confirmation with typed confirmation
		const confirmation = prompt(
			'⚠️ PERMANENT DELETION WARNING\n\n' +
			'You are about to delete code injection data from ' + totalCount + ' ' + type + '(s).\n\n' +
			'This action CANNOT be undone!\n\n' +
			'Type "DELETE" (in capital letters) to confirm:'
		);

		if (confirmation !== 'DELETE') {
			alert('Deletion cancelled. The text you entered did not match "DELETE".');
			return;
		}

		const $container = $button.closest('.cie-migration-results');

		// Hide button and show progress
		$button.hide();

		let html = '<div class="cie-progress-container">';
		html += '<p><strong>Deleting...</strong></p>';
		html += '<div class="cie-progress-bar"><div class="cie-progress-fill" style="width: 0%"></div></div>';
		html += '<p class="cie-progress-text">0 / ' + totalCount + ' (0%)</p>';
		html += '</div>';

		$container.html(html);
		$container.removeClass('warning').addClass('show');

		const batchSize = 50;
		let processed = 0;
		let hasError = false;

		// Process in batches
		while (processed < totalCount && !hasError) {
			try {
				const result = await deleteBatch(type, processed, batchSize);

				if (!result.success) {
					hasError = true;
					showError($container, result.message || 'Deletion failed');
					break;
				}

				processed += result.deleted;

				// Update progress
				const percentage = Math.round((processed / totalCount) * 100);
				$container.find('.cie-progress-fill').css('width', percentage + '%');
				$container.find('.cie-progress-text').text(processed + ' / ' + totalCount + ' (' + percentage + '%)');

				// If no items were deleted in this batch, we're done
				if (result.deleted === 0) {
					break;
				}
			} catch (error) {
				hasError = true;
				showError($container, 'Failed to communicate with server');
				break;
			}
		}

		// Show completion message
		if (!hasError) {
			$container.removeClass('warning').addClass('success');
			$container.html('<p><strong>Success!</strong> Deleted code injection data from ' + processed + ' ' + type + '(s).</p>');
		}
	}

	/**
	 * Delete a single batch
	 *
	 * @param {string} type - Type of data
	 * @param {number} offset - Batch offset
	 * @param {number} limit - Batch size
	 * @returns {Promise} Promise resolving to result object
	 */
	function deleteBatch(type, offset, limit) {
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'cie_delete_batch',
					type: type,
					offset: offset,
					limit: limit,
					nonce: cieDataTools.nonce
				},
				success: function(response) {
					if (response.success) {
						resolve({
							success: true,
							deleted: response.data.deleted
						});
					} else {
						resolve({
							success: false,
							message: response.data.message || 'Batch deletion failed'
						});
					}
				},
				error: function() {
					reject(new Error('AJAX request failed'));
				}
			});
		});
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
