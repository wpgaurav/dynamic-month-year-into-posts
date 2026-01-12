/**
 * Dynamic Date block registration.
 */

import { registerBlockType } from '@wordpress/blocks';
import { calendar as icon } from '@wordpress/icons';

import Edit from './edit';
import metadata from './block.json';

/**
 * Register the block.
 */
registerBlockType( metadata.name, {
	...metadata,
	icon,
	edit: Edit,
	// No save - this is a dynamic block rendered by PHP
	save: () => null,
} );
