<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: order.php 66 2012-07-31 23:46:01Z quannv $
 **/


defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'controller','report','date','currency');


class BookProControllerBusReport extends AController
{
    
    
    var $_model;

    function __construct($config = array())
    {
        parent::__construct($config);
        $this->_model = $this->getModel('busreport');
        $this->_controllerName = CONTROLLER_ORDER;
    }

    /**
     * Display default view - Airport list	
     */
    function display()
    {
        
    	switch ($this->getTask()) {
           	case 'publish':
           	case 'sendemail':
           		$this->sendemail();
           		break;
            case 'unpublish':
            case 'detail':
                JRequest::setVar('view', 'order');
              break;
            case 'trash':
            $this->state($this->getTask());
                break;
            default:
            JRequest::setVar('view', 'busreport');
            
        }
        
        parent::display();
    }
    function exportmanage(){
    	require_once JPATH_COMPONENT.DS."helpers".DS.'PHPExcel.php';
    	//include JPATH_COMPONENT.DS."helpers".DS.'PHPExcel'.DS.'Writer'.DS.'Excel2007.php';
    	$objPHPExcel = new PHPExcel();
    	 
    	$datefrom=JRequest::getVar('filter_from',null);
    	$dateto=JRequest::getVar('filter_to','');
    	 
    	if(!$datefrom){
    		$datefrom=JFactory::getDate(DateHelper::dateBeginMonth(time()))->toFormat();
    	}
    	if(!$dateto){
    		$dateto=JFactory::getDate(DateHelper::dateEndMonth(time()))->toFormat();
    	}
    	 
    	$items=ReportHelper::buildAdminReport($datefrom, $dateto);
		$itemsCount=count($items);
		
		$objPHPExcel->getProperties()->setCreator("Bookpro")
		->setLastModifiedBy("Bookpro")
		->setTitle("Report Manage")
		->setSubject("Tour Document");
		
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'Bk No.')
		->setCellValue('B1', 'REC Date')
		->setCellValue('C1', 'Depart date')
		->setCellValue('D1', 'Full Name')
		->setCellValue('E1', 'Phone')
		->setCellValue('F1', 'Tour Code')
		->setCellValue('G1', 'Pax 	')
		->setCellValue('H1', 'Total')
		->setCellValue('I1', 'Booked')
		->setCellValue('J1', 'Paid')
		->setCellValue('K1', 'Notes');
		
		$i = 2;
		
		foreach ($items as $item){
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i, $item->ordNo)
			->setCellValue('B'.$i, DateHelper::formatDate($item->receiveDate,'d-m-Y'))
			->setCellValue('C'.$i, DateHelper::formatDate($item->start,'d-m-Y'))
			->setCellValue('D'.$i, $item->fullname)
			->setCellValue('E'.$i, $item->telephone)
			->setCellValue('F'.$i, $item->tour_code)
			->setCellValue('G'.$i, ($item->adult  + $item->child))
			->setCellValue('H'.$i, CurrencyHelper::formatprice($item->total))
			->setCellValue('I'.$i, $item->order_status)
			->setCellValue('J'.$i, $item->pay_status)
			->setCellValue('K'.$i, $item->notes);
			$i++;
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="reportmanage'.time().'.xls"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
    }
    /*
     * Export
     */
    function exportdriver(){
    	
    	require_once JPATH_COMPONENT.DS."helpers".DS.'PHPExcel.php';
    	//include JPATH_COMPONENT.DS."helpers".DS.'PHPExcel'.DS.'Writer'.DS.'Excel2007.php';
    	$objPHPExcel = new PHPExcel();
    	
    	$datefrom=JRequest::getVar('filter_from',null);
    	$dateto=JRequest::getVar('filter_to','');
    	
    	if(!$datefrom){
    		$datefrom=JFactory::getDate(DateHelper::dateBeginMonth(time()))->toFormat();
    	}
    	if(!$dateto){
    		$dateto=JFactory::getDate(DateHelper::dateEndMonth(time()))->toFormat();
    	}
    	
    	$items=ReportHelper::buildDriverReport($datefrom, $dateto);
    	$itemsCount=count($items);
    	
    	$objPHPExcel->getProperties()->setCreator("Bookpro")
							 ->setLastModifiedBy("Bookpro")
							 ->setTitle("Driver Report")
							 ->setSubject("Tour Document");
							 


		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'Depart date')
		            ->setCellValue('B1', 'Depart time!')
		            ->setCellValue('C1', 'Full Name')
		            ->setCellValue('D1', 'M/F')
		            ->setCellValue('E1', 'Phone')
		            ->setCellValue('F1', 'Tour Code')
		            ->setCellValue('G1', 'Pax')
		            ->setCellValue('H1', 'Pickup point')
					->setCellValue('I1', 'Notes');
		$i = 2;
		
		foreach ($items as $item){
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i, DateHelper::formatDate($subject->start,'d-m-Y'))
			->setCellValue('B'.$i, DateHelper::formatDate($subject->depart_time,'H:i'))
			->setCellValue('C'.$i, $item->fullname)
			->setCellValue('D'.$i, $item->gender)
			->setCellValue('E'.$i, $item->telephone)
			->setCellValue('F'.$i, $item->tour_code)
			->setCellValue('G'.$i, $item->adult  + $item->child)
			->setCellValue('H'.$i, $item->pickup)
			->setCellValue('I'.$i, $item->notes);
			$i++;
		}
		
		
		// Rename worksheet
		
		
		
		
		
		// Redirect output to a client�s web browser (Excel2007)
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="reportdrive'.time().'.xls"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
    }

    /**
     * Open editing form page
     */
    function editing()
    {
        parent::editing('order');
    }

    /**
     * Cancel edit operation. Check in subject and redirect to subjects list. 
     */
    function cancel()
    {
        parent::cancel('Subject editing canceled');
    }
    
    /**
     * Save items ordering 
     */
    function saveorder()
    {
        JRequest::checkToken() or jexit('Invalid Token');
        
        $cids = ARequest::getCids();
        $order = ARequest::getIntArray('order');
        if (ARequest::controlCids($cids, 'save order')) {
            $mainframe = &JFactory::getApplication();
            if ($this->_model->saveorder($cids, $order)) {
                $mainframe->enqueueMessage(JText::_('Successfully saved order'), 'message');
            } else {
                $mainframe->enqueueMessage(JText::_('Order save failed'), 'error');
            }
        }
        ARequest::redirectList(CONTROLLER_ORDER);
    }

    /**
     * Move item up in ordered list
     */
    function orderup()
    {
        $this->setOrder(- 1);
    }

    /**
     * Move item down in ordered list
     */
    function orderdown()
    {
        $this->setOrder(1);
    }

    /**
     * Set item order
     * 
     * @param int $direct move direction
     */
    function setOrder($direct)
    {
        JRequest::checkToken() or jexit('Invalid Token');
        $cid = ARequest::getCid();
        $mainframe = &JFactory::getApplication();
        if ($this->_model->move($cid, $direct)) {
            $mainframe->enqueueMessage(JText::_('Successfully moved item'), 'message');
        } else {
            $mainframe->enqueueMessage(JText::_('Item move failed'), 'error');
        }
        ARequest::redirectList(CONTROLLER_ORDER);
    }

   
    /**
     * Save subject and state on edit page.
     */
    function apply()
    {
        $this->save(true);
    }
  

    /**
     * Save subject.
     * 
     * @param boolean $apply true state on edit page, false return to browse list
     */
    function sendemail(){
    	
    
    	
    	AImporter::model('customer','order','application');
    	$body_customer = JFile::read(JPATH_COMPONENT_BACK_END.DS.'templates'.DS.'emailconfirm.html');
    	$amount=JRequest::getVar('amount');
    	$order_id=JRequest::getVar('order_id');
    	$orderModel=new BookProModelOrder();
		$applicationModel=new BookProModelApplication();
		$customerModel= new BookProModelCustomer();
		$orderModel->setId($order_id);
		$order=$orderModel->getObject();
		$customerModel->setId($order->user_id);
		$customer=$customerModel->getObject();
		$app=$applicationModel->getObjectByCode($order->type);
		AImporter::helper('email');
		$body_customer= EmailHelper::fillCustomer($body_customer, $customer);
		$body_customer=EmailHelper::fillOrder($body_customer,$order);
		$payment_link=JURI::root().'index.php?option=com_bookpro&task=paymentredirect&controller=payment&order_id='.$order->id;
		$body_customer = str_replace('{payment_link}',$payment_link, $body_customer);
		$order->order_status="CONFIRMED";
		$order->store();
		BookProHelper::sendMail($this->app->email_send_from, $app->email_send_from_name, $customer->email, $app->email_customer_subject, $body_customer,true);
		$this->setRedirect(JURI::root().'/administrator/index.php?option=com_bookpro&view=orders');
		return;
		
    }
    function save($apply = false)
    {
        JRequest::checkToken() or jexit('Invalid Token');
        
        
        $mainframe = &JFactory::getApplication();
        
        $post = JRequest::get('post');
		
        
        $post['id'] = ARequest::getCid();
        
        $post['text'] = JRequest::getVar('text', '', 'post', 'string', JREQUEST_ALLOWRAW);
        
        $id = $this->_model->store($post);
        
        if ($id !== false) {
            $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
        } else {
            $mainframe->enqueueMessage(JText::_('Save failed'), 'error');
        }
        if ($apply) {
            ARequest::redirectEdit(CONTROLLER_ORDER, $id);
        } else {
            ARequest::redirectList(CONTROLLER_ORDER);
        }
    
    }


  }

?>