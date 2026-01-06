# Contributing to SmokeIran Theme

Thank you for considering contributing to the SmokeIran Theme! This document provides guidelines for contributing to the project.

## How to Contribute

### Reporting Bugs

If you find a bug in the theme:

1. Check if the bug has already been reported in the Issues section
2. If not, create a new issue with:
   - A clear, descriptive title
   - Detailed steps to reproduce the bug
   - Expected behavior vs actual behavior
   - Screenshots if applicable
   - Your WordPress version, PHP version, and theme version

### Suggesting Enhancements

We welcome suggestions for new features or improvements:

1. Check if the enhancement has already been suggested
2. Create a new issue with:
   - A clear, descriptive title
   - Detailed description of the proposed feature
   - Why this enhancement would be useful
   - Any relevant examples or mockups

### Pull Requests

1. Fork the repository
2. Create a new branch for your feature (`git checkout -b feature/AmazingFeature`)
3. Make your changes following the coding standards below
4. Test your changes thoroughly
5. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
6. Push to the branch (`git push origin feature/AmazingFeature`)
7. Open a Pull Request with:
   - A clear description of the changes
   - Reference to any related issues
   - Screenshots or examples if applicable

## Coding Standards

### PHP

- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- Use proper indentation (tabs, not spaces)
- Add comments for complex logic
- Escape and sanitize all data
- Use WordPress functions when available

Example:
```php
<?php
// Good
echo esc_html( $variable );

// Bad
echo $variable;
?>
```

### CSS

- Follow [WordPress CSS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/)
- Use meaningful class names
- Group related properties together
- Add comments for complex styles
- Use rem/em for responsive sizing

Example:
```css
/* Good */
.site-header {
    display: flex;
    align-items: center;
    padding: 1rem;
}

/* Avoid */
.header { display:flex;align-items:center;padding:16px; }
```

### JavaScript

- Follow [WordPress JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/)
- Use meaningful variable names
- Add comments for complex logic
- Use ES6 features when appropriate
- Avoid global variables

Example:
```javascript
// Good
function initMobileMenu() {
    const menuToggle = document.querySelector('.menu-toggle');
    // ... more code
}

// Avoid
function init() {
    var x = document.querySelector('.menu-toggle');
    // ... more code
}
```

### HTML

- Use semantic HTML5 elements
- Add proper ARIA labels for accessibility
- Keep markup clean and well-indented
- Validate markup when possible

## Testing

Before submitting your changes:

1. Test on multiple browsers (Chrome, Firefox, Safari, Edge)
2. Test on mobile devices or using browser dev tools
3. Test with WooCommerce if making eCommerce-related changes
4. Validate HTML/CSS if possible
5. Check for JavaScript console errors
6. Test with WordPress debug mode enabled

## Documentation

- Update README.md if adding new features
- Update CHANGELOG.md following the Keep a Changelog format
- Add inline code comments for complex logic
- Update readme.txt for WordPress.org compatibility

## Commit Messages

Write clear, concise commit messages:

- Use present tense ("Add feature" not "Added feature")
- Use imperative mood ("Move cursor to..." not "Moves cursor to...")
- Limit first line to 72 characters or less
- Reference issues and pull requests after the first line

Example:
```
Add mobile menu toggle functionality

- Implement hamburger menu icon
- Add smooth slide animation
- Ensure keyboard accessibility
- Close menu on outside click

Fixes #123
```

## Questions?

If you have questions about contributing, feel free to:
- Open an issue with the "question" label
- Contact the maintainer

## Code of Conduct

- Be respectful and inclusive
- Focus on constructive feedback
- Help others learn and grow
- Maintain a positive environment

## License

By contributing, you agree that your contributions will be licensed under the same GPLv2 or later license as the theme.

---

Thank you for contributing to making SmokeIran Theme better! 🎉
