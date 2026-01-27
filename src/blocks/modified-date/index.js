/**
 * Modified Date block registration.
 */

import { registerBlockType } from '@wordpress/blocks';
import { update as icon } from '@wordpress/icons';

import Edit from './edit';
import metadata from './block.json';

/**
 * Register the block.
 */
registerBlockType( metadata.name, {
	...metadata,
	icon,
	edit: Edit,
	save: () => null,
} );
