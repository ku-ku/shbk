<?php
/**
 * Twenty Sixteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage shabashka
 * @since Shabashka 1.0
 */

remove_filter( 'the_content', 'wpautop' );

function do_scripts() {
    $dojo = "1.13.0";
    wp_dequeue_script('jquery-masonry');
    wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js',array(),'2.2.4');
    wp_enqueue_script('jquery');
    wp_register_script('dojo', 'http://ajax.googleapis.com/ajax/libs/dojo/'.$dojo.'/dojo/dojo.js',array(),$dojo);
    wp_enqueue_script('dojo');
    wp_register_script('bootstrap', 'http://netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js',array(),'3.3.2');
    wp_enqueue_script('bootstrap');
    wp_enqueue_style('bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css' );
/*    
    wp_enqueue_style('dijit', 'http://ajax.googleapis.com/ajax/libs/dojo/'.$dojo.'/dijit/themes/soria/soria.css' );
    wp_enqueue_style('dijitx', 'http://ajax.googleapis.com/ajax/libs/dojo/'.$dojo.'/dojox/form/resources/UploaderFileList.css' );
    $cat = get_category_by_slug('partners');
    if ($cat){
        wp_enqueue_style('dijitx-pager', 'http://ajax.googleapis.com/ajax/libs/dojo/'.$dojo.'/dojox/widget/Pager/Pager.css' );
    }
 * 
 */
}
add_action('wp_enqueue_scripts', 'do_scripts');

require get_stylesheet_directory().'/inc/pg_widget.php';
add_action( 'widgets_init', function(){
     register_widget( 'Pg_Widget' );
});

add_action('wp_mail_from_name', 'action_mail_from_name');
function action_mail_from_name($from_name){
    return mb_encode_mimeheader($from_name,"UTF-8", "B");
}

//[display-posts category="sed" posts_per_page="5" include_date="true" order="DESC" orderby="date"]
function display_posts( $atts ){
    $defs = array('category'=>'news','posts_per_page'=>-1,'include_date'=>false,'order'=>'ASC','orderby'=>'title');
    shortcode_atts( $defs, $atts );
    $conte = '';
    $args = array(  'post_type'=>'post',
                    'category_name'=>$atts['category'],
                    'post_status'=>'publish',
                    'posts_per_page'=>$atts['posts_per_page'],
                    'order'=>$atts['order'],
                    'orderby'=>$atts['title']);
    $q = new WP_Query($args);
    if ($q->have_posts()){
        $conte = '<ul class="display-posts">';
        while ($q->have_posts()){
            $q->the_post();
            
            $has_more = (preg_match( '/<!--more(.*?)?-->/', $q->post->post_content)===1);
            
            $conte .= '<li class="post-'.get_the_ID().'">';
            if ($atts['include_date']){
                $conte .= '<div class="col-md-1 post-date">'.get_the_date('d.m.Y').'</div>';
            }
            $conte .= '<div class="col-md-11 post-content"><h4>';
            if ($has_more){
                $conte .= '<a href="'.get_permalink().'">';
            }
            $conte .= get_the_title();
            if ($has_more){
                $conte .= '</a>';
            }
            $conte .= '</h4><div class="small">'.get_the_content().'</div></div><br class="clearfix" /></li>';
        }
        $conte .= '</ul>';
    }
    return $conte;

}
add_shortcode( 'display-posts', 'display_posts' );