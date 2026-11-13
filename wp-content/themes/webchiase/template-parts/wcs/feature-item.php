<?php
/**
 * Template: [wcs_feature_item icon="" title="" desc=""]
 *
 * @package webchiase
 * @var array $args
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$icon  = ! empty( $args['icon'] ) ? (string) $args['icon'] : 'check-doc';
$title = isset( $args['title'] ) ? (string) $args['title'] : '';
$desc  = isset( $args['desc'] ) ? (string) $args['desc'] : '';
?>
<div class="wcs-feature-item">
	<span class="wcs-feature-item__icon">
		<?php echo wcs_kses_icon( wcs_get_icon_html( $icon ) ); ?>
	</span>
	<div class="wcs-feature-item__content">
		<h4 class="wcs-feature-item__title"><?php echo esc_html( $title ); ?></h4>
		<?php if ( '' !== $desc ) : ?>
			<p class="wcs-feature-item__desc"><?php echo esc_html( $desc ); ?></p>
		<?php endif; ?>
	</div>
</div>
