<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php  23-06-2012 23:33:14
 **/
defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');
jimport( 'joomla.html.html.select');
//import needed models
AImporter::model("payment");
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'request','paymenttype');
//import needed assets
if (IS_SITE) {
    AImporter::joomlaJS();
}
//import custom icons
AHtml::importIcons();

class BookProViewPayment extends BookproJViewLegacy
{
   	
    function display($tpl = null)
    {
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        $model = new BookProModelPayment();
        $model->setId(ARequest::getCid());
        $obj = &$model->getObject();
        $this->_displayForm($tpl, $obj);
        
               
	    }

    /**
     * Prepare to display page.
     * 
     * @param string $tpl name of used template
     * @param TableCustomer $customer
     * @param JUser $user
     */
    function _displayForm($tpl, $obj)
    {
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        
        $error = JRequest::getInt('error');
        $data = JRequest::get('post');
        if ($error) {
            $obj->bind($data);
            
        }
        
        if (! $obj->id && ! $error) {
            $obj->init();
        }
        JFilterOutput::objectHTMLSafe($obj);
        $document->setTitle($obj->title);
        $params = JComponentHelper::getParams(OPTION);
        /* @var $params JParameter */
              
        $this->assignRef('obj', $obj);
        $this->assignRef('params', $params);
        $this->assignRef('paymenttype', $this->getPaymentTypeSelect($obj->code));
        parent::display($tpl);
    }
    
    function getPaymentTypeSelect($select){
    	PaymentType::init();
    	return AHtml::getFilterSelect('code', JText::_('COM_BOOKPRO_PAYMENT_TYPE'), PaymentType::$map, $select, false, '', 'value', 'value');
    }
    
}

?>