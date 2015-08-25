/**
 * 
 */

dojo.provide("zlayer.form.MultiCheckBox");

dojo.declare("zlayer.form.MultiCheckBox", 
    [dijit._Widget],
    {
        _firstCheckbox : null,
        _checkboxChecked : 0,
        _checkboxUnChecked : 0,
        _checkboxCount : 0,
        _checkboxDisabled : 0,
        _atLeastOneChecked : false,
        _errorMessage : 'A opção selecionada não é válida',

        validate: function(){
            this._countCheckbox();

            if(this._atLeastOneChecked == 'true'){
                if (this._checkboxChecked == 0) return false
            }

            return true;
        },

        _countCheckbox: function(){
            _checkboxCount = 0;
            _checkboxChecked = 0;
            _checkboxUnChecked = 0;
            _checkboxDisabled = 0;
            _firstCheckbox = null;

            dojo.query('#' + this.id + '> div').forEach(function(node){
                dNode = dijit.byNode(node);
                if(typeof(dNode) != 'undefined'){
                    if(dNode.get('type') == 'checkbox'){
                        _checkboxCount++;
                        if(_firstCheckbox == null) _firstCheckbox = dNode;
                        if(dNode.get('checked')) _checkboxChecked++;
                        if(dNode.get('disabled')) _checkboxDisabled++;
                        else _checkboxUnChecked++;
                    }
                }
            });

            if(_checkboxChecked == 0 && _checkboxDisabled == _checkboxCount){
                _checkboxChecked = _checkboxDisabled;
            }

            this._checkboxCount = _checkboxCount;
            this._checkboxChecked = _checkboxChecked;
            this._checkboxUnChecked = _checkboxUnChecked;
            this._firstCheckbox = _firstCheckbox;
        },

        startup: function(){
            this._countCheckbox();
            this._atLeastOneChecked = dojo.attr(this.domNode,'atleastonechecked')

            if(this._atLeastOneChecked == 'undefined'){
                this._atLeastOneChecked = false;
            }

            if(dojo.attr(this.domNode,'errormessage') != null){
                this._errorMessage = dojo.attr(this.domNode,'errormessage');
            }
        },

        focus: function(){
            this._firstCheckbox.focus();
            try{
                this._displayMessage(this._errorMessage, this._firstCheckbox.domNode);
            }
            catch(e){
                alert(e);
            }
        },

        _onBlur: function(){
            this._displayMessage('', this._firstCheckbox.domNode);
        },

        _displayMessage: function(/*String*/ message, node){
            dijit.hideTooltip(node);
            if(message != '' && this._focused){
                node.style = 'border:1px red solid';
                dijit.showTooltip(message, node);
            }
        }
    }
);