<?php

/**
 * Support for create pagination. Modified standard Joomla! pagination object.
 *
 * @package Bookpro
 * @author Nguyen Dinh Cuong
 * @link http://ibookingonline.com
 * @copyright Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @version $Id: pagination.php 44 2012-07-12 08:05:38Z quannv $
 */

defined('_JEXEC') or die('Restricted access');

class BookProPagination extends JPagination
{
    
    var $minLimit = 5;
    var $maxLimit = 80;

    /**
     * Creates a dropdown box for selecting how many records to show per page.
     *
     * @return	string	The html for the limit # input box
     */
    function getLimitBox()
    {
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        
        for ($i = $this->minLimit; $i <= $this->maxLimit; $i *= 2) {
            $limits[] = JHTML::_('select.option', $i);
            if ($i > $this->total)
                break;
        }
        
        if ($mainframe->isAdmin())
            return JHTML::_('select.genericlist', $limits, 'limit', 'class="inputbox" size="1" onchange="submitform();"', 'value', 'text', $this->limit);
        else
            return JHTML::_('select.genericlist', $limits, 'limit', 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', $this->limit);
    }
}

?>