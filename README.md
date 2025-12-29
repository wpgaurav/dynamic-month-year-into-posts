# Dynamic Month & Year into Posts

[![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/dynamic-month-year-into-posts)](https://wordpress.org/plugins/dynamic-month-year-into-posts/)
[![WordPress Plugin: Tested WP Version](https://img.shields.io/wordpress/plugin/tested/dynamic-month-year-into-posts)](https://wordpress.org/plugins/dynamic-month-year-into-posts/)
[![License](https://img.shields.io/badge/license-GPL--3.0%2B-blue.svg)](http://www.gnu.org/licenses/gpl-3.0.html)

Automate SEO and content with dynamic shortcodes for dates, years, months in content, titles, and meta descriptions.

**Contributors:** gauravtiwari
**Donate link:** https://gauravtiwari.org/donate/
**Tags:** content, marketing, seo, shortcode, writing, dates, dynamic content
**Requires at least:** 3.0.1
**Tested up to:** 6.8
**Stable tag:** 1.5.5
**License:** GPL-3.0 or later
**License URI:** http://www.gnu.org/licenses/gpl-3.0.html

## Description

### Automate Your SEO

Insert dynamic dates, months, and years anywhere in your WordPress content. Perfect for affiliate marketers, bloggers, and content creators who want their content to stay fresh and up-to-date automatically.

Use shortcodes like `[year]`, `[month]`, `[date]` and more in your posts, pages, titles, and SEO meta to keep your content always current without manual updates.

### Complete Shortcode Reference

#### Year Shortcodes

| Shortcode | Output | Description |
|-----------|--------|-------------|
| `[year]` | 2025 | Current year |
| `[year n=5]` | 2030 | Year with offset (+5 years) |
| `[year n=-3]` | 2022 | Year with offset (-3 years) |
| `[nyear]` | 2026 | Next year |
| `[nnyear]` | 2027 | Year after next |
| `[pyear]` | 2024 | Previous year |
| `[ppyear]` | 2023 | Year before previous |

#### Month Shortcodes

| Shortcode | Output | Description |
|-----------|--------|-------------|
| `[month]` | December | Current month (full name) |
| `[cmonth]` | December | Current month (capitalized) |
| `[mon]` | Dec | Current month (short name) |
| `[cmon]` | Dec | Current month (short, capitalized) |
| `[mm]` | 12 | Month number with zero (01-12) |
| `[mn]` | 12 | Month number without zero (1-12) |
| `[nmonth]` | January | Next month (full name) |
| `[cnmonth]` | January | Next month (capitalized) |
| `[nmon]` | Jan | Next month (short name) |
| `[cnmon]` | Jan | Next month (short, capitalized) |
| `[pmonth]` | November | Previous month (full name) |
| `[cpmonth]` | November | Previous month (capitalized) |
| `[pmon]` | Nov | Previous month (short name) |
| `[cpmon]` | Nov | Previous month (short, capitalized) |

#### Combined Date Shortcodes

| Shortcode | Output | Description |
|-----------|--------|-------------|
| `[date]` | December 29, 2025 | Today's full date |
| `[monthyear]` | December 2025 | Current month and year |
| `[nmonthyear]` | January 2026 | Next month and year |
| `[pmonthyear]` | November 2025 | Previous month and year |

#### Day Shortcodes

| Shortcode | Output | Description |
|-----------|--------|-------------|
| `[dt]` | 29 | Day of month (1-31) |
| `[nd]` | 30 | Tomorrow's day number |
| `[pd]` | 28 | Yesterday's day number |
| `[weekday]` | Sunday | Day of week (full name) |
| `[wd]` | Sun | Day of week (short name) |

#### Post Date Shortcodes

| Shortcode | Description |
|-----------|-------------|
| `[datepublished]` | Post publication date |
| `[datemodified]` | Post last modified date |

#### Special Event Shortcodes

| Shortcode | Output | Description |
|-----------|--------|-------------|
| `[blackfriday]` | November 28 | Black Friday date (auto-calculated) |
| `[cybermonday]` | December 1 | Cyber Monday date (auto-calculated) |

#### Countdown Shortcodes

| Shortcode | Description |
|-----------|-------------|
| `[daysuntil date="2025-12-31"]` | Days until a specific date |
| `[dayssince date="2020-01-01"]` | Days since a specific date |

### Block Editor Features

#### Toolbar Button
A calendar icon in the Block Editor toolbar provides quick access to all dynamic date shortcodes. Click to open a dropdown organized by category.

#### Sidebar Panel
Access the **Dynamic Dates** sidebar from the editor's settings panel for a complete shortcode reference with one-click copy buttons.

#### Block Patterns
Pre-built patterns available in the Block Inserter under "Dynamic Dates" category:
- **Copyright Footer** - Auto-updating copyright year
- **Last Updated Notice** - Shows post modified date
- **Affiliate Post Header** - "Updated for [month] [year]" banner
- **Monthly Sale Banner** - Promotional banner with current month
- **Black Friday Banner** - Auto-calculated Black Friday/Cyber Monday dates
- **Days Until Countdown** - Countdown to a specific date
- **Today's Date Header** - Display current date

### Works With Popular SEO Plugins

- **Rank Math** - Full support including OpenGraph and Schema
- **Rank Math Pro** - 100% compatible
- **Yoast SEO** - Full support including JSON-LD Schema
- **Yoast SEO Premium** - 100% compatible
- **SEOPress** - Full support
- **SEOPress Premium** - Full support

### Page Builder Compatibility

- Gutenberg / Block Editor
- Elementor (100% support)
- WPBakery Page Builder
- Visual Composer
- Beaver Builder
- Brizy Builder
- Oxygen Builder
- Bricks Builder

### Theme Compatibility

Works with all major themes including:
- Default WordPress themes
- Astra
- Neve
- Hello (Elementor)
- Kadence
- GeneratePress
- Blocksy

### Additional Plugin Support

- Jetpack Related Posts
- Contextual Related Posts
- Intelly Related Posts (IRP)
- Advanced Custom Fields (see FAQ)
- Lightweight Accordion
- All popular Gutenberg block plugins

## Key Features

- **Zero Configuration** - Works out of the box, no settings needed
- **Multi-language Support** - WPML ready, renders in your site's language
- **Performance Focused** - No CSS/JS files, no database queries
- **Cache Friendly** - Content rendered on-the-fly
- **Privacy First** - No analytics, no data collection
- **Free Forever** - No upsells, no premium version

## Installation

1. Upload the plugin folder to `/wp-content/plugins/` directory, or install through WordPress plugins screen
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Start using shortcodes in your content - no configuration required!

## Frequently Asked Questions

### Why would I need this?

If you're an affiliate marketer or blogger who uses dates in your posts (e.g., "Best Products of 2025", "January Deals"), this plugin automatically updates those dates as time passes. No more manually updating old posts!

### How can I use shortcodes in my theme/PHP code?

```php
<?php echo do_shortcode('[year]'); ?>
<?php echo do_shortcode('[month]'); ?>
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

Yes! Shortcodes work in both editors, as well as in widget areas (perfect for auto-updating copyright years in footers).

### Will this plugin work in my language?

Yes. All shortcode outputs use WordPress's `date_i18n()` function and render in your site's configured language.

### Does it affect site performance?

No. The plugin adds zero overhead - no CSS/JS files, no database queries. Content is rendered on-the-fly using PHP's native date functions.

## Use Cases

- **Copyright notices**: Use `[year]` in footer widgets
- **Affiliate content**: "Best Products of [year]" or "Top Deals for [month] [year]"
- **Time-sensitive content**: "[month] Sale - Ends Soon!"
- **Black Friday content**: "Black Friday [year] starts [blackfriday]"
- **Evergreen content**: Keep "Updated for [year]" current automatically

## Privacy

This plugin:
- Does not collect any user data
- Does not use analytics or tracking
- Does not make external requests
- Does not store anything in the database
- Has no settings page or admin notices

## Support

- [Documentation](https://gauravtiwari.org/snippet/dynamic-month-year/)
- [Support Forum](https://wordpress.org/support/plugin/dynamic-month-year-into-posts/)
- [Request a Feature](https://gauravtiwari.org/contact/)

## Changelog

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
