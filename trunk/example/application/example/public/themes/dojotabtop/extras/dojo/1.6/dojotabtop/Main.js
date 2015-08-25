/*
 *
 */

dojo.require("dojotabtop.Accor");
dojo.require("zlayer.util._Url");
dojo.require("dojotabtop.Interface");

dojo.provide("dojotabtop.Main");

dojo.declare("dojotabtop.Main",
    [zlayer.util._Url],
    {
        defaultLayout: function() {
            // get config and set response
            var response = null;
            dojo.xhrGet({
                url: dojo.config.ZlBaseThemeUrl + "config/main.json",
                handleAs:"json",
                load: function(data){
                    // set logo
                    
                    var logoNode = dojo.byId("logo"); 
                    dojo.attr(logoNode, "src", dojo.config.ZlBaseThemeUrl + "config/images/logo.png");
                    
                    if (data.menu) {
                        var _accorPane = new dojotabtop.Accor({data: data.menu, moduleIcon: true});
                        var menu = dijit.byId('menu');
                        menu.addChild(_accorPane);
                    }
                    
                    var _interface = dojotabtop.Interface({
                        "module": dojo.config.ZlModule,
                        "controller": dojo.config.ZlController,
                        "action": dojo.config.ZlAction
                    });
                    
                    _interface.build();
                },
                sync: true
            });
            
            this.fadeInLoader();
        },
        
        loginLayout: function() {
            this.fadeInLoader();
        },
        
        fadeInLoader: function() {
            dojo.anim("preloader", { opacity: 0 }, 1000, false, this.removeLoader);
        },
        
        removeLoader: function() {
            dojo.style("preloader", "display", "none")
        }
    }
);
