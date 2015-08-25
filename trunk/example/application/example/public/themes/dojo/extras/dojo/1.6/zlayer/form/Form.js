/**
 *
 */
dojo.require("dijit.form.Form");
dojo.require("zlayer.navigation.Target");

dojo.provide("zlayer.form.Form");

dojo.declare("zlayer.form.Form",
    [dijit.form.Form, zlayer.navigation.Target],
    {
        method: "POST",
    
        postCreate: function(){
            this.inherited(arguments);
            this.setRequest(this.action);
            dojo.attr(this.domNode, "target", "");
        },
        
        onSubmit: function(e){
            this.inherited(arguments);
            if (this.validate()) {
                if (this.format!='html' && this.format!='default' && this.format) {
                    this.getData();
                    dojo.stopEvent(e);
                } else {
                    return true;
                }
            } else {
            	dojo.stopEvent(e);
            }
        },
        
        parseMessenger: function(messenger) {
	
            for(var type in messenger) {
                var messengerType = messenger[type];
                for(var indexType in messengerType) {
                    var messengerMessage = messengerType[indexType];
                    for(var key in messengerMessage) {
	                	if (node = dijit.byId(key)) {
	                	    node._set("state","Error");
	                	    dijit.showTooltip(messengerMessage[key], node.domNode, node.tooltipPosition, !node.isLeftToRight());
	                	    //dijit.hideTooltip(node.domNode);
	                	    //this.connect(node, "onMouseOut", dijit.hideTooltip(node.domNode));
	                	    messengerType.splice(indexType,1);
	                	}
                    }
                }
            }
            return messenger;
        }
        
    }
);