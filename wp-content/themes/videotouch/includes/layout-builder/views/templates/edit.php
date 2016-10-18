<?php
	$message =  __( 'This template does not exists', 'touchsize' );
	if (!isset($_GET['id'])) {
	 	die("<p>" . $message . "</p>");

	} else {
	 	$id = $_GET['id'];
	 	$data = Template::edit($id);

	 	if (!$data) {
	 		die( "<p>" . $message . "</p>" );
	 	} else {
	 		echo '<script>window.touchsizeTemplateID = "'.$id.'"</script>';
	 	}
	}

	$templates = get_option( Template::TEMPLATES, array() );
?>

<!---------------- Empty row template -------------------->
<script id="dragable-row-tpl" type="text/x-handlebars-template">
	<ul class="layout_builder_row">
			<li class="row-editor">
				<ul class="row-editor-options">
					<li><a href="#" class="add-column"><?php _e( '+', 'touchsize' ); ?></a></li>
					<li><a href="#" class="remove-row"><?php _e( 'delete', 'touchsize' ) ?></a></li>
					<li><a href="#" class="move"><?php _e( 'move', 'touchsize' ); ?></a></li>
				</ul>
			</li>
			<li class="edit-row-settings" >
				<a href="#" class="edit-row" id="{{row-id}}">Edit</a>
			</li>
			<li class="empty-row">
				<?php _e( 'The row is empty. Click here to add new column', 'touchsize' ) ?>
			</li>
	</ul>
</script>

<!---------------- Column row template -------------------->
<script id="dragable-column-tpl" type="text/x-handlebars-template">
<li data-columns="1" data-type="empty" class="columns1"> 
	<p><?php _e( 'Empty column', 'touchsize' ); ?></p>
	<span class="plus">&nbsp;</span>
	<span class="minus">&nbsp;</span>
	<span class="edit">&nbsp;</span>
	<span class="delete">&nbsp;</span>
</li>
</script>

<!-- ========= END Tempaltes ====================== -->
<h1 class="template-title">Teamplates &raquo; <b><?php echo esc_html(@$data['template_name']); ?></b></h1>
<p class="template-description">
	On this page you can create your custom templates, with tons of options and features. They can be used on any of your website's pages like categories, posts, archive, search, etc.
	Create your template, add your desired options and blocks, save your settings and then assign it to a page in the Layouts menu.
</p>

<div class="builder-section-container">
	<h2 class="builder-section-title"><?php _e( 'Header', 'touchsize' ); ?></h2>
	<p class="builder-section-descrtiption">This section is used for the upper part of the template you are creating. Feel free to add all kind of elements here to create your own styling and arrangement.</p>
	<!-- Edit Header Strucutre -->
	<div style="clear: both"></div>
	<a href="#" data-location="header" class="app red-ui-button add-top-row"><?php _e( 'Add row to the top', 'touchsize' ); ?></a><br/><br/>
	<div class="layout_builder" id="section-header">
		<?php echo $data['header']; ?>
	</div>
	<div style="clear: both"></div>
	<br>
	<a href="#" data-location="header" class="app red-ui-button add-bottom-row"><?php _e( 'Add row to the bottom', 'touchsize' ) ?></a>
	<div style="clear: both"></div>
</div>
<div class="builder-section-container">
	<h2 class="builder-section-title"><?php _e( 'Content', 'touchsize' ) ?></h2>
	<p class="builder-section-descrtiption">This is your content section. Used for listing content like boxes, posts in all kind of view styles, testimonials, team members, banners, pages, posts and many more. Use whatever combination you want and create your own beautiful website. <br/>
		<i>NOTE: When sidebars is added from layouts, they will apply to this section.</i>
	</p>
	<!-- Edit Content Strucutre -->
	<div style="clear: both"></div>
	<a href="#" data-location="content" class="app red-ui-button add-top-row"><?php _e( 'Add row to the top', 'touchsize' ); ?></a><br/><br/>
	<div class="layout_builder" id="section-content">
		<?php echo $data['content']; ?>
	</div>
	<div style="clear: both"></div>
	<br>
	<a href="#" data-location="content" class="app red-ui-button add-bottom-row"><?php _e( 'Add row to the bottom', 'touchsize' ); ?></a>
	<div style="clear: both"></div>
</div>
<div class="builder-section-container">
	<h2 class="builder-section-title"><?php _e( 'Footer', 'touchsize' ); ?></h2>
	<p class="builder-section-descrtiption">This section is used for elements like social icons, searchbar, copyright text, sidebar with widgets and others.</p>
	<!-- Edit Footer Strucutre -->
	<div style="clear: both"></div>
	<a href="#" data-location="footer" class="app red-ui-button add-top-row"><?php _e( 'Add row to the top', 'touchsize' ); ?></a><br/><br/>
	<div class="layout_builder" id="section-footer">
		<?php echo $data['footer']; ?>
	</div>
	<div style="clear: both"></div>
	<br>
	<a href="#" data-location="footer" class="app red-ui-button add-bottom-row"><?php _e( 'Add row to the bottom', 'touchsize' ) ?></a>
	<div style="clear: both"></div>
</div>
<div class="template-saver">
	<a href="#" class="button-primary" id="save-template">Save template</a>
</div>
