<?php
// Add custom Theme Functions here

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Child Theme Setup: Textdomain & Image Sizes
 */
function wcs_theme_setup() {
	load_child_theme_textdomain( 'webchiase', get_stylesheet_directory() . '/languages' );

	// Tỉ lệ cố định cho bài nổi bật (16:10) và bài list ngang (3:2).
	add_image_size( 'wcs-thumb-lg', 720, 450, true );
	add_image_size( 'wcs-thumb-sm', 240, 160, true );
}
add_action( 'after_setup_theme', 'wcs_theme_setup', 20 );

/**
 * 2. Enqueue Custom CSS
 */
function wcs_enqueue_assets() {
	$theme_version = wp_get_theme()->get( 'Version' );
	$css_path      = get_stylesheet_directory() . '/assets/css/custom.css';
	$css_ver       = file_exists( $css_path ) ? (string) filemtime( $css_path ) : $theme_version;

	wp_enqueue_style(
		'webchiase-custom',
		get_stylesheet_directory_uri() . '/assets/css/custom.css',
		array( 'flatsome-main' ),
		$css_ver,
		'all'
	);
}
add_action( 'wp_enqueue_scripts', 'wcs_enqueue_assets', 110 );

/**
 * 3. Helper: Định dạng số rút gọn (VD: 12400 -> 12.4K)
 *
 * @param int $number Số đầu vào.
 * @return string
 */
function wcs_format_count( $number ) {
	$number = absint( $number );
	if ( $number >= 1000000 ) {
		return round( $number / 1000000, 1 ) . 'M';
	}
	if ( $number >= 1000 ) {
		return round( $number / 1000, 1 ) . 'K';
	}
	return (string) number_format_i18n( $number );
}

/**
 * 4. Helper: Kiểm tra site đã có dữ liệu post_views hay plugin Post Views Counter chưa
 *
 * @return bool
 */
function wcs_has_post_views_support() {
	if ( function_exists( 'pvc_get_post_views' ) || class_exists( 'Post_Views_Counter' ) ) {
		return true;
	}

	global $wpdb;
	static $has_meta = null;
	if ( null === $has_meta ) {
		$meta_id  = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT meta_id FROM {$wpdb->postmeta} WHERE meta_key = %s LIMIT 1",
				'post_views'
			)
		);
		$has_meta = ! empty( $meta_id );
	}

	return $has_meta;
}

/**
 * 5. Helper: Lấy lượt xem bài viết (hỗ trợ Post Views Counter, meta post_views, fallback comment_count)
 *
 * @param int|null $post_id Post ID.
 * @return int
 */
function wcs_get_post_views( $post_id = null ) {
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();
	if ( ! $post_id ) {
		return 0;
	}

	if ( function_exists( 'pvc_get_post_views' ) ) {
		return absint( pvc_get_post_views( $post_id ) );
	}

	$views = get_post_meta( $post_id, 'post_views', true );
	if ( '' !== $views && false !== $views ) {
		return absint( $views );
	}

	return absint( get_comments_number( $post_id ) );
}

/**
 * Tự động cộng lượt xem vào meta `post_views` khi xem bài chi tiết (nếu chưa cài Post Views Counter)
 */
function wcs_track_post_views() {
	if ( ! is_singular( 'post' ) || is_preview() ) {
		return;
	}
	if ( function_exists( 'pvc_get_post_views' ) || class_exists( 'Post_Views_Counter' ) ) {
		return;
	}

	$post_id = get_queried_object_id();
	if ( $post_id ) {
		$current = (int) get_post_meta( $post_id, 'post_views', true );
		update_post_meta( $post_id, 'post_views', $current + 1 );
	}
}
add_action( 'wp_head', 'wcs_track_post_views' );

/**
 * 6. Helper: Lấy chuyên mục chính của bài viết
 *
 * @param int|null $post_id Post ID.
 * @return WP_Term|null
 */
function wcs_get_primary_category( $post_id = null ) {
	$post_id    = $post_id ? absint( $post_id ) : get_the_ID();
	$categories = get_the_category( $post_id );

	if ( empty( $categories ) || is_wp_error( $categories ) ) {
		return null;
	}

	// Hỗ trợ Yoast SEO primary category nếu có.
	if ( class_exists( 'WPSEO_Primary_Term' ) ) {
		$primary_term = new WPSEO_Primary_Term( 'category', $post_id );
		$primary_id   = $primary_term->get_primary_term();
		if ( $primary_id ) {
			foreach ( $categories as $cat ) {
				if ( (int) $cat->term_id === (int) $primary_id ) {
					return $cat;
				}
			}
		}
	}

	return $categories[0];
}

/**
 * 7. Helper: Lấy ảnh đại diện bài viết có fallback placeholder chuẩn tỉ lệ
 *
 * @param int    $post_id Post ID.
 * @param string $size    Image size.
 * @param string $class   CSS class.
 * @return string HTML <img>
 */
function wcs_get_post_thumbnail( $post_id, $size = 'wcs-thumb-sm', $class = 'wcs-thumb-img' ) {
	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail(
			$post_id,
			$size,
			array(
				'class'    => esc_attr( $class ),
				'loading'  => 'lazy',
				'decoding' => 'async',
			)
		);
	}

	$title       = get_the_title( $post_id );
	$placeholder = 'data:image/svg+xml;utf8,' . rawurlencode(
		'<svg xmlns="http://www.w3.org/2000/svg" width="720" height="450" viewBox="0 0 720 450" fill="none"><rect width="720" height="450" fill="#E5EBF5"/><path d="M295 190h130v70H295z" stroke="#94A3B8" stroke-width="6" rx="8"/><circle cx="325" cy="215" r="10" fill="#94A3B8"/><path d="M305 250l35-28 25 20 30-22 20 30H305z" fill="#94A3B8"/></svg>'
	);

	return sprintf(
		'<img src="%1$s" alt="%2$s" class="%3$s" loading="lazy" decoding="async" />',
		esc_url( $placeholder ),
		esc_attr( $title ),
		esc_attr( $class )
	);
}

/**
 * 8. Helper: Thư viện icon SVG nhẹ nội bộ (hoặc URL ảnh nếu truyền link)
 *
 * @param string $icon Tên icon hoặc URL ảnh.
 * @return string HTML SVG hoặc <img>
 */
function wcs_get_icon_html( $icon ) {
	$icon = trim( (string) $icon );
	if ( '' === $icon ) {
		return '';
	}

	// Nếu truyền URL ảnh hoặc đường dẫn file.
	if ( 0 === strpos( $icon, 'http://' ) || 0 === strpos( $icon, 'https://' ) || 0 === strpos( $icon, '/' ) ) {
		return sprintf(
			'<img src="%s" alt="" class="wcs-icon-img" loading="lazy" decoding="async" />',
			esc_url( $icon )
		);
	}

	$icons = array(
		'law'          => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v18"/><path d="M5 7h14"/><path d="M8 21h8"/><path d="M5 7L2 14h6L5 7z"/><path d="M19 7l-3 7h6l-3-7z"/><path d="M2 14a3 3 0 0 0 6 0"/><path d="M16 14a3 3 0 0 0 6 0"/></svg>',
		'code'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="3"/><polyline points="9 9 6 12 9 15"/><polyline points="15 9 18 12 15 15"/><line x1="13" y1="8" x2="11" y2="16"/></svg>',
		'chart'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 20h18"/><path d="M6 16v-4"/><path d="M10 16v-7"/><path d="M14 16v-5"/><path d="M18 16V8"/><path d="M5 10l5-4 4 3 5-5"/><polyline points="15 4 19 4 19 8"/></svg>',
		'finance'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><ellipse cx="12" cy="6" rx="7" ry="3"/><path d="M5 6v6c0 1.66 3.13 3 7 3s7-1.34 7-3V6"/><path d="M5 12v6c0 1.66 3.13 3 7 3s7-1.34 7-3v-6"/></svg>',
		'heart'        => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>',
		'bulb'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18h6"/><path d="M10 22h4"/><path d="M12 2v1"/><path d="M4.93 4.93l.7.7"/><path d="M19.07 4.93l-.7.7"/><path d="M2 12h1"/><path d="M21 12h1"/><path d="M15.5 14.5A5.5 5.5 0 1 0 8.5 14.5c.7.63 1.2 1.47 1.35 2.5h4.3c.15-1.03.65-1.87 1.35-2.5z"/></svg>',
		'grad'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12.5V17c0 1.7 2.7 3 6 3s6-1.3 6-3v-4.5"/><path d="M22 10v6"/></svg>',
		'news'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 22h14a2 2 0 0 0 2-2V4a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-.55.45-1 1-1h4"/><path d="M11 7h5"/><path d="M11 11h5"/><path d="M11 15h3"/></svg>',
		'fire'         => '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M13.5 2C13.5 2 16 5.2 16 8.2C16 9.6 15.2 10.8 14.1 11.4C14.8 9.8 14.3 8.1 13.2 7C11.4 9.2 9.5 11.2 9.5 14.2C9.5 16.5 11.2 18.5 13.5 19C10.1 19.3 7 16.8 7 13.1C7 9.3 10.2 5.9 13.5 2Z" fill="#F97316"/><path d="M12.5 9C14.8 10.9 17 13.1 17 15.8C17 19.2 14.3 22 11 22C7.7 22 5 19.2 5 15.8C5 13.6 6.2 11.6 7.8 10.3C7.5 11.5 8 12.8 9 13.4C8.8 11.6 10.3 9.9 12.5 9Z" fill="#EF4444"/><path d="M11.5 14C12.9 15 13.8 16.3 13.8 17.8C13.8 19.6 12.4 21 10.8 21C9.1 21 7.8 19.6 7.8 17.8C7.8 16.3 8.9 14.9 11.5 14Z" fill="#FACC15"/></svg>',
		'calendar'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
		'comment'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>',
		'eye'          => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>',
		'arrow-right'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="13 6 19 12 13 18"/></svg>',
		'phone'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.58 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
		'mail'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 6l-10 7L2 6"/></svg>',
		'check-doc'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15l2 2 4-4"/></svg>',
		'clock-sync'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 15"/><path d="M19 4v4h-4"/></svg>',
		'users-expert' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
		'shield-heart' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M12 15.5l-2.5-2.3a1.8 1.8 0 0 1 2.5-2.5 1.8 1.8 0 0 1 2.5 2.5L12 15.5z"/></svg>',
		'headset'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>',
		'home'         => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 3l9 8h-3v9h-5v-6h-2v6H6v-9H3l9-8z"/></svg>',
		'grid'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>',
		'search'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
	);

	return isset( $icons[ $icon ] ) ? $icons[ $icon ] : $icons['news'];
}

/**
 * 9. Helper: Cho phép xuất thẻ SVG an toàn qua wp_kses
 *
 * @param string $html Chuỗi HTML chứa SVG.
 * @return string
 */
function wcs_kses_icon( $html ) {
	$allowed = wp_kses_allowed_html( 'post' );

	$allowed['svg']      = array(
		'xmlns'           => true,
		'viewbox'         => true,
		'width'           => true,
		'height'          => true,
		'fill'            => true,
		'stroke'          => true,
		'stroke-width'    => true,
		'stroke-linecap'  => true,
		'stroke-linejoin' => true,
		'aria-hidden'     => true,
		'class'           => true,
	);
	$allowed['path']     = array(
		'd'               => true,
		'fill'            => true,
		'stroke'          => true,
		'stroke-width'    => true,
		'stroke-linecap'  => true,
		'stroke-linejoin' => true,
	);
	$allowed['rect']     = array(
		'x'      => true,
		'y'      => true,
		'width'  => true,
		'height' => true,
		'rx'     => true,
		'ry'     => true,
		'fill'   => true,
		'stroke' => true,
	);
	$allowed['circle']   = array(
		'cx'     => true,
		'cy'     => true,
		'r'      => true,
		'fill'   => true,
		'stroke' => true,
	);
	$allowed['ellipse']  = array(
		'cx'     => true,
		'cy'     => true,
		'rx'     => true,
		'ry'     => true,
		'fill'   => true,
		'stroke' => true,
	);
	$allowed['line']     = array(
		'x1'     => true,
		'y1'     => true,
		'x2'     => true,
		'y2'     => true,
		'stroke' => true,
	);
	$allowed['polyline'] = array(
		'points' => true,
		'fill'   => true,
		'stroke' => true,
	);

	return wp_kses( $html, $allowed );
}

/**
 * 10. Thanh điều hướng cố định dưới đáy màn hình Mobile (giống bản thiết kế Mobile)
 */
function wcs_mobile_bottom_nav() {
	$is_home    = is_front_page() || is_home();
	$is_archive = is_category() || is_archive();
	$is_search  = is_search();
	?>
	<nav class="wcs-mobile-bottom-nav show-for-medium" aria-label="<?php echo esc_attr__( 'Menu di động dưới đáy', 'webchiase' ); ?>">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="wcs-mobile-bottom-nav__item <?php echo $is_home ? 'is-active' : ''; ?>">
			<span class="wcs-mobile-bottom-nav__icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'home' ) ); ?></span>
			<span class="wcs-mobile-bottom-nav__label"><?php echo esc_html__( 'Trang chủ', 'webchiase' ); ?></span>
		</a>
		<a href="#main-menu" data-open="#main-menu" class="wcs-mobile-bottom-nav__item <?php echo $is_archive ? 'is-active' : ''; ?>">
			<span class="wcs-mobile-bottom-nav__icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'grid' ) ); ?></span>
			<span class="wcs-mobile-bottom-nav__label"><?php echo esc_html__( 'Danh mục', 'webchiase' ); ?></span>
		</a>
		<a href="#search-lightbox" data-open="#search-lightbox" class="wcs-mobile-bottom-nav__item <?php echo $is_search ? 'is-active' : ''; ?>">
			<span class="wcs-mobile-bottom-nav__icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'search' ) ); ?></span>
			<span class="wcs-mobile-bottom-nav__label"><?php echo esc_html__( 'Tìm kiếm', 'webchiase' ); ?></span>
		</a>
		<a href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>" class="wcs-mobile-bottom-nav__item">
			<span class="wcs-mobile-bottom-nav__icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'mail' ) ); ?></span>
			<span class="wcs-mobile-bottom-nav__label"><?php echo esc_html__( 'Liên hệ', 'webchiase' ); ?></span>
		</a>
	</nav>
	<?php
}
add_action( 'wp_footer', 'wcs_mobile_bottom_nav', 30 );

/**
 * 11. Tự động hiển thị Footer Strip nền Navy trên trang Chuyên mục, Chi tiết bài viết
 * (Tránh nhân đôi nếu trang tĩnh đã dán trực tiếp shortcode [wcs_feature_item] trong UX Builder).
 */
function wcs_render_global_footer_strip() {
	if ( is_singular() ) {
		$post_obj = get_queried_object();
		if ( $post_obj instanceof WP_Post && has_shortcode( $post_obj->post_content, 'wcs_feature_item' ) ) {
			return;
		}
	}
	get_template_part( 'template-parts/wcs/footer', 'strip' );
}
add_action( 'flatsome_footer', 'wcs_render_global_footer_strip', 5 );

/**
 * 12. Nạp danh sách Shortcodes tái sử dụng & Bộ khởi tạo dữ liệu mẫu 1-Click
 */
require_once get_stylesheet_directory() . '/inc/shortcodes.php';
require_once get_stylesheet_directory() . '/inc/seeder.php';

/**
 * Popup lấy thông tin khách hàng (lightbox Flatsome + Contact Form 7).
 */
require_once get_stylesheet_directory() . '/inc/lead-popup.php';


/**
 * Footer chính của Luật Nhân Tâm.
 */
require_once get_stylesheet_directory() . "/inc/footer-main.php";
