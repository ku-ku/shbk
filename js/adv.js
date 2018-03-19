require([   "dojo/dom", 
            "dojo/domReady!"
        ], function(dom){
    var sp = dom.byId("form-search-pane");
    if (sp){
        require(["shbk/search"], function(search){
            search.init(sp);
        });
    }
    var ap = dom.byId("form-adv-pane");
    if (ap){
        require(["shbk/adv-form"], function(advForm){
            advForm.init(ap);
        });
    }
});