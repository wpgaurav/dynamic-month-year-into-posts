import {
	calculateDays,
	getCalendarDate,
	parseCalendarDate,
} from './date-utils';

describe( 'countdown calendar-date helpers', () => {
	test( 'strictly parses valid ISO dates', () => {
		expect( parseCalendarDate( '2028-02-29' ) ).toEqual( {
			year: 2028,
			month: 2,
			day: 29,
		} );
		expect( parseCalendarDate( '2027-02-29' ) ).toBeNull();
		expect( parseCalendarDate( 'March 1, 2027' ) ).toBeNull();
	} );

	test( 'uses IANA and fixed-offset calendar dates', () => {
		const instant = new Date( '2027-01-01T02:00:00Z' );

		expect( getCalendarDate( 'America/Los_Angeles', instant ) ).toEqual( {
			year: 2026,
			month: 12,
			day: 31,
		} );
		expect( getCalendarDate( '+05:30', instant ) ).toEqual( {
			year: 2027,
			month: 1,
			day: 1,
		} );
	} );

	test( 'counts calendar days across daylight-saving changes', () => {
		const context = {
			targetDate: '2027-03-15',
			timezone: 'America/New_York',
			mode: 'until',
			recurring: false,
		};

		expect(
			calculateDays( context, new Date( '2027-03-13T17:00:00Z' ) )
		).toBe( 2 );
	} );

	test( 'rolls recurring events into the next year', () => {
		const context = {
			targetDate: '2020-01-01',
			timezone: 'UTC',
			mode: 'until',
			recurring: true,
		};

		expect(
			calculateDays( context, new Date( '2027-12-31T12:00:00Z' ) )
		).toBe( 1 );
	} );
} );
