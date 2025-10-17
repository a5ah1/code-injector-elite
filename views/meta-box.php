<?php
/**
 * Meta Box Template
 *
 * @package CodeInjectorElite
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="<?php echo esc_attr( CIE_META_BOX_ID ); ?>">

	<p style="color: slategray;">
		<?php esc_html_e( '🔥 Important: All code will be included as-is. The user is responsible for validating the code and ensuring its safety.', CIE_TEXT_DOMAIN ); ?>
	</p>

	<h4><?php echo esc_html__( 'Add to HTML', CIE_TEXT_DOMAIN ); ?> <code>&lt;head&gt;</code>:</h4>

	<div class="cie-textarea-container">
		<textarea
			id="<?php echo esc_attr( CIE_META_PAGE_HEADER ); ?>"
			name="<?php echo esc_attr( CIE_META_PAGE_HEADER ); ?>"
			class="cie-meta-box"><?php echo esc_textarea( $page_header_code ); ?></textarea>
	</div>

	<h4><?php echo esc_html__( 'Add just before closing', CIE_TEXT_DOMAIN ); ?> <code>&lt;/body&gt;</code> <?php esc_html_e( 'tag:', CIE_TEXT_DOMAIN ); ?></h4>

	<div class="cie-textarea-container">
		<textarea
			id="<?php echo esc_attr( CIE_META_PAGE_FOOTER ); ?>"
			name="<?php echo esc_attr( CIE_META_PAGE_FOOTER ); ?>"
			class="cie-meta-box"><?php echo esc_textarea( $page_footer_code ); ?></textarea>
	</div>

</div>
