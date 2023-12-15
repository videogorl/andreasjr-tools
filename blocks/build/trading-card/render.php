<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$resource_url = get_metadata('post', $block->context['postId'], 'resource_url', true) ?? null;
$resource_image = get_metadata('post', $block->context['postId'], 'resource_image', true);
$resource_description = get_metadata('post', $block->context['postId'], 'resource_description', true);

$permalink = get_permalink($block->context['postId']);

?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<div class="content-header">
		<figure class="content-header--featured-image-background">
			<?= get_the_post_thumbnail($block->context['postId'], 'full' ) ?>
		</figure>
		<figure class="content-header--resource-image"><a href="<?=$permalink?>">
			<?= wp_get_attachment_image( $resource_image['id'], 'full' ) ?>
		</a></figure>
	</div>

	<div class="content-title"><h2><?= get_the_title($block->context['postId']) ?></h2></div>
	<?= $resource_description ? '<div class="content-description">' . $resource_description . '</div>' : '' ?>

	<div class="content-actions">
		<a href="<?=$permalink?>" class="wp-element-button">Learn More</a>
		<a href="<?= $resource_url ?>" class="wp-element-button">Get Shortcut</a>
	</div>
	
</div>
