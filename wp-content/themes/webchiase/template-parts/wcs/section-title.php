<?php
/**
 * Template: [wcs_section_title icon="" title="" link=""]
 *
 * @package webchiase
 * @var array $args
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$icon      = isset( $args['icon'] ) ? (string) $args['icon'] : 'law';
$title     = isset( $args['title'] ) ? (string) $args['title'] : '';
$link      = isset( $args['link'] ) ? (string) $args['link'] : '';
$link_text = ! empty( $args['link_text'] ) ? (string) $args['link_text'] : __( 'Xem tất cả', 'webchiase' );

if ( '' === $title ) {
	return;
}
?>
<div class="wcs-section-title">
	<div class="wcs-section-title__left">
		<?php if ( '' !== $icon ) : ?>
			<span class="wcs-section-title__icon">
				<?php echo wcs_kses_icon( wcs_get_icon_html( $icon ) ); ?>
			</span>
		<?php endif; ?>
		<h2 class="wcs-section-title__heading"><?php echo esc_html( $title ); ?></h2>
	</div>
	<?php if ( '' !== $link ) : ?>
		<a href="<?php echo esc_url( $link ); ?>" class="wcs-section-title__more">
			<span><?php echo esc_html( $link_text ); ?></span>
			<span class="wcs-section-title__arrow" aria-hidden="true">&rarr;</span>
		</a>
	<?php endif; ?>
</div>
