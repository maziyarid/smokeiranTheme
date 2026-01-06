# SmokeIran Theme

A professional, responsive WordPress eCommerce theme built for WooCommerce with modern design, optimized performance, and excellent user experience.

## Features

### Core Features
- **Fully Responsive Design**: Mobile-first approach ensures perfect display on all devices
- **WooCommerce Ready**: Complete integration with WooCommerce for eCommerce functionality
- **Modern UI/UX**: Clean, professional design with intuitive navigation
- **Performance Optimized**: Fast loading times with optimized code
- **SEO Friendly**: Built with SEO best practices in mind
- **Accessibility Compliant**: WCAG 2.1 compliant for better accessibility

### Design Features
- Clean and modern layout
- Responsive grid system
- Smooth animations and transitions
- Professional typography using system font stack
- Customizable color schemes
- Custom logo support
- Widget-ready areas (sidebar and 3 footer widget areas)

### Technical Features
- WordPress 6.0+ compatible
- PHP 7.4+ compatible
- HTML5 and CSS3
- Cross-browser compatible
- Translation ready
- Custom WordPress Customizer options
- Breadcrumb navigation
- Custom pagination
- Post thumbnails support
- Featured images support
- Threaded comments support

### WooCommerce Features
- Product gallery with zoom and lightbox
- Product grid layouts (responsive)
- Cart and checkout pages
- Product single pages
- Shop archive pages
- Related products
- Custom product loops

### JavaScript Features
- Mobile menu toggle with smooth animations
- Smooth scrolling for anchor links
- Form validation enhancement
- Shopping cart AJAX updates
- Lazy loading support
- Sticky header on scroll
- Keyboard navigation support

## Installation

1. Download the theme files
2. In your WordPress admin panel, go to **Appearance > Themes**
3. Click **Add New** then **Upload Theme**
4. Choose the theme ZIP file and click **Install Now**
5. After installation, click **Activate**

## Configuration

### Menus
1. Go to **Appearance > Menus**
2. Create a new menu and assign it to "Primary Menu" or "Footer Menu"
3. Add menu items as needed

### Widgets
1. Go to **Appearance > Widgets**
2. Add widgets to:
   - Sidebar
   - Footer Widget 1
   - Footer Widget 2
   - Footer Widget 3

### Customizer
1. Go to **Appearance > Customize**
2. Configure:
   - Site Identity (logo, site title, tagline)
   - Theme Colors (primary color)
   - Footer Settings (copyright text)

### WooCommerce Setup
1. Install and activate WooCommerce plugin
2. Complete the WooCommerce setup wizard
3. The theme will automatically detect and integrate with WooCommerce

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Opera (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Development

### File Structure
```
smokeiranTheme/
├── js/
│   └── script.js           # Main JavaScript file
├── template-parts/
│   ├── content.php         # Post content template
│   ├── content-search.php  # Search results template
│   └── content-none.php    # No content template
├── woocommerce/
│   └── content-product.php # WooCommerce product template
├── 404.php                 # 404 error page
├── archive.php             # Archive pages
├── comments.php            # Comments template
├── footer.php              # Footer template
├── functions.php           # Theme functions
├── header.php              # Header template
├── index.php               # Main template
├── page.php                # Page template
├── readme.txt              # Theme readme
├── search.php              # Search results page
├── searchform.php          # Search form
├── sidebar.php             # Sidebar template
├── single.php              # Single post template
└── style.css               # Main stylesheet
```

## Theme Customization

### Colors
The theme uses a primary color that can be customized via the WordPress Customizer. The default primary color is `#007bff` (blue).

### Typography
The theme uses a system font stack for optimal performance:
```css
font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
```

### Custom CSS
Add custom CSS through:
1. **Appearance > Customize > Additional CSS**
2. Or create a child theme

## Child Theme Support

To create a child theme:

1. Create a new folder: `smokeiranTheme-child`
2. Create `style.css`:
```css
/*
Theme Name: SmokeIran Child Theme
Template: smokeiranTheme
*/
```
3. Create `functions.php`:
```php
<?php
function smokeiranTheme_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'smokeiranTheme_child_enqueue_styles');
```

## Credits

- Developed by: Maziyar Moradi
- Based on WordPress coding standards
- Icons: Unicode characters
- Fonts: System font stack

## License

This theme is licensed under the GNU General Public License v2 or later.
License URI: http://www.gnu.org/licenses/gpl-2.0.html

## Support

For support, please visit: https://github.com/maziyarid/smokeiranTheme

## Changelog

### Version 1.0.0
- Initial release
- Responsive design implementation
- WooCommerce integration
- Professional UI/UX
- Typography optimization
- Accessibility improvements
- Performance optimizations
- Mobile menu functionality
- Custom pagination
- Breadcrumbs support
- Widget areas
- Customizer options
- Security enhancements

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- WooCommerce 5.0 or higher (for eCommerce features)

## Tested Up To

- WordPress 6.4
- WooCommerce 8.0
- PHP 8.2
