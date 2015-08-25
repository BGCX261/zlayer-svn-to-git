/**
 *
 */
dojo.require("dojox.grid.EnhancedGrid");

dojo.provide("zlayer.grid.EnhancedGrid");

dojo.declare("zlayer.grid.EnhancedGrid",
        dojox.grid.EnhancedGrid,
        {    
            baseUrl: dojo.config.ZlBaseUrl,
            oldVal: null,
            cellName: null,
    
            postCreate: function(){
                this.inherited(arguments);
            },
            
            onStartEdit: function(inCell, inRowIndex) { 
                // returns cell name
                this.cellName = this.getCellName(inCell);
                
                // store cell value into oldVal
                var cellName = this.cellName;
                this.oldVal = this.getItem(inRowIndex).cellName;
    
                this.inherited(arguments);
            },
            
            /*
             * @param inValue string The new cell value
             * @param inRowIndex int Index of the row. Starts on 0 (zero)
             * @param inAttrName string Column name (same as this.cellName)
             */
            onApplyCellEdit: function(inValue, inRowIndex, inAttrName) {
                // if new value is differente from old value, perform database update
                if(inValue !== this.oldVal) {
                    // execute!
                }
               
                this.inherited(arguments);
            },
        }
);