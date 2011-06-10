<?php
/**
 * @package WordPress
 * @subpackage Pim
 */

get_header(); 

?>

	<!-- Content -->
	<div id="content">
		
		<?php if ( ! is_subpage() ) { // if this page is a main page ?>
			
			<h1><?php wp_title(''); ?></h1>
			
			<?php
			if(have_posts()) : while(have_posts()) : the_post();
			
				$args = array('posts_per_page' => -1,
							'post_parent' => $post->ID,
							'post_type' => 'page');
							
				$child_pages = new WP_Query($args); 
				
				if($child_pages->have_posts()) : while($child_pages->have_posts()) : $child_pages->the_post();
				
				$page_excerpt = get_post_meta($post->ID, 'page-excerpt', true);
				?>
					
				<div class="clearfix excerpt">
					<?php
					if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
						the_post_thumbnail('thumbnail');
						echo '<div class="thumb-mask"></div>';
					} ?>
					
					<h2><?php the_title(); ?></h2>
						
					<p><?php echo $page_excerpt; ?> <a href="<?php the_permalink(); ?>">Lees meer</a></p>
				</div>
				
				<?php
				endwhile; endif; // end child pages
			
			endwhile; endif; // end main loop
			
		} else { // if this page is sub page ?>
			
		<?php
		}
		?>
		
		<div id="page-sub-nav">
			<ol>
				<li class="uneven selected">
					<a href="">Gebruikers</a>
				</li>
				<li>
					<a href="">Inzage is een grondrecht</a>
				</li>
				<li class="uneven">
					<a href="">Persoons-gegeven is een ruim begrip</a>
				</li>
				<li>
					<a href="">Waarom weten wat ze weten?</a>
				</li>
			</ol>
		</div>

<?php get_footer(); ?>
