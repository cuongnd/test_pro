/**
 * Javascript for list customers form
 * 
 * @version $Id: view-customers.js 19 2012-06-26 12:58:05Z quannv $
 * @package ARTIO Booking
 * @subpackage assets
 * @copyright Copyright (C) 2010 ARTIO s.r.o.. All rights reserved.
 * @author ARTIO s.r.o., http://www.artio.net
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link http://www.artio.net Official website
 */

var ListCustomers = {
	
	/**
	 * Select customer from element window.
	 * 
	 * @param id
	 * @param name
	 * @return false to disable page submit
	 */
	select : function(id, name) {
		window.parent.document.getElementById('customer_id').value = id;
		window.parent.document.getElementById('customer_name').value = name;
		window.parent.SqueezeBox.close();
		return false;
	},
	
	/**
	 * Fill customer form.
	 */
	fillCustomerCard : function(onParent, title_before, firstname, middlename, surname, title_after, company, street, city, country, zip, telephone, fax, email) {
		if (onParent) {
			window.parent.document.adminForm.title_before.value = title_before;
			window.parent.document.adminForm.firstname.value = firstname;
			window.parent.document.adminForm.middlename.value = middlename;
			window.parent.document.adminForm.surname.value = surname;
			window.parent.document.adminForm.title_after.value = title_after;
			window.parent.document.adminForm.company.value = company;
			window.parent.document.adminForm.street.value = street;
			window.parent.document.adminForm.city.value = city;
			window.parent.document.adminForm.country.value = country;
			window.parent.document.adminForm.zip.value = zip;
			window.parent.document.adminForm.telephone.value = telephone;
			window.parent.document.adminForm.fax.value = fax;
			window.parent.document.adminForm.email.value = email;
		} else {
			document.adminForm.title_before.value = title_before;
			document.adminForm.firstname.value = firstname;
			document.adminForm.middlename.value = middlename;
			document.adminForm.surname.value = surname;
			document.adminForm.title_after.value = title_after;
			document.adminForm.company.value = company;
			document.adminForm.street.value = street;
			document.adminForm.city.value = city;
			document.adminForm.country.value = country;
			document.adminForm.zip.value = zip;
			document.adminForm.telephone.value = telephone;
			document.adminForm.fax.value = fax;
			document.adminForm.email.value = email;
		}
	}
}