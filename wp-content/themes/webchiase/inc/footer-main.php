<?php
/**
 * Footer chính của Luật Nhân Tâm (thay thế footer widgets / UX Block mặc định của Flatsome).
 * Sửa nội dung ở các hàm lnt_footer_*() bên dưới.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Thông tin liên hệ. */
function lnt_footer_contact() {
	return array(
		'company' => 'Công ty Luật Nhân Tâm',
		'heading' => 'LUẬT NHÂN TÂM - CÔNG TY LUẬT UY TÍN, TẬN TÂM, CHUYÊN NGHIỆP',
		'office'  => 'Văn phòng TP. Hồ Chí Minh',
		'phone'   => '0777733355',
		'email'   => 'luatnhantam.vn@gmail.com',
		'address' => '32/3 Bùi Đình Tuý, Phường 12, Bình Thạnh, Hồ Chí Minh',
		'facebook' => 'https://www.facebook.com/luatnhantam',
	);
}

/** Menu "Liên kết": dùng menu footer có sẵn của site (slug menu-footer / menu theo tên). */
function lnt_footer_menu_links() {
	$menu = wp_get_nav_menu_object( 'menu-footer' );
	if ( ! $menu ) {
		foreach ( wp_get_nav_menus() as $m ) {
			if ( false !== stripos( $m->name, 'footer' ) ) {
				$menu = $m;
				break;
			}
		}
	}
	if ( ! $menu ) {
		return array();
	}
	$out = array();
	foreach ( (array) wp_get_nav_menu_items( $menu ) as $item ) {
		$out[] = array( $item->title, $item->url );
	}
	return $out;
}

/** Chuyên mục có sẵn của site. */
function lnt_footer_category_links() {
	$out  = array();
	$cats = get_categories( array( 'parent' => 0, 'hide_empty' => false, 'number' => 8, 'orderby' => 'name' ) );
	foreach ( $cats as $cat ) {
		if ( 'uncategorized' === $cat->slug ) {
			continue;
		}
		$out[] = array( $cat->name, get_category_link( $cat ) );
	}
	return $out;
}

function lnt_footer_links_html( $links ) {
	echo '<ul class="lnt-footer__links">';
	foreach ( $links as $l ) {
		echo '<li><a href="' . esc_url( $l[1] ) . '">' . esc_html( $l[0] ) . '</a></li>';
	}
	echo '</ul>';
}

function lnt_footer_render() {
	$c   = lnt_footer_contact();
	$tel = preg_replace( '/\D+/', '', $c['phone'] );
	$map = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $c['address'] );
	// Ảnh nền banner: ảnh có sẵn trong Media; đổi bằng filter 'lnt_footer_banner'.
	// Bản đồ nhúng theo địa chỉ. Muốn dùng map cũ: filter 'lnt_footer_map_src' trả về src của iframe Google Maps.
	$map_src = apply_filters( 'lnt_footer_map_src', 'https://maps.google.com/maps?q=' . rawurlencode( $c['address'] ) . '&hl=vi&z=16&output=embed' );
	$fb_src  = 'https://www.facebook.com/plugins/page.php?href=' . rawurlencode( $c['facebook'] ) . '&tabs&width=340&height=150&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true';
	$bg  = apply_filters( 'lnt_footer_banner', content_url( '/uploads/2025/10/van-phong-cong-ty-luat-nhan-tam.jpg' ) );
	?>
	<section class="lnt-cta" style="--lnt-cta-bg: url('<?php echo esc_url( $bg ); ?>');">
		<div class="lnt-cta__inner">
			<h2 class="lnt-cta__title"><?php echo esc_html( $c['heading'] ); ?></h2>
			<div class="lnt-cta__btns">
				<a href="#lnt-lead-popup" class="lnt-btn" data-lnt-open>Đặt câu hỏi trực tuyến</a>
				<a href="tel:<?php echo esc_attr( $tel ); ?>" class="lnt-btn">Gọi hotline <?php echo esc_html( $c['phone'] ); ?></a>
			</div>
		</div>
	</section>

	<section class="lnt-footer">
		<div class="lnt-footer__inner">
			<div class="lnt-footer__col">
				<h3 class="lnt-footer__title"><?php echo esc_html( $c['office'] ); ?></h3>
				<ul class="lnt-footer__info">
					<li><i class="icon-map-pin-fill"></i><span><?php echo esc_html( $c['address'] ); ?></span></li>
					<li><i class="icon-phone"></i><a class="is-gold" href="tel:<?php echo esc_attr( $tel ); ?>"><?php echo esc_html( $c['phone'] ); ?></a></li>
					<li><i class="icon-envelop"></i><a href="mailto:<?php echo esc_attr( $c['email'] ); ?>"><?php echo esc_html( $c['email'] ); ?></a></li>
				</ul>
				<a class="lnt-footer__map" href="<?php echo esc_url( $map ); ?>" target="_blank" rel="noopener">Google map &gt;&gt;</a>
			</div>
			<?php
			$menu = lnt_footer_menu_links();
			if ( $menu ) :
				?>
				<div class="lnt-footer__col">
					<h3 class="lnt-footer__title">Liên kết</h3>
					<?php lnt_footer_links_html( $menu ); ?>
				</div>
			<?php endif; ?>
			<?php
			$cats = lnt_footer_category_links();
			if ( $cats ) :
				?>
				<div class="lnt-footer__col">
					<h3 class="lnt-footer__title">Chuyên mục</h3>
					<?php lnt_footer_links_html( $cats ); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="lnt-footer__embeds">
			<div class="lnt-embed lnt-embed--map">
				<iframe title="Bản đồ <?php echo esc_attr( $c['company'] ); ?>" src="<?php echo esc_url( $map_src ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
			</div>
			<div class="lnt-embed lnt-embed--fb">
				<iframe title="Fanpage Facebook" src="<?php echo esc_url( $fb_src ); ?>" loading="lazy" scrolling="no" allow="encrypted-media"></iframe>
			</div>
		</div>
		<div class="lnt-footer__bar">
			<span class="lnt-footer__line"></span>
			<span class="lnt-footer__icons">
				<a href="<?php echo esc_url( $c['facebook'] ); ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="icon-facebook"></i></a>
				<a href="tel:<?php echo esc_attr( $tel ); ?>" aria-label="Điện thoại"><i class="icon-phone"></i></a>
				<a href="mailto:<?php echo esc_attr( $c['email'] ); ?>" aria-label="Email"><i class="icon-envelop"></i></a>
				<a href="<?php echo esc_url( $map ); ?>" target="_blank" rel="noopener" aria-label="Bản đồ"><i class="icon-map-pin-fill"></i></a>
			</span>
		</div>
	</section>
	<?php
}

/** Thay hàm footer mặc định của Flatsome bằng footer này. */
function lnt_footer_replace() {
	remove_action( 'flatsome_footer', 'flatsome_page_footer', 10 );
	add_action( 'flatsome_footer', 'lnt_footer_output', 10 );
}
add_action( 'wp', 'lnt_footer_replace' );

function lnt_footer_output() {
	if ( is_page() && 'disabled' === get_post_meta( get_the_ID(), '_footer', true ) ) {
		return;
	}
	lnt_footer_render();
	get_template_part( 'template-parts/footer/footer-absolute' ); // dòng copyright
}

add_action( 'wp_enqueue_scripts', function () {
	$path = get_stylesheet_directory() . '/assets/footer-main.css';
	wp_enqueue_style( 'lnt-footer-main', get_stylesheet_directory_uri() . '/assets/footer-main.css', array(), file_exists( $path ) ? filemtime( $path ) : null );
}, 120 );
