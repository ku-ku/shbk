<?php
/**
 * The template for displaying the front-page content
 * @package WordPress
 * @subpackage Shabashka
 * @since Shabashka 1.0
 * Template Name: Шаблон формы объявления
 */
    get_header();
?>
<div class="container">
    <div class="row" style="margin:2rem 0">
        <div class="col-md-3">

        </div>
        <div class="col-md-9">
            <div class="form-adv-pane soria" id="form-adv-pane" style="display:none;">
                <form data-dojo-type="dijit/form/Form" id="adv-form">
                    <div class="form-group row" style="margin-left:0;margin-right:0">
                        <div class="adv-contacts">
                            <div class="row">
                                <div class="col-6"><h6 style="margin-top:5px;">КОНТАКТНАЯ ИНФОРМАЦИЯ</h6></div>
                                <div class="col-6"><h6 style="font-weight:400;">РАЗРЕШИТЬ СООБЩЕНИЯ
                                                   <label class="switch switch-flat">
                                                        <input class="switch-input" type="checkbox" />
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
                                            <input data-dojo-type="dijit/form/TextBox" type="text" id="adv-name" name="adv[n]" 
                                                   required 
                                                   class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <label for="adv-eml" class="col-md-3 col-form-label">EMAIL</label>
                                        <div class="col-md-9">
                                            <input data-dojo-type="dijit/form/TextBox" type="email" id="adv-eml" name="adv[e]" 
                                                   class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <label for="adv-tel"  class="col-md-4 col-form-label">ТЕЛЕФОН</label>
                                        <div class="col-md-8">
                                            <input data-dojo-type="dijit/form/TextBox" type="tel" id="adv-tel" name="adv[t]" 
                                               class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <input data-dojo-type="dijit/form/TextBox" type="text" id="adv-title" name="adv[tt]" 
                                   required placeholder="Название" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <textarea data-dojo-type="dijit/form/SimpleTextarea" id="adv-conte" name="adv[co]" 
                                      required placeholder="Описание" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6">
                            <input data-dojo-type="dijit/form/TextBox" type="text" id="adv-price" name="adv[pr]"
                                   required placeholder="Цена, руб." class="form-control" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php    
    get_footer();

    
