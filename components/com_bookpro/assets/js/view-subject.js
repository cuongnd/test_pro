/**
 * Javascript for edit subject form
 * 
 * @version $Id: view-subject.js 19 2012-06-26 12:58:05Z quannv $
 * @package ARTIO Booking
 * @subpackage assets
 * @copyright Copyright (C) 2010 ARTIO s.r.o.. All rights reserved.
 * @author ARTIO s.r.o., http://www.artio.net
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link http://www.artio.net Official website
 */

var EditSubject = {

	/**
	 * Reset subject hits counter
	 * 
	 * @param object
	 *            edit form element
	 * @return boolean false to disable form submit
	 */
	resetHits : function() {
		form = ACommon.getForm();
		if (confirm(LGAreYouSure)) {
			form.hits.value = 0;
			form.hits_disabled.value = 0;
		}
		return false;
	},

	/**
	 * Open dialog for save subject as new template
	 * 
	 * @param object
	 *            edit form element
	 * @return boolean false to disable form submit
	 */
	openSaveAsNewTemplate : function() {
		this.setSaveAsNewTemplate('open', 'saveAsNew');
	},

	/**
	 * Storno dialog for save subject as new template
	 * 
	 * @param object
	 *            edit form element
	 * @return boolean false to disable form submit
	 */
	closeSaveAsNewTemplate : function() {
		this.setSaveAsNewTemplate('close', '');
	},

	/**
	 * Set dialog for save subject as new template
	 * 
	 * @param object
	 *            edit form element
	 * @param string
	 *            way set type: 'open' .. open dialog, 'close' .. close dialog
	 * @return boolean false to disable form submit
	 */
	setSaveAsNewTemplate : function(way, value) {
		this.setTemplateTask(value);
		document.getElementById('saveAsNewTemplate').style.display = way == 'open' ? 'inline'
				: 'none';
		document.getElementById('templateName').style.display = way == 'open' ? 'none'
				: '';
		return false;
	},

	/**
	 * Return true if dialog for save subject as new template is open.
	 */
	isSetSaveAsNewTemplate : function() {
		return document.getElementById('saveAsNewTemplate').style.display == 'inline';
	},

	/**
	 * Open dialog for rename subject template
	 * 
	 * @param object
	 *            edit form element
	 * @return boolean false to disable form submit
	 */
	openRenameTemplate : function() {
		this.setRenameTemplate('open', 'rename');
	},

	/**
	 * Storno dialog for rename subject template
	 * 
	 * @param object
	 *            edit form element
	 * @return boolean false to disable form submit
	 */
	closeRenameTemplate : function() {
		this.setRenameTemplate('close', '');
	},

	/**
	 * Set dialog for save subject as new template
	 * 
	 * @param object
	 *            edit form element
	 * @param string
	 *            way set type: 'open' .. open dialog, 'close' .. close dialog
	 * @return boolean false to disable form submit
	 */
	setRenameTemplate : function(way, value) {
		this.setTemplateTask(value);
		document.getElementById('renameTemplate').style.display = way == 'open' ? 'inline'
				: 'none';
		document.getElementById('templateName').style.display = way == 'open' ? 'none'
				: '';
		return false;
	},

	/**
	 * Return true if dialog for rename subject is open.
	 */
	isSetRenameTemplate : function() {
		return document.getElementById('renameTemplate').style.display == 'inline';
	},

	/**
	 * Set text field content. Remove mask value.
	 * 
	 * @param object
	 *            edit form element
	 * @param string
	 *            mask text mask value
	 */
	setTemplateNameContent : function() {
		form = ACommon.getForm();
		if (form.new_template_name.value == TemplateNameMask) {
			form.new_template_name.value = '';
		}
	},

	/**
	 * Open dialog for change subject template
	 * 
	 * @param object
	 *            edit form element
	 * @return boolean false to disable form submit
	 */
	openChangeTemplate : function() {
		this.setChangeTemplate('open', 'changeTemplate');
	},

	/**
	 * Close dialog for change subject template
	 * 
	 * @param object
	 *            edit form element
	 * @return boolean false to disable form submit
	 */
	closeChangeTemplate : function() {
		this.setChangeTemplate('close', '');
	},

	/**
	 * Set dialog for change subject template
	 * 
	 * @param object
	 *            edit form element
	 * @param string
	 *            way set type: 'open' .. open dialog, 'close' .. close dialog
	 * @return boolean false to disable form submit
	 */
	setChangeTemplate : function(way, value) {
		this.setTemplateTask(value);
		document.getElementById('changeTemplate').style.display = way == 'open' ? 'inline'
				: 'none';
		document.getElementById('templateName').style.display = way == 'open' ? 'none'
				: '';
		return false;
	},

	/**
	 * Open dialog for delete subject template
	 * 
	 * @param object
	 *            edit form element
	 * @return boolean false to disable form submit
	 */
	openDeleteTemplate : function() {
		this.setDeleteTemplate('open', 'deleteTemplate');
	},

	/**
	 * Close dialog for delete subject template
	 * 
	 * @param object
	 *            edit form element
	 * @return boolean false to disable form submit
	 */
	closeDeleteTemplate : function() {
		this.setDeleteTemplate('close', '');
	},

	/**
	 * Set dialog for delete subject template
	 * 
	 * @param object
	 *            edit form element
	 * @param string
	 *            way set type: 'open' .. open dialog, 'close' .. close dialog
	 * @return boolean false to disable form submit
	 */
	setDeleteTemplate : function(way, value) {
		this.setTemplateTask(value);
		document.getElementById('deleteTemplate').style.display = way == 'open' ? 'inline'
				: 'none';
		document.getElementById('templateName').style.display = way == 'open' ? 'none'
				: '';
		return false;
	},

	/**
	 * Submit form for delete template.
	 * 
	 * @param object
	 *            edit form element
	 * @return boolean false to disable form submit
	 */
	deleteTemplate : function() {
		form = ACommon.getForm();
		if (confirm(LGAreYouSure)) {
			submitform('deleteTemplate');
		}
		return false;
	},

	/**
	 * Submit form for change template.
	 * 
	 * @param object
	 *            edit form element
	 * @return boolean false to disable form submit
	 */
	changeTemplate : function() {
		form = ACommon.getForm();
		if (form.template.value == '0') {
			alert(LGErrAddSubjectTemplate);
		} else if (confirm(LGAreYouSure)) {
			submitform('changeTemplate');
		}
		return false;
	},

	/**
	 * Set template task input hidden value
	 * 
	 * @param object
	 *            edit form element
	 * @param value
	 *            to set
	 */
	setTemplateTask : function(value) {
		form = ACommon.getForm();
		form.templateTask.value = value;
	},

	/**
	 * Add rezervation type row.
	 */
	addRtype : function() {
		/* copy default mask row */
		var clone = $('rtype').clone().inject('rtypes');
		/* set as visible */
		clone.style.display = '';
		/* remove element ID - no duplicity */
		clone.removeProperty('id');
		/* get all chidrens as elements array */
		var children = clone.getChildren();
		/* append and setup time pickers */
		var element = clone.getElement('select[name^=rtype-type]');
		element.className = 'notify';
		this.setReservationType(element);
		this.setEmptyRtype(false);
	},

	/**
	 * Set visibilaty of empty row of reservation types with information about
	 * creating reservation types.
	 */
	setEmptyRtype : function(display) {
		document.getElementById('rtype-empty').style.display = display ? ''
				: 'none';
	},

	/**
	 * Prepare saved reservation type displaying.
	 */
	prepareReservationTypes : function(allEnable) {
		var elements = $('rtypes').getElements('select[name^=rtype-type]');
		for ( var i = 1; i < elements.length; i++) {
			this.setReservationType(elements[i], allEnable);
		}
	},

	/**
	 * Remove rezervation types rows.
	 */
	removeRtypes : function() {
		var deletedAll = ACommon.removeRows('rtypes', 'rcid');
		if (deletedAll) {
			this.setEmptyRtype(true);
		}
	},

	setReservationType : function(element, allEnable) {
		var cell = $(element).getParent();
		var row = cell.getParent();

		var title = $(row).getElement('input[name^=rtype-title]');
		var description = $(row)
				.getElement('textarea[name^=rtype-description]');
		var timeUnit = $(row).getElement('input[name^=rtype-time_unit]');
		var gapTime = $(row).getElement('input[name^=rtype-gap_time]');

		var rtypeValue = allEnable ? '1' : element.value;

		var textDisabled = true;
		var timeDisabled = true;
		var className = 'notify';

		switch (rtypeValue) {
		case '1':
			/* hourly */
			textDisabled = false;
			timeDisabled = false;
			className = '';
			break;
		case '2':
			/* daily */
			textDisabled = false;
			className = '';
			break;
		}

		element.className = className;

		title.disabled = textDisabled;
		description.disabled = textDisabled;

		if (textDisabled) {
			title.value = '';
			description.value = '';
		}

		timeUnit.disabled = timeDisabled;
		gapTime.disabled = timeDisabled;

		if (timeDisabled) {
			timeUnit.value = '';
			gapTime.value = '';
		}
	},

	/**
	 * Add price row.
	 */
	addPrice : function() {
		/* copy default mask row */
		var clone = $('price').clone().inject('tprices');
		/* set as visible */
		clone.style.display = '';
		/* remove element ID - no duplicity */
		clone.removeProperty('id');
		/* get all chidrens as elements array */
		var children = clone.getChildren();
		/* append and setup calendars */
		ACommon.createCalendar(children[5], 'priceDateUp#', 'price-date_up[]');
		ACommon.createCalendar(children[6], 'priceDateDown#',
				'price-date_down[]');
		/* append and setup time pickers */
		ACommon.createTimePicker(children[7], 'price-time_up[]');
		ACommon.createTimePicker(children[8], 'price-time_down[]');
		/* prepare new row inptus */
		var element = clone.getElement('select[name^=price-rezervation_type]');
		element.className = 'notify';
		this.setPriceReservationType(element);
		this.setEmptyPrice(false);
	},

	/**
	 * Set visibilaty of empty row of prices with information about creating
	 * prices.
	 */
	setEmptyPrice : function(display) {
		document.getElementById('price-empty').style.display = display ? ''
				: 'none';
	},

	/**
	 * Prepare saved prices displaying.
	 */
	preparePrices : function(allEnable) {
		if (document.getElementById('tprices')) {
			var elements = $('tprices').getElements(
					'select[name^=price-rezervation_type]');
			for ( var i = 1; i < elements.length; i++) {
				this.setPriceReservationType(elements[i], allEnable);
			}
		}
	},

	/**
	 * Remove prices rows.
	 */
	removePrices : function() {
		var deletedAll = ACommon.removeRows('tprices', 'pcid');
		if (deletedAll) {
			this.setEmptyPrice(true);
		}
	},

	setPriceReservationType : function(element, allEnable) {
		var row = $(element).getParent().getParent();

		var value = row.getElement('input[name^=price-value]');
		var deposit = row.getElement('input[name^=price-deposit]');

		var rtype = document.getElementById('rtype-type' + element.value);

		var dateUp = row.getElement('input[name^=price-date_up]');
		var dateDown = row.getElement('input[name^=price-date_down]');

		var timeUp = row.getElement('input[name^=price-time_up]');
		var timeDown = row.getElement('input[name^=price-time_down]');

		var days = row.getElements('input[name^=fake]');

		var rtypeValue = rtype ? rtype.value : false;

		if (allEnable) {
			rtypeValue = '1';
		}

		var className = 'notify';

		var priceDisabled = true;
		var dateDisabled = true;
		var timeDisabled = true;

		switch (rtypeValue) {
		case '1':
			/* hourly */
			priceDisabled = false;
			dateDisabled = false;
			timeDisabled = false;
			className = '';
			break;
		case '2':
			/* daily */
			priceDisabled = false;
			dateDisabled = false;
			className = '';
			break;
		}

		element.className = className;

		value.disabled = priceDisabled;
		deposit.disabled = priceDisabled;

		if (priceDisabled) {
			value.value = '';
			deposit.value = '';
		}

		dateUp.disabled = dateDisabled;
		dateDown.disabled = dateDisabled;

		if (dateDisabled) {
			dateUp.value = '';
			dateDown.value = '';
		}

		timeUp.disabled = timeDisabled;
		timeDown.disabled = timeDisabled;

		if (timeDisabled) {
			timeUp.value = '';
			timeDown.value = '';
		}

		for ( var i = 0; i < days.length; i++) {
			if (!allEnable) {
				days[i].disabled = dateDisabled;
				if (dateDisabled) {
					days[i].checked = !dateDisabled;
				}
				ACommon.check(days[i]);
			}
		}
	},
	checkPriceRowDays : function(element) {
		var row = $(element).getParent().getParent();
		var fakes = row.getElements('input[name^=fake]');
		var check = this.fakesCheck(element);
		for ( var i = 0; i < fakes.length; i++) {
			ACommon.setCheck(fakes[i], check);
		}
		this.setCheck(element);
	},
	checkPriceColDays : function(element, day) {
		var fakes = this.getFakes();
		var check = this.fakesCheck(element);
		for ( var i = (6 + day); i < fakes.length; i += 7) {
			ACommon.setCheck(fakes[i], check);
		}
		this.setCheck(element);
	},
	checkPriceAllDays : function(element) {
		var fakes = this.getFakes();
		var check = this.fakesCheck(element);
		for ( var i = 7; i < fakes.length; i++) {
			ACommon.setCheck(fakes[i], check);
		}
		this.setCheck(element);
	},
	getFakes : function() {
		return $('tprices').getElements('input[name^=fake]');
	},
	fakesCheck : function(element) {
		return element.className == 'checkall';
	},
	setCheck : function(element) {
		element.className = ((element.className == 'checkall') ? 'uncheckall'
				: 'checkall');
	},
	setRLimit : function() {
		if (!$('rlimit_set').checked)
			$('rlimit_count').value = $('rlimit_days').value = '';
		$('rlimit_box').style.display = $('rlimit_set').checked ? 'block'
				: 'none';
	},
	
	/**
	 * Add supplement row.
	 */
	addSupplement : function() {
		/* copy default mask row */
		var clone = $('supplement').clone().inject('supplements');
		/* set as visible */
		clone.style.display = '';
		/* remove element ID - no duplicity */
		clone.removeProperty('id');
		/* get all chidrens as elements array */
		var children = clone.getChildren();
		/* prepare new row inptus */
		var element1 = clone.getElement('select[name^=supplement-description]');
		element1.className = 'notify';
		var element2 = clone.getElement('select[name^=supplement-options]');
		element2.className = 'notify';
	},

	/**
	 * Valid form before submit. Standard in Joomla! administration.
	 * 
	 * @param pressbutton
	 *            button selected in toolbar
	 */

	submitbutton : function(pressbutton) {
		switch (pressbutton) {
		case 'cancel':
		case 'copy':
			submitform(pressbutton);
			return;
		case 'apply':
			ACommon.saveBookmark();
			break;
		}
		if (!ACommon.validMultiParam('input', 'rtype-title',
				LGErrAddReservationTypesTitles, true)) {
			return false;
		}
		if (!ACommon.validMultiParam('select', 'rtype-type',
				LGErrSelectsDefaultTypesReservationTypes, true)) {
			return false;
		}
		if (!ACommon.validMultiParam('input', 'rtype-time_unit',
				LGErrAddTimeUnit, true)) {
			return false;
		}
		if (!ACommon.validMultiParam('input', 'price-value',
				LGErrAddPricesValues, true)) {
			return false;
		}
		if (!ACommon.validMultiParam('select', 'price-rezervation_type',
				LGErrSelectPricesReservationTypes, true)) {
			return false;
		}
		if (!ACommon.validMultiParam('input', 'price-date_up',
				LGErrAddPricesDates, true)
				|| !ACommon.validMultiParam('input', 'price-date_down',
						LGErrAddPricesDates, true)) {
			return false;
		}
		if (!ACommon.validMultiParam('input', 'price-time_up',
				LGErrAddPricesTimes, true)
				|| !ACommon.validMultiParam('input', 'price-time_down',
						LGErrAddPricesTimes, true)) {
			return false;
		}
		var form = ACommon.getForm();
		var cid = document.getElementById('cid').value;
		if (trim(form.title.value) == '') {
			alert(LGErrAddSubjectTitle);
		} else if (trim(form.total_capacity.value) != ''
				&& !AValidator.isInt(form.total_capacity.value)) {
			alert(LGErrTotalCapacityNoNumeric);
		} else if ((EditSubject.isSetSaveAsNewTemplate() && (trim(form.new_template_name.value) == '' || trim(form.new_template_name.value) == TemplateNameMask))
				|| ((EditSubject.isSetRenameTemplate() && trim(form.template_rename.value) == ''))) {
			alert(LGErrAddSubjectTemplate);
		} else if (!EditSubject.isSetSaveAsNewTemplate()
				&& form.template.value == '0') {
			alert(LGErrAddSubjectTemplate);
		} else if (cid != '0' && cid == form.parent.value) {
			alert(LGErrSelfAsParent);
		} else if ($('rlimit_set').checked
				&& ($('rlimit_count').value == '' || $('rlimit_days').value == '')) {
			alert(LGErrAddRLimit);
		} else {
			EditSubject.preparePrices(true);
			EditSubject.prepareReservationTypes(true);
			submitform(pressbutton);
		}
	}
};

try {
	/**
	 * Joomla! 1.6.x
	 */
	Joomla.submitbutton = function(pressbutton) {
		return EditSubject.submitbutton(pressbutton);
	}
} catch (e) {
	/**
	 * Joomla! 1.5.x
	 */
	function submitbutton(pressbutton) {
		return EditSubject.submitbutton(pressbutton);
	}
}

// startup events
window.addEvent('domready', function() {
	EditSubject.preparePrices();
	EditSubject.prepareReservationTypes();
});
