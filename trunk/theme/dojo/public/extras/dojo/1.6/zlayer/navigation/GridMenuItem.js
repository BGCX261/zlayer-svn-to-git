/**
 *
 */
dojo.require("dijit.MenuItem");
dojo.require("zlayer.navigation.Target");

dojo.provide("zlayer.navigation.GridMenuItem");

dojo.declare("zlayer.navigation.GridMenuItem",
    [dijit.MenuItem, zlayer.navigation.Target],
    {
        partialUrl: "",
        sourceKey: "",
        targetKey: "",
        method:  "GET",
        gridId: "",
    
        postCreate: function(){
            this.inherited(arguments);
        },
    
        onClick: function(e) {
            
            var returnVal = null;
            var grid = dijit.byId(this.gridId);
            var selectedRows = grid.selection.getSelected();
            
            if (selectedRows.length) {
                
                var _key = this.sourceKey;
                
                dojo.forEach(selectedRows, function(selectedItem){
                    if (selectedItem != null ) {
                        returnVal = grid.store.getValues(selectedItem, _key);
                    }
                });
                
                if (returnVal) {
                    this.setRequest(this.partialUrl + '/' + this.targetKey + '/' + returnVal);
                    this.getData();
                }
                
            }            
                        
        }
        
    }
);