/**
 * Calendar-date helpers for the live countdown block.
 */

const DAY_IN_MILLISECONDS = 24 * 60 * 60 * 1000;

/**
 * Parse an ISO calendar date without invoking browser UTC parsing.
 *
 * @param {string} value Date in YYYY-MM-DD format.
 * @return {{ year: number, month: number, day: number }|null} Date parts.
 */
export function parseCalendarDate( value ) {
	const match = /^(\d{4})-(\d{2})-(\d{2})$/.exec( value || '' );

	if ( ! match ) {
		return null;
	}

	const parts = {
		year: Number( match[ 1 ] ),
		month: Number( match[ 2 ] ),
		day: Number( match[ 3 ] ),
	};
	if ( ! isValidCalendarDate( parts ) ) {
		return null;
	}

	return parts;
}

/**
 * Check calendar parts without allowing Date.UTC() to normalize invalid dates.
 *
 * @param {{ year: number, month: number, day: number }} parts Date parts.
 * @return {boolean} Whether the parts identify a real calendar date.
 */
export function isValidCalendarDate( parts ) {
	const probe = new Date(
		Date.UTC( parts.year, parts.month - 1, parts.day )
	);

	return (
		probe.getUTCFullYear() === parts.year &&
		probe.getUTCMonth() + 1 === parts.month &&
		probe.getUTCDate() === parts.day
	);
}

/**
 * Get calendar parts in an IANA or fixed-offset timezone.
 *
 * @param {string} timezone WordPress timezone name.
 * @param {Date}   instant  Instant to convert.
 * @return {{ year: number, month: number, day: number }} Date parts.
 */
export function getCalendarDate( timezone, instant = new Date() ) {
	const offsetMatch = /^([+-])(\d{2}):(\d{2})$/.exec( timezone || '' );

	if ( offsetMatch ) {
		const direction = offsetMatch[ 1 ] === '-' ? -1 : 1;
		const offsetMinutes =
			direction *
			( Number( offsetMatch[ 2 ] ) * 60 + Number( offsetMatch[ 3 ] ) );
		const shifted = new Date( instant.getTime() + offsetMinutes * 60000 );

		return {
			year: shifted.getUTCFullYear(),
			month: shifted.getUTCMonth() + 1,
			day: shifted.getUTCDate(),
		};
	}

	try {
		const parts = new Intl.DateTimeFormat( 'en-US', {
			timeZone: timezone || 'UTC',
			year: 'numeric',
			month: 'numeric',
			day: 'numeric',
		} ).formatToParts( instant );
		const values = Object.fromEntries(
			parts.map( ( part ) => [ part.type, part.value ] )
		);

		return {
			year: Number( values.year ),
			month: Number( values.month ),
			day: Number( values.day ),
		};
	} catch {
		return {
			year: instant.getUTCFullYear(),
			month: instant.getUTCMonth() + 1,
			day: instant.getUTCDate(),
		};
	}
}

/**
 * Convert calendar parts to a stable day index.
 *
 * @param {{ year: number, month: number, day: number }} parts Date parts.
 * @return {number} UTC-backed calendar day index.
 */
export function calendarDayIndex( parts ) {
	return Math.floor(
		Date.UTC( parts.year, parts.month - 1, parts.day ) / DAY_IN_MILLISECONDS
	);
}

/**
 * Find the next valid annual occurrence, including leap-day recurrences.
 *
 * Keep this search window aligned with DateEngine::next_occurrence().
 *
 * @param {{ year: number, month: number, day: number }} target Target date parts.
 * @param {{ year: number, month: number, day: number }} today  Current site date.
 * @return {{ year: number, month: number, day: number }|null} Next occurrence.
 */
export function resolveRecurringTarget( target, today ) {
	const todayIndex = calendarDayIndex( today );

	for ( let yearOffset = 0; yearOffset <= 8; yearOffset++ ) {
		const candidate = { ...target, year: today.year + yearOffset };

		if (
			isValidCalendarDate( candidate ) &&
			calendarDayIndex( candidate ) >= todayIndex
		) {
			return candidate;
		}
	}

	return null;
}

/**
 * Calculate countdown days.
 *
 * @param {Object} context Interactivity context.
 * @param {Date}   instant Instant used as "now".
 * @return {number|null} Non-negative day count, or null for an invalid target.
 */
export function calculateDays( context, instant = new Date() ) {
	const target = parseCalendarDate( context.targetDate );

	if ( ! target ) {
		return null;
	}

	const today = getCalendarDate( context.timezone, instant );
	let effectiveTarget = target;

	if ( context.recurring ) {
		effectiveTarget = resolveRecurringTarget( target, today );

		if ( ! effectiveTarget ) {
			return null;
		}
	}

	const difference =
		context.mode === 'since' && ! context.recurring
			? calendarDayIndex( today ) - calendarDayIndex( effectiveTarget )
			: calendarDayIndex( effectiveTarget ) - calendarDayIndex( today );

	return Math.max( 0, difference );
}
