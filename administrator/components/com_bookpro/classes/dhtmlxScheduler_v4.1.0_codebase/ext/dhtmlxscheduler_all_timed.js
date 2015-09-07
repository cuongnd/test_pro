(function(){
    var old_prerender_events_line = scheduler._pre_render_events_line;
    scheduler._pre_render_events_line = function(evs, hold){
		for (var i=0; i < evs.length; i++) {
			var ev=evs[i];

			if (!ev._timed) {

				var ce = this._lame_copy({}, ev); // current event (event for one specific day) is copy of original with modified dates
				ce.start_date = new Date(ce.start_date); // as lame copy doesn't copy date objects

				var next_day = scheduler.date.add(ev.start_date, 1, "day");
				next_day = scheduler.date.date_part(next_day);

				if (ev.end_date < next_day) {
					ce.end_date = new Date(ev.end_date);
				}
				else {
					ce.end_date = next_day;
					if (this.config.last_hour != 24) { // if specific last_hour was set (e.g. 20)
						ce.end_date = scheduler.date.date_part(new Date(ce.start_date));
						ce.end_date.setHours(this.config.last_hour);
					}
				}

				var event_changed = false;
				if (ce.start_date < this._max_date && ce.end_date > this._min_date && ce.start_date < ce.end_date) {
					evs[i] = ce; // adding another event in collection
					event_changed = true;
				}
				if (ce.start_date > ce.end_date) {
					evs.splice(i--,1);
				}

				var re = this._lame_copy({}, ev); // remaining event, copy of original with modified start_date (making range more narrow)
				re.end_date = new Date(re.end_date);
				if (re.start_date < this._min_date)
					re.start_date = new Date(this._min_date);
				else
					re.start_date = this.date.add(ev.start_date, 1, "day");

				re.start_date.setHours(this.config.first_hour);
				re.start_date.setMinutes(0); // as we are starting only with whole hours
				if (re.start_date < this._max_date && re.start_date < re.end_date) {
					if (event_changed)
						evs.splice(i+1,0,re);
					else {
						evs[i--] = re;
						continue;
					}
				}
			}
		}
	    // in case of all_timed pre_render is not applied to the original event
	    // so we need to force redraw in case of dnd
	    var redraw = (this._drag_mode == 'move')?false:hold;
	    return old_prerender_events_line.call(this, evs, redraw);
    };
	var old_get_visible_events = scheduler.get_visible_events;
	scheduler.get_visible_events = function(){
		return old_get_visible_events.call(this, false); // only timed = false
	};
	scheduler.attachEvent("onBeforeViewChange", function (old_mode, old_date, mode, date) {
		scheduler._allow_dnd = (mode == "day" || mode == "week");
		return true;
	});
})();