<?php
/**
 * Template: [wcs_post_list cat="" count="" offset="" show_meta="" show_cat=""]
 *
 * @package webchiase
 * @var array $args
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cat       = isset( $args['cat'] ) ? (string) $args['cat'] : '';
$count     = isset( $args['count'] ) ? max( 1, absint( $args['count'] ) ) : 4;
$offset    = isset( $args['offset'] ) ? max( 0, absint( $args['offset'] ) ) : 0;
$show_meta = ! isset( $args['show_meta'] ) || ( '0' !== (string) $args['show_meta'] && 'false' !== (string) $args['show_meta'] );
$show_cat  = ! empty( $args['show_cat'] ) && '0' !== (string) $args['show_cat'] && 'false' !== (string) $args['show_cat'];

$query = wcs_query_posts( $cat, $count, $offset );
?>
<div class="wcs-post-list">
	<?php if ( $query->have_posts() ) : ?>
		<?php
		while ( $query->have_posts() ) :
			$query->the_post();
			$post_id     = get_the_ID();
			$primary_cat = wcs_get_primary_category( $post_id );
			?>
			<article class="wcs-post-list__item">
				<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="wcs-post-list__thumb" aria-label="<?php echo esc_attr( get_the_title( $post_id ) ); ?>">
					<?php echo wp_kses_post( wcs_get_post_thumbnail( $post_id, 'wcs-thumb-sm', 'wcs-post-list__img' ) ); ?>
				</a>
				<div class="wcs-post-list__body">
					<?php if ( $show_cat && $primary_cat ) : ?>
						<a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="wcs-post-list__cat">
							<?php echo esc_html( $primary_cat->name ); ?>
						</a>
					<?php endif; ?>

					<h3 class="wcs-post-list__title">
						<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
							<?php echo esc_html( get_the_title( $post_id ) ); ?>
						</a>
					</h3>

					<?php if ( $show_meta ) : ?>
						<div class="wcs-post-meta">
							<span class="wcs-post-meta__item wcs-post-meta__date">
								<time datetime="<?php echo esc_attr( get_the_date( 'c', $post_id ) ); ?>">
									<?php echo esc_html( get_the_date( 'd/m/Y', $post_id ) ); ?>
								</time>
							</span>
							<span class="wcs-post-meta__item wcs-post-meta__comments">
								<span class="wcs-post-meta__icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'comment' ) ); ?></span>
								<span><?php echo esc_html( number_format_i18n( get_comments_number( $post_id ) ) ); ?></span>
							</span>
						</div>
					<?php endif; ?>
				</div>
			</article>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	<?php else : ?>
		<p class="wcs-empty"><?php echo esc_html__( 'Chưa có bài viết nào.', 'webchiase' ); ?></p>
	<?php endif; ?>
</div>
