/**
 * Live Countdown - Interactivity API view script.
 *
 * This script handles live updates of the countdown without page refresh.
 */

import { store, getContext } from '@wordpress/interactivity';

const { state, actions } = store( 'dmyip/countdown', {
	state: {
		get displayText() {
			const context = getContext();
			const days = context.days;
			const label = context.label;
			const showLabel = context.showLabel;

			return showLabel ? `${ days } ${ label }` : String( days );
		},
	},

	actions: {
		/**
		 * Calculate and update the days count.
		 */
		updateCountdown() {
			const context = getContext();
			const targetDate = context.targetDate;
			const mode = context.mode;

			if ( ! targetDate ) {
				context.days = 0;
				return;
			}

			const target = new Date( targetDate );
			const today = new Date();
			today.setHours( 0, 0, 0, 0 );
			target.setHours( 0, 0, 0, 0 );

			const diffMs = mode === 'until'
				? target - today
				: today - target;

			const diffDays = Math.floor( diffMs / ( 1000 * 60 * 60 * 24 ) );
			context.days = Math.max( 0, diffDays );
		},
	},

	callbacks: {
		/**
		 * Initialize countdown and set up daily updates.
		 */
		init() {
			// Initial calculation
			actions.updateCountdown();

			// Calculate ms until midnight for next update
			const now = new Date();
			const tomorrow = new Date( now );
			tomorrow.setDate( tomorrow.getDate() + 1 );
			tomorrow.setHours( 0, 0, 0, 0 );
			const msUntilMidnight = tomorrow - now;

			// Update at midnight, then every 24 hours
			setTimeout( () => {
				actions.updateCountdown();

				// Set up daily interval
				setInterval( () => {
					actions.updateCountdown();
				}, 24 * 60 * 60 * 1000 );
			}, msUntilMidnight );
		},
	},
} );
