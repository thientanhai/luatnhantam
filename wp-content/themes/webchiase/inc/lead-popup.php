<?php
/**
 * Lead popup: tự động hiện lightbox (Magnific Popup của Flatsome) chứa form Contact Form 7.
 *
 * Form CF7 được tạo tự động một lần (tên "Lead Popup - Tư vấn"), sau đó có thể chỉnh trong
 * admin: Contact > Contact Forms. ID form lưu ở option `lnt_lead_cf7_id`.
 * Có thể ép dùng form khác: define( 'LNT_LEAD_CF7_ID', 123 ) hoặc filter 'lnt_lead_cf7_id'.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function lnt_lead_provinces() {
	return array(
		'Hà Nội', 'Hồ Chí Minh', 'Hải Phòng', 'Đà Nẵng', 'Cần Thơ', 'Huế', 'An Giang', 'Bắc Ninh', 'Cà Mau',
		'Cao Bằng', 'Đắk Lắk', 'Điện Biên', 'Đồng Nai', 'Đồng Tháp', 'Gia Lai', 'Hà Tĩnh', 'Hưng Yên',
		'Khánh Hòa', 'Lai Châu', 'Lạng Sơn', 'Lào Cai', 'Lâm Đồng', 'Nghệ An', 'Ninh Bình', 'Phú Thọ',
		'Quảng Ngãi', 'Quảng Ninh', 'Quảng Trị', 'Sơn La', 'Tây Ninh', 'Thái Nguyên', 'Thanh Hóa',
		'Tuyên Quang', 'Vĩnh Long',
	);
}

function lnt_lead_form_template() {
	$options = '"' . implode( '" "', lnt_lead_provinces() ) . '"';
	return '<label>Họ và tên <span class="req">*</span>
[text* your-name placeholder "Tên của bạn"]</label>

<label>Số điện thoại <span class="req">*</span>
[tel* your-phone placeholder "Số điện thoại nhận tư vấn"]</label>

<label>Email <span class="req">*</span>
[email* your-email placeholder "Email nhận tư vấn"]</label>

<label>Tỉnh/thành <span class="req">*</span>
[select* your-province first_as_label "Lựa chọn tỉnh/thành" ' . $options . ']</label>

<label>Nội dung cần tư vấn <span class="req">*</span>
[textarea* your-message placeholder "Nhập nội dung bạn cần tư vấn"]</label>

<p class="lnt-lead-note">Dữ liệu cá nhân của bạn sẽ được bảo mật tuyệt đối với mọi tương tác trên ' . esc_html( wp_parse_url( home_url(), PHP_URL_HOST ) ) . '</p>

[submit "Gửi thông tin"]';
}

function lnt_lead_create_form() {
	if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
		return 0;
	}
	$cf = WPCF7_ContactForm::get_template( array( 'title' => 'Lead Popup - Tư vấn' ) );
	$props = $cf->get_properties();
	$props['form'] = lnt_lead_form_template();

	$mail                       = $props['mail'];
	$mail['recipient']          = get_option( 'admin_email' );
	$mail['subject']            = '[Luật Nhân Tâm] Khách hàng mới: [your-name]';
	$mail['body']               = "Họ tên: [your-name]\nSĐT: [your-phone]\nEmail: [your-email]\nTỉnh/thành: [your-province]\n\nNội dung:\n[your-message]\n\n--\nGửi từ popup tư vấn: [_url]";
	$mail['additional_headers'] = 'Reply-To: [your-email]';
	$props['mail']              = $mail;

	$cf->set_properties( $props );
	$cf->save();
	return (int) $cf->id();
}

function lnt_lead_cf7_id() {
	if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
		return 0;
	}
	if ( defined( 'LNT_LEAD_CF7_ID' ) && LNT_LEAD_CF7_ID ) {
		return (int) LNT_LEAD_CF7_ID;
	}
	$id = (int) apply_filters( 'lnt_lead_cf7_id', 0 );
	if ( $id ) {
		return $id;
	}
	$id = (int) get_option( 'lnt_lead_cf7_id' );
	if ( $id && 'publish' === get_post_status( $id ) && 'wpcf7_contact_form' === get_post_type( $id ) ) {
		return $id;
	}
	$id = lnt_lead_create_form();
	update_option( 'lnt_lead_cf7_id', $id, false );
	return $id;
}

add_action( 'wp_enqueue_scripts', function () {
	if ( ! lnt_lead_cf7_id() ) {
		return;
	}
	$dir = get_stylesheet_directory();
	$uri = get_stylesheet_directory_uri();
	wp_enqueue_style( 'lnt-lead-popup', $uri . '/assets/lead-popup.css', array(), filemtime( $dir . '/assets/lead-popup.css' ) );
	wp_enqueue_script( 'lnt-lead-popup', $uri . '/assets/lead-popup.js', array( 'jquery', 'flatsome-js' ), filemtime( $dir . '/assets/lead-popup.js' ), true );
	wp_localize_script( 'lnt-lead-popup', 'LNT_LEAD', array(
		'delay' => 1000, // ms sau khi vào site
	) );
} );

add_action( 'wp_footer', function () {
	$id = lnt_lead_cf7_id();
	if ( ! $id ) {
		return;
	}
	?>
	<div id="lnt-lead-popup" class="lightbox-by-id lightbox-content mfp-hide lightbox-white">
		<h2 class="lnt-lead-title">Gửi câu hỏi của bạn cho chúng tôi</h2>
		<p class="lnt-lead-desc">Công ty Luật Nhân Tâm rất hân hạnh được hỗ trợ và đồng hành cùng bạn. Hãy để lại thông tin cần tư vấn, chúng tôi sẽ nhanh chóng phản hồi.</p>
		<div class="lnt-lead-line"></div>
		<?php echo do_shortcode( '[contact-form-7 id="' . $id . '"]' ); ?>
	</div>
	<?php
} );
