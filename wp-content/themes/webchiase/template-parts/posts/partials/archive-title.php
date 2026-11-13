<?php
/**
 * Post archive title (Category, Tag, Search, Date, Author) theo phong cách LuatNhanTam.vn
 *
 * @package          webchiase
 * @flatsome-version 3.16.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$archive_title = '';
$archive_desc  = '';
$archive_icon  = 'news';
$post_count    = 0;
$child_cats    = array();

if ( is_category() ) {
	$term          = get_queried_object();
	$archive_title = single_cat_title( '', false );
	$archive_desc  = category_description();
	if ( $term && isset( $term->count ) ) {
		$post_count = (int) $term->count;
		$slug       = $term->slug;
		if ( false !== strpos( $slug, 'luat' ) ) {
			$archive_icon = 'law';
		} elseif ( false !== strpos( $slug, 'cong-nghe' ) || false !== strpos( $slug, 'it' ) ) {
			$archive_icon = 'code';
		} elseif ( false !== strpos( $slug, 'kinh-doanh' ) ) {
			$archive_icon = 'chart';
		} elseif ( false !== strpos( $slug, 'tai-chinh' ) ) {
			$archive_icon = 'finance';
		} elseif ( false !== strpos( $slug, 'doi-song' ) ) {
			$archive_icon = 'heart';
		} elseif ( false !== strpos( $slug, 'kien-thuc' ) ) {
			$archive_icon = 'bulb';
		}

		$child_cats = get_categories(
			array(
				'parent'     => $term->term_id,
				'hide_empty' => false,
			)
		);
	}
} elseif ( is_tag() ) {
	$archive_title = single_tag_title( '#', false );
	$archive_desc  = tag_description();
} elseif ( is_search() ) {
	/* translators: %s: từ khóa tìm kiếm */
	$archive_title = sprintf( __( 'Kết quả tìm kiếm: "%s"', 'webchiase' ), get_search_query() );
	$archive_icon  = 'search';
	global $wp_query;
	$post_count = isset( $wp_query->found_posts ) ? (int) $wp_query->found_posts : 0;
} elseif ( is_author() ) {
	the_post();
	/* translators: %s: tên tác giả */
	$archive_title = sprintf( __( 'Tác giả: %s', 'webchiase' ), get_the_author() );
	$archive_desc  = get_the_author_meta( 'description' );
	rewind_posts();
} elseif ( is_day() ) {
	$archive_title = get_the_date();
} elseif ( is_month() ) {
	$archive_title = get_the_date( 'm/Y' );
} elseif ( is_year() ) {
	$archive_title = get_the_date( 'Y' );
} else {
	$archive_title = __( 'Tin tức & Bài viết', 'webchiase' );
}
?>
<header class="wcs-archive-header">
	<div class="row">
		<div class="large-12 col pb-0">
			<nav class="wcs-breadcrumb" aria-label="<?php echo esc_attr__( 'Breadcrumb', 'webchiase' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="wcs-breadcrumb__link">
					<span class="wcs-breadcrumb__home-icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'home' ) ); ?></span>
					<span><?php echo esc_html__( 'Trang chủ', 'webchiase' ); ?></span>
				</a>
				<span class="wcs-breadcrumb__sep" aria-hidden="true">/</span>
				<span class="wcs-breadcrumb__current"><?php echo esc_html( $archive_title ); ?></span>
			</nav>

			<div class="wcs-card wcs-archive-banner">
				<div class="wcs-archive-banner__main">
					<span class="wcs-archive-banner__icon">
						<?php echo wcs_kses_icon( wcs_get_icon_html( $archive_icon ) ); ?>
					</span>
					<div class="wcs-archive-banner__text">
						<div class="wcs-archive-banner__title-row">
							<h1 class="wcs-archive-banner__title"><?php echo esc_html( $archive_title ); ?></h1>
							<?php if ( $post_count > 0 ) : ?>
								<span class="wcs-archive-banner__count">
									<?php
									/* translators: %s: số bài viết */
									printf( esc_html__( '%s bài viết', 'webchiase' ), esc_html( number_format_i18n( $post_count ) ) );
									?>
								</span>
							<?php endif; ?>
						</div>
						<?php if ( ! empty( $archive_desc ) ) : ?>
							<div class="wcs-archive-banner__desc">
								<?php echo wp_kses_post( $archive_desc ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( ! empty( $child_cats ) && ! is_wp_error( $child_cats ) ) : ?>
					<div class="wcs-archive-subcats">
						<?php foreach ( $child_cats as $subcat ) : ?>
							<a href="<?php echo esc_url( get_category_link( $subcat->term_id ) ); ?>" class="wcs-archive-subcats__pill">
								<?php echo esc_html( $subcat->name ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</header>
