<?php get_header(); ?>

		<section id="primary">
			<div id="content" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="entry-title">Upcoming Assignments</h1>
				</header>

				<?php twentyeleven_content_nav( 'nav-above' ); ?>
				
				<div class="entry-content">
					<table>
					<tbody>
					<tr>
						<th>Assignment</th>
						<th>Percentage</th>
						<th>Date Due</th>
					</tr>				

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
				
					<?php $date_due = get_post_meta($post->ID,"date_due",true); ?>
					<?php $timezone = 21600; // // this number changes the due date's timezone ?>
					<?php if (($date_due+86400)+$timezone > time()) { ?>
					<tr>
						<td><?php the_title(); ?></td>
						<td><?php echo get_post_meta($post->ID,"percentage",true); ?></td>
						<td><?php echo $date_due; ?></td>
					</tr>
					<?php } else { $past[] = $post; } ?>

				<?php endwhile; ?>
				
					</tbody>
					</table>

				<?php if ($past) {?>
				
					<h1 class="entry-title">Past Assignments</h1>
					<table>
					<tbody>
					<tr>
						<th>Assignment</th>
						<th>Percentage</th>
						<th>Date Due</th>
					</tr>	
									
				<?php foreach ($past as $post) { setup_postdata($post); ?>
				
					<tr>
						<td><?php the_title(); ?></td>
						<td><?php echo get_post_meta($post->ID,"percentage",true); ?></td>
						<td><?php echo date('m/d/Y', get_post_meta($post->ID,"date_due",true)); ?></td>
					</tr>				
				
				<?php } ?>
				<?php } ?>
				
					</tbody>
					</table>				

				</div>

				<?php twentyeleven_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'twentyeleven' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyeleven' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

			</div><!-- #content -->
		</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>