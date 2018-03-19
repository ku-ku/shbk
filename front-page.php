<?php
/**
 * The template for displaying the front-page content
 * @package WordPress
 * @subpackage Shabashka
 * @since Shabashka 1.0
 */
    get_header();
    $city = $_REQUEST["ac"];
    $distr= $_REQUEST["ad"];
    $acat = $_REQUEST["acat"];
?>
<div class="container">
    <div class="form-search-pane soria" id="form-search-pane">
        <form data-dojo-type="dijit/form/Form" id="adv-search-form">
            <div class="row justify-content-between" style="border:  2px solid #ff4116;">
                <div class="align-self-center" style="padding:0;width:24%;">
                    <button class="btn btn-primary btn-block btn-cat" id="btn-cat">Выберите категорию</button>
                </div>    
                <div class="align-self-center" style="width:33%;">
                    <label for="adv-city">Поиск по объявлениям</label>
                </div>    
                <div class="align-self-center" style="width:19%;border-right:2px solid #ff4116;height:38px;line-height:38px;">
                    <input id="adv-cat" 
                           type="text"
                           data-dojo-type="dijit/form/TextBox" name="acat" 
                           style="display: none;"
                           value="<?php echo $acat;?>" />
                    <div id="adv-city" 
                         data-dojo-type="dijit/form/FilteringSelect" name="ac" 
                         data-dojo-props="store:adv.stories.cities"
                         placeholder="Город"
                         value="<?php echo $city;?>"></div>
                </div>    
                <div class="align-self-center" style="width:19%;">
                    <div id="adv-dis" 
                         data-dojo-type="dijit/form/FilteringSelect" name="ad" 
                         data-dojo-props="store:adv.stories.dstrc,required:false"
                         placeholder="Район"
                         value="<?php echo $distr;?>"></div>
                </div>
                <div class="text-right align-self-center" style="min-width:42px;padding-right:0;">
                    <button class="btn btn-primary btn-do-search" id="btn-do-search" disabled></button>
                </div>
            </div>
            <div class="row">
                <div class="col adv-cat-name" id="adv-cat-name">
<?php if (isset($acat)&&$acat>0){
    $term = get_term_by( 'id', $acat, 'category');
    echo 'еШабашка - '.$term->name;
}
?>
                </div>
            </div>
        </form>
    </div>
    <div id="adv-search-conte" class="adv-search-conte">
        <?php adv_print();?>
    </div>
</div>
<?php    
    get_footer();
