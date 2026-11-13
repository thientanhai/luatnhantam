<?php
/**
 * Posts single (Chi tiết bài viết theo phong cách trang chủ LuatNhanTam.vn).
 *
 * @package          webchiase
 * @flatsome-version 3.19.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		$post_id     = get_the_ID();
		$primary_cat = wcs_get_primary_category( $post_id );
		$views_count = wcs_get_post_views( $post_id );
		?>

		<!-- Breadcrumb -->
		<nav class="wcs-breadcrumb" aria-label="<?php echo esc_attr__( 'Breadcrumb', 'webchiase' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="wcs-breadcrumb__link">
				<span class="wcs-breadcrumb__home-icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'home' ) ); ?></span>
				<span><?php echo esc_html__( 'Trang chủ', 'webchiase' ); ?></span>
			</a>
			<?php if ( $primary_cat ) : ?>
				<span class="wcs-breadcrumb__sep" aria-hidden="true">/</span>
				<a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="wcs-breadcrumb__link">
					<?php echo esc_html( $primary_cat->name ); ?>
				</a>
			<?php endif; ?>
			<span class="wcs-breadcrumb__sep" aria-hidden="true">/</span>
			<span class="wcs-breadcrumb__current"><?php echo esc_html( wp_trim_words( get_the_title(), 10, '...' ) ); ?></span>
		</nav>

		<!-- Card Nội dung bài viết -->
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'wcs-card wcs-single-card' ); ?>>
			<header class="wcs-single__header">
				<?php if ( $primary_cat ) : ?>
					<div class="wcs-single__badge-wrap">
						<a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="wcs-badge">
							<?php echo esc_html( $primary_cat->name ); ?>
						</a>
					</div>
				<?php endif; ?>

				<h1 class="wcs-single__title"><?php the_title(); ?></h1>

				<div class="wcs-single__meta-bar">
					<div class="wcs-single__author">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 32, '', '', array( 'class' => 'wcs-single__author-avatar' ) ); ?>
						<span class="wcs-single__author-name"><?php the_author(); ?></span>
					</div>

					<div class="wcs-post-meta">
						<span class="wcs-post-meta__item wcs-post-meta__date">
							<span class="wcs-post-meta__icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'calendar' ) ); ?></span>
							<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
								<?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?>
							</time>
						</span>
						<span class="wcs-post-meta__item wcs-post-meta__views">
							<span class="wcs-post-meta__icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'eye' ) ); ?></span>
							<span>
								<?php
								/* translators: %s: lượt xem */
								printf( esc_html__( '%s lượt xem', 'webchiase' ), esc_html( wcs_format_count( $views_count ) ) );
								?>
							</span>
						</span>
						<span class="wcs-post-meta__item wcs-post-meta__comments">
							<span class="wcs-post-meta__icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'comment' ) ); ?></span>
							<span>
								<?php
								/* translators: %s: số bình luận */
								printf( esc_html__( '%s bình luận', 'webchiase' ), esc_html( number_format_i18n( get_comments_number() ) ) );
								?>
							</span>
						</span>
					</div>
				</div>
			</header>

			<?php if ( has_post_thumbnail() && get_theme_mod( 'blog_single_featured_image', 1 ) ) : ?>
				<div class="wcs-single__featured-img">
					<?php the_post_thumbnail( 'large', array( 'class' => 'wcs-single__img', 'loading' => 'eager' ) ); ?>
				</div>
			<?php endif; ?>

			<?php get_template_part( 'template-parts/posts/content', 'single' ); ?>
		</article>

		<!-- Khối Bài viết cùng chuyên mục (tái sử dụng shortcode giống Trang chủ) -->
		<?php if ( $primary_cat ) : ?>
			<section class="wcs-card wcs-related-card wcs-mt">
				<?php
				echo do_shortcode(
					sprintf(
						'[wcs_section_title icon="law" title="%1$s" link="%2$s"]',
						esc_attr__( 'BÀI VIẾT CÙNG CHUYÊN MỤC', 'webchiase' ),
						esc_url( get_category_link( $primary_cat->term_id ) )
					)
				);
				?>
				<div class="row row-small">
					<div class="col large-6 medium-6 small-12 pb-0">
						<?php echo do_shortcode( '[wcs_post_featured cat="' . esc_attr( $primary_cat->slug ) . '" count="1"]' ); ?>
					</div>
					<div class="col large-6 medium-6 small-12 pb-0">
						<?php echo do_shortcode( '[wcs_post_list cat="' . esc_attr( $primary_cat->slug ) . '" count="4" offset="1" show_meta="1" show_cat="0"]' ); ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php
	endwhile;
else :
	get_template_part( 'no-results', 'index' );
endif;
