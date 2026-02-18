# Dynamic Month & Year into Posts

[![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/dynamic-month-year-into-posts)](https://wordpress.org/plugins/dynamic-month-year-into-posts/)
[![WordPress Plugin: Tested WP Version](https://img.shields.io/wordpress/plugin/tested/dynamic-month-year-into-posts)](https://wordpress.org/plugins/dynamic-month-year-into-posts/)
[![License](https://img.shields.io/badge/license-GPL--3.0%2B-blue.svg)](http://www.gnu.org/licenses/gpl-3.0.html)

Automate SEO and content with dynamic shortcodes for dates, years, months, age calculations, and countdowns in content, titles, and meta descriptions.

**Contributors:** gauravtiwari
**Donate link:** https://gauravtiwari.org/donate/
**Tags:** dynamic content, shortcode, seo, dates, year
**Requires at least:** 6.0
**Tested up to:** 6.9
**Stable tag:** 1.7.1
**License:** GPL-3.0 or later
**License URI:** http://www.gnu.org/licenses/gpl-3.0.html

## Description

### Automate Your SEO

Insert dynamic dates, months, years, age calculations, and countdowns anywhere in your WordPress content. Perfect for affiliate marketers, bloggers, and content creators who want their content to stay fresh and up-to-date automatically.

Use shortcodes like `[year]`, `[month]`, `[date]`, `[age]`, `[daysuntil]` and more in your posts, pages, titles, and SEO meta to keep your content always current without manual updates.

## Complete Shortcode Reference

### Year Shortcodes

| Shortcode | Output | Description |
|-----------|--------|-------------|
| `[year]` | 2026 | Current year |
| `[year n=5]` | 2031 | Year with offset (+5 years) |
| `[year n=-3]` | 2023 | Year with offset (-3 years) |
| `[nyear]` | 2027 | Next year |
| `[nnyear]` | 2028 | Year after next |
| `[pyear]` | 2025 | Previous year |
| `[ppyear]` | 2024 | Year before previous |

### Month Shortcodes

| Shortcode | Output | Description |
|-----------|--------|-------------|
| `[month]` | January | Current month (full name) |
| `[cmonth]` | January | Current month (capitalized) |
| `[mon]` | Jan | Current month (short name) |
| `[cmon]` | Jan | Current month (short, capitalized) |
| `[mm]` | 01 | Month number with zero (01-12) |
| `[mn]` | 1 | Month number without zero (1-12) |
| `[nmonth]` | February | Next month (full name) |
| `[cnmonth]` | February | Next month (capitalized) |
| `[nmon]` | Feb | Next month (short name) |
| `[pmonth]` | December | Previous month (full name) |
| `[cpmonth]` | December | Previous month (capitalized) |
| `[pmon]` | Dec | Previous month (short name) |

### Combined Date Shortcodes

| Shortcode | Output | Description |
|-----------|--------|-------------|
| `[date]` | January 12, 2026 | Today's full date |
| `[monthyear]` | January 2026 | Current month and year |
| `[nmonthyear]` | February 2026 | Next month and year |
| `[pmonthyear]` | December 2025 | Previous month and year |

### Day Shortcodes

| Shortcode | Output | Description |
|-----------|--------|-------------|
| `[dt]` | 12 | Day of month (1-31) |
| `[nd]` | 13 | Tomorrow's day number |
| `[pd]` | 11 | Yesterday's day number |
| `[weekday]` | Sunday | Day of week (full name) |
| `[wd]` | Sun | Day of week (short name) |

### Post Date Shortcodes

| Shortcode | Description |
|-----------|-------------|
| `[datepublished]` | Post publication date |
| `[datemodified]` | Post last modified date |

### Special Event Shortcodes

| Shortcode | Output | Description |
|-----------|--------|-------------|
| `[blackfriday]` | November 27 | Black Friday date (auto-calculated) |
| `[cybermonday]` | November 30 | Cyber Monday date (auto-calculated) |

### Countdown Shortcodes

| Shortcode | Example | Description |
|-----------|---------|-------------|
| `[daysuntil date="2026-12-25"]` | 347 | Days until a specific date |
| `[dayssince date="2020-01-01"]` | 2203 | Days since a specific date |

### Age Shortcodes

Calculate and display age from any date. Perfect for "years of experience", personal profiles, or anniversary tracking.

| Shortcode | Example Output | Description |
|-----------|----------------|-------------|
| `[age date="1990-05-15"]` | 35 | Age in years only |
| `[age date="1990-05-15" ordinal="true"]` | 35th | Age with ordinal suffix (st/nd/rd/th) |
| `[age date="1990-05-15" rank="true"]` | 35th | Same as ordinal (alias) |
| `[age date="1990-05-15" format="ym"]` | 35 years, 7 months | Years and months |
| `[age date="1990-05-15" format="ymd"]` | 35 years, 7 months, 28 days | Full age breakdown |

**Use cases:**
- Display years of experience: `[age date="2010-06-01"]+ years of experience`
- Display ordinal anniversary: `Celebrating our [age date="2015-08-20" ordinal="true"] anniversary`
- Personal profiles: `Age: [age date="1990-05-15"]`
- Anniversary tracking: `Married for [age date="2015-08-20" format="ym"]`
- Ranking content: `My [age date="2020-01-01" rank="true"] year blogging`

### Season Shortcodes

Display the current season with hemisphere support.

| Shortcode | Example Output | Description |
|-----------|----------------|-------------|
| `[season]` | Winter | Current season (Northern hemisphere) |
| `[season region="north"]` | Winter | Northern hemisphere (explicit) |
| `[season region="south"]` | Summer | Southern hemisphere (reversed) |

**Season mapping:**
- **Northern hemisphere**: Spring (Mar-May), Summer (Jun-Aug), Fall (Sep-Nov), Winter (Dec-Feb)
- **Southern hemisphere**: Seasons are reversed (Summer when North has Winter, etc.)

**Use cases:**
- Seasonal content: `[season] Sale - Shop Now!`
- Regional targeting: `Enjoy [season region="south"] in Australia`
- Dynamic promotions: `Get ready for [season] with our latest collection`

## Block Editor Features

### Dynamic Date Block

A dedicated Gutenberg block for inserting dynamic dates with:
- Live preview in the editor
- All date types available via dropdown
- Custom format options
- Age calculation with format selection
- Typography and color controls

### Published Date Block

Display the post's publication date dynamically:
- Uses WordPress date format from Settings
- Custom date format support
- Typography and color controls

### Modified Date Block

Display the post's last modified date dynamically:
- Uses WordPress date format from Settings
- Custom date format support
- Typography and color controls

### Live Countdown Block

Interactive countdown block powered by the WordPress Interactivity API:
- Real-time countdown display (days, hours, minutes, seconds)
- Auto-updates at midnight
- Customizable target date
- Style options for colors and typography

### Toolbar Button

A calendar icon in the Block Editor formatting toolbar provides quick access to all shortcodes:
- Organized by category (Year, Month, Date, Events, Countdown, Age)
- Shows usage examples for shortcodes with arguments
- Inserted shortcodes are highlighted with orange background for visibility
- One-click insertion into any text block

### Sidebar Panel

Access the **Dynamic Dates** sidebar from the editor's settings panel for a complete shortcode reference with one-click copy buttons.

### Block Patterns

13 pre-built patterns available in the Block Inserter under "Dynamic Dates" category:

**Basic Patterns:**
- **Copyright Footer** - Auto-updating copyright year
- **Last Updated Notice** - Shows post modified date
- **Today's Date Header** - Display current date

**Marketing Patterns:**
- **Affiliate Post Header** - "Updated for [month] [year]" banner
- **Monthly Sale Banner** - Promotional banner with current month
- **Promotional Banner with CTA** - Eye-catching promo with button

**Event Patterns:**
- **Black Friday Banner** - Auto-calculated Black Friday/Cyber Monday dates
- **New Year Countdown** - Festive countdown to next year

**Countdown Patterns:**
- **Days Until Countdown** - Countdown to a specific date
- **Birthday Countdown** - Countdown with celebration styling
- **Days Since Milestone** - Track days since an important event

**Age Patterns:**
- **Age Display Card** - Shows age with prominent styling
- **Experience Badge** - Display years of experience as a badge

## Block Bindings API

For WordPress 6.5+, use Block Bindings to connect core blocks directly to dynamic date values:

```html
<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"dmyip/date","args":{"type":"year"}}}}} -->
<p></p>
<!-- /wp:paragraph -->
```

Available binding types: `year`, `month`, `date`, `monthyear`, `weekday`, and more.

## REST API

Access dynamic dates programmatically:

```
GET /wp-json/dmyip/v1/dates
GET /wp-json/dmyip/v1/shortcodes
GET /wp-json/dmyip/v1/render?shortcode=[year]
```

## WP-CLI Commands

```bash
# Render a shortcode
wp dmyip shortcode "[year]"
wp dmyip shortcode "[age date='1990-05-15' format='ym']"

# List all available shortcodes
wp dmyip list

# Test all shortcodes
wp dmyip test
```

## Compatibility

### SEO Plugins

- **Rank Math** - Full support including OpenGraph and Schema
- **Rank Math Pro** - 100% compatible
- **Yoast SEO** - Full support including JSON-LD Schema
- **Yoast SEO Premium** - 100% compatible
- **SEOPress / SEOPress Premium** - Full support

### Page Builders

- Gutenberg / Block Editor (native support)
- Elementor (100% support)
- WPBakery Page Builder
- Visual Composer
- Beaver Builder
- Brizy Builder
- Oxygen Builder
- Bricks Builder

### Themes

Works with all major themes including Twenty Twenty-Five, Twenty Twenty-Four, Twenty Twenty-Three, Twenty Twenty-Two, Twenty Twenty-One, Twenty Twenty, Astra, Neve, Hello (Elementor), Kadence, GeneratePress, Blocksy, and OceanWP.

### Additional Plugins

- Jetpack Related Posts
- Contextual Related Posts
- Intelly Related Posts (IRP)
- Advanced Custom Fields (see FAQ)
- Lightweight Accordion
- All popular Gutenberg block plugins (Greenshift, GenerateBlocks, Kadence Blocks, Spectra, Otter Blocks)

## Key Features

- **Zero Configuration** - Works out of the box, no settings needed
- **Multi-language Support** - WPML ready, renders in your site's language
- **Performance Focused** - Minimal CSS/JS, no database queries for shortcodes
- **Cache Friendly** - Content rendered on-the-fly
- **Privacy First** - No analytics, no data collection
- **Free Forever** - No upsells, no premium version
- **Modern Codebase** - PSR-4 autoloading, namespaced classes, PHPStan/PHPCS validated

## Installation

1. Upload the plugin folder to `/wp-content/plugins/` directory, or install through WordPress plugins screen
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Start using shortcodes in your content - no configuration required!

## Frequently Asked Questions

### Why would I need this?

If you're an affiliate marketer or blogger who uses dates in your posts (e.g., "Best Products of 2026", "January Deals"), this plugin automatically updates those dates as time passes. No more manually updating old posts!

### How do I calculate someone's age?

Use the `[age]` shortcode with a birth date:

```
[age date="1990-05-15"]              → 35
[age date="1990-05-15" format="ym"]  → 35 years, 7 months
[age date="1990-05-15" format="ymd"] → 35 years, 7 months, 28 days
```

### How can I use shortcodes in my theme/PHP code?

```php
<?php echo do_shortcode('[year]'); ?>
<?php echo do_shortcode('[age date="1990-05-15"]'); ?>
```

### How can I render shortcodes in ACF fields?

Add this to your theme's `functions.php` or Code Snippets plugin:

```php
// For text field type
add_filter('acf/format_value/type=text', 'do_shortcode');

// For a specific field name
add_filter('acf/format_value/name=headline', 'do_shortcode');
```

### Can I use it in Block Editor or Classic Editor?

Yes! Shortcodes work in both editors. In the Block Editor, you also get:
- A dedicated Dynamic Date block
- Toolbar button for quick insertion
- Visual highlighting of shortcodes
- Pre-built patterns

### Will this plugin work in my language?

Yes. All shortcode outputs use WordPress's `date_i18n()` function and render in your site's configured language.

### How can I disable shortcode processing in titles or excerpts?

Since 1.7.1, use the `dmyip_core_filters` filter to selectively disable shortcode processing:

```php
// Disable all core filters (shortcodes only work in post content and blocks)
add_filter( 'dmyip_core_filters', '__return_empty_array' );

// Disable only title processing
add_filter( 'dmyip_core_filters', function ( $filters ) {
    $filters['the_title'] = false;
    return $filters;
} );
```

Available keys: `the_title`, `single_post_title`, `wp_title`, `the_excerpt`, `get_the_excerpt`, `get_the_archive_title`. All default to `true`.

### Does it affect site performance?

Minimal impact. The plugin loads a small CSS file in the editor for shortcode highlighting. No database queries are made for shortcode rendering - content is generated on-the-fly using PHP's native date functions.

## Use Cases

- **Copyright notices**: Use `[year]` in footer widgets
- **Affiliate content**: "Best Products of [year]" or "Top Deals for [month] [year]"
- **Time-sensitive content**: "[month] Sale - Ends Soon!"
- **Black Friday content**: "Black Friday [year] starts [blackfriday]"
- **Evergreen content**: Keep "Updated for [year]" current automatically
- **Experience/tenure**: "[age date="2015-01-01"] years in business"
- **Age display**: "Age: [age date="1990-05-15" format="ym"]"
- **Event countdowns**: "Only [daysuntil date="2026-12-25"] days until Christmas!"
- **Milestone tracking**: "It's been [dayssince date="2020-03-15"] days since we launched"

## Privacy

This plugin:
- Does not collect any user data
- Does not use analytics or tracking
- Does not make external requests
- Does not store anything in the database
- Has no settings page or admin notices

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher

## Support

- [Documentation](https://gauravtiwari.org/snippet/dynamic-month-year/)
- [Support Forum](https://wordpress.org/support/plugin/dynamic-month-year-into-posts/)
- [Request a Feature](https://gauravtiwari.org/contact/)
- [GitHub Repository](https://github.com/wpgaurav/dynamic-month-year-into-posts)

## Changelog

### 1.7.1
- New: Published Date and Modified Date Gutenberg blocks
- New: `dmyip_core_filters` filter to selectively disable shortcode processing on WordPress core filters (titles, excerpts, archive titles). Props @meteorlxy.
- Fix: `[year]` shortcode with large negative offsets (e.g., `[year n=-2003]`) now returns correct results instead of wrong/empty values.
- Fix: `[year]` shortcode no longer zero-pads results (e.g., `[year n=-1100]` returns `926` instead of `0926`).

### 1.7.0
- New: `[season]` shortcode to display current season
  - `[season]` - Northern hemisphere (default)
  - `[season region="south"]` - Southern hemisphere
- New: Ordinal suffix support for `[age]` shortcode
  - `[age date="1990-05-15" ordinal="true"]` - Returns "35th"
  - `[age date="1990-05-15" rank="true"]` - Alias for ordinal
- Improvement: Proper handling of special ordinals (11th, 12th, 13th)

### 1.6.0
- New: `[age]` shortcode to calculate and display age from a birth date
  - `[age date="1990-05-15"]` - Years only
  - `[age date="1990-05-15" format="ym"]` - Years and months
  - `[age date="1990-05-15" format="ymd"]` - Full age breakdown
- New: Dedicated Dynamic Date Gutenberg block with live preview
- New: Live Countdown block with Interactivity API (auto-updates at midnight)
- New: Block Bindings API support (WP 6.5+) for binding core blocks to dynamic dates
- New: REST API endpoints (`/wp-json/dmyip/v1/dates`, `/dmyip/v1/shortcodes`)
- New: WP-CLI commands (`wp dmyip shortcode`, `wp dmyip list`, `wp dmyip test`)
- New: 13 Block Patterns including Age Display Card, Birthday Countdown, Experience Badge, New Year Countdown, Days Since Milestone, and Promotional Banner with CTA
- New: Shortcodes inserted via toolbar are highlighted with orange background in editor
- New: Toolbar dropdown shows usage examples for shortcodes with arguments
- New: PHPStan and PHPCS configuration for code quality
- New: Modernized codebase with namespaced PHP classes (PSR-4 autoloading)
- New: CI/CD pipeline with automated testing
- Improvement: Requires WordPress 6.0+ and PHP 7.4+

### 1.5.5
- Fix: Block Editor toolbar using RichTextToolbarButton for proper formatting toolbar integration

### 1.5.4
- Fix: Block Editor toolbar button using proper BlockControls API

### 1.5.3
- Fix: Block Editor crash when opening options menu

### 1.5.1
- Fix: Block Editor toolbar button display and shortcode insertion

### 1.5.0
- New: `[daysuntil]` and `[dayssince]` countdown shortcodes
- New: Block Editor toolbar button for inserting shortcodes
- New: Dynamic Dates sidebar panel with shortcode reference
- New: 7 pre-built Block Patterns (Copyright Footer, Sale Banners, etc.)
- New: GitHub Actions workflow for automated releases

### 1.4.0
- More improvements (props @meteorlxy)

### 1.3.9
- Refactored code structure
- Fix for Rank Math SEO JSON and Excerpts

### 1.3.8
- New: `[nd]` and `[pd]` for next and previous day numbers

### 1.3.7
- New: `[nmonthyear]` and `[pmonthyear]` shortcodes

### 1.3.6
- Shortcode support in Archive Titles

### 1.3.5
- Dynamic Black Friday and Cyber Monday dates
- Year offset support: `[year n=5]` for future years

### 1.3.4
- New: `[datemodified]` and `[datepublished]` shortcodes

### 1.3.2
- Full Yoast SEO support
- Optimized code
- Complete SEOPress support

### 1.3.0
- New: `[blackfriday]` and `[cybermonday]` shortcodes
- Intelly Related Posts support

[View full changelog](https://wordpress.org/plugins/dynamic-month-year-into-posts/#developers)

## License

This plugin is licensed under the GPL v3 or later.

```
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
```

See [LICENSE](LICENSE) for the full license text.
