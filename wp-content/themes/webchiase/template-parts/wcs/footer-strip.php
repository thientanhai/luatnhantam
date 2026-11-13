<?php
/**
 * Template: Footer Strip nền Navy (4 feature item + slogan + logo)
 *
 * @package webchiase
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="wcs-footer-strip">
	<div class="row row-small align-middle">
		<div class="col large-9 medium-12 small-12 pb-0">
			<div class="wcs-footer-strip__features">
				<?php
				echo do_shortcode( '[wcs_feature_item icon="check-doc" title="Tư vấn pháp lý chính xác" desc="Căn cứ quy định pháp luật hiện hành"]' );
				echo do_shortcode( '[wcs_feature_item icon="clock-sync" title="Phản hồi nhanh chóng" desc="Giải đáp trong thời gian sớm nhất"]' );
				echo do_shortcode( '[wcs_feature_item icon="users-expert" title="Đội ngũ luật sư" desc="Giàu kinh nghiệm thực tiễn"]' );
				echo do_shortcode( '[wcs_feature_item icon="shield-heart" title="Bảo mật thông tin" desc="Cam kết an toàn tuyệt đối"]' );
				?>
			</div>
		</div>
		<div class="col large-3 medium-12 small-12 pb-0">
			<div class="wcs-footer-strip__brand">
				<span class="wcs-footer-strip__slogan">
					<?php echo esc_html__( 'Đồng hành pháp lý cùng bạn!', 'webchiase' ); ?>
				</span>
			</div>
		</div>
	</div>
</section>
