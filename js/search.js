/* global dojoConfig */

define([
         "dojo/dom",
         "dojo/_base/array",
         "dojo/dom-attr",
         "dojo/dom-class",
         "dojo/on",
         "dojo/query",
         "dojo/request/xhr",
         "dijit/registry",
         "dijit/Dialog"
], function(dom,array,attr,domClass,on,query,xhr,registry,Dialog){
    var _s = {
        d_cats:null,
        d_call:null,
        d_write:null
    };
    
    _s.onCity = function(cityId){
        console.log('City change: ', cityId);
        require(["dijit/registry"], function(registry){
            var w = registry.byId("adv-dis");
            if (w){
                try{
                    w.store.close();
                    w.store.url = dojoConfig.wpAjaxUrl+"?action=info&q=dstrc&city="+cityId;
                    w.reset();
                }catch(e){console.log(e);}
            }
        });
    };  //_s.onCity
    
    _s.onCat = function(e){
        console.log(e);
        if (e){
            e.preventDefault();
            e.stopPropagation();
        }
        if (!_s.d_cats){
            _s.d_cats = new Dialog({title:"Выберите категорию",style:"width:640px","class":"adv-dlg-cats"});
            xhr(dojoConfig.wpAjaxUrl + "?action=info&q=cats",{handleAs:'json'}).then(
            function(data){
                var s = '<ul>';
                array.forEach(data.items, function(cat){
                    s += '<li data-cat-id="' + cat.id + '">' + cat.name + '</li>';
                });
                s += '</ul>';
                _s.d_cats.set('content', s);
                dom.setSelectable(_s.d_cats.containerNode,false);
                query("li", _s.d_cats.containerNode).on('click', function(e){
                    var w = registry.byId("adv-cat");
                    w.set('value', attr.get(e.target, "data-cat-id"));
                    var node = query("#adv-cat-name")[0];
                    attr.set(node,'innerHTML','еШабашка - ' + attr.get(e.target,'innerHTML'));
                    _s.d_cats.hide();
                });

            },
            function(err){
                console.log(err); //TODO:
            });
        }
        _s.d_cats.show();
        return false;
    };  //_s.onCat
    
    _s.doActionCall = function(card,btn){
        if (!_s.d_call){
            _s.d_call = new Dialog({title:'<i class="fas fa-mobile-alt"></i>&nbsp;Позвонить',style:"width:640px","class":"adv-dlg-call"});
            _s.d_call.on('load', function(){
                query(".btn-close", _s.d_call.containerNode).on('click', function(){
                    _s.d_call.hide();
                });
            });
        }
        _s.d_call.set('href',dojoConfig.wpAjaxUrl+'?action=info&q=call&id='+attr.get(card,"data-adv-id"));
        _s.d_call.show();
    };  //_s.doActionCall
    
    _s.doActionWrite = function(card,btn){
        var cardId = attr.get(card,"data-adv-id");
        if (!_s.d_write){
            _s.d_write = new Dialog({title:'<i class="far fa-comment-alt"></i>&nbsp;Написать',style:"width:640px","class":"adv-dlg-write"});
            _s.d_write.on('load', function(){
                query(".btn-close", _s.d_write.containerNode).on('click', function(){
                    _s.d_write.hide();
                });
                query(".btn-send", _s.d_write.containerNode).on('click', function(e){
                    if (e){
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    attr.set(e.target,'disabled','disabled');
                    var w = registry.byNode(query(".dijitTextBox",_s.d_write.containerNode)[0]);
                    xhr(_s.d_write.get('href'),{
                        method:'POST',
                        handleAs:'json',
                        query:{
                            msg: w.get('value')
                        }
                    }).then(
                       function(data){
                           console.log(data);
                       }, function(err){
                           console.log(err);
                           _s.d_write.set('content', '<div class="alert alert-warning" role="alert"><i class="fas fa-exclamation-triangle"></i>&nbsp;Сообщение не отправлено, попробуйте написать попозже.</div>');
                       }
                    );
                });
            });
        }
        _s.d_write.set('href',dojoConfig.wpAjaxUrl+'?action=info&q=write&id='+cardId);
        _s.d_write.show();
    };  //_s.doActionWrite
    
    _s.onAction = function(e){
        if (!e){
            return false;
        }
        console.log(e.target);
        var card = e.target;
        while (card){
            if (domClass.contains(card,"card")){
                break;
            } else {
                card = card.parentNode;
            }
        }
        if (domClass.contains(e.target,"btn-call")){
            _s.doActionCall(card,e.target);
        } else if (domClass.contains(e.target,"btn-write")){
            _s.doActionWrite(card,e.target);
        }
    };  //_s.onAction
    
    _s.init = function(pane){
        require(["dojo/parser",
                 "dojo/data/ItemFileReadStore",
                 "dijit/form/TextBox",
                 "dijit/form/FilteringSelect",
                 "dojo/domReady!"
                ], function(parser,ItemFileReadStore){
                    var conte = dom.byId("adv-search-conte");
                    if (conte){
                        query(".btn", conte).on('click',_s.onAction);
                    }
                    
                    var cityId,
                        node = dom.byId("adv-city");
                    cityId = attr.get(node,'value');
                    if (!cityId){
                        cityId = -1;
                    }
                    var b = dom.byId("btn-cat");
                    if (b){
                        on(b,'click', _s.onCat);
                    } else {
                        console.log("No #btn-cat found!");
                    }
                    
                    var adv = window["adv"] || {};
                    adv.stories = {};
                    adv.stories.cities = new ItemFileReadStore({url:dojoConfig.wpAjaxUrl + "?action=info&q=city",clearOnClose:true});
                    adv.stories.dstrc  = new ItemFileReadStore({url:dojoConfig.wpAjaxUrl + "?action=info&q=dstrc&city="+cityId,clearOnClose:true});
                    window["adv"] = adv;
                    
                    parser.parse(pane).then(function(){
                        var w = registry.byId("adv-city");
                        if (w){
                            w.on('change', _s.onCity);
                        } else {
                            console.log("No #adv-city found!");
                        }
                        
                        w = registry.byId("adv-search-form");
                        b = dom.byId("btn-do-search");
                        if (b){
                            attr.remove(b,"disabled");
                            on(b,'click',w.submit);
                        }
                    });
        });
        
    };      //_s.init
    
    return _s;
});