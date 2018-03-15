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
    require(["dijit/form/FilteringSelect", "dijit/form/ValidationTextBox"],
            function(){
        parser.parse(adv_pane);
        style.set(adv_pane,{display:"block"});
     });
});