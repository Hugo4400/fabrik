var FbElementList=new Class({Extends:FbElement,type:"text",initialize:function(b,a){this.parent(b,a);this.addSubClickEvents();this._getSubElements();if(this.options.allowadd===true&&this.options.editable!==false){this.watchAddToggle();this.watchAdd()}},_getSubElements:function(){var a=this.getElement();if(!a){this.subElements=$A()}else{this.subElements=a.getElements("input")}return this.subElements},addSubClickEvents:function(){this._getSubElements().each(function(a){a.addEvent("click",function(b){Fabrik.fireEvent("fabrik.element.click",[this,b])})})},addNewEvent:function(action,js){if(action==="load"){this.loadEvents.push(js);this.runLoadEvent(js)}else{this._getSubElements();c=this.form.form;var delegate=action+":relay(input[type="+this.type+"])";c.addEvent(delegate,function(event,target){typeOf(js)==="function"?js.delay(0):eval(js)})}},checkEnter:function(a){if(a.key==="enter"){a.stop();this.startAddNewOption()}},startAddNewOption:function(){var h=this.getContainer();var a=h.getElement("input[name=addPicklistLabel]");var b=h.getElement("input[name=addPicklistValue]");var e=a.value;if(b){val=b.value}else{val=e}if(val===""||e===""){alert(Joomla.JText._("PLG_ELEMENT_CHECKBOX_ENTER_VALUE_LABEL"))}else{var g=this.subElements.getLast().findUp("li").clone();var f=g.getElement("input");f.value=val;f.checked="checked";if(this.type==="checkbox"){var d=f.name.replace(/^(.*)\[.*\](.*?)$/,"$1$2");f.name=d+"["+(this.subElements.length)+"]"}g.getElement("span").set("text",e);g.inject(this.subElements.getLast().findUp("li"),"after");this._getSubElements();if(b){b.value=""}a.value="";this.addNewOption(val,e);if(this.mySlider){this.mySlider.toggle()}}},watchAdd:function(){var a;if(this.options.allowadd===true&&this.options.editable!==false){var b=this.getContainer();b.getElements("input[name=addPicklistLabel], input[name=addPicklistValue]").addEvent("keypress",function(d){this.checkEnter(d)}.bind(this));b.getElement("input[type=button]").addEvent("click",function(d){d.stop();this.startAddNewOption()}.bind(this));document.addEvent("keypress",function(d){if(d.key==="esc"&&this.mySlider){this.mySlider.slideOut()}}.bind(this))}}});