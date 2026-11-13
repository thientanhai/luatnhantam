<?php
/**
 * Template: [wcs_header_contacts phone="" phone_label="" email="" email_label=""]
 * Khối gom cả Hotline và Email trên Header theo đúng ảnh thiết kế
 *
 * @package webchiase
 * @var array $args
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phone       = isset( $args['phone'] ) ? (string) $args['phone'] : '07777.333.55';
$phone_label = isset( $args['phone_label'] ) ? (string) $args['phone_label'] : __( 'Hotline hỗ trợ', 'webchiase' );
$email       = isset( $args['email'] ) ? (string) $args['email'] : 'luatnhantam.vn@gmail.com';
$email_label = isset( $args['email_label'] ) ? (string) $args['email_label'] : __( 'Email liên hệ', 'webchiase' );
$show_phone  = ! isset( $args['show_phone'] ) || ( '0' !== (string) $args['show_phone'] && 'false' !== (string) $args['show_phone'] );
$show_email  = ! isset( $args['show_email'] ) || ( '0' !== (string) $args['show_email'] && 'false' !== (string) $args['show_email'] );
?>
<div class="wcs-header-contacts">
	<?php if ( $show_phone && '' !== $phone ) : ?>
		<?php
		get_template_part(
			'template-parts/wcs/header',
			'contact',
			array(
				'type'  => 'phone',
				'label' => $phone_label,
				'value' => $phone,
			)
		);
		?>
	<?php endif; ?>

	<?php if ( $show_email && '' !== $email ) : ?>
		<?php
		get_template_part(
			'template-parts/wcs/header',
			'contact',
			array(
				'type'  => 'email',
				'label' => $email_label,
				'value' => $email,
			)
		);
		?>
	<?php endif; ?>
</div>
