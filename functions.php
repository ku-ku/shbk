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
    wp_enqueue_style('soria', '//ajax.googleapis.com/ajax/libs/dojo/'.$dojo.'/dijit/themes/soria/soria.css' );
    wp_enqueue_style('bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' );
    wp_register_script('dojo', '//ajax.googleapis.com/ajax/libs/dojo/'.$dojo.'/dojo/dojo.js',array(),$dojo);
    wp_enqueue_script('dojo');
    wp_register_script('adv', get_stylesheet_directory_uri().'/js/adv.js?v=1.0.3',array('dojo'));
    wp_enqueue_script('adv');
}
add_action('wp_enqueue_scripts', 'do_scripts');


function adv_admin_js() {
    echo '<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js" integrity="sha384-SlE991lGASHoBfWbelyBPLsUlwY1GwNDJo3jSJO04KZ33K2bwfV9YBauFfnzvynJ" crossorigin="anonymous"></script>';
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
 

function adv_init(){
    add_theme_support('post-thumbnails');
    add_image_size('adv-thumb', 262, 182);
    add_image_size('adv-middle', 386,328);
    
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
add_action('init', 'adv_init');

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

function doAdvWrite($postId){
    $post = get_post($postId);
    if ($post){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $res = new stdClass();
            $user = get_current_user();
            if ( 0 == $user->ID ){
                $res->err=-1;
                $res->msg='User is`t authorized';
            } else {
                $data = array(
                    'comment_post_ID' => $post->ID, // to which post the comment will show up
                    'comment_content' => $_POST["msg"],
                    'user_id' => $user->ID
                );
                $comment_id = wp_new_comment( $data );
                $res->commId = $comment_id;
                $res->err = is_wp_error($comment_id) ? 1 : 0;
            } 
            echo json_encode($res);
            
        } else {
            $user = get_user_by('ID', $post->post_author);
            echo '<h4>'.$post->post_title.'</h4>';
            echo '<p>Сообщение для '.$user->display_name.'</p>';
            echo '<textarea data-dojo-type="dijit/form/SimpleTextarea" name="msg" 
                            data-dojo-props="required:true"
                            required placeholder="Сообщение" class="form-control" style="width:100%;"></textarea>';
            echo '<div style="padding:1rem;margin-top:1rem;text-align:right;" class="btn-group d-block">'
               . '<button class="btn btn-outline-primary btn-sm btn-send"><i class="fas fa-paper-plane"></i>&nbsp;Отправить</button>'
               . '<button class="btn btn-outline-primary btn-sm btn-close"><i class="far fa-times-circle"></i>&nbsp;Закрыть</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">К сожалению по Вашему запросу ничего не найдено</div>';
    }
    
}   //doAdvWrite
function doAdvCallInfo($postId){
    $post = get_post($postId);
    if ($post){
        echo '<h4>'.$post->post_title.'</h4>';
        $user = get_user_by('ID', $post->post_author);
        //var_dump($user);
        $tel  = get_user_meta($post->post_author,'phone');
        if (empty($tel)){
            echo '<p>К сожалению пользователь '.$user->display_name.' не оставил телефон для связи.</p>';
        } else{
            echo '<p>Вы можете связаться по телефону '. $tel .', '.$user->display_name.'</p>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">К сожалению по Вашему запросу ничего не найдено</div>';
    }
    echo '<div style="padding:1rem;margin-top:1rem;text-align:right;"><button class="btn btn-outline-primary btn-sm btn-close"><i class="far fa-times-circle"></i>&nbsp;Закрыть</button></div>';
}   //doAdvCallInfo

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
        case "call":
            doAdvCallInfo($_REQUEST["id"]);
            break;
        case "write":
            doAdvWrite($_REQUEST["id"]);
            break;
    }
    
    if ($cat){
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
    }
    wp_die();
}       //adv_info_callback


function get_in_translate($string){
    $arStrES = array("ае","уе","ое","ые","ие","эе","яе","юе","ёе","ее","ье","ъе","ый","ий");
    $arStrOS = array("аё","уё","оё","ыё","иё","эё","яё","юё","ёё","её","ьё","ъё","ый","ий");        
    $arStrRS = array("а$","у$","о$","ы$","и$","э$","я$","ю$","ё$","е$","ь$","ъ$","@","@");
                    
    $replace = array("А"=>"A","а"=>"a","Б"=>"B","б"=>"b","В"=>"V","в"=>"v","Г"=>"G","г"=>"g","Д"=>"D","д"=>"d",
                "Е"=>"Ye","е"=>"e","Ё"=>"Ye","ё"=>"e","Ж"=>"Zh","ж"=>"zh","З"=>"Z","з"=>"z","И"=>"I","и"=>"i",
                "Й"=>"Y","й"=>"y","К"=>"K","к"=>"k","Л"=>"L","л"=>"l","М"=>"M","м"=>"m","Н"=>"N","н"=>"n",
                "О"=>"O","о"=>"o","П"=>"P","п"=>"p","Р"=>"R","р"=>"r","С"=>"S","с"=>"s","Т"=>"T","т"=>"t",
                "У"=>"U","у"=>"u","Ф"=>"F","ф"=>"f","Х"=>"Kh","х"=>"kh","Ц"=>"Ts","ц"=>"ts","Ч"=>"Ch","ч"=>"ch",
                "Ш"=>"Sh","ш"=>"sh","Щ"=>"Shch","щ"=>"shch","Ъ"=>"","ъ"=>"","Ы"=>"Y","ы"=>"y","Ь"=>"","ь"=>"",
                "Э"=>"E","э"=>"e","Ю"=>"Yu","ю"=>"yu","Я"=>"Ya","я"=>"ya","@"=>"y","$"=>"ye");
                
    $string = str_replace($arStrES, $arStrRS, $string);
    $string = str_replace($arStrOS, $arStrRS, $string);
        
    return iconv("UTF-8","UTF-8//IGNORE",strtr($string,$replace));
}       //get_in_translate


function adv_get_user($usi){
    $us = get_user_by('email', $usi->eml);
    if ($us) {
        if (strcasecmp($us->get('first_name'), $usi->name)==0){
            return $us; //TODO:
        } else {
            return new WP_Error('existing_user_email', 'This e-mail already exist by another user'); 
        }
    }
    
    $login = $usi->eml; //get_in_translate($usi->name);  //TODO:!
    $pw = wp_generate_password(12);
    $args = array(
        'user_login'=>$login,
        'user_pass'=>$pw,
        'user_email'=>$usi->eml,
        'display_name'=>$usi->name,
        'first_name'=>$usi->name,
        'role' => 'author'    
    );
    $userId = wp_insert_user($args);
    if ( is_wp_error($userId) ){
        return $userId;
    }
    if (strlen($usi->tel)>0){
        update_user_meta($userId, 'phone', $usi->tel);
    }    
    wp_new_user_notification( $userId, null, 'both');
    $creds = array (
	'user_login'    => $login,
	'user_password' => $pw,
	'remember'      => true
    );
    wp_signon( $creds, false);
    
    return $userId;
   
}       //adv_get_user

function adv_save_adv(){
    $authorId;
    $cuser = wp_get_current_user();
    $res = new stdClass();
    if ( 0 == $cuser->ID ){
        $usi = new stdClass();
        $usi->name = $_REQUEST["name"];
        $usi->eml  = $_REQUEST["eml"];
        $usi->tel  = $_REQUEST["tel"];
        $cuser = adv_get_user($usi);
        if ((!$cuser)||is_wp_error($cuser)){
            $res->id=-1;
            $res->err=1;
            $res->msg="User ".$usi->name." (".$usi->eml.") can`t be registered";
            if (is_wp_error($cuser)){
                $res->msg .= ": ".$cuser->get_error_message();
            }
            echo json_encode($res);
            wp_die();
        }
        $authorId = $cuser;
    } else {
        $authorId = $cuser->ID;
    }
    $res->userId = $authorId;
    
    $meta = array(
        "city"=>$_REQUEST["city"],
        "district"=>$_REQUEST["distr"],
        "onmsg"=>$_REQUEST["onmsg"],
        "price"=>$_REQUEST["pr"]
    );
    
    $post = array(
      'comment_status' => 'closed',
      'ping_status' => 'closed',
      'post_author' => $authorId,
      'post_category' => array($_REQUEST["acat"]),
      'post_content' => $_REQUEST["msg"],
      'post_status' => 'pending',
      'post_title' => $_REQUEST["tt"],
      'post_type' => 'advs',
      'ping_status' => get_option('default_ping_status'), 
      'meta_input' => $meta
    );
    //TODO: ID for update
    $postId = wp_insert_post($post);
    if (is_wp_error($postId)){
        $res->id=-1;
        $res->err=1;
        $res->msg="Can`t reg ADV: ". $postId->get_error_message();        
    } else {
        wp_set_post_categories( $postId, array($_REQUEST["acat"]), false );
        $res->id = $postId;
        $res->err= 0;
        if(isset($_FILES)){
            $res->files = $_FILES;
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );
            $res->thumbId = media_handle_upload('upload', $postId );
            if (is_wp_error($res->thumbId)){
                $res->errThumb = 1;
                $res->errThumbMsg = $res->thumbId->get_error_message();
            } else if ( set_post_thumbnail( $postId, $res->thumbId ) ){
                $res->errThumb = 0;
                $res->thumbUrl = wp_get_attachment_image_url($res->thumbId,'adv-middle'); //TODO: by resp-image
            }
        }
    }
    
    echo json_encode($res);
}       //adv_save_adv

function adv_feedback_callback(){
    $q = $_REQUEST["q"];
    $nonce = $_REQUEST["nonce"];
    if (!wp_verify_nonce( $nonce, 'ajax-nonce' )){
        wp_send_json_error("Bad request");
        wp_die();
    }
    
    if (!isset($q)){
        wp_send_json_error();
    } else {
        switch($q){
            case "save-adv":
                adv_save_adv();
                break;
        }
    }
    wp_die();
}       //adv_feedback_callback
add_action('wp_ajax_feedback', 'adv_feedback_callback');
add_action('wp_ajax_nopriv_feedback', 'adv_feedback_callback');


function adv_print(){
    $args = array(
        'post_type' => 'advs',
        'post_status' => 'publish',
        'posts_per_page' => 12
    );
    if (isset($_REQUEST["acat"])){
        $args["cat"] = $_REQUEST["acat"];
    }
    if (isset($_REQUEST["ad"])){
        $args['meta_query'] = array(
            'relation' => 'and',
            'state_clause' => array('key' => 'district','value' => $_REQUEST["ad"])
        );
    } else if (isset($_REQUEST["ac"])){
        $args['meta_query'] = array(
            'relation' => 'and',
            'state_clause' => array('key' => 'city','value' => $_REQUEST["ac"])
        );
    }
    
    $n = 0;
    $q = new WP_Query( $args );
    if ( $q->have_posts() ) {
        while ( $q->have_posts() ) {
            if ( $n == 0){
                echo '<div class="row">';
            }
            if ( ($n > 0) && ($n % 4) == 4){
                $n = 0;
                echo '</div><!--.row-->';
            }
            $q->the_post();
            $postId = get_the_ID();
            echo '<div class="col-md-3 col-sm-12"><div class="card" data-adv-id="' . $postId .'"><!--'.$n.'-->';
            $thumb_id = get_post_thumbnail_id( $postId );
            $thumb = get_stylesheet_directory_uri(). "/imgs/adv-def-image.png";
            if ( $thumb_id ){
                $thumb = wp_get_attachment_image_url( $thumb_id, 'adv-thumb');
            }
            $price = get_post_meta( $postId,  'price', true );
            $city = get_post_meta( $postId,  'city', true );
            $district = get_post_meta( $postId,  'district', true );
            $place = get_term_by( 'id', ($district) ? $district : $city, 'category'); //TODO: optimize
            echo '<img class="card-img-top" src="' . $thumb .'" alt="' . get_the_title(). '"/>';
            echo '<div class="card-body"><h5 class="card-title">'.get_the_title().'</h5>';
            echo '<div class="card-text"><div class="adv-text">' .get_the_content() . '</div>'
                 . ( ($place) ? '<div class="adv-place">'. $place->name . '</div>' : '')
                 . '<div class="adv-price">' . $price . '</div>'
                 . '<div class="adv-dt">'.get_the_date().'</div></div>'
                 . '<div class="row card-buttons"><div class="col-6"><button class="btn btn-secondary btn-sm btn-block btn-call">Позвонить</button></div>'
                 . '<div class="col-6"><button class="btn btn-secondary btn-sm btn-block btn-write">Написать</button></div>'
                 . '</div></div></div><!--.card-->'
                 . '</div><!--.col-->';
            $n++;
        }
        if ($n != 0){
            echo '</div>';//.row
        }
    } else {
        echo '<div class="alert alert-warning" role="alert"><i class="fas fa-exclamation-triangle"></i>&nbsp;По заданным Вами критериям ничего на найдено. Попробуйте изменить условия и выполнить поиск заново.</div>';
    }
    wp_reset_postdata();    
}   //adv_print

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
