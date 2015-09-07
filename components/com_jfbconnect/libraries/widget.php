<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('sourcecoast.utilities');

abstract class JFBConnectWidget extends JObject
{
    var $fields;
    var $provider;
    var $name;
    var $systemName;
    var $className;
    var $examples;

    function __construct($provider, $fields)
    {
        $this->provider = $provider;

        $this->fields = new JRegistry();
        if(!is_object($fields))
        {
            $newFields = array();
            $params = $this->splitIntoTagParameters($fields);
            foreach($params as $param)
            {
                if($param != null)
                {
                    $paramValues = explode('=', $param, 2);
                    if (count($paramValues) == 2) //[0] name [1] value
                    {
                        $fieldName = strtolower(trim($paramValues[0]));
                        $fieldValue = trim($paramValues[1]);

                        $newFields[$fieldName] = $fieldValue;
                    }
                }
            }
            $this->fields->loadArray($newFields);
        }
        else
        {
            $this->fields->loadObject($fields);
        }
    }

    public function getSystemName()
    {
        return $this->systemName;
    }

    public function getName()
    {
        return $this->name;
    }

    private function splitIntoTagParameters($paramList)
    {
        $paramList = SCStringUtilities::replaceNBSPWithSpace($paramList);
        $params = explode(' ', $paramList);

        $count = count($params);
        for ($i = 0; $i < $count; $i++)
        {
            $params[$i] = str_replace('"', '', $params[$i]);
            if (strpos($params[$i], '=') === false && $i > 0)
            {
                $previousIndex = $this->findPreviousParameter($params, $i - 1);
                //Combine this with previous entry and space
                $combinedParamValue = $params[$previousIndex] . ' ' . $params[$i];
                $params[$previousIndex] = $combinedParamValue;
                unset($params[$i]);
            }
        }
        return $params;
    }

    private function findPreviousParameter($params, $i)
    {
        for ($index = $i; $index >= 0; $index--)
        {
            if (isset($params[$index]))
                return $index;
        }
        return 0;
    }

    public function getField($fieldName, $deprecatedName, $type, $defaultValue, $htmlName)
    {
        $value = $this->getParamValueEx($fieldName, $deprecatedName, $type, $defaultValue);

        if($value)
            return ' ' . $htmlName . '="' . $value . '"';
        else
            return '';
    }

    public function getParamValue($fieldName)
    {
        return $this->getParamValueEx($fieldName, null, null, '');
    }

    public function getParamValueEx($fieldName, $deprecatedName, $type, $defaultValue)
    {
        $value = $defaultValue;

        if($this->fields->exists($fieldName))
            $value = $this->fields->get($fieldName);
        else if($this->fields->exists($deprecatedName))
            $value = $this->fields->get($deprecatedName);

        if($value == '')
            $value = $defaultValue;

        if($type == 'boolean')
        {
            $value = strtolower($value);
            if($value == 'false' || $value == '0')
                $value = 'false';
            else if($value == 'true' || $value == '1')
                $value = 'true';
            else
                $value = $defaultValue;
        }

        return $value;
    }

    public function render()
    {
        $tag = $this->getTagHtml();
        $class[] = "sourcecoast";
        $class[] = $this->systemName;

        if($this->provider)
        {
            $class[] = $this->provider->name;
            $this->provider->needsJavascript = true;

            if($this->provider->needsCss)
                $tag = $this->provider->getStylesheet() . $tag;

            if($tag)
                $this->provider->widgetRendered = true;
        }

        if($tag)
        {
            if($this->className)
                $class[] = $this->className;

            $classString = implode(' ', $class);
            $tag = '<div class="'.$classString.'">' . $tag . '</div>';
        }

        return $tag;
    }

    public function getHeadData() {}
    protected function getTagHtml() {}
}