<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 
delete_option('melibo_activate');
delete_option('melibo_api_key');