<?php
/**
 * Template: [wcs_header_contact type="phone|email" label="" value="" link=""]
 * Khối thông tin liên hệ đơn lẻ (Hotline hoặc Email) trên Header
 *
 * @package webchiase
 * @var array $args
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$type  = ! empty( $args['type'] ) ? sanitize_key( $args['type'] ) : 'phone';
$label = isset( $args['label'] ) ? (string) $args['label'] : ( 'email' === $type || 'mail' === $type ? __( 'Email liên hệ', 'webchiase' ) : __( 'Hotline hỗ trợ', 'webchiase' ) );
$value = isset( $args['value'] ) ? (string) $args['value'] : ( 'email' === $type || 'mail' === $type ? 'luatnhantam.vn@gmail.com' : '07777.333.55' );
$link  = isset( $args['link'] ) ? trim( (string) $args['link'] ) : '';

$icon = ( 'email' === $type || 'mail' === $type ) ? 'mail' : 'phone';
if ( '' === $link ) {
	if ( 'mail' === $icon ) {
		$link = 'mailto:' . sanitize_email( $value );
	} else {
		$link = 'tel:' . preg_replace( '/[^0-9+]/', '', $value );
	}
}
?>
<a href="<?php echo esc_url( $link ); ?>" class="wcs-header-contact wcs-header-contact--<?php echo esc_attr( $type ); ?>">
	<span class="wcs-header-contact__icon">
		<?php echo wcs_kses_icon( wcs_get_icon_html( $icon ) ); ?>
	</span>
	<span class="wcs-header-contact__info">
		<span class="wcs-header-contact__label"><?php echo esc_html( $label ); ?></span>
		<strong class="wcs-header-contact__value"><?php echo esc_html( $value ); ?></strong>
	</span>
</a>
