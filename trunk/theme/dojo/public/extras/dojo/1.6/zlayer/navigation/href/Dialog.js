/**
 * 
 */
dojo.require("zlayer.navigation.Href");
dojo.require("dojox.widget.Dialog");

dojo.provide("zlayer.navigation.href.Dialog");
dojo.declare("zlayer.navigation.href.Dialog", 
    [zlayer.navigation.Href], 
    {
        sizeToViewport: false,

        onClick: function(e) {

            var _dialog = new dojox.widget.Dialog({
            	style: "height: 300px",
            	sizeToViewport : this.sizeToViewport,
            	parseOnLoad: false
            });

            this.setCallBackTarget(false);
            this.target = _dialog.domNode.id;
            this.getData();
            _dialog.show();

            this.connect(_dialog, "onHide", function(){
                setTimeout(function() { _dialog.destroyRecursive(); }, 0);
            });
        }
    }
);