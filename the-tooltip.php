<?php
/*
Plugin Name: The Tooltip
Plugin URI: http://wp-plugins.in/the-tooltip
Description: Simple WordPress tooltip shortcode, full customize and easy to use.
Version: 1.0.2
Author: Alobaidi
Author URI: http://wp-plugins.in
License: GPLv2 or later
*/

/*  Copyright 2015 Alobaidi (email: wp-plugins@outlook.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function alobaidi_the_tooltip_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'the-tooltip.php' ) !== false ) {
		
		$new_links = array(
						'<a href="http://wp-plugins.in/the-tooltip" target="_blank">Explanation of Use</a>',
						'<a href="https://profiles.wordpress.org/alobaidi#content-plugins" target="_blank">More Plugins</a>',
						'<a href="http://j.mp/ET_WPTime_ref_pl" target="_blank">Elegant Themes</a>',
					);
		
		$links = array_merge( $links, $new_links );
		
	}
	
	return $links;
	
}
add_filter( 'plugin_row_meta', 'alobaidi_the_tooltip_plugin_row_meta', 10, 2 );


// Include CSS Style
function alobaidi_simple_tooltip_css(){		
	wp_enqueue_style( 'alobaidi-simple-tooltip-style', plugins_url( '/css/simple-tooltip-style.css', __FILE__ ), false, null);
}
add_action('wp_enqueue_scripts', 'alobaidi_simple_tooltip_css');


// The Tooltip Shortcode
function alobaidi_simple_tooltip_shortcode( $atts, $content = null ){

	extract(
		shortcode_atts(
			array(
				"text"			=>	"",
				"background"	=>	"",
				"url"			=>	"",
				"color"			=>	"",
				"tooltip"		=>	""
			),$atts
		)
	);
	
	if( !empty($text) or !empty($url) ){
		
		if( !empty($text) and empty($url) ){
			$the_text = strip_tags($text);
		}
		
		if( empty($text) and !empty($url) ){
			$website_link 	= 	get_option('siteurl');
			$domain = str_ireplace('www.', '', parse_url($website_link, PHP_URL_HOST));
			$regex = "/($domain)/";
			if( !preg_match($regex, $url) ){
				$rel = ' rel="nofollow"';
				$target = ' target="_blank"';
			}else{
				$rel = null;
				$target = null;
			}
			$url_border 	=	' style="border-bottom:1px dotted !important;"';
			$the_text 		=	'<a'.$rel.$target.' href="'.$url.'"'.$url_border.'>'.$url.'</a>';
			$wrap_border 	=	' style="border:none !important;"';
		}else{
			$wrap_border 	= 	null;
			$url_border		=	null;
		}
		
		if( !empty($text) and !empty($url) ){
			$website_link 	= 	get_option('siteurl');
			$domain = str_ireplace('www.', '', parse_url($website_link, PHP_URL_HOST));
			$regex = "/($domain)/";
			if( !preg_match($regex, $url) ){
				$rel = ' rel="nofollow"';
				$target = ' target="_blank"';
			}else{
				$rel = null;
				$target = null;
			}
			$url_border 	=	' style="border-bottom:1px dotted !important;"';
			$the_text 		=	'<a'.$rel.$target.' href="'.$url.'"'.$url_border.'>'.$text.'</a>';
			$wrap_border 	=	' style="border:none !important;"';
		}else{
			$wrap_border 	= 	null;
			$url_border		=	null;
		}
		
	}else{
		$the_text		=	null;
		$wrap_border 	= 	null;
		$url_border		=	null;
	}
	
	if( !empty($background) ){
		$css_bg 		=	$background;
		$before_id		=	'wptime-simple-tooltip-arrow-color'.rand();
		$arrow_color 	=	'<style type="text/css">.'.$before_id.':after{border-top-color:'.$css_bg.' !important;}</style>';
		$space			=	' ';
	}else{
		$css_bg 		=	null;
		$before_id		=	null;
		$arrow_color 	=	null;
		$space			=	null;
	}
	
	if( !empty($color) ){
		$css_color = $color;
	}else{
		$css_color = null;
	}
	
	if( !empty($background) or !empty($color) ){
		$style = ' style="background-color:'.$css_bg.';color:'.$css_color.';"';
	}else{
		$style = null;
	}
	
	if( !empty($tooltip) ){
		$tooltip_text = strip_tags($tooltip);
	}else{
		$tooltip_text = null;
	}
	
	return '<span class="wptp-the-tooltip"'.$wrap_border.'><i class="wptp-tooltip-text'.$space.$before_id.'"'.$style.'>'.$tooltip_text.'</i>'.$arrow_color.$the_text.'</span>';

}
add_shortcode('the_tooltip', 'alobaidi_simple_tooltip_shortcode');


// Add simple tooltip button to wp editor
function alobaidi_simple_tooltip_tinymce_button($buttons) {
	array_push($buttons, 'wptp_simple_tooltip_button');
	return $buttons;
}
add_filter('mce_buttons', 'alobaidi_simple_tooltip_tinymce_button');


// Register js for simple tooltip button
function alobaidi_simple_tooltip_register_tinymce_js($plugin_array) {
	$plugin_array['wptp_simple_tooltip_button'] = plugins_url( '/js/wptp_simple_tooltip_tinymce_button.js', __FILE__);
	return $plugin_array;
}
add_filter('mce_external_plugins', 'alobaidi_simple_tooltip_register_tinymce_js');

?>