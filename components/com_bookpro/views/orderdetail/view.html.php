<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.view' );
AImporter::model ( 'order' );
class BookproViewOrderDetail extends JViewLegacy {
	function display($tpl = null) {
		$this->config = AFactory::getConfig ();
		$this->_prepareDocument ();
		$layout = JRequest::getVar ( 'layout' );

		$tpl = JRequest::getVar ( 'tpl' );
		$this->setLayout ( $layout );
		$input = JFactory::getApplication ()->input;
		if ($layout == 'voucher' && $tpl == 'posttrip') {
			$modelorder = new BookProModelOrder ();
			$roomtypepassenger_id = $input->get ( 'roomtypepassenger_id' );
			$this->hotel = $modelorder->gethotelByPassengerId ( $roomtypepassenger_id );
			$this->bookinginfo = $modelorder->getBookingInfo ( $roomtypepassenger_id );
			$this->passenger = $modelorder->getPassenger ( $roomtypepassenger_id );

		}
		if ($layout == 'voucher' && $tpl == 'pretrip') {
			$modelorder = new BookProModelOrder ();
			$roomtypepassenger_id = $input->get ( 'roomtypepassenger_id' );
			$this->hotel = $modelorder->gethotelByPassengerId ( $roomtypepassenger_id );
			$this->bookinginfo = $modelorder->getBookingInfo ( $roomtypepassenger_id );
			$this->passenger = $modelorder->getPassenger ( $roomtypepassenger_id );


		}
		if ($layout == 'voucher' && $tpl == 'roomselected') {
			$modelorder = new BookProModelOrder ();
			$roomtypepassenger_id = $input->get ( 'roomtypepassenger_id' );
			$this->hotel = $modelorder->gethotelByPassengerId ( $roomtypepassenger_id );
			$this->bookinginfo = $modelorder->getBookingInfo ( $roomtypepassenger_id );
			$this->passenger = $modelorder->getPassenger ( $roomtypepassenger_id );


		}
		if ($layout == 'voucher' && $tpl == 'pretriptransfer') {
			$modelorder = new BookProModelOrder ();
			$airport_transfer_id = $input->get ( 'airport_transfer_id' );
			$this->airport_transfer = $modelorder->gettriptransferInfo ( $airport_transfer_id );
			$this->passenger = $modelorder->getPassengerByAirportStransferId ( $airport_transfer_id );


		}
		if ($layout == 'voucher' && $tpl == 'posttriptransfer') {
			$modelorder = new BookProModelOrder ();
			$airport_transfer_id = $input->get ( 'airport_transfer_id' );
			$this->airport_transfer = $modelorder->gettriptransferInfo ( $airport_transfer_id );
			$this->passenger = $modelorder->getPassengerByAirportStransferId ( $airport_transfer_id );


		}
		if ($layout == 'voucher' && $tpl == 'addition') {
			$modelorder = new BookProModelOrder ();
			$addonpassenger_id = $input->get ( 'addonpassenger_id' );
			$this->addition = $modelorder->getAdditionInfo ( $addonpassenger_id );
			$this->passenger = $modelorder->getPassengerByadditionId ( $addonpassenger_id );


		}
        $model=new BookProModelCustomer();
        $model->setId($this->order->user_id);
        $this->customer=$model->getObject();
		// $this->setLayout(JRequest::getVar('layout',''));
		parent::display ( $tpl );
	}
	protected function _prepareDocument() {
		$this->document = JFactory::getDocument ();
		$this->document->setTitle ( JText::_ ( 'Booking detail for order number:' . $this->order->id ) );
	}
}

?>
