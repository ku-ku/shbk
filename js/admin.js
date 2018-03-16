/* global dojoConfig */

require([
    "dojo/dom",
    "dojo/dom-attr",
    "dojo/dom-style",
    "dojo/parser",
    "dojo/domReady!"
], function(dom, attr, style, parser){
    var adv_pane = dom.byId("adv-info-pane");
    if (!adv_pane){
        return;
    }
    require([   "dojo/data/ItemFileReadStore",
                "dojo/on",
                "dijit/registry",
                "dijit/form/FilteringSelect", 
                "dijit/form/ValidationTextBox",
                "dijit/form/CurrencyTextBox"
            ], function(ItemFileReadStore,on,registry){
                
                
        var cityId,
            node = dom.byId("adv-city");
        cityId = attr.get(node,'value');
        var adv = {stories:{}};
        adv.stories.cities = new ItemFileReadStore({url:dojoConfig.wpAjaxUrl + "?action=info&q=city",clearOnClose:true});
        adv.stories.dstrc  = new ItemFileReadStore({url:dojoConfig.wpAjaxUrl + "?action=info&q=dstrc&city="+cityId,clearOnClose:true});
        adv.stories.cats   = new ItemFileReadStore({url:dojoConfig.wpAjaxUrl + "?action=info&q=cats",clearOnClose:true});
        window["adv"] = adv;
                
        parser.parse(adv_pane);
        style.set(adv_pane,{display:"block"});
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
     });
});