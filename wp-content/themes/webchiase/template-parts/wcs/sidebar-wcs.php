<?php
/**
 * Template: Sidebar chuẩn WebChiaSe (dùng cho trang Chuyên mục, Tìm kiếm & Chi tiết bài viết)
 *
 * @package webchiase
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = get_categories(
	array(
		'orderby'    => 'count',
		'order'      => 'DESC',
		'hide_empty' => false,
		'number'     => 7,
	)
);
?>
<div class="wcs-sidebar">
	<!-- Khối 1: Bài viết được quan tâm -->
	<div class="wcs-card wcs-mb">
		<?php
		echo do_shortcode( '[wcs_section_title icon="fire" title="' . esc_attr__( 'BÀI VIẾT ĐƯỢC QUAN TÂM', 'webchiase' ) . '"]' );
		echo do_shortcode( '[wcs_post_ranked count="5" orderby="views"]' );
		?>
	</div>

	<?php if ( is_singular( 'post' ) ) : ?>
		<!-- Khối 2 (Trang chi tiết): Bài viết mới nhất -->
		<div class="wcs-card wcs-mb">
			<?php
			echo do_shortcode( '[wcs_section_title icon="news" title="' . esc_attr__( 'BÀI VIẾT MỚI NHẤT', 'webchiase' ) . '"]' );
			echo do_shortcode( '[wcs_post_list count="4" show_cat="1" show_meta="1"]' );
			?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
		<!-- Khối 3: Danh mục chuyên đề -->
		<div class="wcs-card wcs-mb">
			<?php echo do_shortcode( '[wcs_section_title icon="law" title="' . esc_attr__( 'CHUYÊN MỤC', 'webchiase' ) . '"]' ); ?>
			<ul class="wcs-sidebar-cats">
				<?php foreach ( $categories as $cat_item ) : ?>
					<?php
					$is_current = is_category( $cat_item->term_id );
					?>
					<li class="wcs-sidebar-cats__item <?php echo $is_current ? 'is-active' : ''; ?>">
						<a href="<?php echo esc_url( get_category_link( $cat_item->term_id ) ); ?>" class="wcs-sidebar-cats__link">
							<span class="wcs-sidebar-cats__name"><?php echo esc_html( $cat_item->name ); ?></span>
							<span class="wcs-sidebar-cats__count"><?php echo esc_html( number_format_i18n( $cat_item->count ) ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<!-- Khối 4: Banner CTA Hỏi đáp -->
	<div class="wcs-mb">
		<?php echo do_shortcode( '[wcs_cta_banner]' ); ?>
	</div>

	<?php if ( is_active_sidebar( 'sidebar-main' ) ) : ?>
		<div class="wcs-sidebar-widgets">
			<?php dynamic_sidebar( 'sidebar-main' ); ?>
		</div>
	<?php endif; ?>
</div>
