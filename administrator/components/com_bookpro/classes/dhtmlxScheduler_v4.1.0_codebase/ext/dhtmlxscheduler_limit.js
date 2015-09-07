scheduler.config.limit_start = new Date(-3999,0,0);
scheduler.config.limit_end   = new Date( 3999,0,0);
scheduler.config.limit_view  = false;

(function(){
	var before = null;

	var block_days = {};
	var block_units = {};
	var time_block_set = false;
	scheduler.blockTime = function(day, zones, sections){
		var bottom = this.config.first_hour*60;
		var top = this.config.last_hour*60;
		if (zones == "fullday")
			zones = [bottom,top];

		var index = (typeof day == "object")?this.date.date_part(day).valueOf():day;

		if (sections) { // sections to block were specified
			for(var key in sections) {
				if(sections.hasOwnProperty(key)){
					block_units[key] = block_units[key]||{}; // block_units = { "units_view_1":{} }
					if(!(sections[key] instanceof Array))
						sections[key] = [ sections[key] ]; // now key(s) are certainly array
					var units = sections[key]; // list of keys
					for(var k=0; k<units.length; k++){
						var unit_id = units[k];
						var units_list = block_units[key]; // { key1: zones, key2: zones, key3: zones }
						units_list[unit_id] = units_list[unit_id]||{};
						units_list[unit_id][index] = zones;
					}
				}
			}
		}
		else {
			block_days[index] = zones;
		}


		for (var i=0; i<zones.length; i+=2){
			if (zones[i]<bottom)
				zones[i] = bottom;
			if (zones[i+1]>top)
				zones[i+1] = top;
		}

		time_block_set = true;
	};

	scheduler.attachEvent("onScaleAdd", function(area, day){
        var special_view = false;
        var mode = this._mode;
        var zones;

        if(this._props && this._props[mode]){ // we are in the units view
            var view = this._props[mode]; // units view object
            var units = view.options;
            var index = (view.position||0)+Math.floor((this._correct_shift(day.valueOf(),1)-this._min_date.valueOf())/(60*60*24*1000)); // user index
            var unit = units[index]; // key, label
            special_view = "units";
            day = this._date; // for units view actually only 1 day is displayed yet the day variable will change, need to use this._date for all calls

            if(block_units[mode] && block_units[mode][unit.key]){
            	var unit_zones = block_units[mode][unit.key];
            	zones = unit_zones[day.valueOf()]||unit_zones[day.getDay()];
            }
        }


		if(!zones)
			zones = ( block_days[day.valueOf()]||block_days[day.getDay()] ) || [];

			for (var i = 0; i < zones.length; i+=2){
				var start = zones[i];
				var end = zones[i+1];
				var block  = document.createElement("DIV");
				block.className = "dhx_time_block";

                var h_px; // FIXME
				block.style.top = (Math.round((start*60*1000-this.config.first_hour*60*60*1000)*this.config.hour_size_px/(60*60*1000)))%(this.config.hour_size_px*24)+"px";
				block.style.height = (Math.round(((end-start-1)*60*1000)*this.config.hour_size_px/(60*60*1000)))%(this.config.hour_size_px*24)+"px";

				area.appendChild(block);
			}
		}
	);

	scheduler.attachEvent("onBeforeViewChange",function(om,od,nm,nd){
		nd = nd||od; nm = nm||om;
		if (scheduler.config.limit_view){
			if (nd.valueOf()>scheduler.config.limit_end.valueOf() || this.date.add(nd,1,nm)<=scheduler.config.limit_start.valueOf()){
				setTimeout(function(){
					scheduler.setCurrentView(scheduler._date, nm);
				},1);
				return false;
			}
		}
		return true;
	});
	var blocker = function(ev){
		var s = scheduler;
		if(!ev)
			return true;
		var c = s.config;
		var res = (ev.start_date.valueOf() >= c.limit_start.valueOf() && ev.end_date.valueOf() <= c.limit_end.valueOf());
		if (res && time_block_set && ev._timed){
			var day = s.date.date_part(new Date(ev.start_date.valueOf()));

			var zones;
			if(s._props && s._props[s._mode]){
				var view = s._props[s._mode];
				if(block_units[s._mode] && block_units[s._mode][ev[view.map_to]]) {
					var unit_zones = block_units[s._mode][ev[view.map_to]];
					zones = unit_zones[day.valueOf()] || unit_zones[day.getDay()];
				}
			}
			if(!zones)
				var zones = block_days[day.valueOf()] || block_days[day.getDay()];

			var sm = ev.start_date.getHours()*60+ev.start_date.getMinutes();
			var em = ev.end_date.getHours()*60+ev.end_date.getMinutes();
			if (zones){
				for (var i = 0; i < zones.length; i+=2){
					var sz = zones[i];
					var ez = zones[i+1];
					if (sz<em && ez>sm) {
						if (sm<=ez && sm >=sz){
							if (ez == 24*60 || em<ez){
								res = false;
								break;
							}
                            if(s._drag_id && s._drag_mode == "new-size"){
                                ev.start_date.setHours(0);
                                ev.start_date.setMinutes(ez);
                            }
                            else {
                                res = false;
                                break;
                            }
						}
						if ((em>=sz && em<ez) || (sm < sz && em > ez)){
                            if(s._drag_id && s._drag_mode == "new-size"){
                                ev.end_date.setHours(0);
                                ev.end_date.setMinutes(sz);
                            }
                            else {
                                res = false;
                                break;
                            }
						}
					}
				}
			}
		}
		if (!res) {
			s._drag_id = null;
			s._drag_mode = null;
			s.callEvent("onLimitViolation",[ev.id, ev]);
		}
		return res;
	};

	scheduler.attachEvent("onBeforeDrag",function(id){
		if (!id) return true;
		return blocker(scheduler.getEvent(id));
	});
	scheduler.attachEvent("onClick", function (event_id, native_event_object){
		//if(event_id)
			return blocker(scheduler.getEvent(event_id));
		//return true;
    });
	scheduler.attachEvent("onBeforeLightbox",function(id){

		var ev = scheduler.getEvent(id);
		before = [ev.start_date, ev.end_date];
		return blocker(ev);
	});
	scheduler.attachEvent("onEventAdded",function(id){
		if (!id) return true;
		var ev = scheduler.getEvent(id);
		if (!blocker(ev)){
			if (ev.start_date < scheduler.config.limit_start) {
				ev.start_date = new Date(scheduler.config.limit_start);
			}
			if (ev.start_date.valueOf() >= scheduler.config.limit_end.valueOf()) {
				ev.start_date = this.date.add(scheduler.config.limit_end, -1, "day");
			}
			if (ev.end_date < scheduler.config.limit_start) {
				ev.end_date = new Date(scheduler.config.limit_start);
			}
			if (ev.end_date.valueOf() >= scheduler.config.limit_end.valueOf()) {
				ev.end_date = this.date.add(scheduler.config.limit_end, -1, "day");
			}
			if (ev.start_date.valueOf() >= ev.end_date.valueOf()) {
				ev.end_date = this.date.add(ev.start_date, (this.config.event_duration||this.config.time_step), "minute");
			}
			ev._timed=this.is_one_day_event(ev);
		}
		return true;
	});
	scheduler.attachEvent("onEventChanged",function(id){
		if (!id) return true;
		var ev = scheduler.getEvent(id);
		if (!blocker(ev)){
			if (!before) return false;
			ev.start_date = before[0];
			ev.end_date = before[1];
			ev._timed=this.is_one_day_event(ev);
		};
		return true;
	});
	scheduler.attachEvent("onBeforeEventChanged",function(ev, native_object, is_new){
		return blocker(ev);
	});

})();
