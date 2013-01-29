<?php if (!defined ('ABSPATH')) die ('No direct access allowed');

/**
 * This is a sample option page: you can insert the media helper in
 * setting pages or user profile or where you like...
 *
 * Go in "Settings -> ALO Media Helper Sample" and see the live sample.
 * 
 */



/**
 * Add the option page in admin menu
 */
function alo_mh_add_admin_option_page() {
  global $wp_version;

	if ( version_compare( $wp_version, '3.5', '>=' ) )	
		add_options_page('ALO Media Helper Sample', 'ALO Media Helper Sample', 'upload_files', ALO_MH_PLUGIN_DIR, 'alo_mh_admin_option_page' );
}
add_action('admin_menu', 'alo_mh_add_admin_option_page');


/**
 * Render the option page
 */
function alo_mh_admin_option_page() {

	// Update Option
	if ( isset($_POST['submit']) ) {
		if ( !wp_verify_nonce( $_POST["alo_mh_sample_options"], ALO_MH_PLUGIN_DIR ) ) wp_die(__('Cheatin&#8217; uh?'));
		
		if ( isset($_POST['alo_mh_custom_logo']) && is_numeric($_POST['alo_mh_custom_logo']) )
		{
			update_option( 'alo_mh_custom_logo', $_POST['alo_mh_custom_logo'] );
		}
		else
		{
			delete_option( 'alo_mh_custom_logo' );
		}
		
		echo '<div id="message" class="updated fade"><p>'. __( 'Settings saved.') .'</p></div>';
	}

?>

<form action="" method="post">
	
<div class="wrap" >

<div class="icon32" id="icon-options-general"><br></div>
<h2><?php _e( 'Settings' ) ?>: ALO Media Helper Sample</h2>

<table class="form-table"><tbody>

	<tr valign="top">
	<th scope="row"><?php _e('Select Image') ?>:</th>
	<td>
	<?php
	
	// Here the option key name
	$option_key = 'alo_mh_custom_logo';

	// The preview option value
	$prev_value = get_option( $option_key );

	// Image size ( standards are: 'thumbnail', 'medium', 'large', 'full' )
	$size = 'thumbnail';
	
	// Print the html media helper
	alo_mh_insert_from_media_lib( $option_key, $prev_value, $size );
	?>		
	</td>
	</tr>	
	
	</tbody> </table>

	<p class="submit">
	<?php wp_nonce_field( ALO_MH_PLUGIN_DIR, "alo_mh_sample_options" ); ?>
	<input type="submit" name="submit" value="<?php _e('Save Changes') ?>" class="button-primary" />
	</p>

	<hr style="width: 100%" />

	<p>After saving the image, the option stores the attachment ID:</p>

	<pre>
	// Get the attachment ID from option
	$attachment_id = get_option( 'alo_mh_custom_logo' );

	// Now you can get and use attachment: e.g print image	
	echo wp_get_attachment_image( $attachment_id, 'medium' );
	</pre>

	<?php
	
	$attachment_id = get_option( 'alo_mh_custom_logo' );
	if ( $attachment_id )
		echo wp_get_attachment_image( $attachment_id, 'medium' );
	?>
	
</div>


</form>

<?php
}
