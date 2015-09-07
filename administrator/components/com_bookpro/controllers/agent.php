<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: agent.php 23 2012-07-08 02:20:56Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('bookpro', 'controller', 'parameter', 'request');

class BookProControllerAgent extends AController
{
    
    /**
     * Main model
     * 
     * @var BookProModelAgent
     */
    var $_model;

    function __construct($config = array())
    {
        parent::__construct($config);
        if (! class_exists('BookProModelAgent')) {
            AImporter::model('agent');
        }
        $this->_model = new BookProModelAgent();
        $this->_controllerName = CONTROLLER_AGENT;
    }

    /**
     * Display default view - agents list	
     */
    function display()
    {
        switch ($this->getTask()) {
            case 'trash':
            case 'restore':
                $this->state($this->getTask());
                break;
            case 'detail':
                JRequest::setVar('view', 'agent');
                break;
            default:
                JRequest::setVar('view', 'agents');
                break;
        }
        parent::display();
    }

    /**
     * Display browse agents page into element window.
     */
    function element()
    {
        $this->display();
    }

    /**
     * Open editing form page.
     */
    function editing()
    {
        parent::editing('agent');
    }

    /**
     * Cancel edit operation. Check in agent and redirect to agents list. 
     */
    function cancel()
    {
        parent::cancel('Agent editing canceled');
    }

    /**
     * Save agent.
     * 
     * @param boolean $apply true state on edit page, false return to browse list
     */
    function save($apply = false)
    {
        JRequest::checkToken() or jexit('Invalid Token');
        $mainframe = &JFactory::getApplication();
         $user = &JFactory::getUser();
		 
        $config = &AFactory::getConfig();
        $post = JRequest::get('post');
		
		if (IS_ADMIN) {
            $post['id'] = ARequest::getCid();
        } elseif (IS_SITE) {
            
            if ($user->id) {
                $this->_model->setIdByUserId();
                $post['id'] = $this->_model->getId();
            } else {
                $post['id'] = 0;
            }
        }
		
        $id = $this->_model->store($post);
        if ($id !== false) {
            $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
            
            if (IS_SITE) {
                
                if (! $user->id) {
                   
                    //new registration, logged after success
                    //$mainframe->login(array('password' => $post['password'] , 'username' => $post['username']), array('remember' => 1 , 'return' => ARoute::detail($this->_controllerName)));
                    
					/*
                    $htmlMode = BookProHelper::getEmailMode($config->sendRegistrationsMode);
       
                    if ($config->sendRegistrationsEmails == SEND_EMAIL_CUSTOMER || $config->sendRegistrationsEmails == SEND_EMAIL_BOTH) {
                        $body = $this->replaceEmailBody($config->sendRegistrationsBodyAgent, $user, $post, $this->_model->_table, $htmlMode);
                        BookProHelper::sendMail($config->sendRegistrationsEmailsFrom, $config->sendRegistrationsEmailsFromname, $user->email, $config->sendRegistrationsEmailsSubjectAgent, $body, $htmlMode);
                    }
                    if ($config->sendRegistrationsEmails == SEND_EMAIL_ADMIN || $config->sendRegistrationsEmails == SEND_EMAIL_BOTH) {
                        $body = $this->replaceEmailBody($config->sendRegistrationsBodyAdmin, $user, $post, $this->_model->_table, $htmlMode);
                        BookProHelper::sendMail($config->sendRegistrationsEmailsFrom, $config->sendRegistrationsEmailsFromname, $config->sendRegistrationsEmailsFrom, $config->sendRegistrationsEmailsSubjectAdmin, $body, $htmlMode);
                    }
					*/
                }
                 
                $customParams = array();
                $startSubjectId = JRequest::getInt('startSubjectId');
                
                if ($startSubjectId) {
                    $customParams['startSubjectId'] = $startSubjectId;
                }
                
                ARequest::redirectDetail($this->_controllerName, null, $customParams);
            
            } 
            elseif ($apply) {
            	
                ARequest::redirectEdit($this->_controllerName, $id);
            } else {
                ARequest::redirectList($this->_controllerName);
            }
        } else {
            JRequest::setVar('error', 1);
            foreach ($this->_model->_errors as $error) {
                $language = &JFactory::getLanguage();
                $language->load('com_users', JPATH_ADMINISTRATOR);
                $mainframe->enqueueMessage(JText::_($error), 'error');
            }
            
            $this->editing();
        }
    }

   
    function replaceEmailBody($body, &$user, &$post, &$agent, $htmlMode)
    {
        $body = str_replace('{REGISTRATION DATE}', AHtml::date($agent->create_date, ADATE_FORMAT_LONG), $body);
        //$body = str_replace('{USERNAME}', $user->username, $body);
        //$body = str_replace('{PASSWORD}', $post['password'], $body);
        $body = str_replace('{E-MAIL}', $agent->email, $body);
        $body = str_replace('{NAME}', BookProHelper::formatName($agent), $body);
        //$body = str_replace('{COMPANY}', $agent->company, $body);
        $body = str_replace('{ADDRESS}', BookProHelper::formatAdrress($agent), $body);
        $body = str_replace('{TELEPHONE}', $agent->telephone, $body);
        $body = str_replace('{FAX}', $agent->fax, $body);
        
        
		$body .= "\n\n" . BookProHelper::get();
		
        
        if (! $htmlMode)
            $body = BookProHelper::html2text($body);
        
        return $body;
    }
}

?>