/**
 * Settings Page Script
 *
 * Initializes CodeMirror on the settings page textareas.
 */

jQuery(document).ready(function($) {
	// Initialize CodeMirror on each textarea with the 'cie-textarea' class
	$('.cie-textarea').each(function() {
		wp.codeEditor.initialize($(this), codeEditorSettings);
	});
});
