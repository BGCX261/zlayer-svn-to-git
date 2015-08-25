/* 
 * 
 */
 
dojo.require("dijit.layout.AccordionContainer");
dojo.require("dijit.layout.AccordionPane");
dojo.require("dijit.layout.ContentPane");
dojo.require("dojox.layout.ContentPane");
dojo.require("dojotop.widget.Icon");
dojo.require("dojotabtop.Call");
dojo.require("zlayer.util._Url");

dojo.provide("dojotabtop.Accor");

dojo.declare("dojotabtop.Accor", 
	[dijit.layout.AccordionContainer, zlayer.util._Url], 
    {
        minSize: "240",
        
        data: null,
        moduleIcon: false,
        
        postCreate: function(){
            this.inherited(arguments);
            this._buildContent();
        },

        _buildContent: function() {

            var _accor = this;
            var _baseThemeUrl = this.baseThemeUrl;
            var _baseUrl = this.baseUrl;
            var _moduleIcon = this.moduleIcon;
            
            //this.startup();
            
            dojo.forEach(this.data, function(menu, menuIndex){
                
                var _pane = new dojox.layout.ContentPane({ 
                    title: menu.name
                });
                
                dojo.forEach(menu.items, function(item, itemIndex){
                    
                    var _link = new dojotabtop.Call({
                        module: item.module,
                        controller: item.controller,
                        action: item.action
                    }).placeAt(_pane.containerNode);
                    
                    if (_moduleIcon) {
                        var _iconUrl = _baseThemeUrl('config/modules/' + item.module + '/images/menu.png');
                    } else {
                        var _iconUrl = _baseThemeUrl('config/modules/' + item.module + '/controllers/' + item.controller + '/' + item.action + '/images/menu.png');
                    }
                    
                    dojo.xhrGet({
                            url: _iconUrl,
                            failOk: true,
                            error: function(){ 
                                _iconUrl = _baseUrl("/themes/dojotabtop/extras/dojo/1.6/dojotabtop/images/icons/menu.png");
                                return true;
                            },
                            sync: true
                    });
                    
                    var _icon = new dojotop.widget.Icon({
                        src: _iconUrl, 
                        label: item.name
                    }).placeAt(_link.domNode);
                    
                });
                
                _accor.addChild(_pane);
                //_accor.resize();
                
            });
            
            //this.resize();
            //this.startup();
            
        }
    }
);