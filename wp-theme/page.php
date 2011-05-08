<?php
/**
 * @package WordPress
 * @subpackage Pim
 */

get_header(); ?>

	<div id="page">
		
		
		
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?php 
		$args = array('child_of' => $post->ID);
		?>
		<ul>
		<?php wp_list_pages($args); ?>
		
		<?php
			$defaults = array( 
			    'post_parent' => $post->ID,
			    'post_type'   => 'page', 
			    'numberposts' => -1,
			    'post_status' => 'any'
			);
			$child_pages = get_children($defaults);
			
			foreach ($child_pages as $page) {
				echo get_post_meta($page->ID, 'page-excerpt', true);
			}
			
		?>
		</ul>
		
		<div class="post" id="post-<?php $post->ID; ?>">
		<h2><?php the_title(); ?></h2>
			<div class="entry">
				<?php the_content('<p class="serif">' . __('Read the rest of this page &raquo;', 'kubrick') . '</p>'); ?>

				
			</div>
		</div>
		<?php endwhile; endif; ?>
	</div>

<?php get_footer(); ?>
