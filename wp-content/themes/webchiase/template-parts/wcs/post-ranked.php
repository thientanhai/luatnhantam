<?php
/**
 * Template: [wcs_post_ranked count="5" orderby="views|comments"]
 *
 * @package webchiase
 * @var array $args
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$count      = isset( $args['count'] ) ? max( 1, absint( $args['count'] ) ) : 5;
$orderby    = isset( $args['orderby'] ) ? sanitize_key( $args['orderby'] ) : 'views';
$cat        = isset( $args['cat'] ) ? trim( (string) $args['cat'] ) : '';
$show_views = ! isset( $args['show_views'] ) || ( '0' !== (string) $args['show_views'] && 'false' !== (string) $args['show_views'] );

$query_args = array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => $count,
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
);

if ( '' !== $cat ) {
	if ( is_numeric( $cat ) ) {
		$query_args['cat'] = absint( $cat );
	} else {
		$query_args['category_name'] = sanitize_title( $cat );
	}
}

// Hỗ trợ Post Views Counter (meta_key post_views); chưa có thì fallback comment_count.
if ( 'views' === $orderby && wcs_has_post_views_support() ) {
	$query_args['meta_key'] = 'post_views';
	$query_args['orderby']  = array(
		'meta_value_num' => 'DESC',
		'comment_count'  => 'DESC',
		'date'           => 'DESC',
	);
} else {
	$query_args['orderby'] = array(
		'comment_count' => 'DESC',
		'date'          => 'DESC',
	);
}

$query = new WP_Query( $query_args );

// Nếu lọc theo meta_key `post_views` mà chưa đủ bài có meta, fallback sang comment_count để luôn đủ danh sách.
if ( ! $query->have_posts() && isset( $query_args['meta_key'] ) ) {
	unset( $query_args['meta_key'] );
	$query_args['orderby'] = array(
		'comment_count' => 'DESC',
		'date'          => 'DESC',
	);
	$query = new WP_Query( $query_args );
}
?>
<ol class="wcs-post-ranked">
	<?php if ( $query->have_posts() ) : ?>
		<?php
		$rank = 1;
		while ( $query->have_posts() ) :
			$query->the_post();
			$post_id     = get_the_ID();
			$views_count = wcs_get_post_views( $post_id );
			?>
			<li class="wcs-post-ranked__item">
				<span class="wcs-post-ranked__num"><?php echo esc_html( (string) $rank ); ?></span>
				<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="wcs-post-ranked__title">
					<?php echo esc_html( get_the_title( $post_id ) ); ?>
				</a>
				<?php if ( $show_views ) : ?>
					<span class="wcs-post-ranked__views show-for-small">
						<span class="wcs-post-ranked__views-icon"><?php echo wcs_kses_icon( wcs_get_icon_html( 'eye' ) ); ?></span>
						<span><?php echo esc_html( wcs_format_count( $views_count ) ); ?></span>
					</span>
				<?php endif; ?>
			</li>
			<?php
			$rank++;
		endwhile;
		wp_reset_postdata();
		?>
	<?php else : ?>
		<li class="wcs-empty"><?php echo esc_html__( 'Chưa có bài viết nào.', 'webchiase' ); ?></li>
	<?php endif; ?>
</ol>
