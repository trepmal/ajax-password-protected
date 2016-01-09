jQuery(document).ready( function($) {

	var wp = window.wp;

	$('.post-password-required').on( 'submit', 'form[action$="postpass"]', function( ev ) {

		// don't reload page
		ev.preventDefault();

		// remove any old errors
		if ( $( document.getElementById( 'app-error' ) ).length > 0 ) {
			$( document.getElementById( 'app-error' ) ).remove();
		}
		// get post id from label attr
		var $form = $(this),
		    id = $form.find('label').attr('for').replace('pwbox-', ''),
		    $passwordInput = $form.find('input[type="password"]'),
		    $post = $( document.getElementById( 'post-' + id ) );

		//
		$passwordInput.addClass('app-submitting').attr( 'disabled', 'disabled' );

		wp.ajax.send( 'do_post_password', {
			data: {
				pass: $form.find('input[name="post_password"]').val(),
				pid: id
			},
			success: function( data ) {
				$post.find('.entry-title').html( data.title );
				$post.find('.entry-content').html( data.content );
			},
			error: function( data ) {
				console.log( data );
				msg = '<p id="app-error" class="error" style="background:#fcc;padding:10px;">' + data + '</p>';
				$post.find('.entry-content').prepend( msg );
				$passwordInput.removeClass('app-submitting').removeAttr( 'disabled' );
			}

		});
	});
});