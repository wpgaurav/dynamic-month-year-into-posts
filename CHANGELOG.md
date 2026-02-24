# Changelog

All notable changes to the **Dynamic Month & Year into Posts** plugin will be documented in this file.

## [1.7.2] - 2026-02-24

### Added
- Bricks Builder integration — shortcodes now render inside Bricks dynamic data elements.
- Published Date and Modified Date Gutenberg blocks.
- `dmyip_core_filters` filter to selectively disable shortcode processing on WordPress core filters (titles, excerpts, archive titles). Props @meteorlxy.

### Improved
- Release workflow auto-generates changelogs and attaches them to GitHub releases.

### Fixed
- `[year]` shortcode with large negative offsets (e.g., `[year n=-2003]`) now returns correct results instead of wrong/empty values.
- `[year]` shortcode no longer zero-pads results (e.g., `[year n=-1100]` returns `926` instead of `0926`).

## [1.7.0] - 2025-01-17

### Added
- `[season]` shortcode to display current season (Spring, Summer, Fall, Winter).
  - `[season]` — Northern hemisphere (default).
  - `[season region="south"]` — Southern hemisphere (reversed seasons).
- Ordinal suffix support for `[age]` shortcode.
  - `[age date="1990-05-15" ordinal="true"]` — Returns "35th".
  - `[age date="1990-05-15" rank="true"]` — Alias for ordinal.

### Improved
- Proper handling of special ordinals (11th, 12th, 13th).

## [1.6.2]

- More PHP 7.4 improvements.

## [1.6.1]

### Fixed
- Countdown timer was not compatible with < PHP 8.

## [1.6.0]

### Added
- `[age]` shortcode to calculate and display age from a birth date. Supports three formats:
  - `[age date="1990-05-15"]` — Years only (e.g., "34").
  - `[age date="1990-05-15" format="ym"]` — Years and months (e.g., "34 years, 7 months").
  - `[age date="1990-05-15" format="ymd"]` — Full age (e.g., "34 years, 7 months, 12 days").
- 6 additional Block Patterns — Age Display Card, Birthday Countdown, Experience Badge, New Year Countdown, Days Since Milestone, and Promotional Banner with CTA.
- Shortcodes inserted via toolbar are now highlighted with orange background in the editor for better visibility.
- Toolbar dropdown now shows usage examples for shortcodes with arguments.

### Improved
- Dynamic Date block now supports age calculation with configurable format options.

## [1.5.0]

### Added
- `[daysuntil]` and `[dayssince]` countdown shortcodes for calculating days until/since a specific date.
- Block Editor toolbar button for quick shortcode insertion.
- Dynamic Dates sidebar panel with complete shortcode reference and copy buttons.
- 7 pre-built Block Patterns (Copyright Footer, Sale Banners, Countdown, etc.).
- GitHub Actions workflow for automated plugin releases.

## [1.4.0]

- More improvements. Props @meteorlxy.

## [1.3.9]

- Refactor code.
- Attempted a fix for Rank Math SEO JSON and Excerpts.

## [1.3.8]

### Added
- `[nd]` and `[pd]` display next and previous dates (number only).

## [1.3.7]

### Added
- `[nmonthyear]` and `[pmonthyear]` display next and previous month and years together.

## [1.3.6]

### Added
- Shortcode support in Archive Titles.

## [1.3.5]

### Added
- Black Friday and Cyber Monday dates made totally dynamic and auto-updating.
- `[year n=number]` to display any year next or previous.

## [1.3.4]

### Added
- `[datemodified]` — Post modified/updated date.
- `[datepublished]` — Post publication date.

## [1.3.2]

### Added
- Full Yoast SEO Support.
- SEOPress Support.

### Improved
- Optimized code.

## [1.3.0]

### Added
- `[blackfriday]` and `[cybermonday]` shortcodes.
- Intelly Related Posts (IRP) support.

## [1.2.6]

### Added
- Full Elementor Support.

## [1.2.5]

### Added
- `[weekday]` shortcode renders day of the week.
- `[wd]` shortcode renders short names for days of the week.

## [1.2.2]

### Added
- Capitalize Month Names with `c` prefix shortcodes (`[cmonth]`, `[cmon]`, etc.).
- `[nnyear]` and `[ppyear]` show 2 years next and previous.
- `[monthyear]` shows current month and year together.

## [1.2.1]

### Added
- `[mm]` renders month number with trailing zero (01-12).
- `[mn]` renders month number without trailing zero (1-12).
- Full Jetpack Related Posts Support.

## [1.2.0]

### Added
- `[nmonth]` renders next month (full name).
- `[pmonth]` renders previous month (full name).
- `[nmon]` renders next month (short name).
- `[pmon]` renders previous month (short name).

## [1.1.6]

### Added
- `[dt]` shortcode to render the day of the month.
- Support for shortcodes in Rank Math's Product schema description.

### Improved
- `[date]` shortcode renders date as set in Dashboard → Settings → General → Date.

## [1.1.5]

### Added
- `[mon]` shortcode to render the first three letters of the month.

## [1.0.5]

### Added
- Multiple Language Support.
- `[date]` shortcode for today's date.

## [1.0.2]

### Added
- `[pyear]` yields previous year.
- `[nyear]` yields next year.

## [1.0.0]

- Initial release.
