<?php
/**
 * Template: [wcs_cta_banner title="" text="" btn_text="" btn_link="" image=""]
 *
 * @package webchiase
 * @var array $args
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$subtitle = ! empty( $args['subtitle'] ) ? (string) $args['subtitle'] : __( 'CẦN HỖ TRỢ?', 'webchiase' );
$title    = ! empty( $args['title'] ) ? (string) $args['title'] : __( 'Bạn có câu hỏi?', 'webchiase' );
$text     = ! empty( $args['text'] ) ? (string) $args['text'] : __( 'Đội ngũ chuyên gia của chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7', 'webchiase' );
$btn_text = ! empty( $args['btn_text'] ) ? (string) $args['btn_text'] : __( 'Liên hệ ngay', 'webchiase' );
$btn_link = ! empty( $args['btn_link'] ) ? (string) $args['btn_link'] : home_url( '/lien-he/' );
$image    = isset( $args['image'] ) ? trim( (string) $args['image'] ) : '';
?>
<div class="wcs-cta-banner <?php echo '' !== $image ? 'has-image' : ''; ?>">
	<div class="wcs-cta-banner__icon-mobile" aria-hidden="true">
		<?php echo wcs_kses_icon( wcs_get_icon_html( 'headset' ) ); ?>
	</div>
	<div class="wcs-cta-banner__body">
		<span class="wcs-cta-banner__subtitle"><?php echo esc_html( $subtitle ); ?></span>
		<h3 class="wcs-cta-banner__title"><?php echo esc_html( $title ); ?></h3>
		<p class="wcs-cta-banner__text"><?php echo esc_html( $text ); ?></p>
		<a href="<?php echo esc_url( $btn_link ); ?>" class="wcs-cta-banner__btn">
			<span><?php echo esc_html( $btn_text ); ?></span>
			<span class="wcs-cta-banner__btn-arrow" aria-hidden="true">&rarr;</span>
		</a>
	</div>
	<?php if ( '' !== $image ) : ?>
		<div class="wcs-cta-banner__media">
			<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="wcs-cta-banner__img" loading="lazy" decoding="async" />
		</div>
	<?php else : ?>
		<div class="wcs-cta-banner__decor" aria-hidden="true">
			<span class="wcs-cta-banner__decor-icon">
				<?php echo wcs_kses_icon( wcs_get_icon_html( 'headset' ) ); ?>
			</span>
		</div>
	<?php endif; ?>
</div>
