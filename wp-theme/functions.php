<?php

add_filter('get_pages', 'custom_get_page', 10, 1);

function custom_get_page($pages) {
	/*
	 * XXX: this is a dirty hack. However, I think that is in style with
	 * the rest of Wordpress.
	 *
	 * With the right Wordpress URL configuration this will create a menu
	 * item pointing to /start/. Which is what we want.
	 *
	 * Add a "start" page which points to Django as the second list item.
	 */

	$r_pages = Array();
	$i = 0;
	foreach ($pages as $p) {
		// If is the second item in the list create both a start page
		// and copy the third item.
		if ($i == 1) {
			$p2 = new stdClass;
			$p2->post_title = 'start';
			$p2->ID = 1337;
			$r_pages[] = $p2;
		}
		$r_pages[] = $p;
	
		$i++;
	}

	return $r_pages;
}

add_filter('page_css_class', 'custom_page_css_class', 10, 2);
/**
 *  custom_page_css_class()
 *  Hooks into page_css_class and modifies the page item class so that
 *  it uses the name of the page instead of the id.
 *
 *  @global int $id.    The ID of the page being viewed. Needed to set current_page_item.
 *  @param array $class.  The page css class being modified, passed as an array from Walker_Page
 *  @param object $page.  The page object passed from Walker_Page
 *  @return array     Returns the new page css class.
 */
function custom_page_css_class($class, $page) {
  global $id;
 
  if ($page->ID == $id)
    /* checks if the page ID matches the current page and if it does,
        adds the current_page_item class
    */
    $t = array( 'lol_page_item', 'page-item-'.$page->post_name, 'current_page_item');
  else
    $t = array( 'lol_page_item', 'page-item-'.$page->post_name);
 
  return $t;
 
}

?>
