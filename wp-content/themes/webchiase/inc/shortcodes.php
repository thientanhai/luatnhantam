<?php
/**
 * Đăng ký các Shortcode tái sử dụng cho LuatNhanTam.vn
 *
 * @package webchiase
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helper gọi template part trong template-parts/wcs/ và trả về chuỗi HTML
 *
 * @param string $slug Tên file template trong template-parts/wcs/.
 * @param array  $args Tham số truyền vào template.
 * @return string
 */
function wcs_render_shortcode_template( $slug, $args = array() ) {
	ob_start();
	get_template_part( 'template-parts/wcs/' . $slug, null, $args );
	return ob_get_clean();
}

/**
 * Helper tạo WP_Query thông minh theo tham số cat (hỗ trợ cả ID và slug, tự fallback bài mới nhất nếu chuyên mục chưa có bài)
 *
 * @param string|int $cat    Category ID hoặc slug.
 * @param int        $count  Số lượng bài.
 * @param int        $offset Độ lệch bài viết.
 * @return WP_Query
 */
function wcs_query_posts( $cat = '', $count = 4, $offset = 0 ) {
	$query_args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => max( 1, absint( $count ) ),
		'offset'              => max( 0, absint( $offset ) ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	$cat = trim( (string) $cat );
	if ( '' !== $cat ) {
		if ( is_numeric( $cat ) ) {
			$query_args['cat'] = absint( $cat );
		} else {
			$query_args['category_name'] = sanitize_title( $cat );
		}
	}

	$query = new WP_Query( $query_args );

	// Fallback: Nếu chuyên mục chỉ định chưa được tạo hoặc chưa có đủ bài viết, lấy bài mới nhất chung.
	if ( ! $query->have_posts() && '' !== $cat ) {
		unset( $query_args['cat'], $query_args['category_name'] );
		$query = new WP_Query( $query_args );
	}

	return $query;
}

/**
 * 1. [wcs_section_title icon="" title="" link=""]
 * Tiêu đề khối: Icon + Chữ HOA + "Xem tất cả →"
 */
function wcs_shortcode_section_title( $atts ) {
	$atts = shortcode_atts(
		array(
			'icon'      => 'law',
			'title'     => '',
			'link'      => '',
			'link_text' => __( 'Xem tất cả', 'webchiase' ),
		),
		$atts,
		'wcs_section_title'
	);

	return wcs_render_shortcode_template( 'section-title', $atts );
}
add_shortcode( 'wcs_section_title', 'wcs_shortcode_section_title' );

/**
 * 2. [wcs_post_list cat="" count="" offset="" show_meta="" show_cat=""]
 * Danh sách bài viết ngang (thumb nhỏ bên trái, tiêu đề 2 dòng, ngày + bình luận)
 */
function wcs_shortcode_post_list( $atts ) {
	$atts = shortcode_atts(
		array(
			'cat'       => '',
			'count'     => 4,
			'offset'    => 0,
			'show_meta' => '1',
			'show_cat'  => '0',
		),
		$atts,
		'wcs_post_list'
	);

	return wcs_render_shortcode_template( 'post-list', $atts );
}
add_shortcode( 'wcs_post_list', 'wcs_shortcode_post_list' );

/**
 * 3. [wcs_post_featured cat="" count="1"]
 * Bài viết nổi bật: ảnh lớn + badge chuyên mục + tiêu đề + mô tả ngắn + meta
 */
function wcs_shortcode_post_featured( $atts ) {
	$atts = shortcode_atts(
		array(
			'cat'        => '',
			'count'      => 1,
			'offset'     => 0,
			'badge_pos'  => 'below', // 'below' (desktop khối Pháp luật/IT) hoặc 'overlay'
			'show_views' => '1',
		),
		$atts,
		'wcs_post_featured'
	);

	return wcs_render_shortcode_template( 'post-featured', $atts );
}
add_shortcode( 'wcs_post_featured', 'wcs_shortcode_post_featured' );

/**
 * 4. [wcs_post_ranked count="5" orderby="views|comments"]
 * Danh sách bài viết được quan tâm đánh số vòng tròn màu primary
 */
function wcs_shortcode_post_ranked( $atts ) {
	$atts = shortcode_atts(
		array(
			'count'      => 5,
			'orderby'    => 'views',
			'cat'        => '',
			'show_views' => '1',
		),
		$atts,
		'wcs_post_ranked'
	);

	return wcs_render_shortcode_template( 'post-ranked', $atts );
}
add_shortcode( 'wcs_post_ranked', 'wcs_shortcode_post_ranked' );

/**
 * 5. [wcs_cat_tile icon="" title="" desc="" link="" active=""]
 * Ô danh mục (icon tròn nền xanh nhạt)
 */
function wcs_shortcode_cat_tile( $atts ) {
	$atts = shortcode_atts(
		array(
			'icon'   => 'law',
			'title'  => '',
			'desc'   => '',
			'link'   => '#',
			'active' => '0',
		),
		$atts,
		'wcs_cat_tile'
	);

	return wcs_render_shortcode_template( 'cat-tile', $atts );
}
add_shortcode( 'wcs_cat_tile', 'wcs_shortcode_cat_tile' );

/**
 * 6. [wcs_cta_banner title="" text="" btn_text="" btn_link="" image=""]
 * Banner CTA "Bạn có câu hỏi?"
 */
function wcs_shortcode_cta_banner( $atts ) {
	$atts = shortcode_atts(
		array(
			'subtitle' => __( 'CẦN HỖ TRỢ?', 'webchiase' ),
			'title'    => __( 'Bạn có câu hỏi?', 'webchiase' ),
			'text'     => __( 'Đội ngũ chuyên gia của chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7', 'webchiase' ),
			'btn_text' => __( 'Liên hệ ngay', 'webchiase' ),
			'btn_link' => '/lien-he/',
			'image'    => '',
		),
		$atts,
		'wcs_cta_banner'
	);

	return wcs_render_shortcode_template( 'cta-banner', $atts );
}
add_shortcode( 'wcs_cta_banner', 'wcs_shortcode_cta_banner' );

/**
 * 7. [wcs_feature_item icon="" title="" desc=""]
 * Mục cam kết trong Footer Strip nền navy
 */
function wcs_shortcode_feature_item( $atts ) {
	$atts = shortcode_atts(
		array(
			'icon'  => 'check-doc',
			'title' => '',
			'desc'  => '',
		),
		$atts,
		'wcs_feature_item'
	);

	return wcs_render_shortcode_template( 'feature-item', $atts );
}
add_shortcode( 'wcs_feature_item', 'wcs_shortcode_feature_item' );

/**
 * 8. [wcs_header_contact type="phone|email" label="" value="" link=""]
 * Dùng cho từng mục Hotline hoặc Email đơn lẻ trên Header
 */
function wcs_shortcode_header_contact( $atts ) {
	$atts = shortcode_atts(
		array(
			'type'  => 'phone',
			'label' => '',
			'value' => '',
			'link'  => '',
		),
		$atts,
		'wcs_header_contact'
	);

	return wcs_render_shortcode_template( 'header-contact', $atts );
}
add_shortcode( 'wcs_header_contact', 'wcs_shortcode_header_contact' );

/**
 * 8b. [wcs_header_contacts phone="" phone_label="" email="" email_label=""]
 * Dùng gom cả Hotline và Email vào 1 shortcode tiện lợi cho Header Builder (HTML 2 hoặc HTML 3)
 */
function wcs_shortcode_header_contacts( $atts ) {
	$atts = shortcode_atts(
		array(
			'phone'       => '07777.333.55',
			'phone_label' => __( 'Hotline hỗ trợ', 'webchiase' ),
			'email'       => 'luatnhantam.vn@gmail.com',
			'email_label' => __( 'Email liên hệ', 'webchiase' ),
			'show_phone'  => '1',
			'show_email'  => '1',
		),
		$atts,
		'wcs_header_contacts'
	);

	return wcs_render_shortcode_template( 'header-contacts', $atts );
}
add_shortcode( 'wcs_header_contacts', 'wcs_shortcode_header_contacts' );

/**
 * 9. [wcs_top_auth_links login_url="" register_url=""]
 * Dùng trong Top Bar (HTML 1) cho liên kết Đăng nhập | Đăng ký
 */
function wcs_shortcode_top_auth_links( $atts ) {
	$atts = shortcode_atts(
		array(
			'login_url'    => wp_login_url(),
			'register_url' => wp_registration_url(),
		),
		$atts,
		'wcs_top_auth_links'
	);

	$user_svg = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';

	if ( is_user_logged_in() ) {
		$current_user = wp_get_current_user();
		return sprintf(
			'<div class="wcs-top-links"><a href="%1$s">%2$s <span>%3$s</span></a></div>',
			esc_url( admin_url( 'profile.php' ) ),
			wcs_kses_icon( $user_svg ),
			esc_html( $current_user->display_name )
		);
	}

	return sprintf(
		'<div class="wcs-top-links"><a href="%1$s">%2$s <span>%3$s</span></a><span class="wcs-top-links__sep">|</span><a href="%4$s">%2$s <span>%5$s</span></a></div>',
		esc_url( $atts['login_url'] ),
		wcs_kses_icon( $user_svg ),
		esc_html__( 'Đăng nhập', 'webchiase' ),
		esc_url( $atts['register_url'] ),
		esc_html__( 'Đăng ký', 'webchiase' )
	);
}
add_shortcode( 'wcs_top_auth_links', 'wcs_shortcode_top_auth_links' );

/**
 * Tích hợp các Shortcode vào UX Builder của Flatsome để chỉnh sửa trực quan
 */
function wcs_register_ux_builder_elements() {
	if ( ! function_exists( 'add_ux_builder_shortcode' ) ) {
		return;
	}

	add_ux_builder_shortcode(
		'wcs_section_title',
		array(
			'name'     => __( 'WCS - Tiêu đề khối', 'webchiase' ),
			'category' => __( 'WebChiaSe', 'webchiase' ),
			'options'  => array(
				'icon'  => array(
					'type'    => 'textfield',
					'heading' => __( 'Icon (law, code, fire, news... hoặc URL)', 'webchiase' ),
					'default' => 'law',
				),
				'title' => array(
					'type'    => 'textfield',
					'heading' => __( 'Tiêu đề', 'webchiase' ),
					'default' => 'PHÁP LUẬT',
				),
				'link'  => array(
					'type'    => 'textfield',
					'heading' => __( 'Link Xem tất cả', 'webchiase' ),
					'default' => '',
				),
			),
		)
	);

	add_ux_builder_shortcode(
		'wcs_post_list',
		array(
			'name'     => __( 'WCS - Bài viết List ngang', 'webchiase' ),
			'category' => __( 'WebChiaSe', 'webchiase' ),
			'options'  => array(
				'cat'       => array(
					'type'    => 'textfield',
					'heading' => __( 'Slug hoặc ID chuyên mục', 'webchiase' ),
					'default' => '',
				),
				'count'     => array(
					'type'    => 'slider',
					'heading' => __( 'Số lượng bài', 'webchiase' ),
					'default' => 4,
					'min'     => 1,
					'max'     => 10,
				),
				'offset'    => array(
					'type'    => 'slider',
					'heading' => __( 'Bỏ qua (Offset)', 'webchiase' ),
					'default' => 0,
					'min'     => 0,
					'max'     => 10,
				),
				'show_meta' => array(
					'type'    => 'checkbox',
					'heading' => __( 'Hiện ngày & bình luận', 'webchiase' ),
					'default' => '1',
				),
				'show_cat'  => array(
					'type'    => 'checkbox',
					'heading' => __( 'Hiện tên chuyên mục', 'webchiase' ),
					'default' => '0',
				),
			),
		)
	);

	add_ux_builder_shortcode(
		'wcs_post_featured',
		array(
			'name'     => __( 'WCS - Bài viết Nổi bật', 'webchiase' ),
			'category' => __( 'WebChiaSe', 'webchiase' ),
			'options'  => array(
				'cat'   => array(
					'type'    => 'textfield',
					'heading' => __( 'Slug hoặc ID chuyên mục', 'webchiase' ),
					'default' => '',
				),
				'count' => array(
					'type'    => 'slider',
					'heading' => __( 'Số lượng bài', 'webchiase' ),
					'default' => 1,
					'min'     => 1,
					'max'     => 4,
				),
			),
		)
	);

	add_ux_builder_shortcode(
		'wcs_post_ranked',
		array(
			'name'     => __( 'WCS - Bài viết Đánh số', 'webchiase' ),
			'category' => __( 'WebChiaSe', 'webchiase' ),
			'options'  => array(
				'count'   => array(
					'type'    => 'slider',
					'heading' => __( 'Số lượng bài', 'webchiase' ),
					'default' => 5,
					'min'     => 1,
					'max'     => 10,
				),
				'orderby' => array(
					'type'    => 'select',
					'heading' => __( 'Sắp xếp theo', 'webchiase' ),
					'default' => 'views',
					'options' => array(
						'views'    => __( 'Lượt xem (fallback Bình luận)', 'webchiase' ),
						'comments' => __( 'Số bình luận', 'webchiase' ),
					),
				),
			),
		)
	);

	add_ux_builder_shortcode(
		'wcs_cat_tile',
		array(
			'name'     => __( 'WCS - Ô Danh mục', 'webchiase' ),
			'category' => __( 'WebChiaSe', 'webchiase' ),
			'options'  => array(
				'icon'   => array(
					'type'    => 'textfield',
					'heading' => __( 'Icon (law, code, chart, finance, heart, bulb, news)', 'webchiase' ),
					'default' => 'law',
				),
				'title'  => array(
					'type'    => 'textfield',
					'heading' => __( 'Tên danh mục', 'webchiase' ),
					'default' => 'Luật pháp',
				),
				'desc'   => array(
					'type'    => 'textfield',
					'heading' => __( 'Mô tả ngắn', 'webchiase' ),
					'default' => 'Tư vấn - Hỏi đáp',
				),
				'link'   => array(
					'type'    => 'textfield',
					'heading' => __( 'Đường dẫn', 'webchiase' ),
					'default' => '#',
				),
				'active' => array(
					'type'    => 'checkbox',
					'heading' => __( 'Trạng thái nổi bật (Mobile)', 'webchiase' ),
					'default' => '0',
				),
			),
		)
	);

	add_ux_builder_shortcode(
		'wcs_cta_banner',
		array(
			'name'     => __( 'WCS - Banner CTA Hỏi đáp', 'webchiase' ),
			'category' => __( 'WebChiaSe', 'webchiase' ),
			'options'  => array(
				'title'    => array(
					'type'    => 'textfield',
					'heading' => __( 'Tiêu đề', 'webchiase' ),
					'default' => 'Bạn có câu hỏi?',
				),
				'text'     => array(
					'type'    => 'textfield',
					'heading' => __( 'Nội dung', 'webchiase' ),
					'default' => 'Đội ngũ chuyên gia của chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7',
				),
				'btn_text' => array(
					'type'    => 'textfield',
					'heading' => __( 'Chữ trên nút', 'webchiase' ),
					'default' => 'Liên hệ ngay',
				),
				'btn_link' => array(
					'type'    => 'textfield',
					'heading' => __( 'Link nút', 'webchiase' ),
					'default' => '/lien-he/',
				),
				'image'    => array(
					'type'    => 'textfield',
					'heading' => __( 'URL ảnh nhân viên tư vấn (tùy chọn)', 'webchiase' ),
					'default' => '',
				),
			),
		)
	);

	add_ux_builder_shortcode(
		'wcs_feature_item',
		array(
			'name'     => __( 'WCS - Mục Footer Strip', 'webchiase' ),
			'category' => __( 'WebChiaSe', 'webchiase' ),
			'options'  => array(
				'icon'  => array(
					'type'    => 'textfield',
					'heading' => __( 'Icon (check-doc, clock-sync, users-expert, shield-heart)', 'webchiase' ),
					'default' => 'check-doc',
				),
				'title' => array(
					'type'    => 'textfield',
					'heading' => __( 'Tiêu đề', 'webchiase' ),
					'default' => 'Nội dung chất lượng',
				),
				'desc'  => array(
					'type'    => 'textfield',
					'heading' => __( 'Mô tả', 'webchiase' ),
					'default' => 'Được kiểm duyệt kỹ lưỡng',
				),
			),
		)
	);

	add_ux_builder_shortcode(
		'wcs_header_contacts',
		array(
			'name'     => __( 'WCS - Hotline & Email Header', 'webchiase' ),
			'category' => __( 'WebChiaSe', 'webchiase' ),
			'options'  => array(
				'phone_label' => array(
					'type'    => 'textfield',
					'heading' => __( 'Nhãn Hotline', 'webchiase' ),
					'default' => 'Hotline hỗ trợ',
				),
				'phone'       => array(
					'type'    => 'textfield',
					'heading' => __( 'Số điện thoại Hotline', 'webchiase' ),
					'default' => '07777.333.55',
				),
				'email_label' => array(
					'type'    => 'textfield',
					'heading' => __( 'Nhãn Email', 'webchiase' ),
					'default' => 'Email liên hệ',
				),
				'email'       => array(
					'type'    => 'textfield',
					'heading' => __( 'Địa chỉ Email', 'webchiase' ),
					'default' => 'luatnhantam.vn@gmail.com',
				),
			),
		)
	);
}
add_action( 'ux_builder_setup', 'wcs_register_ux_builder_elements' );
