<?php
/**
 * @package WordPress
 * @subpackage Pim
 */

get_header(); 

?>

	<!-- Content -->
	<div id="content" class="clearfix">
			
		<div id="articles">
			<h1>Je zocht op: <?php the_search_query(); ?> </h1>
			<ol>
			<?php
			if(have_posts()) : while(have_posts()) : the_post();
				
				$page_excerpt = get_post_meta($post->ID, 'page-excerpt', true);
				?>
				
				<li class="clearfix excerpt">
					<?php
					if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
						the_post_thumbnail('thumbnail');
						echo '<div class="thumb-mask"></div>';
					} ?>
						
					<h2><?php the_title(); ?></h2>
						
					<p><?php echo $page_excerpt; ?> <a href="<?php the_permalink(); ?>">Lees meer</a></p>
				</li>
				
			<?php endwhile; endif; // end main loop ?>
				
			</ol>
		</div>
		
<?php get_footer(); ?>
