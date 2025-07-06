<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Blocksy
 */

$prefix = blocksy_manager()->screen->get_prefix();

$maybe_custom_output = apply_filters(
	'blocksy:posts-listing:canvas:custom-output',
	null
);

if ($maybe_custom_output) {
	echo $maybe_custom_output;
	return;
}

$blog_post_structure = blocksy_listing_page_structure([
	'prefix' => $prefix
]);

$container_class = 'ct-container';

if ($blog_post_structure === 'gutenberg') {
	$container_class = 'ct-container-narrow';
}


/**
 * Note to code reviewers: This line doesn't need to be escaped.
 * Function blocksy_output_hero_section() used here escapes the value properly.
 */
echo blocksy_output_hero_section([
	'type' => 'type-2'
]);

$section_class = '';

if (! have_posts()) {
	$section_class = 'class="ct-no-results"';
}

$placeholder = esc_attr_x('Search', 'placeholder', 'blocksy');

if (isset($args['search_placeholder'])) {
	$placeholder = $args['search_placeholder'];
}

if (isset($args['search_live_results'])) {
	$has_live_results = $args['search_live_results'];
} else {
	$has_live_results = get_theme_mod('search_enable_live_results', 'yes');
}

$search_live_results_output = '';

if ($has_live_results === 'yes') {
	if (! isset($args['live_results_attr'])) {
		$args['live_results_attr'] = 'thumbs';
	}

	$live_results_attr = !empty($args['live_results_attr']) ? [$args['live_results_attr']] : [];

	$search_live_results_output = 'data-live-results="' . implode(':', $live_results_attr) . '"';
}

$class_output = '';

$any = ['post'];

if (
	isset($args['enable_search_field_class'])
	&&
	$args['enable_search_field_class']
) {
	$class_output = 'class="modal-field"';
}

$home_url = home_url('/');

$icon = apply_filters(
	'blocksy:search-form:icon',
	'<svg class="ct-icon" aria-hidden="true" width="15" height="15" viewBox="0 0 15 15"><path d="M14.8,13.7L12,11c0.9-1.2,1.5-2.6,1.5-4.2c0-3.7-3-6.8-6.8-6.8S0,3,0,6.8s3,6.8,6.8,6.8c1.6,0,3.1-0.6,4.2-1.5l2.8,2.8c0.1,0.1,0.3,0.2,0.5,0.2s0.4-0.1,0.5-0.2C15.1,14.5,15.1,14,14.8,13.7z M1.5,6.8c0-2.9,2.4-5.2,5.2-5.2S12,3.9,12,6.8S9.6,12,6.8,12S1.5,9.6,1.5,6.8z"/></svg>'
);

if (isset($args['icon'])) {
	$icon = $args['icon'];
}

?>

<div class="news <?php echo $container_class ?>" <?php echo wp_kses_post(blocksy_sidebar_position_attr()); ?> <?php echo blocksy_get_v_spacing() ?>>

<?php if (have_posts()) { ?>
	<div class="supportgenix-custom-search-box">
		<form role="search" method="get" class="search-form" action="<?php echo esc_url($home_url); ?>" aria-haspopup="listbox" <?php echo wp_kses_post($search_live_results_output) ?>>

			<input type="search" <?php echo $class_output ?> placeholder="<?php echo $placeholder; ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" title="<?php echo __('Search for...', 'blocksy') ?>" aria-label="<?php echo __('Search for...', 'blocksy') ?>">

			<button type="submit" class="search-submit" aria-label="<?php echo __('Search button', 'blocksy')?>">
				<?php
					/**
					 * Note to code reviewers: This line doesn't need to be escaped.
					 * The value used here escapes the value properly.
					 * It contains an inline SVG, which is safe.
					 */
					echo $icon
				?>

				<span data-loader="circles"><span></span><span></span><span></span></span>
			</button>

			<?php if (count($any) === 1) { ?>
				<input type="hidden" name="post_type" value="<?php echo esc_attr($any[0]) ?>">
			<?php } ?>

			<?php if (count($any) > 1) { ?>
				<input type="hidden" name="ct_post_type" value="<?php echo esc_attr(implode(':', $any)) ?>">
			<?php } ?>



			<?php if ($has_live_results === 'yes') { ?>
				<div class="screen-reader-text" aria-live="polite" role="status">
					<?php echo __('No results', 'blocksy') ?>
				</div>
			<?php } ?>

		</form>
		
		<?php
		// Fetch categories and filter out empty and non-parent categories
		$categories = get_categories(array(
			'hide_empty' => true,
			'parent' => 0
		));

		if (!empty($categories)) { ?>
			<ul class="entry-meta sg-blog-archive-cat" data-type="simple:slash" data-id="dkffUY">
				<li class="meta-categories" data-type="pill">
					<?php foreach ($categories as $category) { ?>
						<a href="<?php echo get_category_link($category->term_id); ?>" rel="tag" class="ct-term-<?php echo $category->term_id; ?>">
							<?php echo esc_html($category->name); ?>
						</a>
					<?php } ?>
				</li>
			</ul>
		<?php } ?>
	</div>	
<?php } ?>



	<section <?php echo $section_class ?>>
		<?php
			/**
			 * Note to code reviewers: This line doesn't need to be escaped.
			 * Function blocksy_output_hero_section() used here
			 * escapes the value properly.
			 */
			echo blocksy_output_hero_section([
				'type' => 'type-1'
			]);

			echo blocksy_render_archive_cards();
		?>
	</section>

	<?php get_sidebar(); ?>
</div>

