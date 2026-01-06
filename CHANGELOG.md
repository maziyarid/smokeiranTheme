# Changelog

All notable changes to the SmokeIran Theme will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-01-06

### Added
- Initial release of SmokeIran Theme
- Fully responsive design with mobile-first approach
- Complete WooCommerce integration
- Modern, clean UI/UX design
- Professional typography using system font stack
- WordPress Customizer integration
  - Custom logo support
  - Primary color customization
  - Footer text customization
- Widget areas
  - Sidebar widget area
  - Three footer widget areas
- Template files
  - index.php - Main template
  - header.php - Header template
  - footer.php - Footer template
  - single.php - Single post template
  - page.php - Page template
  - archive.php - Archive template
  - search.php - Search results template
  - 404.php - Error page template
- Template parts
  - content.php - Post content
  - content-search.php - Search result content
  - content-none.php - No content found
- WooCommerce templates
  - content-product.php - Product loop template
- JavaScript functionality
  - Mobile menu toggle
  - Smooth scrolling
  - Form validation
  - Cart updates
  - Sticky header
  - Keyboard navigation
- CSS features
  - Responsive grid layouts
  - Flexbox navigation
  - Smooth transitions
  - Accessible focus states
  - Print styles
- Accessibility features
  - ARIA labels
  - Keyboard navigation
  - Skip to content link
  - Screen reader text support
- Security features
  - Proper escaping and sanitization
  - Nonce verification
  - Input validation
  - WordPress version removal
- Performance optimizations
  - Emoji scripts removal
  - Async/defer script loading
  - Optimized image loading
  - Minimal CSS and JS
- SEO features
  - Semantic HTML5 markup
  - Proper heading hierarchy
  - Breadcrumb navigation
  - Schema-ready structure
- RTL (Right-to-Left) language support
- Editor styles for WordPress block editor
- Translation ready
- Custom pagination
- Post thumbnails and featured images
- Threaded comments support
- Custom search form
- Breadcrumb navigation function

### Security
- Removed WordPress version from head
- Added proper input sanitization
- Added nonce verification for forms
- Implemented proper escaping for outputs

### Performance
- Removed emoji detection scripts
- Added defer attribute to custom scripts
- Optimized CSS with efficient selectors
- Used system fonts for faster loading

### Accessibility
- Added ARIA labels for navigation
- Implemented skip to content link
- Added keyboard navigation support
- Screen reader friendly markup

### Browser Support
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Opera (latest)
- Mobile browsers

### Requirements
- WordPress 6.0+
- PHP 7.4+
- WooCommerce 5.0+ (optional, for eCommerce features)

[1.0.0]: https://github.com/maziyarid/smokeiranTheme/releases/tag/v1.0.0
