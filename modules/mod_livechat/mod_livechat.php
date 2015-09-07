<?php

//    This file is part of LiveChat.
//
//    LiveChat is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    LiveChat is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with LiveChat.  If not, see <http://www.gnu.org/licenses/>.

/**
* @version		2.0.0
* @package		LiveChat
* @copyright	Copyright (C) 2010 LIVECHAT Software. All rights reserved.
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

//don't allow other scripts to grab and execute our file
defined('_JEXEC') or die('Direct access to this location is not allowed.');

class Livechat
{
	/**
	 * Singleton pattern
	 */
	var $_instance = NULL;

	/**
	 * Module directory
	 */
	var $_module_dir = NULL;

	/**
	 * Livechat options initializing
	 */
	var $_options = array(
		'license' => NULL,
		'skill' => NULL,
		'button_code' => 1
	);

	/**
	 * Singleton pattern
	 */
	function get_instance()
	{
		if (is_null(self::$_instance))
		{
			self::$_instance = new Livechat();
		}

		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	function Livechat($module, $params)
	{
		$this->_module_dir = JPATH_BASE.'/modules/'.$module->module;

		$this->set_option('license', $params->get('license_number'));
		$this->set_option('skill', $params->get('skill'));
		$this->set_option('button_code', $params->get('button_code'));

	}

	/**
	 * Sets value for given option key
	 */
	function set_option($key, $value)
	{
		$this->_options[$key] = $value;
	}

	/**
	 * Returns value for given option key
	 */
	function get_option($key)
	{
		if (!isset($this->_options[$key])) return FALSE;

		return $this->_options[$key];
	}

	/**
	 * License number validation
	 */
	function validate_license($license)
	{
		return preg_match('/^[0-9]{1,20}$/', $license);
	}

	/**
	 * Skill validation
	 */
	function validate_skill($skill)
	{
		return preg_match('/^[0-9]{1,10}$/', $skill);
	}
	
	/**
	 * Checks if Livechat settings are properly set up
	 */
	function is_installed()
	{
		if ($this->validate_license($this->get_option('license')) == FALSE) return FALSE;
		if ($this->validate_skill($this->get_option('skill')) == FALSE) return FALSE;

		return TRUE;
	}

	/**
	 * Checks if LiveChat monitoring code is installed properly
	 */
	function monitoring_code_installed()
	{
		if ($this->is_installed() == FALSE) return FALSE;

		return TRUE;
	}

	/**
	 * Checks if LiveChat button code is installed properly
	 */
	function chat_button_installed()
	{
		if ($this->is_installed() == FALSE) return FALSE;

		var_dump(JModuleHelper::isEnabled('mod_livechat'));exit;

		$mod =& JModuleHelper::getDocument();
		$doc->addScriptDeclaration($monitoring_code);

		// Check `status` value for `chat-button` block
		/*
		$r = db_query('SELECT status FROM {blocks} WHERE module="livechat" AND delta="chat-button"');
		$row = db_fetch_array($r);
		if (!isset($row['status']) || $row['status'] != '1') return FALSE;
		*/

		return TRUE;
	}

	/**
	 * Adds monitoring code to header
	 */
	function addMonitoringCode()
	{
		if ($this->is_installed() == FALSE) return FALSE;

		$path = $this->_module_dir . '/files/codes/monitoring_code.php';
		if (!file_exists($path)) return;

		$monitoring_code = file_get_contents($path);

		$monitoring_code = str_replace(
			array('{%LICENSE%}', '{%SKILL%}'),
			array($this->get_option('license'), $this->get_option('skill')),
		$monitoring_code);

		// Install monitoring code just before </head>
		$doc =& JFactory::getDocument();
		$doc->addScriptDeclaration($monitoring_code);
	}

	/**
	 * Returns LiveChat button HTML code
	 */
	function getChatButtonCode()
	{
		if ($this->is_installed() == FALSE) return FALSE;

		if($this->get_option('button_code')==1)
		{
		
		$path = $this->_module_dir . '/files/codes/chat_button.php';
		if (!file_exists($path)) return;

		$chat_button = file_get_contents($path);

		$chat_button = str_replace(
			array('{%LICENSE%}', '{%SKILL%}'),
			array($this->get_option('license'), $this->get_option('skill')),
		$chat_button);
		}
		else
		{
			$chat_button = '';
		}

		// Return chat button code
		return $chat_button;
	}
}

$Livechat = new Livechat($module, $params);
$Livechat->addMonitoringCode();
echo $Livechat->getChatButtonCode();