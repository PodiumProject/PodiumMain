<?php
$renam_read1 = get_option('readlist_title');

if ($renam_read1) {
	if (array_key_exists('title', $renam_read1) && isset($renam_read1['title']) ) 
	{
		$renam_read = $renam_read1['title'];
	}
}
if (!$renam_read) {
	$renam_read = 'Readlist';
}

$user = wp_get_current_user();

$approve = get_user_meta($user->ID , 'approve-user' ,true);
if ( is_user_logged_in() )
{

	if ( $approve && !$approve == 1) 
	{
		echo "<h4>Your Registration is Pending approval.

		Please check back in few minutes!</h4>";
		return;
	}
	if(isset($_GET['id']))
	{ 		
		global $wpdb;
		$jk=$_GET['id']; 
		$vv=$wpdb->get_results("select * from " .$wpdb->prefix."readlists where id ='".$jk."'" );
		global $wpdb;
		$yu=$wpdb->get_results("select * from " .$wpdb->prefix."user_readlist where readlists_name ='".$vv[0]->readlist_name."'" );
		if(count($yu)== '0')
		{
			echo "<p class=test>This '".$renam_read."' has no items </p> ";
		}else
		{ ?>
			<style type="text/css">
				article {
					background-color: #f5f5f5 !important;
				}
				.readlist_item .entry-title {
					font-size: 20px;
					font-weight: 700;
					line-height: 3;
					text-align: center;
				}
				.show_img {
					text-align: center;
				}
				.readlist_item article {
					background: #fff none repeat scroll 0 0 !important;
					border: 1px solid #c1c1c1;
					display: inline-block;
					margin-bottom: 2%;
					margin-left: 1.5%;
					position: relative;
					vertical-align: top;
					width: 32%;
				}
				.more-link.left {
					float: left;
				}
				.more-link.right {
					float: right;
					position: relative;
					z-index: 99999999999999;
				}
				.show_content {
					font-size: 16px;
					height: 145px;
					line-height: 2;
					padding: 0 15px;
					text-align: center;
				}
				.content-style-social a {
					background: #faf1e0 none repeat scroll 0 0;
					border-bottom: 0 none;
				}
				.content-style-social a > span {
					margin-left: 14px;
					font-size: 11px;
				}
				.show_img img{
					height: 152px;
					max-width: 260px;
				}
				a.more-link {
					background-image: url("<?php echo plugins_url('assets/img/read_more.png',__FILE__); ?>");
					background-position: 10px center;
					background-repeat: repeat-x;
					display: block;
					font-size: 12px;
					font-weight: 400;
					border-bottom: 0px; 
				}
				button.more-link {
					/*background-image: url("<?php echo plugins_url('assets/img/read_more.png',__FILE__); ?>");*/
					background-position: 10px center;
					background-repeat: repeat-x;
					display: block;
					font-size: 12px;
					font-weight: 400;
					border: none; 
				}
				.uk-link, a {
					color: #1d8acb;
					cursor: pointer;
					text-decoration: none;
				}
				.uk-link, a .button.more-link {
					color: #1d8acb;
					cursor: pointer;
					text-decoration: none;
				}

				a.more-link span {
					background: #ffffff none repeat scroll 0 0;
					border: 1px solid;
					border-radius: 4px;
					display: block;
					line-height: 32px;
					margin: 13px 15px;
					text-align: center;
					transition: all 0.3s ease-in-out 0s;
					width: 78px;
				}
				button.more-link span {
					background: #ffffff none repeat scroll 0 0;
					border: 1px solid;
					border-radius: 4px;
					display: block;
					line-height: 32px;
					margin: 0 auto;
					text-align: center;
					transition: all 0.3s ease-in-out 0s;
					width: 86px;
				}
				.readlist_item a.more-link::after {
					content: unset;
				}
				.readlist_item button.more-link::after {
					content: unset;
				}

				.uk-link:hover, a:hover {
					color: #0b5f90;
					text-decoration: none;
				}
				a:hover {
					color: #ab182f !important;
				}
				button:hover {
					color: #ab182f !important;
				}
				a.more-link:hover > span {
					border-radius: 14px;
					transition: all 0.3s ease-in-out 0s;
				}
				button.more-link:hover > span {
					border-radius: 14px;
					transition: all 0.3s ease-in-out 0s;
				}
				.morph-content .content-style-social a {
					line-height: 16px;
				}
			
			</style>
			<link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/font-awesome.min.css', __FILE__ );?>">
			<link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'assets/css/normalize.css', __FILE__ );?>" />
			<link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'assets/css/demo.css', __FILE__ );?>" />
			<link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'assets/css/component1.css', __FILE__ );?>" />
			<link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'assets/css/content.css', __FILE__ );?>" />
			<script src="<?php echo plugins_url( 'js/modernizr.custom.js', __FILE__ );?>"></script>
			<div class="readlist_item">
			<?php 
			foreach ($yu as $key => $value) 
			{
				$post = get_post($value->post_id);
				$id = $post->ID;
				$permalink = get_permalink( $id );
				?>
				<article class="type-postformat-standard">
					<header class="entry-header">
						<h2 itemprop="headline" class="entry-title">
							<a rel="bookmark" href="<?php echo $permalink; ?>"><?php echo $post->post_title; ?></a>
						</h2> 
					</header>
					
					<div class="show_img">
						<?php
						$thumb = get_the_post_thumbnail( $id, array(197 , 120)); 
						echo $thumb;
						if (!$thumb) 
						{
							?>
								<img width="131" height="120" alt="asa" class="attachment-197x120 wp-post-image" src="<?php echo plugins_url('classes/images/no-image.png' ,__FILE__); ?>">
							<?php
						}
						?> 
					</div>
					<style type="text/css">
						
						.morph-button_<?php echo $id; ?> .morph-content a {
							pointer-events: initial !important;
						}
					</style>
					<div class="show_content">
						<?php 
						$excerpt = $post->post_content;
						$excerpt = strip_shortcodes($excerpt);
						$excerpt = strip_tags($excerpt);
						$the_str = substr($excerpt, 0, 150);
						echo $the_str;
						?>
					</div>
					<script src="<?php echo plugins_url( 'js/classie.js', __FILE__ );?>"></script>
					<script src="<?php echo plugins_url( 'js/uiMorphingButton_inflow.js', __FILE__ );?>"></script>
					<div class="morph-button_<?php echo $id; ?> morph-button-inflow morph-button-inflow-2">
						<button type="button" class="more-link left left_<?php echo $id; ?>">
							<span>SHARE THIS</span>
						</button>
						<div class="morph-content">
							<div>
								<div class="content-style-social" >
									<a class="close_time close_time_<?php echo $id; ?>" href="#">
										<i class="fa fa-times"></i>
									</a>
									<a class="twitter-share-button"
									href="https://twitter.com/home?status=<?php echo $permalink; ?>" target="_blank">
										<i class="fa fa-twitter"></i>
										<span>Share on Twitter</span>
									</a>
									<a href='https://www.facebook.com/sharer/sharer.php?u=<?php echo $permalink; ?>' target="_blank">
										<i class="fa fa-facebook"></i>
										<span>Share on Facebook</span>
									</a>
									<a class="googleplus" href="https://plus.google.com/share?url=<?php echo $permalink; ?>" target="_blank">
										<i class="fa fa-google-plus"></i>
										<span>Share on Google+</span>
									</a>
								</div>
							</div>
						</div>
					</div>
				
					<script>
						(function($) {	
							new UIMorphingButton( document.querySelector( '.morph-button_<?php echo $id; ?>' ) );
							// var $ =jQuery;
							//$('.readlist_item article .morph-button_<?php echo $id; ?> .active .open').css('height' , '200px');
							$('.close_time').on('click',function(e){
								e.preventDefault();
								$('.readlist_item article .morph-button_<?php echo $id; ?>').removeClass('active open');
							});	
							// var $ = jQuery;
							$('.left_<?php echo $id; ?>').on('click',function(){
								$('.right_<?php echo $id; ?>').css('position','unset');
							});
							$('.close_time_<?php echo $id; ?>').on('click',function(){
								$('.right_<?php echo $id; ?>').css('position','relative');
							});
						})(jQuery);
					</script>
					<a class="more-link right right_<?php echo $id; ?>" href="<?php echo $permalink; ?>">
						<span>READ MORE</span>
					</a>
				</article>
				<?php
			}
		}
		$current_user = wp_get_current_user();

		$um=$current_user->user_email;
		$user_id = $current_user->ID; ?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery(function($) 
				{
					jQuery(".trial").click(function(event)
					{
						var wname='<?php echo $vv[0]->readlist_name; ?>';
						var trial='trial';
						event.preventDefault();
						jQuery.ajax({
							type: "post",
							url: '<?php echo admin_url("/admin-ajax.php"); ?>',
							data: { 
								action: 'action_shrt',
								post_id : this.id,
								trial:trial,
								uid:<?php echo $user_id ; ?>,
								wname:this.name,
							}, 
							success: function(data) {
								location.reload();
							},
							error:function(res) {
								console.log(res);
							}
						}); 
					});

					jQuery("a.topopup").click(function(e) {
						e.preventDefault();
						loading(); 
						setTimeout(function(){
							loadPopup(); 
						}, 500); 
						return false;
					});

					jQuery("div.close").hover(
						function() {
							$('span.ecs_tooltip').show();
						},
						function () {
							$('span.ecs_tooltip').hide();
						}
					);

					jQuery("div.close").click(function() {
						disablePopup();
					});

					jQuery(this).keyup(function(event) {
						if (event.which == 27) {
							disablePopup();
						}      
					});

					jQuery("div#backgroundPopup").click(function() {
						disablePopup();
					});

					jQuery('a.livebox').click(function() {
						alert('Hello World!');
						return false;
					});


					/************** start: functions. **************/
					function loading() {
						jQuery("div.loader").show();  
					}
					function closeloading() {
						jQuery("div.loader").fadeOut('normal');  
					}

		    		var popupStatus = 0; // set value

		    		function loadPopup() {
				        if(popupStatus == 0) { // if value is 0, show popup
				            closeloading(); // fadeout loading
				            jQuery("#toPopup").fadeIn(0500); // fadein popup div
				            jQuery("#backgroundPopup").css("opacity", "0.7"); // css opacity, supports IE7, IE8
				            jQuery("#backgroundPopup").fadeIn(0001);
				            popupStatus = 1; // and set value to 1
				        }    
				    }

				    function disablePopup() {
				        if(popupStatus == 1) { // if value is 1, close popup
				        	jQuery("#toPopup").fadeOut("normal");  
				        	jQuery("#backgroundPopup").fadeOut("normal");  
				            popupStatus = 0;  // and set value to 0
				        }
				    }
				    /************** end: functions. **************/
				});
			});
		</script>
		<?php	
	}elseif (isset($_GET['email'])) 
	{
		$current_user = wp_get_current_user();
		$um=$current_user->user_email;
		?>
		<form method="post" id="send_email">
			<div class="email-content">
				<div class="left-column">
					<div id="your-friends-details">
						<h3>To</h3>
						<p>
							<div class="mail_label" for="email-friend-name">Name</div>
							<input type="text" required name="email-friend-name1" id="email-friend-name1" class="email_input">
						</p>
						<p>
							<div class="mail_label" for="email-friend-email">Email Address</div>
							<input type="email" required name="email-friend-email1" id="email-friend-email1" class="email_input">
						</p>
					</div>
					<div id="your-details">
						<h3>From</h3>
						<p>
							<div class="mail_label" for="email-your-name">Name</div>
							<input type="text" value="" required name="email-your-name" id="email-your-name1" class="email_input">
						</p>
						<p>
							<div class="mail_label" for="email-your-email">Email Address</div>
							<input type="text" value="<?php echo $um; ?>" name="email-your-email" id="email-your-email1" class="email_input">
						</p>
					</div>

				</div>
				<div class="right-column">
					<div id="lovelist-message">
						<h3><?php echo $renam_read; ?> Items</h3>
						<?php 
						global $wpdb;
						$id = $_GET['email'];

						$t=$wpdb->get_results("select * from ".$wpdb->prefix.'readlists'." where id = '".$id."' ;");
						foreach($t as $key =>$value)
						{	
							$rr=$wpdb->get_results("select * from " .$wpdb->prefix."user_readlist  where readlists_name='".$value->readlist_name."';"); 
							echo "<input type='hidden' name='readlist_name' value='".$value->readlist_name."' >";
							$i = 1 ;
							foreach ($rr as $kk) {
								$post_id = $kk->post_id;
								$post = get_post($post_id);

								echo $i.".  ".$post->post_title."<br>";
								echo "<input type='hidden' name='items_$i' value='".$post_id."' >";
								$i++;
							}
						}
						?>
					</div><!-- Copyright (c) 2007 [Sur http://expressica.com] -->
				</div>
				<input align="center" type="submit" name="send" value="Send" class="send"   id="send">
			</div>
		</form>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('#send_email').on('submit',function(event){
					event.preventDefault();
					var values = jQuery('#send_email').serialize();
					// alert(values);
					jQuery.ajax(
					{
						type: "post",
						url: '<?php echo admin_url("/admin-ajax.php"); ?>',
						data: { 
							action: 'email_action',
							fields : values
						}, 
						success: function(data) {
							//alert(data);
							 alert('Email Send Successfully');
							//window.location.href = arr[0];
						},
						error:function(res) {
							console.log(res);
						} 
					}); 
				});
			});
		</script>
		<?php
	}else
	{ ?>
		<!-- <h1 class="test"><?php// echo $renam_read; ?></h1> -->
		<div class="tabe">
			<table align="center">
				<tr>
					<th><?php echo $renam_read;; ?>s</th>
					<th colspan="3">Action</th>
				</tr>
				
				<?php global $wpdb;
				$t=$wpdb->get_results("select * from ".$wpdb->prefix.'readlists');
	
				foreach($t as $key =>$value)
				{
					$rr=$wpdb->get_results("select count(*) as te from " .$wpdb->prefix."user_readlist  where readlists_name='".$t[$key]->readlist_name."';"); ?>
					
					<tr>
						<td align="center">
							<?php echo $t[$key]->readlist_name.'('.$rr[0]->te.')'; ?>
						</td>
						<td>
							<?php
							$bb=$t[$key]->id;
							echo '<input type="button" name="del" value="Delete" class="trial button" id="'.$bb.'">'; ?>
						</td>
						<td>
							<?php echo '<button id="'.$bb.'" class="editt button" >Edit/View</button>'; ?>
						</td>
						<td>
							<?php echo '<button id="'.$bb."_".$rr[0]->te.'" class="emaill button">Email</button>'; ?>

						</td>
					</tr>
					<style type="text/css">
					.rty {
						display: none;
					}
					</style>
					<?php
				} 
				$user_id=wp_get_current_user();
				$uid= $user_id->ID;?>
				<script type="text/javascript">
					jQuery(document).ready(function(){
						var uid ='<?php echo $uid; ?>';
						jQuery(".shrt").click(function(event){
							event.preventDefault();
							jQuery.ajax({
								type: "post",
								url: '<?php echo admin_url("/admin-ajax.php"); ?>',
								data: { 
									action: 'action_shrt',
									post_id : this.id
								}, 
								success: function(data) {
									location.reload();
								},
								error:function(res) {
									alert('error ' );
									console.log(res);
								}
							}); 
						});
						jQuery(document).on('click' , '.editt' , function(event){
							event.preventDefault();
							var y= this.id;
							var tc="<?php echo plugins_url( 'edit.php', __FILE__ );?>";
							var url      = window.location.href;
							if(url.indexOf('?') > -1)
							{
								window.location = url+'&id='+y;
							}else{
								window.location = url+'?id='+y;
							}
						});	 
						jQuery(document).on('click' , '.emaill' , function(e){
							e.preventDefault();
							var z= this.id;
							var x = z.split('_');
							var y = x[0];
							var a = x[1];
							if (a == 0 || a == 'Null') {
								alert('Add Items In <?php echo $renam_read; ?> to Email');
							}else{
								var url      = window.location.href;
								if(url.indexOf('?') > -1)
								{
									window.location = url+'&email='+y;
								}else{
									window.location = url+'?email='+y;
								}
							};
						});  
					});
				</script>
			</table>
		</div>
		<div class="tabe">
			<table align="center">
				<th>Create A New <?php echo $renam_read; ?></th>
				<tr class="cer">
					<td>
						<?php
						$renam_read1 = get_option('readlist_title');

						if ($renam_read1) {
							if (array_key_exists('title', $renam_read1) && isset($renam_read1['title']) ) 
							{
								$renam_read = $renam_read1['title'];
							}
						}
						if (!$renam_read) {
							$renam_read = 'Readlist';
						}
						?>
						<!-- <h1 class="entry-title">Create A New <?php //echo $renam_read; ?></h1> -->
						<form class="readlist_form_2" method="post" action=""> 
							<input type="hidden" value="" class="post_id_read">
							<input type="hidden" value="" class="post_type_read">
							<input type="text" name="cwish" placeholder="Enter <?php echo $renam_read; ?> name">
							<input type="submit" name="sub" value="Create">
						</form>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}
}else
{
	echo "<h4>Please <a href='".wp_login_url()."' title='Login'>Logged in</a></h4>";
}


