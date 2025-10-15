<?php
/**
 * Plugin Name:       videogorl-tools
 * Plugin URI:        https://videogorl.me
 * Description:       Tools by Felicity for Felicity
 * Requires at least: 6.3
 * Requires PHP:      7.4
 * Version:           1.0.0
 * Author:            Felicity Reif
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       videogorl-tools
 *
 * @package           videogorl-tools
 */

namespace videogorl\tools;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'ANDREASJR_TOOLS_ROOT_URL' ) ) {
	define( 'ANDREASJR_TOOLS_ROOT_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
add_action(
	'init',
	function () {
		foreach ( glob( plugin_dir_path( __FILE__ ) . 'blocks/build/*', GLOB_ONLYDIR ) as $folder ) {

			/**
			 * Find out if block has a helper
			 */
			$helper_url = $folder . '/helper.php';
			if ( file_exists( $helper_url ) ) {
				include $helper_url;
			}

			register_block_type( $folder );
		}
	}
);


// Enqueue Editor Assets.
add_action(
	'enqueue_block_editor_assets',
	function () {

		foreach ( glob( plugin_dir_path( __FILE__ ) . 'editor/build/*', GLOB_ONLYDIR ) as $folder ) {
			$dep_name = explode( '/', $folder );
			$dep_name = $dep_name[ count( $dep_name ) - 1 ];

			/**
			 * Find out if plugin has a helper
			 */
			$helper_url = $folder . '/helper.php';
			if ( file_exists( $helper_url ) ) {
				include $helper_url;
			}

			/**
			 * Register script
			 */
			$dependencies = require $folder . '/index.asset.php';
			wp_enqueue_script(
				'appalachia-plugins-' . $dep_name . '-script', // unique handle
				plugin_dir_url( __FILE__ ) . 'editor/build/' . $dep_name . '/index.js',
				$dependencies['dependencies'],
				$dependencies['version']
			);
		}
	}
);

/**
 * Add custom post types.
 */

add_action(
	'init',
	function () {
		/**
		 * Post Type: Portfolio.
		 */
		register_post_type(
			'portfolio-gallery',
			array(
				'label'               => esc_html__( 'Portfolio', 'inkling' ),
				'labels'              => array(
					'name'          => esc_html__( 'Portfolio', 'inkling' ),
					'singular_name' => esc_html__( 'Piece', 'inkling' ),
				),
				'description'         => '',
				'public'              => true,
				'publicly_queryable'  => true,
				'show_ui'             => true,
				'show_in_rest'        => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'delete_with_user'    => false,
				'exclude_from_search' => false,
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'hierarchical'        => true,
				'can_export'          => true,
				'rewrite'             => array(
					'slug'       => 'portfolio',
					'with_front' => false,
				),
				'query_var'           => true,
				'menu_icon'           => 'dashicons-excerpt-view',
				'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
				'taxonomies'          => array( 'category', 'post_tag' ),
				'show_in_graphql'     => true,
				'has_archive'         => 'portfolio',
			)
		);

		/**
		 * Post Type: Projects.
		 */
		register_post_type(
			'project-gallery',
			array(
				'label'               => esc_html__( 'Projects', 'inkling' ),
				'labels'              => array(
					'name'          => esc_html__( 'Projects', 'inkling' ),
					'singular_name' => esc_html__( 'Project', 'inkling' ),
				),
				'description'         => '',
				'public'              => true,
				'publicly_queryable'  => true,
				'show_ui'             => true,
				'show_in_rest'        => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'delete_with_user'    => false,
				'exclude_from_search' => false,
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'hierarchical'        => true,
				'can_export'          => true,
				'rewrite'             => array(
					'slug'       => 'projects',
					'with_front' => false,
				),
				'query_var'           => true,
				'menu_icon'           => 'dashicons-excerpt-view',
				'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
				'taxonomies'          => array( 'project-gallery-type' ),
				'show_in_graphql'     => false,
				'has_archive'         => 'projects-beta',
			)
		);

		register_taxonomy(
			'project-gallery-type',
			array( 'project-gallery' ),
			array(
				'label'              => esc_html__( 'Project Type', 'andreasjr-filament' ),
				'labels'             => array(
					'name'          => esc_html__( 'Project Types', 'andreasjr-filament' ),
					'singular_name' => esc_html__( 'Project Type', 'andreasjr-filament' ),
					'add_new_item'  => esc_html__( 'Add New Type', 'andreasjr-filament ' ),
					'parent_item'   => esc_html__( 'Parent Type', 'andreasjr-filament ' ),
				),
				'public'             => true,
				'publicly_queryable' => true,
				'hierarchical'       => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'query_var'          => true,
				'rewrite'            => array(
					'slug'       => 'type',
					'with_front' => false,
				),
				'show_admin_column'  => true,
				'show_in_rest'       => true,
				'show_tagcloud'      => false,
				'show_in_quick_edit' => true,
				'sort'               => false,
				'show_in_graphql'    => false,
			)
		);

		// Resource URL.
		register_meta(
			'post',
			'resource_url',
			array(
				'type'           => 'string',
				'single'         => true,
				'show_in_rest'   => true,
				'object_subtype' => 'project-gallery',
			)
		);
		// Resource Description.
		register_meta(
			'post',
			'resource_description',
			array(
				'type'           => 'string',
				'single'         => true,
				'show_in_rest'   => true,
				'object_subtype' => 'project-gallery',
			)
		);
		// Resource Image.
		register_meta(
			'post',
			'resource_image',
			array(
				'type'         => 'object',
				'single'       => true,
				'show_in_rest' => array(
					'schema' => array(
						'type'                 => 'object',
						'properties'           => array(
							'id'           => array( 'type' => 'number' ),
							'url'          => array( 'type' => 'string' ),
							'accent_color' => array( 'type' => 'string' ),
						),
						'additionalProperties' => true,
					),
				),
			)
		);

		/**
		 * "Series" taxonomy.
		 */
		register_taxonomy(
			'post-series',
			array( 'post' ),
			array(
				'label'              => esc_html__( 'Series', 'andreasjr-filament' ),
				'public'             => true,
				'publicly_queryable' => true,
				'hierarchical'       => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'query_var'          => true,
				'rewrite'            => array(
					'slug'       => 'series',
					'with_front' => false,
				),
				'show_admin_column'  => true,
				'show_in_rest'       => true,
				'show_tagcloud'      => false,
				'show_in_quick_edit' => true,
				'sort'               => false,
				'show_in_graphql'    => false,
			)
		);
	}
);

add_action(
	'enqueue_block_editor_assets',
	function () {
		/**
		 * Find out if plugin has a helper.
		 */
		$helper_url = plugin_dir_path( __FILE__ ) . 'editor/build/helper.php';
		if ( file_exists( $helper_url ) ) {
			include $helper_url;
		}

		/**
		 * Register script.
		 */
		$dependencies = require plugin_dir_path( __FILE__ ) . 'editor/build/index.asset.php';
		wp_enqueue_script(
			'andreasjr-tools-editor-script', // unique handle.
			plugin_dir_url( __FILE__ ) . 'editor/build/index.js',
			$dependencies['dependencies'],
			$dependencies['version']
		);
	}
);

add_theme_support( 'post-formats', array( 'aside', 'quote', 'status', 'image', 'gallery', 'chat', 'link', 'audio', 'video' ) );
