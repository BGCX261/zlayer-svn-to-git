/*
 *
 */

dojo.require("zlayer.navigation._Call");
dojo.require("dojotabtop.Interface");

dojo.provide("dojotabtop.Call");

dojo.declare("dojotabtop.Call",
    dojotabtop.Interface,
    {
        postCreate: function(){
            this.inherited(arguments);
            this.connect(this.domNode, "onmouseover", "onMouseOver");
            this.connect(this.domNode, "onclick", "onClick");
        },

        onMouseOver: function(e){
            this.inherited(arguments);
            dojo.style(this.domNode, "cursor", "pointer");
        },

        onClick: function(e) {
            this.inherited(arguments);
            if (this.build()) {
                var _request = this.baseUrl("/" + this.module + "/" + this.controller + "/" + this.action);
                var _call = zlayer.navigation._Call();
                _call.setMethod("GET");
                _call.setTarget(this.getContentId());
                _call.setRequest(_request);
                _call.setCallBackTarget(false);
                _call.getData();
            }
        },

        
        
    }
);
