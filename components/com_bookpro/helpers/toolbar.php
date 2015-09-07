<?php

/**
 * Bookpro check class
 *
 * @package Bookpro
 * @author Nguyen Dinh Cuong
 * @link http://ibookingonline.com
 * @copyright Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @version $Id: toolbar.php 44 2012-07-12 08:05:38Z quannv $
 */
defined('_JEXEC') or die('Restricted access');

class BookProToolbar
{

    /**
     * New subject button with select box to choose template. Add custom button to JToolBar instance.
     */
    function newSubject ()
    {
        $templateHelper = AFactory::getTemplateHelper();
        $html = '<div class="toolbarTitle">';
        $html .= '<a class="toolbar" onclick="javascript: submitbutton(\'add\')" href="#">';
        $html .= '<span class="icon-32-new" title="' . JText::_('New') . '">';
        $html .= '</span>';
        $html .= JText::_('New');
        $html .= '</a>';
        $html .= '<div class="clr"></div>';
        $html .= '</div>';
        $html .= '<div class="toolbarSelect">';
        $html .= $templateHelper->getSelectBox('template', 'new template', 0, false, ' onchange="ListSubjects.setTemplate(this);" ');
        $html .= '</div>';
        JToolBar::getInstance('toolbar')->appendButton('Custom', $html);
    }
}
?>