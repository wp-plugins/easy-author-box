<?php

add_action( 'edit_user_profile', 'ts_fab_extra_user_details' );
add_action( 'show_user_profile', 'ts_fab_extra_user_details' );
function ts_fab_extra_user_details( $user ) { ?>

	<h3>Easy Author Box <?php _e( 'User Details', 'ts-fab' ); ?></h3>

	<table class="form-table">
		<?php
			$userid = $user->ID;
			$user_hide = get_user_meta( $userid, 'ts_fab_user_hide', false );
			( $user_hide == true ) ? $checked = 'checked="checked"' : $checked = '';
		?>
		<tr>
			<th><?php _e( 'Display  Author Box', 'ts-fab' ); ?></th>
			<td>
				<label for="ts_fab_user_hide">
					<input type="checkbox" name="ts_fab_user_hide" id="ts_fab_user_hide" value="true" <?php echo $checked; ?> />
					<?php _e( 'Do not show  Author Box in your posts, pages and custom posts', 'ts-fab' ); ?>
				</label>
			</td>
		</tr>

		<tr>
			<th><label for="ts_fab_twitter">Twitter</label></th>

			<td>
				<input type="text" name="ts_fab_twitter" id="ts_fab_twitter" value="<?php echo esc_attr( get_the_author_meta( 'ts_fab_twitter', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e( 'Your Twitter username.', 'ts-fab' ); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="ts_fab_facebook">Facebook</label></th>

			<td>
				<input type="text" name="ts_fab_facebook" id="ts_fab_facebook" value="<?php echo esc_attr( get_the_author_meta( 'ts_fab_facebook', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e( 'Your Facebook username or ID.', 'ts-fab' ); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="ts_fab_googleplus">Google+</label></th>

			<td>
				<input type="text" name="ts_fab_googleplus" id="ts_fab_googleplus" value="<?php echo esc_attr( get_the_author_meta( 'ts_fab_googleplus', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e( 'Your Google+ ID.', 'ts-fab' ); ?></span>
			</td>
		</tr>

		
	</table>

<?php }




add_action( 'personal_options_update', 'ts_fab_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'ts_fab_save_extra_profile_fields' );

function ts_fab_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	if( isset( $_POST['ts_fab_user_hide'] ) ) {
		update_user_meta( $user_id, 'ts_fab_user_hide', $_POST['ts_fab_user_hide'] );
	} else {
		delete_user_meta( $user_id, 'ts_fab_user_hide' );
	}
	
	update_user_meta( $user_id, 'ts_fab_twitter', strip_tags( $_POST['ts_fab_twitter'] ) );
	update_user_meta( $user_id, 'ts_fab_facebook', strip_tags( $_POST['ts_fab_facebook'] ) );
	update_user_meta( $user_id, 'ts_fab_googleplus', strip_tags( $_POST['ts_fab_googleplus'] ) );
	
	
}

?>