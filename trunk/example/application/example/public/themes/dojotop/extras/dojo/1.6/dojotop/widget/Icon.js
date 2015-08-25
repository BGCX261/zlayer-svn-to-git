/**
 *
 */

dojo.require("dijit._Widget");
dojo.require("dijit._Templated");
dojo.require("zlayer.navigation.Href");
dojo.require("zlayer.util._UniqueId");
dojo.require("dojotop.widget.icon.fx.Jelly");
dojo.require("dojotop.widget.icon.fx.FadeBack");

dojo.provide("dojotop.widget.Icon");

dojo.declare("dojotop.widget.Icon",
    [dijit._Widget, dijit._Templated, zlayer.util._UniqueId],
    {
        href: '',
        target: '',
        src: '',
        label: '',
        bgColorDefault: 'white',
        bgColorOver: '#E0F0FF',
        bgColorClick: '#ABD6FF',
        radius: '5',
        _onMouseOverFXStarted: false,

        templateString: dojo.cache("dojotop.widget", "templates/Icon.html"),

        baseClass: "dojotopWidgetIcon",

        postCreate: function(){

            this.inherited(arguments);
            this.connect(this.listNode, "onmouseover", "onMouseOver");
            this.connect(this.listNode, "onmouseout", "onMouseOut");
            this.connect(this.listNode, "onclick", "onClick");
            this._setStylesheet();

        },

        onMouseOver: function(e){

            this.inherited(arguments);
            dojo.style(this.listNode, "cursor", "pointer");

            dojo.animateProperty({
              node: this.listNode,
              properties: {
                  "background-color": this.bgColorOver
              }
            }).play();

            if (!this._onMouseOverFXStarted) {
                this._onMouseOverFXStarted = true;
                dojo.query(this.imgNode).instantiate("dojotop.widget.icon.fx.Jelly");
            }

        },

        onMouseOut: function(e){

            this.inherited(arguments);
            //dojo.style(this.listNode, "background-color", this.bgColorDefault);
            dojo.animateProperty({
              node: this.listNode,
              properties: {
                  "background-color": this.bgColorDefault
              }
            }).play();

        },

        onClick: function(e){

            this.inherited(arguments);
            /*
            zlayer.Href({
                    href: this.href,
                    format: "html-json",
                    method: "GET",
                    target: this.target,
                }).changeContent();
            */

            dojo.style(this.listNode, "background-color", this.bgColorClick);

            var _click = dojo.animateProperty({
              node: this.listNode,
              duration: 300,
              properties: {
                  "background-color": this.bgColorOver
              }
            }).play();

        },

        _setStylesheet: function(){

            dojo.style(this.listNode, "float", "left");
            dojo.style(this.listNode, "text-align", "center");
            dojo.style(this.listNode, "padding", "5px 5px 5px 5px");
            dojo.style(this.listNode, "width", "58px");
            dojo.style(this.listNode, "background-color", this.bgColorDefault);

            dojo.style(this.imgContainerNode, "width", "52px");
            dojo.style(this.imgContainerNode, "height", "52px");

            dojo.style(this.imgNode, "width", "48px");
            dojo.style(this.imgNode, "height", "48px");
            //dojo.attr(this.imgNode, "src", this.imgSrc);

            dojo.attr(this.imgNode,"id","dojotabtopWidgetIcon" + this.gen());

            if ( this.radius > 0 ) {

                dojo.style(this.listNode, "-moz-border-radius", this.radius + "px");
                dojo.style(this.listNode, "-webkit-border-radius", this.radius + "px");
                dojo.style(this.listNode, "border-radius", this.radius + "px");

            }

        },
    }
);


/*
.iconList
{
    float: left;
    text-align: center;
    padding: 5px 5px 5px 5px;
    width: 58px;
}

.iconListImg
{
    width: 48px;
    height: 48px;
}

.iconListImgBox
{
    width: 52px;
    height: 52px;
}



<a dojoType="zlayer.Href" href="<?= $this->baseUrl('index/index') ?>" target="content">

*/