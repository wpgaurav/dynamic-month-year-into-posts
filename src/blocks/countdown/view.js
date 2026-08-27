/**
 * Live Countdown Interactivity API view module.
 */

import { store, getContext } from '@wordpress/interactivity';

import { calculateDays } from './date-utils';

/**
 * Schedule the next cache-safe refresh on an aligned minute boundary.
 *
 * @param {Object}   context Interactivity context.
 * @param {Function} update  Update callback.
 */
function scheduleRefresh( context, update ) {
	if ( context.timerId ) {
		window.clearTimeout( context.timerId );
	}

	const delay = 60050 - ( Date.now() % 60000 );
	context.timerId = window.setTimeout( () => {
		update();
		scheduleRefresh( context, update );
	}, delay );
}

store( 'dmyip/countdown', {
	callbacks: {
		init() {
			const context = getContext();
			const update = () => {
				const days = calculateDays( context );

				if ( days === null ) {
					context.displayText = '';
					return;
				}

				context.days = days;

				let label = context.label;

				if ( context.autoLabel ) {
					label =
						days === 1
							? context.singularLabel
							: context.pluralLabel;
				}

				context.displayText =
					context.showLabel && label
						? `${ context.days } ${ label }`
						: String( context.days );
			};

			update();
			scheduleRefresh( context, update );
		},
	},
} );
