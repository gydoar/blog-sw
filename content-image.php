<?php
/**
 * The template to display post content
 *
 * @package Versed
 * @since Versed 1.0
 */

zilla_post_before(); ?>
<!--BEGIN .post -->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php zilla_post_start(); ?>

	<?php if (is_singular() || zilla_get_mod('blog_layout', false) !== 'blog-list-compressed'): ?>

		<?php if ( has_post_thumbnail() ): ?>
		<figure class="entry-thumbnail">
			<button type="button" class="btn-modal" data-toggle="modal" data-target="#image-modal-<?php the_ID(); ?>"><i class="fa fa-search-plus"></i></button>
			<?php zilla_post_thumbnail( get_the_ID() ); ?>
			<figcaption class="thumbnail-meta">
				<?php zilla_post_thumbnail_meta( get_post_thumbnail_id() ); ?>
			</figcaption>
		</figure>

		<div class="modal fade image-modal" id="image-modal-<?php the_ID(); ?>" tabindex="-1" role="dialog" aria-labelledby="Image" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<figure class="entry-thumbnail">
						<?php zilla_post_thumbnail( get_the_ID(), 'fullscreen' ); ?>
						<figcaption class="thumbnail-meta">
						<?php zilla_post_thumbnail_meta( get_post_thumbnail_id() ); ?>
						</figcaption>
					</figure>
				</div>
			</div>
		</div>
		<?php endif; ?>

	<?php else: ?>

		<?php if ( has_post_thumbnail() ): ?>
		<figure class="entry-thumbnail">
			<?php the_post_thumbnail( 's-thumb' ); ?>
		</figure>
		<?php endif; ?>

	<?php endif; ?>

	<?php get_template_part('content', 'post-body'); ?>

<?php zilla_post_end(); ?>
<!--END .post-->
</article>
<?php zilla_post_after(); ?>
