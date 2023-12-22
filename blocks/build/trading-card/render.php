<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */


if ( $attributes['getDataFrom'] == 'otherPost' ) $scoped_id = $attributes['postId'];
else 											 $scoped_id = $block->context['postId'];

$resource_url 			= get_metadata('post', $scoped_id, 'resource_url', true) ?? null;
$resource_image 		= get_metadata('post', $scoped_id, 'resource_image', true) ?? null;
$resource_description 	= get_metadata('post', $scoped_id, 'resource_description', true) ?? null;

$permalink 				= get_permalink($scoped_id);

$category 				= get_the_terms($scoped_id, 'project-gallery-type')[0];
$category_slug 			= $category->slug;
$category_name 			= $category->name;

$buttons = [];
$buttons["shortcut"] 	= '<a href="' . ($resource_url ?? '') . '" class="wp-block-shortcut-badge"><img src="' . ANDREASJR_TOOLS_ROOT_URL . '/assets/svg/download-badge.svg" class="badge">'.wp_get_attachment_image( $resource_image['id'] ?? '', 'full', false, ['class' => 'badge-background'] ).'</a>';
$buttons["shortcut-engine"] = $buttons['shortcut'];

?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<div class="content-header">
		<span class="post-category"><?= $category_name ?></span>
		<figure class="content-header--featured-image-background">
			<?= get_the_post_thumbnail($scoped_id, 'full' ) ?>
		</figure>
		<?php if ($resource_image) : ?><figure class="content-header--resource-image"><a href="<?=$permalink?>">
			<?= wp_get_attachment_image( $resource_image['id'], 'full' ) ?>
		</a></figure><?php endif; ?>
	</div>

	<div class="content-title"><h2><?= get_the_title($scoped_id) ?></h2></div>
	<?= $resource_description ? '<div class="content-description">' . $resource_description . '</div>' : '' ?>

	<div class="content-actions wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">
		<div class="wp-block-button is-style-outline"><a href="<?=$permalink?>" class="wp-block-button__link wp-element-button">Learn More</a></div>
		<?php if ($resource_url) {
			echo !empty($buttons[$category_slug]) ?
			$buttons[$category_slug] : 
			'<div class="wp-block-button"><a href="'.$resource_url.'" target="_blank" class="wp-element-button">Get Resource</a></div>';
		 } ?>
	</div>
	
</div>
