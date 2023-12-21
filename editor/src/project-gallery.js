import { __, sprintf } from '@wordpress/i18n';
import {
	registerPlugin
} from "@wordpress/plugins";

import {
	PluginDocumentSettingPanel
} from "@wordpress/edit-post";

import {
	Button,
	TextControl,
	TextareaControl,
	BaseControl,
	PanelRow,
	ButtonGroup,
	DropZone,
	Spinner,
	ResponsiveWrapper,
	withNotices,
	withFilters,
	__experimentalHStack as HStack,
} from "@wordpress/components";

import {
	select,
	withSelect,
	withDispatch,
	useSelect,
	useDispatch
} from "@wordpress/data";

import {
	useRef,
	useState
} from "@wordpress/element"

import {
	MediaUpload,
	MediaUploadCheck,
	store as blockEditorStore,
} from "@wordpress/block-editor";


// Get core store options
import { store as coreStore } from '@wordpress/core-data';
// const { canUser, getEntityRecord, getEditedEntityRecord } = select( coreStore );

const ALLOWED_MEDIA_TYPES = [ 'image' ];
const DEFAULT_FEATURE_IMAGE_LABEL = __( 'Featured image' );
const DEFAULT_SET_FEATURE_IMAGE_LABEL = __( 'Set featured image' );
const instructions = (
	<p>
		{ __(
			'To edit the featured image, you need permission to upload media.'
		) }
	</p>
);

export default function App() {
	const postType = useSelect((select) => select('core/editor').getCurrentPostType());
	console.log('post type', postType)
	if (postType !== 'project-gallery') return;

	const {
		editPost
	} = useDispatch( 'core/editor' );


	const metaValues = useSelect(
		(select) => select('core/editor').getEditedPostAttribute('meta')
	);

	const {
		resource_url,
		resource_image,
		resource_description
	} = metaValues;

	function setMeta(newValue) {
		editPost({
			meta: {
				...metaValues,
				...newValue
			}
		});
	}

	return(<PluginDocumentSettingPanel name="project-details" title="Details">
		<BaseControl label={__('Resource Image')}>
			<MediaUploadCheck><MediaUpload
				onSelect={ ( media ) =>
					setMeta({
						resource_image: {
							id: media.id,
							url: media.url
						}
					})
				}
				allowedTypes={ ALLOWED_MEDIA_TYPES }
				value={ resource_image?.id }
				render={ ( { open } ) => (<>
					<img
						src={ resource_image?.url ? resource_image.url : 'https://placehold.co/600x300?text=Choose+An+Image' }
						onClick={ open }
					/>
					<ButtonGroup>
						<Button onClick={ open }>{resource_image?.url ? "Replace media" : "Open Media Library"}</Button>
						{resource_image?.url && <Button isDestructive onClick={ () => setMeta({
							'resource_image': undefined
						}) }>Remove</Button>}
					</ButtonGroup>
				</>) }
			/></MediaUploadCheck>
		</BaseControl>
		<TextareaControl
			label 		= {__("Resource Description")}
			onChange 	= {(e) => setMeta({resource_description: e})}
			value		= {resource_description}
		/>
		<TextControl
			onChange 	= {(e) => setMeta({resource_url: e})}
			value		= {resource_url}
			label 		= {__("Resource URL")}
		/>
	</PluginDocumentSettingPanel>);
}