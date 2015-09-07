<?php

defined('JPATH_BASE') or die();
jimport('joomla.html.toolbar');
class JToolbarButtonALink extends JToolbarButtonLink
	{
	
		function fetchId($type = 'Link', $name = 'back', $text = '', $url = null, $id = null)
		{
			if ($id)
				return $id;
			return parent::fetchId($type, $name, $text, $url);
		}
		
		public function fetchButton($type = 'Link', $name = 'back', $text = '', $url = null)
		{
			$text = JText::_($text);
			$class = $this->fetchIconClass($name);
			$doTask = $this->_getCommand($url);
		
			$html = "<button class=\"btn btn-small\" onclick=\"".$doTask."; return false;\">\n";
			$html .= "<span class=\"".$class."\">\n";
			$html .= "</span>\n";
			$html .= $text."\n";
			$html .= "</button>\n";
		
			return $html;
		}
}

?>