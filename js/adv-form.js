/* global dojoConfig */

define(function(){
    var _s = {
    };
    
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
                 "dijit/form/SimpleTextarea",
                 "dojo/domReady!"
                ], function(dom,attr,parser,ItemFileReadStore,on,registry){
            parser.parse(pane).then(function(){
                attr.remove(pane, "style");
            });
        });
    };  //_s.init
    
    return _s;
});    
