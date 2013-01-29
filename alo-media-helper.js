var $alomh = jQuery.noConflict();
$alomh (document).ready ( function(){

  $alomh('a.choose-from-library-btn').each ( function (index) {

		if ( $alomh(this).find('img.choose-from-library-preview').length > 0 ) {
			$alomh(this).parent().find('span.insert-attach-img-btn').hide();
			$alomh(this).parent().find('a.remove-attach-img-btn').show();
		} else {
			$alomh(this).parent().find('a.remove-attach-img-btn').hide();
		}
	});

	// http://mikejolley.com/2012/12/using-the-new-wordpress-3-5-media-uploader-in-plugins/
	
	if ( $alomh('.choose-from-library-btn').length > 0 ) {
		
		$alomh('.choose-from-library-btn').live('click', function( event ){
			var frame;
			var $el = $alomh(this);

			event.preventDefault();

			var $target = $el.data('target');
				
			// If the media frame already exists, reopen it.
			if ( frame ) {
				frame.open();
				return;
			}

			// Create the media frame.
			frame = wp.media.frames.customBackground = wp.media({
				// Set the title of the modal.
				title: $el.data('title'),

				// Tell the modal to show only images.
				library: {
					type: 'image'
				},

				// Customize the submit button.
				button: {
					// Set the text of the button.
					text: $el.data('update'),
					close: true // Set to false to not close frame on click submit button
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});

			// When an image is selected, run a callback.
			frame.on( 'select', function() {
				// Grab the selected attachment.
				var attachment = frame.state().get('selection').first().toJSON();

				// alert( attachment.id + ' ' + attachment.url );

				$alomh( '#' + $target ).val( attachment.id );

				$alomh.post( ajaxurl, {
					
					action: 'alo_mh_set_preview_image',
					attachment_id: attachment.id,
					target_id : $target + '-preview',
					size: $el.data('size')
					
				}).done( function( html ) {

					//window.location.reload();

					$alomh( '#' + $target + '-preview' ).remove();

					$el.append( html );
					
					$el.parent().find('a.remove-attach-img-btn').show();
					$alomh( '#' + $target + '-insert' ).hide();
				});
				
			});

			// Finally, open the modal.
			frame.open();
		});


		$alomh('.remove-attach-img-btn').live('click', function( event ){
			
			event.preventDefault();

			if ( ! confirm( 'Confirm?' ) ) return false;
				
			var $el = $alomh(this);
			var $target = $el.data('target');

			$alomh( '#' + $target + '-preview' ).remove();
			$alomh( '#' + $target + '-insert' ).show();
			$el.hide();

			$alomh( '#' + $target ).val('');
		});
				
	}
	
});
