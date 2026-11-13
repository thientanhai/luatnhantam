<?php
/**
 * Posts layout right sidebar (dùng cho Chuyên mục, Tìm kiếm & Chi tiết bài viết).
 *
 * @package          webchiase
 * @flatsome-version 3.19.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'flatsome_before_blog' );
?>

<div class="row wcs-blog-layout">
	<div class="large-8 medium-12 small-12 col">
		<?php
		if ( is_single() ) {
			get_template_part( 'template-parts/posts/single' );
			if ( comments_open() || get_comments_number() ) {
				echo '<div class="wcs-card wcs-comments-card wcs-mt">';
				comments_template();
				echo '</div>';
			}
		} else {
			get_template_part( 'template-parts/posts/archive' );
		}
		?>
	</div>

	<div class="post-sidebar large-4 medium-12 small-12 col">
		<?php flatsome_sticky_column_open( 'blog_sticky_sidebar' ); ?>
		<?php get_template_part( 'template-parts/wcs/sidebar', 'wcs' ); ?>
		<?php flatsome_sticky_column_close( 'blog_sticky_sidebar' ); ?>
	</div>
</div>

<?php
do_action( 'flatsome_after_blog' );
