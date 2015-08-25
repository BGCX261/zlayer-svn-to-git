/**
 *
 */

dojo.require("dijit.form.Button");
dojo.require("dojo.NodeList-traverse");

dojo.provide("zlayer.form.Duplicate");

dojo.declare("zlayer.form.Duplicate",
    [dijit.form.Button],
    {
        node: '',
        children: '',
        
        postCreate: function(){
            this.inherited(arguments);
        },
        
        onClick: function(e) {
            
            // get a reference to some node where this.node is a string
            var node = dojo.byId(this.node);
            
            // get children node where this.children is a string like 'div'
            var children = dojo.query(node).children(this.children).first();
            
            // register date in miliseconds in order to use as ID for new node
            var date = new Date();
            var hash = date.getTime();
            
            // creates new node
            dojo.create(this.children, { id: hash}, this.node, "last");
            
            var newnode = dojo.clone(children);
            var queue = dojo.query(newnode).children();
            
            dojo.forEach(queue,function(element){
                var elementId = dojo.attr(element,'id')  + '[' + hash.toString() +']';
                if(dojo.attr(element,'id') !== 'duplicate-element'){
                    if(dojo.attr(element,'id')){
                        dojo.attr(element,'id',elementId);
                    }
                    if(dojo.attr(element,'widgetid')){
                        dojo.attr(element,'widgetid',elementId);
                    }
                    dojo.query(element).children().forEach(function(child){
                        if(dojo.attr(child,'id')){
                            dojo.attr(child,'id',dojo.attr(child,'id') + '[' + hash.toString() +']');
                        }
                        if(dojo.attr(child,'widgetid')){
                            dojo.attr(child,'widgetid',dojo.attr(child,'widgetid') + '[' + hash.toString() +']');
                        }
                        dojo.query(child).children().forEach(function(grand){
                            if(dojo.attr(child,'id')){
                                dojo.attr(grand,'id',dojo.attr(grand,'id') + '[' + hash.toString() + ']');
                            }
                        });
                    });
                    dojo.place(element,hash.toString(),'last');
                }
            });
            this.inherited(arguments);
        }
    }
);