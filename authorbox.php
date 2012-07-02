<?php

/*

Plugin Name:Easy Author Box 

Plugin URI: http://www.7boats.com/wordpress-plugins/rohini/

Description:Adds an author box after your post contents.The box contains author's avatar,name,post count,site link,personal description and Email.This plugin will allow you to place author bio in author url above all posts written by the author.This plugin works best with the twenty eleven theme.

Version: 1.0

Author: Rohini Singh
Author URI:http://www.7boats.com/


/*

Copyright 2012  Seven Boats Info-System Pvt. Ltd.(Email : info@7boats.com)



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

function add_twitter_contactmethod( $contactmethods ) {

  $contactmethods['twitter'] = 'Twitter';

  $contactmethods['facebook'] = 'Facebook';

  $contactmethods['google_profile'] = 'Google Profile';

  return $contactmethods;

}

add_filter('user_contactmethods','add_twitter_contactmethod',10,1);



	

function add_authorbox_css() {

    ?>

<link rel="stylesheet" href="<?php echo get_option('siteurl') . '/' . PLUGINDIR . '/' . dirname(plugin_basename (__FILE__))?>/authorbox.css" type="text/css" media="screen" />

    <?php

}

add_action(wp_head,add_authorbox_css,1);





add_filter('the_content', 'add_author_box');

function add_author_box($content) {



	// Main Part of Author Box

	$author_box='

	<div class="author_info">
         <p><span class="author_photo">'.get_avatar(get_the_author_id() ).'</span><a rel="nofollow" href="'.get_the_author_meta( 'user_url' ).'">'.get_the_author_meta('display_name').'</a> &ndash; who has written <a rel="author" href="'.get_author_posts_url(get_the_author_meta( 'ID' )).'">'. get_the_author_posts().'</a> posts on <a href="'.get_bloginfo("home").'">'.get_bloginfo("name").'</a>.<br>'.get_the_author_description().'</p>

	<p class="author_email">

	<a href="mailto:'.get_the_author_email().'" title="Send an Email to the Author of this Post">Email</a>


	';

	

	//Fetch the User Social Contact Infomation

	$twitter = get_the_author_meta( 'twitter' );

	$facebook = get_the_author_meta( 'facebook' );

	$google_profile = get_the_author_meta( 'google_profile' );

	

	if($google_profile){

	$display_google_profile='&nbsp;&#8226;&nbsp;<a title="My Google +" rel="me nofollow" href="' . esc_url($google_profile) . '" target="_blank">Google +</a>';

	}

	if($facebook){

	$display_facebook_profile='&nbsp;&#8226;&nbsp;<a title="My facebook" rel="me nofollow" href="' . esc_url($facebook) . '" target="_blank">Facebook </a>';

	}

	if($twitter){	

	$display_twitter_profile='&nbsp;&#8226;&nbsp;<a title="My Twitter" rel="me nofollow" href="' . esc_url($twitter) . '" target="_blank">Twitter</a>';

	}

	

	//Dynamic Output of the Author Box (Show Info you've set)

	if(is_single()) {

	$content.= ($author_box.$display_google_profile.$display_facebook_profile.$display_twitter_profile.'</p></div>');

    }

    return $content;

}
	function add_EABDescription_menu()
{
	add_menu_page(__('EAB Description','menu-EABDescription'), __('EAB Description','menu-EABDescription'), 'manage_options', 'EABDescription-admin', 'showEABDescriptionMenu' );
}

add_action( 'admin_menu', 'add_EABDescription_menu' );

function showEABDescriptionMenu()
{
	include("admin/overview.php");
}

