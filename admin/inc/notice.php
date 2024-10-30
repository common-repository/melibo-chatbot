<?php if ( $type == 'error' ) : ?>
    <div class="notice notice-error">
        <p><?php echo esc_html($msg); ?></p>
    </div>
<?php elseif ( $type == 'no-api-key' ) : ?>
<div class="notice notice-info is-dismissible melibo-notice">
    <div class="melibo-notice-logo">
        <img src="<?php echo esc_url( plugins_url( './img/logo_transparent128x128.png', __FILE__ ) ); ?>" alt="melibo Logo" width="45">
    </div>
    <div class="melibo-notice-content">
        <p>
            <?php echo __('Hello! Thank you very much for downloading our plugin and using it for your website!', MeliboChatbot::PLUGIN_NAME)?>
            <br />
            <?php echo __('You can test melibo for free.', MeliboChatbot::PLUGIN_NAME)?>
        </p>
        <p>
            <a target="_blank" href="https://editor.melibo.de/registration" class="melibo-button"><?php echo __('Start now!', MeliboChatbot::PLUGIN_NAME); ?></a>
        </p>
    </div>
</div>
<?php endif; ?>