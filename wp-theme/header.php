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
		
		<link href="<?php bloginfo('stylesheet_url'); ?>" media="screen" rel="stylesheet" type="text/css" />
		<link href="wp-content/themes/pim/wp-style.css" media="screen" rel="stylesheet" type="text/css" />
		
		<!--[if lt IE 9]>
		<script type="text/javascript" src="/static/global/IE9.js"></script>
		<![endif]-->
		
		<script type="text/javascript" src="http://use.typekit.com/wge3fla.js"></script>
		<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
		
		<!--<base href="http://82.94.216.83/dataview/" />-->
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?> 

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

        <div id="top-nav-container">
		<div id="top-nav"><a href="/">PIM</a> is een project van <a href="http://www.bof.nl">Bits of Freedom</a></div>
        </div>
	<div id="content" class="clearfix">
		<a href="/"><img src="wp-content/themes/pim/images/pim_logo.png" alt="PIM logo" id="pim-logo"/></a>
		<a href="http://www.bof.nl"><img src="wp-content/themes/pim/images/pim_bof.png" alt="BOF logo" id="bof-logo"/></a>

		<ul class="flat-list" id="main-nav">
		<?php wp_list_pages(Array('title_li' => '')); ?>
		</ul>

