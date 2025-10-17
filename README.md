# Code Injector Elite

![WordPress Plugin Version](https://img.shields.io/badge/version-1.0.4-blue)
![WordPress Compatibility](https://img.shields.io/badge/wordpress-5.2%2B-blue)
![PHP Compatibility](https://img.shields.io/badge/php-7.2%2B-blue)
![License](https://img.shields.io/badge/license-MIT-green)

Professional code injection plugin for WordPress. Inject custom HTML, JavaScript, and CSS into page headers and footers with precision and control.

## Features

- **Two-Tier Injection System**
  - Global code: Applied site-wide to every page
  - Page-specific code: Applied to individual posts or pages only

- **Flexible Control**
  - Enable/disable code injection for posts and pages independently
  - Non-destructive - disabling preserves your data

- **CodeMirror Integration**
  - Syntax highlighting for HTML, JavaScript, and CSS
  - The same professional editor used in WordPress Theme Editor

- **Advanced Tools**
  - Usage reports to track which pages have custom code
  - Bulk data deletion with progress tracking
  - Legacy data migration tool

- **Security-Conscious**
  - Capability checks (admin and editor only)
  - CSRF protection with nonces
  - PHP tag removal

## Installation

### From GitHub

1. Download the latest release from the [Releases page](https://github.com/a5ah1/code-injector-elite/releases)
2. Upload the `code-injector-elite` folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Navigate to Settings → Code Injector Elite to configure

### Manual Installation

1. Clone this repository into your WordPress plugins directory:
   ```bash
   cd wp-content/plugins
   git clone https://github.com/a5ah1/code-injector-elite.git
   ```
2. Activate the plugin through the 'Plugins' menu in WordPress

## Usage

### Global Code Injection

1. Go to **Settings → Code Injector Elite**
2. Navigate to the **Settings** tab
3. Add your code in the **Header Code** or **Footer Code** fields:
   - Header code injects into `<head>` section
   - Footer code injects before closing `</body>` tag
4. Click **Save and Deploy Code**

**Use cases:**
- Google Analytics or tracking scripts
- Site-wide custom CSS
- Third-party integrations (chat widgets, etc.)
- Custom meta tags

### Page-Specific Code Injection

1. Edit any post or page
2. Scroll to the **Code Injector Elite** meta box
3. Add your custom code in the **Header Code** or **Footer Code** fields
4. Publish or update the page

**Use cases:**
- Landing page specific tracking
- Custom styles for individual pages
- Page-specific JavaScript functionality
- A/B testing scripts

### Enable/Disable for Post Types

1. Go to **Settings → Code Injector Elite → Settings** tab
2. Under **Plugin Activation**, check/uncheck:
   - **Enable for Posts** - Show meta boxes on post edit screens
   - **Enable for Pages** - Show meta boxes on page edit screens
3. Click **Save Activation Settings**

## Requirements

- **WordPress:** 5.2 or higher
- **PHP:** 7.2 or higher
- **User Capabilities:** `manage_options` for global settings, `edit_post` for page-specific code

## Automatic Updates

This plugin supports automatic updates via GitHub! When a new version is released, you'll receive an update notification in your WordPress admin just like plugins from the WordPress.org repository.

To receive updates:
1. Ensure your WordPress site can connect to GitHub (most hosting environments allow this)
2. Check for updates in **Dashboard → Updates** or **Plugins → Installed Plugins**

## Documentation

### Plugin Structure

```
code-injector-elite/
├── code-injector-elite.php    # Main plugin file
├── includes/                   # Core classes
│   ├── constants.php           # Plugin constants
│   ├── class-plugin.php        # Main plugin class
│   ├── class-assets.php        # Asset management
│   ├── class-settings.php      # Settings page
│   ├── class-meta-boxes.php    # Post/page meta boxes
│   ├── class-frontend.php      # Frontend code injection
│   ├── class-migration.php     # Data migration tools
│   └── class-data-tools.php    # Usage reports & bulk deletion
├── views/                      # Template files
│   ├── settings-page.php       # Settings page template
│   └── meta-box.php           # Meta box template
├── css/                        # Stylesheets
├── js/                         # JavaScript files
└── uninstall.php              # Uninstall cleanup
```

### Developer Hooks

The plugin uses standard WordPress hooks:
- `plugins_loaded` - Plugin initialization
- `admin_menu` - Settings page registration
- `add_meta_boxes` - Meta box registration
- `save_post` - Meta box data saving
- `wp_head` - Header code injection
- `wp_footer` - Footer code injection

## Security

This plugin is designed for **trusted administrators** and follows a trust-based security model:

**What's Protected:**
- Capability checks ensure only admins and editors can inject code
- CSRF protection via nonces
- PHP tags are stripped (they wouldn't execute anyway)

**What's Intentionally Allowed:**
- HTML, JavaScript, and CSS injection (by design)
- Inline scripts and styles
- External resource loading

**Important:** Only grant access to users you trust. This plugin is designed for DIY users who need direct code injection capabilities.

## Migration from Legacy Versions

If you're upgrading from an older version using `attr_*` field names:

1. Go to **Settings → Code Injector Elite → Migration** tab
2. Click the detection buttons to scan for legacy data
3. Review the detected items
4. Click **Migrate** to transfer data to the new format
5. Legacy data is automatically removed after successful migration

## Data Management

### Usage Reports

View which posts and pages have custom code:
1. Navigate to **Data Tools** tab
2. Click **Check Posts Usage** or **Check Pages Usage**
3. View detailed report with edit links

### Bulk Deletion

Permanently remove all code injection data:
1. Navigate to **Data Tools** tab
2. Scroll to **Bulk Data Deletion** section
3. Follow the prompts (requires typed confirmation)

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history and updates.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Support

- **Issues:** [GitHub Issues](https://github.com/a5ah1/code-injector-elite/issues)
- **Documentation:** This README and inline code documentation

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Author

**a5ah1**
- GitHub: [@a5ah1](https://github.com/a5ah1)

---

**Note:** This plugin intentionally allows raw HTML/JS/CSS injection for maximum flexibility. It's designed for users who understand code and need direct control over their site's markup.
