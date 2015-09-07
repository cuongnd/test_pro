/**
 * ------------------------------------------------------------------------
 * JA Animation module for Joomla 2.5 & 3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

var JADependForm = new Class({ 	
	
	initialize: function(){
		this.depends = {};
		this.controls = {};
	},
	
	register: function(to, depend){
		var controls = this.controls;
		
		if(!controls[to]){
			controls[to] = [];
			
			var inst = this;
			if(typeof jQuery != 'undefined' && jQuery.fn.jquery > '1.7.0'){
				jQuery(this.elmsFrom(to)).on('change', function(e){
					inst.change(this);
				});
			}
			this.elmsFrom(to).addEvent('change', function(e){
				inst.change(this);
			});
		}
		
		if(controls[to].indexOf(depend) == -1){
			controls[to].push(depend);
		}
	},
	
	change: function(ctrlelm){
		var controls = this.controls,
			depends = this.depends,
			ctrls = controls[ctrlelm.name];
			
		if(!ctrls){
			ctrls = controls[ctrlelm.name.substr(ctrlelm.name.length - 2)];
		}
		
		if(!ctrls){
			return false;
		}
		
		ctrls.each(function(dpd){
			var showup = true;
			
			Object.each(depends[dpd], function(cvals, ctrl){
				if(showup){
					var celms = this.elmsFrom(ctrl);
					showup = showup && celms.every(function(celm){ return !celm._disabled; });
					if(showup){
						showup = showup && this.valuesFrom(celms).some(function(val){ return cvals.contains(val); });
					}
				}
			}, this);
			
			this.elmsFrom(dpd).each(function(delm){
				if(showup){
					this.enable(delm);
				} else {
					this.disable(delm);
				}
			}, this);
			
			if(controls[dpd] && controls[dpd] != dpd){
				this.elmsFrom(dpd)[0].fireEvent('change');
			}
			
		}, this);
	},
	
	add: function(control, info){
		
		var depends = this.depends,
			name = info.group + '[' + control + ']';
			
		info = Object.append({
			group: 'params',
			hiderow: true,
			control: name
		}, info);
		
		info.hiderow = !!info.hiderow;
		
		info.elms.split(',').each(function(el){
			var elm = info.group +'[' + el.trim() + ']';
			
			if (!depends[elm]) {
				depends[elm] = {};
			}
			
			if (!depends[elm][name]) {
				depends[elm][name] = [];
			}
			
			depends[elm][name].push(info.val);
			
			this.register(name, elm);
			
		}, this);
	},
	
	start: function(){
		$(document.adminForm).getElements('h4.block-head').each(function(el){
			this.closest(el, 'li, div.control-group').addClass('segment')
		}, this);
		
		$(document.adminForm).getElements('.hideanchor').each(function(el){
			this.closest(el, 'li, div.control-group').addClass('hide');
		}, this);

		this.update();

		console.log(this.depends);
	},
	
	update: function () {
		Object.each(this.controls, function(ctrls, ctrl){
			this.elmsFrom(ctrl).fireEvent('change');
		}, this);
	},
	
	enable: function (el) {
		el._disabled = false; //selector 'li' is J2.5 compactible
		this.closest(el, 'li, div.control-group').setStyle('display', 'block');
	},
	
	disable: function (el) {
		el._disabled = true; //selector 'li' is J2.5 compactible
		this.closest(el, 'li, div.control-group').setStyle('display', 'none');
	},
	
	elmsFrom: function(name){
		var el = document.adminForm[name];
		if(!el){
			el = document.adminForm[name + '[]'];
		}
		
		//Mootools 1.4.5 compatible
		return (typeOf(el) == 'element' && el.get('tag') == 'select') ? $$([el]) : $$(el);
	},
	
	valuesFrom: function(els){
		var vals = [];
		
		((typeOf(els) == 'element' && els.get('tag') == 'select') ? $$([els]) : $$(els)).each(function(el){
			var type = el.type,
				value = (el.get('tag') == 'select') ? el.getSelected().map(function(opt){
					return document.id(opt).get('value');
				}) : ((type == 'radio' || type == 'checkbox') && !el.checked) ? null : el.get('value');

			vals.include(Array.from(value));
		});
		
		return vals.flatten();
	},
	
	closest: function(elm, sel){
		var parents = elm.getParents(sel),
			cur = elm;
			
		while(cur){
			if(parents.contains(cur)){
				return cur;
			}
			
			cur = cur.getParent();
		}
	},
	
	segment: function(seg){
		if($(seg).hasClass('close')){
			this.showseg(seg);
		} else {
			this.hideseg(seg);
		}
	},
	
	showseg: function(seg){
		
		var segelm = $(seg),
			snext = this.closest(segelm, 'li, div.control-group').getNext();
		
		while(snext && !snext.hasClass('segment')){
			if(!snext.hasClass('hide')){
				snext.setStyle('display', snext.retrieve('jdisplay') || '');
			}
			snext = snext.getNext();
		}
		
		segelm.removeClass('close').addClass('open');  
	},
	
	hideseg: function(seg){
		var segelm = $(seg),
			snext = this.closest(segelm, 'li, div.control-group').getNext();
		
		while(snext && !snext.hasClass('segment')){
			if(!snext.hasClass('hide')){
				snext.store('jdisplay', snext.getStyle('display')).setStyle('display', 'none');
			}
			snext = snext.getNext();
		}
		
		segelm.removeClass('open').addClass('close');  
	}
});

var JAProfileConfig = new Class({
	
	vars: {
	},
	
	initialize: function(optionid){
		var vars = this.vars;
		vars.group = 'jaform';
		vars.el = $(optionid);

		if(vars.el){
			vars.el.addEvent('change', function(){
				JAFileConfig.inst.changeProfile(this.value);
			});

			if(typeof jQuery != 'undefined' && jQuery.fn.jquery > '1.7.0'){
				jQuery(vars.el).on('change', function(){
					JAFileConfig.inst.changeProfile(this.value);
				});
			}
		}
		
		var adminlist = $('module-sliders');
		if(adminlist){
			adminlist = adminlist.getElement('ul.adminformlist');
			if(adminlist){
				new Element('li', {'class':'clearfix level2'}).inject(adminlist);
			}
		}
	},
	
	changeProfile: function(profile){
		console.log('change profile ', profile);
		if(profile == ''){
			return;
		}
		
		this.vars.active = profile;
		this.fillData();
		
		if(typeof JADepend != 'undefined' && JADepend.inst){
			JADepend.inst.update();
		}
	},
	
	serializeArray: function(){
		var vars = this.vars,
			els = [],
			allelms = document.adminForm.elements,
			pname1 = vars.group + '\\[params\\]\\[.*\\]',
			pname2 = vars.group + '\\[params\\]\\[.*\\]\\[\\]';
			
		for (var i = 0, il = allelms.length; i < il; i++){
		    var el = $(allelms[i]);
			
		    if (el.name && ( el.name.test(pname1) || el.name.test(pname2))){
		    	els.push(el);
		    }
		}
		
		return els;
	},

	fillData: function (){
		var vars = this.vars,
			els = this.serializeArray(),
			profile = JAFileConfig.profiles[vars.active];
			
		if(els.length == 0 || !profile){
			return;
		}
		
		els.each(function(el){
			var name = this.getName(el),
				values = (profile[name] != undefined) ? profile[name] : '';
			
			this.setValues(el, Array.from(values));

			//J3.0 compatible
			if(el.hasClass('chzn-done') && typeof jQuery != 'undefined'){
				var chosen = jQuery(el).trigger('liszt:updated').data('chosen');
				if(chosen){
					chosen.current_value = values;
				}
			}
		}, this);
	},
	
	valuesFrom: function(els){
		var vals = [];
		
		((typeOf(els) == 'element' && els.get('tag') == 'select') ? $$([els]) : $$(els)).each(function(el){
			var type = el.type,
				value = (el.get('tag') == 'select') ? el.getSelected().map(function(opt){
					return document.id(opt).get('value');
				}) : ((type == 'radio' || type == 'checkbox') && !el.checked) ? null : el.get('value');

			vals.include(Array.from(value));
		});
		
		return vals.flatten();
	},
	
	setValues: function(el, vals){
		if(el.get('tag') == 'select'){
			var selected = false;
			for(var i = 0, il = el.options.length; i < il; i++){
				var option = el.options[i];
				option.selected = false;
				if (vals.contains (option.value)) {
					option.selected = true;
					selected = true;
				}
			}
			
			if(!selected){
				el.options[0].selected = true;
			}
		}else {
			if(el.type == 'checkbox' || el.type == 'radio'){
				el.set('checked', vals.contains(el.value));
			} else {
				el.set('value', vals[0]);
			}
		}
	},
	
	getName: function(el){
		var matches = el.name.match(this.vars.group + '\\[params\\]\\[([^\\]]*)\\]');
		if (matches){
			return matches[1];
		}
		
		return '';
	},
	
	/****  Functions of Profile  ----------------------------------------------   ****/
	deleteProfile: function(){
		if(confirm(JAFileConfig.langs.confirmDelete)){			
			this.submitForm(JAFileConfig.mod_url + '?jaction=delete&profile=' + this.vars.active, {}, 'profile');
		}		
	},
	
	cloneProfile: function (){
		var nname = prompt(JAFileConfig.langs.enterName);
		
		if(nname){
			nname = nname.replace(/[^0-9a-zA-Z_-]/g, '').replace(/ /, '').toLowerCase();
			if(nname == ''){
				alert(JAFileConfig.langs.invalidName);
				return this.cloneProfile();
			}
			
			JAFileConfig.profiles[nname] = JAFileConfig.profiles[this.vars.active];
			
			this.submitForm(JAFileConfig.mod_url + '?jaction=duplicate&profile=' + nname + '&from=' + this.vars.active, {}, 'profile');
		}
	},
	
	saveProfile: function (task){
		/* Rebuild data */		
		
		if(task){
			JAFileConfig.profiles[this.vars.active] = this.rebuildData();
			this.submitForm(JAFileConfig.mod_url + '?jaction=save&profile=' + this.vars.active, JAFileConfig.profiles[this.vars.active], 'profile', task);
		}
	},
	
	submitForm: function(url, request, type, task){
		if(JAFileConfig.run){
			JAFileConfig.ajax.cancel();
		}
		
		JAFileConfig.run = true;
    	
		JAFileConfig.ajax = new Request.JSON({
			url: url, 
			onComplete: function(result){
				
				JAFileConfig.run = false;
				
				if(result == ''){
					return;
				}
				
				if(!task){
					alert(result.error || result.successful);
				}

				var vars = this.vars;
				if(result.profile){
					switch (result.type){	
						case 'new':
							Joomla.submitbutton(document.adminForm.task.value);
						break;
						
						case 'delete':
							if(result.template == 0 || typeof(result.template) == 'undefined'){
								var opts = vars.el.options;
								
								for(var j = 0, jl = opts.length; j < jl; j++){
									if(opts[j].value == result.profile){
										vars.el.remove(j);
										break;
									}
								}
								//J3.0 compatible
								if(vars.el.hasClass('chzn-done') && typeof jQuery != 'undefined'){
									jQuery(vars.el).trigger('liszt:updated');
								}
								
							}
							
							vars.el.options[0].selected = true;					
							this.changeProfile(vars.el.options[0].value);
							
						break;
						
						case 'duplicate':
							vars.el.options[vars.el.options.length] = new Option(result.profile, result.profile);							
							vars.el.options[vars.el.options.length - 1].selected = true;
							this.changeProfile(result.profile);
							//J3.0 compatible
							if(vars.el.hasClass('chzn-done') && typeof jQuery != 'undefined'){
								jQuery(vars.el).trigger('liszt:updated');
							}
						break;
						
						default:
					}
				}
			}.bind(this),
			
			onSuccess: function(){
				if(task){
					Joomla.submitform(task, document.getElementById('module-form'));
				}
			}
		}).post(request);
	},
	
	rebuildData: function (){
		var els = this.serializeArray(),
			json = {};
			
		els.each(function(el){
			var values = this.valuesFrom(el);
			if(values.length){
				json[this.getName(el)] = el.name.substr(-2) == '[]' ? values : values[0];
			}
		}, this);
		
		return json;
	}
});

var JADepend = window.JADepend || {},
	JAFileConfig = window.JAFileConfig || {};

JADepend.inst = new JADependForm();
window.addEvent('load', function() {
	setTimeout(JADepend.inst.start.bind(JADepend.inst), 100);
});