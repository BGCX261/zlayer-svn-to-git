/**
 * 
 */



dojo.require("dijit._Widget");
dojo.require("zlayer.navigation.Target");

dojo.provide("zlayer.navigation.Href");
dojo.declare("zlayer.navigation.Href", 
    [dijit._Widget, zlayer.navigation.Target], 
    {
    	method: "GET",
        href: "",

        postCreate: function(){
            this.inherited(arguments);
            this.connect(this.domNode, "onclick", "onClick");
            this.connect(this.domNode, "onmouseover", "onMouseOver");
            dojo.attr(this.domNode, "href", "javascript:void(0)");
            dojo.attr(this.domNode, "target", "");
            dojo.attr(this.domNode, "method", "");
            this.setRequest(this.href);
        },

        onMouseOver: function(e){
            this.inherited(arguments);
            dojo.style(this.domNode, "cursor", "pointer");
        },

        onClick: function(e) {
            this.inherited(arguments);
            this.getData();
        }
    }
);