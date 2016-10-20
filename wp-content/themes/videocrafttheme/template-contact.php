<?php
/*
  Template Name: Contact Page
 */
?>
<?php get_header(); ?>
<?php
$nameError = '';
$emailError = '';
$commentError = '';
if (isset($_POST['submitted'])) {
    if (trim($_POST['contactName']) === '') {
        $nameError = 'Please enter your name.';
        $hasError = true;
    } else {
        $name = trim($_POST['contactName']);
    }
    if (trim($_POST['email']) === '') {
        $emailError = 'Please enter your email address.';
        $hasError = true;
    } else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
        $emailError = 'You entered an invalid email address.';
        $hasError = true;
    } else {
        $email = trim($_POST['email']);
    }
    if (trim($_POST['comments']) === '') {
        $commentError = 'Please enter a message.';
        $hasError = true;
    } else {
        if (function_exists('stripslashes')) {
            $comments = stripslashes(trim($_POST['comments']));
        } else {
            $comments = trim($_POST['comments']);
        }
    }
    if (!isset($hasError)) {
        $emailTo = get_option('tz_email');
        if (!isset($emailTo) || ($emailTo == '')) {
            $emailTo = get_option('admin_email');
        }
        $subject = '[PHP Snippets] From ' . $name;
        $body = "Name: $name \n\nEmail: $email \n\nComments: $comments";
        $headers = 'From: ' . $name . ' <' . $emailTo . '>' . "\r\n" . 'Reply-To: ' . $email;
        mail($emailTo, $subject, $body, $headers);
        $emailSent = true;
    }
}
?>
<script>
jQuery(document).ready(function(){
	jQuery("#contactForm").validate();
});
</script>
<div class="page_container">
  <div class="container_24">
    <div class="grid_24">
      <div class="page-content">
        <div class="grid_18 alpha">
          <div class="content-bar">
               <div class="page-heading">
        <h1><span class="arrow"><?php the_title(); ?></span></h1>		
      </div>
            <div class="contact-page">
           <?php the_content(); ?>
            <?php if (isset($emailSent) && $emailSent == true) { ?>
<div class="thanks">
	<p>Thanks, your email was sent successfully.</p>
</div>
<?php } else { ?>
<?php if (isset($hasError) || isset($captchaError)) { ?>
	<p class="error">Sorry, an error occured. </p>
<?php } ?>
           <form action="<?php the_permalink(); ?>" id="contactForm" class="contactForm" method="post">
                     
                      <input type="text" name="contactName" id="contactName" value="<?php if (isset($_POST['contactName']))
	echo $_POST['contactName']; ?>" placeholder="Your  Name*" class="required requiredField" />
	<?php if ($nameError != '') { ?>
		<span class="error"> <?php echo $nameError; ?> </span>
		<br/>
	<?php } ?>
                       <label for="contactName">Name<span class="required">(Required)</span></label> 
                       <br /><br />
					   
                     <input type="text" name="email" id="email" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" placeholder="Your  Email*" class="required requiredField email" />
					 <?php if ($emailError != '') { ?>
		<span class="error"> <?php echo $emailError; ?> </span>
		<br/>
	<?php } ?>
					 
                      <label for="email">Mail<span class="required">(required)</span></label>
					  
                      <br/> <br/>
                    
                      <textarea name="comments" id="commentsText" rows="20" cols="30" placeholder="Message"  class="required requiredField"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea>
					    <?php if ($commentError != '') { ?>
		<span class="error"> <?php echo $commentError; ?> </span>
		<br/>
	<?php } ?>
                      <br /><br />
                          <div class="clear"></div>
                      <input class="submit" type="submit" value="Send Messegas"/>
                      <input type="hidden" name="submitted" id="submitted" value="true" />
                    </form>
					<?php } ?>
            </div>
          </div>
        </div>
        <div class="grid_6 omega">
          <!--Start Sidebar-->
        <?php get_sidebar('contact'); ?>
        <!--End Sidebar-->
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</div>
<?php get_footer(); ?>