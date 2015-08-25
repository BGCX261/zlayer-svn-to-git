/**
 * 
 */

dojo.require("dojox.widget.FisheyeLite");

dojo.provide("dojotop.widget.icon.fx.Jelly");

dojo.declare("dojotop.widget.icon.fx.Jelly", 
    dojox.widget.FisheyeLite, 
    {
		constructor: function(props, node){
			this.inherited(arguments);
			this.properties = props.properties || {
				height:1.1,
		        width:1.1,
			};
		},
		
    }
);