<?php
/*
  If you would like to edit this file, copy it to your current theme's directory and edit it there.
  WPUF will always look in your theme's directory first, before using this default template.
 */
?>
<div class="login" id="wpuf-login-form">

    <?php
    $message = apply_filters( 'login_message', '' );
    if ( ! empty( $message ) ) {
        echo $message . "\n";
    }
    ?>

    <?php FRONTEND_USER()->login->show_errors();
    FRONTEND_USER()->login->show_messages(); ?>

    <?php echo FRONTEND_USER()->login->user_get_action_links( array( 'login' => false ) ); ?>
</div>
