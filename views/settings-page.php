<?php
/**
 * Settings Page Template
 *
 * @package CodeInjectorElite
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get current tab
$current_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'settings';
?>

<div class="wrap">

	<h2><?php echo esc_html__( 'Code Injector Elite', CIE_TEXT_DOMAIN ); ?></h2>

	<!-- Tab Navigation -->
	<nav class="nav-tab-wrapper">
		<a href="<?php echo esc_url( admin_url( 'options-general.php?page=' . CIE_SETTINGS_PAGE_SLUG . '&tab=settings' ) ); ?>"
		   class="nav-tab <?php echo $current_tab === 'settings' ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Settings', CIE_TEXT_DOMAIN ); ?>
		</a>
		<a href="<?php echo esc_url( admin_url( 'options-general.php?page=' . CIE_SETTINGS_PAGE_SLUG . '&tab=migration' ) ); ?>"
		   class="nav-tab <?php echo $current_tab === 'migration' ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Migration', CIE_TEXT_DOMAIN ); ?>
		</a>
		<a href="<?php echo esc_url( admin_url( 'options-general.php?page=' . CIE_SETTINGS_PAGE_SLUG . '&tab=data-tools' ) ); ?>"
		   class="nav-tab <?php echo $current_tab === 'data-tools' ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Data Tools', CIE_TEXT_DOMAIN ); ?>
		</a>
		<a href="<?php echo esc_url( admin_url( 'options-general.php?page=' . CIE_SETTINGS_PAGE_SLUG . '&tab=about' ) ); ?>"
		   class="nav-tab <?php echo $current_tab === 'about' ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'About', CIE_TEXT_DOMAIN ); ?>
		</a>
	</nav>

	<div class="cie-tab-content">

		<?php if ( $current_tab === 'settings' ) : ?>
			<!-- Settings Tab -->

			<!-- Enable/Disable Settings -->
			<div class="cie-settings-box">
				<h3><?php echo esc_html__( 'Plugin Activation', CIE_TEXT_DOMAIN ); ?></h3>
				<p><?php esc_html_e( 'Control whether code injection is enabled for posts and pages. Disabling will hide the meta boxes on edit screens and prevent code output (data is not deleted).', CIE_TEXT_DOMAIN ); ?></p>

				<form method="post" action="options.php">
					<?php
					settings_fields( CIE_SETTINGS_GROUP_ENABLE );
					do_settings_fields( CIE_SETTINGS_PAGE_SLUG, CIE_SETTINGS_SECTION_ENABLE );
					submit_button( esc_html__( 'Save Activation Settings', CIE_TEXT_DOMAIN ) );
					?>
				</form>
			</div>

			<!-- Global Code Settings -->
			<div class="cie-settings-box">
				<h3><?php echo esc_html__( 'Global scripts and code', CIE_TEXT_DOMAIN ); ?></h3>

				<p>
					<?php
					printf(
						/* translators: %1$s and %2$s are HTML tags */
						esc_html__( 'Code entered into the fields below will be added to the HTML %1$s and the end of the page just before the closing %2$s tag, respectively.', CIE_TEXT_DOMAIN ),
						'<code>&lt;head&gt;</code>',
						'<code>&lt;/body&gt;</code>'
					);
					?>
				</p>

				<p class="cie-warning">
					<?php esc_html_e( '🔥 Important: All code will be included as-is. The user is responsible for validating the code and ensuring its safety.', CIE_TEXT_DOMAIN ); ?>
				</p>

				<form method="post" action="options.php">
					<?php
					settings_fields( CIE_SETTINGS_GROUP );
					do_settings_fields( CIE_SETTINGS_PAGE_SLUG, CIE_SETTINGS_SECTION );
					submit_button( esc_html__( 'Save and Deploy Code', CIE_TEXT_DOMAIN ) );
					?>
				</form>
			</div>

		<?php elseif ( $current_tab === 'migration' ) : ?>
			<!-- Migration Tab -->
			<h3><?php esc_html_e( 'Legacy Data Migration', CIE_TEXT_DOMAIN ); ?></h3>

			<p>
				<?php esc_html_e( 'This tool helps you migrate data from the old field names (attr_*) to the new field names (cie_*).', CIE_TEXT_DOMAIN ); ?>
			</p>

			<p>
				<?php esc_html_e( 'Click each button below to detect legacy data that needs migration:', CIE_TEXT_DOMAIN ); ?>
			</p>

			<div class="cie-migration-tools">
				<div class="cie-migration-section">
					<h4><?php esc_html_e( 'Global Settings', CIE_TEXT_DOMAIN ); ?></h4>
					<p><?php esc_html_e( 'Check for legacy global header and footer code options.', CIE_TEXT_DOMAIN ); ?></p>
					<button type="button" class="button button-secondary cie-detect-legacy" data-type="global">
						<?php esc_html_e( 'Detect Global Legacy Data', CIE_TEXT_DOMAIN ); ?>
					</button>
					<div class="cie-migration-results" id="cie-global-results"></div>
				</div>

				<div class="cie-migration-section">
					<h4><?php esc_html_e( 'Posts', CIE_TEXT_DOMAIN ); ?></h4>
					<p><?php esc_html_e( 'Check all posts for legacy meta fields.', CIE_TEXT_DOMAIN ); ?></p>
					<button type="button" class="button button-secondary cie-detect-legacy" data-type="post">
						<?php esc_html_e( 'Detect Post Legacy Data', CIE_TEXT_DOMAIN ); ?>
					</button>
					<div class="cie-migration-results" id="cie-post-results"></div>
				</div>

				<div class="cie-migration-section">
					<h4><?php esc_html_e( 'Pages', CIE_TEXT_DOMAIN ); ?></h4>
					<p><?php esc_html_e( 'Check all pages for legacy meta fields.', CIE_TEXT_DOMAIN ); ?></p>
					<button type="button" class="button button-secondary cie-detect-legacy" data-type="page">
						<?php esc_html_e( 'Detect Page Legacy Data', CIE_TEXT_DOMAIN ); ?>
					</button>
					<div class="cie-migration-results" id="cie-page-results"></div>
				</div>
			</div>

		<?php elseif ( $current_tab === 'data-tools' ) : ?>
			<!-- Data Tools Tab -->
			<h3><?php esc_html_e( 'Data Management Tools', CIE_TEXT_DOMAIN ); ?></h3>

			<!-- Usage Report Section -->
			<div class="cie-tool-section">
				<h4><?php esc_html_e( 'Usage Report', CIE_TEXT_DOMAIN ); ?></h4>
				<p><?php esc_html_e( 'View which posts and pages currently have code injection data.', CIE_TEXT_DOMAIN ); ?></p>

				<div class="cie-migration-tools">
					<div class="cie-migration-section">
						<h4><?php esc_html_e( 'Posts Usage', CIE_TEXT_DOMAIN ); ?></h4>
						<p><?php esc_html_e( 'Check which posts have code injection data.', CIE_TEXT_DOMAIN ); ?></p>
						<button type="button" class="button button-secondary cie-usage-report-btn" data-type="post">
							<?php esc_html_e( 'Check Posts Usage', CIE_TEXT_DOMAIN ); ?>
						</button>
						<div class="cie-migration-results" id="cie-usage-post-results"></div>
					</div>

					<div class="cie-migration-section">
						<h4><?php esc_html_e( 'Pages Usage', CIE_TEXT_DOMAIN ); ?></h4>
						<p><?php esc_html_e( 'Check which pages have code injection data.', CIE_TEXT_DOMAIN ); ?></p>
						<button type="button" class="button button-secondary cie-usage-report-btn" data-type="page">
							<?php esc_html_e( 'Check Pages Usage', CIE_TEXT_DOMAIN ); ?>
						</button>
						<div class="cie-migration-results" id="cie-usage-page-results"></div>
					</div>
				</div>
			</div>

			<hr>

			<!-- Bulk Deletion Section -->
			<div class="cie-tool-section">
				<h4><?php esc_html_e( 'Bulk Data Deletion', CIE_TEXT_DOMAIN ); ?></h4>
				<p><?php esc_html_e( 'Permanently delete all code injection data. These actions cannot be undone!', CIE_TEXT_DOMAIN ); ?></p>

				<div class="cie-migration-tools">
					<!-- Global Settings Deletion -->
					<div class="cie-migration-section">
						<h4><?php esc_html_e( 'Global Settings', CIE_TEXT_DOMAIN ); ?></h4>
						<p><?php esc_html_e( 'Delete all global header and footer code.', CIE_TEXT_DOMAIN ); ?></p>
						<div class="cie-danger-zone">
							<p><strong><?php esc_html_e( '⚠️ Warning: This will delete global code that appears on all pages!', CIE_TEXT_DOMAIN ); ?></strong></p>
							<button type="button" class="button button-secondary cie-delete-global-btn">
								<?php esc_html_e( 'Delete Global Code', CIE_TEXT_DOMAIN ); ?>
							</button>
						</div>
						<div class="cie-migration-results" id="cie-delete-global-results"></div>
					</div>

					<!-- Post Metadata Deletion -->
					<div class="cie-migration-section">
						<h4><?php esc_html_e( 'Post Metadata', CIE_TEXT_DOMAIN ); ?></h4>
						<p><?php esc_html_e( 'Delete code injection data from all posts.', CIE_TEXT_DOMAIN ); ?></p>
						<button type="button" class="button button-secondary cie-check-count-btn" data-type="post">
							<?php esc_html_e( 'Check Posts', CIE_TEXT_DOMAIN ); ?>
						</button>
						<div class="cie-migration-results" id="cie-delete-post-results"></div>
					</div>

					<!-- Page Metadata Deletion -->
					<div class="cie-migration-section">
						<h4><?php esc_html_e( 'Page Metadata', CIE_TEXT_DOMAIN ); ?></h4>
						<p><?php esc_html_e( 'Delete code injection data from all pages.', CIE_TEXT_DOMAIN ); ?></p>
						<button type="button" class="button button-secondary cie-check-count-btn" data-type="page">
							<?php esc_html_e( 'Check Pages', CIE_TEXT_DOMAIN ); ?>
						</button>
						<div class="cie-migration-results" id="cie-delete-page-results"></div>
					</div>
				</div>
			</div>

		<?php elseif ( $current_tab === 'about' ) : ?>
			<!-- About Tab -->
			<h3><?php esc_html_e( 'About Code Injector Elite', CIE_TEXT_DOMAIN ); ?></h3>

			<div class="cie-settings-box">
				<p><?php esc_html_e( 'Code Injector Elite allows you to inject custom HTML, JavaScript, and CSS into your WordPress site\'s header and footer sections.', CIE_TEXT_DOMAIN ); ?></p>

				<p><?php esc_html_e( 'The plugin operates at two levels:', CIE_TEXT_DOMAIN ); ?></p>
				<ul>
					<li><strong><?php esc_html_e( 'Global Code:', CIE_TEXT_DOMAIN ); ?></strong> <?php esc_html_e( 'Applied site-wide to every page', CIE_TEXT_DOMAIN ); ?></li>
					<li><strong><?php esc_html_e( 'Page-Specific Code:', CIE_TEXT_DOMAIN ); ?></strong> <?php esc_html_e( 'Applied to individual posts or pages only', CIE_TEXT_DOMAIN ); ?></li>
				</ul>

				<p><?php esc_html_e( 'Perfect for adding analytics tracking, custom scripts, third-party integrations, or any custom code snippets without modifying your theme files.', CIE_TEXT_DOMAIN ); ?></p>

				<hr style="margin: 25px 0;">

				<h4><?php esc_html_e( 'License & Disclaimer', CIE_TEXT_DOMAIN ); ?></h4>

				<p><strong><?php esc_html_e( 'License:', CIE_TEXT_DOMAIN ); ?></strong> <?php esc_html_e( 'MIT License', CIE_TEXT_DOMAIN ); ?></p>

				<p><?php esc_html_e( 'This is a private plugin that is made available to the public for free, essentially "for the heck of it."', CIE_TEXT_DOMAIN ); ?></p>

				<p><strong><?php esc_html_e( 'NO WARRANTY, GUARANTEE, OR SUPPORT PROVIDED:', CIE_TEXT_DOMAIN ); ?></strong></p>

				<p style="background: #fff3cd; padding: 10px; border-left: 4px solid #ffc107;">
					<?php esc_html_e( 'This software is provided "as is", without warranty of any kind, express or implied. The author provides no guarantee that it will work correctly or at all. No technical support, bug fixes, or updates are guaranteed. Use at your own risk.', CIE_TEXT_DOMAIN ); ?>
				</p>

				<p>
					<?php
					printf(
						/* translators: %s is the GitHub repository URL */
						esc_html__( 'Source code: %s', CIE_TEXT_DOMAIN ),
						'<a href="https://github.com/a5ah1/code-injector-elite" target="_blank" rel="noopener noreferrer">https://github.com/a5ah1/code-injector-elite</a>'
					);
					?>
				</p>
			</div>

		<?php endif; ?>

	</div>

</div>
