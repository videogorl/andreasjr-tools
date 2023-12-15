<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$resource_url = get_metadata('post', $block->context['postId'], 'resource_url', true) ?? null;
$resource_image = get_metadata('post', $block->context['postId'], 'resource_image', true) ?? null;
$resource_description = get_metadata('post', $block->context['postId'], 'resource_description', true) ?? null;

$permalink = get_permalink($block->context['postId']);

?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<div class="content-header">
		<figure class="content-header--featured-image-background">
			<?= get_the_post_thumbnail($block->context['postId'], 'full' ) ?>
		</figure>
		<?php if ($resource_image) : ?><figure class="content-header--resource-image"><a href="<?=$permalink?>">
			<?= wp_get_attachment_image( $resource_image['id'], 'full' ) ?>
		</a></figure><?php endif; ?>
	</div>

	<div class="content-title"><h2><?= get_the_title($block->context['postId']) ?></h2></div>
	<?= $resource_description ? '<div class="content-description">' . $resource_description . '</div>' : '' ?>

	<div class="content-actions wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">
		<div class="wp-block-button is-style-outline"><a href="<?=$permalink?>" class="wp-block-button__link wp-element-button">Learn More</a></div>
		<?php if ($resource_url): ?><div class="wp-block-button"><a href="<?= $resource_url ?>" target="_blank" class="wp-element-button">Get Shortcut</a></div><?php endif; ?>
	</div>
	
</div>
