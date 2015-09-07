/**
 * Javascript for site calendars
 * 
 * @version $Id: calendars.js 19 2012-06-26 12:58:05Z quannv $
 * @package ARTIO Booking
 * @subpackage assets
 * @copyright Copyright (C) 2010 ARTIO s.r.o.. All rights reserved.
 * @author ARTIO s.r.o., http://www.artio.net
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link http://www.artio.net Official website
 */

var Calendars = {
	operation : 0,
	checkIn : '',
	checkOut : '',
	firstDay : '',
	lastDay : '',
	dayLength : 86400,
	dateBegin : 0,
	dateEnd : 0,
	select : false,
	boxes : new Array(),
	init : function() {
		var form = this.getForm();
		this.operation = parseInt(form.operation.value);
		switch (this.operation) {
		default:
		case CheckOpIn:
			this.setOperation(CheckOpIn);
			break;
		case CheckOpOut:
			this.setOperation(CheckOpOut);
			break;
		}
		if (form.boxIds.value != '') {
			var boxes = form.boxIds.value.split(',');
			this.checkIn = boxes[0];
			this.checkOut = boxes[boxes.length - 1];
			var checkInBox = this.getBox(this.checkIn);
			var checkOutBox = this.getBox(this.checkOut);
			if (checkInBox && checkOutBox) {
				var interval1 = this.getBoxInterval(checkInBox);
				var interval2 = this.getBoxInterval(checkOutBox);
				this.setBookFrom(checkInBox.fromDate, checkInBox.fromDisplay);
				this.setBookTo(checkOutBox.toDate, checkOutBox.toDisplay);
				this.checkBoxLimit(interval1[0], interval1[1], interval2[2]);
			}
		}
	},
	setCheckDate : function(id) {
		this.setCheckInfo('');
		this.setRType(ReservationDaily);
		var box = this.boxes[id];
		switch (this.operation) {
		default:
		case CheckOpIn:
			var checkIn = id;
			var checkOut = this.getIntervalEnd(box);
			this.setBookFrom(value, display);
			if (this.checkOut != '' && this.checkOut >= checkIn) {
				if (!this.testLimit(checkIn, checkOut)) {
					this.setCheckInfo(LGNoContinuousInterval, 'error');
					return false;
				}
			}
			this.checkIn = checkIn;
			this.checkOut = checkOut;
			this.setBookTo(value, display);
			this.checkLimit(this.checkIn, checkOut);
			this.setOperation(CheckOpOut);
			break;
		case CheckOpOut:
			var checkOut = dayNum;
			if (this.checkIn == '') {
				this.setCheckInfo(LGSelectCheckIn, 'error');
				return false;
			}
			if (this.checkIn > checkOut) {
				this.setCheckInfo(LGSelectRealInterval, 'error');
				return false;
			}
			var checkIn = this.checkIn < this.firstDay ? this.firstDay
					: this.checkIn;
			var canCheck = this.testLimit(checkIn, checkOut);
			if (!canCheck) {
				this.setCheckInfo(LGNoContinuousInterval, 'error');
				return false;
			}
			this.checkOut = checkOut;
			this.setBookTo(value, display);
			this.checkLimit(checkIn, checkOut);
			this.setOperation(CheckOpNext);
			break;
		}
	},
	setCheckBox : function(id) {
		var box = this.getBox(id);
		switch (this.operation) {
		default:
		case CheckOpIn:
			var interval = this.getBoxInterval(box);
			if (!this.testBoxLimit(interval[0], interval[1], interval[2],
					box.boxes)) {
				this.setCheckInfo(LGNoContinuousInterval, 'error');
				return false;
			}
			this.checkIn = this.getBoxId(interval);
			this.checkOut = this.getBoxId(interval, true);
			var checkInBox = this.getBox(this.checkIn);
			var checkOutBox = this.getBox(this.checkOut);
			this.setBookFrom(checkInBox.fromDate, checkInBox.fromDisplay);
			this.setBookTo(checkOutBox.toDate, checkOutBox.toDisplay);
			this.checkBoxLimit(interval[0], interval[1], interval[2]);
			this.setOperation(CheckOpOut);
			break;
		case CheckOpOut:
			var interval1 = this.getBoxInterval(this.getBox(this.checkIn));
			var interval2 = this.getBoxInterval(box);
			if (this.checkIn == '')
				this.setCheckInfo(LGSelectCheckIn, 'error');
			else if (interval1[0] != interval2[0])
				this.setCheckInfo(LGSelectRealInterval, 'error');
			else if (!this.testBoxLimit(interval1[0], interval1[1],
					interval2[2], box.boxes))
				this.setCheckInfo(LGNoContinuousInterval, 'error');
			else {
				this.checkOut = this.getBoxId(interval2, true);
				var lastBox = this.getBox(this.checkOut);
				this.setBookTo(lastBox.toDate, lastBox.toDisplay);
				this.checkBoxLimit(interval1[0], interval1[1], interval2[2]);
				this.setOperation(CheckOpNext);
				break;
			}
			return false;
		}
	},
	getBoxInterval : function(box) {
		var parts = box.id.match(/(.*-)(\d+)/);
		return new Array(parts[1], parts[2], box.boxes == '0' ? parts[2]
				: (parseInt(parts[2]) + parseInt(box.boxes) - 1));
	},
	getBoxId : function(interval, useSecond) {
		return interval[0] + (useSecond == true ? interval[2] : interval[1]);
	},
	getDayNum : function(id) {
		return parseInt(id.replace('day', ''));
	},
	getBoxNum : function(id) {
		return parseInt(id.replace('box', ''));
	},
	getDay : function(i) {
		return document.getElementById('day' + i);
	},
	getBox : function(id) {
		for ( var i = 0; i < this.boxes.length; i++)
			if (this.boxes[i] && this.boxes[i].id == id)
				return this.boxes[i];
	},
	setBookFrom : function(value, display) {
		var form = this.getForm();
		form.iFrom.value = display;
	},
	setBookTo : function(value, display) {
		var form = this.getForm();
		form.iTo.value = display;
	},
	testLimit : function(start, stop) {
		for ( var i = start; i <= stop; i += this.dayLength) {
			var day = this.getDay(i);
			if (!day) {
				return false;
			}
		}
		return true;
	},
	checkLimit : function(start, stop) {
		for ( var i = this.firstDay; i <= this.lastDay; i += this.dayLength) {
			var day = this.getDay(i);
			if (day) {
				day.className = ((i >= start) && (i <= stop)) ? 'day actual selected'
						: 'day actual';
			}
		}
	},
	testBoxLimit : function(prefix, from, to, boxes) {
		for ( var i = from; i <= to; i++)
			if (!$(prefix.concat(i)))
				return false;
		var diff = (parseInt(to) - parseInt(from) + 1) / parseInt(boxes);
		return Math.round(diff) == diff;
	},
	checkBoxLimit : function(prefix, from, to) {
		var boxIds = new Array();
		for ( var i = 0; i < this.boxes.length; i++) {
			if (this.boxes[i]) {
				var box = $(this.boxes[i].id);
				box.className = this.cleanSelected(box.className);
				box.selected = false;
				interval = this.getBoxInterval(box);
				if (interval[0] == prefix
						&& parseInt(interval[1]) >= parseInt(from)
						&& parseInt(interval[1]) <= parseInt(to)) {
					box.className = this.addSelected(box.className);
					box.selected = true;
					boxIds.push(box.id);
				}
			}
		}
		var form = this.getForm();
		form.boxIds.value = boxIds.join(',');
	},
	setOperation : function(operation) {
		var selectCheckInDay = document.getElementById('selectCheckInDay');
		var selectCheckOutDay = document.getElementById('selectCheckOutDay');
		var className1 = 'checkButton checkButtonActive';
		var className2 = 'checkButton checkButtonUnactive';
		this.operation = operation;
		var form = this.getForm();
		form.operation.value = operation;
		switch (this.operation) {
		case CheckOpIn:
		case CheckOpNext:
			selectCheckInDay.className = className1;
			selectCheckOutDay.className = className2;
			var checkInfo = this.operation == CheckOpIn ? LGSelectCheckIn
					: LGSelectCheckNext;
			break;
		case CheckOpOut:
			selectCheckInDay.className = className2;
			selectCheckOutDay.className = className1;
			var checkInfo = LGSelectCheckOut;
			break;
		}
		this.setCheckInfo(checkInfo, 'message');
	},
	setCheckInfo : function(value, type) {
		var checkInfo = document.getElementById('checkInfo');
		checkInfo.innerHTML = value;
		switch (type) {
		case 'message':
			checkInfo.className = 'checkInfo checkInfoMessage';
			break;
		case 'notice':
			checkInfo.className = 'checkInfo checkInfoNotice';
			break;
		case 'error':
			checkInfo.className = 'checkInfo checkInfoError';
			break;
		}
	},
	setRType : function(value) {
		/*
		 * var form = this.getForm(); form.rtype.value = value;
		 */
	},
	getForm : function() {
		var form = document.bookSetting;
		return form;
	},
	monthNavigation : function(month, year) {
		if (year == undefined) {
			var parts = month.split(',');
			month = parts[0];
			year = parts[1];
		}
		var form = this.getForm();
		form.month.value = month;
		form.year.value = year;
		form.submit();
	},
	weekNavigation : function(week, year) {
		if (year == undefined) {
			var parts = week.split(',');
			week = parts[0];
			year = parts[1];
		}
		var form = this.getForm();
		form.week.value = week;
		form.year.value = year;
		form.submit();
	},
	dayNavigation : function(day, month, year) {
		if (month == undefined) {
			var parts = day.split('-');
			year = parts[0];
			month = parts[1];
			day = parts[2];
		}
		var form = this.getForm();
		form.month.value = month;
		form.year.value = year;
		form.day.value = day;
		form.submit();
	},
	reset : function() {
		this.resetCheckIn();
		this.resetCheckOut();
		this.checkLimit(0, 0);
		this.checkBoxLimit(0, 0);
		this.setOperation(CheckOpIn);
		var form = this.getForm();
		form.boxIds.value = '';
	},
	resetCheckIn : function() {
		this.setBookFrom('', '');
		this.checkIn = '';
	},
	resetCheckOut : function() {
		this.setBookTo('', '');
		this.checkOut = '';
	},
	bookIt : function() {
		if (this.checkIn == '' || this.checkOut == '') {
			this.setCheckInfo(LGSelectCheckInAndCheckOut, 'error');
		} else {
			var form = this.getForm();
			form.controller.value = 'reservation';
			form.task.value = 'add';
			form.view.value = '';
			form.submit();
		}
	},
	unhighlightInterval : function(id) {
		var interval = this.getBoxInterval(this.getBox(id));
		for ( var i = interval[1]; i <= interval[2]; i++) {
			var box = $(interval[0] + i);
			if (box && !box.selected)
				box.className = this.cleanSelected(box.className);
		}
	},
	highlightInterval : function(id) {
		var interval = this.getBoxInterval(this.getBox(id));
		for ( var i = interval[1]; i <= interval[2]; i++) {
			var box = $(interval[0] + i);
			if (!box || box.selected)
				return;
		}
		for ( var i = interval[1]; i <= interval[2]; i++) {
			var box = $(interval[0] + i);
			box.className = this.addSelected(box.className);
		}
	},
	cleanSelected : function(className) {
		return trim(className.replace('selected', ''));
	},
	addSelected : function(className) {
		return trim(className + ' selected');
	}
}
function disallowDate(date) {
	var year = date.getFullYear().toString();
	var month = date.getMonth() + 1;
	if (month < 10)
		month = '0' + month.toString();
	var day = date.getDate();
	if (day < 10)
		day = '0' + day.toString();
	var current = parseInt(year + month + day);
	return Calendars.dateBegin > current || Calendars.dateEnd < current;
}
function onSelectDate(calendar, date) {
	if (calendar.dateClicked)
		Calendars.dayNavigation(date);
}
