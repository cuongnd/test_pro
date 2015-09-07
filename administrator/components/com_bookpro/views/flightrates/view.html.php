<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 26 2012-07-08 16:07:54Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');

//import needed JoomLIB helpers
AImporter::helper('route', 'bookpro', 'request');
AImporter::model('flight');



class BookProViewFlightRates extends BookproJViewLegacy
{
    /**
     * Array containing browse table filters properties.
     * 
     * @var array
     */
    var $lists;
    
    /**
     * Array containig browse table subjects items to display.
     *  
     * @var array
     */
    var $items;
    var $bustrip;
    
    function display($tpl = null)
    {
        $document = JFactory::getDocument();
        $document->addScript(JUri::base().'components/com_bookpro/assets/js/pncalendar.js');
        $flight_id=JFactory::getApplication()->input->get('flight_id');
        $model=new BookProModelFlight();
        
        $this->flight=$model->getObjectFullById($flight_id);
        
        parent::display($tpl);
        
       
    }                                                             
}

?>