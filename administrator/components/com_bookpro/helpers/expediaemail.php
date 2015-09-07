<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id$
 **/
defined('_JEXEC') or die('Restricted access');
AImporter::model('order','orders','customer','application');
AImporter::helper('currency','date');
require_once JPATH_ROOT . DS . 'components/com_bookpro/classes/Expedia/API.php';
class ExpediaEmailHelper {
	/**
	 *
	 * @param String $input
	 * @param CustomerTable $customer
	 */
	var $config;
	var $app;
	var $tempalte='default';
	function __construct()
	{
		$this->config=AFactory::getConfig();
	}
	public function setTemplate($value){
		$this->tempalte=$value;
	}
	public function sendMail($order_id) {
            
        
        
        $applicationModel = new BookProModelApplication();
        $customerModel = new BookProModelCustomer();

        $orderModel->setId($order_id);
        
        $order = $orderModel->getObject();
        $customerModel->setId($order->user_id);
        $customer = $customerModel->getObject();
        $this->app = $applicationModel->getObjectByCode($order->type);
        $config = AFactory::getConfig();
        require_once JPATH_COMPONENT . DS . 'classes/Expedia/API.php';
        $expedia = new API('55505', $config->api_key,$config->currency_code,$config->minor_rev);
        $params=array(
			'itineraryId'=>$order->itineraryid,
			'email'=>$customer->email
		);
	$itin=$expedia->itin($params);
        
        
        $body_customer = $this->app->email_customer_body;
        $body_customer = $this->fillCustomer($body_customer, $customer);
        $body_customer = $this->fillOrder($body_customer, $order);
        $this->app->email_customer_subject = $this->fillCustomer($this->app->email_customer_subject, $customer);

        BookProHelper::sendMail($this->app->email_send_from, $this->app->email_send_from_name, $customer->email, $this->app->email_customer_subject, $body_customer, true);

        $body_admin = $this->app->email_admin_body;
        $body_admin = $this->fillCustomer($body_admin, $customer);
        $body_admin = $this->fillOrder($body_admin, $order);
        BookProHelper::sendMail($this->app->email_send_from, $this->app->email_send_from_name, $this->app->email_admin, $this->app->email_admin_subject, $body_admin, true);
    }

    public function changeOrderStatus($order_id){

		$orderModel=new BookProModelOrder();
		$applicationModel=new BookProModelApplication();
		$customerModel= new BookProModelCustomer();

		$orderModel->setId($order_id);
		$order=$orderModel->getObject();
		$customerModel->setId($order->user_id);
		$customer=$customerModel->getObject();
		$this->app=$applicationModel->getObjectByCode($order->type);
		$msg='COM_BOOKPRO_ORDER_STATUS_'.$order->order_status.'_EMAIL_BODY';
		$body_customer=JText::_($msg);
		$body_customer=$this->fillCustomer($body_customer, $customer);
		$body_customer=$this->fillOrder($body_customer,$order);
		BookProHelper::sendMail($this->app->email_send_from, $this->app->email_send_from_name, $customer->email, JText::_('COM_BOOKPRO_ORDER_STATUS_CHANGE_EMAIL_SUB') , $body_customer,true);
	}
	public function registerNotify($custtomer_id){

		$customerModel= new BookProModelCustomer();
		$customerModel->setId($custtomer_id);
		$customer=$customerModel->getObject();
		$body_admin=$this->config->sendRegistrationsBodyAdmin;
		$body_customer=$this->config->sendRegistrationsBodyCustomer;
		$body_customer=$this->fillCustomer($body_customer, $customer);
		$body_admin=$this->fillCustomer($body_admin, $customer);
		if($this->config->sendRegistrationsEmails=1 || $this->config->sendRegistrationsEmails=3 )
			BookProHelper::sendMail($this->config->sendRegistrationsEmailsFrom,
					$this->config->sendRegistrationsEmailsFromname,
					$customer->email,
					$this->config->sendRegistrationsEmailsSubjectCustomer ,
					$body_customer,true);
		if($this->config->sendRegistrationsEmails=1 || $this->config->sendRegistrationsEmails=2 )
			BookProHelper::sendMail($config->sendRegistrationsEmailsFrom,
					$config->sendRegistrationsEmailsFromname,
					$config->sendRegistrationsEmailsFrom,
					$config->sendRegistrationsEmailsSubjectAdmin,
					$body_admin, $htmlMode);

	}

	/**
	 *
	 * @param html $input
	 * @param Customer $customer
	 * @return mixed
	 */
	public function fillCustomer($input, $customer){
		$input = str_replace('{email}', $customer->email, $input);
		$input = str_replace('{firstname}', $customer->firstname, $input);
		$input = str_replace('{lastname}', $customer->lastname, $input);
        $input = str_replace('{customer}', $customer->firstname.' '.$customer->lastname, $input);
		$input = str_replace('{address}', $customer->address, $input);
		$input = str_replace('{city}', $customer->city, $input);
		$input = str_replace('{gender}', BookProHelper::formatGender($customer->gender), $input);
		$input = str_replace('{telephone}', $customer->telephone, $input);
		$input = str_replace('{states}', $customer->states, $input);
		$input = str_replace('{zip}', $customer->zip?'N/A':$customer->zip, $input);
		$input = str_replace('{country}', $customer->country_name, $input);
		return $input;
	}

	public function fillOrder($input, $order){
		$input = str_replace('{order_number}', $order->order_number, $input);
		$input = str_replace('{total}', CurrencyHelper::formatprice($order->total), $input);
		$input = str_replace('{note}', $order->note, $input);
		$input = str_replace('{payment_status}', $order->pay_status, $input);
		$input = str_replace('{deposit}', $order->deposit, $input);
		$input = str_replace('{pay_method}', $order->pay_method, $input);
		$input = str_replace('{note}', $order->notes, $input);
		$input = str_replace('{created}', $order->created, $input);
		$input = str_replace('{order_time}', $order->created, $input);
		$input = str_replace('{order_status}', $order->order_status, $input);
		$order_link=JURI::root().'index.php?option=com_bookpro&controller=order&task=viewdetail&order_id='.$order->id;
		$input = str_replace('{order_link}', $order_link, $input);
		if($order->type=='TOUR'){
			$input=$this->fillTourInfo($input, $order);
		}
		if($order->type=='BUS'){
			$input=$this->fillBusTicket($input, $order);
		}
		if($order->type=='FLIGHT'){
			$input=$this->fillFlightTicket($input, $order);
		}
		if($order->type=='TRANSPORT'){
			$input=$this->fillTransportInfo($input, $order);
		}
		return $input;

	}

	function fillTransportInfo($input,$order){

		AImporter::model('transports','transport','orderinfos','airport');
		$infomodel=new BookProModelOrderinfos();
		$param=array('order_id'=>$order->id);
		$infomodel->init($param);
		$orderinfos=$infomodel->getData();

		$tmodel=new BookProModelTransport();
		for ($i = 0; $i < count($orderinfos); $i++) {
			$tmodel->setId($orderinfos[$i]->obj_id);
			$transport=$tmodel->getObject();

			$model=new BookProModelAirport();
			$model->setId($transport->from);
			$dest=$model->getObject();

			$orderinfos[$i]->from_type=$dest->air;
			$orderinfos[$i]->tfrom=$transport->tfrom;

			$model=new BookProModelAirport();
			$model->setId($transport->to);
			$dest=$model->getObject();

			$orderinfos[$i]->to_type=$dest->air;
			$orderinfos[$i]->tto=$transport->tto;
		}
		$infos="<table class='transport_trip'><thead>
				<tr>
				<th>".JText::_('COM_BOOKPRO_TRANSPORT_PICKUP_LOCATION')."</th><th>".JText::_('COM_BOOKPRO_TRANSPORT_DROP_LOCATION')."</th>
						<th>".JText::_('COM_BOOKPRO_BUSTRIP_PRICE')."</th><th>".JText::_('COM_BOOKPRO_TRAVELER')."</th></tr></thead><tbody>";

		foreach ($orderinfos as $trip) {
			$infos.='<tr><td nowrap="nowrap" valign="top">'.$trip->tfrom.'<br/>';
			if($trip->from_type){
				$infos.=JText::_('COM_BOOKPRO_TRANSPORT_FLIGHT_NUMBER').': '.$trip->purpose.'<br/>';
				$infos.=JText::_('COM_BOOKPRO_TRANSPORT_FLIGH_TIME').':'.JFactory::getDate($trip->start)->format('d-m-Y H:i');
			} else {
				$infos.= $trip->location;
			}
			$infos.="</td>";
			$infos.='<td nowrap="nowrap" valign="top">'.$trip->tto.'<br />';
			if($trip->to_type && !$trip->from_type){
				$infos.=JText::_('COM_BOOKPRO_TRANSPORT_FLIGHT_NUMBER').': '.$trip->purpose.'<br/>';
				$infos.=JText::_('COM_BOOKPRO_TRANSPORT_FLIGH_TIME').':'.JFactory::getDate($trip->start)->format('d-m-Y H:i');
			} else{
				$infos.= $trip->location;
			}
			$infos.='</td><td>'.CurrencyHelper::formatprice($trip->price).'</td>';
			$infos.='<td>'.$trip->adult.'</td></tr>';
		}
		$infos.="</tbody></table>";
		$input = str_replace('{transports}', $infos, $input);
		return $input;
	}

	public function fillTourInfo($input, $order){

		AImporter::helper('tour');
		$tour=TourHelper::getBookedTour($order->id);
		if (! class_exists('BookProModelOrderInfos')) {
			AImporter::model('orderinfos');
		}
		$omodel=new BookProModelOrderInfos();
		$lists=array('order_id'=>$order->id);
		$omodel->init($lists);
		$orderinfo=$omodel->getData();

		$input = str_replace('{tour_name}', $tour->title, $input);
		$input = str_replace('{traveller}', ($orderinfo[0]->adult+$orderinfo[0]->child) , $input);
		$input = str_replace('{start_time}', $tour->start_time, $input);
		$input = str_replace('{depart}',DateHelper::formatDate($orderinfo[0]->start), $input);
		$input = str_replace('{tour_package}',$tour->package, $input);
		$input = str_replace('{price}',$tour->pprice, $input);
		return $input;

	}
	function fillBusTicket($input, $order){
		if (! class_exists('BookProModelBustrip')) {
			AImporter::model('bustrip');
		}
		if (! class_exists('BookProModelOrderInfos')) {
			AImporter::model('orderinfos');
		}

		if (! class_exists('BookProModelPassengers')) {
			AImporter::model('passengers');
		}
		$passModel=new BookProModelPassengers();
		$plists=array('order_id'=>$order->id);
		$passModel->init($plists);
		$passengers=$passModel->getData();
		$omodel=new BookProModelOrderInfos();
		$lists=array('order_id'=>$order->id);
		$omodel->init($lists);
		$orderinfo=$omodel->getData();
		$order_detail="
				<style>
				<!--
				table.trip {
				width:100%;
	}
				table.trip tr th {
				border: 1px solid #ccc;
	}
				table.trip tr td {
				border: 1px solid #ccc;
	}
				-->
				</style>";

		$order_detail.='<table class="trip"  style="border:1px solid #ccc;"><thead><tr>';
		$order_detail.='<th nowrap>'.JText::_('COM_BOOKPRO_BUSTRIP_FROM').'</th>';
		$order_detail.='<th nowrap>'.JText::_('COM_BOOKPRO_BUSTRIP_TO').'</th>';
		$order_detail.='<th width="10%">'.JText::_('COM_BOOKPRO_ADULT').'</th>';
		$order_detail.='<th width="10%">'.JText::_('COM_BOOKPRO_CHILDREN').'</th>';
		$order_detail.='<th width="4%">'.JText::_('COM_BOOKPRO_BUSTRIP_DEPART').'</th>';
		$order_detail.='<th width="15%">'.JText::_('COM_BOOKPRO_BUSTRIP_BUS').'</th>';
		$order_detail.='</tr></thead>';


		if (count($orderinfo)>0){
			foreach ($orderinfo as $subject)
			{
				$fmodel=new BookProModelBusTrip();
				$bustrip=$fmodel->getObjectByID($subject->obj_id);
				$order_detail.='
						<tr>
						<td style="text-align: center;">'.$bustrip->from_name.'</td>
								<td style="text-align: center;">'.$bustrip->to_name.'</td>
										<td style="text-align: center;">'.$subject->adult.'</td>
												<td style="text-align: center;">'.$subject->child.'</td>
														<td style="text-align: center;">'.CurrencyHelper::formatprice($subject->price).'</td>
																<td style="text-align: center;">'.JFactory::getDate($subject->start)->format('d-m').' '.$bustrip->start.'</td>
																		<td>'.$bustrip->bus_name.'</td></tr>';
			}
		}
		$order_detail.='</table>';
		$input = str_replace('{tripdetail}', $order_detail, $input);

		if(count($passengers)>0){
			$pstr='';
			$pstr.='<table class="trip" style="border:1px solid #ccc;"><thead><tr>';
			$pstr.='<th>'.JText::_('COM_BOOKPRO_PASSENGER_FIRST_NAME').'</th>';
			$pstr.='<th>'.JText::_('COM_BOOKPRO_PASSENGER_LAST_NAME').'</th>';
			$pstr.='<th>'.JText::_('COM_BOOKPRO_PASSENGER_PASSPORT').'</th>';
			$pstr.='</tr></thead>';
			foreach ($passengers as $pass)
			{
				$pstr.='<tr>
						<td >'.$pass->firstname.'</td>
								<td >'.$pass->lastname.'</td>
										<td >'.$pass->passport.'</td>
												</tr>';
			}
			$pstr.='</table>';
			$input = str_replace('{passenger}', $pstr, $input);
		}
		return $input;

	}
	function fillFlightTicket($input, $order){
		if (! class_exists('BookProModelFlight')) {
			AImporter::model('flight');
		}
		if (! class_exists('BookProModelOrderInfos')) {
			AImporter::model('orderinfos');
		}

		if (! class_exists('BookProModelPassengers')) {
			AImporter::model('passengers');
		}
		$passModel=new BookProModelPassengers();
		$plists=array('order_id'=>$order->id);
		$passModel->init($plists);
		$passengers=$passModel->getData();
		$omodel=new BookProModelOrderInfos();
		$lists=array('order_id'=>$order->id);
		$omodel->init($lists);
		$orderinfo=$omodel->getData();
		$order_detail="
				<style>
				<!--
				table.trip {
				width:100%;
	}
				table.trip tr th {
				border: 1px solid #ccc;
	}
				table.trip tr td {
				border: 1px solid #ccc;
	}
				-->
				</style>";

		$order_detail.='<table class="trip"  style="border:1px solid #ccc;"><thead><tr>';
		$order_detail.='<th nowrap>'.JText::_('COM_BOOKPRO_FLIGHT_FROM').'</th>';
		$order_detail.='<th nowrap>'.JText::_('COM_BOOKPRO_FLIGHT_TO').'</th>';
		$order_detail.='<th width="10%">'.JText::_('COM_BOOKPRO_ADULT').'</th>';
		$order_detail.='<th width="10%">'.JText::_('COM_BOOKPRO_CHILDREN').'</th>';
		$order_detail.='<th width="4%">'.JText::_('COM_BOOKPRO_FLIGHT_DEPART').'</th>';
		$order_detail.='<th width="15%">'.JText::_('COM_BOOKPRO_FLIGHT_TITLE').'</th>';
		$order_detail.='</tr></thead>';


		if (count($orderinfo)>0){
			foreach ($orderinfo as $subject)
			{
				$fmodel=new BookProModelFlight();
				$flight=$fmodel->getFlightInfo($subject->obj_id);

				$order_detail.='
						<tr>
						<td style="text-align: center;">'.$flight->from_name.'</td>
								<td style="text-align: center;">'.$flight->to_name.'</td>
										<td style="text-align: center;">'.$subject->adult.'</td>
												<td style="text-align: center;">'.$subject->child.'</td>
														<td style="text-align: center;">'.CurrencyHelper::formatprice($subject->price).'</td>
																<td style="text-align: center;">'.JFactory::getDate($subject->start)->format('d-m').' '.$flight->start.'</td>
																		<td>'.$flight->title.'</td></tr>';
			}
		}
		$order_detail.='</table>';
		$input = str_replace('{flightdetail}', $order_detail, $input);

		if(count($passengers)>0){
			$pstr='';
			$pstr.='<table class="trip" style="border:1px solid #ccc;"><thead><tr>';
			$pstr.='<th>'.JText::_('COM_BOOKPRO_PASSENGER_FIRST_NAME').'</th>';
			$pstr.='<th>'.JText::_('COM_BOOKPRO_PASSENGER_LAST_NAME').'</th>';
			$pstr.='<th>'.JText::_('COM_BOOKPRO_PASSENGER_PASSPORT').'</th>';
			$pstr.='</tr></thead>';
			foreach ($passengers as $pass)
			{
				$pstr.='<tr>
						<td >'.$pass->firstname.'</td>
								<td >'.$pass->lastname.'</td>
										<td >'.$pass->passport.'</td>
												</tr>';
			}
			$pstr.='</table>';
			$input = str_replace('{passenger}', $pstr, $input);
		}
		return $input;

	}
	function fillOrderInfo($order){
		if (! class_exists('BookProModelFlight')) {
			AImporter::model('flight');
		}
		if (! class_exists('BookProModelOrderInfos')) {
			AImporter::model('orderinfos');
		}

		if (! class_exists('BookProModelPassengers')) {
			AImporter::model('passengers');
		}
		$passModel=new BookProModelPassengers();
		$plists=array('order_id'=>$order->id);
		$passModel->init($plists);
		$passengers=$passModel->getData();

		$omodel=new BookProModelOrderInfos();
		$lists=array('order_id'=>$order->id);
		$omodel->init($lists);
		$orderinfo=$omodel->getData();


		$order_detail='

				<table width="100%" style="border:1px solid #ccc;">
				<thead>
				<tr>
				<th width="20%">From</th>
				<th width="14%">To</th>
				<th width="10%">Adult</th>
				<th width="10%">Children</th>
				<th width="10%">Infant</th>
				<th width="4%">Price</th>
				<th width="15%">Depart</th>
				<th width="15%">Airline</th>
				</tr>
				</thead>';

		if (count($orderinfo)>0){

			foreach ($orderinfo as $subject)

			{
				$fmodel=new BookProModelFlight();
				$flightObj=$fmodel->getFlightInfo($subject->obj_id);
				$depart_date= new DateTime($subject->start);

				$order_detail.='
						<tr>
						<td style="text-align: center;">'.$flightObj->destfrom.'</td>
								<td style="text-align: center;">'.$flightObj->destto.'</td>
										<td style="text-align: center;">'.$subject->adult.'</td>
												<td style="text-align: center;">'.$subject->child.'</td>
														<td style="text-align: center;">'.$subject->enfant.'</td>
																<td style="text-align: center;">'.CurrencyHelper::formatprice($subject->price).'</td>
																		<td style="text-align: center;">'.date_format($depart_date,"F j, Y").'</td>
																				<td>'. $flightObj->airline_name.'</td></tr>';
			}

		}

		$order_detail.='</table>';

		$pstr='
				<label>Passenger information</label>
				<table id="passenger" style="border:1px solid #ccc;">
				<thead>
				<tr>
				<th >First name
				</th>
				<th >Last name
				</th>
				<th>Baggage
				</th>
				<th>Weight
				</th>
				</tr>
				</thead>';

		foreach ($passengers as $pass)
		{

			$pstr.='
					<tr>

					<td >'.$pass->firstname.'</td>
							<td >'.$pass->lastname.'</td>
									<td >'.$pass->passport.'</td>
											<td >'.$pass->weight.'</td>
													</tr>';

		}
		$pstr.='</table>';
		return $order_detail.$pstr;

	}
}
