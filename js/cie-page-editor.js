/**
 * 
 * Script for adding CodeMirror to the textareas on individual pages
 * 
 */

jQuery(window).load(function ($) {

  // Initialize CodeMirror on textareas with the class 'cie-meta-box'
  jQuery('.cie-meta-box').each(function () {

    var textarea = jQuery(this); // Reference to the original textarea
    var editor = wp.codeEditor.initialize(textarea, codeEditorSettings);

    // Refresh CodeMirror instances after a slight delay to ensure proper display
    setTimeout(function () {
      editor.codemirror.refresh();
    }, 1);

    editor.codemirror.on('change', function () {
      // Update the hidden textarea with the content of the CodeMirror editor
      textarea.val(editor.codemirror.getValue());
    });
  });

});
