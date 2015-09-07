<?php

    /**
    * @package 	Bookpro
    * @author 		Nguyen Dinh Cuong
    * @link 		http://ibookingonline.com
    * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
    * @version 	$Id: controller.php 16 2012-06-26 12:45:19Z quannv $
    **/

    defined('_JEXEC') or die('Restricted access');

    //import needed Joomla! libraries
    jimport('joomla.application.component.controller');
    //import needed JoomLIB helpers
    AImporter::helper('bookpro');

    class AController extends JControllerLegacy
    {
        /**
        * String name of controller usable in request data.
        * 
        * @var string
        */
        var $_controllerName;
        /**
        * Sign if after satisfied task do redirect on another page.
        * 
        * @var boolean
        */
        var $_doRedirect;

        function __construct($config = array())
        {
            parent::__construct($config);
            $this->_doRedirect = true;
        }

        function execute($task)
        {
            parent::execute($task);

        }

        /**
        * Add new object.
        */
        function add()
        {
            if (IS_SITE) {
                JRequest::setVar('view', 'reservation');
                JRequest::setVar('layout', 'form');
                parent::display();
            } elseif (IS_ADMIN) {
                $this->editing();
            }
        }

        /**
        * Edit existing object.
        */
        function edit()
        {
            $this->editing();
        }

        /**
        * Copy existing subject
        */
        function copy()
        {
            $this->editing();
        }

        /**
        * Open editing form page.
        * 
        * @param string $view name of view edit form
        */
        function editing($view)
        {
            JRequest::setVar('hidemainmenu', 1);
            JRequest::setVar('layout', 'form');
            JRequest::setVar('view', $view);
            $id = ARequest::getCid();
            $this->_model->setId($id);
            $this->_model->checkout();
            parent::display();
        }

        /**
        * Save object and state on edit page.
        */
        function apply()
        {
            $this->save(true);
        }

        /**
        * Save object.
        * 
        * @param boolean $apply true state on edit page, false return to browse list
        */
        function save($apply = false)
        {
            JRequest::checkToken() or jexit('Invalid Token');
            $post = JRequest::get('post');
            $post['id'] = ARequest::getCid();
            $mainframe = &JFactory::getApplication();
            $id = $this->_model->store($post);
            if ($id !== false) {
                $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
                if ($apply) {
                    ARequest::redirectEdit($this->_controllerName, $id);
                } else {
                    ARequest::redirectList($this->_controllerName);
                }
            }
        }

        /**
        * Cancel edit operation. Check in object and redirect to objects list. 
        */
        function cancel($msg)
        {
            $mainframe = &JFactory::getApplication();
            $id = ARequest::getCid();
            if ($id) {
                $this->_model->setId($id);
                $this->_model->checkin();
            }
            $mainframe->enqueueMessage(JText::_($msg));
            ARequest::redirectList($this->_controllerName);
        }

        /**
        * Set object state by choosen operation.
        * 
        * @param string $operation
        */
        function state($operation, $checkToken = true, $redirect = true)
        {
            if ($checkToken)
                JRequest::checkToken() or jexit('Invalid Token');
            $mainframe = &JFactory::getApplication();
            /* @var $mainframe JApplication */
            if (ARequest::controlCids(($cids = ARequest::getCids()), $operation)) {
                
                if (($success = $this->_model->$operation($cids)) && $this->_doRedirect)
                    $mainframe->enqueueMessage(JText::_('Successfully ' . $operation), 'message');
                elseif (! $success && $this->_doRedirect)
                    $mainframe->enqueueMessage(JText::_($operation . ' failed'), 'error');
            }
            if ($this->_doRedirect && $redirect)
                ARequest::redirectList($this->_controllerName);
        }

        /**
        * Remove trashed objects.
        */
        function emptyTrash()
        {
            JRequest::checkToken() or jexit('Invalid Token');
            $mainframe = &JFactory::getApplication();
            if ($this->_model->emptyTrash()) {
                $mainframe->enqueueMessage(JText::_('Successfully emptied trash'), 'message');
            } else {
                $mainframe->enqueueMessage(JText::_('Empty trash failed'), 'error');
            }
            ARequest::redirectList($this->_controllerName);
        }


        function setTextProperties(&$object, $text)
        {
            $pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
            $tagPos = preg_match($pattern, $text);
            if ($tagPos == 0) {
                $object->introtext = $text;
            } else {
                list ($object->introtext, $object->fulltext) = preg_split($pattern, $text, 2);
            }
        }

        function setEditorProperties(&$object)
        {
            if (JString::strlen($object->fulltext) > 1) {
                $object->text = $object->introtext . '<hr id="system-readmore" />' . $object->fulltext;
            } else {
                $object->text = $object->introtext;
            }
        }

        function setDirectByUserGroup($vName)
        {
            $mainframe = &JFactory::getApplication();
            $config = &AFactory::getConfig();
            $user = JFactory::getUser();
            $totalview = array();

            $views[$config->supplierUsergroup]  = array('supplierpage','registerhotel', 'registerhotels', 'room', 'rooms', 'roomrate', 'roomratedetail','scripthotel' );
            $views[$config->customersUsergroup] = array('mypage','booking');
            $views[$config->agentUsergroup] = array('agentpage','booking');
            
            $totalviews = array_merge($views[$config->supplierUsergroup], $views[$config->customersUsergroup],$views[$config->agentUsergroup]);        

            if((in_array($vName, $totalviews)) && $user->guest ){
                $return = 'index.php?option=com_bookpro&view='.$vName;
                $url = 'index.php?option=com_bookpro&view=login';
                $url .= '&return='.urlencode(base64_encode($return));
                $mainframe->redirect($url, JText::_('COM_BOOKPRO_LOGIN_REQUIRED'));
                return;
            }
            else if((in_array($vName, $totalviews)) && $user->id ){         

                if(!$this->_checkUserRight($vName, $views, $user))
                {
                    $mainframe->redirect(JUri::base() , JText::_('COM_BOOKPRO_PERMISSION_REQUIRED'));
                }         
            }
        }

        function _checkUserRight($vName,$views,$user){

            $config = &AFactory::getConfig();
            $checked = false;                                       

            if(in_array($vName, $views[$config->supplierUsergroup]) && in_array($config->supplierUsergroup, $user->groups)){
                $checked = true;
            }
            if(in_array($vName, $views[$config->customersUsergroup]) && in_array($config->customersUsergroup, $user->groups)){
                $checked = true;
            }
            return $checked;       
        }

        function checkGroupForUser()
        {                  
            $user = JFactory::getUser();
            $config = &AFactory::getConfig(); 
            if(in_array($config->supplierUsergroup, $user->groups))
            {
                return $config->supplierUsergroup; 
            }elseif(in_array($config->customersUsergroup, $user->groups))
            {
                return $config->customersUsergroup; 
            }else{
                return null;
            }
        }
        function getSelectBoxGroups($select=null) {   
            $config = AFactory::getConfig();
            $arrayGroup[]   =     JText::_('-Select Group-');
            $arrayGroup[$config->supplierUsergroup] =  JText::_('Supplier');
            $arrayGroup[$config->customersUsergroup] =  JText::_('Customer');
           return JHtmlSelect::genericlist($arrayGroup,'group_id');
                                                                             
          /*  $config = AFactory::getConfig();
            $db =& JFactory::getDBO();
            $sql  = "SELECT id, title  FROM #__usergroups ";
            $sql .= "WHERE id=".$config->supplierUsergroup." OR id=".$config->customersUsergroup." ORDER BY id ";
            $db->setQuery($sql);
            $list = $db->loadObjectList();
            return AHtmlFrontEnd::getFilterSelect('group_id', 'Select Group', $list, $select, null, '', 'id', 'title');   */
        }

    }

?>