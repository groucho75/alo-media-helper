<?php
/*
Plugin Name: ALO Media Helper
Description: A html helper to open image library and select an image for options. It requires WP 3.5+. You have a helper function ('<em>alo_mh_insert_from_media_lib</em>') that you can use in your plugin. After activation, you can visit "<em>Settings -> ALO Media Helper Sample</em>" in your dashboard to see a live sample (the code is in "<em>sample.php</em>" file included).
Version: 1.0
Author: Alessandro Massasso
*/

/*  Copyright 2013
 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


define("ALO_MH_PLUGIN_DIR", basename(dirname(__FILE__)) );
define("ALO_MH_PLUGIN_URL", untrailingslashit( plugin_dir_url(__FILE__) ) );
define("ALO_MH_PLUGIN_ABS", untrailingslashit( plugin_dir_path(__FILE__) ) );



/**
 * Load Scripts and Styles in ADMIN
 */
function alo_mh_head_scripts() {
  global $wp_version;
	if ( version_compare( $wp_version, '3.5', '>=' ) )
	{
		wp_enqueue_media();
		wp_enqueue_script( 'axelms-admin', ALO_MH_PLUGIN_URL .'/alo-media-helper.js', array('jquery') );
	}
}
add_action( 'admin_enqueue_scripts', 'alo_mh_head_scripts');


/**
 * Create a button to open media library, a preview of selected image, a button to remove selected image
 */
function alo_mh_insert_from_media_lib( $field, $prev=false, $size='medium') {
	global $wp_version;

	if ( version_compare( $wp_version, '3.5', '>=' ) )
	{	
		echo '<a href="" class="choose-from-library-btn" title="'. esc_attr__('Select Image') .'" data-update="'. esc_attr__('Select Image') .'" data-title="'. esc_attr__('Select Image') .'" data-target="'. $field.'" data-size="'. $size.'">';

		if ( $prev )
		{
			$attr = array(
				'id'	=> $field . "-preview",
				'class' => 'choose-from-library-preview',
				'alt'   => esc_attr__('Select Image') ,
				'style' => 'border: 1px solid #cccccc;'
			);
			echo wp_get_attachment_image( $prev, $size, 0, $attr );
		}
		echo '<span class="button insert-attach-img-btn" id="'. $field .'-insert">' . esc_attr__('Select Image') .'...</span>';
		echo '</a>';
		
		echo ' <a href="" class="remove-attach-img-btn" id="'. $field .'-remove" data-target="'. $field.'">'. __('Delete') .'</a>';
		
		echo '<input type="hidden" value="'. $prev .'" name="'. $field .'" id="'. $field .'" />';
	}
		
}

/**
 * Return the image after selection in library modal
 */
function alo_mh_ajax_set_preview_image () {
	if ( ! current_user_can('upload_files') || ! isset( $_POST['attachment_id'] ) ) exit;
	
	$attachment_id = absint($_POST['attachment_id']);
	$sizes = array_keys(apply_filters( 'image_size_names_choose', array('thumbnail' => __('Thumbnail'), 'medium' => __('Medium'), 'large' => __('Large'), 'full' => __('Full Size')) ));
	$size = 'medium';
	if ( in_array( $_POST['size'], $sizes ) )
		$size = esc_attr( $_POST['size'] );

	$attr = array(
		'id'	=> esc_attr($_POST['target_id']),
		'alt'   => esc_attr( __('Select Image') ),
		'style' => 'border: 1px solid #cccccc;'
	);
	echo wp_get_attachment_image( $attachment_id, $size, 0, $attr );
	exit;
}
add_action('wp_ajax_alo_mh_set_preview_image', 'alo_mh_ajax_set_preview_image');



// Include a sample option page
if ( @file_exists ( ALO_MH_PLUGIN_ABS . '/sample.php' ) ) include( 'sample.php' );


/* EOF */
