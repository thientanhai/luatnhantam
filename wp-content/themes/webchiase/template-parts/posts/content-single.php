<?php
/**
 * Posts content single (Nội dung chi tiết bài viết, Tags, Share, Author box, Điều hướng bài).
 *
 * @package          webchiase
 * @flatsome-version 3.20.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="entry-content single-page wcs-single__content">
	<?php the_content(); ?>

	<?php
	wp_link_pages(
		array(
			'before' => '<div class="page-links">' . esc_html__( 'Trang:', 'webchiase' ),
			'after'  => '</div>',
		)
	);
	?>
</div>

<?php
$tags = get_the_tags();
?>
<footer class="wcs-single__footer">
	<?php if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) : ?>
		<div class="wcs-single__tags">
			<span class="wcs-single__tags-label"><?php echo esc_html__( 'Từ khóa:', 'webchiase' ); ?></span>
			<?php foreach ( $tags as $tag ) : ?>
				<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="wcs-single__tag-pill">
					#<?php echo esc_html( $tag->name ); ?>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( get_theme_mod( 'blog_share', 1 ) ) : ?>
		<div class="wcs-single__share">
			<span class="wcs-single__share-label"><?php echo esc_html__( 'Chia sẻ:', 'webchiase' ); ?></span>
			<?php
			echo flatsome_apply_shortcode(
				'share',
				array(
					'style' => 'small',
				)
			);
			?>
		</div>
	<?php endif; ?>
</footer>

<?php if ( get_theme_mod( 'blog_author_box', 1 ) && get_the_author_meta( 'description' ) ) : ?>
	<div class="wcs-author-box">
		<div class="wcs-author-box__avatar">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 72 ); ?>
		</div>
		<div class="wcs-author-box__info">
			<h4 class="wcs-author-box__name"><?php the_author_meta( 'display_name' ); ?></h4>
			<p class="wcs-author-box__bio"><?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?></p>
		</div>
	</div>
<?php endif; ?>

<?php
$prev_post = get_previous_post();
$next_post = get_next_post();
if ( $prev_post || $next_post ) :
	?>
	<nav class="wcs-post-nav" aria-label="<?php echo esc_attr__( 'Điều hướng bài viết', 'webchiase' ); ?>">
		<div class="wcs-post-nav__col">
			<?php if ( $prev_post ) : ?>
				<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="wcs-post-nav__link">
					<span class="wcs-post-nav__label">&larr; <?php echo esc_html__( 'Bài trước', 'webchiase' ); ?></span>
					<span class="wcs-post-nav__title"><?php echo esc_html( get_the_title( $prev_post->ID ) ); ?></span>
				</a>
			<?php endif; ?>
		</div>
		<div class="wcs-post-nav__col is-next">
			<?php if ( $next_post ) : ?>
				<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="wcs-post-nav__link">
					<span class="wcs-post-nav__label"><?php echo esc_html__( 'Bài tiếp theo', 'webchiase' ); ?> &rarr;</span>
					<span class="wcs-post-nav__title"><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></span>
				</a>
			<?php endif; ?>
		</div>
	</nav>
<?php endif; ?>
