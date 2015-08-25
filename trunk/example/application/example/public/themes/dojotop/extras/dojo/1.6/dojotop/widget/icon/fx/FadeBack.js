/**
 * 
 */

dojo.provide("dojotop.widget.icon.fx.FadeBack");

dojo.declare("dojotop.widget.icon.fx.FadeBack", 
    null, 
    {
		constructor: function(props, node){
			dojo.style(node, "opacity", "1");
            var fadeArgs = {
                node: node
            };
            dojo.fadeOut(fadeArgs).play();
		},
		
    }
);