/**
 * Javascript for edit customer form.
 * 
 * @version $Id: view-customer-submitbutton.js 19 2012-06-26 12:58:05Z quannv $
 * @package ARTIO Booking
 * @subpackage assets
 * @copyright Copyright (C) 2010 ARTIO s.r.o.. All rights reserved.
 * @author ARTIO s.r.o., http://www.artio.net
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link http://www.artio.net Official website
 */

/**
 * Valid form before submit. Standard in Joomla! administration.
 * 
 * @param pressbutton
 *            button selected in toolbar
 */

var ViewCustomerSubmit = {
	submitbutton : function(pressbutton) {
		var form = ACommon.getForm();
		switch (pressbutton) {
		case 'save':
		case 'apply':
			break;
		default:
			submitform(pressbutton);
			return;
		}
		if (trim(form.firstname.value) == '') {
			alert(LGErrAddCustomerFirstName);
			return;
		}
		
		
		if (trim(form.email.value) == '') {
			alert(LGErrAddCustomerEmail);
			return;
		}
		if (!isEmail(form.email.value)) {
			alert(LGErrAddValidCustomerEmail);
			return;
		}
		if (trim(form.telephone.value) == '') {
			alert(LGErrAddCustomerTelephone);
			return;
		}
		
		submitform(pressbutton);
	}
}

try {
	/**
	 * Joomla! 1.6.x
	 */
	Joomla.submitbutton = function(pressbutton) {
		return ViewCustomerSubmit.submitbutton(pressbutton);
	}
} catch (e) {
	/**
	 * Joomla! 1.5.x
	 */
	function submitbutton(pressbutton) {
		return ViewCustomerSubmit.submitbutton(pressbutton);
	}
}