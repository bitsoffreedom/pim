<?php
/**
 * @package WordPress
 * @subpackage Pim
 */

/*
Template Name: Links
*/
?>

<?php get_header(); ?>

<div id="page">

<h2><?php _e('Links:', 'kubrick'); ?></h2>
<ul>
<?php wp_list_bookmarks(); ?>
</ul>

</div>

<?php get_footer(); ?>
