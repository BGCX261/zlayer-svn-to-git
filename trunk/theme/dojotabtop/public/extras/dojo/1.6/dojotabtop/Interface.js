/*
 *
 */

dojo.require("dijit._Widget");
dojo.require("zlayer.util._Url");
dojo.require("zlayer.navigation._Call");
dojo.require("dojox.layout.ContentPane");
dojo.require("dijit.layout.TabContainer");
dojo.require("dijit.layout.BorderContainer");
dojo.require("dojox.layout.ExpandoPane");
//dojo.require("dojotabtop.Accor");


dojo.provide("dojotabtop.Interface");

dojo.declare("dojotabtop.Interface",
    [dijit._Widget, zlayer.util._Url],
    {
        module: 'default',
        controller: 'default',
        action: 'default',
        contentId: '',
        moduleNameSpace: '',
        actionControllerNameSpace: '',
        
        setContentId: function(id) {
            this.contentId = id;
        },
        
        getContentId: function() {
            return this.contentId;
        },
            
        build: function() {
            
            var _moduleHostId = "tabModule";
            var _moduleHostWidget = dijit.byId(_moduleHostId);
            var _moduleItemId = _moduleHostId + this.module;
            var _moduleItemWidget = dijit.byId(_moduleItemId);
            var _actionHostId = _moduleItemId + "host"
            
            if(!_moduleItemWidget){
                
                var _moduleFile = dojo.config.ZlBaseThemeUrl + "config/modules/" + this.module + "/module.json";
                var _moduleResponse = this._getConfig(_moduleFile);
                
                if (!_moduleResponse) {
                    return;
                }
                
                var _moduleMenu = null;
                if (_moduleResponse.menu) {
                    _moduleMenu = _moduleResponse.menu;
                }
                
                this._buildBaseInterface(_moduleHostWidget,_moduleItemId,_moduleResponse.description,_moduleMenu);
                
                var _actionHostWidget = new dijit.layout.TabContainer({
                    id: _actionHostId,
                    region: "center",
                    tabStrip: true
                });
                
                var _moduleItemWidget = dijit.byId(_moduleItemId);
                _moduleItemWidget.addChild(_actionHostWidget);
                
            } else {
                
                _moduleHostWidget.selectChild(_moduleItemWidget);
                
            }
            
            var _actionHostWidget = dijit.byId(_actionHostId);
            var _actionItemId = _moduleItemId + this.controller + this.action;
            var _actionItemWidget = dijit.byId(_actionItemId);
            var _contentId = _actionItemId + "content";
            this.setContentId(_contentId);
            
            if(!_actionItemWidget){
                
                var _actionFile = dojo.config.ZlBaseThemeUrl + "config/modules/" + this.module + "/controllers/" + this.controller + "/" + this.action + "/action.json";
                var _actionResponse = this._getConfig(_actionFile);
                
                if (!_actionResponse) {
                    return;
                }
                
                var _actionMenu = null;
                if (_actionResponse.menu) {
                    _actionMenu = _actionResponse.menu;
                }
                
                this._buildBaseInterface(_actionHostWidget,_actionItemId,_actionResponse.description,_actionMenu);
                var _contentWidget = dijit.byId(_contentId);
                
                if (!_contentWidget) {
                    
                    var _contentWidget = new dojox.layout.ContentPane({
                        id: _contentId,
                        region: "center",
                        style: "overflow-y: auto"
                    });
                    
                    var _actionItemWidget = dijit.byId(_actionItemId);
                    _actionItemWidget.addChild(_contentWidget);
                    
                    return true;
                
                } else {
                    
                    var _actionItemWidget = dijit.byId(_actionItemId);
                    _actionItemWidget.addChild(_contentWidget);
                    
                    return false;
                    
                }
                
                
            } else {
                _actionHostWidget.selectChild(_actionItemWidget);
                return false;
            }

        },
        
        _buildBaseInterface: function(host,itemId,label,menu) {
            
            // Create border container
            var _borderContainer = new dijit.layout.BorderContainer({
                id: itemId,
                title: label,
                closable: true,
                gutters: true,
                liveSplitters: false,
                class: "actionBorderContainer"
            });

            if (menu) {
                
                // Add Right
                var _expandoPane = new dojox.layout.ExpandoPane({
                    region: "right",
                    splitter: "true",
                    slideFrom: "right",
                    easeIn: "dojo.fx.easing.backInOut",
                    easeOut: "dojo.fx.easing.backIn",
                    class: "menu"
                });
                
                var _accorPane = new dojotabtop.Accor({data: menu});
                _expandoPane.addChild(_accorPane);
                _borderContainer.addChild(_expandoPane);
                
            }

            // Add/Focus on Host
            host.addChild(_borderContainer);
            host.selectChild(_borderContainer);
            
        },
        
        _getConfig: function(file) {
            
            var _response = null;
            
            dojo.xhrGet({
                url: file,
                handleAs:"json",
                error: function() {
                    _response = null;
                },
                load: function(data){
                    
                    _response = data;
                    
                },
                sync: true
            });
            
            return _response;
        }
        
        
    }
);
