/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';

import {
	PanelBody,
	SelectControl,
	FormTokenField
} from "@wordpress/components";

import {
	useSelect
} from "@wordpress/data";

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';


import settings from './settings.json';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit({
	attributes: {
		getDataFrom,
		postId: selectedPostId,
		customData
	},
	setAttributes,
	context: {
		postId,
		postType
	}
}) {
	const postOptions = useSelect( select => {
		if (getDataFrom !== 'otherPost') return [];
		const postList = select('core').getEntityRecords(
			'postType',
			'project-gallery'
		)
		
		if (!postList) return [];

		return postList.map( post => ({ value: post.id, label: post.title.rendered }) );
	}, [getDataFrom]);
	return (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody title={__('Post')}>
				<SelectControl
					onChange	={(e) => setAttributes({getDataFrom: e})}
					value		={getDataFrom}
					options={[
						{
							disabled: true,
							label: 'Select an Option',
							value: ''
						},
						{
							label: 'Current Post',
							value: 'currentPost'
						},
						{
							label: 'Other Post',
							value: 'otherPost'
						},
						{
							label: 'Custom Data',
							value: 'custom'
						}
					]}
				/>
				{ getDataFrom == 'otherPost' && <SelectControl
					label		={ __("Select a project") }
					onChange	={ (e) => setAttributes({postId: e}) }
					value		={ selectedPostId }
					options		={[
						{
							label: postOptions.length <= 0 ? "Loading..." : "Select...",
							value: null,
							disabled: true
						},
						...postOptions
					]}
				/>}
				</PanelBody>

				{ getDataFrom == 'custom' && <PanelBody title={__('Custom Data')}>
					
				</PanelBody>}
			</InspectorControls>
			
			{ __( 'Trading Card – hello from the editor!', 'andreasjr-tools' ) }
		</div>
	);
}
