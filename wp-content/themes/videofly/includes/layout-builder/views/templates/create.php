<?php
function validate_layout_title()
{
	if ( empty( $_POST['template_name'] ) ) {
		return new WP_Error( 'empty_title', esc_html__( "Title is empty", 'videofly' ) );
	} else {
		return $_POST['template_name'];
	}
}

if ( isset( $_POST['create_template'] ) ) {
	$title = validate_layout_title();

	if ( is_wp_error( $title ) ) {
		echo '<div class="error">' . $title->get_error_message() . '</div>';
	} else {
		Template::create();
	}
} else { ?>

<form action="<?php echo esc_url( admin_url( 'admin.php?page=templates&action=create' ) ); ?>" method="POST">
	<table cellpadding="10">
		<tr>
			<td><label for="template_name"><?php esc_html_e('Template name','videofly'); ?>:</label></td>
			<td><input type="text" name="template_name" id="template_name" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="create_template" class="button-primary red-ui-button" value="<?php esc_html_e('Create Template','videofly');?>" /></td>
		</tr>
	</table>
</form>
<?php } ?>
