<?php 
/**
 * Misc functions breadcrumbs / pagination / transient data
 *
 * @package mantra
 * @subpackage Functions
 */
 
 /**
 * Creates breadcrumns with page sublevels and category sublevels.
 */
function mantra_breadcrumbs() {
$mantra_options= mantra_get_theme_options();
foreach ($mantra_options as $key => $value) {
     ${"$key"} = $value ;
}
global $post;
echo '<div class="breadcrumbs">';
if (is_page() && !is_front_page() || is_single() || is_category() || is_archive()) {
        echo '<a href="'.get_bloginfo('url').'">'.get_bloginfo('name').' &raquo; </a>';
 
        if (is_page()) {
            $ancestors = get_post_ancestors($post);
 
            if ($ancestors) {
                $ancestors = array_reverse($ancestors);
 
                foreach ($ancestors as $crumb) {
                    echo '<a href="'.get_permalink($crumb).'">'.get_the_title($crumb).' &raquo; </a>';
                }
            }
        }
 
        if (is_single()) {
       if(has_category())    { $category = get_the_category();
            echo '<a href="'.get_category_link($category[0]->cat_ID).'">'.$category[0]->cat_name.' &raquo; </a>';
								}
        }
 
        if (is_category()) {
            $category = get_the_category();
            echo ''.$category[0]->cat_name.'';
        }


 
        // Current page
        if (is_page() || is_single()) {
            echo ''.get_the_title().'';
        }
        echo '';
    } elseif (is_home() && $mantra_frontpage!="Enable" ) {
        // Front page
        echo '';
        echo '<a href="'.get_bloginfo('url').'">'.get_bloginfo('name').'</a> '."&raquo; ";
        _e('Home Page','mantra');
        echo '';
    }
echo '</div>';
}

/**
 * Creates pagination for blog pages.
 */
function mantra_pagination($pages = '', $range = 2, $prefix ='')
{  
     $showitems = ($range * 2)+1;  

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if(1 != $pages)
     {
		echo "<nav class='pagination'>";
         if ($prefix) {echo "<span id='paginationPrefix'>$prefix </span>";}
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";  
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
         echo "</nav>\n";
     }
}

/**
 * Show the social icons in case they are enabled.
 */
function mantra_set_social_icons() {
	global $mantra_options;
		foreach ($mantra_options as $key => $value) {
		${"$key"} = $value ;
					}
					
for ($i=1; $i<=9; $i+=2) {
	$j=$i+1;
	if ( ${"mantra_social$j"} ) {?>
		<a target="_blank" rel="nofollow" href="<?php echo esc_url(${"mantra_social$j"}); ?>" class="socialicons social-<?php echo esc_attr(${"mantra_social$i"}); ?>" title="<?php echo esc_attr(${"mantra_social$i"}); ?>"><img alt="<?php echo esc_attr(${"mantra_social$i"}); ?>" src="<?php echo get_template_directory_uri().'/images/socials/'.${"mantra_social$i"}.'.png'; ?>" /></a><?php 
				} 
		}
}

// Get any existing copy of our transient data
if ( false === ( $cryout_theme_info = get_transient( 'cryout_theme_info' ) ) ) {
    // It wasn't there, so regenerate the data and save the transient
 if ( ! function_exists( 'get_custom_header' ) ) {  $cryout_theme_info = get_theme_data( get_theme_root() . '/mantra/style.css' ); }
else { $cryout_theme_info = wp_get_theme( );}

     set_transient( 'cryout_theme_info',  $cryout_theme_info ,60*60);
}

add_action('wp_ajax_nopriv_do_ajax', 'mantrra_ajax_function');
add_action('wp_ajax_do_ajax', 'mantra_ajax_function');

function mantra_ajax_function(){
ob_clean();
 
   // the first part is a SWTICHBOARD that fires specific functions
   // according to the value of Query Var 'fn'
 
     switch($_REQUEST['fn']){
          case 'get_latest_posts':
               $output = mantra_ajax_get_latest_posts($_REQUEST['count'],$_REQUEST['categName']);
          break;
          default:
              $output = 'No function specified, check your jQuery.ajax() call';
          break;
 
     }
 
   // at this point, $output contains some sort of valuable data!
   // Now, convert $output to JSON and echo it to the browser
   // That way, we can recapture it with jQuery and run our success function
 
          $output=json_encode($output);
         if(is_array($output)){
        print_r($output);
         }
         else{
        echo $output;
         }
         die;
 
}

function mantra_ajax_get_latest_posts($count,$categName){
 $testVar='';
// The Query
query_posts( 'category_name='.$categName);
// The Loop
if ( have_posts() ) : while ( have_posts() ) : the_post();
$testVar .=the_title("<option>","</option>",0);
endwhile; else: endif;

// Reset Query
wp_reset_query();

return $testVar;
}


?>
