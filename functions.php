<?php
/**
 *
 * @package WordPress
 * @subpackage shabashka
 * @since Shabashka 1.0
 */

static $dojo = "1.13.0";
remove_filter( 'the_content', 'wpautop' );

function do_scripts() {
    global $dojo;
    wp_dequeue_script('jquery-masonry');
    wp_register_script('dojo', '//ajax.googleapis.com/ajax/libs/dojo/'.$dojo.'/dojo/dojo.js',array(),$dojo);
    wp_enqueue_script('dojo');
    wp_enqueue_style('bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' );
}
add_action('wp_enqueue_scripts', 'do_scripts');

function add_admin_scripts($hook){
/*    
    if ("post-new.php" != $hook){
        return;
    }
 * 
 */
    global $dojo;
    wp_register_script('adm-dojo', '//ajax.googleapis.com/ajax/libs/dojo/'.$dojo.'/dojo/dojo.js', array(), $dojo, true);
    wp_register_script('adm-adv', get_stylesheet_directory_uri(). '/js/admin.js',array('adm-dojo'),false,true);
    wp_enqueue_script('adm-dojo');
    wp_enqueue_script('adm-adv');
    wp_enqueue_style('adm-soria', '//ajax.googleapis.com/ajax/libs/dojo/'.$dojo.'/dijit/themes/soria/soria.css' );
}
add_action( 'admin_enqueue_scripts', 'add_admin_scripts');


require get_stylesheet_directory().'/inc/pg_widget.php';
add_action( 'widgets_init', function(){
     register_widget( 'Pg_Widget' );
});
 

function add_post_types(){
    $labels = array(
        'name' => 'ШАБАШКА',
        'singular_name' => 'ШАБАШКА',
	'add_new' => 'Добавить новую шабашку',
        'add_new_item' => 'Новая шабашка',
        'edit_item' => 'Редактировать',
        'new_item' => 'Новые',
        'view_item' => 'Посмотреть',
        'search_items' => 'Найти',
        'not_found' => 'Не найдено',
        'not_found_in_trash' => 'В корзине не найдено',
        'parent_item_colon' => '',
        'menu_name' => 'ШАБАШКИ' 
    );
    
    $args = array(
            'label' => null,
            'labels' => $labels,
            'description' => 'Список шабашек',
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => false,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-hammer',
            'capability_type' => 'post',
            'hierarchical' => false,
            'supports' => array('title','editor','thumbnail'),
            'taxonomies' => array(),
            'has_archive' => 'advs',
            'rewrite' => 'advs',
            'query_var' => true,
            'show_in_nav_menus' => null
	);
    register_post_type( 'advs', $args );
    
}
add_action('init', 'add_post_types');

function adv_fields_func($post){
    if (!($post->post_status=="auto-draft")){
        $city = get_post_meta( $post->ID,  'city', true );
        $district = get_post_meta( $post->ID,  'district', true );
        $price = get_post_meta( $post->ID,  'price', true );
    }
?>
    <input type="hidden" name="extra_fields_nonce" id="adv_extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
    <style type="text/css">
        .adv-info{display:none;}
        .adv-info label{display:block;margin-top: 1rem;}
    </style>
        
    <div id="adv-info-pane" class="soria adv-info">
        <h3>Местонахождение</h3>
        <label for="adv-city">Город</label>
        <div  data-dojo-type="dijit/form/FilteringSelect" id="adv-city" name="extra[city]" style="width:30rem;" value="<?php echo $city;?>" required></div>
        <br />
        <label for="adv-dis">Район</label>
        <div  data-dojo-type="dijit/form/FilteringSelect" id="adv-dis" name="extra[district]" style="width:30rem;" value="<?php echo $district;?>" required></div>
        <br />
        <label for="adv-price">Стоимость</label>
        <input data-dojo-type="dijit/form/ValidationTextBox" id="adv-price" name="extra[price]" type="text" value="<?php echo $price;?>"  required/>
    </div>    
<?php
}
function adv_extra_fields() {
    add_meta_box('adv-add', 'Дополнительно', 'adv_fields_func', 'advs', 'normal', 'high');
}
add_action('add_meta_boxes', 'adv_extra_fields', 1);

/*
add_action('manage_shop_posts_custom_column',  'add_show_shop_columns', 10, 2);

add_filter('manage_adv_posts_columns', 'add_adv_columns');
function add_show_shop_columns($name) {
    global $post;
    switch ($name) {
        case 'city':
            $city = get_post_meta($post->ID, 'city', true);
            if (empty($city)){
                echo '-';
            } else {    
                echo $city;
            }
            break;
        case 'mail':
            $mail = get_post_meta($post->ID, 'mail', true);
            if (empty($mail)){
                echo '-';
            } else {    
                echo '<a href="mailto:' . $mail . '">' . $mail . '</a>';
            }
            break;
    }
}

*/


/*
add_action('wp_mail_from_name', 'action_mail_from_name');
function action_mail_from_name($from_name){
    return mb_encode_mimeheader($from_name,"UTF-8", "B");
}
 * 
 */

?>
