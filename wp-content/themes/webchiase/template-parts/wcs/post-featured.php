<?php
/**
 * Template: [wcs_post_featured cat="" count="1"]
 *
 * @package webchiase
 * @var array $args
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cat        = isset( $args['cat'] ) ? (string) $args['cat'] : '';
$count      = isset( $args['count'] ) ? max( 1, absint( $args['count'] ) ) : 1;
$offset     = isset( $args['offset'] ) ? max( 0, absint( $args['offset'] ) ) : 0;
$show_views = ! isset( $args['show_views'] ) || ( '0' !== (string) $args['show_views'] && 'false' !== (string) $args['show_views'] );

$query = wcs_query_posts( $cat, $count, $offset );
?>
<div class="wcs-post-featured-wrap">
	<?php if ( $query->have_posts() ) : ?>
		<?php
		while ( $query->have_posts() ) :
			$query->the_post();
			$post_id     = get_the_ID();
			$primary_cat = wcs_get_primary_category( $post_id );
			$views_count = wcs_get_post_views( $post_id );
			?>
			<article class="wcs-post-featured">
				<div class="wcs-post-featured__media">
					<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="wcs-post-featured__thumb" aria-label="<?php echo esc_attr( get_the_title( $post_id ) ); ?>">
						<?php echo wp_kses_post( wcs_get_post_thumbnail( $post_id, 'wcs-thumb-lg', 'wcs-post-featured__img' ) ); ?>
					</a>
				</div>

				<div class="wcs-post-featured__body">
					<?php if ( $primary_cat ) : ?>
						<div class="wcs-post-featured__badge-wrap">
							<a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="wcs-badge">
								<?php echo esc_html( $primary_cat->name ); ?>
							</a>
						</div>
					<?php endif; ?>

					<h3 class="wcs-post-featured__title">
						<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
							<?php echo esc_html( get_the_title( $post_id ) ); ?>
						</a>
					</h3>

					<p class="wcs-post-featured__excerpt">
						<?php echo esc_html( wp_trim_words( get_the_excerpt( $post_id ), 26, '...' ) ); ?>
					</p>

					<div class="wcs-post-meta">
						<span class="wcs-post-meta__item wcs-post-meta__date">
							<span class="wcs-post-meta__icon show-for-small"><?php echo wcs_kses_icon( wcs_get_icon_html( 'calendar' ) ); ?></span>
							<time datetime="<?php echo esc_attr( get_the_date( 'c', $post_id ) ); ?>">
								<?php echo esc_html( get_the_date( 'd/m/Y', $post_id ) ); ?>
							</time>
						</span>
						<?php if ( $show_views ) : ?>
							<span class="wcs-post-meta__item wcs-post-meta__views show-for-small">
								<span class="wcs-post-meta__icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'eye' ) ); ?></span>
								<span><?php echo esc_html( wcs_format_count( $views_count ) ); ?></span>
							</span>
						<?php endif; ?>
						<span class="wcs-post-meta__item wcs-post-meta__comments">
							<span class="wcs-post-meta__icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'comment' ) ); ?></span>
							<span><?php echo esc_html( number_format_i18n( get_comments_number( $post_id ) ) ); ?></span>
						</span>
					</div>
				</div>
			</article>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	<?php else : ?>
		<p class="wcs-empty"><?php echo esc_html__( 'Chưa có bài viết nổi bật.', 'webchiase' ); ?></p>
	<?php endif; ?>
</div>
