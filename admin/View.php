<?php

class View {
    public static function LoadFile($name, array $args = array()) {
        $args = apply_filters('melibo_view_arguments', $args, $name);
		
		foreach ( $args AS $key => $val ) {
			$$key = $val;
		}
		
		load_plugin_textdomain( 'melibo-chatbot' );

		$file = plugin_dir_path( __FILE__ ) . '/inc/'. $name . '.php';

		include( $file );
    }
}