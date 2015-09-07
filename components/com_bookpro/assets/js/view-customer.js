

var ViewCustomer = {

	/**
	 * Submit page to display customers reservations list.
	 */
	displayReservations : function() {
		var form = ACommon.getForm();
		form.controller.value = 'reservation';
		form.task.value = '';
		form.submit();
	},
	
	selectExistingUser : function() {
		$('user1').setStyle('display', '');
	
	},
	
	selectNewUser : function() {
		$('user1').setStyle('display', 'none');
		
	}
}