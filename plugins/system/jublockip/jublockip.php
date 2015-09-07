<?php
/**
 * ------------------------------------------------------------------------
 * JU BlockIP Plugin for Joomla 2.5, 3.x
 * ------------------------------------------------------------------------
 * Copyright (C) 2010-2013 JoomUltra. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: JoomUltra Co., Ltd
 * Websites: http://www.joomultra.com
 * ------------------------------------------------------------------------
 */

// No direct access.
defined('_JEXEC') or die();

/**
 * JUBlockIP Plugin
 *
 * @package		Joomla
 * @subpackage	System
 * @since 		2.5
 */

class plgSystemJUBlockIP extends JPlugin
{
	protected $_session;
	protected $_ipaddress;

	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
    function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
		
		$this->_session 	= JFactory::getSession();
		$this->_ipaddress 	= $this->get_ip_address();
    }
	
	// Function to get the client ip address
	function get_ip_address() {
		$ipaddress = '';
		
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';

		return $ipaddress;
	}
	
	/**
	* @since	1.6
	*/
    public function onAfterRender()
    {
		$app = JFactory::getApplication();
		
		$token = trim($this->params->get('token'));
		$user = JFactory::getUser();
		
		if($app->isAdmin() && $user->guest && $token != '')
		{
			//Try to get token via url get var
			$token_key = JRequest::getVar($token);
			
			//If pass the right token -> set token = 1
			if(isset($token_key)) {
				$this->_session->set('token', 1, 'jublockip');
			}
			
			//Get token_passed in session
			$token_passed = $this->_session->get('token', null, 'jublockip');
			
			//Redirect to home page if wrong token key
			if(!$token_passed) {
				$app->redirect(JURI::root());
			}
		}
		
		//Don't run in backend if filterip = frontend
		//Don't run in frontend if filterip = backend
        if (($this->params->get('filterip', 1) == 1 && $app->isAdmin()) || ($this->params->get('filterip', 1) == 2 && !$app->isAdmin())) {
            return false;
		}
		
		// enable plugin on the listed pages
		$enabledpage = false;
		$enablepaths = trim((string) $this->params->get('enablepaths'));
		if ($enablepaths) {
			$paths = array_map('trim', (array) explode("\n", $enablepaths));
			$current_uri_string = JURI::getInstance()->toString();
			
			foreach ($paths as $regex_pattern) {
				//preg_quote and remove ending slash of JURI::root()
				$root_path = preg_quote(preg_replace('#\/$#', '', JURI::root()), '/');
				$regex_pattern = "#".str_replace("[root]", $root_path, $regex_pattern)."#i";
				preg_match($regex_pattern, $current_uri_string, $matches);
				if (count($matches)) {
					$enabledpage = true;
					break;
				}
			}
		}
		
		// disable plugin in the listed pages, if the page is not in list of enabled pages
		$disablepaths = trim((string) $this->params->get('disablepaths'));
		$disabledpage = false;
		if (!$enabledpage && $disablepaths) {
			$paths = array_map('trim', (array) explode("\n", $disablepaths));
			$current_uri_string = JURI::getInstance()->toString();
			
			foreach ($paths as $regex_pattern) {
				//preg_quote and remove ending slash of JURI::root()
				$root_path = preg_quote(preg_replace('#\/$#', '', JURI::root()), '/');
				$regex_pattern = "#".str_replace("[root]", $root_path, $regex_pattern)."#i";
				preg_match($regex_pattern, $current_uri_string, $matches);
				if (count($matches)) {
					$disabledpage = true;
					break;
				}
			}
		}
		
		//Force run tab in enabled pages
		if(!$enabledpage && $disabledpage) {
			return false;
		}
		
		require_once(dirname(__FILE__) . '/ipblocklist.class.php');
		$whitelist_str = $this->params->get('whitelistip', '');
		$blacklist_str = $this->params->get('blacklistip', '');
		
		$checkip = new IpBlockList( $whitelist_str, $blacklist_str );
		
		$is_passed = $checkip->ipPass( $this->_ipaddress );
		
		if(!$is_passed) {
			//Redirect
			if($this->params->get('restricted_action', 1) == 1) {
				$current_url = JFactory::getURI()->toString();
				$redirect_to =  $this->params->get('redirect_to', 'http://google.com');
				$error_message = $this->params->get('error_message', 'Your IP has been blocked!');
				
				//Allow to redirect to a page in site, this checking makes redirect will not be infinite loop
				if( strpos($current_url, $redirect_to)===FALSE && strpos($current_url, JRoute::_($redirect_to))===FALSE ) {
					$app->redirect(JRoute::_($redirect_to), 'Your IP has been blocked!', 'error');
				}
			//Print restricted message
			} else {
				//JResponse::setBody('Your IP has been blocked!');
				echo $this->params->get('restricted_message', 'Your IP has been blocked!');
				exit();
			}
		}
	}
}
