<?php
/*
Plugin Name: Ajax Password Protected
Plugin URI: http://trepmal.com/
Description: Ajax-ified password-protected form
Version: 0.1
Author: Kailey Lampert
Author URI: http://kaileylampert.com

Copyright (C) 2012  Kailey Lampert

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

$ajax_password_protected = new Ajax_Password_Protected();

class Ajax_Password_Protected {

	function __construct() {
		add_action( 'wp_ajax_do_post_password',        array( $this, 'do_x_post_password_cb' ) );
		add_action( 'wp_ajax_nopriv_do_post_password', array( $this, 'do_x_post_password_cb' ) );
		add_action( 'wp_enqueue_scripts',              array( $this, 'wp_enqueue_scripts' ) );
	}

	function do_x_post_password_cb() {

		//snag from wp-login.php:386-393
		require_once( ABSPATH . 'wp-includes/class-phpass.php' );
		// By default, use the portable hash from phpass
		$wp_hasher = new PasswordHash(8, true);
		// 10 days
		setcookie( 'wp-postpass_' . COOKIEHASH, $wp_hasher->HashPassword( stripslashes( $_POST['pass'] ) ), time() + 864000, COOKIEPATH );
		//fake it so it's available in the loop below
		$_COOKIE['wp-postpass_' . COOKIEHASH] = $wp_hasher->HashPassword( stripslashes( $_POST['pass'] ) );

		$q = new WP_Query( "p={$_POST['pid']}" );
		if ( $q->have_posts() ) : while( $q->have_posts() ) : $q->the_post();

			// verifies password hash
			if ( post_password_required() ) {
				wp_send_json_error( 'Invalid password' );
			}

			// get post title
			ob_start();
			the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' );
			$title = ob_get_clean();

			// get post content
			ob_start();
			the_content();
			$content = ob_get_clean();

		endwhile; endif;
		wp_reset_postdata();

		$return = array(
			'title'   => $title,
			'content' => $content,
		);

		wp_send_json_success( $return );
	}

	//lazily doing my best to get jquery available before my script below
	function wp_enqueue_scripts() {
		wp_enqueue_script( 'ajax-password-protected', plugins_url( 'ajax-password-protected.js', __FILE__ ), array( 'jquery', 'wp-util' ), '0.0.1', true );
		// wp_localize_script( 'ajax-password-protected', 'ajaxPasswordProtected', array(
		// 	'loading' => includes_url( 'images/spinner.gif' ),
		// ) );
	}

}