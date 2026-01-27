/**
 * WordPress Scripts webpack configuration.
 *
 * Extends the default config to include main entry point.
 */

const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
	...defaultConfig,
	entry: {
		// Main editor script (toolbar button, format type)
		index: path.resolve( process.cwd(), 'src', 'index.js' ),
		// Block entries
		'blocks/dynamic-date/index': path.resolve( process.cwd(), 'src', 'blocks', 'dynamic-date', 'index.js' ),
		'blocks/countdown/index': path.resolve( process.cwd(), 'src', 'blocks', 'countdown', 'index.js' ),
		'blocks/countdown/view': path.resolve( process.cwd(), 'src', 'blocks', 'countdown', 'view.js' ),
		'blocks/published-date/index': path.resolve( process.cwd(), 'src', 'blocks', 'published-date', 'index.js' ),
		'blocks/modified-date/index': path.resolve( process.cwd(), 'src', 'blocks', 'modified-date', 'index.js' ),
	},
};
