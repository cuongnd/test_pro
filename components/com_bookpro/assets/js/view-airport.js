/**
 * Javascript for edit customer form.
 * 
 * @version $Id: view-airport.js 19 2012-06-26 12:58:05Z quannv $
 * @package ARTIO Booking
 * @subpackage assets
 * @copyright Copyright (C) 2010 ARTIO s.r.o.. All rights reserved.
 * @author ARTIO s.r.o., http://www.artio.net
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link http://www.artio.net Official website
 */

var ViewCustomer = {

	/**
	 * Submit page to display customers reservations list.
	 */
	displayReservations : function() {
		var form = ACommon.getForm();
		form.controller.value = 'reservation';
		form.task.value = '';
		form.submit();
	}
}