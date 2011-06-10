<?php
/**
 * @package WordPress
 * @subpackage Pim
 */

get_header(); 

?>

	<!-- Content -->
	<div id="content" class="clearfix">
		
		<?php if ( ! is_subpage() ) { // if this page is a main page ?>
			
			<?php
			if(have_posts()) : while(have_posts()) : the_post();
				
				$perma_link = get_permalink();
				$title = get_the_title();
				
				$args = array('posts_per_page' => -1,
							'post_parent' => $post->ID,
							'post_type' => 'page');
							
				$child_pages = new WP_Query($args); 
				
				echo '<div id="articles">';
				echo '<h1>' . get_the_title('') . '</h1>';
				echo '<ol>';
				
				// List all child pages
				if($child_pages->have_posts()) : while($child_pages->have_posts()) : $child_pages->the_post();
				
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
				
				<?php endwhile; endif; // end child pages ?>
				
					</ol>
				</div>
				
				
				<?php // Show sub nav ?>
				<ol id="page-sub-nav">
					
					<li class="selected uneven">
						<a href="<?php echo $perma_link; ?>"><?php echo $title; ?></a>
					</li>
					
					<?php
					$parity = 'even';
					if($child_pages->have_posts()) : while($child_pages->have_posts()) : $child_pages->the_post(); ?>
						
						<li class="<?php echo $parity ?>">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</li>
					
					<?php
					$parity = $parity == 'uneven' ? 'even' : 'uneven';
					endwhile; endif; ?>
					
				</ol>
				<?php // end sub nav ?>
				
			<?php endwhile; endif; // end main loop
			
		} else { // if this page is sub page 
		
			if(have_posts()) : while(have_posts()) : the_post();
				
				$parent_title = get_the_title($post->post_parent);
				$parent_link = get_permalink($post->post_parent);
				$current_title = get_the_title();
				
				echo '<div id="article">';
				if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
					the_post_thumbnail();
				}
				echo '<h1>' . get_the_title('') . '</h1>';
				the_content();
				echo '</div>';
				
				// Show sub nav ?>
				<ol id="page-sub-nav">
					
					<li class="uneven">
						<a href="<?php echo $parent_link; ?>"><?php echo $parent_title; ?></a>
					</li>
					
					<?php
					$args = array('posts_per_page' => -1,
							'post_parent' => $post->post_parent,
							'post_type' => 'page');
							
					$child_pages = new WP_Query($args); 
				
					$parity = 'even';
					if($child_pages->have_posts()) : while($child_pages->have_posts()) : $child_pages->the_post();
						// Select the current sub nav item
						$selected = (get_the_title() == $current_title) ? 'selected' : '';
					?>
						
						<li class="<?php echo $parity; echo ' '.$selected; ?>">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</li>
					
					<?php
					$parity = $parity == 'uneven' ? 'even' : 'uneven';
					endwhile; endif; ?>
					
				</ol>
				<?php // end sub nav ?>
				
			<?php endwhile; endif; // end main loop
			
		}
		?>
		
<?php get_footer(); ?>
