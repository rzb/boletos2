jQuery(document).ready(function() {

// Back to top button animation
jQuery(function() {
	jQuery(window).scroll(function() {

var x=jQuery(this).scrollTop();
		if(x != 0) {
			jQuery('#toTop').fadeIn(3000);	
		} else {
			jQuery('#toTop').fadeOut();
		}
	});
 
	jQuery('#toTop').click(function() {
		jQuery('body,html').animate({scrollTop:0},800);
	});	


});

// Menu animation

jQuery("#access ul ul").css({display: "none"}); // Opera Fix

jQuery("#access li").hover(function(){
	jQuery(this).find('ul:first').css({visibility: "visible",display: "none"}).show(400);
	},function(){
	jQuery(this).find('ul:first').css({visibility: "hidden"});
								});
// Social Icons Animation

jQuery(".socialicons").hover(
function(){
	jQuery(this).animate({"top": "-5px" },{ queue: false, duration:200});
/*	 jQuery(this).css({
                        '-webkit-transform': 'rotate(5deg)',
                        '-moz-transform': 'rotate(5deg)',
                        '-ms-transform': 'rotate(5deg)',
                        '-o-transform': 'rotate(5deg)',
                        'transform': 'rotate(5deg)',
                        'zoom': 1
            });*/

					},
function(){
	jQuery(this).animate({ "top": "0px" }, { queue: false, duration:200 });
/* jQuery(this).css({
                        '-webkit-transform': 'rotate(0deg)',
                        '-moz-transform': 'rotate(0deg)',
                        '-ms-transform': 'rotate(0deg)',
                        '-o-transform': 'rotate(0deg)',
                        'transform': 'rotate(0deg)',
                        'zoom': 1
            });*/

					});

/*! http://tinynav.viljamis.com v1.03 by @viljamis */
(function ($, window, i) {
  $.fn.tinyNav = function (options) {

    // Default settings
    var settings = $.extend({
      'active' : 'selected', // String: Set the "active" class
      'header' : false // Boolean: Show header instead of the active item
    }, options);

    return this.each(function () {

      // Used for namespacing
      i++;

      var $nav = $(this),
        // Namespacing
        namespace = 'tinynav',
        namespace_i = namespace + i,
        l_namespace_i = '.l_' + namespace_i,
        $select = $('<select/>').addClass(namespace + ' ' + namespace_i);

      if ($nav.is('ul,ol')) {

        if (settings.header) {
          $select.append(
            $('<option/>').text('Navigation')
          );
        }

        // Build options
        var options = '';
		var indent = 0;
		var indented = ["&nbsp;"];
		for ( var i = 0; i < 10; i++) {
			indented.push(indented[indented.length-1]+indented[indented.length-1]);
		}
		indented[0] = "";
        $nav
          .addClass('l_' + namespace_i)
          .children('li')
          .each(buildNavTree=function () {
            var a = $(this).children('a').first();
            
            options +=
              '<option value="' + a.attr('href') + '">' +
              indented[indent] + a.text() +
              '</option>';
              indent++;
              $(this).children('ul,ol').children('li').each(buildNavTree);
              indent--;
          });

        // Append options into a select
        $select.append(options);

        // Select the active item
        if (!settings.header) {
          $select
            .find(':eq(' + $(l_namespace_i + ' li')
            .index($(l_namespace_i + ' li.' + settings.active)) + ')')
            .attr('selected', true);
        }

        // Change window location
        $select.change(function () {
          window.location.href = $(this).val();
        });

        // Inject select
        $(l_namespace_i).after($select);

      }

	$('option[value="'+document.location+'"]').attr("selected","selected");

    });

  };
})(jQuery, this, 0);





}); // ready 

/*!
* FitVids 1.0
*
* Copyright 2011, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
* Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
* Released under the WTFPL license - http://sam.zoy.org/wtfpl/
*
* Date: Thu Sept 01 18:00:00 2011 -0500
*/

(function( $ ){

  $.fn.fitVids = function( options ) {
    var settings = {
      customSelector: null
    }
    
    var div = document.createElement('div'),
        ref = document.getElementsByTagName('base')[0] || document.getElementsByTagName('script')[0];
        
   div.className = 'fit-vids-style';
    div.innerHTML = '&shy;<style> \
.fluid-width-video-wrapper { \
width: 100%; \
position: relative; \
padding: 0; \
} \
\
.fluid-width-video-wrapper iframe, \
.fluid-width-video-wrapper object, \
.fluid-width-video-wrapper embed { \
position: absolute; \
top: 0; \
left: 0; \
width: 100%; \
height: 100%; \
} \
</style>';
                      
    ref.parentNode.insertBefore(div,ref);
    
    if ( options ) {
      $.extend( settings, options );
    }
    
    return this.each(function(){
      var selectors = [
        "iframe[src*='player.vimeo.com']",
        "iframe[src*='www.youtube.com']",
        "iframe[src*='www.kickstarter.com']",
        "object",
        "embed"
      ];
      
      if (settings.customSelector) {
        selectors.push(settings.customSelector);
      }
      
      var $allVideos = $(this).find(selectors.join(','));

      $allVideos.each(function(){
        var $this = $(this);
        if (this.tagName.toLowerCase() == 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) { return; }
        var height = this.tagName.toLowerCase() == 'object' ? $this.attr('height') : $this.height(),
            aspectRatio = height / $this.width();
if(!$this.attr('id')){
var videoID = 'fitvid' + Math.floor(Math.random()*999999);
$this.attr('id', videoID);
}
        $this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+"%");
        $this.removeAttr('height').removeAttr('width');
      });
    });
  
  }
})( jQuery );

