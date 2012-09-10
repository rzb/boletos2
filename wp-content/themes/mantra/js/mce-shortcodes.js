/**
 * Adding buttons to the the mce menu for the main short codes
 * @package mantra
 * 
 */
(function() {  
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('mantrashortcodes');

	tinymce.create('tinymce.plugins.mantraShortCodes', {  
		init : function(ed, url) {  
			
			ed.addButton('button-light', {  
				title : ed.getLang('mantrashortcodes.buttonlighttitle', 'Add a light button'),  
				image : url+'/buttons/button-light.png',  
				onclick : function() {  
					 ed.selection.setContent('[mantra-button-light url="#"]' + ed.selection.getContent() + '[/mantra-button-light]');  
				}  
			});
			
			ed.addButton('button-dark', {  
				title : ed.getLang('mantrashortcodes.buttondarktitle', 'Add a dark button'),  
				image : url+'/buttons/button-dark.png',  
				onclick : function() {  
					 ed.selection.setContent('[mantra-button-dark url="#"]' + ed.selection.getContent() + '[/mantra-button-dark]');  
				}  
			});
			
			ed.addButton('button-color', {  
				title : ed.getLang('mantrashortcodes.buttoncolortitle', 'Add a color button'),  
				image : url+'/buttons/button-color.png',  
				onclick : function() {  
					 ed.selection.setContent('[mantra-button-color url="#" color="#47AFFF"]' + ed.selection.getContent() + '[/mantra-button-color]');  
				}  
			});
			
			ed.addButton('pullquote', {  
				title : ed.getLang('mantrashortcodes.pullquotetitle', 'Add a pullquote'), 
				image : url + '/buttons/pullquote.png',
				onclick : function() {  
					 ed.selection.setContent('[mantra-pullquote align="left|center|right" textalign="left|center|right" width="33%"]' + ed.selection.getContent() + '[/mantra-pullquote]');  
				}  
			});
			
						ed.addButton('multi-column', {  
				title : ed.getLang('mantrashortcodes.multicolumntitle', 'Add multiple columns'), 
				image : url + '/buttons/multi-column.png',
				onclick : function() {  
					 ed.selection.setContent('[mantra-multi][mantra-column width="1/4"] [/mantra-column] [mantra-column width="1/2"]' + ed.selection.getContent() + '[/mantra-column] [mantra-column width="1/4"] [/mantra-column] [/mantra-multi]');  
				}  
			});
			
		},  
		createControl : function(n, cm) {  
			return null;  
		},  
	});  
	tinymce.PluginManager.add('mantrashortcodes', tinymce.plugins.mantraShortCodes);  
})();