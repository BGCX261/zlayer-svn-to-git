/**
 * 
 */

dojo.provide("zlayer.util._Url");
dojo.declare("zlayer.util._Url", 
	null, 
    {
		baseUrl: function(url){
            return dojo.config.ZlBaseUrl + url;
		},
		
		baseThemeUrl: function(url){
            return dojo.config.ZlBaseThemeUrl + url;
		}
		
    }
);