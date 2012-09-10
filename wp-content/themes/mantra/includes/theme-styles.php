<?php 
/*
 * Styles and scripts registration and enqueuing 
 *
 * @package mantra
 * @subpackage Functions
 */
 
// Adding the viewport meta if the mobile view has been enabled
if($mantra_mobile=="Enable") add_action('wp_head', 'mantra_mobile_meta');

function mantra_mobile_meta() {
global $mantra_options;
foreach ($mantra_options as $key => $value) {
    							 ${"$key"} = $value ;
									}
 echo '<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">';
}


// Loading mantra css style

function mantra_style() {
global $mantra_options;
foreach ($mantra_options as $key => $value) {
    							 ${"$key"} = $value ;
									}
// Loading the style.css
	wp_register_style( 'mantras', get_stylesheet_uri() );
	wp_enqueue_style( 'mantras');
// Loading the style-mobile.css if the mobile view is enabled

}


// Loading google font styles
function mantra_google_styles() {
global $mantra_options;
foreach ($mantra_options as $key => $value) {
    							 ${"$key"} = $value ;
									}
	wp_register_style( 'mantra_googlefont', esc_attr($mantra_googlefont2 ));
	wp_register_style( 'mantra_googlefonttitle', esc_attr($mantra_googlefonttitle2 ));
	wp_register_style( 'mantra_googlefontside',esc_attr($mantra_googlefontside2) );
	wp_register_style( 'mantra_googlefontsubheader', esc_attr($mantra_googlefontsubheader2) );
	wp_enqueue_style( 'mantra_googlefont');
	wp_enqueue_style( 'mantra_googlefonttitle');
	wp_enqueue_style( 'mantra_googlefontside');
	wp_enqueue_style( 'mantra_googlefontsubheader');
	if($mantra_mobile=="Enable") {	wp_register_style( 'mantra-mobile', get_template_directory_uri() . '/style-mobile.css' );
	wp_enqueue_style( 'mantra-mobile');}

}

// CSS loading and hook into wp_enque_scripts

		add_action('wp_print_styles', 'mantra_style',1 );
		add_action('wp_head', 'mantra_custom_styles' ,8);
if($mantra_customcss!="/* Mantra Custom CSS */")		add_action('wp_head', 'mantra_customcss',9);
		add_action('wp_head', 'mantra_google_styles');
		
// JS loading and hook into wp_enque_scripts

	add_action('wp_head', 'mantra_customjs' );
		



// Scripts loading and hook into wp_enque_scripts

function mantra_scripts_method() {
global $mantra_options;
foreach ($mantra_options as $key => $value) {
    							 ${"$key"} = $value ;
									}

// If frontend - load the js for the menu and the social icons animations
	if ( !is_admin() ) {
		wp_register_script('cryout-frontend',get_template_directory_uri() . '/js/frontend.js', array('jquery') );
		wp_enqueue_script('cryout-frontend');
  		// If mantra from page is enabled and the current page is home page - load the nivo slider js							
		if($mantra_frontpage =="Enable" && is_home()) {
							wp_register_script('cryout-nivoSlider',get_template_directory_uri() . '/js/nivo-slider.js', array('jquery'));
							wp_enqueue_script('cryout-nivoSlider');
							}
  	}
	

	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
}

add_action('wp_enqueue_scripts', 'mantra_scripts_method');

/**
 *  Adding CSS3 PIE behavior to elements that need it
 */
function mantra_ie_pie() {
   echo '
<!--[if lte IE 8]>
<style type="text/css" media="screen">
 #access ul  li,
article.sticky , .imageTwo, .imageThree, .imageFour, .imageSix, .imageSeven, .edit-link a ,
.widget-title, #footer-widget-area .widget-title, .entry-meta,.entry-meta .comments-link,
.short-button-light, .short-button-dark ,.short-button-color ,blockquote  {
     position:relative;
     behavior: url('.get_stylesheet_directory_uri().'/js/PIE/PIE.php);
   }
   
input[type="text"],textarea ,#site-title a ,#site-description, #access  ul  li.current_page_item,  #access ul li.current-menu-item ,
#access ul  li ,#access ul ul ,#access ul ul li,  #content .wp-caption,.commentlist li.comment	,.commentlist .avatar,
#respond .form-submit input#submit, .contentsearch #searchsubmit , .widget_search #s, #search #s  ,  .widget_search #searchsubmit ,
.nivo-caption, .theme-default .nivoSlider {
     behavior: url('.get_stylesheet_directory_uri().'/js/PIE/PIE.php);
   }
</style>
<![endif]-->
';
}
add_action('wp_head', 'mantra_ie_pie', 10);

?>
