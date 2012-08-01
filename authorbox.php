<?php

/*

Plugin Name:Easy Author Box 

Plugin URI: http://www.7boats.com/wordpress-plugins/rohini/

Description:Adds an author box after your post contents.The box contains author's avatar,name,post count,site link,personal description and Email.This plugin will allow you to place author bio in author url above all posts written by the author.This plugin works best with the twenty eleven theme.It also comes with the new display settings to the user who can change the author box colours accordingly.The display setting is included in the tools section from where the user can change the colours of the author box.Also the social media links can be added to the author box through a special settings page which is present in the user profile section page.

Version: 1.0.1

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




if ( is_admin() ) {

	// Register plugin settings page
	require_once( dirname(__FILE__) . '/includes/settings.php' );
	
	// Add user settings
	require_once( dirname(__FILE__) . '/includes/user-settings.php' );
	
}

// Include tab constructor
require_once( dirname(__FILE__) . '/includes/construct-tabs.php' );




add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ts_fab_plugin_action_links' );
function ts_fab_plugin_action_links( $links ) {

	return array_merge(
		array(
			
		),
		$links
	);

}




add_filter( 'plugin_row_meta', 'ts_fab_plugin_meta_links', 10, 2 );
function ts_fab_plugin_meta_links( $links, $file ) {

	$plugin = plugin_basename(__FILE__);
	
	
	return $links;

}




function ts_fab_get_display_settings() {

	$default_display_settings = array(
		'show_in_posts'				=> 'below',
		'show_in_pages'				=> 'below',
		'latest_posts_count'		=> 3,
		
		'inactive_tab_background'	=> '#e9e9e9',
		'inactive_tab_border'		=> '#e9e9e9',
		'inactive_tab_color'		=> '#333',
		
		'active_tab_background'		=> '#333',
		'active_tab_border'			=> '#333',
		'active_tab_color'			=> '#fff',
		
		'tab_content_background'	=> '#f9f9f9',
		'tab_content_border'		=> '#333',
		'tab_content_color'			=> '#555',
	);
	
	$custom_post_types_display_settings = array();
	
	$args = array(
		'public'   => true,
		'_builtin' => false
	); 
	$output = 'names';
	$operator = 'and';
	$custom_post_types = get_post_types( $args, $output, $operator ); 
	
	foreach ( $custom_post_types  as $custom_post_type ) {
		$custom_post_types_display_settings['show_in_' . $custom_post_type] = ( 'below' );
	}
	
	$default_display_settings = array_merge( $default_display_settings, $custom_post_types_display_settings );


	$display_settings = wp_parse_args( get_option( 'ts_fab_display_settings' ), $default_display_settings );
	
	return $display_settings;

}



/**
 * Add  Author Box to post/page content
 *
 * @since 1.0.1
 */
add_filter( 'the_content', 'ts_fab_add_author_box' );
function ts_fab_add_author_box( $content ) {

	global $authordata;
	
	// Use helper functions to get plugin settings
	$ts_fab_display_settings = ts_fab_get_display_settings();

	if( !get_user_meta( $authordata->ID, 'ts_fab_user_hide', false ) ) {

		// Show  Author Box in posts
		if( is_singular( 'post' ) ) {

			$show_in_posts = $ts_fab_display_settings['show_in_posts'];
			if( $show_in_posts == 'above' ) {
				$content = ts_fab_construct_fab( 'above', $authordata->ID ) . $content;
			} elseif( $show_in_posts == 'below' ) {
				$content .= ts_fab_construct_fab( 'below', $authordata->ID );
			} elseif( $show_in_posts == 'both' ) {
				$content = ts_fab_construct_fab( 'above', $authordata->ID ) . $content . ts_fab_construct_fab( 'below', $authordata->ID );
			}

		}

		// Show Easy Author Box in pages
		if( is_singular( 'page' ) ) {

			$show_in_pages = $ts_fab_display_settings['show_in_pages'];
			if( $show_in_pages == 'above' ) {
				$content = ts_fab_construct_fab( 'above', $authordata->ID ) . $content;
			} elseif( $show_in_pages == 'below' ) {
				$content .= ts_fab_construct_fab( 'below', $authordata->ID );
			} elseif( $show_in_pages == 'both' ) {
				$content = ts_fab_construct_fab( 'above', $authordata->ID ) . $content . ts_fab_construct_fab( 'below', $authordata->ID );
			}

		}

		// Show Easy Author Box in custom post types
		$args = array(
			'public'   => true,
			'_builtin' => false
		); 
		$output = 'names';
		$operator = 'and';
		$custom_post_types = get_post_types( $args, $output, $operator ); 
		foreach ( $custom_post_types  as $custom_post_type ) {
			if( is_singular( $custom_post_type ) ) {
		
				$show_in_custom = $ts_fab_display_settings['show_in_' . $custom_post_type];
				if( $show_in_custom == 'above' ) {
					$content = ts_fab_construct_fab( 'above', $authordata->ID ) . $content;
				} elseif( $show_in_custom == 'below' ) {
					$content .= ts_fab_construct_fab( 'below', $authordata->ID );
				} elseif( $show_in_custom == 'both' ) {
					$content = ts_fab_construct_fab( 'above', $authordata->ID ) . $content . ts_fab_construct_fab( 'below', $authordata->ID );
				}	
			
			}	
		}
	}	

	return $content;

}



/**
 * Enqueue Easy Author Box scripts and styles
 *
 * @since 1.0.1
 */
add_action( 'wp_enqueue_scripts', 'ts_fab_add_scripts_styles' );
function ts_fab_add_scripts_styles() {

	$css_url = plugins_url( 'css/ts-fab.min.css', __FILE__ );
	wp_register_style( 'ts_fab_css', $css_url, '', '1.0' );
	wp_enqueue_style( 'ts_fab_css' );

	$js_url = plugins_url( 'js/ts-fab.min.js', __FILE__ );
	wp_register_script( 'ts_fab_js', $js_url, array( 'jquery' ), '1.0' );
	wp_enqueue_script( 'ts_fab_js' );
	
}



/**
 * Print CSS for color options
 *
 * @since 1.0.1
 */
add_action( 'wp_head', 'ts_fab_print_color_settings' );
function ts_fab_print_color_settings() {

	$default_colors = array(
		'#e9e9e9',		// Inactive tab background
		'#e9e9e9',		// Inactive tab border
		'#333',			// Inactive tab text color
		
		'#333',			// Active tab background
		'#333',			// Active tab border
		'#fff',			// Active tab text color
		
		'#f9f9f9',		// Tab content background
		'#333',			// Tab content border
		'#555'			// Tab content text color
	);
	
	$options = ts_fab_get_display_settings();

	$current_colors = array(
		$options['inactive_tab_background'],
		$options['inactive_tab_border'],
		$options['inactive_tab_color'],
		
		$options['active_tab_background'],
		$options['active_tab_border'],
		$options['active_tab_color'],
		
		$options['tab_content_background'],
		$options['tab_content_border'],
		$options['tab_content_color'],
	);
	
	// Check if default colors should be used
	if( count( array_diff( $current_colors, $default_colors ) ) > 0 ) {
	?>
	<style>
	.ts-fab-list li a { background-color: <?php echo $options['inactive_tab_background']; ?>; border: 1px solid <?php echo $options['inactive_tab_border']; ?>; color: <?php echo $options['inactive_tab_color']; ?>; }
	.ts-fab-list li.active a { background-color: <?php echo $options['active_tab_background']; ?>; border: 1px solid <?php echo $options['active_tab_border']; ?>; color: <?php echo $options['active_tab_color']; ?>; }		
	.ts-fab-tab { background-color: <?php echo $options['tab_content_background']; ?>; border: 2px solid <?php echo $options['tab_content_border']; ?>; color: <?php echo $options['tab_content_color']; ?>; }		
	</style>
	<?php
	}
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



?>