<?php

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

/** Import library dependencies */
jimport('joomla.event.plugin');
jimport('joomla.utilities.string');

class BookproPluginBase extends JPlugin
{
	/**
	 * @var $_element  string  Should always correspond with the plugin's filename,
	 *                         forcing it to be unique
	 */
	var $_element    = '';

	/**
	 * Checks to make sure that this plugin is the one being triggered by the extension
	 *
	 * @access public
	 * @return mixed Parameter value
	 * @since 2.5
	 */
	function _isMe( $row )
	{
		$element = $this->_element;

		$success = false;
		if (is_object($row) && !empty($row->element) && $row->element == $element )
		{
			$success = true;
		}

		if (is_string($row) && $row == $element ) {
			$success = true;
		}

		return $success;
	}

	/**
	 * Prepares variables for the form
	 *
	 * @return string   HTML to display
	 */
	function _renderForm($data)
	{
		$vars = new JObject();
		$html = $this->_getLayout('form', $vars);
		return $html;
	}

	/**
	 * Prepares the 'view' tmpl layout
	 *
	 * @param array
	 * @return string   HTML to display
	 */
	function _renderView( $options)
	{
		$vars = new JObject();
		$html = $this->_getLayout('view', $vars);
		return $html;
	}

	/**
	 * Wraps the given text in the HTML
	 *
	 * @param string $text
	 * @return string
	 * @access protected
	 */
	function _renderMessage($message = '')
	{
		$vars = new JObject();
		$vars->message = $message;
		$html = $this->_getLayout('message', $vars);
		return $html;
	}

	/**
	 * Gets the parsed layout file
	 *
	 * @param string $layout The name of  the layout file
	 * @param object $vars Variables to assign to
	 * @param string $plugin The name of the plugin
	 * @param string $group The plugin's group
	 * @return string
	 * @access protected
	 */
	function _getLayout($layout, $vars = false, $plugin = '', $group = 'bookpro' )
	{
		if (empty($plugin))
		{
			$plugin = $this->_element;
		}

		ob_start();
		$layout = $this->_getLayoutPath( $plugin, $group, $layout );
		include($layout);
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}


	/**
	 * Get the path to a layout file
	 *
	 * @param   string  $plugin The name of the plugin file
	 * @param   string  $group The plugin's group
	 * @param   string  $layout The name of the plugin layout file
	 * @return  string  The path to the plugin layout file
	 * @access protected
	 */
	function _getLayoutPath($plugin, $group, $layout = 'default')
	{
		$app = JFactory::getApplication();

		// get the template and default paths for the layout
		$templatePath = JPATH_SITE."templates/".$app->getTemplate()."/html/plugins/".$group."/".$plugin."/".$layout.'.php';
		
		$defaultPath = JPATH_SITE."/plugins/".$group."/".$plugin."/layouts/".$layout.'.php';
		// if the site template has a layout override, use it
		jimport('joomla.filesystem.file');
		if (JFile::exists( $templatePath ))
		{
			return $templatePath;
		}
		else
		{
			return $defaultPath;
		}
	}



	/**
	 * Checks for a form token in the request
	 * Using a suffix enables multi-step forms
	 *
	 * @param string $suffix
	 * @return boolean
	 */
	function _checkToken( $suffix='', $method='post' )
	{
		$token  = JUtility::getToken();
		$token .= ".".strtolower($suffix);
		if (JRequest::getVar( $token, '', $method, 'alnum' ))
		{
			return true;
		}
		return false;
	}

	/**
	 * Generates an HTML form token and affixes a suffix to the token
	 * enabling the form to be identified as a step in a process
	 *
	 * @param string $suffix
	 * @return string HTML
	 */
	function _getToken( $suffix='' )
	{
		$token  = JUtility::getToken();
		$token .= ".".strtolower($suffix);
		$html  = '<input type="hidden" name="'.$token.'" value="1" />';
		$html .= '<input type="hidden" name="tokenSuffix" value="'.$suffix.'" />';
		return $html;
	}

	/**
	 * Gets the suffix affixed to the form's token
	 * which helps identify which step this is
	 * in a multi-step process
	 *
	 * @return string
	 */
	function _getTokenSuffix( $method='post' )
	{
		$suffix = JRequest::getVar( 'tokenSuffix', '', $method );
		if (!$this->_checkToken($suffix, $method))
		{
			// what to do if there isn't this suffix's token in the request?
			// anything?
		}
		return $suffix;
	}


}
