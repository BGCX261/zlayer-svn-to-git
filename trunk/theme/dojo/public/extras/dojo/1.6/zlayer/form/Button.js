/**
 *
 */

dojo.require("dijit.form.Button");
dojo.require("zlayer.navigation.Href");

dojo.provide("zlayer.form.Button");

dojo.declare("zlayer.form.Button",
    [dijit.form.Button, zlayer.navigation.Href],
    {
        postCreate: function(e){
            this.inherited(arguments);
            this.connect(this.domNode, "onclick", "onClick");
        },
        onClick: function(e) {
            this.inherited(arguments);
            this.getData();
        }
    }
);