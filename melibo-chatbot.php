<?php

/**
 * Plugin Name: melibo Chatbot
 * Description: add your melibo chatbot to your WordPress for free
 * Version: 1.4.3
 * Author: Melibo
 * Author URI: www.melibo.de
 * Text Domain: melibo-chatbot
 * Domain Path: /languages/
 */
define( 'MELIBO_VERSION', '1.4.3');

require_once( plugin_dir_path( __FILE__ ) . '/admin/MeliboTranslation.class.php' );
require_once( plugin_dir_path( __FILE__ ) . '/admin/MeliboView.class.php' );
require_once( plugin_dir_path( __FILE__ ) . '/admin/MeliboValidation.class.php' );
require_once( plugin_dir_path( __FILE__ ) . '/admin/MeliboAjaxFunctions.class.php' );

require_once( plugin_dir_path( __FILE__ ) . '/admin/MeliboChatbot.class.php' );

$meliboChatbot = new MeliboChatbot();
