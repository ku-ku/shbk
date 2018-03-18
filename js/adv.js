require([   "dojo/dom", 
            "dojo/domReady!"
        ], function(dom){
    var sp = dom.byId("form-search-pane");
    if (sp){
        require(["shbk/search"], function(search){
            search.init(sp);
        });
    }
});