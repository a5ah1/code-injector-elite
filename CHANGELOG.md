# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.4] - 2024-10-16

### Added
- GitHub integration for automatic updates
- Comprehensive documentation in README.md
- MIT License file
- .gitignore for repository management

### Changed
- Prepared plugin for public GitHub release
- Updated plugin structure for better distribution

## [1.0.3] - 2024

### Added
- About tab with plugin information
- Enhanced settings page UI

### Changed
- Improved user interface consistency

## [1.0.2] - 2024

### Added
- Data management tools (usage reports and bulk deletion)
- Batch processing for large datasets with progress tracking
- Post and page usage reporting
- Typed confirmation for destructive operations

### Changed
- Improved admin interface organization with tabbed navigation

## [1.0.1] - 2024

### Added
- Legacy data migration tools (attr_* to cie_*)
- AJAX-powered migration detection and execution
- Migration tab in settings page

### Fixed
- Compatibility with older plugin versions

## [1.0.0] - 2024

### Added
- Initial release
- Two-tier code injection system (global and page-specific)
- CodeMirror integration for syntax highlighting
- Enable/disable controls for posts and pages
- Global header and footer code settings
- Page-specific meta boxes
- WordPress capability-based security
- CSRF protection with nonces
- PHP tag removal for safety
- Professional admin interface

### Features
- OOP architecture with singleton pattern
- Class-based component structure
- Constants-based configuration
- View template separation
- WordPress coding standards compliance

---

## Release Notes

### Version Numbering
- **Major version** (X.0.0): Breaking changes or major feature additions
- **Minor version** (1.X.0): New features, backwards compatible
- **Patch version** (1.0.X): Bug fixes and minor improvements

### Upgrade Process
Automatic updates are delivered via GitHub releases. To receive updates:
1. Ensure the plugin is installed from this repository
2. WordPress will check for updates automatically
3. Update from Dashboard → Updates or Plugins → Installed Plugins

### Support
For issues, feature requests, or questions, please visit:
- [GitHub Issues](https://github.com/a5ah1/code-injector-elite/issues)
