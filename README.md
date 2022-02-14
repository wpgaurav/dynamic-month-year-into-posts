## Plugin Name
Contributors: gauravtiwari
Donate link: https://gauravtiwari.org/donate/
Tags: seo, year, automatic, add-on, hooks, dynamic-content, admin, shortcode, current date, month, yoast, gutenberg, widget, content
Requires at least: 3.0.1
Tested up to: 5.9
Stable tag: 1.2.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automate your SEO: add today's date by [date], current year by [year], previous year by [pyear], next year by [nyear] and months by [month] [nmonth] [pmonth] etc., shortcodes anywhere, including content, title, meta title, widget, excerpt and Breadcrumbs.

## Description

### Automate your SEO

Add today's date by `[date]`, current year by `[year]`, previous year by `[pyear]`, next year by `[nyear]`, current month (like January) by `[month]`, next month (like February) by `[nmonth]`, previous month (like December) by `[pmonth]`, current/next/previous month shortname (like Jan, Feb and Dec) by `[mon]`, `[nmon]` & `[pmon]` shortcodes anywhere, including content and title. Uses your default WordPress language. Use this plugin to boost your site's SEO, automate your affiliate marketing, automatically updating blogging lists, offer dynamic coupon expiries and more, just by using these variables anywhere.

[See Full List of Shortcodes](https://gauravtiwari.org/snippet/dynamic-month-year/#shortcodes)

### Works with popular SEO Plugins

* Supports RankMath, Yoast and SEOPress plugins and can be used to replace all their date based variables.
* `[year]` works as a replacement to `%currentyear%` , `[month]` as a replacement to `%currentmonth%` already.
* Use various combinations to make your articles even more dynamic and make your content always SEO ready — no matter the date or year.
* Extremely useful for Affiliate Marketers, Amazon Affiliates and Copywriters.

= Supports =
* Rank Math
* Rank Math Pro
* SEOPress
* SEOPress Premium
* Yoast SEO
* Yoast SEO Premium
* Gutenberg and Block Editor Content, Headings and Buttons
* Elementor Page Builder

Tested to be working with Yoast SEO, SEOPress and Rank Math's breadcrumbs, custom meta titles, excerpt etc.

Note: Since August 2021, Google has started showing h1 titles in search results, this plugin becomes even more useful as no other SEO plugins adds Current Month, Current Year or Today's Date in h1 titles except this; keeping the same in SEO meta as well.

### More features

* Full Rank Math OpenGraph Support.
* Schema and OpenGraph support in YoastSEO.
* Multiple Langauge (WPML) Support: Shortcode renders your site's defined language.
* Contextual Related Posts Support
* Jetpack Related Posts Support
* Totally native. No configuration required.

### Easy to use

Just install the plugin (see Installation tab) and activate it. Add [year] to render current year, [nyear] to render next year, [pyear] to render previous year, [month] to render current month (full name) and [mon] to render first three letters of months automatically. [See Full List of Shortcodes](https://gauravtiwari.org/snippet/dynamic-month-year/#shortcodes)

As the months & years change, these shortcodes get updated into the content and title automatically on the shortcode locations.

Zero bloat. No CSS/JS files loaded.

* [More details](https://gauravtiwari.org/snippet/dynamic-month-year/)
* [Free Support](https://wordpress.org/support/plugin/dynamic-month-year-into-posts/)
* [More WordPress Plugins and Web Tools](https://gauravtiwari.org/code/)
* [Request a Feature](https://gauravtiwari.org/contact/)
* [Follow Development on Github](https://github.com/wpgaurav/dynamic-month-year-into-posts)

### Privacy

Dynamic Month & Year into Posts is a completely native shortcode plugin. It has no settings page and does not use any analytics tool to gather or use your data. It's bloat and ad-free.

### Fast Support and Feature Implementation

I will provide instant support for all your queries or feature requests. Use [support forum](https://wordpress.org/support/plugin/dynamic-month-year-into-posts/) to ask your questions, request new features or report something broken.

I will try my best to ensure that this plugin is compatible with every functionality plugin you use.

## Frequently Asked Questions

### Why would I need this?
If you are an affiliate marketer or blogger who uses month, years and dates in your posts. If you use these dynamic month, year, date, next year, previous year shortcodes you won't have to update those posts again and again as the time passes.

### How can I access settings?
You can not. There is no options panel because there is no need to have one.

### Can I use it in Block Editor or Classic Editor?
You can use the shortcodes in both Block Editor and Classic Editor like running text. You can also the shortcodes in widget areas including footer (use-case: Autoupdating Copyright Year) and headers (use-case: Today's Date).

### Will this plugin work in my language?
Yes. The shortcode outputs are WPML ready. Since there is no settings page, you don't have to translate this plugin.

### How can I use these shortcodes in my theme/PHP code?
You can use `<?php echo do_shortcode('[year]');?>`, `<?php echo do_shortcode('[month]');?>` etc. in themes or in functionality plugins to use these shortcodes.

## Installation

1. Upload the plugin folder to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. There is no additional configuration required. There will not be any menus or settings for this plugin.


## Changelog

= 1.2.3 =
* Improved: `[monthyear]` shortcode renders next month if the current month is about to end, after 28th every month.

= 1.2.2 =
* New: Capitalize Month Names. Add `c` before any month based shortcode to render it capitalized. Useful in various languages where Month names are generally in small letters, like French, Swedish etc.
* Example shortcodes: `[cmonth]`, `[cmon]`, `[cnmonth]`, `[cpmonth]`, `[cnmon]`, `[cpmon]` etc.
* New Shortcodes: `[nnyear]` and `[ppyear]` show 2 years next and previous years, like 2023 and 2019 respectively.
* New Shortcode: `[monthyear]` shows current month and year together for specific purposes.
* Better compatibility with Rank Math Pro and Elementor.

= 1.2.1 =
* New Shortcodes: `[mm]` renders month number including trailing zero (01-12)
* New Shortcodes: `[mn]` renders month number without trailing zero (1-12)
* Full Jetpack Related Posts Support (Thanks Jetpack team!)

= 1.2.0 =
* WordPress 5.8 Support
* New Shortcode: [nmonth] renders next month (full name)
* New Shortcode: [pmonth] renders previous month (full name)
* New Shortcode: [nmon] renders next month (short name, like Jan, Feb)
* New Shortcode: [pmon] renders previous month (short name, like Dec, Nov)

= 1.1.9 =
* IMPROVED! Performance by removing `rank_math/paper/auto_generated_description/apply_shortcode` filter that causes more load on sites, specially where `wp_query` is used.
* This is first of many attempts to remove unnecessary elements.

= 1.1.8 =
* Revert back some of the changes in 1.1.6

= 1.1.7 =
* Bug fix

= 1.1.6 =
* NEW! `[dt]` shortcode to render only the day of the month (like 1, 2, 3, 17, 28). Combine this with other shortcodes to create date formats you desire.
* Added support for shortcodes in Rank Math's Product schema description
* IMPROVED! `[date]` shortcode renders date as set by Dashboard -> Settings -> General -> Date.

= 1.1.5 =
* NEW! `[mon]` shortcode to render only the first three-letters of Month, like Jan, Feb, Mar, Apr etc.
* Tested with WordPress 5.8 Beta

= 1.1.2 =
* Updated Readme.txt and Instructions

= 1.1.1 =
* Bug fixes.

= 1.1.0 =
* Bug fixes.
* New: Rank Math OpenGraph Support

= 1.0.9 =
* Improved: Schema and OpenGraph support in YoastSEO.
* Work in Progress: Full Schema and OpenGraph support in Rank Math
* Removed: Rank Math recommendation.

= 1.0.8 =
* New: Contextual Related Posts Support

= 1.0.7 =
* New: SEOPress Support

= 1.0.6 =
* New: Yoast SEO Support

= 1.0.5 =
* Multiple Langauge Support: Shortcode renders your site's defined language.
* New: [date] shortcode for today's date.
* WordPress 5.6 Compatibility

= 1.0.4 =
* Optimization

= 1.0.3 =
* Updated Readme.txt file

= 1.0.2 =
* [pyear] yields previous year (e.g., 2020)
* [nyear] yields next year (e.g., 2022)

= 1.0.1 =
* Fixed Name Conflicts

= 1.0.0 =
* First version
