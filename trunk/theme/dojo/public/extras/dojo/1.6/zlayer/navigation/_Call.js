/**
 * 
 */

dojo.require("dojo.io.script");
dojo.require("dijit.Dialog");
dojo.require("dijit.layout.ContentPane");
dojo.require("dijit.layout.TabContainer");
dojo.require("zlayer.util._Url");

dojo.provide("zlayer.navigation._Call");

dojo.declare("zlayer.navigation._Call", 
    [zlayer.util._Url], 
    {
        hash: false,
        format: "html-json",
        method: "GET",
        target: "content",
        request: "",
        callBackTarget: true,
        _renderNode: dojo.byId(this.renderOn),
        _handleAs: "json",
        _call: dojo.xhrGet,
        _data: false,
        
        postCreate: function(){
            this.inherited(arguments);
            
            if ((this.format == "html-json") || (this.format == "json")) {
                this._handleAs = "json";
            }
            
            if (this.method.toUpperCase() == "POST") {
                this._call = dojo.xhrPost;
            }
            
        },
        
        setRequest: function(request) {
            this.request = request;
        },
        
        setTarget: function(target) {
            this.target = target;
        },
        
        setMethod: function(method) {
            this.method = method;
        },
        
        setCallBackTarget: function(status) {
            this.callBackTarget = status;
        },
        
        parseMessenger: function(messenger) {
            return messenger;
        },
        
        getData: function() {
        
            var _request = this.request + "/__format/" + this.format;
            var _handleAs = this._handleAs
            var _loadCss = this._loadCss;
            var _dialog = this._dialog;
            var _id = this.id;
            var _destroyDescendants = this._destroyDescendants;
            var _target = this.target;
            var parseMessenger = this.parseMessenger;
            
            var _callBackTargetFunction = null;
            if ((dojo.config.ZlCallBackTarget) && (this.callBackTarget)) {
                var _callBackTargetFunction = dojo.config.ZlCallBackTarget;
            }
            
            this._call({
                form: this.id,
                url: _request,
                handleAs: _handleAs,
                failOk: true,
                handle: function(data,args){ 
                    
                    if(data instanceof Error) {
                    
                        if (_handleAs.toUpperCase() == "JSON") {
                            var fromMethod = dojo.fromJson;
                        } else { // @todo: other methods
                            var fromMethod = dojo.fromXml;
                        }
                    
                        var response = fromMethod(data.responseText);
                    
                        if (response.messenger) {
                            _dialog(null, parseMessenger(response.messenger), response.exception, response.trace, response.params);
                        } else {
                            _dialog('An unexpected error occurred', data);
                        }
                        
                        return false;
                        
                    } else if (data.body) {
                    
                        // unload css
                        /*
                        doc = window.document;
                        var links = doc.getElementsByTagName("LINK");
                        for (var i in links) {
                            var node = links[i];
                            if (node.title == "_temp_") {
                                alert(node.href);
                                node.parentNode.removeChild(node);
                            };
                        };
                        */
    
                        // set dojo djconfig
                        if (data.dojo.djConfig) {
                            dojo.config = data.dojo.djConfig;
                        }
    
                        // headScript
                        if (data.headScript) {
                            for(var i in data.headScript) {
                                dojo.io.script.get({url: data.headScript[i]});
                            }
                        }
    
                        // headLink
                        if (data.headLink) {
                            for(var i in data.headLink) {
                                //dojo.io.script.get({url: data.headLink[i]});
                                _loadCss(data.headLink[i]);
                            }
                        }
    
                        // load dojo modules
                        if (data.dojo.modules) {
                            for(var i in data.dojo.modules) {
                                dojo.require(data.dojo.modules[i]);
                            }
                        }
    
                        // load dojo stylesheets
                        if (data.dojo.stylesheets) {
                            for(var i in data.dojo.stylesheets) {
                                //dojo.io.script.get({url: data.dojo.stylesheets[i]});
                                _loadCss(data.dojo.stylesheets[i]);
                            }
                        }
    
                        //@todo add dojo onload
    
                        // set body
                        if (_callBackTargetFunction) {
                            _target = callBackTarget();
                            var targetNode = dijit.byId(_target);
                        } else {
                            var targetNode = dijit.byId(_target);
                        }
                        
                        if (targetNode == null) {
                            if (dojo.config.isDebug) {
                                _dialog(null, 'Target not found in HTML: ' + _target);
                            }
                        } else {
                            _destroyDescendants(targetNode);
                            targetNode.set('content',data.body);
                            try {
                                dojo.parser.parse(_target);
                            } catch (e) {}
                        }
                        
                    }
                    
                    if (data.messenger) {
                        _dialog(null, parseMessenger(data.messenger));
                    }
                    
                }
            });
            
        },
        
        _destroyDescendants: function(node) {
            
            //var _destroyDescendants = this._destroyDescendants();
            dojo.forEach(node.getDescendants(), function(widget){
                //_destroyDescendants(node);
                widget.destroyDescendants();
                try {
                    widget.destroy(true);
                } catch (e) {}
            });
            
        },
        
        _loadCss: function(url) {
            doc = window.document;
            for (var i in doc.styleSheets) {
                if (doc.styleSheets[i].href == url) {
                    return;
                };
            };
            var node = doc.createElement("link");
            node.type = "text/css";
            node.rel = "stylesheet";
            node.href = url;
            doc.getElementsByTagName("HEAD")[0].appendChild(node);
        },
        
        _dialog: function(title, message, exception, trace, params) {
            
            var dialog = new dijit.Dialog({
                title: title
            });
            
            var messageContentPane = new dijit.layout.ContentPane({
                title: "Message",
            });
            
            var _Url = new zlayer.util._Url();
            
            var messageCount = 0;
            
            if (typeof(message) == 'string') {
                
                var line = document.createElement("div");
                line.style = "clear: both;";
                
                var imageContent = document.createElement("div");
                dojo.style(imageContent, "float", "left");
                dojo.style(imageContent, "padding", "10px");
                
                var imageTag = document.createElement("img");
                imageTag.src = _Url.baseUrl("/themes/dojo/extras/dojo/" + dojo.version.major + "." + dojo.version.minor + "/zlayer/icons/images/error.png");
                imageContent.appendChild(imageTag);
                
                var messageContent = document.createElement("div");
                dojo.style(messageContent, "padding", "5px");
                messageContent.appendChild(document.createTextNode(message));
                
                line.appendChild(imageContent);
                line.appendChild(messageContent);
                
                messageContentPane.domNode.appendChild(line);
                messageCount++;
                
            } else {
                
                for(var type in message) {
                    var messageType = message[type];                               
                    for(var index in messageType) {
                        var msg = messageType[index];
                        for(var key in msg) {
                            if (msg[key]) {
                                var line = document.createElement("div");
                                line.style = "clear: both;";
                                var imageContent = document.createElement("div");
                                dojo.style(imageContent, "float", "left");
                                dojo.style(imageContent, "padding", "10px");
                                var imageTag = document.createElement("img");
                                imageTag.src = _Url.baseUrl("/themes/dojo/extras/dojo/" + dojo.version.major + "." + dojo.version.minor + "/zlayer/icons/images/" + type + ".png");
                                imageContent.appendChild(imageTag);
                                var messageContent = document.createElement("div");
                                dojo.style(messageContent, "padding", "5px");
                                messageContent.appendChild(document.createTextNode(msg[key]));
                                line.appendChild(imageContent);
                                line.appendChild(messageContent);
                                messageContentPane.domNode.appendChild(line);
                                messageCount++;
                            }
                        }
                    }
                }
                
            }
            
            
            
            if (exception) {
            
                var exceptionContentPane = new dijit.layout.ContentPane({
                    title: "Exception"
                });

                var exceptionCount = 0;
                for(var item in exception) {
                    line = document.createElement("div");
                    line.appendChild(document.createTextNode(exception[item]));
                    exceptionContentPane.domNode.appendChild(line);
                    exceptionCount++;
                }
                
                var traceContentPane = new dijit.layout.ContentPane({
                    title: "Trace"
                });

                for(var item in trace) {
                    line = document.createElement("div");
                    pre = document.createElement("pre");
                    pre.appendChild(document.createTextNode(trace[item]));
                    line.appendChild(pre);
                    traceContentPane.domNode.appendChild(line);
                }
            
                var paramsContentPane = new dijit.layout.ContentPane({
                    title: "Parameters"
                });
                
                for(var item in params) {
                    line = document.createElement("div");
                    field = document.createElement("strong");
                    field.appendChild(document.createTextNode(item + ": "));
                    line.appendChild(field);
                    line.appendChild(document.createTextNode(params[item]));
                    paramsContentPane.domNode.appendChild(line);
                }
                
                if ((messageCount>0)&&(exceptionCount>0)) {
                    
                    var dialogTabContainer = new dijit.layout.TabContainer({ style: "width: 400px; height: 300px"}).placeAt(dialog.containerNode);
                    
                    if (messageCount>0) {
                        dialogTabContainer.addChild(messageContentPane);
                    }
                    
                    if (exceptionCount>0) {
                        dialogTabContainer.addChild(exceptionContentPane);
                        dialogTabContainer.addChild(traceContentPane);
                        dialogTabContainer.addChild(paramsContentPane);
                    }
                    
                    dialog.show();
                    
                } else if (messageCount>0) {
                    
                    messageContentPane.placeAt(dialog.containerNode);
                    dialog.show();
                    
                }
            
            } else if (messageCount>0) {
                messageContentPane.placeAt(dialog.containerNode);
                dialog.show();
            }
            
        }
        
    }
);