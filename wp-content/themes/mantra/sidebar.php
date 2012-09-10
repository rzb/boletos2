<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package Cryout Creations
 * @subpackage mantra
 * @since mantra 0.5
 */

/* This  retrieves  admin options. */
$mantra_options= mantra_get_theme_options();
foreach ($mantra_options as $key => $value) {
     ${"$key"} = esc_attr($value) ;
}

if (is_page_template() && !is_page_template('template-blog.php') && !is_page_template('template-onecolumn.php') && !is_page_template('template-page-with-intro.php') ) {
?>
	<div id="primary" class="widget-area" role="complementary">

			<ul class="xoxo">

<?php
	/* When we call the dynamic_sidebar() function, it'll spit out
	 * the widgets for that widget area. If it instead returns false,
	 * then the sidebar simply doesn't exist, so we'll hard-code in
	 * some default sidebar stuff just in case.
	 */
	if ( ! dynamic_sidebar( 'primary-widget-area' )  &&  ! dynamic_sidebar( 'secondary-widget-area' )) : ?>

			<li id="search" class="widget-container widget_search">
				<?php get_search_form(); ?>
			</li>

			<li id="archives" class="widget-container">
				<h3 class="widget-title"><?php _e( 'Archives', 'mantra' ); ?></h3>
				<ul>
					<?php wp_get_archives( 'type=monthly' ); ?>
				</ul>
			</li>

			<li id="meta" class="widget-container">
				<h3 class="widget-title"><?php _e( 'Meta', 'mantra' ); ?></h3>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
			</li>

		<?php endif; // end primary widget area ?>
			</ul>

			<ul class="xoxo">
				<?php dynamic_sidebar( 'secondary-widget-area' ); ?>
			</ul>
		</div><!-- #primary .widget-area -->
		
		<?php 
		if (is_page_template("template-threecolumns-right.php") || is_page_template("template-threecolumns-left.php") || is_page_template("template-threecolumns-center.php")) { ?>

		<div id="secondary" class="widget-area" role="complementary" >
			<ul class="xoxo">
				<?php dynamic_sidebar( 'third-widget-area' ); ?>
			</ul>
			<ul class="xoxo">
				<?php dynamic_sidebar( 'fourth-widget-area' ); ?>
			</ul>
		</div><!-- #secondary .widget-area -->

<?php } 
		
		}
else 
if ($mantra_side != "1c") { ?>
		<div id="primary" class="widget-area" role="complementary">

			<ul class="xoxo">

<?php
	/* When we call the dynamic_sidebar() function, it'll spit out
	 * the widgets for that widget area. If it instead returns false,
	 * then the sidebar simply doesn't exist, so we'll hard-code in
	 * some default sidebar stuff just in case.
	 */
	if ( ! dynamic_sidebar( 'primary-widget-area' )  &&  ! dynamic_sidebar( 'secondary-widget-area' )) : ?>

			<li id="search" class="widget-container widget_search">
				<?php get_search_form(); ?>
			</li>

			<li id="archives" class="widget-container">
				<h3 class="widget-title"><?php _e( 'Archives', 'mantra' ); ?></h3>
				<ul>
					<?php wp_get_archives( 'type=monthly' ); ?>
				</ul>
			</li>

			<li id="meta" class="widget-container">
				<h3 class="widget-title"><?php _e( 'Meta', 'mantra' ); ?></h3>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
			</li>

		<?php endif; // end primary widget area ?>
			</ul>

			<ul class="xoxo">
				<?php dynamic_sidebar( 'secondary-widget-area' ); ?>
			</ul>
		</div><!-- #primary .widget-area -->

<?php
	// A second sidebar for widgets, just because.
	if ( is_active_sidebar( 'third-widget-area' ) || is_active_sidebar( 'fourth-widget-area' )) {
	if ( $mantra_side != "2cSr" &&  $mantra_side != "2cSl") { ?>
		<div id="secondary" class="widget-area" role="complementary" >
			<ul class="xoxo">
				<?php dynamic_sidebar( 'third-widget-area' ); ?>
			</ul>
			<ul class="xoxo">
				<?php dynamic_sidebar( 'fourth-widget-area' ); ?>
			</ul>
		</div><!-- #secondary .widget-area -->
<?php }
} }?> <!-- 1c -->
