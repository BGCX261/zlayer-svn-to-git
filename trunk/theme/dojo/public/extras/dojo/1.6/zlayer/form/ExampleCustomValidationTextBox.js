/**
 * 
 */
dojo.require("dijit.form.ValidationTextBox");

dojo.provide("zlayer.form.ExampleCustomValidationTextBox");

dojo.declare("zlayer.form.ExampleCustomValidationTextBox", 
    dijit.form.ValidationTextBox, 
    {
        onFocus: function(e) {
            this.inherited(arguments);
            //alert("Hello world!");
        }
    }
);