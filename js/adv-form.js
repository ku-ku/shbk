/* global dojoConfig */

define(["dojo/window",
        "dojo/dom",
        "dojo/dom-attr",
        "dojo/dom-class",
        "dojo/dom-style",
        "dojo/query",
        "dijit/registry",
        "dojo/domReady!"
    ], function(win,dom,attr,domClass,style,query,registry){

    var _s = {
    };
    
    _s.empty = function(s){
        return (s) ? /^\s*$/.test(s) : true;
    };

    
    _s.msg = function(s, cls, scroll){
        var node = dom.byId("adv-info-msg");
        if (_s.empty(s)){
            domClass.add(node,'d-none');
            return;
        }
        domClass.remove(node,["alert-success","alert-info","alert-warning","alert-danger"]);
        if (!_s.empty(cls)){
            domClass.add(node,cls);
        }
        attr.set(node,'innerHTML', s);
        domClass.remove(node,'d-none');
        if (scroll){
            win.scrollIntoView(node);
        }
    };  //_s.msg
    
    _s.onComplete = function(e){
        console.log(e);
        var s, b  = dom.byId("adv-submit");
        attr.remove(b,'disabled');
        
        if ((e.err)&&(e.err==1)){
            _s.onError(e.msg);
            return false;
        }
        
        attr.set(b,'innerHTML', 'Разместить еще');
        attr.set(b,'data-btn-action', 'new');
        s = '<i class="fas fa-check"></i>Ваша заявка успешно зарегистрирована под номером ' + e.id;
        s += ' и после модерации будет опубликована';
        _s.msg(s,"alert-success",true);
        
        if ((e.thumbId)&&(e.errThumb==0)){
            query("#uploader").addClass("invisible");
            query(".adv-add-foto").style({"backgroundImage":'url("'+e.thumbUrl+'")'});
        }
    };      //_s.onComplete
    
    _s.onError = function(err){
        console.debug(err);
        var b  = dom.byId("adv-submit");
        attr.remove(b,'disabled');
        attr.set(b,'innerHTML', 'Повторить');
        attr.set(b,'data-btn-action', 'retry');
        var s = '<i class="fas fa-exclamation-triangle"></i>&nbsp;Ваша заявка не зарегистрирована. Повторите попытку позже.';
        s += '<div class="error-info">Информация для службы поддержки: ' + err + '</div>';
        _s.msg(s,"alert-danger",true);
    };      //_s.onError
    
    _s.onSubmit = function(e){
        console.log(e);
        if (e){
            e.preventDefault();
        }
        
        _s.msg();
        var v, frm = registry.byId("adv-form");
        var b = dom.byId("adv-submit");
        
        v = attr.get(b,"data-btn-action");
        
        if (v){
            if ('new'===v){
                window.location.reload();
                return;
            } else if ('retry'===v){
                window.location.reload();//TODO:(nonce)
                return;
            }
        }
        
        var _bad = function(){
            _s.msg('<i class="fas fa-exclamation-triangle"></i>&nbsp;Пожалуйста, для правильной обработки Вашей заявки заполните требуемые поля формы.','alert-warning');
        };
        
        if ( !frm.validate() ) {
            _bad();
            return false;
        }
        v = frm.get('value');
        var w = registry.byId("adv-acat");
        if (!w.validate()){
            _bad();
            w.focus();
            return false;
        }
        v.acat = w.get('value');
        w = registry.byId("adv-city");
        if (!w.validate()){
            _bad();
            w.focus();
            return false;
        }
        v.city = w.get('value');
        w = registry.byId("adv-dis");
        v.distr = w.get('value');
        w = dom.byId("adv-chk-on");
        v.onmsg = attr.get(w,'checked') ? 1 : 0;

        var fu = registry.byId("uploader");
        fu.uploadUrl = dojoConfig.wpAjaxUrl;
        
        w = dom.byId("adv-submit");
        attr.set(w,'innerHTML', '<i class="fas fa-spinner fa-spin"></i>&nbsp;Отправка...');
        attr.set(w, 'disabled', 'disabled');
        
        v.action = "feedback";
        v.q = "save-adv";
        v.nonce = dojoConfig.wpNonce;   //see header
        
        fu.upload(v);
        return false;
    };      //_s.onSubmit
    
    _s.init = function(pane){
        _s.msg('<i class="fas fa-spinner fa-spin"></i>&nbsp;Подготовка формы...', "alert-info");
        require([
                 "dojo/parser",
                 "dojo/data/ItemFileReadStore",
                 "dojo/on",
                 "dijit/form/TextBox",
                 "dijit/form/ValidationTextBox",
                 "dijit/form/FilteringSelect",
                 "dijit/form/SimpleTextarea",
                 "dojox/form/Uploader"
                ], function(parser,ItemFileReadStore,on){
            var adv = window["adv"] || {};
            adv.stories = {};
            adv.stories.cats   = new ItemFileReadStore({url:dojoConfig.wpAjaxUrl + "?action=info&q=cats"});
            adv.stories.cities = new ItemFileReadStore({url:dojoConfig.wpAjaxUrl + "?action=info&q=city"});
            adv.stories.dstrc  = new ItemFileReadStore({url:dojoConfig.wpAjaxUrl + "?action=info&q=dstrc&city=-1",clearOnClose:true});
            adv.frm = _s;
            window["adv"] = adv;
            parser.parse(pane).then(function(){
                domClass.remove(pane,"invisible");
                
                var w = registry.byId("adv-city");
                if (w){
                    var _on_city = function(city){
                        console.log('City change: ', city);
                        adv.stories.dstrc.close();
                        adv.stories.dstrc.url=dojoConfig.wpAjaxUrl + "?action=info&q=dstrc&city=" + city;
                        var w = registry.byId("adv-dis");
                        if (w){
                            w.reset();
                        }
                    };  //_on_city
                    w.on('change', _on_city);
                }
                var fu = registry.byId("uploader");
                fu.uploadUrl = dojoConfig.wpAjaxUrl;
                on(fu, "complete", _s.onComplete);
                on(fu, "error", _s.onError);
                
                var b = dom.byId("adv-submit");
                on(b,'click', _s.onSubmit);
                
                var w = registry.byId("adv-acat");
                w.focus();
                
                _s.msg();
            });
        });
    };  //_s.init
    
    return _s;
});    
