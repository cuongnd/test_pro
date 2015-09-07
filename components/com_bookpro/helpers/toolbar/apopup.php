<?php

defined('JPATH_BASE') or die();

	
	
	class JToolbarButtonAPopup extends JToolbarButton
	{
		/**
		 * Button type
		 *
		 * @var    string
		 */
		protected $_name = 'APopup';

		/**
	 * Fetch the HTML for the button
	 *
	 * @param   string   $type     Unused string, formerly button type.
	 * @param   string   $name     Modal name, used to generate element ID
	 * @param   string   $text     The link text
	 * @param   string   $url      URL for popup
	 * @param   integer  $width    Width of popup
	 * @param   integer  $height   Height of popup
	 * @param   integer  $top      Top attribute.  [@deprecated  Unused, will be removed in 4.0]
	 * @param   integer  $left     Left attribute. [@deprecated  Unused, will be removed in 4.0]
	 * @param   string   $onClose  JavaScript for the onClose event.
	 * @param   string   $title    The title text
	 *
	 * @return  string  HTML string for the button
	 *
	 * @since   3.0
	 */
	public function fetchButton($type = 'Modal', $name = '', $text = '', $url = '', $width = 640, $height = 480, $top = 0, $left = 0,
		$onClose = '', $title = '')
	{
		// If no $title is set, use the $text element
		if (strlen($title) == 0)
		{
			$title = $text;
		}
		// Store all data to the options array for use with JLayout
		$options = array();
		$options['name'] = JText::_($name);
		$options['text'] = JText::_($text);
		$options['title'] = JText::_($title);
		$options['class'] = $this->fetchIconClass($name);
		$options['doTask'] = $this->_getCommand($url);
		
		$options['height'] = $height;
		$options['width']  = $width;
		$options['onclose']  = $onClose;
		
		JHtml::_('behavior.modal');
		$html = $this->layoutRender($options);

		/*
		// Place modal div and scripts in a new div
		$html[] = '</div><div class="btn-group" style="width: 0; margin: 0">';

		// Build the options array for the modal
		$params = array();
		$params['title']  = $options['title'];
		$params['url']    = $options['doTask'];
		$params['height'] = $height;
		$params['width']  = $width;
		$html[] = JHtml::_('bootstrap.renderModal', 'modal-' . $name, $params);

		// If an $onClose event is passed, add it to the modal JS object
		if (strlen($onClose) >= 1)
		{
			$html[] = '<script>'
				. 'jQuery(\'#modal-' . $name . '\').on(\'hide\', function () {' . $onClose . ';});'
				. '</script>';
		}
		*/

		return implode("\n", $html);
	}
		
		/**
		 * Get the button id
		 *
		 * @param   string  $type  Button type
		 * @param   string  $name  Button name
		 *
		 * @return  string	Button CSS Id
		 *
		 * @since   3.0
		 */
		public function fetchId($type, $name)
		{
			return $this->_parent->getName() . '-popup-' . $name;
		}

		/**
		 * Get the JavaScript command for the button
		 *
		 * @param   string  $url  URL for popup
		 *
		 * @return  string  JavaScript command string
		 *
		 * @since   3.0
		 */
		private function _getCommand($url)
		{
			if (substr($url, 0, 4) !== 'http')
			{
				$url = JUri::base() . $url;
			}
		
			return $url;
		}
		
		private function layoutRender($options)
		{
			$doTask = $options['doTask'];
			$class  = $options['class'];
			$text   = $options['text'];
			$name   = $options['name'];
			
			$height = $options['height'];
			$width = $options['width'];
			$onClose = $options['onclose'];
			
			//code from J25
			$html[] = "<a class=\"btn btn-small jbmodal\" href=\"$doTask\" rel=\"{handler: 'iframe', size: {x: $width, y: $height}, onClose: function() {" . $onClose . "}}\">";
			$html[] = "<span class=\"$class\">\n";
			$html[] = "</span>";
			$html[] = $text;
			$html[] = "</a>";
			
			return $html;
		}
	}

?>