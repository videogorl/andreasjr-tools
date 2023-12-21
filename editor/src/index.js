import {
	registerPlugin
} from "@wordpress/plugins";

import ProjectGallery from './project-gallery';

registerPlugin( 'andreasjr-tools-details', {
	render: ProjectGallery,
	icon: false
} );