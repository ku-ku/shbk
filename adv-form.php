<?php
/**
 * The template for displaying the front-page content
 * @package WordPress
 * @subpackage Shabashka
 * @since Shabashka 1.0
 * Template Name: Шаблон формы объявления
 */
    get_header();
    $city = $_REQUEST["ac"];
    $distr= $_REQUEST["ad"];
    $acat = $_REQUEST["acat"];
    $user = wp_get_current_user();
    if ($user->ID > 0){
        $q = new WP_Query( array(
            'author'=>$user->ID,
            'post_type'=>'advs',
            'post_status'=>'any',
            'posts_per_page'=>1,
            'order'=>'DESC',
            'orderby'=>'ID'
            ) );
        if ($q->have_posts()){
            while ( $q->have_posts() ) {
		$q->the_post();
                if (!isset($city)){
                    $city = get_post_meta( get_the_ID(),  'city', true );
                }
                if (!isset($distr)){
                    $city = get_post_meta( get_the_ID(),  'district', true );
                }
                if (!isset($acat)){
                    $cats  = wp_get_post_categories( get_the_ID() );
                    $acat = (sizeof($cats) > 0) ? $cats[0] : 0;
                }
            }
        }
    }
?>
<div class="container soria conte-adv invisible" id="conte-adv">
    <div class="row" style="margin:2rem 0;">
        <div class="col-md-3">
            <h6>ВЫБЕРИТЕ КАТЕГОРИЮ</h6>
                <div id="adv-acat" 
                     data-dojo-type="dijit/form/FilteringSelect"
                     data-dojo-props="store:adv.stories.cats"
                     style="width:100%;"
                     class="form-control"
                     required
                     value="<?php echo $acat;?>"></div>
            <h6>ВЫБЕРИТЕ МЕСТО</h6>
                <div id="adv-city" 
                     data-dojo-type="dijit/form/FilteringSelect"
                     data-dojo-props="store:adv.stories.cities"
                     class="form-control"
                     style="width:100%;"
                     required
                     placeholder="Город"
                     value="<?php echo $city;?>"></div><br />
                <div id="adv-dis" 
                     data-dojo-type="dijit/form/FilteringSelect"
                     data-dojo-props="store:adv.stories.dstrc,required:false"
                     class="form-control"
                     placeholder="Район"
                     style="margin-top:0.25rem;width:100%;"
                     value="<?php echo $distr;?>"></div>
        </div>
        <div class="col-md-9">
            <div class="alert d-none" role="alert" id="adv-info-msg"></div>
            <div class="form-adv-pane soria" id="form-adv-pane">
                <form data-dojo-type="dijit/form/Form" id="adv-form"
                      enctype="multipart/form-data">
                    <div class="form-group row" style="margin-left:0;margin-right:0">
                        <div class="d-none">
                        </div>
                        <div class="adv-contacts">
                            <div class="row">
                                <div class="col-6 align-self-center"><h6>КОНТАКТНАЯ ИНФОРМАЦИЯ</h6></div>
                                <div class="col-6 align-self-center"><h6 style="font-weight:400;">РАЗРЕШИТЬ СООБЩЕНИЯ
                                                   <label class="switch switch-flat">
                                                        <input class="switch-input" type="checkbox" id="adv-chk-on" />
                                                        <span class="switch-label" data-on="On" data-off="Off"></span> 
                                                        <span class="switch-handle"></span>
                                                    </label>
                                                    </h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row">
                                        <label for="adv-name" class="col-md-3 col-form-label">ИМЯ</label>
                                        <div class="col-md-9">
                                            <input data-dojo-type="dijit/form/ValidationTextBox" type="text" id="adv-name" name="name" 
                                                   data-dojo-props="required:true"
                                                   required class="form-control" 
                                                   value="<?php echo(($user->ID>0)?$user->user_firstname:'');?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <label for="adv-eml" class="col-md-3 col-form-label">EMAIL</label>
                                        <div class="col-md-9">
                                            <input data-dojo-type="dijit/form/ValidationTextBox" type="email" id="adv-eml" name="eml" 
                                                   data-dojo-props="required:true"
                                                   required class="form-control" 
                                                   value="<?php echo(($user->ID>0)?$user->user_email:'');?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <label for="adv-tel"  class="col-md-4 col-form-label">ТЕЛЕФОН</label>
                                        <div class="col-md-8">
<?php
    $tel = "";
    if ($user->ID>0){
        $tel = get_user_meta($user->ID,'phone',true);
    }
?>    
                                            <input data-dojo-type="dijit/form/ValidationTextBox" type="tel" id="adv-tel" name="tel" 
                                               class="form-control" 
                                               value="<?php echo($tel);?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <input data-dojo-type="dijit/form/ValidationTextBox" type="text" id="adv-title" name="tt" 
                                   data-dojo-props="required:true"
                                   required placeholder="Название" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <textarea data-dojo-type="dijit/form/SimpleTextarea" id="adv-conte" name="msg" 
                                      data-dojo-props="required:true"
                                      required placeholder="Описание" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="adv-add-foto">
                                <input multiple="false" type="file" id="uploader"
                                       data-dojo-type="dojox/form/Uploader" 
                                       data-dojo-props="label:'Выберите одно или несколько изображений',name:'upload',force:'html5',uploadOnSelect:false" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row no-gutters" style="height:100%;">
                                <div class="col-12 align-self-start">
                                    <input data-dojo-type="dijit/form/TextBox" type="text" id="adv-price" name="pr"
                                           data-dojo-props="required:true"
                                           required placeholder="Цена, руб." class="form-control" />
                                </div>
                                 <div class="col-12 align-self-end">
                                    <button class="btn adv-submit" id="adv-submit">Разместить</button>
                                 </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php    
    get_footer();

    
