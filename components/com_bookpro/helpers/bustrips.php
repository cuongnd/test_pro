<?php
/**
 * Bookpro check class
 *
 * @package Bookpro
 * @author Nguyen Dinh Cuong
 * @link http://ibookingonline.com
 * @copyright Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @version $Id: tourhelper.php 105 2012-08-30 13:20:09Z quannv $
 */
class BustripsHelper {


    /**
     * Get a list of filter options for the state of a module.
     *
     * @return  array  An array of JHtmlOption elements.
     */
    public static function getStateOptions()
    {
        // Build the filter options.
        $options	= array();
        $options[]	= JHtml::_('select.option',	'1',	JText::_('JPUBLISHED'));
        $options[]	= JHtml::_('select.option',	'0',	JText::_('JUNPUBLISHED'));
        $options[]	= JHtml::_('select.option',	'-2',	JText::_('JTRASHED'));
        return $options;
    }


   }