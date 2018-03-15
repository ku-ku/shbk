<?php
/**
 *
 * @package WordPress
 * @subpackage shabashka
 * @since Shabashka 1.0
 */

remove_filter( 'the_content', 'wpautop' );

function do_scripts() {
    $dojo = "1.13.0";
    wp_dequeue_script('jquery-masonry');
    wp_register_script('dojo', 'http://ajax.googleapis.com/ajax/libs/dojo/'.$dojo.'/dojo/dojo.js',array(),$dojo);
    wp_enqueue_script('dojo');
    wp_enqueue_style('bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' );
}
add_action('wp_enqueue_scripts', 'do_scripts');

/*
require get_stylesheet_directory().'/inc/pg_widget.php';
add_action( 'widgets_init', function(){
     register_widget( 'Pg_Widget' );
});
 * 
 

add_action('wp_mail_from_name', 'action_mail_from_name');
function action_mail_from_name($from_name){
    return mb_encode_mimeheader($from_name,"UTF-8", "B");
}
 * 
 */

?>
