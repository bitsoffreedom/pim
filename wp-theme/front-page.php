<?php
/**
 * @package WordPress
 * @subpackage Pim
 */

get_header(); ?>
	
	<!-- Content -->
	<div id="content">
		<h1>Ontdek wat organisaties over jou weten</h1>
		
		<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
			
			<?php echo the_content(); ?>
			
			<a href="/start/" id="next-step" class="step-button">Start PIM</a>
			
		<?php endwhile; endif; ?>
		

<?php get_footer(); ?>
