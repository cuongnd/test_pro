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
var jaAnim = new Class({
	Implements: [Options],
	
	options: {
		direction: 'v', //h: horizontal, v: verical
		movetype: 'straight', //straight | sine | random | preset
		loop: 1, // 1: normal loop 2: circle loop 0: noloop
		duration: 20000,
		org_pos: {x:200,y:200},
		begin_pos: {x:200,y:500},
		end_pos: {x:200,y:0},
		pre_pos: {x:[100,200,300],y:[0,0,0]},
		step: 4,
		radius: 20,
		index: 1,
		desc: false, //whether show desc on mouse over
		changebg: false,
		bgurl: 'bird.png',
		framesize: 180,
		frameitem: 24,
		frameorder: 'v',
		changespeed: 1000,
		delay: 5000,
		screen: false,
		offsets: {x:120,y:20},
		fps: 50
	},
	
	initialize: function(item, options){
		this.setOptions(options);
		
		var jitem = $(item),
			jcookie = new Hash.Cookie(item);
		
		if(!jitem){
			return false;
		}
		
		var viewport = jitem.getParent().getParent().getParent();
		
		this.item = jitem;
		this.ck = jcookie;
		jcookie.load();
		
		if(jcookie.get('hide')){
			jitem.setStyle('display', 'none');
			return;
		}
		
		var	p_width = viewport.getWidth(),
			p_height = viewport.getHeight();
		
		if(this.options.direction == 'h'){
			p_width -= jitem.getWidth();
			
			if(this.options.end_pos.x == 'auto' && this.options.begin_pos.x != 'auto'){				
				this.options.end_pos.x = p_width;				
			}
			
			if(this.options.begin_pos.x == 'auto' && this.options.end_pos.x != 'auto'){
				this.options.begin_pos.x = p_width;
			}			
		}else{
			p_height -= jitem.getHeight();
			
			if(this.options.end_pos.y == 'auto'){
				this.options.end_pos.y = p_height;
			}
			if(this.options.begin_pos.y == 'auto'){
				this.options.begin_pos.y = p_height;
			}
		}	
		
		if(this.options.begin_pos.x == 'auto' && this.options.end_pos.x == 'auto'){
			this.options.begin_pos.x = 0;
			this.options.end_pos.x = p_width;
		}
		if(this.options.begin_pos.y == 'auto' && this.options.end_pos.y == 'auto'){
			this.options.begin_pos.y = 0;
			this.options.end_pos.y = p_height;
		}
		
		this.time = 0;
		
		tZindex = parseInt(this.options.index) + 1;
		
		window.addEvent('load',function(){
			if(this.ck.get('hide')){
				this.item.setStyle('display','none');
				return;
			}
			
			this.item.setStyle('display','block');
			this.start();
			
			if(this.options.changebg){
				this.bg = this.item.getElement('.changebg').setStyle('background','url('+ this.options.bgurl +') no-repeat scroll 0 0 transparent');
				this.loopbg();
			}
			
			if(this.options.desc){
				this.tips = new JATips (this.item, {
					'className': 'ja-anim',
					'fixed':true, 
					offsets: {
						'x':this.options.offsets.x,
						'y': this.options.offsets.y
					},
					onShow: function(){
						this.toolTip.setStyle('display','block');
					},
					onHide: function(){
						this.toolTip.setStyle('display','none');
					}
				});
				
				this.item.addEvent('mouseenter', function(e){
					if(this.click||this.showtip){
						return;
					}
					
					clearTimeout(this.t2);
					clearTimeout(this.delay);
					
					if(this.fx){
						this.fx.stop();
					}
					
					if(this.options.changebg){
						this.stoploop();
					}
					
				}.bind(this)).addEvent('mouseleave', function(e){
					if(this.showtip){
						return;
					}
					
					clearTimeout(this.delay);
					this.delay = this.mouselv.delay(1000, this, e);
					
				}.bind(this)).addEvent('click', function(e){
					if(!this.click){
						this.click = 1;
						if(this.options.changebg){
							this.stoploop();
							this.loopbg();
						}
					}else{
						this.click = 0;
					}
				}.bind(this)).addEvent('dblclick',function(e){
					this.fx.stop();
					
					if(this.options.changebg){
						this.stoploop();
					}
					
					clearTimeout(this.t2);
					clearTimeout(this.delay);
					
					this.dblclick = 1;
					
					new Fx.Tween(this.item, {
						property: 'opacity',
						duration: 1000,
						wait: false,
						onComplete: function(el){
							el.setStyle('display', 'none');
						}
					}).start(0);

					this.ck.set('hide', '1');
					this.ck.save();
				}.bind(this));	
				
				$(this.item.id + '-tip').addEvent('mouseenter',function(){
					this.showtip = 1;
					clearTimeout(this.delay);
				}.bind(this)).addEvent('mouseleave',function(e){
					this.showtip = 0;
					this.mouselv(e);
				}.bind(this));		
			}
			
		}.bind(this));		
		// Pause all animations when the current window is inactive
		
		var zwnd = window,
			focus = 'focus',
			blur = 'blur';
		
		if(Browser.ie && Browser.version <= 8){
			zwnd = document.body;
			focus = 'focusin';
			blur = 'focusout';
		}
		
		$(zwnd).addEvent(focus, function(){
			if(this.blur){
				this.options.org_pos.x = this.item.getStyle('left').toInt();
				this.options.org_pos.y = this.item.getStyle('top').toInt();

				if(isNaN(this.options.org_pos.x)){
					this.options.org_pos.x = 0;
				}
				if(isNaN(this.options.org_pos.y)){
					this.options.org_pos.y = 0;
				}
				
				clearTimeout(this.t2);
				clearTimeout(this.delay);
				
				if(this.fx){
					this.fx.stop();
				}		
				this.start();
				
				if(this.options.changebg){
					this.stoploop();
					this.loopbg();
				}
			}
			
			this.blur = 0;
		}.bind(this)).addEvent(blur, function(){
			this.blur = 1;
			
			clearTimeout(this.t2);
			clearTimeout(this.delay);
			
			if(this.fx){
				this.fx.stop();
			}
			
			if(this.options.changebg){
				this.stoploop();
			}
		}.bind(this));
	},
	hidetip: function(){
		$$('.ja-anim-tip').setStyle('opacity', '0');	
	},
	mouselv: function(e){
		if(this.click || this.dblclick){
			return;
		}		
		
		this.options.org_pos.x = this.item.getStyle('left').toInt();
		this.options.org_pos.y = this.item.getStyle('top').toInt();
		
		if(isNaN(this.options.org_pos.x)){
			this.options.org_pos.x = 0;
		}
		if(isNaN(this.options.org_pos.y)){
			this.options.org_pos.y = 0;
		}
		
		clearTimeout(this.t2);
		clearTimeout(this.delay);
		
		this.fx.stop();			
		this.start();
		
		if(this.options.changebg){
			this.stoploop();
			this.loopbg();
		}
	},
	start: function(){				
		//Set item to original position
		if(this.fx){
			this.fx.stop();
		}
			
		this.item.setStyles({
			'top': this.options.org_pos.y,
			'left': this.options.org_pos.x,
			'z-index': this.options.index
		});
		
		if(this.options.screen){
			this.item.setStyle('position','fixed');
		}
		
		//Begin moving
		switch(this.options.movetype){
			case 'straight':
				this.movestraight();
				break;
			case 'sine':
				this.movesine();
				break;
			case 'preset':
				this.movepreset();
				this.t2 = this.movepreset.periodical(this.options.delay,this);
				break;
			default:
				this.moverandom();
				break;
		}		
	},
	movestraight: function(){
		//Get finish position
		if(this.x==undefined){
			this.x = this.options.end_pos.x;		
		}
		if(this.y==undefined){
			this.y = this.options.end_pos.y;
		}
		if(this.options.loop == 2){
			
			var itemwidth = this.item.getWidth();
			var itemheight = this.item.getHeight();
			
			if(this.options.direction == 'h'){
				if(this.options.begin_pos.x < this.options.end_pos.x){
					this.x = parseInt(parseInt(this.options.end_pos.x) + itemwidth);
				}else{
					this.x = parseInt(parseInt(this.options.end_pos.x) - itemwidth);
				}
			}else{
				if(this.options.begin_pos.y < this.options.end_pos.y){
					this.y = this.options.end_pos.y + itemwidth;
				}else{
					this.y = this.options.end_pos.y - itemwidth;
				}
			}
		}
		var dur = this.getDuration({x:this.x,y:this.y});
		this.fx = new Fx.Morph(this.item,{
			fps: this.options.fps,
			duration: dur, 
			wait: false,
			transition: Fx.Transitions.linear,
			onComplete: function(){									
				if(this.options.loop == 1){
					if(this.x == this.options.end_pos.x){
						this.x = this.options.begin_pos.x;
					}else{
						this.x = this.options.end_pos.x;
					}				
					if(this.y == this.options.end_pos.y){
						this.y = this.options.begin_pos.y;
					}else{
						this.y = this.options.end_pos.y;
					}		
					this.movestraight();					
				}
				if(this.options.loop == 2){
					if(this.options.direction == 'h'){
						if(this.options.begin_pos.x < this.options.end_pos.x){
							$(this.item).setStyle('left',this.options.begin_pos.x - itemwidth);
						}else{
							$(this.item).setStyle('left',this.options.begin_pos.x + itemwidth);
						}								
						this.movestraight();
					}else{
						if(this.options.begin_pos.x < this.options.end_pos.x){
							$(this.item).setStyle('top',this.options.begin_pos.y - itemheight);
						}else{
							$(this.item).setStyle('top',this.options.begin_pos.y + itemheight);
						}	
						$(this.item).setStyle('top',this.options.begin_pos.y - itemheight);
						this.movestraight.delay(this.options.delay,this);
					}
				}
			}.bind(this)
		});
		this.fx.cancel();
		this.fx.start({
			'top': this.y+'px',
			'left': this.x+'px'
		});		
	},
	movesine: function(){
		
		if(this.fx){
			this.fx.stop();
		}
		
		switch(this.options.direction){
			case 'v':
				this.steplength = (this.options.end_pos.y - this.options.begin_pos.y)/this.options.step;
				if(this.options.begin_pos.y != this.options.org_pos.y && this.beginstep == undefined){
					for(var i = 0; i < this.options.step; i++){
						if(this.options.org_pos.y > (i * this.steplength)){
							this.beginstep = i + 1;
						}
					}					
				}
				break;
			case 'h':
				this.steplength = (this.options.end_pos.x - this.options.begin_pos.x)/this.options.step;
				if(this.options.begin_pos.x != this.options.org_pos.x && this.beginstep == undefined){
					for(var i = 0; i < this.step; i++){
						if(this.options.org_pos.x > (i * this.steplength)){
							this.beginstep = i + 1;
						}
					}
				}
				break;
		}
			
		if(this.beginstep == undefined){
			this.beginstep = 0;
		}
		
		this.stepduration = this.options.duration / this.options.step;
		if(this.options.direction == 'v'){
			this.cur = this.item.getStyle('top').toInt();
		}else{
			this.cur = this.item.getStyle('left').toInt();
		}
		
		if(isNaN(this.cur)){
			this.cur = this.item.getPosition().x;
		}
		
		if(this.time % 2 == 0){
			this.next = this.cur + this.steplength;
		}else{
			this.next = this.cur - this.steplength;
		}
		
		if(((this.time % 2 == 0 && this.beginstep == this.options.step - 1)||(this.time % 2 == 1 && this.beginstep == 0)) && this.options.loop){
			this.time++;
		}
		if(this.beginstep % 2 == 0){
			this.radius = this.options.radius;
		}else{
			this.radius = -this.options.radius;
		}			
		if(this.beginstep < this.options.step - 1 && this.time % 2 ==0){
			this.beginstep++;
		}
		if(this.beginstep > 0 && this.time & 2 != 0){
			this.beginstep--;
		}	
		switch(this.options.direction){
			case 'v':	
				var dur = this.getDuration({x:(this.options.org_pos.x + this.radius),y:this.next});					
				break;
			case 'h':
				var dur = this.getDuration({x:this.next,y:(this.options.org_pos.y + this.radius)});					
				break;
		}
		
		
		this.fx = new Fx.Morph(this.item,{
			fps: this.options.fps,
			duration: dur, 
			wait: false,
			transition: Fx.Transitions.linear,
			onComplete: this.movesine.bind(this)
		});
			
		switch(this.options.direction){
			case 'v':
				this.fx.start({
					'top': this.next,
					'left': this.options.org_pos.x + this.radius					
				});
			break;
			
			case 'h':
				this.fx.start({
					'left': this.next,
					'top': this.options.org_pos.y + this.radius					
				});
			break;
		}
	},
	moverandom: function(){
		this.v_length = this.options.end_pos.y - this.options.begin_pos.y;
		if(this.v_length < 0)this.v_length = - this.v_length;
		this.h_length = parseInt(this.options.end_pos.x - this.options.begin_pos.x);
		if(this.h_length < 0)this.h_length = - this.h_length;			
		this.x = Math.floor(Math.random() * this.h_length);
		this.y = Math.floor(Math.random() * this.v_length);
		this.x = this.options.begin_pos.x + this.x; 
		this.y = this.options.begin_pos.y + this.y;	
		var dur = this.getDuration({x:this.x,y:this.y});				
		this.fx = new Fx.Morph(this.item,{
				fps: this.options.fps,
				duration: dur, 
				wait: false,
				transition: Fx.Transitions.linear,
				onComplete: function(){						
					this.moverandom();			
				}.bind(this)
			});
		this.fx.cancel();
		this.fx.start({
			'left': this.x,
			'top': this.y
		});
	},
	movepreset: function(){
		this.numpos = this.options.pre_pos.x.length;
		if(this.curpos == undefined){
			this.curpos = 0;
		}else{
			this.tmppos = this.curpos;
			while(this.tmppos == this.curpos){
				this.tmppos = Math.floor(Math.random() * this.numpos);
			}				
			this.curpos = this.tmppos;
			//destroy
			this.tmppos = null;
		}	
		var dur = this.getDuration({x:this.options.pre_pos.x[this.curpos],y:this.options.pre_pos.y[this.curpos]});		
		this.fx = new Fx.Morph(this.item,{
				fps: this.options.fps,
				duration: dur, 
				wait: false,
				transition: Fx.Transitions.Sine					
			});
		this.fx.cancel();
		this.fx.start({
			'left': this.options.pre_pos.x[this.curpos],
			'top': this.options.pre_pos.y[this.curpos]
		});
	},
	loopbg: function(){
		this.frame = 0;
		this.changebg();
		
		this.bgtimer = this.changebg.periodical(this.options.changespeed / this.options.frameitem, this);			
	},
	changebg: function(){
		if(!this.bg){
			return;
		}
		
		this.frP = - this.frame * this.options.framesize;
		if(this.options.frameorder == 'v'){			
			this.bg.setStyle('background-position','0 ' + this.frP + 'px');
		}else{
			this.bg.setStyle('background-position',this.frP + 'px 0');
		}
		
		if(this.frame == this.options.frameitem - 1){
			this.frame = 0;
		}
		else {
			this.frame++;
		}
	},
	stoploop: function(){
		clearTimeout(this.bgtimer);
		if(this.bg){
			this.bg.setStyle('background-position', '0 0');
		}
	},
	getPathLength: function(a,b){
		var h = a.x - b.x;
		h = h > 0 ? h : -h;
		var v = a.y - b.y;
		v = v > 0 ? v : -v;
		return Math.sqrt(Math.pow(h,2)+ Math.pow(v,2));
	},
	getDuration: function(to){
		var curpos = {};
		curpos.x = parseInt($(this.item).getStyle('left'));
		curpos.y = parseInt($(this.item).getStyle('top'));
		var path = this.getPathLength({x:curpos.x,y:curpos.y},to);		
		return Math.round(path * 1000 / this.options.duration);
	}
});
//Overwrite Tips class

var JATips = new Class({
	Implements: [Options],
	options: {
		onShow: function(tip){
			tip.setStyle('visibility', 'visible');
		},
		onHide: function(tip){
			tip.setStyle('visibility', 'hidden');
		},
		maxTitleChars: 30,
		showDelay: 100,
		hideDelay: 100,
		className: 'tool',
		offsets: {'x': 16, 'y': 16},
		fixed: false
	},

	initialize: function(elements, options){
		this.setOptions(options);
		this.options.timeout = 0; //no timeout
		this.toolTip = new Element('div', {
			'class': this.options.className + '-tip',
			'styles': {				
				//'position': window.ie6?'absolute':'fixed',
				'position': $(elements).getStyle('position'),
				'display': 'none'			
			},
			'id': $(elements).getProperty('id')+'-tip'
		}).inject(document.body);
		//Updated by thuanlq
		this.wrapper = new Element('div',{'class':this.options.className+'-inner'}).inject(this.toolTip);
		//End
		$$(elements).each(this.build, this);
		if (this.options.initialize) this.options.initialize.call(this);
		this.toolTip.addEvent ('mouseenter', function(event) {
			this.start(this.curTip); event.stop();
			this.curTip.fireEvent ('mouseenter', event);
		}.bind(this));
		this.toolTip.addEvent ('mouseleave', function (event) {
			this.end(event);
			this.curTip.fireEvent ('mouseleave', event);
		}.bind (this));
	},

	build: function(el){
		var myTitle = (el.href && el.get('tag') == 'a') ? el.href.replace('http://', '') : (el.rel || false);
		var myText;
		if (el.title){
			var dual = el.title.split('::');
			if (dual.length > 1){
				myTitle = dual[0].trim();
				myText = dual[1].trim();
			} else {
				myText = el.title;
			}
			el.removeAttribute('title');
		} else {
			myText = '';
		}
		if (myTitle && myTitle.length > this.options.maxTitleChars) myTitle = myTitle.substr(0, this.options.maxTitleChars - 1) + "&hellip;";
		else{myTitle='';}
		el.setProperties({'myTitle': myTitle, 'myText': myText});
		
		el.addEvent('mouseenter', function(event){
			this.start(el);
			if (!this.options.fixed) this.locate(event);
			else this.position(el);
		}.bind(this));
		if (!this.options.fixed) el.addEvent('mousemove', this.locate.bindWithEvent(this));
		var end = this.end.bind(this);
		el.addEvent('mouseleave', end);
		el.addEvent('trash', end);
	},

	start: function(el){
		if (!el) el = this.curTip;
		//Add status to disable tips
		if (el.tip && el.tip == 'disabled') return;
		var myText = el.getProperty('myText');
		var myTitle = el.getProperty('myTitle');
		if (myText == '') return; //blank tip
		this.curTip = el;
		//Original code
		this.wrapper.empty();
		if (myTitle){
			//this.title = new Element('span').inject(new Element('div', {'class': this.options.className + '-title'}).inject(this.wrapper)).setHTML(myTitle);
			this.title = new Element('span').inject(new Element('div', {'class': this.options.className + '-title'}).inject(this.wrapper)).set('html', myTitle);
		}
		if (myText){
			//this.text = new Element('span').inject(new Element('div', {'class': this.options.className + '-text'}).inject(this.wrapper)).setHTML(myText);
			this.text = new Element('span').inject(new Element('div', {'class': this.options.className + '-text'}).inject(this.wrapper)).set('html', myText);
		}
		clearTimeout(this.timer);
		this.timer = this.show.delay(this.options.showDelay, this);
	},

	end: function(event){
		clearTimeout(this.timer);
		this.timer = this.hide.delay(this.options.hideDelay, this);
	},

	position: function(element){
		var pos = element.getPosition();
		this.toolTip.setStyles({
			'left': pos.x + this.options.offsets.x,
			'top': pos.y + this.options.offsets.y
		});
	},

	locate: function(event){
		var win = {'x': window.getWidth(), 'y': window.getHeight()};
		//var win = window.getOffsetSize();
		//var scroll = {'x': window.getScrollLeft(), 'y': window.getScrollTop()};
		var scroll = window.getScroll();
		var tip = {'x': this.toolTip.offsetWidth, 'y': this.toolTip.offsetHeight};
		var prop = {'x': 'left', 'y': 'top'};
		for (var z in prop){
			var pos = event.page[z] + this.options.offsets[z];
			if ((pos + tip[z] - scroll[z]) > win[z]) pos = event.page[z] - this.options.offsets[z] - tip[z];
			this.toolTip.setStyle(prop[z], pos);
		};
	},

	show: function(){
		if (this.options.timeout) this.timer = this.hide.delay(this.options.timeout, this);
		this.fireEvent('onShow', [this.toolTip]);
	},

	hide: function(){
		this.fireEvent('onHide', [this.toolTip]);
	},
	
	enableTip: function(el){
		if (el) el.tip = 'enabled';
	},
	
	disableTip: function(el){
		if (el) el.tip = 'disabled';
		if (this.curTip && this.curTip == el) this.hide();
	}

});

JATips.implement(new Events, new Options);

