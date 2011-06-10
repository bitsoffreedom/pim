<?php
// This theme uses wp_nav_menu() in one location.  
register_nav_menus( array(  
    'primary' => __( 'Primary Navigation', 'pim' ),  
) );

// Add support for post thumbnails
if ( function_exists( 'add_theme_support' ) ) { 
	add_theme_support( 'post-thumbnails' ); 
} 

// Check to see if page is a subpage
function is_subpage() {
	global $post;                                 // load details about this page
	if ( is_page() && $post->post_parent ) {      // test to see if the page has a parent
		return $post->post_parent;             // return the ID of the parent post
	} else {                                      // there is no parent so...
		return false;                          // ...the answer to the question is false
	}
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
