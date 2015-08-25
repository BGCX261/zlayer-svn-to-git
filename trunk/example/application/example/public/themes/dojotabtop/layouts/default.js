//dojo.require("dojotabtop.Main");
//dojo.require("dojotabtop.Interface");


var bootstrap = function(){
    
    var main = new dojotabtop.Main;
    main.defaultLayout();
    
};

var callBackTarget = function(){
    
    var _interface = dojotabtop.Interface({
        "module": dojo.config.ZlModule,
        "controller": dojo.config.ZlController,
        "action": dojo.config.ZlAction
    });
    
    _interface.build();
    
    return _interface.getContentId();
};