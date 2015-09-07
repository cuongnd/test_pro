<?php

/**
 * Support for custom manipulating with HTML document using standard Joomla! object JDocumentHTML. 
 * 
 * @package Bookpro
 * @author Nguyen Dinh Cuong
 * @link http://ibookingonline.com
 * @copyright Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @version $Id: document.php 44 2012-07-12 08:05:38Z quannv $
 */

defined('_JEXEC') or die('Restricted access');

class ADocument
{

  

    /**
     * Add link to style sheet for Internet Explorer 6 with hack to ignore by others browsers.
     * 
     * @param string $url style sheet URL
     */
        
    static function addJQueryDomready($code)
    {
    	$document = JFactory::getDocument();
    	/* @var $document JDocument */
    	$js = 'jQuery(document).ready(function($) {' . PHP_EOL;
    	$js .= $code . PHP_EOL;
    	$js .= '});' . PHP_EOL;
    	$document->addScriptDeclaration($js);
    }

    /**
     * Add language constants into HTML head
     * 
     * @param array $languages key is name of param
     */
    function addLGScriptDeclaration($languages)
    {
        foreach ($languages as $name => $value) {
            ADocument::addScriptPropertyDeclaration($name, JText::_($value));
        }
    }

    /**
     * Get Joomla! object JDocument
     * 
     * @return JDocument
     */
    function getDocument()
    {
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        return $document;
    }

    /**
     * Add Javascript property into HTML page head.
     * 
     * @param string $name property name
     * @param mixed $value property value
     * @param boolean $quote add quotes
     * @param boolean $htmlentities convert value as htmlentities
     */
    function addScriptPropertyDeclaration($name, $value, $quote = true, $htmlentities = true)
    {
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        if ($htmlentities) {
            $value = str_replace(array('"' , "'"), array('&quot;' , '&#039;'), $value);
        }
        if ($quote) {
            $value = '"' . $value . '"';
        }
        $document->addScriptDeclaration('	var ' . $name . ' = ' . $value . ';');
    }

    /**
     * Add into HTML HEAD URL base javascript property with name 'juri'. 
     */
    function setScriptJuri()
    {
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        $document->addScriptDeclaration('var juri = "' . JURI::base() . '";');
    }

    /**
     * Add into HTML HEAD relative URL to calendar holder image.
     */
	function setCalendarHolder()
    {
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $document->addScriptDeclaration('var calendarHolder = "' . IMAGES . 'icon-16-calendar.png' . '";');
        $document->addScriptDeclaration('var calendarEraser = "' . IMAGES . 'icon-16-calendar-erase.png' . '";');
    }

    /**
     * Add javascript event into page HTML head running on domready.
     * 
     * @param string $code event code
     */
    function addDomreadyEvent($code)
    {

    }
   

    /**
     * Set reservation box params as javascript object
     * 
     * @param BookingBox $box
     */
    function setBoxParams(&$box, $i, $useId2 = false)
    {
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        $vars = get_object_vars($box);
        $id = $vars['id'] = $useId2 ? $vars['id2'] : $vars['id'];
        unset($vars['id2']);
        foreach ($vars as $param => $value)
            $boxes[] = $param . ' : "' . addslashes($value) . '"';
        $document->addScriptDeclaration('Calendars.boxes[' . $i ++ . '] = {' . implode(', ', $boxes) . '}');
        ADocument::addDomreadyEvent("$('" . $id . "').addEvent('click',function(){Calendars.setCheckBox(\"" . $id . "\");});");
        ADocument::addDomreadyEvent("$('" . $id . "').addEvent('mouseover',function(){Calendars.highlightInterval(\"" . $id . "\");});");
        ADocument::addDomreadyEvent("$('" . $id . "').addEvent('mouseout',function(){Calendars.unhighlightInterval(\"" . $id . "\");});");
    }
}

?>