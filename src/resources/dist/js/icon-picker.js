void 0===Craft.IconPicker&&(Craft.IconPicker={}),function(e){Craft.IconPicker.Input=Garnish.Base.extend({container:null,$selectize:null,init:function(t){this.options=t;var i=this;this.loadSpriteSheets(),this.loadFonts(),this.container=e("#"+t.inputId+"-field"),this.$selectize=this.container.find(".icon-picker-select"),this.$selectize.selectize({maxItems:1,maxOptions:100,create:!1,render:{item:function(e,t){if("svg"==e.type)var i='<img src="'+e.url+'" alt="'+t(e.text)+'" />';else if("sprite"==e.type)var i='<svg viewBox="0 0 1000 1000"><use xlink:href="#'+e.url+'" /></svg>';else if("glyph"==e.type)var i='<span class="icon-picker-font font-face-'+e.name+'">'+e.url+"</span>";return'<div class="icon-picker-thumb"><div class="icon-picker-thumb-icon">'+i+"</div><span>"+t(e.text)+"</span></div>"},option:function(e,t){if("svg"==e.type)var n='<img src="'+e.url+'" alt="'+t(e.text)+'" title="'+t(e.text)+'" />';else if("sprite"==e.type)var n='<svg viewBox="0 0 1000 1000"><use xlink:href="#'+e.url+'" /></svg>';else if("glyph"==e.type)var n='<span class="icon-picker-font font-face-'+e.name+'">'+e.url+"</span>";var s;return'<div class="icon-picker-item"><div class="icon-picker-item-wrap"><div class="icon-picker-item-icon">'+n+'</div><span class="icon-picker-item-label">'+(i.options.settings.showLabels?t(e.text):"")+"</span></div></div>"}}})},loadFonts:function(){for(var t=0;t<this.options.fonts.length;t++){var i=this.options.fonts[t];if(-1==e.inArray(i.name,Craft.IconPicker.Cache.fonts)){Craft.IconPicker.Cache.fonts.push(i.name);var n='@font-face {font-family: "font-face-'+i.name+'";src: url("'+i.url+'");font-weight: normal;font-style: normal;}.font-face-'+i.name+' {font-family: "font-face-'+i.name+'" !important;}';e("head").append('<style type="text/css">'+n+"</style>")}}},loadSpriteSheets:function(){for(var t=0;t<this.options.spriteSheets.length;t++){var i=this.options.spriteSheets[t];-1==e.inArray(i.name,Craft.IconPicker.Cache.stylesheets)&&(Craft.IconPicker.Cache.stylesheets.push(i.name),e.get(i.url,function(t){var n=document.createElement("div");n.innerHTML=(new XMLSerializer).serializeToString(t.documentElement),$svg=e(n).find("> svg"),$svg.attr("id","icon-picker-spritesheet-"+i.name),$svg.css("display","none").prependTo("body")}))}}})}(jQuery);
//# sourceMappingURL=icon-picker.js.map