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


function adv_admin_js() {
    echo '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/fontawesome.css" integrity="sha384-q3jl8XQu1OpdLgGFvNRnPdj5VIlCvgsDQTQB6owSOHWlAurxul7f+JpUOVdAiJ5P" crossorigin="anonymous">';
    echo '<script type="text/javascript">
        dojoConfig = {
                parseOnLoad: false,
                async: true,
                tlmSiblingOfDojo: false,
                dojoBlankHtmlUrl: "'.get_stylesheet_directory_uri().'/blank.html",
                wpAjaxUrl:"'.admin_url('admin-ajax.php').'",
                wpNonce:"' . wp_create_nonce('ajax-nonce') .'"
        };
    </script>';
}
add_action('admin_head', 'adv_admin_js');

function add_admin_scripts($hook){
/*  TODO:  
    if ("post-new.php" != $hook){
        return;
    }
 * 
 */
    global $dojo;
    wp_enqueue_style('adm-soria', '//ajax.googleapis.com/ajax/libs/dojo/'.$dojo.'/dijit/themes/soria/soria.css' );
    wp_enqueue_style('bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' );
    wp_enqueue_style('adm-adm',   get_stylesheet_directory_uri(). '/js/admin.css',array('bootstrap','adm-soria'));
    wp_register_script('adm-dojo', '//ajax.googleapis.com/ajax/libs/dojo/'.$dojo.'/dojo/dojo.js', array(), $dojo, true);
    wp_register_script('adm-adv',  get_stylesheet_directory_uri(). '/js/admin.js',array('adm-dojo'),false,true);
    wp_enqueue_script('adm-dojo');
    wp_enqueue_script('adm-adv');
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
        $cats  = wp_get_post_categories( $post->ID );
        $cat = (sizeof($cats) > 0) ? $cats[0] : 0;
    }
?>
    <input type="hidden" name="extra_fields_nonce" id="adv_extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
    <div id="adv-info-pane" class="container-fluid soria adv-info">
        <div class="row">
            <div class="col-6">
                <h3><i class="fas fa-map-marker-alt"></i>&nbsp;Местонахождение</h3>
                <label for="adv-city">Город</label>
                <div  data-dojo-type="dijit/form/FilteringSelect" id="adv-city" name="extra[city]" 
                      data-dojo-props="store:adv.stories.cities"
                      style="width:30rem;" value="<?php echo $city;?>" 
                      required></div>
                <br />
                <label for="adv-dis">Район</label>
                <div  data-dojo-type="dijit/form/FilteringSelect" id="adv-dis" name="extra[district]"
                      data-dojo-props="store:adv.stories.dstrc"
                      style="width:30rem;" value="<?php echo $district;?>" required></div>
            </div>
        <div class="col-6">
            <h3><i class="fas fa-ruble-sign"></i>&nbsp;Стоимость</h3>
            <label for="adv-price">Стоимость, руб.</label>
            <input data-dojo-type="dijit/form/CurrencyTextBox" id="adv-price" name="extra[price]" value="<?php echo $price;?>"  
                   data-dojo-props="constraints:{fractional:false},currency:'руб.'" required />
            <label for="adv-cat">Категория</label>
            <div  data-dojo-type="dijit/form/FilteringSelect" id="adv-cat" name="extra[cat]" 
                  data-dojo-props="store:adv.stories.cats"
                  style="width:30rem;" value="<?php echo $cat;?>" required></div>
        </div>
    </div>    
<?php
}
function adv_extra_fields() {
    add_meta_box('adv-add', 'Дополнительно', 'adv_fields_func', 'advs', 'normal', 'high');
}
add_action('add_meta_boxes', 'adv_extra_fields', 1);

function add_extra_fields_update( $post_id ){
    if ( !wp_verify_nonce($_POST['extra_fields_nonce'], __FILE__) ) return false;
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return false;
	if ( !current_user_can('edit_post', $post_id) ) return false;

	if( !isset($_POST['extra']) ) return false;
        
	$extra = array_map('trim', $_POST['extra']);
        
        $post_type=$_POST['post_type'];
        $cats = array($extra['cat']);
        wp_set_post_categories( $post_id, $cats, false );
        
	foreach( $extra as $key=>$value ){
            if( empty($value) ){
                delete_post_meta($post_id, $key);
                continue;
            }
            update_post_meta($post_id, $key, $value);
	}
	return $post_id;
}
add_action('save_post', 'add_extra_fields_update', 0);



add_action('wp_ajax_info', 'adv_info_callback');
add_action('wp_ajax_nopriv_info', 'adv_info_callback');
function adv_info_callback(){
    $q = $_REQUEST["q"];
    $cat = false;
    
    switch ($q){
        case "city":
            $cat = get_category_by_slug("towns");
            $cat = ($cat) ? $cat->cat_ID : -1;
            break;
        case "dstrc":
            $cat = (int)$_REQUEST["city"];
            break;
        case "cats":
            $cat = get_category_by_slug("adverts");
            $cat = ($cat) ? $cat->cat_ID : -1;
            break;
    }
    
    $childs = get_terms( array(
        'taxonomy' => 'category',
        'parent'   => $cat,
        'childless'=> false,
        'orderby'  => 'name',
        'hierarchical' => false,
        'hide_empty' => false) );
            
            
    //get_term_children( $cat, 'category');
    $items  =  array();
    foreach($childs as $cat){
        $item = new stdClass();
        $item->id = $cat->term_id;
        $item->name = $cat->name;
        $items[] = $item;
    }
    
    $root = new stdClass();
    $root->identifier = 'id';
    $root->label = 'name';
    $root->items = $items;
    echo json_encode($root);
    wp_die();    
}       //adv_info_callback



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
