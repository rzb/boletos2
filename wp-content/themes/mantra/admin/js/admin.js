/*!
 * Admin js
 */

jQuery(document).ready(function() {

// WP image uploader for custom use in the admin section
		var uploadparent = 0;
		var old_send_to_editor = window.send_to_editor;
		var old_tb_remove = window.tb_remove;
		
		jQuery('.upload_image_button').click(function(){
			uploadparent = jQuery(this).closest('div');
			tb_show('', 'media-upload.php?post_id=0&amp;type=file&amp;TB_iframe=true');
			return false;
		});
		
		window.tb_remove = function() {
			uploadparent = 0;
			old_tb_remove();
		}
		
		window.send_to_editor = function(html) {
			if(uploadparent){              
				imgurl = jQuery('img',html).attr('src');
				uploadparent.find('.slideimages').attr('value', imgurl);
				uploadparent.find('.imagebox').attr('src', imgurl);
				tb_remove();
			} else {
				old_send_to_editor();
			}
			}

// Show/hide slides		
		jQuery('.slidetitle').click(function() {
				jQuery(this).next().toggle("fast");
				});


// Jquery confim window on reset to defaults
jQuery('#mantra_defaults').click (function() {
		if (!confirm('Reset Mantra Settings to Defaults?')) { return false;}
	});
 
// Hide or show dimmensions
jQuery('#mantra_dimselect').change(function() {
	if	(jQuery('#mantra_dimselect option:selected').val()=="Absolute") {
				jQuery('#relativedim').hide("normal");jQuery('#absolutedim').show("normal");
				}
	else {	
				jQuery('#relativedim').show("normal");jQuery('#absolutedim').hide("normal");
				}
	});
	
if (jQuery('#mantra_dimselect option:selected').val()=="Absolute") {
		jQuery('#relativedim').hide("normal");jQuery('#absolutedim').show("normal");}
else {
		jQuery('#relativedim').show("normal");jQuery('#absolutedim').hide("normal");
		}


// Hide or show slider settings
jQuery('#mantra_slideType').change(function() {
	jQuery('.slideDivs').hide("normal");
	switch (jQuery('#mantra_slideType option:selected').val()) {

		case "Custom Slides" :
 		jQuery('#sliderCustomSlides').show("normal");
		break;

		case "Latest Posts" :
 		jQuery('#sliderLatestPosts').show("normal");
		break;

		case "Random Posts" :
 		jQuery('#sliderRandomPosts').show("normal");
		break;

		case "Sticky Posts" :
 		jQuery('#sliderStickyPosts').show("normal");
		break;

		case "Latest Posts from Category" :
 		jQuery('#sliderLatestCateg').show("normal");
		break;

		case "Random Posts from Category" :
 		jQuery('#sliderRandomCateg').show("normal");
		break;

		case "Specific Posts" :
 		jQuery('#sliderSpecificPosts').show("normal");
		break;

	}//switch

});//function

jQuery('.slideDivs').hide("normal");
	switch (jQuery('#mantra_slideType option:selected').val()) {

		case "Custom Slides" :
 		jQuery('#sliderCustomSlides').show("normal");
		break;

		case "Latest Posts" :
 		jQuery('#sliderLatestPosts').show("normal");
		break;

		case "Random Posts" :
 		jQuery('#sliderRandomPosts').show("normal");
		break;

		case "Sticky Posts" :
 		jQuery('#sliderStickyPosts').show("normal");
		break;

		case "Latest Posts from Category" :
 		jQuery('#sliderLatestCateg').show("normal");
		break;

		case "Random Posts from Category" :
 		jQuery('#sliderRandomCateg').show("normal");
		break;

		case "Specific Posts" :
 		jQuery('#sliderSpecificPosts').show("normal");
		break;
};//switch

//Slide type value
$sliderNr=jQuery('#mantra_slideType').val();

//Show category if a category type is selected
if ($sliderNr=="Latest Posts from Category" || $sliderNr=="Random Posts from Category" )
		jQuery('#slider-category').show();
else 	jQuery('#slider-category').hide();

//Show number of slides if that's the case
if ($sliderNr=="Latest Posts" || $sliderNr =="Random Posts" || $sliderNr =="Sticky Posts" ||  $sliderNr=="Latest Posts from Category" || $sliderNr=="Random Posts from Category" )
		jQuery('#slider-post-number').show();
else 	jQuery('#slider-post-number').hide();

//On change
jQuery('#mantra_slideType').change(function(){
	$sliderNr=jQuery('#mantra_slideType').val();
//Show category if a category type is selected
	if ($sliderNr=="Latest Posts from Category" || $sliderNr=="Random Posts from Category" )
				jQuery('#slider-category').show();
	else 		jQuery('#slider-category').hide();
//Show number of slides if that's the case
	if ($sliderNr=="Latest Posts" || $sliderNr =="Random Posts" || $sliderNr =="Sticky Posts" || $sliderNr=="Latest Posts from Category" || $sliderNr=="Random Posts from Category" )
			jQuery('#slider-post-number').show();
else 		jQuery('#slider-post-number').hide();
     });//onchange funciton
	 


// Create accordion from existing settings table
	jQuery('.form-table').wrap('<div>');
	jQuery(function() {
			jQuery( "#accordion" ).accordion({
				header: 'h3',
				autoHeight: false,
				collapsible: true,
				navigation: true,
				active: false 
				});
					});
					

  });// ready

  // Change border for selecte inputs
function changeBorder (idName, className) {
	jQuery('.'+className).removeClass( 'checkedClass' );
	jQuery('.'+className).removeClass( 'borderful' );
	jQuery('#'+idName).addClass( 'borderful' );

return 0;
}