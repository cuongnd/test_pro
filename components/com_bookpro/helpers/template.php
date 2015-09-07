<?php

/**
 * Support for manipulating with objects templates.
 * 
 * @package Bookpro
 * @author Nguyen Dinh Cuong
 * @link http://ibookingonline.com
 * @copyright Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @version $Id: template.php 44 2012-07-12 08:05:38Z quannv $
 */

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.filesystem.file');

AImporter::model('template');

class ATemplateHelper
{
    
    /**
     * Collection of templates
     * 
     * @var array
     */
    var $_templates;

    function __construct()
    {
        $this->loadTemplates();
    }

    /**
     * Get templates select
     * 
     * @param int $select select option
     * @param boolean $autoSubmit sign if select box is list filter or edit field
     * @return string HTML code
     */
    function getSelectBox($name, $noSelect, $select, $autoSubmit, $customParams = '')
    
    {
        $templates = $this->_templates;
        return AHtmlFrontEnd::getFilterSelect($name, $noSelect, $templates, $select, $autoSubmit, $customParams, 'id', 'name');
    }

    /**
     * Load templates from XML. Create objects and save into object collection.
     */
    function loadTemplates()
    {
        $model = new BookingModelTemplate();
        $sources = &$model->loadList();
        $this->_templates = array();
        foreach ($sources as $source) {
            $template = new ATemplate();
            $template->source = $source->xml;
            $template->init();
            $this->_templates[] = $template;
        }
    }

    /**
     * Info about count saved templates.
     * 
     * @return boolean true have more templates, false templates pool is empty
     */
    function haveTemplates()
    {
        $count = count($this->_templates);
        $haveTemplates = $count != 0;
        return $haveTemplates;
    }

    /**
     * Search in loaded templates by id
     * 
     * @param int $id
     * @return ATemplate null if not found
     */
    function getTemplateById($id)
    {
        $id = (int) $id;
        foreach ($this->_templates as $template) {
            if ((int) $template->id == $id) {
                return $template;
            }
        }
        return new ATemplate();
    }

    /**
     * Import template js source files
     */
    function importAssets()
    {
        $files = JFolder::files(SITE_ROOT . DS . 'assets' . DS . 'js', '.js$', false, false);
        foreach ($files as $file) {
            if (strpos($file, 'template') === 0) {
                AImporter::js(substr($file, 0, strlen($file) - 3));
            }
        }
    }

    function removeItems($itemsToDelete)
    {
        $templates = array();
        foreach ($itemsToDelete as $itemToDelete) {
            if (! isset($templates[$itemToDelete->template])) {
                $templates[$itemToDelete->template] = array();
            }
            $templates[$itemToDelete->template][] = $itemToDelete->id;
        }
        foreach ($templates as $templateId => $templateItems) {
            $templateObject = $this->getTemplateById($templateId);
            if (is_object($templateObject)) {
                $templateObject->removeItem($templateItems);
            }
        }
        return true;
    }

    /**
     * Import Templates Icons to Javascript Array and flush into HTML Head.
     * 
     * @param string $apath absolute path to icons
     * @param string $rpath relative patch to icons
     */
    function importIconsToJS($apath, $rpath)
    {
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        if (! is_dir($apath)) {
            JError::raiseNotice(100, JText::_('Path to directory with objects icons no exists or is no directory. Please check Bookit! global configuration.'));
            $icons = array();
        } else {
            $icons = &JFolder::files($apath);
        }
        $count = count($icons);
        $thumbsIcons = array();
        $realIcons = array();
        for ($i = 0; $i < $count; $i ++) {
            $icon = &$icons[$i];
            $thumbsIcons[] = '"' . htmlspecialchars(AImage::thumb($apath . $icon, 30, 30)) . '"';
            $realIcons[] = '"' . htmlspecialchars($icon) . '"';
        }
        $document->addScriptDeclaration('	var TmpIconsThumbs = new Array(' . implode(',', $thumbsIcons) . ');');
        $document->addScriptDeclaration('	var TmpIconsReal = new Array(' . implode(',', $realIcons) . ');');
    }
}

/**
 * Object template with params loaded from XML source file
 * 
 */
class ATemplate
{
    
    /**
     * XML parser
     * 
     * @var JSimpleXML
     */
    var $parser;
    
    /**
     * XML source file
     * 
     * @var string
     */
    var $source;
    
    /**
     * Template name
     * 
     * @var string
     */
    var $name;
    
    /**
     * Unique ID use in database
     * 
     * @var int
     */
    var $id;
    
    var $params;

    function __construct()
    {
        $this->parser = JFactory::getXMLParser('Simple');
        $this->source = '';
        $this->name = '';
        $this->id = 0;
    }

    /**
     * Init object from XML source file
     */
    function init()
    {
        $this->parser->loadString($this->source);
        $root = $this->parser->document;
        if (is_object($root)) {
            $this->name = $root->attributes('name');
            $this->id = (int) $root->attributes('id');
        }
    }

    /**
     * Get template table name
     * 
     * @param int id if is null use global id
     * @return string
     */
    function getDBTableName($id = null)
    {
        return TEMPLATES_DB_PREFIX . (is_null($id) ? $this->id : $id);
    }

    /**
     * Load saved template params for concrete object using this template. Add params into format: param=value.
     * 
     * @param int $id object ID
     * @return string
     */
    function loadObjectParams($id)
    {
        $params = new JParameter('');
        if ($this->tableExist()) {
            $db = &JFactory::getDBO();
            /* @var $db JDatabaseMySQL */
            $db->setQuery('SELECT * FROM `' . $this->getDBTableName() . '` WHERE `id` = ' . (int) $id);
            $result = $db->query();
            if ($result instanceof mysqli_result)
                $fields = mysqli_fetch_assoc($result);
            elseif (is_resource($result))
                $fields = mysql_fetch_assoc($result);
            if (is_array($fields)) {
                unset($fields['id']);
                $params->bind($fields);
            }
        }
        return $params->toString();
    }

    function translateParam($value)
    {
        if (BookingHelper::joomFishIsActive()) {
            static $translations;
            if (is_null($translations)) {
                $db = &JFactory::getDBO();
                /* @var $db JDatabaseMySQL */
                $language = &JFactory::getLanguage();
                /* @var $language JLanguage */
                $tag = $language->getTag();
                $query = 'SELECT `id` FROM `#__languages` WHERE `code` = \'' . $tag . '\'';
                $db->setQuery($query);
                $lid = (int) $db->loadResult();
                $query = 'SELECT `value`.`value` AS `original`, COALESCE(`view`.`value`,`value`.`value`) AS `translation` ';
                $query .= 'FROM `#__booking_template_value` AS `value` ';
                $query .= 'LEFT JOIN `#__booking_template_value_view` AS `view` ';
                $query .= 'ON `view`.`id` = `value`.`id` AND `view`.`language` = ' . $lid;
                $db->setQuery($query);
                $data = &$db->loadObjectList();
                $translations = array();
                $count = count($data);
                for ($i = 0; $i < $count; $i ++) {
                    $item = &$data[$i];
                    $translations[$item->original] = $item->translation;
                }
                unset($data);
            }
            $value = isset($translations[$value]) ? $translations[$value] : $value;
        }
        return $value;
    }

    /**
     * Display param value. If param type is checkbox display text yes/no. Otherwise display translated param text value. 
     * 
     * @param array $param
     * @return string
     */
    function displayParamValue(&$param)
    {
        if ($param[PARAM_TYPE] == 'checkbox')
            return JText::_($param[PARAM_VALUE] == 1 ? 'JYes' : 'JNo');
        else
            return ATemplate::translateParam($param[PARAM_VALUE]);
    }

    /**
     * Save template from request.
     * 
     * @param stdClass $item object using template
     * @param array $data request params
     */
    function store(&$item = null, $copy = false)
    {
        if ($copy) {
            $from = $this->id;
            $this->setNewId();
            $to = $this->id;
            $this->copyTable($from, $to);
        }
        
        if (! $this->tableExist()) {
            $this->setNewId();
            $this->createTable();
            if (is_object($item)) {
                $item->template = $this->id;
            }
        }
        
        $this->loadParams();
        
        $requestParams = &ARequest::getStringArray('params');
        $requestParamsOutput = &ARequest::getStringArray('params-output');
        
        foreach ($this->params as $name => $existParam) {
            if (! array_key_exists($name, $requestParams)) {
                unset($this->params[$name]);
                $this->dropColumn($name);
            } else {
                $this->params[$name]->value = $requestParams[$name];
            }
            if (array_key_exists($name, $requestParamsOutput)) {
                $this->params[$name]->update($requestParamsOutput[$name]);
                for ($i = 0; $i < count($existParam->options); $i ++) {
                    if (! isset($existParam->newOptions[$i])) {
                        $this->updateColumnValue($name, $existParam->options[$i], '');
                    } elseif ($existParam->options[$i] != $existParam->newOptions[$i]) {
                        $this->updateColumnValue($name, $existParam->options[$i], $existParam->newOptions[$i]);
                    }
                }
                $this->params[$name]->options = $this->params[$name]->newOptions;
                unset($requestParamsOutput[$name]);
            }
        }
        
        foreach ($requestParamsOutput as $name => $newParam) {
            $this->params[$name] = new ATemplateParam();
            $this->params[$name]->name = $name;
            $this->params[$name]->value = isset($requestParams[$name]) ? $requestParams[$name] : '';
            $this->params[$name]->update($newParam);
            $this->params[$name]->options = $this->params[$name]->newOptions;
            $this->addColumn($name, $this->params[$name]->type);
        }
        
        if (is_object($item)) {
            $this->saveItem($item->id);
        }
        
        $xml = $this->getXML();
        
        return $xml;
    }

    /**
     * Update template xml source.
     */
    function getXML()
    {
        $root = $this->parser->document;
        $attrs = array('name' => $this->name , 'id' => $this->id);
        if ($root) {
            $root->_attributes = array_change_key_case($attrs, CASE_LOWER);
        } else {
            $root = new JSimpleXMLElement('form', $attrs);
        }
        $params = $root->getElementByPath('params');
        if (! $params) {
            $root->addChild('params');
            $params = $root->getElementByPath('params');
        }
        $params->_children = array();
        foreach ($this->params as $param) {
            $attributes = array();
            $attributes['name'] = $param->name;
            $attributes['type'] = $param->type;
            $attributes['default'] = '';
            $attributes['label'] = $param->label;
            $attributes['description'] = '';
            $attributes['searchable'] = $param->searchable;
            $attributes['filterable'] = $param->filterable;
            $attributes['icon'] = $param->icon;
            $child = $params->addChild('param', $attributes);
            if (is_array($param->options)) {
                foreach ($param->options as $option) {
                    $attributes = array();
                    $attributes['value'] = $option;
                    $child->addChild('option', $attributes);
                }
            }
        }
        $xml = $root->toString();
        $xml = '<?xml version="1.0" encoding="utf-8"?>' . $xml;
        
        return $xml;
    }

    /**
     * Load template params from xml source.
     */
    function loadParams()
    {
        $root = $this->parser->document;
        $params = $root ? $root->getElementByPath('params') : null;
        $params = $params ? $params->children() : null;
        $this->params = array();
        if (is_array($params)) {
            foreach ($params as $param) {
                $object = new ATemplateParam();
                $object->load($param);
                $this->params[$object->name] = $object;
            }
        }
    }

    /**
     * Check if template table exists
     * 
     * @return boolean
     */
    function tableExist()
    {
        AImporter::helper('model');
        $tableExists = AModel::tableExists($this->getDBTableName());
        return $tableExists;
    }

    /**
     * Check if template have saved items.
     * 
     * @return boolean
     */
    function haveItems()
    {
        $db = &JFactory::getDBO();
        /* @var $db JDatabaseMySQL */
        $query = 'SELECT COUNT(*) FROM `' . $this->getDBTableName($this->id) . '`';
        $db->setQuery($query);
        $count = (int) $db->loadResult();
        return $count != 0;
    }

    /**
     * Get new template ID
     * 
     * @return int
     */
    function setNewId()
    {
        $db = &JFactory::getDBO();
        /* @var $db JDatabaseMySQL */
        $tmpPrefix = str_replace('#__', $db->getPrefix(), $this->getDBTableName(''));
        $query = 'SHOW TABLES LIKE ' . $db->Quote($tmpPrefix . '%');
        $db->setQuery($query);
        $tables = $db->loadResultArray();
        $existsIds = array();
        foreach ($tables as $table) {
            $existsIds[] = (int) str_replace($tmpPrefix, '', $table);
        }
        $this->id = count($existsIds) ? (max($existsIds) + 1) : 1;
        $this->source = TEMPLATES_SOURCE . DS . 'template_' . $this->id . '.xml';
    }

    /**
     * Create new template table with primary ID column
     */
    function createTable()
    {
        $db = &JFactory::getDBO();
        $query = 'CREATE TABLE IF NOT EXISTS ' . $this->getDBTableName() . ' ( id int(11) NOT NULL auto_increment, PRIMARY KEY  (id) ) ENGINE=MyISAM DEFAULT CHARSET=utf8';
        $db->setQuery($query);
        $db->query();
    }

    function copyTable($from, $to)
    {
        $db = &JFactory::getDBO();
        $query = 'CREATE TABLE `' . $this->getDBTableName($to) . '` LIKE `' . $this->getDBTableName($from) . '`';
        $db->setQuery($query);
        $db->query();
    }

    /**
     * Add column into existing table
     * 
     * @param string $name column name
     * @param string $type data type
     * @param int $length data size
     * @param int $after ID column after insert
     */
    function addColumn($name, $type)
    {
        switch ($type) {
            case 'textarea':
                $field = 'TEXT';
                break;
            case 'checkbox':
                $field = 'TINYINT(4)';
                break;
            default:
                $field = 'VARCHAR(255)';
                break;
        }
        $db = &JFactory::getDBO();
        $query = 'ALTER TABLE ' . $this->getDBTableName() . ' ADD `' . $name . '` ' . $field . ' NOT NULL';
        $db->setQuery($query);
        $db->query();
    }

    /**
     * Add column index
     * 
     * @param string $name column name
     */
    function addIndex($name)
    {
        $db = &JFactory::getDBO();
        $query = 'ALTER TABLE ' . $this->getDBTableName() . ' ADD INDEX (`' . $name . '`)';
        $db->setQuery($query);
        $db->query();
    }

    /**
     * Drop table column
     * 
     * @param string $name column name
     */
    function dropColumn($name)
    {
        if ($this->tableExist()) {
            $db = &JFactory::getDBO();
            $query = 'ALTER TABLE ' . $this->getDBTableName() . ' DROP `' . $name . '`';
            $db->setQuery($query);
            $db->query();
        }
    }

    /**
     * Update column value
     * 
     * @param string $name column name
     * @param string $oldValue old value
     * @param string $newValue new value
     */
    function updateColumnValue($name, $oldValue, $newValue)
    {
        if ($name && $oldValue && $newValue) {
            $db = &JFactory::getDBO();
            $query = 'UPDATE ' . $this->getDBTableName() . ' SET `' . $name . '` = ' . $db->Quote($newValue) . ' WHERE `' . $name . '` = ' . $db->Quote($oldValue);
            $db->setQuery($query);
            $db->query();
        }
    }

    /**
     * Check if item with ID already saved
     * 
     * @param int $id
     * @return boolean
     */
    function itemSaved($id)
    {
        $db = &JFactory::getDBO();
        $query = 'SELECT COUNT(id) FROM ' . $this->getDBTableName() . ' WHERE id = ' . $id;
        $db->setQuery($query);
        $count = (int) $db->loadResult();
        return $count > 0;
    }

    /**
     * Save template item
     * 
     * @param int $id
     */
    function saveItem($id)
    {
        if (count($this->params)) {
            $db = &JFactory::getDBO();
            $item = new stdClass();
            $item->id = $id;
            foreach ($this->params as $param) {
                $name = $param->name;
                $item->$name = $param->value;
            }
            if ($this->itemSaved($id)) {
                $db->updateObject($this->getDBTableName(), $item, 'id');
            } else {
                $db->insertObject($this->getDBTableName(), $item, 'id');
            }
        }
    }

    /**
     * Remove item from template table.
     * 
     * @param int $id item id
     * @param int $template template id
     * @return boolean true if success
     */
    function removeItem($id, $template = null)
    {
        if ($this->tableExist()) {
            $db = &JFactory::getDBO();
            $query = 'DELETE FROM ' . $this->getDBTableName($template) . ' WHERE id ' . (is_array($id) && count($id) ? 'IN (' . implode(',', $id) . ')' : '= ' . (int) $id);
            $db->setQuery($query);
            return $db->query();
        }
        return true;
    }

    /**
     * Delete template: delete database table and XML source file.
     * 
     * @return true if successfull
     */
    function delete()
    {
        $db = &JFactory::getDBO();
        /* @var $db JDatabaseMySQL */
        
        $query = 'DROP TABLE IF EXISTS ' . $this->getDBTableName();
        
        $db->setQuery($query);
        $success = $db->query();
        
        return $success;
    }
}

/**
 * Template param (object property). 
 */
class ATemplateParam
{
    
    /**
     * Param name
     * 
     * @var string
     */
    var $name;
    
    /**
     * Pram data type: list,radio or text
     * 
     * @var string
     */
    var $type;
    
    /**
     * Param label
     * 
     * @var string
     */
    var $label;
    
    /**
     * Sign param searchable
     * 
     * @var int
     */
    var $searchable;
    
    /**
     * Sign param is filterable
     * 
     * @var int
     */
    var $filterable;
    
    /**
     * Icon filename
     * 
     * @var string
     */
    var $icon;
    /**
     * Options for list or radio param type
     * 
     * @var array
     */
    var $options;
    
    /**
     * Tmp options memory
     * 
     * @var array
     */
    var $newOptions;
    
    /**
     * Param value
     * 
     * @var mixed
     */
    var $value;

    /**
     * Load param attributes
     * 
     * @param JSimpleXMLElement $param
     */
    function load(&$param)
    {
        $this->name = $param->attributes('name');
        $this->type = $param->attributes('type');
        $this->label = $param->attributes('label');
        $this->searchable = $param->attributes('searchable');
        $this->filterable = $param->attributes('filterable');
        $this->icon = $param->attributes('icon');
        $this->loadMulti($param);
    }

    /**
     * Load param multi attributes
     * 
     * @param JSimpleXMLElement $param
     */
    function loadMulti(&$param)
    {
        $options = & $param->children();
        $this->options = array();
        if (is_array($options)) {
            foreach ($options as $option) {
                $this->options[] = $option->attributes('value');
            }
        }
    }

    /**
     * Update by new values from request
     * 
     * @param string $data
     */
    function update($data)
    {
        $parts = explode('|', $data);
        $this->label = isset($parts[0]) ? $parts[0] : '';
        $this->searchable = isset($parts[1]) ? (int) $parts[1] : 0;
        $this->filterable = isset($parts[2]) ? (int) $parts[2] : 0;
        $this->icon = (isset($parts[3]) && $parts[3] != '0') ? $parts[3] : '';
        $this->type = isset($parts[4]) ? $parts[4] : '';
        $this->newOptions = array();
        for ($i = 5; $i < count($parts); $i ++) {
            $parts[$i] = JString::trim($parts[$i]);
            if ($parts[$i]) {
                $this->newOptions[] = JString::trim($parts[$i]);
            }
        }
    }
}

?>