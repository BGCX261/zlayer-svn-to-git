/**
 * 
 */

dojo.provide("zlayer.util._UniqueId");
dojo.declare("zlayer.util._UniqueId", 
    null, 
    {
        gen: function(){
                var rand = 1 + Math.floor((Math.random()*32767));
                return rand;
        }
    }
);