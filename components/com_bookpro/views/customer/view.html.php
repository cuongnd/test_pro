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
    AImporter::model('customer',"countries",'airports','usergroups','usergroup' );
    //import needed JoomLIB helpers
    AImporter::helper('bookpro', 'request');
    //import needed assets
    AImporter::js('view-customer');


    class BookProViewCustomer extends JViewLegacy
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
            $model = new BookProModelCustomer();
            $model->getObjectByUserId();
            $model->setId(ARequest::getCid());
            $customer = &$model->getObject(); 
            if ($customer) {
                $customerUser = new JUser($customer->user);     
                if(!$customer->id){    
                    $customerUser = &JFactory::getUser();
                    $model = new BookProModelCustomer();
                    $customer = $model->getObjectByUserId($customerUser->id);
                    
                }			
                if ($this->getLayout() == 'form') {
                    $this->_displayForm($tpl,$customerUser, $customer);
                    return;
                }

                $document->setTitle(BookProHelper::formatName($customer));
                $params = JComponentHelper::getParams(OPTION);
                /* @var $params JParameter */


                $this->assignRef('customer', $customer);
                $this->assignRef('user', $customerUser);
                $this->assignRef('params', $params);
                parent::display($tpl);
                return;
            }
            JError::raise(E_ERROR, 500, 'Customer not found');
        }

        /**
        * Prepare to display page.
        * 
        * @param string $tpl name of used template
        * @param TableCustomer $customer
        * @param JUser $user
        */
        function _displayForm($tpl,$user, $customer)
        {
            $document = &JFactory::getDocument();
            /* @var $document JDocument */

            $error = JRequest::getInt('error');
            $data = JRequest::get('post');
            if ($error) {
                $customer->bind($data);
                $user->bind($data);

            }

            if (! $customer->id && ! $error) {
                $customer->init();
            }
            JFilterOutput::objectHTMLSafe($customer);
            JFilterOutput::objectHTMLSafe($user);
            $document->setTitle(BookProHelper::formatName($customer));
            $params = JComponentHelper::getParams(OPTION);
            /* @var $params JParameter */
            $countryselectbox = $this->getCountrySelectBox($customer->country_id);
            $this->assignRef("countries",$countryselectbox);
			$group_id = JUserHelper::getUserGroups($customer->user);
			
			$usergroupselectbox = $this->getUserGroupSelectBox($group_id);
			$this->assignRef("usergroups",$usergroupselectbox);
            
            $this->assignRef("cities",$this->getCitySelectBox($customer->city));
            $this->assignRef('customer', $customer);
            $this->assignRef('user', $user);
            $this->assignRef('params', $params);
            parent::display($tpl);
        }
       

        function getCitySelectBox($select)
        {
            $model = new BookProModelAirports();
            $fullList = $model->getData();
            return AHtmlFrontEnd::getFilterSelect('city', JText::_('COM_BOOKPRO_SELECT_CITY') , $fullList, $select, false, '', 'id', 'title');
        }

        function getGroupSelectBox($select)
        {
            $model = new BookProModelCGroups();
            $fullList = $model->getData();
            return AHtmlFrontEnd::getFilterSelect('cgroup_id', JText::_('Group') , $fullList, $select, false, '', 'id', 'title');
        }

        function getCompaniesSelectBox($select)
        {                                                      
            return '';
        }
         function getCountrySelectBox($select)
        {
            $model = new BookProModelCountries();
            $lists=array('order'=>id,'order_dir'=>'DESC');
            $model->init($lists);
            $fullList = $model->getData();
			//var_dump($fullList);
            return AHtmlFrontEnd::getFilterSelect('country_id', JText::_('COM_BOOKPRO_SELECT_COUNTRY'), $fullList, $select, false, '', 'id', 'country_name');
        }

        function getUserGroupSelectBox($select)
        {                                                      
            $model = new BookProModelUserGroups();  
			$lists=array('order'=>id,'order_dir'=>'DESC');
			 $model->init($lists);
            $fullList = $model->getData(); 
			
            return AHtmlFrontEnd::getFilterSelect('usergroups_id', JText::_('User Group') , $fullList, $select, false, '', 'id', 'title');
        }  


    }

?>