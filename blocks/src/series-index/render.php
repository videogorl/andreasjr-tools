<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$terms = wp_get_post_terms( $block->context['postId'], 'post-series' );

if ( empty($terms[0] )) return;
$term 				= $terms[0];
$term_name 			= $term -> name;
$term_description 	= $term-> description;
$term_id 			= $terms[0]-> term_id;

$query_args = array(
	"post_type" => "post",
	"tax_query" => [
		[
			"taxonomy" 	=> "post-series",
			"field" 	=> 'term_id',
			"terms" 		=> $term_id
		]
	]
);
$query = new WP_Query( $query_args );

if (!$query->have_posts()) return;

?>
<div <?= get_block_wrapper_attributes([
	'class' => 'series-' . $term_id
]) ?>>
	<!-- <span class="is-style-text-action">Series</span> -->
	<h2>Part of series: <a href="<?= get_term_link( $term_id ) ?>"><?= $term_name ?></a></h2>
	<?= $term_description ? '<p class="series-description">'. $term_description .'</p>' : '' ?>
	<ul>
		<?php while ($query->have_posts()) {
			$query->the_post();
			$is_current_post = get_the_ID() === $block->context['postId'];
			echo sprintf(
				'<li class="%3$s%4$s"><a href="%2$s">%1$s</a></li>',
				get_the_title(),
				get_the_permalink(),
				'item-' . get_the_ID(),
				$is_current_post ? ' current-item' : ''
			);
		} ?>
	</ul>
</div>
