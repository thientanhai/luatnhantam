<?php
/**
 * Bộ khởi tạo dữ liệu mẫu 1-Click cho LuatNhanTam.vn
 * Tạo tự động:
 * - 7 Chuyên mục chính + chuyên mục con
 * - 14 Bài viết mẫu chuẩn nội dung như ảnh thiết kế (kèm ảnh đại diện + lượt xem post_views)
 * - Trang chủ tĩnh (gắn sẵn UX Builder Shortcode + Template Page - Full Width)
 * - Menu chính (gắn vào vị trí Main Menu & Mobile Menu)
 * - Cấu hình mặc định Flatsome Theme Options & Header Builder
 *
 * @package webchiase
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Thêm trang con trong Appearance -> Khởi tạo dữ liệu WCS & Admin Notice
 */
function wcs_register_seeder_menu() {
	add_theme_page(
		__( 'Khởi tạo dữ liệu WebChiaSe', 'webchiase' ),
		__( 'Khởi tạo dữ liệu WCS', 'webchiase' ),
		'manage_options',
		'wcs-seeder',
		'wcs_render_seeder_admin_page'
	);
}
add_action( 'admin_menu', 'wcs_register_seeder_menu' );

/**
 * Hiển thị thông báo gợi ý khởi tạo nhanh nếu chưa chạy lần nào
 */
function wcs_seeder_admin_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( get_option( 'wcs_sample_data_seeded' ) ) {
		return;
	}
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( $screen && 'appearance_page_wcs-seeder' === $screen->id ) {
		return;
	}
	$seeder_url = admin_url( 'themes.php?page=wcs-seeder' );
	?>
	<div class="notice notice-info is-dismissible">
		<p>
			<strong>LuatNhanTam.vn:</strong>
			<?php echo esc_html__( 'Bạn có thể tạo tự động 7 Chuyên mục, 14 Bài viết mẫu (kèm ảnh đại diện), Menu chính và Trang chủ chuẩn thiết kế chỉ với 1 cú click.', 'webchiase' ); ?>
			<a href="<?php echo esc_url( $seeder_url ); ?>" class="button button-primary" style="margin-left:8px;">
				<?php echo esc_html__( 'Đi tới Khởi tạo dữ liệu mẫu →', 'webchiase' ); ?>
			</a>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'wcs_seeder_admin_notice' );

/**
 * 2. Giao diện trang Admin Khởi tạo dữ liệu
 */
function wcs_render_seeder_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$results = array();
	if ( isset( $_POST['wcs_run_seeder'] ) && check_admin_referer( 'wcs_seeder_action', 'wcs_seeder_nonce' ) ) {
		$results = wcs_execute_sample_data_seed();
	}

	$is_seeded = (bool) get_option( 'wcs_sample_data_seeded' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'Khởi tạo dữ liệu mẫu LuatNhanTam.vn (1-Click Setup)', 'webchiase' ); ?></h1>

		<?php if ( ! empty( $results ) ) : ?>
			<div class="notice notice-success" style="padding:12px 16px;margin-top:16px;">
				<h3 style="margin:0 0 8px;"><?php echo esc_html__( 'Đã khởi tạo dữ liệu thành công!', 'webchiase' ); ?></h3>
				<ul style="list-style:disc;padding-left:20px;margin:0;">
					<?php foreach ( $results as $msg ) : ?>
						<li><?php echo esc_html( $msg ); ?></li>
					<?php endforeach; ?>
				</ul>
				<p style="margin-top:12px;margin-bottom:0;">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button button-primary" target="_blank">
						<?php echo esc_html__( 'Xem Trang chủ ngay →', 'webchiase' ); ?>
					</a>
				</p>
			</div>
		<?php endif; ?>

		<div class="card" style="max-width:760px;padding:20px;margin-top:16px;">
			<h2 style="margin-top:0;"><?php echo esc_html__( 'Công cụ này sẽ tự động tạo:', 'webchiase' ); ?></h2>
			<ol style="line-height:1.7;">
				<li><strong>7 Chuyên mục chính</strong>: <code>Luật pháp</code>, <code>Công nghệ - IT</code>, <code>Kinh doanh</code>, <code>Tài chính</code>, <code>Đời sống</code>, <code>Kiến thức</code>, <code>Tin tức</code> (kèm mô tả ngắn).</li>
				<li><strong>14 Bài viết mẫu</strong> khớp với ảnh thiết kế, có sẵn nội dung HTML chi tiết, lượt xem (<code>post_views</code>) và tự động tạo <strong>Ảnh đại diện (Featured Image)</strong>.</li>
				<li><strong>Trang tĩnh "Trang chủ"</strong> gắn sẵn mã UX Builder Shortcode + Template <code>Page - Full Width</code> và tự đặt làm <strong>Homepage</strong> trong <em>Settings → Reading</em>.</li>
				<li><strong>Menu chính (Main Menu)</strong> gồm Trang chủ + 7 Chuyên mục + Liên hệ, gán vào vị trí <code>Main Menu</code> & <code>Main Menu - Mobile</code>.</li>
				<li><strong>Cấu hình Flatsome Theme Options & Header Builder</strong>: Primary Color <code>#1D63DC</code>, Layout <code>1200px</code>, Font <code>Be Vietnam Pro</code>, Top Bar Navy, Search giữa, Hotline & Email bên phải.</li>
			</ol>

			<form method="post" style="margin-top:20px;">
				<?php wp_nonce_field( 'wcs_seeder_action', 'wcs_seeder_nonce' ); ?>
				<button type="submit" name="wcs_run_seeder" value="1" class="button button-primary button-hero">
					<?php
					echo $is_seeded
						? esc_html__( 'Chạy lại / Cập nhật dữ liệu mẫu LuatNhanTam.vn', 'webchiase' )
						: esc_html__( 'Khởi tạo toàn bộ dữ liệu mẫu ngay (1-Click)', 'webchiase' );
					?>
				</button>
			</form>
		</div>
	</div>
	<?php
}

/**
 * 3. Hàm tạo ảnh đại diện bằng PHP GD (không phụ thuộc mạng ngoài) và lưu vào Media Library
 *
 * @param string $title    Tiêu đề bài viết.
 * @param string $cat_name Tên chuyên mục.
 * @param string $hex_bg   Màu nền chủ đạo (hex).
 * @param int    $post_id  Post ID.
 * @return int Attachment ID hoặc 0.
 */
function wcs_generate_demo_thumbnail( $title, $cat_name, $hex_bg, $post_id ) {
	$existing_thumb = get_post_thumbnail_id( $post_id );
	if ( $existing_thumb ) {
		return (int) $existing_thumb;
	}

	if ( ! function_exists( 'imagecreatetruecolor' ) ) {
		return 0;
	}

	$upload_dir = wp_upload_dir();
	if ( ! empty( $upload_dir['error'] ) ) {
		return 0;
	}

	$width  = 720;
	$height = 450;
	$img    = imagecreatetruecolor( $width, $height );
	if ( ! $img ) {
		return 0;
	}

	$hex_bg = ltrim( $hex_bg, '#' );
	$r      = hexdec( substr( $hex_bg, 0, 2 ) );
	$g      = hexdec( substr( $hex_bg, 2, 2 ) );
	$b      = hexdec( substr( $hex_bg, 4, 2 ) );

	// Vẽ nền gradient nhẹ
	for ( $y = 0; $y < $height; $y++ ) {
		$ratio = $y / $height;
		$cr    = (int) max( 0, min( 255, $r + ( 20 - 45 * $ratio ) ) );
		$cg    = (int) max( 0, min( 255, $g + ( 25 - 45 * $ratio ) ) );
		$cb    = (int) max( 0, min( 255, $b + ( 30 - 35 * $ratio ) ) );
		$col   = imagecolorallocate( $img, $cr, $cg, $cb );
		imageline( $img, 0, $y, $width, $y, $col );
	}

	// Họa tiết vòng tròn trang trí
	$accent = imagecolorallocatealpha( $img, 255, 255, 255, 112 );
	imagefilledellipse( $img, 600, 110, 280, 280, $accent );
	imagefilledellipse( $img, 140, 380, 220, 220, $accent );

	$card_bg = imagecolorallocatealpha( $img, 255, 255, 255, 105 );
	imagefilledrectangle( $img, 60, 60, 660, 390, $card_bg );

	$white = imagecolorallocate( $img, 255, 255, 255 );
	imagestring( $img, 5, 90, 90, 'LUATNHANTAM.VN - ' . strtoupper( remove_accents( $cat_name ) ), $white );
	imagestring( $img, 4, 90, 130, substr( remove_accents( $title ), 0, 52 ), $white );

	$filename  = 'wcs-demo-' . $post_id . '.jpg';
	$file_path = trailingslashit( $upload_dir['path'] ) . $filename;

	imagejpeg( $img, $file_path, 88 );
	imagedestroy( $img );

	if ( ! file_exists( $file_path ) ) {
		return 0;
	}

	$attachment = array(
		'post_mime_type' => 'image/jpeg',
		'post_title'     => sanitize_text_field( $title ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);

	$attach_id = wp_insert_attachment( $attachment, $file_path, $post_id );
	if ( ! is_wp_error( $attach_id ) && $attach_id ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		set_post_thumbnail( $post_id, $attach_id );
		return (int) $attach_id;
	}

	return 0;
}

/**
 * 4. Nội dung Shortcode UX Builder chuẩn cho Trang chủ
 *
 * @return string
 */
function wcs_get_homepage_ux_shortcode() {
	return '[section padding="20px" bg_color="#F4F7FB" class="wcs-hero-section"]
[row style="small"]

[col span="8" span__md="12" span__sm="12" class="pb-0"]
<div class="wcs-hero-slider">
[ux_slider style="normal" slide_width="100%" arrows="true" bullets="true" auto_slide="true" timer="5000" nav_color="light"]

[ux_banner height="380px" height__sm="300px" bg_color="#0B2A5B" bg="https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&w=1200&q=80" bg_overlay="rgba(11, 42, 91, 0.68)"]
[text_box width="75" width__sm="90" position_x="5" position_y="50" text_align="left"]
<span class="wcs-hero-banner__badge">CÔNG NGHỆ - IT</span>
<h2 class="wcs-hero-banner__title">Bảo mật thông tin cá nhân trên môi trường số</h2>
<p class="wcs-hero-banner__desc">Những nguyên tắc quan trọng giúp bạn bảo vệ dữ liệu cá nhân, tránh rủi ro lừa đảo và tấn công mạng.</p>
<a href="/category/cong-nghe-it/" class="wcs-hero-banner__btn">Xem chi tiết &rarr;</a>
[/text_box]
[/ux_banner]

[ux_banner height="380px" height__sm="300px" bg_color="#0B2A5B" bg="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=1200&q=80" bg_overlay="rgba(11, 42, 91, 0.68)"]
[text_box width="75" width__sm="90" position_x="5" position_y="50" text_align="left"]
<span class="wcs-hero-banner__badge">PHÁP LUẬT</span>
<h2 class="wcs-hero-banner__title">Những điều cần biết về quyền và nghĩa vụ của người lao động</h2>
<p class="wcs-hero-banner__desc">Cập nhật các quy định pháp luật lao động mới nhất giúp bảo vệ quyền lợi hợp pháp của bạn.</p>
<a href="/category/phap-luat/" class="wcs-hero-banner__btn">Xem chi tiết &rarr;</a>
[/text_box]
[/ux_banner]

[ux_banner height="380px" height__sm="300px" bg_color="#0B2A5B" bg="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&q=80" bg_overlay="rgba(11, 42, 91, 0.68)"]
[text_box width="75" width__sm="90" position_x="5" position_y="50" text_align="left"]
<span class="wcs-hero-banner__badge">KINH DOANH</span>
<h2 class="wcs-hero-banner__title">5 kỹ năng giúp bạn thành công trong kinh doanh online</h2>
<p class="wcs-hero-banner__desc">Chiến lược xây dựng thương hiệu, vận hành dòng tiền và tối ưu chuyển đổi bền vững.</p>
<a href="/category/kinh-doanh/" class="wcs-hero-banner__btn">Xem chi tiết &rarr;</a>
[/text_box]
[/ux_banner]

[/ux_slider]
</div>
[/col]

[col span="4" span__md="12" span__sm="12" visibility="hide-for-small" class="pb-0"]
<div class="wcs-card wcs-card--full-height">
[wcs_post_list count="4" show_cat="1" show_meta="1"]
</div>
[/col]

[/row]
[/section]

[section padding="10px" bg_color="#F4F7FB"]
[row style="small"]
[col span="12" class="pb-0"]
<div class="wcs-cat-strip">
[wcs_cat_tile icon="law" title="Luật pháp" desc="Tư vấn - Hỏi đáp" link="/category/phap-luat/" active="1"]
[wcs_cat_tile icon="code" title="Công nghệ - IT" desc="Thủ thuật - Bảo mật" link="/category/cong-nghe-it/"]
[wcs_cat_tile icon="chart" title="Kinh doanh" desc="Khởi nghiệp - Quản trị" link="/category/kinh-doanh/"]
[wcs_cat_tile icon="finance" title="Tài chính" desc="Đầu tư - Ngân hàng" link="/category/tai-chinh/"]
[wcs_cat_tile icon="heart" title="Đời sống" desc="Sức khỏe - Gia đình" link="/category/doi-song/"]
[wcs_cat_tile icon="bulb" title="Kiến thức" desc="Học tập - Phát triển" link="/category/kien-thuc/"]
[wcs_cat_tile icon="news" title="Tin tức" desc="Trong nước - Quốc tế" link="/category/tin-tuc/"]
</div>
[/col]
[/row]
[/section]

[section padding="15px" bg_color="#F4F7FB"]
[row style="small" class="wcs-row-main"]

[col span="5" span__md="12" span__sm="12"]
<div class="wcs-card wcs-card--full-height">
[wcs_section_title icon="law" title="PHÁP LUẬT" link="/category/phap-luat/"]
[row_inner style="small"]
[col_inner span="6" span__sm="12" class="pb-0"]
[wcs_post_featured cat="phap-luat" count="1"]
[/col_inner]
[col_inner span="6" span__sm="12" class="pb-0"]
[wcs_post_list cat="phap-luat" count="4" offset="1" show_meta="1" show_cat="0"]
[/col_inner]
[/row_inner]
</div>
[/col]

[col span="4" span__md="12" span__sm="12"]
<div class="wcs-card wcs-card--full-height">
[wcs_section_title icon="code" title="CÔNG NGHỆ - IT" link="/category/cong-nghe-it/"]
[wcs_post_featured cat="cong-nghe-it" count="1"]
<div class="wcs-it-list-wrap">
[wcs_post_list cat="cong-nghe-it" count="2" offset="1" show_meta="1" show_cat="0"]
</div>
</div>
[/col]

[col span="3" span__md="12" span__sm="12"]
<div class="wcs-card wcs-mb">
[wcs_section_title icon="fire" title="BÀI VIẾT ĐƯỢC QUAN TÂM"]
[wcs_post_ranked count="5" orderby="views"]
</div>
[wcs_cta_banner title="Bạn có câu hỏi?" text="Đội ngũ chuyên gia của chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7" btn_text="Liên hệ ngay" btn_link="/lien-he/"]
[/col]

[/row]
[/section]';
}

/**
 * 5. Thực thi khởi tạo toàn bộ dữ liệu mẫu
 *
 * @return array Danh sách thông báo kết quả.
 */
function wcs_execute_sample_data_seed() {
	$log = array();

	// A. Tạo 7 Chuyên mục chính
	$categories_data = array(
		array(
			'name'  => 'Luật pháp',
			'slug'  => 'phap-luat',
			'desc'  => 'Tư vấn pháp luật, hỏi đáp quy định lao động, dân sự, doanh nghiệp và hành chính mới nhất.',
			'color' => '#8B5A2B',
		),
		array(
			'name'  => 'Công nghệ - IT',
			'slug'  => 'cong-nghe-it',
			'desc'  => 'Thủ thuật máy tính, phần mềm, bảo mật thông tin, đánh giá thiết bị và xu hướng chuyển đổi số.',
			'color' => '#1D63DC',
		),
		array(
			'name'  => 'Kinh doanh',
			'slug'  => 'kinh-doanh',
			'desc'  => 'Kiến thức khởi nghiệp, quản trị doanh nghiệp, bán hàng online và phát triển thương hiệu.',
			'color' => '#0E7490',
		),
		array(
			'name'  => 'Tài chính',
			'slug'  => 'tai-chinh',
			'desc'  => 'Quản lý tài chính cá nhân, đầu tư chứng khoán, bất động sản và dịch vụ ngân hàng.',
			'color' => '#1E40AF',
		),
		array(
			'name'  => 'Đời sống',
			'slug'  => 'doi-song',
			'desc'  => 'Chăm sóc sức khỏe, dinh dưỡng, gia đình và kỹ năng cân bằng cuộc sống hiện đại.',
			'color' => '#15803D',
		),
		array(
			'name'  => 'Kiến thức',
			'slug'  => 'kien-thuc',
			'desc'  => 'Học tập, phát triển bản thân, ngoại ngữ, kỹ năng mềm và tri thức tổng hợp.',
			'color' => '#6D28D9',
		),
		array(
			'name'  => 'Tin tức',
			'slug'  => 'tin-tuc',
			'desc'  => 'Tổng hợp tin tức kinh tế, xã hội trong nước và quốc tế cập nhật mỗi ngày.',
			'color' => '#0B2A5B',
		),
	);

	$cat_ids   = array();
	$cat_color = array();

	foreach ( $categories_data as $cat_item ) {
		$existing = get_term_by( 'slug', $cat_item['slug'], 'category' );
		if ( $existing && ! is_wp_error( $existing ) ) {
			$cat_ids[ $cat_item['slug'] ] = (int) $existing->term_id;
			wp_update_term(
				$existing->term_id,
				'category',
				array(
					'name'        => $cat_item['name'],
					'description' => $cat_item['desc'],
				)
			);
		} else {
			$inserted = wp_insert_term(
				$cat_item['name'],
				'category',
				array(
					'slug'        => $cat_item['slug'],
					'description' => $cat_item['desc'],
				)
			);
			if ( ! is_wp_error( $inserted ) && isset( $inserted['term_id'] ) ) {
				$cat_ids[ $cat_item['slug'] ] = (int) $inserted['term_id'];
			}
		}
		$cat_color[ $cat_item['slug'] ] = $cat_item['color'];
	}
	$log[] = sprintf( 'Đã khởi tạo / cập nhật %d chuyên mục chính.', count( $cat_ids ) );

	// B. Tạo 14 Bài viết mẫu khớp thiết kế
	$sample_posts = array(
		// 6 bài thuộc chuyên mục Luật pháp (1 bài nổi bật + 4 bài list)
		array(
			'title'   => 'Những điều cần biết về quyền và nghĩa vụ của người lao động',
			'slug'    => 'nhung-dieu-can-biet-ve-quyen-va-nghia-vu-cua-nguoi-lao-dong',
			'cat'     => 'phap-luat',
			'excerpt' => 'Người lao động cần nắm rõ quyền lợi và nghĩa vụ của mình để bảo vệ bản thân, tránh những rủi ro pháp lý không đáng có trong quan hệ lao động.',
			'views'   => 12400,
			'date'    => '2025-10-03 21:00:00',
		),
		array(
			'title'   => 'Hướng dẫn làm thủ tục đăng ký kinh doanh online',
			'slug'    => 'huong-dan-lam-thu-tuc-dang-ky-kinh-doanh-online',
			'cat'     => 'phap-luat',
			'excerpt' => 'Quy trình chuẩn bị hồ sơ và nộp đăng ký hộ kinh doanh, doanh nghiệp trực tuyến qua Cổng thông tin quốc gia nhanh chóng và hợp lệ.',
			'views'   => 15200,
			'date'    => '2025-10-02 09:15:00',
		),
		array(
			'title'   => 'Các trường hợp được miễn án phí khi khởi kiện',
			'slug'    => 'cac-truong-hop-duoc-mien-an-phi-khi-khoi-kien',
			'cat'     => 'phap-luat',
			'excerpt' => 'Tổng hợp danh mục các đối tượng và vụ việc dân sự, hành chính được miễn hoặc giảm tạm ứng án phí, án phí Tòa án theo quy định hiện hành.',
			'views'   => 6800,
			'date'    => '2025-10-01 10:00:00',
		),
		array(
			'title'   => 'Phân biệt hợp đồng lao động và hợp đồng dịch vụ',
			'slug'    => 'phan-biet-hop-dong-lao-dong-va-hop-dong-dich-vu',
			'cat'     => 'phap-luat',
			'excerpt' => 'So sánh chi tiết bản chất pháp lý, quyền lợi bảo hiểm xã hội, thuế thu nhập và trách nhiệm của các bên trong hai loại hợp đồng phổ biến.',
			'views'   => 7400,
			'date'    => '2025-09-30 14:20:00',
		),
		array(
			'title'   => 'Quy định mới về xử phạt vi phạm giao thông năm 2025',
			'slug'    => 'quy-dinh-moi-ve-xu-phat-vi-pham-giao-thong-nam-2025',
			'cat'     => 'phap-luat',
			'excerpt' => 'Cập nhật mức phạt tiền và hệ thống trừ điểm giấy phép lái xe đối với các hành vi vi phạm trật tự an toàn giao thông đường bộ.',
			'views'   => 8200,
			'date'    => '2025-09-28 16:00:00',
		),
		array(
			'title'   => 'Thủ tục ly hôn đơn phương mới nhất năm 2025',
			'slug'    => 'thu-tuc-ly-hon-don-phuong-moi-nhat-nam-2025',
			'cat'     => 'phap-luat',
			'excerpt' => 'Hướng dẫn chi tiết hồ sơ, thẩm quyền Tòa án giải quyết, thời gian thụ lý và quy định chia tài sản, quyền nuôi con khi ly hôn đơn phương.',
			'views'   => 9800,
			'date'    => '2025-09-25 11:00:00',
		),

		// 4 bài thuộc chuyên mục Công nghệ - IT (1 bài nổi bật + 2 bài list + 1 bài thêm)
		array(
			'title'   => 'Hướng dẫn cài đặt Windows 11 chi tiết từ A-Z',
			'slug'    => 'huong-dan-cai-dat-windows-11-chi-tiet-tu-a-z',
			'cat'     => 'cong-nghe-it',
			'excerpt' => 'Bài viết hướng dẫn từng bước cài đặt Windows 11 một cách dễ hiểu, phù hợp cho cả người mới bắt đầu mà không làm mất dữ liệu.',
			'views'   => 8700,
			'date'    => '2025-10-02 21:00:00',
		),
		array(
			'title'   => 'Cách bảo vệ tài khoản Facebook khỏi bị hack',
			'slug'    => 'cach-bao-ve-tai-khoan-facebook-khoi-bi-hack',
			'cat'     => 'cong-nghe-it',
			'excerpt' => 'Thiết lập xác thực 2 yếu tố (2FA), cảnh báo đăng nhập lạ và nhận diện các đường link giả mạo đánh cắp thông tin tài khoản.',
			'views'   => 12800,
			'date'    => '2025-10-02 10:30:00',
		),
		array(
			'title'   => 'Tối ưu SEO cho website mới: 10 bước quan trọng',
			'slug'    => 'toi-uu-seo-cho-website-moi-10-buoc-quan-trong',
			'cat'     => 'cong-nghe-it',
			'excerpt' => 'Checklist cấu trúc on-page, tốc độ tải trang, nghiên cứu từ khóa và xây dựng nội dung chuẩn EEAT giúp website lên top Google bền vững.',
			'views'   => 9300,
			'date'    => '2025-09-29 09:45:00',
		),
		array(
			'title'   => 'Laptop cho sinh viên: Nên chọn hãng nào tốt nhất?',
			'slug'    => 'laptop-cho-sinh-vien-nen-chon-hang-nao-tot-nhat',
			'cat'     => 'cong-nghe-it',
			'excerpt' => 'Tư vấn tiêu chí chọn mua laptop theo từng ngành học Kinh tế, Kỹ thuật, Đồ họa với độ bền cao và thời lượng pin ấn tượng.',
			'views'   => 7900,
			'date'    => '2025-09-20 18:00:00',
		),

		// Các chuyên mục Kinh doanh, Đời sống, Tài chính, Kiến thức
		array(
			'title'   => '5 kỹ năng giúp bạn thành công trong kinh doanh online',
			'slug'    => '5-ky-nang-giup-ban-thanh-cong-trong-kinh-doanh-online',
			'cat'     => 'kinh-doanh',
			'excerpt' => 'Nắm vững nghiên cứu thị trường, xây dựng thương hiệu cá nhân, chăm sóc khách hàng và quản trị dòng tiền hiệu quả trong kỷ nguyên số.',
			'views'   => 8500,
			'date'    => '2025-10-01 20:00:00',
		),
		array(
			'title'   => 'Cách sống khỏe mỗi ngày dành cho người bận rộn',
			'slug'    => 'cach-song-khoe-moi-ngay-danh-cho-nguoi-ban-ron',
			'cat'     => 'doi-song',
			'excerpt' => 'Xây dựng chế độ dinh dưỡng cân bằng, vận động ngắn tại văn phòng và cải thiện chất lượng giấc ngủ chỉ với 20 phút mỗi ngày.',
			'views'   => 8900,
			'date'    => '2025-09-30 19:30:00',
		),
		array(
			'title'   => 'Quy tắc 50/30/20 trong quản lý tài chính cá nhân hiệu quả',
			'slug'    => 'quy-tac-50-30-20-trong-quan-ly-tai-chinh-ca-nhan',
			'cat'     => 'tai-chinh',
			'excerpt' => 'Phương pháp phân bổ thu nhập khoa học giúp kiểm soát chi tiêu thiết yếu, tận hưởng cuộc sống và tích lũy quỹ dự phòng dài hạn.',
			'views'   => 6400,
			'date'    => '2025-09-27 14:00:00',
		),
		array(
			'title'   => 'Phương pháp ghi nhớ nhanh và học tập chủ động Active Recall',
			'slug'    => 'phuong-phap-ghi-nho-nhanh-va-hoc-tap-chu-dong-active-recall',
			'cat'     => 'kien-thuc',
			'excerpt' => 'Ứng dụng kỹ thuật Active Recall kết hợp Spaced Repetition để tăng gấp đôi hiệu suất tiếp thu kiến thức mới và ôn luyện chuyên sâu.',
			'views'   => 6100,
			'date'    => '2025-09-26 09:00:00',
		),
	);

	$created_posts = 0;
	foreach ( $sample_posts as $item ) {
		$existing_post = get_page_by_path( $item['slug'], OBJECT, 'post' );
		$term_id       = isset( $cat_ids[ $item['cat'] ] ) ? $cat_ids[ $item['cat'] ] : 1;

		$body_html = sprintf(
			'<p><strong>%1$s</strong></p>
			<h2>1. Tổng quan vấn đề cần lưu ý</h2>
			<p>Trong bối cảnh hiện nay, việc trang bị kiến thức chuẩn xác về <strong>%2$s</strong> đóng vai trò vô cùng quan trọng giúp cá nhân và doanh nghiệp chủ động nắm bắt cơ hội cũng như phòng ngừa rủi ro phát sinh.</p>
			<blockquote>Bài viết được đội ngũ biên tập viên và chuyên gia của LuatNhanTam.vn tổng hợp, kiểm duyệt kỹ lưỡng theo các quy định và xu hướng thực tiễn mới nhất.</blockquote>
			<h2>2. Hướng dẫn các bước thực hiện chi tiết</h2>
			<ul>
				<li><strong>Bước 1:</strong> Xác định rõ nhu cầu, đối tượng áp dụng và các điều kiện cần thiết trước khi triển khai.</li>
				<li><strong>Bước 2:</strong> Chuẩn bị đầy đủ hồ sơ, công cụ hoặc dữ liệu đối chiếu theo hướng dẫn tiêu chuẩn.</li>
				<li><strong>Bước 3:</strong> Kiểm tra định kỳ và cập nhật những thay đổi mới nhất để đảm bảo tính an toàn, hiệu quả lâu dài.</li>
			</ul>
			<h2>3. Lời khuyên từ chuyên gia LuatNhanTam.vn</h2>
			<p>Nếu bạn có bất kỳ vướng mắc nào trong quá trình áp dụng thực tế, đừng ngần ngại để lại bình luận phía dưới hoặc liên hệ trực tiếp với đội ngũ tư vấn của LuatNhanTam.vn để được hỗ trợ kịp thời.</p>',
			esc_html( $item['excerpt'] ),
			esc_html( $item['title'] )
		);

		$post_data = array(
			'post_title'    => $item['title'],
			'post_name'     => $item['slug'],
			'post_excerpt'  => $item['excerpt'],
			'post_content'  => $body_html,
			'post_status'   => 'publish',
			'post_type'     => 'post',
			'post_date'     => $item['date'],
			'post_category' => array( $term_id ),
		);

		if ( $existing_post ) {
			$post_data['ID'] = $existing_post->ID;
			$post_id         = wp_update_post( $post_data );
		} else {
			$post_id = wp_insert_post( $post_data );
			if ( $post_id && ! is_wp_error( $post_id ) ) {
				$created_posts++;
			}
		}

		if ( $post_id && ! is_wp_error( $post_id ) ) {
			update_post_meta( $post_id, 'post_views', (int) $item['views'] );
			$bg_hex   = isset( $cat_color[ $item['cat'] ] ) ? $cat_color[ $item['cat'] ] : '#1D63DC';
			$cat_term = get_term( $term_id, 'category' );
			$cat_name = ( $cat_term && ! is_wp_error( $cat_term ) ) ? $cat_term->name : 'WebChiaSe';
			wcs_generate_demo_thumbnail( $item['title'], $cat_name, $bg_hex, $post_id );
		}
	}
	$log[] = sprintf( 'Đã đồng bộ 14 bài viết mẫu (tạo mới %d bài) kèm ảnh đại diện và lượt xem.', $created_posts );

	// C. Tạo trang "Trang chủ" & trang "Liên hệ" và đặt làm Front Page
	$home_page = get_page_by_path( 'trang-chu', OBJECT, 'page' );
	$home_args = array(
		'post_title'   => 'Trang chủ',
		'post_name'    => 'trang-chu',
		'post_content' => wcs_get_homepage_ux_shortcode(),
		'post_status'  => 'publish',
		'post_type'    => 'page',
	);
	if ( $home_page ) {
		$home_args['ID'] = $home_page->ID;
		$home_id         = wp_update_post( $home_args );
	} else {
		$home_id = wp_insert_post( $home_args );
	}

	if ( $home_id && ! is_wp_error( $home_id ) ) {
		update_post_meta( $home_id, '_wp_page_template', 'page-blank.php' );
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', (int) $home_id );
		$log[] = 'Đã tạo trang "Trang chủ" (Template: Page - Full Width) và đặt làm Trang chủ tĩnh.';
	}

	$contact_page = get_page_by_path( 'lien-he', OBJECT, 'page' );
	if ( ! $contact_page ) {
		wp_insert_post(
			array(
				'post_title'   => 'Liên hệ',
				'post_name'    => 'lien-he',
				'post_content' => '<div class="wcs-card"><h2>Liên hệ với LuatNhanTam.vn</h2><p>Hotline hỗ trợ: <strong>07777.333.55</strong></p><p>Email: <strong>luatnhantam.vn@gmail.com</strong></p></div>',
				'post_status'  => 'publish',
				'post_type'    => 'page',
			)
		);
	}

	// D. Tạo Menu chính (Main Menu) và gán vào vị trí primary & primary_mobile
	$menu_name = 'Main Menu WebChiaSe';
	$menu_obj  = wp_get_nav_menu_object( $menu_name );
	if ( ! $menu_obj ) {
		$menu_id = wp_create_nav_menu( $menu_name );
		if ( ! is_wp_error( $menu_id ) ) {
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'  => 'Trang chủ',
					'menu-item-url'    => home_url( '/' ),
					'menu-item-status' => 'publish',
					'menu-item-type'   => 'custom',
				)
			);

			foreach ( $categories_data as $cat_item ) {
				if ( isset( $cat_ids[ $cat_item['slug'] ] ) ) {
					wp_update_nav_menu_item(
						$menu_id,
						0,
						array(
							'menu-item-title'     => $cat_item['name'],
							'menu-item-object-id' => $cat_ids[ $cat_item['slug'] ],
							'menu-item-object'    => 'category',
							'menu-item-type'      => 'taxonomy',
							'menu-item-status'    => 'publish',
						)
					);
				}
			}

			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'  => 'Liên hệ',
					'menu-item-url'    => home_url( '/lien-he/' ),
					'menu-item-status' => 'publish',
					'menu-item-type'   => 'custom',
				)
			);

			$locations                   = get_theme_mod( 'nav_menu_locations', array() );
			$locations['primary']        = $menu_id;
			$locations['primary_mobile'] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
			$log[] = 'Đã tạo "Main Menu WebChiaSe" và gán vào vị trí Main Menu & Mobile Menu.';
		}
	}

	// E. Thiết lập Flatsome Theme Options & Header Builder chuẩn thiết kế
	set_theme_mod( 'site_width', 1230 );
	set_theme_mod( 'color_primary', '#1D63DC' );
	set_theme_mod( 'color_secondary', '#0B2A5B' );
	set_theme_mod( 'content_bg', '#F4F7FB' );
	set_theme_mod( 'topbar_bg', '#0B2A5B' );
	set_theme_mod( 'nav_style_bottom', 'nav-line-bottom' );
	set_theme_mod( 'topbar_left', 'Chia sẻ tri thức - Kết nối cộng đồng - Phát triển cùng bạn' );
	set_theme_mod( 'header_block_1', '[wcs_top_auth_links]' );
	set_theme_mod( 'header_block_2', '<div class="wcs-header-contacts">[wcs_header_contact type="phone" label="Hotline hỗ trợ" value="07777.333.55"][wcs_header_contact type="email" label="Email liên hệ" value="luatnhantam.vn@gmail.com"]</div>' );
	set_theme_mod( 'topbar_elements_left', array( 'html' ) );
	set_theme_mod( 'topbar_elements_right', array( 'custom-1', 'social' ) );
	set_theme_mod( 'header_elements_left', array() );
	set_theme_mod( 'header_elements_center', array( 'search-form' ) );
	set_theme_mod( 'header_elements_right', array( 'custom-2' ) );
	set_theme_mod( 'header_elements_bottom_left', array( 'nav' ) );
	$log[] = 'Đã cấu hình tự động Flatsome Theme Options & Header Builder.';

	update_option( 'wcs_sample_data_seeded', 1 );

	return $log;
}
