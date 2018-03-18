/* global dojoConfig */

define(function(){
    var _s = {
        d_cats: null
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
        require(["dojo/dom",
                 "dijit/registry",
                 "dijit/Dialog", 
                 "dojo/request/xhr",
                 "dojo/dom-attr",
                 "dojo/_base/array",
                 "dojo/on",
                 "dojo/query"
            ], function(dom,registry,Dialog,xhr,attr,array,on,query){
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
                
        });
        
        return false;
    };  //_s.onCat
    
    _s.init = function(pane){
        require([
                 "dojo/dom",
                 "dojo/dom-attr",
                 "dojo/parser",
                 "dojo/data/ItemFileReadStore",
                 "dojo/on",
                 "dijit/registry",
                 "dijit/form/TextBox",
                 "dijit/form/FilteringSelect",
                 "dojo/domReady!"
                ], function(dom,attr,parser,ItemFileReadStore,on,registry){
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