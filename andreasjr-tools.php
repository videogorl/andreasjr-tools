<?php
/**
 * Plugin Name:       andreasjr-tools
 * Plugin URI:        https://andreasjr.com
 * Description:       Tools by Andreas for Andreas
 * Requires at least: 6.3
 * Requires PHP:      7.4
 * Version:           1.0.0
 * Author:            Andreas Reif
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       andreasjr-tools
 *
 * @package           andreasjr-tools
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
add_action( 'init', function() {
	foreach (glob( plugin_dir_path( __FILE__ ) . 'blocks/build/*', GLOB_ONLYDIR ) as $folder) {
	
		/**
		 * Find out if block has a helper
		 */
		$helper_url = $folder . '/helper.php';
		if (file_exists( $helper_url )) {
			include $helper_url;
		}
	
		register_block_type( $folder );
	}
} );


// Enqueue Editor Assets
add_action( 'enqueue_block_editor_assets', function() {

	foreach (glob( plugin_dir_path( __FILE__ ) . 'editor/build/*', GLOB_ONLYDIR ) as $folder) {
		$dep_name = explode('/', $folder);
		$dep_name = $dep_name[ count($dep_name) - 1 ];

		/**
		 * Find out if plugin has a helper
		 */
		$helper_url = $folder . '/helper.php';
		if (file_exists( $helper_url )) {
			include $helper_url;
		}
		
		/**
		 * Register script
		 */
		$dependencies = require($folder . '/index.asset.php');
		wp_enqueue_script(
			'appalachia-plugins-' . $dep_name . '-script', // unique handle
			plugin_dir_url( __FILE__ ) . 'editor/build/' . $dep_name . '/index.js',
			$dependencies['dependencies'],
			$dependencies['version']
		);
	}
} );

/**
 * Add custom post types
 */

add_action( 'init', function() {
	/**
	 * Post Type: Portfolio.
	 */
	register_post_type( "portfolio-gallery", [
		"label" => esc_html__( "Portfolio", "inkling" ),
		"labels" => [
			"name" => esc_html__( "Portfolio", "inkling" ),
			"singular_name" => esc_html__( "Piece", "inkling" ),
		],
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => true,
		"can_export" => true,
		"rewrite" => [
			"slug" => "portfolio-beta",
			"with_front" => false
		],
		"query_var" => true,
		"menu_icon" => "dashicons-excerpt-view",
		"supports" => [ "title", "editor", "thumbnail", "excerpt", "page-attributes" ],
		"taxonomies" => [ "category", "post_tag" ],
		"show_in_graphql" => true,
		"has_archive" => "portfolio-beta"
	] );

	/**
	 * Post Type: Projects.
	 */
	register_post_type( "project-gallery", [
		"label" => esc_html__( "Projects", "inkling" ),
		"labels" => [
			"name" => esc_html__( "Projects", "inkling" ),
			"singular_name" => esc_html__( "Project", "inkling" ),
		],
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => true,
		"can_export" => true,
		"rewrite" => [ "slug" => "projects", "with_front" => false ],
		"query_var" => true,
		"menu_icon" => "dashicons-excerpt-view",
		"supports" => [ "title", "editor", "thumbnail", "excerpt", "custom-fields" ],
		"taxonomies" => [ "project-gallery-type" ],
		"show_in_graphql" => false,
		"has_archive" => "projects-beta"
	] );

	register_taxonomy( "project-gallery-type", [ "project-gallery" ], [
		"label" 				=> esc_html__( "Project Type", "andreasjr-filament" ),
		"labels" 				=> [
			"name" 					=> esc_html__( "Project Types", "andreasjr-filament" ),
			"singular_name" 		=> esc_html__( "Project Type", "andreasjr-filament" ),
			"add_new_item"			=> esc_html__( "Add New Type", "andreasjr-filament "),
			"parent_item"			=> esc_html__( "Parent Type", "andreasjr-filament "),
		],
		"public" 				=> true,
		"publicly_queryable" 	=> true,
		"hierarchical" 			=> true,
		"show_ui" 				=> true,
		"show_in_menu" 			=> true,
		"show_in_nav_menus" 	=> true,
		"query_var" 			=> true,
		"rewrite" 				=> [ 'slug' => 'type', 'with_front' => false, ],
		"show_admin_column" 	=> true,
		"show_in_rest" 			=> true,
		"show_tagcloud" 		=> false,
		"show_in_quick_edit" 	=> true,
		"sort" 					=> false,
		"show_in_graphql" 		=> false,
	] );


	// Resource URL
	register_meta('post', 'resource_url', [
		'type' 		=> 'string',
		'single' 	=> true,
		'show_in_rest' => true,
		'object_subtype' => 'project-gallery'
	]);
	// Resource Description
	register_meta('post', 'resource_description', [
		'type' 		=> 'string',
		'single' 	=> true,
		'show_in_rest' => true,
		'object_subtype' => 'project-gallery'
	]);
	// Resource Image
	register_meta('post', 'resource_image', [
		'type' => 'object',
		'single' => true,
		'show_in_rest' => array(
			'schema' => array(
				'type'		=> 'object',
				'properties' => array(
					'id'  => array('type' => 'number'),
					'url' => array('type' => 'string'),
					'accent_color' => array('type' => 'string')
				),
				'additionalProperties' => true
			),
		),
	]);

	
} );

// Enqueue Editor Assets
add_action( 'enqueue_block_editor_assets', function() {

	$plugin_dir = plugin_dir_url( __FILE__ ) . 'editor/build/';

	foreach (glob( $plugin_dir . '*', GLOB_ONLYDIR ) as $folder) {
		$dep_name = basename($folder);

		/**
		 * Find out if plugin has a helper
		 */
		$helper_url = $folder . '/helper.php';
		if (file_exists( $helper_url )) include $helper_url;
		
		/**
		 * Register script
		 */
		$dependencies = require($folder . '/index.asset.php');
		wp_enqueue_script(
			'andreasjr-' 	. $dep_name . '-script',
			$plugin_dir 	. $dep_name . '/index.js',
			$dependencies['dependencies'],
			$dependencies['version']
		);
	}

} );

// add_filter( 'get_block_templates', function ( $query_result, $query, $template_type ) {
//     if( is_post_type_archive('project-gallery') ) {
//         $template = plugin_dir_path(__FILE__)  .'templates/archive-project.html';
//     }

// 	$theme = wp_get_theme();

//     $template_contents = file_get_contents( plugin_dir_path( __DIR__ ) . 'templates/archive-ale.html' );
//     $template_contents = str_replace( '~theme~', $theme->stylesheet, $template_contents );

//     $new_block                 = new WP_Block_Template();
//     $new_block->type           = 'wp_template';
//     $new_block->theme          = $theme->stylesheet;
//     $new_block->slug           = 'archive-ale';
//     $new_block->id             = $theme->stylesheet . '//archive-ale';
//     $new_block->title          = 'archive-ale';
//     $new_block->description    = '';
//     $new_block->source         = 'custom';
//     $new_block->status         = 'publish';
//     $new_block->has_theme_file = true;
//     $new_block->is_custom      = true;
//     $new_block->content        = $template_contents;

//     $query_result[] = $new_block;

//     return $query_result;
// } );

// add_theme_support( 'post-formats', array( 'aside', 'quote', 'status', 'image', 'gallery', 'chat', 'link', 'audio', 'video' ) );