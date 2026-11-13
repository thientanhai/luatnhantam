<?php
/**
 * Blog & Category Archive template theo phong cách trang chủ LuatNhanTam.vn.
 *
 * @package          webchiase
 * @flatsome-version 3.16.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( have_posts() ) :
	$posts_array = array();
	while ( have_posts() ) {
		the_post();
		$posts_array[] = get_post();
	}
	rewind_posts();

	$total_in_page = count( $posts_array );
	$show_top_hero = ( ! is_paged() && ! is_search() && $total_in_page >= 2 );
	$feed_posts    = $show_top_hero ? array_slice( $posts_array, min( 4, $total_in_page ) ) : $posts_array;
	?>

	<?php if ( $show_top_hero ) : ?>
		<?php
		$hero_main = $posts_array[0];
		$hero_list = array_slice( $posts_array, 1, 3 );
		?>
		<div class="wcs-card wcs-mb">
			<?php
			echo do_shortcode(
				'[wcs_section_title icon="fire" title="' . esc_attr__( 'TIÊU ĐIỂM CHUYÊN MỤC', 'webchiase' ) . '"]'
			);
			?>
			<div class="row row-small">
				<div class="col large-6 medium-6 small-12 pb-0">
					<?php
					$post_id     = $hero_main->ID;
					$primary_cat = wcs_get_primary_category( $post_id );
					$views_count = wcs_get_post_views( $post_id );
					?>
					<article class="wcs-post-featured">
						<div class="wcs-post-featured__media">
							<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="wcs-post-featured__thumb">
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
							<h2 class="wcs-post-featured__title">
								<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
									<?php echo esc_html( get_the_title( $post_id ) ); ?>
								</a>
							</h2>
							<p class="wcs-post-featured__excerpt">
								<?php echo esc_html( wp_trim_words( get_the_excerpt( $post_id ), 26, '...' ) ); ?>
							</p>
							<div class="wcs-post-meta">
								<span class="wcs-post-meta__item wcs-post-meta__date">
									<time datetime="<?php echo esc_attr( get_the_date( 'c', $post_id ) ); ?>">
										<?php echo esc_html( get_the_date( 'd/m/Y', $post_id ) ); ?>
									</time>
								</span>
								<span class="wcs-post-meta__item wcs-post-meta__views">
									<span class="wcs-post-meta__icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'eye' ) ); ?></span>
									<span><?php echo esc_html( wcs_format_count( $views_count ) ); ?></span>
								</span>
								<span class="wcs-post-meta__item wcs-post-meta__comments">
									<span class="wcs-post-meta__icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'comment' ) ); ?></span>
									<span><?php echo esc_html( number_format_i18n( get_comments_number( $post_id ) ) ); ?></span>
								</span>
							</div>
						</div>
					</article>
				</div>

				<div class="col large-6 medium-6 small-12 pb-0">
					<div class="wcs-post-list">
						<?php foreach ( $hero_list as $list_post ) : ?>
							<?php
							$l_id  = $list_post->ID;
							$l_cat = wcs_get_primary_category( $l_id );
							?>
							<article class="wcs-post-list__item">
								<a href="<?php echo esc_url( get_permalink( $l_id ) ); ?>" class="wcs-post-list__thumb">
									<?php echo wp_kses_post( wcs_get_post_thumbnail( $l_id, 'wcs-thumb-sm', 'wcs-post-list__img' ) ); ?>
								</a>
								<div class="wcs-post-list__body">
									<?php if ( $l_cat ) : ?>
										<a href="<?php echo esc_url( get_category_link( $l_cat->term_id ) ); ?>" class="wcs-post-list__cat">
											<?php echo esc_html( $l_cat->name ); ?>
										</a>
									<?php endif; ?>
									<h3 class="wcs-post-list__title">
										<a href="<?php echo esc_url( get_permalink( $l_id ) ); ?>">
											<?php echo esc_html( get_the_title( $l_id ) ); ?>
										</a>
									</h3>
									<div class="wcs-post-meta">
										<span class="wcs-post-meta__item wcs-post-meta__date">
											<time datetime="<?php echo esc_attr( get_the_date( 'c', $l_id ) ); ?>">
												<?php echo esc_html( get_the_date( 'd/m/Y', $l_id ) ); ?>
											</time>
										</span>
										<span class="wcs-post-meta__item wcs-post-meta__comments">
											<span class="wcs-post-meta__icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'comment' ) ); ?></span>
											<span><?php echo esc_html( number_format_i18n( get_comments_number( $l_id ) ) ); ?></span>
										</span>
									</div>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $feed_posts ) ) : ?>
		<div class="wcs-card wcs-archive-feed">
			<?php
			echo do_shortcode(
				'[wcs_section_title icon="news" title="' . esc_attr__( 'BÀI VIẾT MỚI CẬP NHẬT', 'webchiase' ) . '"]'
			);
			?>
			<div class="wcs-archive-list">
				<?php foreach ( $feed_posts as $feed_post ) : ?>
					<?php
					$f_id    = $feed_post->ID;
					$f_cat   = wcs_get_primary_category( $f_id );
					$f_views = wcs_get_post_views( $f_id );
					?>
					<article id="post-<?php echo esc_attr( (string) $f_id ); ?>" class="wcs-archive-item">
						<a href="<?php echo esc_url( get_permalink( $f_id ) ); ?>" class="wcs-archive-item__thumb">
							<?php echo wp_kses_post( wcs_get_post_thumbnail( $f_id, 'wcs-thumb-lg', 'wcs-archive-item__img' ) ); ?>
						</a>
						<div class="wcs-archive-item__body">
							<?php if ( $f_cat ) : ?>
								<a href="<?php echo esc_url( get_category_link( $f_cat->term_id ) ); ?>" class="wcs-post-list__cat">
									<?php echo esc_html( $f_cat->name ); ?>
								</a>
							<?php endif; ?>
							<h2 class="wcs-archive-item__title">
								<a href="<?php echo esc_url( get_permalink( $f_id ) ); ?>">
									<?php echo esc_html( get_the_title( $f_id ) ); ?>
								</a>
							</h2>
							<p class="wcs-archive-item__excerpt">
								<?php echo esc_html( wp_trim_words( get_the_excerpt( $f_id ), 28, '...' ) ); ?>
							</p>
							<div class="wcs-post-meta">
								<span class="wcs-post-meta__item wcs-post-meta__date">
									<span class="wcs-post-meta__icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'calendar' ) ); ?></span>
									<time datetime="<?php echo esc_attr( get_the_date( 'c', $f_id ) ); ?>">
										<?php echo esc_html( get_the_date( 'd/m/Y', $f_id ) ); ?>
									</time>
								</span>
								<span class="wcs-post-meta__item wcs-post-meta__views">
									<span class="wcs-post-meta__icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'eye' ) ); ?></span>
									<span><?php echo esc_html( wcs_format_count( $f_views ) ); ?></span>
								</span>
								<span class="wcs-post-meta__item wcs-post-meta__comments">
									<span class="wcs-post-meta__icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'comment' ) ); ?></span>
									<span><?php echo esc_html( number_format_i18n( get_comments_number( $f_id ) ) ); ?></span>
								</span>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>

			<div class="wcs-pagination-wrap">
				<?php flatsome_posts_pagination(); ?>
			</div>
		</div>
	<?php endif; ?>

<?php else : ?>
	<div class="wcs-card">
		<?php get_template_part( 'template-parts/posts/content', 'none' ); ?>
	</div>
<?php endif; ?>
