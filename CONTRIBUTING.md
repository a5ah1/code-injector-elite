# Contributing to Code Injector Elite

Thank you for considering contributing to Code Injector Elite! We welcome contributions from the community.

## How to Contribute

### Reporting Bugs

Before creating bug reports, please check the [existing issues](https://github.com/a5ah1/code-injector-elite/issues) to avoid duplicates. When you create a bug report, include as many details as possible using our bug report template.

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, use our feature request template and provide:

- A clear description of the feature
- Why this feature would be useful
- How it should work

### Pull Requests

1. **Fork the repository** and create your branch from `main`
2. **Make your changes** following our coding standards
3. **Test thoroughly** on a local WordPress installation
4. **Update documentation** if needed
5. **Submit a pull request** using our PR template

## Development Setup

### Prerequisites

- WordPress 5.2 or higher
- PHP 7.2 or higher
- Composer (for dependencies)
- Local WordPress development environment (Local, MAMP, Docker, etc.)

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/a5ah1/code-injector-elite.git
   cd code-injector-elite
   ```

2. Install dependencies:
   ```bash
   composer install --no-dev
   ```

3. Symlink or copy to your WordPress plugins directory:
   ```bash
   ln -s $(pwd) /path/to/wordpress/wp-content/plugins/code-injector-elite
   ```

4. Activate the plugin in WordPress admin

## Coding Standards

### PHP Standards

- Follow [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- Use meaningful variable and function names
- Comment complex logic
- Always escape output appropriately
- Always sanitize and validate input

### Code Structure

- **OOP Approach**: Use classes for new features
- **Singleton Pattern**: Main classes use singleton pattern
- **Constants**: Define all constants in `includes/constants.php`
- **Prefix**: Use `CIE_` for constants, `cie_` for data keys
- **Templates**: Keep views separate in `views/` directory

### JavaScript Standards

- Follow [WordPress JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/)
- Use jQuery when interacting with WordPress
- Comment your code
- Maintain backward compatibility

### CSS Standards

- Follow [WordPress CSS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/)
- Use `.cie-` prefix for all classes
- Keep styles maintainable and organized

## Testing

Before submitting a PR, test the following:

### Functionality Tests

1. **Global Code Injection**
   - Add code in Settings tab
   - Verify output in page source
   - Test with HTML, CSS, and JavaScript

2. **Page-Specific Code**
   - Enable for posts/pages
   - Add code in meta boxes
   - Verify output only on specific pages

3. **Enable/Disable Settings**
   - Toggle post/page support
   - Verify meta boxes appear/disappear
   - Verify code output respects settings

4. **Migration Tools** (if applicable)
   - Test legacy data detection
   - Verify migration success
   - Check data integrity

5. **Data Tools** (if applicable)
   - Test usage reports
   - Test bulk deletion with various dataset sizes

### Browser Compatibility

Test in major browsers:
- Chrome
- Firefox
- Safari
- Edge

### WordPress Compatibility

Test with:
- Latest WordPress version
- Minimum supported version (5.2)
- Common themes (Twenty Twenty-Four, etc.)
- With/without other popular plugins

## Commit Messages

Write clear commit messages:

- Use present tense ("Add feature" not "Added feature")
- Use imperative mood ("Move cursor to..." not "Moves cursor to...")
- First line is 50 characters or less
- Reference issues and PRs when relevant

Example:
```
Add batch processing for data deletion

Implements chunked deletion to prevent PHP timeouts
when processing large datasets. Refs #123
```

## Documentation

- Update README.md if you change functionality
- Update CHANGELOG.md with your changes
- Add inline code comments for complex logic
- Update CLAUDE.md if you change architecture

## Code Review Process

1. All submissions require review
2. Maintainers may request changes
3. Changes must pass all tests
4. Documentation must be updated
5. Code must follow standards

## Release Process

Maintainers handle releases:

1. Update version in `code-injector-elite.php`
2. Update CHANGELOG.md
3. Create GitHub release with tag
4. Plugin Update Checker notifies users automatically

## Community

- Be respectful and inclusive
- Follow the [WordPress Code of Conduct](https://make.wordpress.org/handbook/community-code-of-conduct/)
- Help others when you can
- Share knowledge and best practices

## Questions?

Feel free to open an issue for questions about contributing!

## License

By contributing, you agree that your contributions will be licensed under the MIT License.
