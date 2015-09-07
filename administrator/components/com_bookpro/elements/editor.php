<?php

/**
 * Editor parameter element.
 * 
 * @version		$Id: editor.php 15 2012-06-26 12:42:47Z quannv $
 * @package		ARTIO Booking
 * @subpackage  elements
 * @copyright	Copyright (C) 2010 ARTIO s.r.o.. All rights reserved.
 * @author 		ARTIO s.r.o., http://www.artio.net
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link        http://www.artio.net Official website
 */

defined('_JEXEC') or die('Restricted access');

class JElementEditor extends JElement
{
    
    var $_name = 'Editor';

    function fetchElement($name, $value, &$node, $control_name)
    {
        $editor = &JFactory::getEditor();
        /* @var $editor JEditor */
        $code = $editor->display($control_name . '[' . $name . ']', $value, 800, 500, 1, 1);
        return $code;
    }
}

?>