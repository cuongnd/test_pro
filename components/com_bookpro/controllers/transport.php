<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
AImporter::helper('bookpro', 'controller');
AImporter::model('customer');

class BookProControllerTransport extends JControllerLegacy{

	public function display(){

	}

	function book(){
		JSession::checkToken() or jexit('Invalid Token');
		$config=AFactory::getConfig();
		$cart = JModelLegacy::getInstance('TransportCart', 'bookpro');
		$cart->load();
		$cart->order_id=null;
		
		$input=JFactory::getApplication()->input;
		
		$roundtrip=$input->getInt('roundtrip');
		$pax=$input->getInt('adult');
		$rpax=$input->getInt('radult');
		$total=0;
		$orderinfos=array();
		
		
		if($roundtrip==1 || $roundtrip==3){
			$from=$input->getInt('from');
			$to=$input->getInt('to');
			$start=$input->getString('start');
			$depart_time=$input->getString('depart_time');
			$depart=$this->getTransportPrice($from, $to, $pax);
			
			//$depart->flightNumber=$input->getString('flight_number');
			//$depart->start=$start;
			//$depart->pax=$pax;
			//$depart->depart_time=$depart_time;
			//$depart->gatetype=$input->getBool('gatetype');
			//$depart->private=$input->getString('private_address');
			
			$datetime=JFactory::getDate(trim($start).' '.$depart_time)->toSql();
			$package=$input->getBool('type');
			$price=$package?$depart->private_price:$depart->price;
				
			$orderinfos[]=array('obj_id'=>$depart->id,
					'adult'=>$pax,
					'start'=>$datetime,
					'price'=>$price,
					'location'=>$input->getString('private_address'),
					'package'=>$package,
					'purpose'=>$input->getString('flight_number'),
					'from_title'=>$depart->tfrom,
					'to_title'=>$depart->tto,
					'priority'=>1
					);
			
			$total+=$price*$pax;
		}

		if($roundtrip==2 || $roundtrip==3){
			$rfrom=$input->getInt('rfrom');
			$rto=$input->getInt('rto');
			$start=$input->getString('rstart');
			$depart_time=$input->getString('rdepart_time');
			$return=$this->getTransportPrice($rfrom, $rto, $pax);
			$datetime=JFactory::getDate(trim($start).' '.$depart_time)->toSql();
			$rpackage=$input->getBool('rtype');
			$price=$rpackage?$return->private_price:$return->price;
			
			$orderinfos[]=array('obj_id'=>$return->id,
					'price'=>$price,
					'adult'=>$rpax,
					'start'=>$datetime,
					'package'=>$rpackage,
					'location'=>$input->getString('rprivate_address'),
					'purpose'=>$input->getString('rflight_number'),
					'from_title'=>$return->tfrom,
					'to_title'=>$return->tto,
					'priority'=>2
					);
			
			$total+=$price*$rpax;
		}
		$cart->orderinfos=$orderinfos;
		$cart->total=$total;
		$cart->saveToSession();
		//display confirmation
		$user=JFactory::getUser();

		if($config->anonymous){
			$this->display_booking_form();
		}else {
			if($user->id==0){
				$return = base64_encode(JURI::base().'index.php?option=com_bookpro&controller=transport&task=display_booking_form');
				$login= JURI::base().'index.php?option=com_bookpro&view=login&return='.$return;
				$this->setRedirect($login);
				return;
			}else{
				$this->display_booking_form();
			}
		}
	}

	public function display_booking_form(){
		if (! class_exists('BookProModelCustomer')) {
			AImporter::model('customer');
		}
		
		$customerModel=new BookProModelCustomer();
		$customer = $customerModel->getObjectByUserId();
		$cart = JModelLegacy::getInstance('TransportCart', 'bookpro');
		$cart->load();
		$view=$this->getView('transportconfirm','html','BookProView');
		$view->assign('cart',$cart);
		$view->assign('trips',$cart->orderinfos);
		$view->assign('customer',$customer);
		$view->display();

	}
	private function getTransportPrice($from,$to,$pax){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('t.*,`dest1`.`title` as tfrom, `dest2`.`title` AS tto');
		$query->from('#__bookpro_transport AS t');
		$query->leftJoin('#__bookpro_dest AS `dest1` ON `t`.`from` = `dest1`.`id`');
		$query->leftJoin('#__bookpro_dest AS `dest2` ON `t`.`to` = `dest2`.`id`');
		$query->where('t.state=1 AND t.pax >= '.$pax.' AND t.from='.$from.' AND t.to='.$to );
		$query->order('t.pax ASC LIMIT 0,1');
		$sql = (string)$query;
		$db->setQuery($sql);
		return $db->loadObject();
	}
	function getRoutePrice(){
		AImporter::helper('currency');
		$input=JFactory::getApplication()->input;
		$from=$input->getInt('from');
		$to=$input->getInt('to');
		$pax=$input->getInt('pax');
		$private=$input->getBool('type');
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('t.*');
		$query->from('#__bookpro_transport AS t');
		$query->where('t.state=1 AND t.pax >= '.$pax.' AND t.from='.$from.' AND t.to='.$to );
		$query->order('t.pax ASC LIMIT 0,1');
		$sql = (string)$query;
		$db->setQuery($sql);
		$obj= $db->loadObject();
		if($obj){
			$price=$private?$obj->private_price:$obj->price;
			echo JText::sprintf('COM_BOKPRO_TRANSPORT_PRICE_TEXT',CurrencyHelper::formatprice($price));
			die;
		}else{
			echo 'N/A';
			die;
		}
		
	}

	public function confirm(){
		JSession::checkToken() or jexit(JText::_('JInvalid_Token'));
		$config=AFactory::getConfig();
		$app=JFactory::getApplication();
		$cart = &JModelLegacy::getInstance('TransportCart', 'bookpro');
		$cart->load();
		if (! class_exists('BookProModelPassenger')) {
			AImporter::model('passenger');
		}
		if (! class_exists('BookProModelOrderInfo')) {
			AImporter::model('orderinfo');
		}
		if (! class_exists('BookProModelOrder')) {
			AImporter::model('order');
		}
		$pModel = new BookProModelPassenger();
		$orderModel = new BookProModelOrder();
		$orderModelInfo= new BookProModelOrderInfo();

		$cmodel=new BookProModelCustomer();
		$post=JRequest::get('post');

		if($config->anonymous){
			JTable::addIncludePath(JPATH_COMPONENT_FRONT_END.'/tables');
			$customer = JTable::getInstance('customer', 'table');
			$customer->bind($post);
			$customer->id = null;
			$customer->store();
			$cid=$customer->id;
		}else {
			$post['id']=$post['customer_id'];
			$cid=$cmodel->store($post);
			if($err=$cmodel->getError()){
				$app->enqueueMessage($err,'error');
				$app->redirect(JURI::base());
				exit;
			}
		}

		//store order
		$order=array(
				'id'=>$cart->order_id,
				'type'=>'TRANSPORT',
				'user_id'=>$cid,
				'total'=>$cart->total,
				'pay_status'=>'PENDING',
				'order_status'=>OrderStatus::$NEW->getValue(),
				'notes'=>$cart->notes,
				'tax'=>$cart->tax,
				'service_fee'=>$cart->service_fee
					
		);
		$orderid = $orderModel->store($order);
		$cart->order_id=$orderid;
		$cart->saveToSession();
		if($err=$orderModel->getError()){
			$app->enqueueMessage($err,'error');
			$app->redirect(JURI::base());
			exit;
		}

		$orderinfos=$cart->orderinfos;
		for ($i = 0; $i < count($orderinfos); $i++) {
			$orderinfos[$i]['order_id']=$orderid;
			$orderinfos[$i]['type']='TRANSPORT';
			$orderModelInfo->store($orderinfos[$i]);
			if($err= $orderModelInfo->getError()){
				$app->enqueueMessage($err,'error');
				$app->redirect(JURI::base());
				exit;
			}
		}

		$this->setRedirect(JURI::base().'index.php?option=com_bookpro&view=formpayment&order_id='.$orderid.'&'.JSession::getFormToken().'=1');
		return;

	}
	function findDestination()
	{
		$input=JFactory::getApplication()->input;
		$from=$input->getInt('from');
		if($from){
			
			$db = JFactory::getDBO();
			$query =$db->getQuery(true);
			$query->select('f.to AS `key` ,`d2`.`title` AS `text`');
			$query->from('#__bookpro_transport AS f');
			$query->leftJoin('#__bookpro_dest AS d2 ON f.to =d2.id');
			$query->where(array('f.from='.$from,'f.state=1'));
			$query->group('f.to');
			$query->order('d2.ordering ASC');
			$sql = (string)$query;
			$db->setQuery($sql);
			$dests = $db->loadObjectList();
			$return = '<option value="">'.JText::_('COM_BOOKPRO_SELECT').'</option>';
			if(is_array($dests)) {
				foreach ($dests as $dest) {
					$return .="<option value='".$dest->key."'>".$dest->text."</option>";
				}
			}
			echo trim($return);
			die();
		}

	}

}