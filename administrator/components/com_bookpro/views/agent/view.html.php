<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 63 2012-07-29 10:43:08Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');

if (! class_exists('JUser')) {
	jimport('joomla.user.user');
}
//import needed models
AImporter::model('agent',"countries",'airports');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'request','image','document');
AImporter::js('view-images');
//import needed assets
//AImporter::js('view-agent', 'view-agent-submitbutton');


class BookProViewAgent extends BookproJViewLegacy
{

    /**
     * Prepare to display page.
     * 
     * @param string $tpl name of used template
     */
	
    function display($tpl = null)
    {
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        $model = new BookProModelAgent();
        $model->setId(ARequest::getCid());
        $agent = &$model->getObject();
        if ($agent) {
            $agentUser = new JUser($agent->user);     
			
            if ($this->getLayout() == 'form') {
                $this->_displayForm($tpl,$agentUser, $agent);
                return;
            }
            
            $document->setTitle(BookProHelper::formatName($agent));
            $params = JComponentHelper::getParams(OPTION);
            /* @var $params JParameter */
           
           
			$this->assignRef('agent', $agent);
			$this->assignRef('user', $agentUser);
            $this->assignRef('params', $params);
            parent::display($tpl);
            return;
        }
        JError::raise(E_ERROR, 500, 'Agent not found');
    }

    /**
     * Prepare to display page.
     * 
     * @param string $tpl name of used template
     * @param TableCustomer $agent
     * @param JUser $user
     */
    function _displayForm($tpl,$user, $agent)
    {
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        
        $error = JRequest::getInt('error');
        $data = JRequest::get('post');
        if ($error) {
            $agent->bind($data);
			 $user->bind($data);
            
        }
        
        if (! $agent->id && ! $error) {
            $agent->init();
        }
        JFilterOutput::objectHTMLSafe($agent);
		JFilterOutput::objectHTMLSafe($user);
        $document->setTitle(BookProHelper::formatName($agent));
        $params = JComponentHelper::getParams(OPTION);
        /* @var $params JParameter */
        $countryselectbox = $this->getCountrySelectBox($agent->country_id);
        $this->assignRef("countries",$countryselectbox);
        $this->assignRef("cities",$this->getCitySelectBox($agent->city));
        $this->assignRef('agent', $agent);
		$this->assignRef('user', $user);
        $this->assignRef('params', $params);
        parent::display($tpl);
    }
	function getCountrySelectBox($select)
    {
        $model = new BookProModelCountries();
        $lists=array('order'=>id,'order_dir'=>'DESC');
        $model->init($lists);
        $fullList = $model->getData();
        return AHtml::getFilterSelect('country_id', JText::_('COM_BOOKPRO_SELECT_COUNTRY'), $fullList, $select, false, '', 'id', 'country_name');
    }
    
    function getCitySelectBox($select)
    {
    	$model = new BookProModelAirports();
    	$fullList = $model->getData();
    	return AHtml::getFilterSelect('city', JText::_('COM_BOOKPRO_SELECT_CITY') , $fullList, $select, false, '', 'id', 'title');
    }
}

?>