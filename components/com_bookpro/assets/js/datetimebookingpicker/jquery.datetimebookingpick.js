/* http://keith-wood.name/datetimebookingpick.html
   Date picker for jQuery v4.0.6.
   Written by Keith Wood (kbwood{at}iinet.com.au) February 2010.
   Dual licensed under the GPL (http://dev.jquery.com/browser/trunk/jquery/GPL-LICENSE.txt) and 
   MIT (http://dev.jquery.com/browser/trunk/jquery/MIT-LICENSE.txt) licenses. 
   Please attribute the author if you use it. */

(function($) { // Hide scope, no $ conflict

/* datetimebookingpicker manager. */
function datetimebookingpicker() {
	this._defaults = {
		pickerClass: '', // CSS class to add to this instance of the datetimebookingpicker
		showOnFocus: true, // True for popup on focus, false for not
		showTrigger: null, // Element to be cloned for a trigger, null for none
		showAnim: 'show', // Name of jQuery animation for popup, '' for no animation
		showOptions: {}, // Options for enhanced animations
		showSpeed: 'normal', // Duration of display/closure
		popupContainer: null, // The element to which a popup calendar is added, null for body
		alignment: 'bottom', // Alignment of popup - with nominated corner of input:
			// 'top' or 'bottom' aligns depending on language direction,
			// 'topLeft', 'topRight', 'bottomLeft', 'bottomRight'
		fixedWeeks: false, // True to always show 6 weeks, false to only show as many as are needed
		firstDay: 0, // First day of the week, 0 = Sunday, 1 = Monday, ...
		calculateWeek: this.iso8601Week, // Calculate week of the year from a date, null for ISO8601
		monthsToShow: 1, // How many months to show, cols or [rows, cols]
		monthsOffset: 0, // How many months to offset the primary month by;
			// may be a function that takes the date and returns the offset
		monthsToStep: 1, // How many months to move when prev/next clicked
		monthsToJump: 12, // How many months to move when large prev/next clicked
		useMouseWheel: true, // True to use mousewheel if available, false to never use it
		changeMonth: true, // True to change month/year via drop-down, false for navigation only
		yearRange: 'c-10:c+10', // Range of years to show in drop-down: 'any' for direct text entry
			// or 'start:end', where start/end are '+-nn' for relative to today
			// or 'c+-nn' for relative to the currently selected date
			// or 'nnnn' for an absolute year
		shortYearCutoff: '+10', // Cutoff for two-digit year in the current century
		showOtherMonths: false, // True to show dates from other months, false to not show them
		selectOtherMonths: false, // True to allow selection of dates from other months too
		defaultDate: null, // Date to show if no other selected
		selectDefaultDate: false, // True to pre-select the default date if no other is chosen
		minDate: null, // The minimum selectable date
		maxDate: null, // The maximum selectable date
		dateFormat: 'mm/dd/yyyy', // Format for dates
		autoSize: true, // True to size the input field according to the date format
		rangeSelect: false, // Allows for selecting a date range on one date picker
		rangeSeparator: ' - ', // Text between two dates in a range
		multiSelect: 0, // Maximum number of selectable dates, zero for single select
		multiSeparator: ',', // Text between multiple dates
		onDate: null, // Callback as a date is added to the datetimebookingpicker
		onShow: null, // Callback just before a datetimebookingpicker is shown
		onChangeMonthYear: null, // Callback when a new month/year is selected
		onSelect: null, // Callback when a date is selected
		onClose: null, // Callback when a datetimebookingpicker is closed
		altField: null, // Alternate field to update in synch with the datetimebookingpicker
		altFormat: null, // Date format for alternate field, defaults to dateFormat
		constrainInput: true, // True to constrain typed input to dateFormat allowed characters
		commandsAsDateFormat: false, // True to apply formatDate to the command texts
		commands: this.commands // Command actions that may be added to a layout by name
	};
	this.regional = {
		'': { // US/English
			monthNames: ['January', 'February', 'March', 'April', 'May', 'June',
			'July', 'August', 'September', 'October', 'November', 'December'],
			monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
			dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			dayNamesMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
			dateFormat: 'mm/dd/yyyy', // See options on formatDate
			firstDay: 0, // The first day of the week, Sun = 0, Mon = 1, ...
			renderer: this.defaultRenderer, // The rendering templates
			prevText: '&lt;Prev', // Text for the previous month command
			prevStatus: 'Show the previous month', // Status text for the previous month command
			prevJumpText: '&lt;&lt;', // Text for the previous year command
			prevJumpStatus: 'Show the previous year', // Status text for the previous year command
			nextText: 'Next&gt;', // Text for the next month command
			nextStatus: 'Show the next month', // Status text for the next month command
			nextJumpText: '&gt;&gt;', // Text for the next year command
			nextJumpStatus: 'Show the next year', // Status text for the next year command
			currentText: 'Current', // Text for the current month command
			currentStatus: 'Show the current month', // Status text for the current month command
			todayText: 'Today', // Text for the today's month command
			todayStatus: 'Show today\'s month', // Status text for the today's month command
			clearText: 'Clear', // Text for the clear command
			clearStatus: 'Clear all the dates', // Status text for the clear command
			closeText: 'Close', // Text for the close command
			closeStatus: 'Close the datetimebookingpicker', // Status text for the close command
			yearStatus: 'Change the year', // Status text for year selection
			monthStatus: 'Change the month', // Status text for month selection
			weekText: 'Wk', // Text for week of the year column header
			weekStatus: 'Week of the year', // Status text for week of the year column header
			dayStatus: 'Select DD, M d, yyyy', // Status text for selectable days
			defaultStatus: 'Select a date', // Status text shown by default
			isRTL: false // True if language is right-to-left
		}};
	$.extend(this._defaults, this.regional['']);
	this._disabled = [];
}

$.extend(datetimebookingpicker.prototype, {
	dataName: 'datetimebookingpick',
	
	/* Class name added to elements to indicate already configured with datetimebookingpicker. */
	markerClass: 'hasdatetimebookingpick',

	_popupClass: 'datetimebookingpick-popup', // Marker for popup division
	_triggerClass: 'datetimebookingpick-trigger', // Marker for trigger element
	_disableClass: 'datetimebookingpick-disable', // Marker for disabled element
	_coverClass: 'datetimebookingpick-cover', // Marker for iframe backing element
	_monthYearClass: 'datetimebookingpick-month-year', // Marker for month/year inputs
	_curMonthClass: 'datetimebookingpick-month-', // Marker for current month/year
	_anyYearClass: 'datetimebookingpick-any-year', // Marker for year direct input
	_curDoWClass: 'datetimebookingpick-dow-', // Marker for day of week
	
	commands: { // Command actions that may be added to a layout by name
		// name: { // The command name, use '{button:name}' or '{link:name}' in layouts
		//		text: '', // The field in the regional settings for the displayed text
		//		status: '', // The field in the regional settings for the status text
		//      // The keystroke to trigger the action
		//		keystroke: {keyCode: nn, ctrlKey: boolean, altKey: boolean, shiftKey: boolean},
		//		enabled: fn, // The function that indicates the command is enabled
		//		date: fn, // The function to get the date associated with this action
		//		action: fn} // The function that implements the action
		prev: {text: 'prevText', status: 'prevStatus', // Previous month
			keystroke: {keyCode: 33}, // Page up
			enabled: function(inst) {
				var minDate = inst.curMinDate();
				return (!minDate || $.datetimebookingpick.add($.datetimebookingpick.day(
					$.datetimebookingpick._applyMonthsOffset($.datetimebookingpick.add($.datetimebookingpick.newDate(inst.drawDate),
					1 - inst.get('monthsToStep'), 'm'), inst), 1), -1, 'd').
					getTime() >= minDate.getTime()); },
			date: function(inst) {
				return $.datetimebookingpick.day($.datetimebookingpick._applyMonthsOffset($.datetimebookingpick.add(
					$.datetimebookingpick.newDate(inst.drawDate), -inst.get('monthsToStep'), 'm'), inst), 1); },
			action: function(inst) {
				$.datetimebookingpick.changeMonth(this, -inst.get('monthsToStep')); }
		},
		prevJump: {text: 'prevJumpText', status: 'prevJumpStatus', // Previous year
			keystroke: {keyCode: 33, ctrlKey: true}, // Ctrl + Page up
			enabled: function(inst) {
				var minDate = inst.curMinDate();
				return (!minDate || $.datetimebookingpick.add($.datetimebookingpick.day(
					$.datetimebookingpick._applyMonthsOffset($.datetimebookingpick.add($.datetimebookingpick.newDate(inst.drawDate),
					1 - inst.get('monthsToJump'), 'm'), inst), 1), -1, 'd').
					getTime() >= minDate.getTime()); },
			date: function(inst) {
				return $.datetimebookingpick.day($.datetimebookingpick._applyMonthsOffset($.datetimebookingpick.add(
					$.datetimebookingpick.newDate(inst.drawDate), -inst.get('monthsToJump'), 'm'), inst), 1); },
			action: function(inst) {
				$.datetimebookingpick.changeMonth(this, -inst.get('monthsToJump')); }
		},
		next: {text: 'nextText', status: 'nextStatus', // Next month
			keystroke: {keyCode: 34}, // Page down
			enabled: function(inst) {
				var maxDate = inst.get('maxDate');
				return (!maxDate || $.datetimebookingpick.day($.datetimebookingpick._applyMonthsOffset($.datetimebookingpick.add(
					$.datetimebookingpick.newDate(inst.drawDate), inst.get('monthsToStep'), 'm'), inst), 1).
					getTime() <= maxDate.getTime()); },
			date: function(inst) {
				return $.datetimebookingpick.day($.datetimebookingpick._applyMonthsOffset($.datetimebookingpick.add(
					$.datetimebookingpick.newDate(inst.drawDate), inst.get('monthsToStep'), 'm'), inst), 1); },
			action: function(inst) {
				$.datetimebookingpick.changeMonth(this, inst.get('monthsToStep')); }
		},
		nextJump: {text: 'nextJumpText', status: 'nextJumpStatus', // Next year
			keystroke: {keyCode: 34, ctrlKey: true}, // Ctrl + Page down
			enabled: function(inst) {
				var maxDate = inst.get('maxDate');
				return (!maxDate || $.datetimebookingpick.day($.datetimebookingpick._applyMonthsOffset($.datetimebookingpick.add(
					$.datetimebookingpick.newDate(inst.drawDate), inst.get('monthsToJump'), 'm'), inst), 1).
					getTime() <= maxDate.getTime()); },
			date: function(inst) {
				return $.datetimebookingpick.day($.datetimebookingpick._applyMonthsOffset($.datetimebookingpick.add(
					$.datetimebookingpick.newDate(inst.drawDate), inst.get('monthsToJump'), 'm'), inst), 1); },
			action: function(inst) {
				$.datetimebookingpick.changeMonth(this, inst.get('monthsToJump')); }
		},
		current: {text: 'currentText', status: 'currentStatus', // Current month
			keystroke: {keyCode: 36, ctrlKey: true}, // Ctrl + Home
			enabled: function(inst) {
				var minDate = inst.curMinDate();
				var maxDate = inst.get('maxDate');
				var curDate = inst.selectedDates[0] || $.datetimebookingpick.today();
				return (!minDate || curDate.getTime() >= minDate.getTime()) &&
					(!maxDate || curDate.getTime() <= maxDate.getTime()); },
			date: function(inst) {
				return inst.selectedDates[0] || $.datetimebookingpick.today(); },
			action: function(inst) {
				var curDate = inst.selectedDates[0] || $.datetimebookingpick.today();
				$.datetimebookingpick.showMonth(this, curDate.getFullYear(), curDate.getMonth() + 1); }
		},
		today: {text: 'todayText', status: 'todayStatus', // Today's month
			keystroke: {keyCode: 36, ctrlKey: true}, // Ctrl + Home
			enabled: function(inst) {
				var minDate = inst.curMinDate();
				var maxDate = inst.get('maxDate');
				return (!minDate || $.datetimebookingpick.today().getTime() >= minDate.getTime()) &&
					(!maxDate || $.datetimebookingpick.today().getTime() <= maxDate.getTime()); },
			date: function(inst) { return $.datetimebookingpick.today(); },
			action: function(inst) { $.datetimebookingpick.showMonth(this); }
		},
		clear: {text: 'clearText', status: 'clearStatus', // Clear the datetimebookingpicker
			keystroke: {keyCode: 35, ctrlKey: true}, // Ctrl + End
			enabled: function(inst) { return true; },
			date: function(inst) { return null; },
			action: function(inst) { $.datetimebookingpick.clear(this); }
		},
		close: {text: 'closeText', status: 'closeStatus', // Close the datetimebookingpicker
			keystroke: {keyCode: 27}, // Escape
			enabled: function(inst) { return true; },
			date: function(inst) { return null; },
			action: function(inst) { $.datetimebookingpick.hide(this); }
		},
		prevWeek: {text: 'prevWeekText', status: 'prevWeekStatus', // Previous week
			keystroke: {keyCode: 38, ctrlKey: true}, // Ctrl + Up
			enabled: function(inst) {
				var minDate = inst.curMinDate();
				return (!minDate || $.datetimebookingpick.add($.datetimebookingpick.newDate(inst.drawDate), -7, 'd').
					getTime() >= minDate.getTime()); },
			date: function(inst) { return $.datetimebookingpick.add($.datetimebookingpick.newDate(inst.drawDate), -7, 'd'); },
			action: function(inst) { $.datetimebookingpick.changeDay(this, -7); }
		},
		prevDay: {text: 'prevDayText', status: 'prevDayStatus', // Previous day
			keystroke: {keyCode: 37, ctrlKey: true}, // Ctrl + Left
			enabled: function(inst) {
				var minDate = inst.curMinDate();
				return (!minDate || $.datetimebookingpick.add($.datetimebookingpick.newDate(inst.drawDate), -1, 'd').
					getTime() >= minDate.getTime()); },
			date: function(inst) { return $.datetimebookingpick.add($.datetimebookingpick.newDate(inst.drawDate), -1, 'd'); },
			action: function(inst) { $.datetimebookingpick.changeDay(this, -1); }
		},
		nextDay: {text: 'nextDayText', status: 'nextDayStatus', // Next day
			keystroke: {keyCode: 39, ctrlKey: true}, // Ctrl + Right
			enabled: function(inst) {
				var maxDate = inst.get('maxDate');
				return (!maxDate || $.datetimebookingpick.add($.datetimebookingpick.newDate(inst.drawDate), 1, 'd').
					getTime() <= maxDate.getTime()); },
			date: function(inst) { return $.datetimebookingpick.add($.datetimebookingpick.newDate(inst.drawDate), 1, 'd'); },
			action: function(inst) { $.datetimebookingpick.changeDay(this, 1); }
		},
		nextWeek: {text: 'nextWeekText', status: 'nextWeekStatus', // Next week
			keystroke: {keyCode: 40, ctrlKey: true}, // Ctrl + Down
			enabled: function(inst) {
				var maxDate = inst.get('maxDate');
				return (!maxDate || $.datetimebookingpick.add($.datetimebookingpick.newDate(inst.drawDate), 7, 'd').
					getTime() <= maxDate.getTime()); },
			date: function(inst) { return $.datetimebookingpick.add($.datetimebookingpick.newDate(inst.drawDate), 7, 'd'); },
			action: function(inst) { $.datetimebookingpick.changeDay(this, 7); }
		}
	},

	/* Default template for generating a datetimebookingpicker. */
	defaultRenderer: {
		// Anywhere: '{l10n:name}' to insert localised value for name,
		// '{link:name}' to insert a link trigger for command name,
		// '{button:name}' to insert a button trigger for command name,
		// '{popup:start}...{popup:end}' to mark a section for inclusion in a popup datetimebookingpicker only,
		// '{inline:start}...{inline:end}' to mark a section for inclusion in an inline datetimebookingpicker only
		// Overall structure: '{months}' to insert calendar months
		picker: '<div class="datetimebookingpick">' +
		'<div class="datetimebookingpick-nav">{link:prev}{link:today}{link:next}</div>{months}' +
		'{popup:start}<div class="datetimebookingpick-ctrl">{link:clear}{link:close}</div>{popup:end}' +
		'<div class="datetimebookingpick-clear-fix"></div></div>',
		// One row of months: '{months}' to insert calendar months
		monthRow: '<div class="datetimebookingpick-month-row">{months}</div>',
		// A single month: '{monthHeader:dateFormat}' to insert the month header -
		// dateFormat is optional and defaults to 'MM yyyy',
		// '{weekHeader}' to insert a week header, '{weeks}' to insert the month's weeks
		month: '<div class="datetimebookingpick-month"><div class="datetimebookingpick-month-header">{monthHeader}</div>' +
		'<table><thead>{weekHeader}</thead><tbody>{weeks}</tbody></table></div>',
		// A week header: '{days}' to insert individual day names
		weekHeader: '<tr>{days}</tr>',
		// Individual day header: '{day}' to insert day name
		dayHeader: '<th>{day}</th>',
		// One week of the month: '{days}' to insert the week's days, '{weekOfYear}' to insert week of year
		week: '<tr>{days}</tr>',
		// An individual day: '{day}' to insert day value
		day: '<td {style} class="{class_default} {class}" {atrrib}>{day}</td>',
		// jQuery selector, relative to picker, for a single month
		monthSelector: '.datetimebookingpick-month',
		// jQuery selector, relative to picker, for individual days
		daySelector: 'td',
		// Class for right-to-left (RTL) languages
		rtlClass: 'datetimebookingpick-rtl',
		// Class for multi-month datetimebookingpickers
		multiClass: 'datetimebookingpick-multi',
		// Class for selectable dates
		defaultClass: '',
		// Class for currently selected dates
		selectedClass: 'datetimebookingpick-selected',
		// Class for highlighted dates
		highlightedClass: 'datetimebookingpick-highlight',
		// Class for today
		todayClass: 'datetimebookingpick-today',
		// Class for days from other months
		otherMonthClass: 'datetimebookingpick-other-month',
		// Class for days on weekends
		weekendClass: 'datetimebookingpick-weekend',
		// Class prefix for commands
		commandClass: 'datetimebookingpick-cmd',
		// Extra class(es) for commands that are buttons
		commandButtonClass: '',
		// Extra class(es) for commands that are links
		commandLinkClass: '',
		// Class for disabled commands
		disabledClass: 'datetimebookingpick-disabled'
	},

	/* Override the default settings for all datetimebookingpicker instances.
	   @param  settings  (object) the new settings to use as defaults
	   @return  (datetimebookingpicker) this object */
	setDefaults: function(settings) {
		$.extend(this._defaults, settings || {});
		return this;
	},

	_ticksTo1970: (((1970 - 1) * 365 + Math.floor(1970 / 4) - Math.floor(1970 / 100) +
		Math.floor(1970 / 400)) * 24 * 60 * 60 * 10000000),
	_msPerDay: 24 * 60 * 60 * 1000,

	ATOM: 'yyyy-mm-dd', // RFC 3339/ISO 8601
	COOKIE: 'D, dd M yyyy',
	FULL: 'DD, MM d, yyyy',
	ISO_8601: 'yyyy-mm-dd',
	JULIAN: 'J',
	RFC_822: 'D, d M yy',
	RFC_850: 'DD, dd-M-yy',
	RFC_1036: 'D, d M yy',
	RFC_1123: 'D, d M yyyy',
	RFC_2822: 'D, d M yyyy',
	RSS: 'D, d M yy', // RFC 822
	TICKS: '!',
	TIMESTAMP: '@',
	W3C: 'yyyy-mm-dd', // ISO 8601

	/* Format a date object into a string value.
	   The format can be combinations of the following:
	   d  - day of month (no leading zero)
	   dd - day of month (two digit)
	   o  - day of year (no leading zeros)
	   oo - day of year (three digit)
	   D  - day name short
	   DD - day name long
	   w  - week of year (no leading zero)
	   ww - week of year (two digit)
	   m  - month of year (no leading zero)
	   mm - month of year (two digit)
	   M  - month name short
	   MM - month name long
	   yy - year (two digit)
	   yyyy - year (four digit)
	   @  - Unix timestamp (s since 01/01/1970)
	   !  - Windows ticks (100ns since 01/01/0001)
	   '...' - literal text
	   '' - single quote
	   @param  format    (string) the desired format of the date (optional, default datetimebookingpicker format)
	   @param  date      (Date) the date value to format
	   @param  settings  (object) attributes include:
	                     dayNamesShort    (string[]) abbreviated names of the days from Sunday (optional)
	                     dayNames         (string[]) names of the days from Sunday (optional)
	                     monthNamesShort  (string[]) abbreviated names of the months (optional)
	                     monthNames       (string[]) names of the months (optional)
						 calculateWeek    (function) function that determines week of the year (optional)
	   @return  (string) the date in the above format */
	formatDate: function(format, date, settings) {
		if (typeof format != 'string') {
			settings = date;
			date = format;
			format = '';
		}
		if (!date) {
			return '';
		}
		format = format || this._defaults.dateFormat;
		settings = settings || {};
		var dayNamesShort = settings.dayNamesShort || this._defaults.dayNamesShort;
		var dayNames = settings.dayNames || this._defaults.dayNames;
		var monthNamesShort = settings.monthNamesShort || this._defaults.monthNamesShort;
		var monthNames = settings.monthNames || this._defaults.monthNames;
		var calculateWeek = settings.calculateWeek || this._defaults.calculateWeek;
		// Check whether a format character is doubled
		var doubled = function(match, step) {
			var matches = 1;
			while (iFormat + matches < format.length && format.charAt(iFormat + matches) == match) {
				matches++;
			}
			iFormat += matches - 1;
			return Math.floor(matches / (step || 1)) > 1;
		};
		// Format a number, with leading zeroes if necessary
		var formatNumber = function(match, value, len, step) {
			var num = '' + value;
			if (doubled(match, step)) {
				while (num.length < len) {
					num = '0' + num;
				}
			}
			return num;
		};
		// Format a name, short or long as requested
		var formatName = function(match, value, shortNames, longNames) {
			return (doubled(match) ? longNames[value] : shortNames[value]);
		};
		var output = '';
		var literal = false;
		for (var iFormat = 0; iFormat < format.length; iFormat++) {
			if (literal) {
				if (format.charAt(iFormat) == "'" && !doubled("'")) {
					literal = false;
				}
				else {
					output += format.charAt(iFormat);
				}
			}
			else {
				switch (format.charAt(iFormat)) {
					case 'd': output += formatNumber('d', date.getDate(), 2); break;
					case 'D': output += formatName('D', date.getDay(),
						dayNamesShort, dayNames); break;
					case 'o': output += formatNumber('o', this.dayOfYear(date), 3); break;
					case 'w': output += formatNumber('w', calculateWeek(date), 2); break;
					case 'm': output += formatNumber('m', date.getMonth() + 1, 2); break;
					case 'M': output += formatName('M', date.getMonth(),
						monthNamesShort, monthNames); break;
					case 'y':
						output += (doubled('y', 2) ? date.getFullYear() :
							(date.getFullYear() % 100 < 10 ? '0' : '') + date.getFullYear() % 100);
						break;
					case '@': output += Math.floor(date.getTime() / 1000); break;
					case '!': output += date.getTime() * 10000 + this._ticksTo1970; break;
					case "'":
						if (doubled("'")) {
							output += "'";
						}
						else {
							literal = true;
						}
						break;
					default:
						output += format.charAt(iFormat);
				}
			}
		}
		return output;
	},

	/* Parse a string value into a date object.
	   See formatDate for the possible formats, plus:
	   * - ignore rest of string
	   @param  format    (string) the expected format of the date ('' for default datetimebookingpicker format)
	   @param  value     (string) the date in the above format
	   @param  settings  (object) attributes include:
	                     shortYearCutoff  (number) the cutoff year for determining the century (optional)
	                     dayNamesShort    (string[]) abbreviated names of the days from Sunday (optional)
	                     dayNames         (string[]) names of the days from Sunday (optional)
	                     monthNamesShort  (string[]) abbreviated names of the months (optional)
	                     monthNames       (string[]) names of the months (optional)
	   @return  (Date) the extracted date value or null if value is blank
	   @throws  errors if the format and/or value are missing,
	            if the value doesn't match the format,
	            or if the date is invalid */
	parseDate: function(format, value, settings) {
		if (value == null) {
			throw 'Invalid arguments';
		}
		value = (typeof value == 'object' ? value.toString() : value + '');
		if (value == '') {
			return null;
		}
		format = format || this._defaults.dateFormat;
		settings = settings || {};
		var shortYearCutoff = settings.shortYearCutoff || this._defaults.shortYearCutoff;
		shortYearCutoff = (typeof shortYearCutoff != 'string' ? shortYearCutoff :
			this.today().getFullYear() % 100 + parseInt(shortYearCutoff, 10));
		var dayNamesShort = settings.dayNamesShort || this._defaults.dayNamesShort;
		var dayNames = settings.dayNames || this._defaults.dayNames;
		var monthNamesShort = settings.monthNamesShort || this._defaults.monthNamesShort;
		var monthNames = settings.monthNames || this._defaults.monthNames;
		var year = -1;
		var month = -1;
		var day = -1;
		var doy = -1;
		var shortYear = false;
		var literal = false;
		// Check whether a format character is doubled
		var doubled = function(match, step) {
			var matches = 1;
			while (iFormat + matches < format.length && format.charAt(iFormat + matches) == match) {
				matches++;
			}
			iFormat += matches - 1;
			return Math.floor(matches / (step || 1)) > 1;
		};
		// Extract a number from the string value
		var getNumber = function(match, step) {
			var isDoubled = doubled(match, step);
			var size = [2, 3, isDoubled ? 4 : 2, 11, 20]['oy@!'.indexOf(match) + 1];
			var digits = new RegExp('^-?\\d{1,' + size + '}');
			var num = value.substring(iValue).match(digits);
			if (!num) {
				throw 'Missing number at position {0}'.replace(/\{0\}/, iValue);
			}
			iValue += num[0].length;
			return parseInt(num[0], 10);
		};
		// Extract a name from the string value and convert to an index
		var getName = function(match, shortNames, longNames, step) {
			var names = (doubled(match, step) ? longNames : shortNames);
			for (var i = 0; i < names.length; i++) {
				if (value.substr(iValue, names[i].length) == names[i]) {
					iValue += names[i].length;
					return i + 1;
				}
			}
			throw 'Unknown name at position {0}'.replace(/\{0\}/, iValue);
		};
		// Confirm that a literal character matches the string value
		var checkLiteral = function() {
			if (value.charAt(iValue) != format.charAt(iFormat)) {
				throw 'Unexpected literal at position {0}'.replace(/\{0\}/, iValue);
			}
			iValue++;
		};
		var iValue = 0;
		for (var iFormat = 0; iFormat < format.length; iFormat++) {
			if (literal) {
				if (format.charAt(iFormat) == "'" && !doubled("'")) {
					literal = false;
				}
				else {
					checkLiteral();
				}
			}
			else {
				switch (format.charAt(iFormat)) {
					case 'd': day = getNumber('d'); break;
					case 'D': getName('D', dayNamesShort, dayNames); break;
					case 'o': doy = getNumber('o'); break;
					case 'w': getNumber('w'); break;
					case 'm': month = getNumber('m'); break;
					case 'M': month = getName('M', monthNamesShort, monthNames); break;
					case 'y':
						var iSave = iFormat;
						shortYear = !doubled('y', 2);
						iFormat = iSave;
						year = getNumber('y', 2);
						break;
					case '@':
						var date = this._normaliseDate(new Date(getNumber('@') * 1000));
						year = date.getFullYear();
						month = date.getMonth() + 1;
						day = date.getDate();
						break;
					case '!':
						var date = this._normaliseDate(
							new Date((getNumber('!') - this._ticksTo1970) / 10000));
						year = date.getFullYear();
						month = date.getMonth() + 1;
						day = date.getDate();
						break;
					case '*': iValue = value.length; break;
					case "'":
						if (doubled("'")) {
							checkLiteral();
						}
						else {
							literal = true;
						}
						break;
					default: checkLiteral();
				}
			}
		}
		if (iValue < value.length) {
			throw 'Additional text found at end';
		}
		
		if (year == -1) {
			year = this.today().getFullYear();
		}
		else if (year < 100 && shortYear) {
			year += (shortYearCutoff == -1 ? 1900 : this.today().getFullYear() -
				this.today().getFullYear() % 100 - (year <= shortYearCutoff ? 0 : 100));
		}
		if (doy > -1) {
			month = 1;
			day = doy;
			for (var dim = this.daysInMonth(year, month); day > dim;
					dim = this.daysInMonth(year, month)) {
				month++;
				day -= dim;
			}
		}
		var date = this.newDate(year, month, day);
		if (date.getFullYear() != year || date.getMonth() + 1 != month || date.getDate() != day) {
			throw 'Invalid date';
		}
		return date;
	},

	/* A date may be specified as an exact value or a relative one.
	   @param  dateSpec     (Date or number or string) the date as an object or string
	                        in the given format or an offset - numeric days from today,
	                        or string amounts and periods, e.g. '+1m +2w'
	   @param  defaultDate  (Date) the date to use if no other supplied, may be null
	   @param  currentDate  (Date) the current date as a possible basis for relative dates,
	                        if null today is used (optional)
	   @param  dateFormat   (string) the expected date format - see formatDate above (optional)
	   @param  settings     (object) attributes include:
	                        shortYearCutoff  (number) the cutoff year for determining the century (optional)
	                        dayNamesShort    (string[7]) abbreviated names of the days from Sunday (optional)
	                        dayNames         (string[7]) names of the days from Sunday (optional)
	                        monthNamesShort  (string[12]) abbreviated names of the months (optional)
	                        monthNames       (string[12]) names of the months (optional)
	   @return  (Date) the decoded date */
	determineDate: function(dateSpec, defaultDate, currentDate, dateFormat, settings) {
		if (currentDate && typeof currentDate != 'object') {
			settings = dateFormat;
			dateFormat = currentDate;
			currentDate = null;
		}
		if (typeof dateFormat != 'string') {
			settings = dateFormat;
			dateFormat = '';
		}
		var offsetString = function(offset) {
			try {
				return $.datetimebookingpick.parseDate(dateFormat, offset, settings);
			}
			catch (e) {
				// Ignore
			}
			offset = offset.toLowerCase();
			var date = (offset.match(/^c/) && currentDate ? $.datetimebookingpick.newDate(currentDate) : null) ||
				$.datetimebookingpick.today();
			var pattern = /([+-]?[0-9]+)\s*(d|w|m|y)?/g;
			var matches = pattern.exec(offset);
			while (matches) {
				date = $.datetimebookingpick.add(date, parseInt(matches[1], 10), matches[2] || 'd');
				matches = pattern.exec(offset);
			}
			return date;
		};
		defaultDate = (defaultDate ? $.datetimebookingpick.newDate(defaultDate) : null);
		dateSpec = (dateSpec == null ? defaultDate :
			(typeof dateSpec == 'string' ? offsetString(dateSpec) : (typeof dateSpec == 'number' ?
			(isNaN(dateSpec) || dateSpec == Infinity || dateSpec == -Infinity ? defaultDate :
			$.datetimebookingpick.add($.datetimebookingpick.today(), dateSpec, 'd')) : $.datetimebookingpick.newDate(dateSpec))));
		return dateSpec;
	},

	/* Find the number of days in a given month.
	   @param  year   (Date) the date to get days for or
	                  (number) the full year
	   @param  month  (number) the month (1 to 12)
	   @return  (number) the number of days in this month */
	daysInMonth: function(year, month) {
		month = (year.getFullYear ? year.getMonth() + 1 : month);
		year = (year.getFullYear ? year.getFullYear() : year);
		return this.newDate(year, month + 1, 0).getDate();
	},

	/* Calculate the day of the year for a date.
	   @param  year   (Date) the date to get the day-of-year for or
	                  (number) the full year
	   @param  month  (number) the month (1-12)
	   @param  day    (number) the day
	   @return  (number) the day of the year */
	dayOfYear: function(year, month, day) {
		var date = (year.getFullYear ? year : this.newDate(year, month, day));
		var newYear = this.newDate(date.getFullYear(), 1, 1);
		return Math.floor((date.getTime() - newYear.getTime()) / this._msPerDay) + 1;
	},

	/* Set as calculateWeek to determine the week of the year based on the ISO 8601 definition.
	   @param  year   (Date) the date to get the week for or
	                  (number) the full year
	   @param  month  (number) the month (1-12)
	   @param  day    (number) the day
	   @return  (number) the number of the week within the year that contains this date */
	iso8601Week: function(year, month, day) {
		var checkDate = (year.getFullYear ?
			new Date(year.getTime()) : this.newDate(year, month, day));
		// Find Thursday of this week starting on Monday
		checkDate.setDate(checkDate.getDate() + 4 - (checkDate.getDay() || 7));
		var time = checkDate.getTime();
		checkDate.setMonth(0, 1); // Compare with Jan 1
		return Math.floor(Math.round((time - checkDate) / 86400000) / 7) + 1;
	},

	/* Return today's date.
	   @return  (Date) today */
	today: function() {
		return this._normaliseDate(new Date());
	},

	/* Return a new date.
	   @param  year   (Date) the date to clone or
					  (number) the year
	   @param  month  (number) the month (1-12)
	   @param  day    (number) the day
	   @return  (Date) the date */
	newDate: function(year, month, day) {
		return (!year ? null : (year.getFullYear ? this._normaliseDate(new Date(year.getTime())) :
			new Date(year, month - 1, day, 12)));
	},

	/* Standardise a date into a common format - time portion is 12 noon.
	   @param  date  (Date) the date to standardise
	   @return  (Date) the normalised date */
	_normaliseDate: function(date) {
		if (date) {
			date.setHours(12, 0, 0, 0);
		}
		return date;
	},

	/* Set the year for a date.
	   @param  date  (Date) the original date
	   @param  year  (number) the new year
	   @return  the updated date */
	year: function(date, year) {
		date.setFullYear(year);
		return this._normaliseDate(date);
	},

	/* Set the month for a date.
	   @param  date   (Date) the original date
	   @param  month  (number) the new month (1-12)
	   @return  the updated date */
	month: function(date, month) {
		date.setMonth(month - 1);
		return this._normaliseDate(date);
	},

	/* Set the day for a date.
	   @param  date  (Date) the original date
	   @param  day   (number) the new day of the month
	   @return  the updated date */
	day: function(date, day) {
		date.setDate(day);
		return this._normaliseDate(date);
	},

	/* Add a number of periods to a date.
	   @param  date    (Date) the original date
	   @param  amount  (number) the number of periods
	   @param  period  (string) the type of period d/w/m/y
	   @return  the updated date */
	add: function(date, amount, period) {
		if (period == 'd' || period == 'w') {
			this._normaliseDate(date);
			date.setDate(date.getDate() + amount * (period == 'w' ? 7 : 1));
		}
		else {
			var year = date.getFullYear() + (period == 'y' ? amount : 0);
			var month = date.getMonth() + (period == 'm' ? amount : 0);
			date.setTime($.datetimebookingpick.newDate(year, month + 1,
				Math.min(date.getDate(), this.daysInMonth(year, month + 1))).getTime());
		}
		return date;
	},

	/* Apply the months offset value to a date.
	   @param  date  (Date) the original date
	   @param  inst  (object) the current instance settings
	   @return  (Date) the updated date */
	_applyMonthsOffset: function(date, inst) {
		var monthsOffset = inst.get('monthsOffset');
		if ($.isFunction(monthsOffset)) {
			monthsOffset = monthsOffset.apply(inst.target[0], [date]);
		}
		return $.datetimebookingpick.add(date, -monthsOffset, 'm');
	},

	/* Attach the datetimebookingpicker functionality to an input field.
	   @param  target    (element) the control to affect
	   @param  settings  (object) the custom options for this instance */
	_attachPicker: function(target, settings) {
		target = $(target);
		if (target.hasClass(this.markerClass)) {
			return;
		}
		target.addClass(this.markerClass);
		var inst = {target: target, selectedDates: [], drawDate: null, pickingRange: false,
			inline: ($.inArray(target[0].nodeName.toLowerCase(), ['div', 'span']) > -1),
			get: function(name) { // Get a setting value, defaulting if necessary
				var value = this.settings[name] !== undefined ?
					this.settings[name] : $.datetimebookingpick._defaults[name];
				if ($.inArray(name, ['defaultDate', 'minDate', 'maxDate']) > -1) { // Decode date settings
					value = $.datetimebookingpick.determineDate(
						value, null, this.selectedDates[0], this.get('dateFormat'), inst.getConfig());
				}
				return value;
			},
			curMinDate: function() {
				return (this.pickingRange ? this.selectedDates[0] : this.get('minDate'));
			},
			getConfig: function() {
				return {dayNamesShort: this.get('dayNamesShort'), dayNames: this.get('dayNames'),
					monthNamesShort: this.get('monthNamesShort'), monthNames: this.get('monthNames'),
					calculateWeek: this.get('calculateWeek'),
					shortYearCutoff: this.get('shortYearCutoff')};
			}
		};
		$.data(target[0], this.dataName, inst);
		var inlineSettings = ($.fn.metadata ? target.metadata() : {});
		inst.settings = $.extend({}, settings || {}, inlineSettings || {});
		if (inst.inline) {
			inst.drawDate = $.datetimebookingpick._checkMinMax($.datetimebookingpick.newDate(inst.selectedDates[0] ||
				inst.get('defaultDate') || $.datetimebookingpick.today()), inst);
			inst.prevDate = $.datetimebookingpick.newDate(inst.drawDate);
			this._update(target[0]);
			if ($.fn.mousewheel) {
				target.mousewheel(this._doMouseWheel);
			}
		}
		else {
			this._attachments(target, inst);
			target.bind('keydown.' + this.dataName, this._keyDown).
				bind('keypress.' + this.dataName, this._keyPress).
				bind('keyup.' + this.dataName, this._keyUp);
			if (target.attr('disabled')) {
				this.disable(target[0]);
			}
		}
	},

	/* Retrieve the settings for a datetimebookingpicker control.
	   @param  target  (element) the control to affect
	   @param  name    (string) the name of the setting (optional)
	   @return  (object) the current instance settings (name == 'all') or
	            (object) the default settings (no name) or
	            (any) the setting value (name supplied) */
	options: function(target, name) {
		var inst = $.data(target, this.dataName);
		return (inst ? (name ? (name == 'all' ?
			inst.settings : inst.settings[name]) : $.datetimebookingpick._defaults) : {});
	},

	/* Reconfigure the settings for a datetimebookingpicker control.
	   @param  target    (element) the control to affect
	   @param  settings  (object) the new options for this instance or
	                     (string) an individual property name
	   @param  value     (any) the individual property value (omit if settings is an object) */
	option: function(target, settings, value) {
		target = $(target);
		if (!target.hasClass(this.markerClass)) {
			return;
		}
		settings = settings || {};
		if (typeof settings == 'string') {
			var name = settings;
			settings = {};
			settings[name] = value;
		}
		var inst = $.data(target[0], this.dataName);
		var dates = inst.selectedDates;
		extendRemove(inst.settings, settings);
		this.setDate(target[0], dates, null, false, true);
		inst.pickingRange = false;
		inst.drawDate = $.datetimebookingpick.newDate(this._checkMinMax(
			(settings.defaultDate ? inst.get('defaultDate') : inst.drawDate) ||
			inst.get('defaultDate') || $.datetimebookingpick.today(), inst));
		if (!inst.inline) {
			this._attachments(target, inst);
		}
		if (inst.inline || inst.div) {
			this._update(target[0]);
		}
	},

	/* Attach events and trigger, if necessary.
	   @param  target  (jQuery) the control to affect
	   @param  inst    (object) the current instance settings */
	_attachments: function(target, inst) {
		target.unbind('focus.' + this.dataName);
		if (inst.get('showOnFocus')) {
			target.bind('focus.' + this.dataName, this.show);
		}
		if (inst.trigger) {
			inst.trigger.remove();
		}
		var trigger = inst.get('showTrigger');
		inst.trigger = (!trigger ? $([]) :
			$(trigger).clone().removeAttr('id').addClass(this._triggerClass)
				[inst.get('isRTL') ? 'insertBefore' : 'insertAfter'](target).
				click(function() {
					if (!$.datetimebookingpick.isDisabled(target[0])) {
						$.datetimebookingpick[$.datetimebookingpick.curInst == inst ?
							'hide' : 'show'](target[0]);
					}
				}));
		this._autoSize(target, inst);
		var dates = this._extractDates(inst, target.val());
		if (dates) {
			this.setDate(target[0], dates, null, true);
		}
		if (inst.get('selectDefaultDate') && inst.get('defaultDate') &&
				inst.selectedDates.length == 0) {
			this.setDate(target[0], $.datetimebookingpick.newDate(inst.get('defaultDate') || $.datetimebookingpick.today()));
		}
	},

	/* Apply the maximum length for the date format.
	   @param  inst  (object) the current instance settings */
	_autoSize: function(target, inst) {
		if (inst.get('autoSize') && !inst.inline) {
			var date = $.datetimebookingpick.newDate(2009, 10, 20); // Ensure double digits
			var dateFormat = inst.get('dateFormat');
			if (dateFormat.match(/[DM]/)) {
				var findMax = function(names) {
					var max = 0;
					var maxI = 0;
					for (var i = 0; i < names.length; i++) {
						if (names[i].length > max) {
							max = names[i].length;
							maxI = i;
						}
					}
					return maxI;
				};
				date.setMonth(findMax(inst.get(dateFormat.match(/MM/) ? // Longest month
					'monthNames' : 'monthNamesShort')));
				date.setDate(findMax(inst.get(dateFormat.match(/DD/) ? // Longest day
					'dayNames' : 'dayNamesShort')) + 20 - date.getDay());
			}
			inst.target.attr('size', $.datetimebookingpick.formatDate(dateFormat, date, inst.getConfig()).length);
		}
	},

	/* Remove the datetimebookingpicker functionality from a control.
	   @param  target  (element) the control to affect */
	destroy: function(target) {
		target = $(target);
		if (!target.hasClass(this.markerClass)) {
			return;
		}
		var inst = $.data(target[0], this.dataName);
		if (inst.trigger) {
			inst.trigger.remove();
		}
		target.removeClass(this.markerClass).empty().unbind('.' + this.dataName);
		if (inst.inline && $.fn.mousewheel) {
			target.unmousewheel();
		}
		if (!inst.inline && inst.get('autoSize')) {
			target.removeAttr('size');
		}
		$.removeData(target[0], this.dataName);
	},

	/* Apply multiple event functions.
	   Usage, for example: onShow: multipleEvents(fn1, fn2, ...)
	   @param  fns  (function...) the functions to apply */
	multipleEvents: function(fns) {
		var funcs = arguments;
		return function(args) {
			for (var i = 0; i < funcs.length; i++) {
				funcs[i].apply(this, arguments);
			}
		};
	},

	/* Enable the datetimebookingpicker and any associated trigger.
	   @param  target  (element) the control to use */
	enable: function(target) {
		var $target = $(target);
		if (!$target.hasClass(this.markerClass)) {
			return;
		}
		var inst = $.data(target, this.dataName);
		if (inst.inline)
			$target.children('.' + this._disableClass).remove().end().
				find('button,select').attr('disabled', '').end().
				find('a').attr('href', 'javascript:void(0)');
		else {
			target.disabled = false;
			inst.trigger.filter('button.' + this._triggerClass).
				attr('disabled', '').end().
				filter('img.' + this._triggerClass).
				css({opacity: '1.0', cursor: ''});
		}
		this._disabled = $.map(this._disabled,
			function(value) { return (value == target ? null : value); }); // Delete entry
	},

	/* Disable the datetimebookingpicker and any associated trigger.
	   @param  target  (element) the control to use */
	disable: function(target) {
		var $target = $(target);
		if (!$target.hasClass(this.markerClass))
			return;
		var inst = $.data(target, this.dataName);
		if (inst.inline) {
			var inline = $target.children(':last');
			var offset = inline.offset();
			var relOffset = {left: 0, top: 0};
			inline.parents().each(function() {
				if ($(this).css('position') == 'relative') {
					relOffset = $(this).offset();
					return false;
				}
			});
			var zIndex = $target.css('zIndex');
			zIndex = (zIndex == 'auto' ? 0 : parseInt(zIndex, 10)) + 1;
			$target.prepend('<div class="' + this._disableClass + '" style="' +
				'width: ' + inline.outerWidth() + 'px; height: ' + inline.outerHeight() +
				'px; left: ' + (offset.left - relOffset.left) + 'px; top: ' +
				(offset.top - relOffset.top) + 'px; z-index: ' + zIndex + '"></div>').
				find('button,select').attr('disabled', 'disabled').end().
				find('a').removeAttr('href');
		}
		else {
			target.disabled = true;
			inst.trigger.filter('button.' + this._triggerClass).
				attr('disabled', 'disabled').end().
				filter('img.' + this._triggerClass).
				css({opacity: '0.5', cursor: 'default'});
		}
		this._disabled = $.map(this._disabled,
			function(value) { return (value == target ? null : value); }); // Delete entry
		this._disabled.push(target);
	},

	/* Is the first field in a jQuery collection disabled as a datetimebookingpicker?
	   @param  target  (element) the control to examine
	   @return  (boolean) true if disabled, false if enabled */
	isDisabled: function(target) {
		return (target && $.inArray(target, this._disabled) > -1);
	},

	/* Show a popup datetimebookingpicker.
	   @param  target  (event) a focus event or
	                   (element) the control to use */
	show: function(target) {
		target = target.target || target;
		var inst = $.data(target, $.datetimebookingpick.dataName);
		if ($.datetimebookingpick.curInst == inst) {
			return;
		}
		if ($.datetimebookingpick.curInst) {
			$.datetimebookingpick.hide($.datetimebookingpick.curInst, true);
		}
		if (inst) {
			// Retrieve existing date(s)
			inst.lastVal = null;
			inst.selectedDates = $.datetimebookingpick._extractDates(inst, $(target).val());
			inst.pickingRange = false;
			inst.drawDate = $.datetimebookingpick._checkMinMax($.datetimebookingpick.newDate(inst.selectedDates[0] ||
				inst.get('defaultDate') || $.datetimebookingpick.today()), inst);
			inst.prevDate = $.datetimebookingpick.newDate(inst.drawDate);
			$.datetimebookingpick.curInst = inst;
			// Generate content
			$.datetimebookingpick._update(target, true);
			// Adjust position before showing
			var offset = $.datetimebookingpick._checkOffset(inst);
			inst.div.css({left: offset.left, top: offset.top});
			// And display
			var showAnim = inst.get('showAnim');
			var showSpeed = inst.get('showSpeed');
			showSpeed = (showSpeed == 'normal' && $.ui && $.ui.version >= '1.8' ?
				'_default' : showSpeed);
			var postProcess = function() {
				var cover = inst.div.find('.' + $.datetimebookingpick._coverClass);
				if (cover.length) {
					var borders = $.datetimebookingpick._getBorders(inst.div);
					cover.css({left: -borders[0], top: -borders[1], // IE6- only
						width: inst.div.outerWidth() + borders[0],
						height: inst.div.outerHeight() + borders[1]});
				}
			};
			if ($.effects && $.effects[showAnim]) {
				var data = inst.div.data(); // Update old effects data
				for (var key in data) {
					if (key.match(/^ec\.storage\./)) {
						data[key] = inst._mainDiv.css(key.replace(/ec\.storage\./, ''));
					}
				}
				inst.div.data(data).show(showAnim, inst.get('showOptions'), showSpeed, postProcess);
			}
			else {
				inst.div[showAnim || 'show']((showAnim ? showSpeed : ''), postProcess);
			}
			if (!showAnim) {
				postProcess();
			}
		}
	},

	/* Extract possible dates from a string.
	   @param  inst  (object) the current instance settings
	   @param  text  (string) the text to extract from
	   @return  (CDate[]) the extracted dates */
	_extractDates: function(inst, datesText) {
		if (datesText == inst.lastVal) {
			return;
		}
		inst.lastVal = datesText;
		var dateFormat = inst.get('dateFormat');
		var multiSelect = inst.get('multiSelect');
		var rangeSelect = inst.get('rangeSelect');
		datesText = datesText.split(multiSelect ? inst.get('multiSeparator') :
			(rangeSelect ? inst.get('rangeSeparator') : '\x00'));
		var dates = [];
		for (var i = 0; i < datesText.length; i++) {
			try {
				var date = $.datetimebookingpick.parseDate(dateFormat, datesText[i], inst.getConfig());
				if (date) {
					var found = false;
					for (var j = 0; j < dates.length; j++) {
						if (dates[j].getTime() == date.getTime()) {
							found = true;
							break;
						}
					}
					if (!found) {
						dates.push(date);
					}
				}
			}
			catch (e) {
				// Ignore
			}
		}
		dates.splice(multiSelect || (rangeSelect ? 2 : 1), dates.length);
		if (rangeSelect && dates.length == 1) {
			dates[1] = dates[0];
		}
		return dates;
	},

	/* Update the datetimebookingpicker display.
	   @param  target  (event) a focus event or
	                   (element) the control to use
	   @param  hidden  (boolean) true to initially hide the datetimebookingpicker */
	_update: function(target, hidden) {
		target = $(target.target || target);
		var inst = $.data(target[0], $.datetimebookingpick.dataName);
		if (inst) {
			if (inst.inline || $.datetimebookingpick.curInst == inst) {
				var onChange = inst.get('onChangeMonthYear');
				if (onChange && (!inst.prevDate ||
						inst.prevDate.getFullYear() != inst.drawDate.getFullYear() ||
						inst.prevDate.getMonth() != inst.drawDate.getMonth())) {
					onChange.apply(target[0], [inst.drawDate.getFullYear(), inst.drawDate.getMonth() + 1]);
				}
			}
			if (inst.inline) {
				target.html(this._generateContent(target[0], inst));
			}
			else if ($.datetimebookingpick.curInst == inst) {
				if (!inst.div) {
					inst.div = $('<div></div>').addClass(this._popupClass).
						css({display: (hidden ? 'none' : 'static'), position: 'absolute',
							left: target.offset().left,
							top: target.offset().top + target.outerHeight()}).
						appendTo($(inst.get('popupContainer') || 'body'));
					if ($.fn.mousewheel) {
						inst.div.mousewheel(this._doMouseWheel);
					}
				}
				inst.div.html(this._generateContent(target[0], inst));
				target.focus();
			}
		}
	},

	/* Update the input field and any alternate field with the current dates.
	   @param  target  (element) the control to use
	   @param  keyUp   (boolean, internal) true if coming from keyUp processing */
	_updateInput: function(target, keyUp) {
		var inst = $.data(target, this.dataName);
		if (inst) {
			var value = '';
			var altValue = '';
			var sep = (inst.get('multiSelect') ? inst.get('multiSeparator') :
				inst.get('rangeSeparator'));
			var dateFormat = inst.get('dateFormat');
			var altFormat = inst.get('altFormat') || dateFormat;
			for (var i = 0; i < inst.selectedDates.length; i++) {
				value += (keyUp ? '' : (i > 0 ? sep : '') + $.datetimebookingpick.formatDate(
					dateFormat, inst.selectedDates[i], inst.getConfig()));
				altValue += (i > 0 ? sep : '') + $.datetimebookingpick.formatDate(
					altFormat, inst.selectedDates[i], inst.getConfig());
			}
			if (!inst.inline && !keyUp) {
				$(target).val(value);
			}
			$(inst.get('altField')).val(altValue);
			var onSelect = inst.get('onSelect');
			if (onSelect && !keyUp && !inst.inSelect) {
				inst.inSelect = true; // Prevent endless loops
				onSelect.apply(target, [inst.selectedDates]);
				inst.inSelect = false;
			}
		}
	},

	/* Retrieve the size of left and top borders for an element.
	   @param  elem  (jQuery) the element of interest
	   @return  (number[2]) the left and top borders */
	_getBorders: function(elem) {
		var convert = function(value) {
			var extra = ($.browser.msie ? 1 : 0);
			return {thin: 1 + extra, medium: 3 + extra, thick: 5 + extra}[value] || value;
		};
		return [parseFloat(convert(elem.css('border-left-width'))),
			parseFloat(convert(elem.css('border-top-width')))];
	},

	/* Check positioning to remain on the screen.
	   @param  inst  (object) the current instance settings
	   @return  (object) the updated offset for the datetimebookingpicker */
	_checkOffset: function(inst) {
		var base = (inst.target.is(':hidden') && inst.trigger ? inst.trigger : inst.target);
		var offset = base.offset();
		var isFixed = false;
		$(inst.target).parents().each(function() {
			isFixed |= $(this).css('position') == 'fixed';
			return !isFixed;
		});
		if (isFixed && $.browser.opera) { // Correction for Opera when fixed and scrolled
			offset.left -= document.documentElement.scrollLeft;
			offset.top -= document.documentElement.scrollTop;
		}
		var browserWidth = (!$.browser.mozilla || document.doctype ?
			document.documentElement.clientWidth : 0) || document.body.clientWidth;
		var browserHeight = (!$.browser.mozilla || document.doctype ?
			document.documentElement.clientHeight : 0) || document.body.clientHeight;
		if (browserWidth == 0) {
			return offset;
		}
		var alignment = inst.get('alignment');
		var isRTL = inst.get('isRTL');
		var scrollX = document.documentElement.scrollLeft || document.body.scrollLeft;
		var scrollY = document.documentElement.scrollTop || document.body.scrollTop;
		var above = offset.top - inst.div.outerHeight() -
			(isFixed && $.browser.opera ? document.documentElement.scrollTop : 0);
		var below = offset.top + base.outerHeight();
		var alignL = offset.left;
		var alignR = offset.left + base.outerWidth() - inst.div.outerWidth() -
			(isFixed && $.browser.opera ? document.documentElement.scrollLeft : 0);
		var tooWide = (offset.left + inst.div.outerWidth() - scrollX) > browserWidth;
		var tooHigh = (offset.top + inst.target.outerHeight() + inst.div.outerHeight() -
			scrollY) > browserHeight;
		if (alignment == 'topLeft') {
			offset = {left: alignL, top: above};
		}
		else if (alignment == 'topRight') {
			offset = {left: alignR, top: above};
		}
		else if (alignment == 'bottomLeft') {
			offset = {left: alignL, top: below};
		}
		else if (alignment == 'bottomRight') {
			offset = {left: alignR, top: below};
		}
		else if (alignment == 'top') {
			offset = {left: (isRTL || tooWide ? alignR : alignL), top: above};
		}
		else { // bottom
			offset = {left: (isRTL || tooWide ? alignR : alignL),
				top: (tooHigh ? above : below)};
		}
		offset.left = Math.max((isFixed ? 0 : scrollX), offset.left - (isFixed ? scrollX : 0));
		offset.top = Math.max((isFixed ? 0 : scrollY), offset.top - (isFixed ? scrollY : 0));
		return offset;
	},

	/* Close date picker if clicked elsewhere.
	   @param  event  (MouseEvent) the mouse click to check */
	_checkExternalClick: function(event) {
		if (!$.datetimebookingpick.curInst) {
			return;
		}
		var target = $(event.target);
		if (!target.parents().andSelf().hasClass($.datetimebookingpick._popupClass) &&
				!target.hasClass($.datetimebookingpick.markerClass) &&
				!target.parents().andSelf().hasClass($.datetimebookingpick._triggerClass)) {
			$.datetimebookingpick.hide($.datetimebookingpick.curInst);
		}
	},

	/* Hide a popup datetimebookingpicker.
	   @param  target     (element) the control to use or
	                      (object) the current instance settings
	   @param  immediate  (boolean) true to close immediately without animation */
	hide: function(target, immediate) {
		var inst = $.data(target, this.dataName) || target;
		if (inst && inst == $.datetimebookingpick.curInst) {
			var showAnim = (immediate ? '' : inst.get('showAnim'));
			var showSpeed = inst.get('showSpeed');
			showSpeed = (showSpeed == 'normal' && $.ui && $.ui.version >= '1.8' ?
				'_default' : showSpeed);
			var postProcess = function() {
				inst.div.remove();
				inst.div = null;
				$.datetimebookingpick.curInst = null;
				var onClose = inst.get('onClose');
				if (onClose) {
					onClose.apply(target, [inst.selectedDates]);
				}
			};
			inst.div.stop();
			if ($.effects && $.effects[showAnim]) {
				inst.div.hide(showAnim, inst.get('showOptions'), showSpeed, postProcess);
			}
			else {
				var hideAnim = (showAnim == 'slideDown' ? 'slideUp' :
					(showAnim == 'fadeIn' ? 'fadeOut' : 'hide'));
				inst.div[hideAnim]((showAnim ? showSpeed : ''), postProcess);
			}
			if (!showAnim) {
				postProcess();
			}
		}
	},

	/* Handle keystrokes in the datetimebookingpicker.
	   @param  event  (KeyEvent) the keystroke
	   @return  (boolean) true if not handled, false if handled */
	_keyDown: function(event) {
		var target = event.target;
		var inst = $.data(target, $.datetimebookingpick.dataName);
		var handled = false;
		if (inst.div) {
			if (event.keyCode == 9) { // Tab - close
				$.datetimebookingpick.hide(target);
			}
			else if (event.keyCode == 13) { // Enter - select
				$.datetimebookingpick.selectDate(target,
					$('a.' + inst.get('renderer').highlightedClass, inst.div)[0]);
				handled = true;
			}
			else { // Command keystrokes
				var commands = inst.get('commands');
				for (var name in commands) {
					var command = commands[name];
					if (command.keystroke.keyCode == event.keyCode &&
							!!command.keystroke.ctrlKey == !!(event.ctrlKey || event.metaKey) &&
							!!command.keystroke.altKey == event.altKey &&
							!!command.keystroke.shiftKey == event.shiftKey) {
						$.datetimebookingpick.performAction(target, name);
						handled = true;
						break;
					}
				}
			}
		}
		else { // Show on 'current' keystroke
			var command = inst.get('commands').current;
			if (command.keystroke.keyCode == event.keyCode &&
					!!command.keystroke.ctrlKey == !!(event.ctrlKey || event.metaKey) &&
					!!command.keystroke.altKey == event.altKey &&
					!!command.keystroke.shiftKey == event.shiftKey) {
				$.datetimebookingpick.show(target);
				handled = true;
			}
		}
		inst.ctrlKey = ((event.keyCode < 48 && event.keyCode != 32) ||
			event.ctrlKey || event.metaKey);
		if (handled) {
			event.preventDefault();
			event.stopPropagation();
		}
		return !handled;
	},

	/* Filter keystrokes in the datetimebookingpicker.
	   @param  event  (KeyEvent) the keystroke
	   @return  (boolean) true if allowed, false if not allowed */
	_keyPress: function(event) {
		var target = event.target;
		var inst = $.data(target, $.datetimebookingpick.dataName);
		if (inst && inst.get('constrainInput')) {
			var ch = String.fromCharCode(event.keyCode || event.charCode);
			var allowedChars = $.datetimebookingpick._allowedChars(inst);
			return (event.metaKey || inst.ctrlKey || ch < ' ' ||
				!allowedChars || allowedChars.indexOf(ch) > -1);
		}
		return true;
	},

	/* Determine the set of characters allowed by the date format.
	   @param  inst  (object) the current instance settings
	   @return  (string) the set of allowed characters, or null if anything allowed */
	_allowedChars: function(inst) {
		var dateFormat = inst.get('dateFormat');
		var allowedChars = (inst.get('multiSelect') ? inst.get('multiSeparator') :
			(inst.get('rangeSelect') ? inst.get('rangeSeparator') : ''));
		var literal = false;
		var hasNum = false;
		for (var i = 0; i < dateFormat.length; i++) {
			var ch = dateFormat.charAt(i);
			if (literal) {
				if (ch == "'" && dateFormat.charAt(i + 1) != "'") {
					literal = false;
				}
				else {
					allowedChars += ch;
				}
			}
			else {
				switch (ch) {
					case 'd': case 'm': case 'o': case 'w':
						allowedChars += (hasNum ? '' : '0123456789'); hasNum = true; break;
					case 'y': case '@': case '!':
						allowedChars += (hasNum ? '' : '0123456789') + '-'; hasNum = true; break;
					case 'J':
						allowedChars += (hasNum ? '' : '0123456789') + '-.'; hasNum = true; break;
					case 'D': case 'M': case 'Y':
						return null; // Accept anything
					case "'":
						if (dateFormat.charAt(i + 1) == "'") {
							allowedChars += "'";
						}
						else {
							literal = true;
						}
						break;
					default:
						allowedChars += ch;
				}
			}
		}
		return allowedChars;
	},

	/* Synchronise datetimebookingpicker with the field.
	   @param  event  (KeyEvent) the keystroke
	   @return  (boolean) true if allowed, false if not allowed */
	_keyUp: function(event) {
		var target = event.target;
		var inst = $.data(target, $.datetimebookingpick.dataName);
		if (inst && !inst.ctrlKey && inst.lastVal != inst.target.val()) {
			try {
				var dates = $.datetimebookingpick._extractDates(inst, inst.target.val());
				if (dates.length > 0) {
					$.datetimebookingpick.setDate(target, dates, null, true);
				}
			}
			catch (event) {
				// Ignore
			}
		}
		return true;
	},

	/* Increment/decrement month/year on mouse wheel activity.
	   @param  event  (event) the mouse wheel event
	   @param  delta  (number) the amount of change */
	_doMouseWheel: function(event, delta) {
		var target = ($.datetimebookingpick.curInst && $.datetimebookingpick.curInst.target[0]) ||
			$(event.target).closest('.' + $.datetimebookingpick.markerClass)[0];
		if ($.datetimebookingpick.isDisabled(target)) {
			return;
		}
		var inst = $.data(target, $.datetimebookingpick.dataName);
		if (inst.get('useMouseWheel')) {
			delta = ($.browser.opera ? -delta : delta);
			delta = (delta < 0 ? -1 : +1);
			$.datetimebookingpick.changeMonth(target,
				-inst.get(event.ctrlKey ? 'monthsToJump' : 'monthsToStep') * delta);
		}
		event.preventDefault();
	},

	/* Clear an input and close a popup datetimebookingpicker.
	   @param  target  (element) the control to use */
	clear: function(target) {
		var inst = $.data(target, this.dataName);
		if (inst) {
			inst.selectedDates = [];
			this.hide(target);
			if (inst.get('selectDefaultDate') && inst.get('defaultDate')) {
				this.setDate(target,
					$.datetimebookingpick.newDate(inst.get('defaultDate') ||$.datetimebookingpick.today()));
			}
			else {
				this._updateInput(target);
			}
		}
	},

	/* Retrieve the selected date(s) for a datetimebookingpicker.
	   @param  target  (element) the control to examine
	   @return  (CDate[]) the selected date(s) */
	getDate: function(target) {
		var inst = $.data(target, this.dataName);
		return (inst ? inst.selectedDates : []);
	},

	/* Set the selected date(s) for a datetimebookingpicker.
	   @param  target   (element) the control to examine
	   @param  dates    (CDate or number or string or [] of these) the selected date(s)
	   @param  endDate  (CDate or number or string) the ending date for a range (optional)
	   @param  keyUp    (boolean, internal) true if coming from keyUp processing
	   @param  setOpt   (boolean, internal) true if coming from option processing */
	setDate: function(target, dates, endDate, keyUp, setOpt) {
		var inst = $.data(target, this.dataName);
		if (inst) {
			if (!$.isArray(dates)) {
				dates = [dates];
				if (endDate) {
					dates.push(endDate);
				}
			}
			var dateFormat = inst.get('dateFormat');
			var minDate = inst.get('minDate');
			var maxDate = inst.get('maxDate');
			var curDate = inst.selectedDates[0];
			inst.selectedDates = [];
			for (var i = 0; i < dates.length; i++) {
				var date = $.datetimebookingpick.determineDate(
					dates[i], null, curDate, dateFormat, inst.getConfig());
				if (date) {
					if ((!minDate || date.getTime() >= minDate.getTime()) &&
							(!maxDate || date.getTime() <= maxDate.getTime())) {
						var found = false;
						for (var j = 0; j < inst.selectedDates.length; j++) {
							if (inst.selectedDates[j].getTime() == date.getTime()) {
								found = true;
								break;
							}
						}
						if (!found) {
							inst.selectedDates.push(date);
						}
					}
				}
			}
			var rangeSelect = inst.get('rangeSelect');
			inst.selectedDates.splice(inst.get('multiSelect') ||
				(rangeSelect ? 2 : 1), inst.selectedDates.length);
			if (rangeSelect) {
				switch (inst.selectedDates.length) {
					case 1: inst.selectedDates[1] = inst.selectedDates[0]; break;
					case 2: inst.selectedDates[1] =
						(inst.selectedDates[0].getTime() > inst.selectedDates[1].getTime() ?
						inst.selectedDates[0] : inst.selectedDates[1]); break;
				}
				inst.pickingRange = false;
			}
			inst.prevDate = (inst.drawDate ? $.datetimebookingpick.newDate(inst.drawDate) : null);
			inst.drawDate = this._checkMinMax($.datetimebookingpick.newDate(inst.selectedDates[0] ||
				inst.get('defaultDate') || $.datetimebookingpick.today()), inst);
			if (!setOpt) {
				this._update(target);
				this._updateInput(target, keyUp);
			}
		}
	},

	/* Determine whether a date is selectable for this datetimebookingpicker.
	   @param  target  (element) the control to check
	   @param  date    (Date or string or number) the date to check
	   @return  (boolean) true if selectable, false if not */
	isSelectable: function(target, date) {
		var inst = $.data(target, this.dataName);
		if (!inst) {
			return false;
		}
		date = $.datetimebookingpick.determineDate(date, inst.selectedDates[0] || this.today(), null,
			inst.get('dateFormat'), inst.getConfig());
		return this._isSelectable(target, date, inst.get('onDate'),
			inst.get('minDate'), inst.get('maxDate'));
	},

	/* Internally determine whether a date is selectable for this datetimebookingpicker.
	   @param  target   (element) the control to check
	   @param  date     (Date) the date to check
	   @param  onDate   (function or boolean) any onDate callback or callback.selectable
	   @param  mindate  (Date) the minimum allowed date
	   @param  maxdate  (Date) the maximum allowed date
	   @return  (boolean) true if selectable, false if not */
	_isSelectable: function(target, date, onDate, minDate, maxDate) {
		var dateInfo = (typeof onDate == 'boolean' ? {selectable: onDate} :
			(!onDate ? {} : onDate.apply(target, [date, true])));
		return (dateInfo.selectable != false) &&
			(!minDate || date.getTime() >= minDate.getTime()) &&
			(!maxDate || date.getTime() <= maxDate.getTime());
	},

	/* Perform a named action for a datetimebookingpicker.
	   @param  target  (element) the control to affect
	   @param  action  (string) the name of the action */
	performAction: function(target, action) {
		var inst = $.data(target, this.dataName);
		if (inst && !this.isDisabled(target)) {
			var commands = inst.get('commands');
			if (commands[action] && commands[action].enabled.apply(target, [inst])) {
				commands[action].action.apply(target, [inst]);
			}
		}
	},

	/* Set the currently shown month, defaulting to today's.
	   @param  target  (element) the control to affect
	   @param  year    (number) the year to show (optional)
	   @param  month   (number) the month to show (1-12) (optional)
	   @param  day     (number) the day to show (optional) */
	showMonth: function(target, year, month, day) {
		var inst = $.data(target, this.dataName);
		if (inst && (day != null ||
				(inst.drawDate.getFullYear() != year || inst.drawDate.getMonth() + 1 != month))) {
			inst.prevDate = $.datetimebookingpick.newDate(inst.drawDate);
			var show = this._checkMinMax((year != null ?
				$.datetimebookingpick.newDate(year, month, 1) : $.datetimebookingpick.today()), inst);
			inst.drawDate = $.datetimebookingpick.newDate(show.getFullYear(), show.getMonth() + 1, 
				(day != null ? day : Math.min(inst.drawDate.getDate(),
				$.datetimebookingpick.daysInMonth(show.getFullYear(), show.getMonth() + 1))));
			this._update(target);
		}
	},

	/* Adjust the currently shown month.
	   @param  target  (element) the control to affect
	   @param  offset  (number) the number of months to change by */
	changeMonth: function(target, offset) {
		var inst = $.data(target, this.dataName);
		if (inst) {
			var date = $.datetimebookingpick.add($.datetimebookingpick.newDate(inst.drawDate), offset, 'm');
			this.showMonth(target, date.getFullYear(), date.getMonth() + 1);
		}
	},

	/* Adjust the currently shown day.
	   @param  target  (element) the control to affect
	   @param  offset  (number) the number of days to change by */
	changeDay: function(target, offset) {
		var inst = $.data(target, this.dataName);
		if (inst) {
			var date = $.datetimebookingpick.add($.datetimebookingpick.newDate(inst.drawDate), offset, 'd');
			this.showMonth(target, date.getFullYear(), date.getMonth() + 1, date.getDate());
		}
	},

	/* Restrict a date to the minimum/maximum specified.
	   @param  date  (CDate) the date to check
	   @param  inst  (object) the current instance settings */
	_checkMinMax: function(date, inst) {
		var minDate = inst.get('minDate');
		var maxDate = inst.get('maxDate');
		date = (minDate && date.getTime() < minDate.getTime() ? $.datetimebookingpick.newDate(minDate) : date);
		date = (maxDate && date.getTime() > maxDate.getTime() ? $.datetimebookingpick.newDate(maxDate) : date);
		return date;
	},

	/* Retrieve the date associated with an entry in the datetimebookingpicker.
	   @param  target  (element) the control to examine
	   @param  elem    (element) the selected datetimebookingpicker element
	   @return  (CDate) the corresponding date, or null */
	retrieveDate: function(target, elem) {
		var inst = $.data(target, this.dataName);
		return (!inst ? null : this._normaliseDate(
			new Date(parseInt(elem.className.replace(/^.*dp(-?\d+).*$/, '$1'), 10))));
	},

	/* Select a date for this datetimebookingpicker.
	   @param  target  (element) the control to examine
	   @param  elem    (element) the selected datetimebookingpicker element */
	selectDate: function(target, elem) {
		
		var inst = $.data(target, this.dataName);
		if (inst && !this.isDisabled(target)) {
			var date = this.retrieveDate(target, elem);
			var multiSelect = inst.get('multiSelect');
			var rangeSelect = inst.get('rangeSelect');
			if (multiSelect) {
				var found = false;
				for (var i = 0; i < inst.selectedDates.length; i++) {
					if (date.getTime() == inst.selectedDates[i].getTime()) {
						inst.selectedDates.splice(i, 1);
						found = true;
						break;
					}
				}
				if (!found && inst.selectedDates.length < multiSelect) {
					inst.selectedDates.push(date);
				}
			}
			else if (rangeSelect) {
				if (inst.pickingRange) {
					inst.selectedDates[1] = date;
				}
				else {
					inst.selectedDates = [date, date];
				}
				inst.pickingRange = !inst.pickingRange;
			}
			else {
				inst.selectedDates = [date];
			}
			inst.prevDate = $.datetimebookingpick.newDate(date);
			this._updateInput(target);
			if (inst.inline || inst.pickingRange || inst.selectedDates.length <
					(multiSelect || (rangeSelect ? 2 : 1))) {
				this._update(target);
			}
			else {
				this.hide(target);
			}
		}
	},

	/* Generate the datetimebookingpicker content for this control.
	   @param  target  (element) the control to affect
	   @param  inst    (object) the current instance settings
	   @return  (jQuery) the datetimebookingpicker content */
	_generateContent: function(target, inst) {
		var renderer = inst.get('renderer');
		var monthsToShow = inst.get('monthsToShow');
		monthsToShow = ($.isArray(monthsToShow) ? monthsToShow : [1, monthsToShow]);
		inst.drawDate = this._checkMinMax(
			inst.drawDate || inst.get('defaultDate') || $.datetimebookingpick.today(), inst);
		var drawDate = $.datetimebookingpick._applyMonthsOffset($.datetimebookingpick.newDate(inst.drawDate), inst);
		// Generate months
		var monthRows = '';
		for (var row = 0; row < monthsToShow[0]; row++) {
			var months = '';
			for (var col = 0; col < monthsToShow[1]; col++) {
				months += this._generateMonth(target, inst, drawDate.getFullYear(),
					drawDate.getMonth() + 1, renderer, (row == 0 && col == 0));
				$.datetimebookingpick.add(drawDate, 1, 'm');
			}
			monthRows += this._prepare(renderer.monthRow, inst).replace(/\{months\}/, months);
		}
		var picker = this._prepare(renderer.picker, inst).replace(/\{months\}/, monthRows).
			replace(/\{weekHeader\}/g, this._generateDayHeaders(inst, renderer)) +
			($.browser.msie && parseInt($.browser.version, 10) < 7 && !inst.inline ?
			'<iframe src="javascript:void(0);" class="' + this._coverClass + '"></iframe>' : '');
		// Add commands
		var commands = inst.get('commands');
		var asDateFormat = inst.get('commandsAsDateFormat');
		var addCommand = function(type, open, close, name, classes) {
			if (picker.indexOf('{' + type + ':' + name + '}') == -1) {
				return;
			}
			var command = commands[name];
			var date = (asDateFormat ? command.date.apply(target, [inst]) : null);
			picker = picker.replace(new RegExp('\\{' + type + ':' + name + '\\}', 'g'),
				'<' + open +
				(command.status ? ' title="' + inst.get(command.status) + '"' : '') +
				' class="' + renderer.commandClass + ' ' +
				renderer.commandClass + '-' + name + ' ' + classes +
				(command.enabled(inst) ? '' : ' ' + renderer.disabledClass) + '">' +
				(date ? $.datetimebookingpick.formatDate(inst.get(command.text), date, inst.getConfig()) :
				inst.get(command.text)) + '</' + close + '>');
		};
		for (var name in commands) {
			addCommand('button', 'button type="button"', 'button', name,
				renderer.commandButtonClass);
			addCommand('link', 'a href="javascript:void(0)"', 'a', name,
				renderer.commandLinkClass);
		}
		picker = $(picker);
		if (monthsToShow[1] > 1) {
			var count = 0;
			$(renderer.monthSelector, picker).each(function() {
				var nth = ++count % monthsToShow[1];
				$(this).addClass(nth == 1 ? 'first' : (nth == 0 ? 'last' : ''));
			});
		}
		// Add datetimebookingpicker behaviour
		var self = this;
		picker.find(renderer.daySelector+'.enable').hover(
				function() { $(this).addClass(renderer.highlightedClass); },
				function() {
					(inst.inline ? $(this).parents('.' + self.markerClass) : inst.div).
						find(renderer.daySelector + ' a').
						removeClass(renderer.highlightedClass);
				}).
			click(function() {
				self.selectDate(target, this);
			}).end().
			find('select.' + this._monthYearClass + ':not(.' + this._anyYearClass + ')').
			change(function() {
				var monthYear = $(this).val().split('/');
				self.showMonth(target, parseInt(monthYear[1], 10), parseInt(monthYear[0], 10));
			}).end().
			find('select.' + this._anyYearClass).
			click(function() {
				$(this).css('visibility', 'hidden').
					next('input').css({left: this.offsetLeft, top: this.offsetTop,
					width: this.offsetWidth, height: this.offsetHeight}).show().focus();
			}).end().
			find('input.' + self._monthYearClass).
			change(function() {
				try {
					var year = parseInt($(this).val(), 10);
					year = (isNaN(year) ? inst.drawDate.getFullYear() : year);
					self.showMonth(target, year, inst.drawDate.getMonth() + 1, inst.drawDate.getDate());
				}
				catch (e) {
					alert(e);
				}
			}).keydown(function(event) {
				if (event.keyCode == 13) { // Enter
					$(event.target).change();
				}
				else if (event.keyCode == 27) { // Escape
					$(event.target).hide().prev('select').css('visibility', 'visible');
					inst.target.focus();
				}
			});
		// Add command behaviour
		picker.find('.' + renderer.commandClass).click(function() {
				if (!$(this).hasClass(renderer.disabledClass)) {
					var action = this.className.replace(
						new RegExp('^.*' + renderer.commandClass + '-([^ ]+).*$'), '$1');
					$.datetimebookingpick.performAction(target, action);
				}
			});
		// Add classes
		if (inst.get('isRTL')) {
			picker.addClass(renderer.rtlClass);
		}
		if (monthsToShow[0] * monthsToShow[1] > 1) {
			picker.addClass(renderer.multiClass);
		}
		var pickerClass = inst.get('pickerClass');
		if (pickerClass) {
			picker.addClass(pickerClass);
		}
		// Resize
		$('body').append(picker);
		var width = 0;
		picker.find(renderer.monthSelector).each(function() {
			width += $(this).outerWidth()+($(this).css("margin-right").replace("px", "").toInt())+($(this).css("margin-left").replace("px", "").toInt());
		});
		picker.width(width / monthsToShow[0]);
		// Pre-show customisation
		var onShow = inst.get('onShow');
		if (onShow) {
			onShow.apply(target, [picker, inst]);
		}
		return picker;
	},

	/* Generate the content for a single month.
	   @param  target    (element) the control to affect
	   @param  inst      (object) the current instance settings
	   @param  year      (number) the year to generate
	   @param  month     (number) the month to generate
	   @param  renderer  (object) the rendering templates
	   @param  first     (boolean) true if first of multiple months
	   @return  (string) the month content */
	_generateMonth: function(target, inst, year, month, renderer, first) {
		var daysInMonth = $.datetimebookingpick.daysInMonth(year, month);
		var monthsToShow = inst.get('monthsToShow');
		monthsToShow = ($.isArray(monthsToShow) ? monthsToShow : [1, monthsToShow]);
		var fixedWeeks = inst.get('fixedWeeks') || (monthsToShow[0] * monthsToShow[1] > 1);
		var firstDay = inst.get('firstDay');
		var leadDays = ($.datetimebookingpick.newDate(year, month, 1).getDay() - firstDay + 7) % 7;
		var numWeeks = (fixedWeeks ? 6 : Math.ceil((leadDays + daysInMonth) / 7));
		var showOtherMonths = inst.get('showOtherMonths');
		var selectOtherMonths = inst.get('selectOtherMonths') && showOtherMonths;
		var dayStatus = inst.get('dayStatus');
		var minDate = (inst.pickingRange ? inst.selectedDates[0] : inst.get('minDate'));
		var maxDate = inst.get('maxDate');
		var rangeSelect = inst.get('rangeSelect');
		var onDate = inst.get('onDate');
		var showWeeks = renderer.week.indexOf('{weekOfYear}') > -1;
		var calculateWeek = inst.get('calculateWeek');
		var today = $.datetimebookingpick.today();
		var drawDate = $.datetimebookingpick.newDate(year, month, 1);
		
		$.datetimebookingpick.add(drawDate, -leadDays - (fixedWeeks && (drawDate.getDay() == firstDay) ? 7 : 0), 'd');
		var ts = drawDate.getTime();
		var htmls=inst.get('htmls');
		
		// Generate weeks
		var weeks = '';
		for (var week = 0; week < numWeeks; week++) {
			var weekOfYear = (!showWeeks ? '' : '<span class="dp' + ts + '">' +
				(calculateWeek ? calculateWeek(drawDate) : 0) + '</span>');
			var days = '';
			for (var day = 0; day < 7; day++) {
				var selected = false;
				if (rangeSelect && inst.selectedDates.length > 0) {
					selected = (drawDate.getTime() >= inst.selectedDates[0] &&
						drawDate.getTime() <= inst.selectedDates[1]);
				}
				else {
					for (var i = 0; i < inst.selectedDates.length; i++) {
						if (inst.selectedDates[i].getTime() == drawDate.getTime()) {
							selected = true;
							break;
						}
					}
				}
				
				var dateInfo = (!onDate ? {} :
					onDate.apply(target, [drawDate, drawDate.getMonth() + 1 == month]));
				var selectable = (selectOtherMonths || drawDate.getMonth() + 1 == month) &&
					this._isSelectable(target, drawDate, dateInfo.selectable, minDate, maxDate);
				var str_fulldate=year.toString().concat((month.toString().length==1?'0'+month.toString():month),(drawDate.getDate().toString().length==1?'0'+drawDate.getDate().toString():drawDate.getDate()));
				//selectable=htmls[str_fulldate] !== undefined?true:false;
				days += this._prepare(renderer.day, inst).replace(/\{day\}/g,
					 
					(selectable ? '<a href="javascript:void(0)"' : '<span') +
					' class="dp' + ts + ' ' + (dateInfo.dateClass || '') +
					(selected && (selectOtherMonths || drawDate.getMonth() + 1 == month) ?
					' ' + renderer.selectedClass : '') +
					(selectable ? ' ' + renderer.defaultClass : '') +
					((drawDate.getDay() || 7) < 6 ? '' : ' ' + renderer.weekendClass) +
					(drawDate.getMonth() + 1 == month ? '' : ' ' + renderer.otherMonthClass) +
					(drawDate.getTime() == today.getTime() && (drawDate.getMonth() + 1) == month ?
					' ' + renderer.todayClass : '') +
					(drawDate.getTime() == inst.drawDate.getTime() && (drawDate.getMonth() + 1) == month ?
					' ' + renderer.highlightedClass : '') + '"' +
					(dateInfo.title || (dayStatus && selectable) ? ' title="' +
					(dateInfo.title || $.datetimebookingpick.formatDate(
					dayStatus, drawDate, inst.getConfig())) + '"' : '') + '>' +
					(showOtherMonths || (drawDate.getMonth() + 1) == month ?
					dateInfo.content || drawDate.getDate() : '&nbsp;') +
					(selectable ? '</a>' : '</span>')+
					(htmls[str_fulldate] !== undefined&&(showOtherMonths || (drawDate.getMonth() + 1) == month) ?htmls[str_fulldate].status :'')+
					(htmls[str_fulldate] !== undefined&&(showOtherMonths || (drawDate.getMonth() + 1) == month) ?htmls[str_fulldate].qty :'')
					
					).replace(/\{style\}/g,(htmls[str_fulldate] !== undefined&&(showOtherMonths || (drawDate.getMonth() + 1) == month) ?('style="'+htmls[str_fulldate].style)+'"' :''))
					.replace(/\{class\}/g,(htmls[str_fulldate] !== undefined&&(showOtherMonths || (drawDate.getMonth() + 1) == month) ?htmls[str_fulldate].a_class+(drawDate.getTime() >= today.getTime()?(htmls[str_fulldate].enable==1?' enable' :' disable'):'') :''))
					.replace(/\{class_default\}/g,
							ts + ' ' + (dateInfo.dateClass || '') +
							(selected && (selectOtherMonths || drawDate.getMonth() + 1 == month) ?
							' ' + renderer.selectedClass : '') +
							(selectable ? ' ' + renderer.defaultClass : '') +
							((drawDate.getDay() || 7) < 6 ? '' : ' ' + renderer.weekendClass) +
							(drawDate.getMonth() + 1 == month ? '' : ' ' + renderer.otherMonthClass) +
							(drawDate.getTime() == today.getTime() && (drawDate.getMonth() + 1) == month ?
							' ' + renderer.todayClass : '') +
							(drawDate.getTime() == inst.drawDate.getTime() && (drawDate.getMonth() + 1) == month ?
							' ' + renderer.highlightedClass : '')
					)
					.replace(/\{atrrib\}/g,(htmls[str_fulldate] !== undefined&&(showOtherMonths || (drawDate.getMonth() + 1) == month) ?htmls[str_fulldate].atrrib :''));
				$.datetimebookingpick.add(drawDate, 1, 'd');
				ts = drawDate.getTime();
			}
			weeks += this._prepare(renderer.week, inst).replace(/\{days\}/g, days).
				replace(/\{weekOfYear\}/g, weekOfYear);
		}
		var monthHeader = this._prepare(renderer.month, inst).match(/\{monthHeader(:[^\}]+)?\}/);
		monthHeader = (monthHeader[0].length <= 13 ? 'MM yyyy' :
			monthHeader[0].substring(13, monthHeader[0].length - 1));
		monthHeader = (first ? this._generateMonthSelection(
			inst, year, month, minDate, maxDate, monthHeader, renderer) :
			$.datetimebookingpick.formatDate(monthHeader, $.datetimebookingpick.newDate(year, month, 1), inst.getConfig()));
		var weekHeader = this._prepare(renderer.weekHeader, inst).
			replace(/\{days\}/g, this._generateDayHeaders(inst, renderer));
		return this._prepare(renderer.month, inst).replace(/\{monthHeader(:[^\}]+)?\}/g, monthHeader).
			replace(/\{weekHeader\}/g, weekHeader).replace(/\{weeks\}/g, weeks);
	},

	/* Generate the HTML for the day headers.
	   @param  inst      (object) the current instance settings
	   @param  renderer  (object) the rendering templates
	   @return  (string) a week's worth of day headers */
	_generateDayHeaders: function(inst, renderer) {
		var firstDay = inst.get('firstDay');
		var dayNames = inst.get('dayNames');
		var dayNamesMin = inst.get('dayNamesMin');
		var header = '';
		for (var day = 0; day < 7; day++) {
			var dow = (day + firstDay) % 7;
			header += this._prepare(renderer.dayHeader, inst).replace(/\{day\}/g,
				'<span class="' + this._curDoWClass + dow + '" title="' +
				dayNames[dow] + '">' + dayNamesMin[dow] + '</span>');
		}
		return header;
	},

	/* Generate selection controls for month.
	   @param  inst         (object) the current instance settings
	   @param  year         (number) the year to generate
	   @param  month        (number) the month to generate
	   @param  minDate      (CDate) the minimum date allowed
	   @param  maxDate      (CDate) the maximum date allowed
	   @param  monthHeader  (string) the month/year format
	   @return  (string) the month selection content */
	_generateMonthSelection: function(inst, year, month, minDate, maxDate, monthHeader) {
		if (!inst.get('changeMonth')) {
			return $.datetimebookingpick.formatDate(
				monthHeader, $.datetimebookingpick.newDate(year, month, 1), inst.getConfig());
		}
		// Months
		var monthNames = inst.get('monthNames' + (monthHeader.match(/mm/i) ? '' : 'Short'));
		var html = monthHeader.replace(/m+/i, '\\x2E').replace(/y+/i, '\\x2F');
		var selector = '<select class="' + this._monthYearClass +
			'" title="' + inst.get('monthStatus') + '">';
		for (var m = 1; m <= 12; m++) {
			if ((!minDate || $.datetimebookingpick.newDate(year, m, $.datetimebookingpick.daysInMonth(year, m)).
					getTime() >= minDate.getTime()) &&
					(!maxDate || $.datetimebookingpick.newDate(year, m, 1).getTime() <= maxDate.getTime())) {
				selector += '<option value="' + m + '/' + year + '"' +
					(month == m ? ' selected="selected"' : '') + '>' +
					monthNames[m - 1] + '</option>';
			}
		}
		selector += '</select>';
		html = html.replace(/\\x2E/, selector);
		// Years
		var yearRange = inst.get('yearRange');
		if (yearRange == 'any') {
			selector = '<select class="' + this._monthYearClass + ' ' + this._anyYearClass +
				'" title="' + inst.get('yearStatus') + '">' +
				'<option>' + year + '</option></select>' +
				'<input class="' + this._monthYearClass + ' ' + this._curMonthClass +
				month + '" value="' + year + '">';
		}
		else {
			yearRange = yearRange.split(':');
			var todayYear = $.datetimebookingpick.today().getFullYear();
			var start = (yearRange[0].match('c[+-].*') ? year + parseInt(yearRange[0].substring(1), 10) :
				((yearRange[0].match('[+-].*') ? todayYear : 0) + parseInt(yearRange[0], 10)));
			var end = (yearRange[1].match('c[+-].*') ? year + parseInt(yearRange[1].substring(1), 10) :
				((yearRange[1].match('[+-].*') ? todayYear : 0) + parseInt(yearRange[1], 10)));
			selector = '<select class="' + this._monthYearClass +
				'" title="' + inst.get('yearStatus') + '">';
			start = $.datetimebookingpick.add($.datetimebookingpick.newDate(start + 1, 1, 1), -1, 'd');
			end = $.datetimebookingpick.newDate(end, 1, 1);
			var addYear = function(y) {
				if (y != 0) {
					selector += '<option value="' + month + '/' + y + '"' +
						(year == y ? ' selected="selected"' : '') + '>' + y + '</option>';
				}
			};
			if (start.getTime() < end.getTime()) {
				start = (minDate && minDate.getTime() > start.getTime() ? minDate : start).getFullYear();
				end = (maxDate && maxDate.getTime() < end.getTime() ? maxDate : end).getFullYear();
				for (var y = start; y <= end; y++) {
					addYear(y);
				}
			}
			else {
				start = (maxDate && maxDate.getTime() < start.getTime() ? maxDate : start).getFullYear();
				end = (minDate && minDate.getTime() > end.getTime() ? minDate : end).getFullYear();
				for (var y = start; y >= end; y--) {
					addYear(y);
				}
			}
			selector += '</select>';
		}
		html = html.replace(/\\x2F/, selector);
		return html;
	},

	/* Prepare a render template for use.
	   Exclude popup/inline sections that are not applicable.
	   Localise text of the form: {l10n:name}.
	   @param  text  (string) the text to localise
	   @param  inst  (object) the current instance settings
	   @return  (string) the localised text */
	_prepare: function(text, inst) {
		var replaceSection = function(type, retain) {
			while (true) {
				var start = text.indexOf('{' + type + ':start}');
				if (start == -1) {
					return;
				}
				var end = text.substring(start).indexOf('{' + type + ':end}');
				if (end > -1) {
					text = text.substring(0, start) +
						(retain ? text.substr(start + type.length + 8, end - type.length - 8) : '') +
						text.substring(start + end + type.length + 6);
				}
			}
		};
		replaceSection('inline', inst.inline);
		replaceSection('popup', !inst.inline);
		var pattern = /\{l10n:([^\}]+)\}/;
		var matches = null;
		while (matches = pattern.exec(text)) {
			text = text.replace(matches[0], inst.get(matches[1]));
		}
		return text;
	}
});

/* jQuery extend now ignores nulls!
   @param  target  (object) the object to extend
   @param  props   (object) the new settings
   @return  (object) the updated object */
function extendRemove(target, props) {
	$.extend(target, props);
	for (var name in props)
		if (props[name] == null || props[name] == undefined)
			target[name] = props[name];
	return target;
};

/* Attach the datetimebookingpicker functionality to a jQuery selection.
   @param  command  (string) the command to run (optional, default 'attach')
   @param  options  (object) the new settings to use for these instances (optional)
   @return  (jQuery) for chaining further calls */
$.fn.datetimebookingpick = function(options) {
	var otherArgs = Array.prototype.slice.call(arguments, 1);
	if ($.inArray(options, ['getDate', 'isDisabled', 'isSelectable', 'options', 'retrieveDate']) > -1) {
		return $.datetimebookingpick[options].apply($.datetimebookingpick, [this[0]].concat(otherArgs));
	}
	return this.each(function() {
		if (typeof options == 'string') {
			$.datetimebookingpick[options].apply($.datetimebookingpick, [this].concat(otherArgs));
		}
		else {
			$.datetimebookingpick._attachPicker(this, options || {});
		}
	});
};

/* Initialise the datetimebookingpicker functionality. */
$.datetimebookingpick = new datetimebookingpicker(); // singleton instance

$(function() {
	$(document).mousedown($.datetimebookingpick._checkExternalClick).
		resize(function() { $.datetimebookingpick.hide($.datetimebookingpick.curInst); });
});

})(jQuery);
