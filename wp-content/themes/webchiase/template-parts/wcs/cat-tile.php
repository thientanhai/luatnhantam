<?php
/**
 * Template: [wcs_cat_tile icon="" title="" desc="" link="" active=""]
 *
 * @package webchiase
 * @var array $args
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$icon      = isset( $args['icon'] ) ? (string) $args['icon'] : 'law';
$title     = isset( $args['title'] ) ? (string) $args['title'] : '';
$desc      = isset( $args['desc'] ) ? (string) $args['desc'] : '';
$link      = ! empty( $args['link'] ) ? (string) $args['link'] : '#';
$is_active = ! empty( $args['active'] ) && '0' !== (string) $args['active'] && 'false' !== (string) $args['active'];

$tile_classes = 'wcs-cat-tile' . ( $is_active ? ' is-active' : '' );
?>
<a href="<?php echo esc_url( $link ); ?>" class="<?php echo esc_attr( $tile_classes ); ?>">
	<span class="wcs-cat-tile__icon">
		<?php echo wcs_kses_icon( wcs_get_icon_html( $icon ) ); ?>
	</span>
	<span class="wcs-cat-tile__title"><?php echo esc_html( $title ); ?></span>
	<?php if ( '' !== $desc ) : ?>
		<span class="wcs-cat-tile__desc"><?php echo esc_html( $desc ); ?></span>
	<?php endif; ?>
</a>
