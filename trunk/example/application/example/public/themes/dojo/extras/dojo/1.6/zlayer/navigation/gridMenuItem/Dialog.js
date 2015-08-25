/**
 * 
 */
dojo.require("zlayer.navigation.GridMenuItem");
dojo.require("dojox.widget.Dialog");


dojo.provide("zlayer.navigation.gridMenuItem.Dialog");
dojo.declare("zlayer.navigation.gridMenuItem.Dialog", 
    [zlayer.navigation.GridMenuItem], 
    {
        sizeToViewport: false,
        dimensions: [700,400],

        onClick: function(e) {

            var _dialog = new dojox.widget.Dialog({
            	style: "height: 300px;",
            	sizeToViewport : this.sizeToViewport,
            	parseOnLoad: false
            });
            if(this.dimensions.length == 2) {
                _dialog.set("dimensions", this.dimensions); // [width, height]
                _dialog.layout(); //starts the resize
            }

            _dialog.show();
            
            this.connect(_dialog, "onHide", function(){
                setTimeout(function() { _dialog.destroyRecursive(); }, 0);
            });
            
            this.setCallBackTarget(false);
            this.setTarget(_dialog.domNode.id);
            
            this.inherited(arguments);
        }
    }
);