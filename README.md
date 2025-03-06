# Dynamic Month & Year into Posts
Contributors: gauravtiwari
Donate link: https://gauravtiwari.org/donate/
Tags: content, marketing, seo, shortcode, writing
Requires at least: 3.0.1
Tested up to: 6.8
Stable tag: 1.4.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Automate SEO and content with dynamic shortcodes for dates, years, months in content, titles and more.

## Description

### Automate your SEO

Add today's date by `[date]`, current year by `[year]`, previous year by `[pyear]`, next year by `[nyear]`, current month (like January) by `[month]`, next month (like February) by `[nmonth]`, previous month (like December) by `[pmonth]`, current/next/previous month shortname (like Jan, Feb and Dec) by `[mon]`, `[nmon]` & `[pmon]` shortcodes anywhere, including content and title. Uses your default WordPress language. Use this plugin to boost your site's SEO, automate your affiliate marketing, automatically updating blogging lists, offer dynamic coupon expiries and more, just by using these variables anywhere.

But that's not it.

[See Full List of Shortcodes](https://gauravtiwari.org/snippet/dynamic-month-year/#shortcodes)

* New: Add post modified date by using `[datemodified]` and post publication date by using `[datepublished]` shortcodes.
* New: Ace Black Friday with new dynamic shortcodes. Black Friday and Cyber Monday dates can be inserted anywhere by using `[blackfriday]` and `[cybermonday]`.

### Works with popular SEO Plugins

* Supports RankMath, Yoast and SEOPress plugins and can be used to replace all their date based variables.
* `[year]` works as a replacement to `%currentyear%` , `[month]` as a replacement to `%currentmonth%` already.
* Use various combinations to make your articles even more dynamic and make your content always SEO ready — no matter the date or year.
* Extremely useful for Affiliate Marketers, Amazon Affiliates and Copywriters.

= Tested Support With =
* Rank Math (100%)
* Rank Math Pro (100%)
* SEOPress
* SEOPress Premium
* Yoast SEO
* Yoast SEO Premium
* Gutenberg and Block Editor Content, Headings and Buttons
* [All popular Block Editor Addons](https://gauravtiwari.org/best-gutenberg-blocks-plugins-for-wordpress/), including Greenshift, GenerateBlocks, Ultimate Blocks, Kadence Blocks, Spectra, Otter Blocks.
* Elementor Page Builder (100%)
* WP Bakery Page Builder
* Visual Composer
* Beaver Builder
* Brizy Builder
* Oxygen Builder
* Bricks Builder
* Jetpack
* Intelly Related Posts a.k.a. Inline Related Posts (IRP)
* Advanced Custom Fields (Manual: please see the FAQs)
* Lightweight Accordion (including Schema)
* All major themes like default WordPress themes, Astra, Neve, Hello, Kadence, GeneratePress and Blocksy.

Tested to be totally working with Yoast SEO, SEOPress and Rank Math's breadcrumbs, custom meta titles, excerpt etc.

Note: Since August 2021, Google has started showing h1 titles in search results, this plugin becomes even more useful as no other SEO plugins adds Current Month, Current Year or Today's Date in h1 titles except this; keeping the same in SEO meta as well.

### More features

* Full Rank Math OpenGraph Support.
* Full Schema and OpenGraph support in YoastSEO.
* Multiple Langauge (WPML) Support: Shortcode renders your site's defined language.
* Contextual Related Posts Support.
* Jetpack Related Posts Support.
* Totally native. Doesn't write anything to the database.
* No configuration required.
* Cache friendly.

### Easy to use

Just install the plugin (see Installation tab) and activate it. Add [year] to render current year, [nyear] to render next year, [pyear] to render previous year, [month] to render current month (full name) and [mon] to render first three letters of months automatically. [See Full List of Shortcodes](https://gauravtiwari.org/snippet/dynamic-month-year/#shortcodes) (You can also see this link in *Plugins* page in your WordPress dashboard).

As the months & years change, these shortcodes get updated into the content and title automatically on the shortcode locations.

Zero bloat. No CSS/JS files loaded. No database queries are made and the content is rendered on-the-fly. I am a performance geek and digital marketer myself, so I understand what you need. Please use a [WordPress cache plugin](https://gauravtiwari.org/best-wordpress-caching-plugin/) to cache the rendered content.

Also, Dynamic Month & Year into Posts is a totally free plugin. There are no upsells and will never be.

* [More details](https://gauravtiwari.org/snippet/dynamic-month-year/)
* [Free Support](https://wordpress.org/support/plugin/dynamic-month-year-into-posts/)
* [More WordPress Plugins and Web Tools](https://gauravtiwari.org/code/)
* [WordPress Deals](https://gauravtiwari.org/wordpress-deals/)
* [Request a Feature](https://gauravtiwari.org/contact/)

### Privacy

Dynamic Month & Year into Posts by [Gaurav Tiwari](https://gauravtiwari.org) is a completely native shortcode plugin. It has no settings page and does not use any analytics tool to gather or use your data. It's bloat and ad-free. No notifications. No upgrade notices. Nothing.

### Fast Support and Feature Implementation

I will provide instant support for all your queries or feature requests. Use [support forum](https://wordpress.org/support/plugin/dynamic-month-year-into-posts/) to ask your questions, request new features or report something broken. I will also update the plugin for every major and minor WordPress version.

### Compatibility

I use the plugin myself on [my primary website](https://gauravtiwari.org) and other client sites that I run. I try my best to ensure that this plugin is compatible with every functionality plugin you use. Please let me know if this plugin isn't compatible with your stack, create a support request and allow me some time.

Please note that some plugins strip shortcodes or disable rendering of shortcodes. In such cases, it's impossible to implement dynamic dates without rewriting the whole plugin code and breaking a couple of things. But still, I'll give a try.

Help me with a positive review to keep the development going.

## Frequently Asked Questions

= Why would I need this? =
If you are an affiliate marketer or blogger who uses months, years and dates in your posts. If you use this plugin to render dynamic month, year, date, next year, and previous year shortcodes, you won’t have to update those posts again and again as time passes.

= How can I access settings? =
You cannot. There is no options panel because there is no need to have one. Why bloat the WordPress dashboard with more options?

= Can I use it in Block Editor or Classic Editor? =
Like running text, you can use the shortcodes in both Block Editor and Classic Editor. You can also use the shortcodes in widget areas, including the footer (use-case: Autoupdating Copyright Year) and headers (use-case: Today’s Date).

= Will this plugin work in my language? =
Yes. The shortcode outputs are WPML ready and render as per the language set in your WordPress dashboard. Since there is no settings page, you don’t have to translate this plugin.

= How can I use these shortcodes in my theme/PHP code? =
You can use `<?php echo do_shortcode('[year]');?>`, `<?php echo do_shortcode('[month]');?>` etc. in themes or in functionality plugins to use these shortcodes.

= How can I render shortcodes in ACF fields? =
This plugin doesn’t render shortcodes in ACF fields by default (due to various reasons, security being the first). But if you really need to render [year] etc., shortcodes, you can enable selective rendering.
Just add this code in your theme’s functions.php file or in the Code Snippets plugin:

ACF field type => text
	```
	add_filter('acf/format_value/type=text', 'do_shortcode');
	```

ACF field name => headline
	```
	add_filter('acf/format_value/name=headline', 'do_shortcode');
	```

## Installation

1. Upload the plugin folder to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. There is no additional configuration required. There will not be any menus or settings for this plugin.

== Screenshots ==

1. Backend Editing
2. Front end preview (without Rank Math)
3. With RM Breadcrumb
4. Admin Post list demo
5. Multi Language Support


== Changelog ==
= 1.4.0 =
* More improvements. props @meteorlxy

= 1.3.9 =
* Refactor code.
* Attempted a fix for Rank Math SEO JSON and Excerpts.

= 1.3.8 =
* New: [nd] and [pd] display next and previous dates (number only).

= 1.3.7 =
* New: [nmonthyear] and [pmonthyear] display next and previous month and years together. Useful in many cases.

= 1.3.6 =
* Shortcode support in Archive Titles.

= 1.3.5 =
* Made Black Friday and Cyber Monday dates totally dynamic and auto-updating.
* Use [year n=number] to display any year next or previous to this year. For example [year n=5] renders 5th year from now and [year n=-5] renders 5th year back from today.

= 1.3.4 =
* Two new useful shortcodes. Post modified/updated date: `[datemodified]` and post publication date: `[datepublished]`. Use these anywhere you want to make your site more dynamic and SEO friendly.


= 1.3.3 =
* Revert some changes


= 1.3.2 =
* Full Yoast SEO Support. Finally.
* Optimized Code
* SEOPress Support complete

= 1.3.1 =
* Date change for Black Friday and Cyber Monday dates

= 1.3.0 =
* New: `[blackfriday]` and `[cybermonday]` shortcodes render this years Black Fridy and Cyber Monday dates like November 25 and November 28. Couple these with `[year]` to make the dates complete. [See this example](https://gauravtiwari.org/wp-content/uploads/2022/10/black-friday-date.jpg)
* Intelly Related Posts a.k.a. IRP support.

= 1.2.9 =
* Readme update.

= 1.2.8 =
* Fixed some issues related to rendering of next month.
* `[monthyear]` no longer prints next month after 28th. Reason: User experience, as the user may be tricked to thinking the wrong current month and year names.

= 1.2.7 =
* Fix rendering of [monthyear] shortcode in languages other than English.

= 1.2.6 =
* New: Full Elementor Support. Now the shortcodes should render in all core and Elementor Pro widgets

= 1.2.5 =
* Added: `[weekday]` shortcode renders day of the week like Sunday, Monday, …
* Added: `[wd]` shortcode renders shortnames for days of the week like Sun, Mon,…
* Compatibility with 6.0.1 tested.

= 1.2.4 =
* Added: JSON-LD support for Yoast SEO Schema. Props to [@sathoro](https://wordpress.org/support/topic/works-great-8169/)

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
* [pyear] yields previous year (e.g., 2021)
* [nyear] yields next year (e.g., 2023)

= 1.0.1 =
* Fixed Name Conflicts

= 1.0.0 =
* First version