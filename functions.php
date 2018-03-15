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

function add_admin_scripts($hook) {
/*    
    wp_register_script('adm_app', get_stylesheet_directory_uri().'/js/admin.js', array('jquery'));
    wp_enqueue_script( 'adm_app?'.$hook );
 * 
 */
}
add_action( 'admin_enqueue_scripts', 'add_admin_scripts' );


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
    <div class="adv-item">
        <label for="adv-city">Город</label>
        <input id="adv-city" name="extra[city]" type="text" value="<?php echo $city;?>"/>
        <label for="adv-dis">Район</label>
        <input id="adv-dis" name="extra[district]" style="width:auto;min-width: 480px;" type="text" value="<?php echo $district;?>"/>
        <label for="adv-price">Стоимость</label>
        <input id="adv-price" name="extra[price]" style="width:auto;min-width: 480px;" type="text" value="<?php echo $price;?>"/>
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
