<h1 class="template-title">Templates</h1>
<p class="template-description">
	On this page you can find all your templates. You can add, edit, delete any template.
</p>
<div class="builder-section-container">
	<button id="create-new-layout" class="red-ui-button" data-create-uri="<?php echo esc_url( admin_url('admin.php?page=templates&action=create') ); ?>">Create a new template</button>
	<?php
		$templates = Template::select_all();
		if ($templates):
	?>
		<table cellspacing="0" class="red-ui-table-list">
			<thead>
				<th width="300"><?php esc_html_e( 'Template name', 'videofly' ); ?></th>
				<th><?php esc_html_e( 'Options', 'videofly' ); ?></th>
			</thead>
			<tbody>
				<?php foreach ($templates as $tempalte_id => $template): ?>
					<tr>
						<td><?php echo vdf_var_sanitize($template['name'] ); ?> </td>
						<td> <a class="red-ui-button-grey" href="<?php echo esc_url( admin_url('admin.php?page=templates&action=edit&id=' . $tempalte_id) ); ?>">Edit</a><a class="red-ui-button-grey" href="<?php echo esc_url( admin_url('admin.php?page=templates&action=delete&id=' . $tempalte_id) ); ?>">Delete</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p><?php esc_html_e( 'You don\'t have templates, but you can create them by pressing "Create a new Template" button', 'videofly' ); ?></p>
	<?php endif ?>
</div>
