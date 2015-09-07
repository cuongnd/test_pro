
/**
 * View subject detail page or page with edit form.
 * 
 * @version		$Id: view-upgrade.js 19 2012-06-26 12:58:05Z quannv $
 * @package		ARTIO Booking
 * @subpackage  assets 
 * @copyright	Copyright (C) 2010 ARTIO s.r.o.. All rights reserved.
 * @author 		ARTIO s.r.o., http://www.artio.net
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link        http://www.artio.net Official website
 */

var BookingUpdate = {
	submitbutton : function(pressbutton) {
		var form = document.adminForm;

		var sendOk = true;

		if (BookingNeedConfirm) {
			sendOk = confirm(BookingTxtConfirm);
		}
		if (sendOk) {
			form.fromserver.value = '1';
			form.submit();
		}
	}
}