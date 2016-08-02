<?php

/*********************************
load javascript and css
**********************************/

function boxer_load_styles() {
		wp_enqueue_style('pstyle',  plugin_dir_url( __FILE__ ).'css/style.css');
		wp_enqueue_style('font-awesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css');

}
add_action('wp_enqueue_scripts', 'boxer_load_styles');

function boxer_load_js() {
		wp_register_script('ajax_script',  plugin_dir_url( __FILE__ ) . 'js/myscript.js');
	    //wp_localize_script( 'ajax_script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        

   wp_enqueue_script( 'jquery' );
   wp_enqueue_script( 'ajax_script' );
}
add_action('wp_enqueue_scripts', 'boxer_load_js');

?>