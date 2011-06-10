<?php
/**
 * @package WordPress
 * @subpackage Pim
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /> -->
		<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
		
		<link href="<?php bloginfo('template_url'); ?>/style.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="<?php bloginfo('template_url'); ?>/wp-style.css" media="screen" rel="stylesheet" type="text/css" />
		
		<!--[if lt IE 9]>
		<script type="text/javascript" src="/static/global/IE9.js"></script>
		<![endif]-->
		
		<script type="text/javascript" src="http://use.typekit.com/wge3fla.js"></script>
		<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
		
		<!--<base href="http://82.94.216.83/dataview/" />-->

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	
	<!-- Wrapper -->
	<div id="wrapper" class="clearfix">
		<a href="/"><img src="<?php bloginfo("template_url"); ?>/images/pim_logo.png" alt="PIM logo" id="pim-logo"/></a>
		<a href="http://www.bof.nl"><img src="<?php bloginfo("template_url"); ?>/images/pim_bof.png" alt="BOF logo" id="bof-logo"/></a>

		<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'menu_class' => 'flat-list clearfix main-nav', 'theme_location' => 'primary' ) ); ?>

		<form action="<?php echo site_url(); ?>" method="get" id="search">
			<fieldset>
				<input type="text" name="s" value="<?php is_search() ? the_search_query() : ''; ?>" />
				<input type="submit" value="zoek" id="searchsubmit" />
			</fieldset>
		</form>