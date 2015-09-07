<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bus.php 66 2012-07-31 23:46:01Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'controller');


class BookProControllerBussearch extends AController
{
    
    
    var $_model;
    var $appObj=null;

    function __construct($config = array())
    {
        parent::__construct($config);
        $this->_model = $this->getModel('bus');
        $this->_controllerName = CONTROLLER_BUSSEARCH;
        
        if (! class_exists('BookProModelApplication')) {
        	AImporter::model('application');
        }
        $aModel=new BookProModelApplication();
        $this->appObj=$aModel->getObjectByCode('BUS');
    }
    
    
   
    /**
     * Display default view - Airport list	
     */
    function display()
    {
    	switch ($this->getTask()) {
           	case 'publish':
           		$this->state($this->getTask());
           		break;
            case 'unpublish':
              break;
            case 'trash':
            $this->state($this->getTask());
                break;
            default:
        }
        JRequest::setVar('view', 'buses');
        parent::display();
    }
    function listdestination()
    {
    	$from=JRequest::getVar('from',0);
    	$db = JFactory::getDBO();
    	$query =$db->getQuery(true);
    	$query->select('f.to AS `key` ,`d2`.`title` AS `title`,`d2`.`ordering` AS `t_order`');
    	$query->from('#__bookpro_bustrip AS f');
    	$query->leftJoin('#__bookpro_dest AS d2 ON f.to =d2.id');
    	$query->where(array('f.from='.$from,'f.state=1'));
    	$query->group('f.to');
    	$query->order('t_order');
    	$sql = (string)$query;
    	$db->setQuery($sql);
    	$dests = $db->loadObjectList();
    		
    	$return = "<?xml version=\"1.0\" encoding=\"utf8\" ?>";
    	$return .= "<options>";
    	$return .= "<option id='0'>".JText::_( 'TO' )."</option>";
    	if(is_array($dests)) {
    		foreach ($dests as $dest) {
    			$return .="<option id='".$dest->key."'>".JText::_($dest->title)."</option>";
    		}
    	}
    	$return .= "</options>";
    	echo $return;
    }
    
    /**
     * Find destination for ajax call
     */
function findDestination()
	{
		$from=JRequest::getVar('from',0);
		$db = JFactory::getDBO();
		$query =$db->getQuery(true);
		$query->select('d2.id AS `key` ,`d2`.`title` AS `title`,`d2`.`ordering` AS `t_order`');
		$query->from('#__bookpro_bustrip AS f');
		$query->leftJoin('#__bookpro_dest AS d2 ON f.to =d2.id');
		$query->where(array('f.from='.$from,'f.state=1'));
		$query->group('f.to');
		$query->order('t_order');
		$sql = (string)$query;
		
		$db->setQuery($sql);
		$dests = $db->loadObjectList();
			
		$return = '<option value="">'.JText::_('COM_BOOKPRO_BUSTRIP_TO').'</option>';
		if(is_array($dests)) {
			foreach ($dests as $dest) {
				$return .="<option value='".$dest->key."'>".$dest->title."</option>";
			}
		}
		echo trim($return);
		die();

	}
    function search(){
    	
    	
    	 
    	$view=$this->getView('bussearch','html','BookProView');
    	 
    	
    	$this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
    	$this->lists['roundtrip'] = ARequest::getUserStateFromRequest('roundtrip', 0, 'int');
    	$this->lists['bustrip-from'] = ARequest::getUserStateFromRequest('desfrom', null, 'int');
    	$this->lists['bustrip-to'] = ARequest::getUserStateFromRequest('desto', null, 'int');
    	
    	$this->lists['adult'] = ARequest::getUserStateFromRequest('adult', null, 'int');
    	$this->lists['start'] = ARequest::getUserStateFromRequest('start', null,'string');
    	$this->lists['end'] = ARequest::getUserStateFromRequest('end', null,'string');
    	
    	$view->assign('lists',$this->lists);
    	$view->display();
    	
    }
    function confirm(){
    	AImporter::helper('bus','orderstatus');
    	AImporter::model('bustrip','orders','orderinfo','passenger','customer','order');
    	$app = JFactory::getApplication();
    	$input = $app->input;
    	
    	$currency_id = $input->get('currency_id',null,'int');
    	$this->lists['limitstart'] = $input->get('limitstart',0,'int');
    	$this->lists['roundtrip'] = $input->get('roundtrip',0,'int');
    	
    	$this->lists['bustrip-from'] = $input->get('desfrom',0,'int');
    	$this->lists['bustrip-to'] = $input->get('desto',0,'int');
    	$this->lists['adult'] = $input->get('adult',0,'int');
    	$this->lists['start'] = $input->get('start','','string');
    	$this->lists['end'] = $input->get('end','','string');
    	
    	$this->lists['notes'] = $input->get('notes','','string');
    	
    	$this->lists['bustrip_id'] = $input->get('bustrip_id',0,'int');
        $this->lists['listseat'] = $input->get('listseat_'.$this->lists['bustrip_id'],'','string');
       
        $pGroup = $input->get('pGroup',array(),'array');
        $pGender= $input->get('pGender',array(),'array');
        $pFirstname = $input->get('pFirstname',array(),'array');
        $pMiddlename = $input->get('pMiddlename',array(),'array');
        $pAge= $input->get('age',array());
        $pPassport = $input->get('pPassporst',array(),'array');
        $pCountry = $input->get('pCountry',array(),'array');
        $pSeat = $input->get('pSeat',array(),'array');
        $pReturnSeat = $input->get('pReturnSeat',array(),'array');
        $pBag = $input->get('pBag',array(),'array');
        $pBagReturn = $input->get('pBagReturn',array(),'array');
        $pModel = new BookProModelPassenger();
        $passengers = array();
        for($i=0; $i< count($pFirstname); $i++){
        	$passenger = array( 'gender'=>$pGender[$i],
        			'firstname'=>$pFirstname[$i],
        			'lastname'=>$pMiddlename[$i],
        			'passport'=>$pPassport[$i],
        			'group_id'=>$pGroup[$i],
        			'age'=>$pAge[$i],
        			'country_id'=>$pCountry[$i],
        			'seat'=>$pSeat[$i],
        			'bag_qty'=>$pBag[$i],
        			'return_bag_qty'=>$pBagReturn[$i]
        
        	);
        	if($this->lists['roundtrip'] == 1){
        		$passenger['return_seat'] = $pReturnSeat[$i];
        	}
        	$passengers[] = $passenger;
        	
        }
        
    	$tripModel=new BookProModelBusTrip();
    	
    	$cmodel=new BookProModelCustomer();
    	$cmodel->setIdByUserId();
    	$customer=$cmodel->getObject();
    	$bustrips=array();
    	

    	
       
    	if ($this->lists['bustrip_id']) {
    		$bustrip= BusHelper::getObjectFullById($this->lists['bustrip_id'], $this->lists['start'],$this->lists['roundtrip']);
    		
    		for ($i = 0;$i < count($passengers);$i++){
    			$passengers[$i]['route_id'] = $this->lists['bustrip_id'];
    			$passengers[$i]['start'] = $this->lists['start'];
    			$passengers[$i]['price'] = BusHelper::getPassengerPrice($bustrip->price, $passengers[$i]['group_id'],$this->lists['roundtrip']);
    			$agent = BusHelper::getAgentBustrip($bustrip->id);
    				
    			$passengers[$i]['price_bag'] = BusHelper::getPriceBag($passengers[$i]['bag_qty'], $agent->agent_id,0);
    		}
    		
          
    	}
    	
    	
    	$subtotal=BusHelper::getPrice($bustrip->price,$pGroup,$this->lists['roundtrip']);
    	
    	$total_bag = BusHelper::getPriceBags($passengers, $bustrip->id,0);
    	
    	if ($this->lists['roundtrip'] == 1){
    		$this->lists['return_bustrip_id'] = ARequest::getUserStateFromRequest('return_bustrip_id', null, 'int');
    		$this->lists['returnlistseat'] = ARequest::getUserStateFromRequest('listseat_'.$this->lists['return_bustrip_id'], null,'string');
    		
    		if ($this->lists['return_bustrip_id']) {
    			$return_bustrip=BusHelper::getObjectFullById($this->lists['return_bustrip_id'], $this->lists['end'],$this->lists['roundtrip']);
    			$total_bag += BusHelper::getPriceBags($passengers, $return_bustrip->id,1);
    			
    			$subtotal+=BusHelper::getPrice($return_bustrip->price,$pGroup,$this->lists['roundtrip']);
    			for ($i = 0;$i < count($passengers);$i++){
    				$passengers[$i]['return_route_id'] = $this->lists['return_bustrip_id'];
    				$passengers[$i]['return_start'] = $this->lists['end'];
    				$passengers[$i]['return_price'] = BusHelper::getPassengerPrice($return_bustrip->price, $passengers[$i]['group_id'],$this->lists['roundtrip']);
    				$agent = BusHelper::getAgentBustrip($return_bustrip->id);
    				$passengers[$i]['return_price_bag'] = BusHelper::getPriceBag($passengers[$i]['return_bag_qty'], $agent->agent_id,1);
    			}
    			
    			
  			
    		}
    		
    	}
    	$subtotal = $subtotal + $total_bag;
    	$service_fee=$this->appObj->service_fee*$subtotal/100;
    	$total=$subtotal+$this->appObj->service_fee*$subtotal/100;
    	
    	
    	$post=JRequest::getVar('customer',array());
    	$post['id']=$post['customer_id'];
    	$config=AFactory::getConfig();
    	$app=JFactory::getApplication();
    	
    	
    	$customer = JTable::getInstance('customer', 'table');
    		$customer->bind($post);
    		$customer->id = null;
    		$customer->store();
    		$cid=$customer->id;
    	
    	
    	
    	$orderModel = new BookProModelOrder();
    	$orderModelInfo= new BookProModelOrderInfo();
    	
    	$cmodel=new BookProModelCustomer();
    	//AImporter::helper('orderstatus');
    	OrderStatus::init();
    	$order=array(
    			'type'=>'BUS',
    			'user_id'=>$cid,
    			'total'=>$total,
    			'subtotal'=>$subtotal,
    			'pay_method'=>'',
    			'pay_status'=>'PENDING',
    			'order_status'=>OrderStatus::$NEW->getValue(),
    			'notes'=>$this->lists['notes'],
    			'tax'=>0,
    			'currency_id'=>$currency_id,
    			'service_fee'=>$service_fee
    	
    	);
    	
    	
    	$orderid = $orderModel->store($order);
    	
    	if($err=$orderModel->getError()){
    		$app->enqueueMessage($err,'error');
    		exit;
    	}
    	
    	for ($i = 0; $i < count($passengers); $i++) {
    		$passengers[$i]['order_id']=$orderid;
    		$passModel = new BookProModelPassenger();
    		$passModel->save($passengers[$i]);
    		
    		
    		if($err= $passModel->getError()){
    			$app->enqueueMessage($err,'error');
    			exit;
    		}
    	}
    	
    	/*Lưu lại passenger
    	 * dang sua den day
    	 * */
    	
    	
    	
    	
    	
    	
    	
    	$this->setRedirect('index.php?option=com_bookpro&controller=order&task=detail&cid[]='.$orderid);
    	
    }
  

  }

?>