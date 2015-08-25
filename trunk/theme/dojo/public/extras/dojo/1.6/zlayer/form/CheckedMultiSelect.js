dojo.provide("zlayer.form.CheckedMultiSelect");

dojo.require("dojox.form.CheckedMultiSelect");

dojo.declare("zlayer.form.CheckedMultiSelect",
    [dojox.form.CheckedMultiSelect],
    {
        postCreate: function(){
            console.log('\n <br /> Teste \n <br />');
            this.inherited(arguments);
        }
    }
);