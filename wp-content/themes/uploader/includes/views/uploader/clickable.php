<div class="form-group exc-form-field col-sm-6">
    <label for="contentType"><?php echo $label;?></label>
    <div class="content-button exc-clickable-wrapper" data-toggle="buttons">
        <?php echo $markup;?>
    </div>

    <?php if ( $help ) :?>
        <p><?php echo $help;?></p>
    <?php endif;?>
</div>