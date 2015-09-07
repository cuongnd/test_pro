<?php

/**
 * Create parameter table for template properties. 
 * @package Bookpro
 * @author Nguyen Dinh Cuong
 * @link http://ibookingonline.com
 * @copyright Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @version $Id: parameter.php 44 2012-07-12 08:05:38Z quannv $
 */

defined('_JEXEC') or die('Restricted access');


if (! class_exists('JForm'))
    jimport('joomla.html.jform');

class AParameter extends JForm
{
    
    /**
     * Image base 
     * 
     * @var string
     */
    var $images;

    /**
     * Construct object.
     * 
     * @param $data
     * @param $path
     */
    function __construct($data, $path = null, &$xml = null)
    {
        $this->images = JURI::root() . 'components/' . OPTION . '/assets/images/';
       parent::__construct('form');
        if ($xml) {
            $this->load($xml);
        }
    }

   

    /**
     * Render properties table.
     * 
     * @param string $name type of params to render
     * @param string $group params group to render 
     */
    function render($name = 'params', $group = '_default')
    {
        $params = $this->getParams($name, $group);
        $config = &AFactory::getConfig();
        $ripath = AImage::getIPath($config->templatesIcons);
        $ids = array();
        $html = array();
        $html[] = '<table class="template">';
        $html[] = '<thead>';
        $html[] = '<tr>';
        $html[] = '<th>&nbsp;</th>';
        $html[] = '<th><h3>' . JText::_('Property') . '</h3></th>';
        $html[] = '<th><h3>' . JText::_('Field') . '</h3></th>';
        $html[] = '<th><h3>' . JText::_('Icon') . '</h3></th>';
        $html[] = '<th><h3>' . JText::_('Tools') . '</h3></th>';
        $html[] = '</tr>';
        $html[] = '</thead>';
        $html[] = '<tbody id="paramlist">';
        if (is_array($params)) {
            foreach ($params as $i => $param) {
                if (! is_null($param)) {
                    $label = $param[0];
                    $value = $param[1];
                    $id = (int) $param[5];
                    $searchable = (int) $param[6];
                    $filterable = (int) $param[7];
                    $type = $param[8];
                    $paramValue = $param[9];
                    $icon = $param[10];
                    if ($type == 'radio') {
                        $value = '<input type="radio" class="inputRadio" name="params[' . $id . ']" value="" style="display: none" ' . (! $paramValue ? 'checked="checked"' : '') . '/>' . $value;
                    }
                    $ids[] = $id;
                    $html[] = '<tr id="params' . $id . '-row">';
                    $html[] = '<td class="check">';
                    $html[] = '<input type="checkbox" class="inputCheckbox" name="cid[]" id="params' . $id . '-check" value="' . $id . '"/>';
                    $html[] = '</td>';
                    $html[] = '<td class="label">' . $label . '</td>';
                    $html[] = '<td id="params' . $id . '-value">' . $value . '</td>';
                    $html[] = '<td>';
                    $html[] = '<img src="' . htmlspecialchars(AImage::thumb($ripath . $icon, 30, 30)) . '" alt="" id="params' . $id . '-icons" />';
                    $html[] = '<input type="hidden" name="params-icons-orig[]" id="params' . $id . '-icons-orig" value="' . htmlspecialchars($icon) . '" />';
                    $html[] = '</td>';
                    $html[] = '<td id="params' . $id . '-toolbar">';
                    $html[] = $this->tool(true, 'config', null, 'ATemplate.config(' . $id . ')');
                    $html[] = $this->tool(true, 'trash', null, 'ATemplate.trash(' . $id . ',true)');
                    $html[] = $this->tool($searchable != 0, 'search', $id);
                    $html[] = $this->tool($filterable != 0, 'filter', $id);
                    $html[] = '</td>';
                    $html[] = '</tr>';
                }
            }
        }
        $html[] = '</tbody>';
        $html[] = '</table>';
        $html[] = '<div class="glossary">';
        $glossary = array('Glossary' => null , 'Config' => 'config' , 'Trash' => 'trash' , 'Searchable' => 'search' , 'Filterable' => 'filter');
        foreach ($glossary as $label => $icon) {
            if ($icon) {
                $html[] = $this->tool(true, $icon);
                ADocument::addScriptPropertyDeclaration('TmpImg' . ucfirst($icon), $this->getToolImage($icon), true, false);
            }
            $html[] = '<span>' . JText::_($label) . ($icon ? '' : ':') . '</span>';
        }
        $html[] = '</div>';
        $max = count($ids) ? max($ids) : 0;
        ADocument::addScriptPropertyDeclaration('TmpId', $max, false, false);
        return implode(PHP_EOL, $html);
    }

    /**
     * Get toolbar image as only info icon or button with javascript onclick event function.
     * 
     * @param boolean $icon add image or empty div
     * @param string $name name of image and tool
     * @param int $id property ID 
     * @param string $function javascript event function
     * @return string HTML code
     */
    function tool($icon, $name, $id = null, $function = null)
    {
        $image = $this->getToolImage($name);
        $id = $id ? (' id="icon-' . $name . '-' . $id . '" ') : '';
        if ($icon) {
            $uname = ucfirst($name);
            $function = $function ? (' onclick="' . $function . ';" ') : '';
            $class = $function ? 'tool' : 'icon';
            return '<img src="' . $image . '" alt="' . $uname . '"' . $function . ' class="' . $class . '"' . $id . '/>';
        } else {
            return '<div class="emptyIcon"' . $id . '>&nbsp;</div>';
        }
    }

    /**
     * Get tool image full path.
     * 
     * @param string $name
     * @return string
     */
    function getToolImage($name)
    {
        return $this->images . 'icon-16-' . $name . '.png';
    }

    /**
     * Get main table toolbar table.
     * 
     * @return string HTML code
     */
    function toolbar()
    {
        $bar = &JToolBar::getInstance('template-properties');
        /* @var $bar JToolBar */
        $bar->appendButton('Link', 'new', 'New', 'javascript:ATemplate.add()');
        $bar->appendButton('Link', 'delete', 'Delete', 'javascript:ATemplate.trash(\'all\',true)');
        return $bar->render();
    }

    /**
     * Get toolbar button.
     * 
     * @param string $name tool name
     * @param string $function javascript onclick event function
     * @return array parts of HTML code
     */
 	function button($name, $function)
    {
        $html = array();
        $html[] = '<td class="button">';
        $html[] = '<a class="toolbar" href="javascript:' . $function . '">';
        $html[] = '<span class="icon-32-' . $name . '" title="' . ucfirst($name) . '">&nbsp;</span>';
        $name = JString::ucfirst($name);
        $html[] = JText::_($name);
        $html[] = '</a>';
        $html[] = '</td>';
        return $html;
    }

    /**
     * Load param.
     * 
     * @param JSimpleXMLElement $node param node
     * @param string $control_name param name
     * @param string $group param group
     * @return array param values
     */
    function getParam(&$node, $control_name = 'params', $group = '_default')
    {
        $type = $node->attributes('type');
        $type = str_replace('mos_', '', $type);
        $value = $this->get($node->attributes('name'), $node->attributes('default'), $group);
        switch ($type) {
            case 'checkbox':
                $param = &$this->renderCheckBox($node, $value, $control_name);
                break;
            case 'radio':
                $param = &$this->renderRadio($node, $value, $control_name);
                break;
            default:
                $element = &$this->loadElement($type);
                $param = &$element->render($node, $value, $control_name);
                break;
        }
        $param[] = $node->attributes('searchable');
        $param[] = $node->attributes('filterable');
        $param[] = $node->attributes('type');
        $param[] = $value;
        $param[] = $node->attributes('icon');
        $param[] = $node;
        return $param;
    }

    /**
     * Render check box.
     * 
     * @param JSimpleXMLElement $node param node
     * @param mixed $value param value
     * @param string $control_name param name
     * @return array
     */
    function renderCheckBox(&$node, $value, $control_name)
    {
        $param = array();
        
        $name = $node->attributes('name');
        $label = $node->attributes('label');
        
        $nodeName = $control_name . '[' . $name . ']';
        $nodeId = $control_name . $name;
        
        $param[] = '<label id="' . $nodeId . '-lbl" for="' . $nodeId . '">' . $label . '</label>';
        $param[] = '<input type="hidden" name="' . $nodeName . '" value="0"/><input type="checkbox" class="inputCheckbox" name="' . $nodeName . '" id="' . $nodeId . '" value="1" ' . (((int) $value == 1) ? 'checked="checked"' : '') . '/>';
        $param[] = '';
        $param[] = $label;
        $param[] = $value;
        $param[] = $name;
        
        return $param;
    }

    /**
     * Render radio buttons list.
     * 
     * @param JSimpleXMLElement $node param node
     * @param mixed $value param value
     * @param string $control_name param name
     * @return array
     */
    function renderRadio(&$node, $value, $control_name)
    {
        static $id;
        if (is_null($id)) {
            $id = 0;
        }
        $param = array();
        
        $name = $node->attributes('name');
        $label = $node->attributes('label');
        
        $nodeName = $control_name . '[' . $name . ']';
        $nodeId = $control_name . $name;
        
        $param[] = '<label id="' . $nodeId . '-lbl">' . $label . '</label>';
        
        $options = &$node->children();
        $count = count($options);
        $values = '';
        for ($i = 0; $i < $count; $i ++) {
            /* @var $option JSimpleXMLElement */
            $option = &$options[$i];
            $optionValue = $option->attributes('value');
            $id ++;
            $values .= '<input type="radio" class="inputRadio" name="' . $nodeName . '" id="radio' . $id . '" value="' . htmlspecialchars($optionValue) . '"';
            if ($value == $optionValue)
                $values .= ' checked="checked" ';
            $values .= '/><label for="radio' . $id . '" style="float: left">' . $optionValue . '</label>';
        }
        $param[] = $values;
        $param[] = '';
        $param[] = $label;
        $param[] = $value;
        $param[] = $name;
        return $param;
    }

    /**
     * Load component main params configuration.
     * @return JParameter
     */
    function loadComponentParams()
    {
        static $params;
        if (is_null($params)) {
            $db = &JFactory::getDBO();
            /* @var $db JDatabaseMySQL */
                     
            $db->setQuery('SELECT * FROM `#__bookpro_config`');
            $data = $db->loadAssocList('key','value');

            if(!$data)
            {
            	//no config values in the db
            	//JError::raiseWarning(0, "Config values wasn't loaded from database");
            	JLog::add("Config values wasn't loaded from database",JLog::DEBUG);
            }
            $params = new JRegistry($data);
        }
        return $params;
    }

    /**
     * Get component manifest data.
     * 
     * 
     */
    function getComponentInfo()
    {
        static $data;
        if (is_null($data)) {
            $data = &JApplicationHelper::parseXMLInstallFile(MANIFEST);
        }
        return $data;
    }
}

?>