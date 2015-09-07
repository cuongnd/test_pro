<?php
//namespace components\com_jchat\models\fields;
/**  
 * @package JCHAT::components::com_jchat 
 * @subpackage models
 * @subpackage fields
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html   
 */ 
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.form.fields.list');

/**
 * Form Field for ACL Groups
 * @package JCHAT::components::com_jchat
 * @subpackage models 
 * @subpackage fields
 * @since 2.0
 */
class JFormFieldAclGroupsMultiselect extends JFormFieldList {
	  
	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return string The field input markup.
	 *        
	 * @since 11.1
	 */
	protected function getInput() {
		// Initialize variables.
		$html = array ();
		$attr = '';
		
		// Initialize some field attributes.
		$attr .= $this->element ['class'] ? ' class="' . ( string ) $this->element ['class'] . '"' : '';
		
		// To avoid user's confusion, readonly="true" should imply
		// disabled="true".
		if (( string ) $this->element ['readonly'] == 'true' || ( string ) $this->element ['disabled'] == 'true') {
			$attr .= ' disabled="disabled"';
		}
		
		$attr .= $this->element ['size'] ? ' size="' . ( int ) $this->element ['size'] . '"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';
		
		// Initialize JavaScript field attributes.
		$attr .= $this->element ['onchange'] ? ' onchange="' . ( string ) $this->element ['onchange'] . '"' : '';
		
		// Get the field options.
		$options = ( array ) $this->getOptions ();
		
		$html = JHtml::_ ( 'select.genericlist', $options, $this->name, trim ( $attr ), 'value', 'text', $this->value, $this->id );
		
		return $html;
	}
	
	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions() {
		$db = JFactory::getDbo ();
		$db->setQuery ( 'SELECT a.id AS value, a.title AS text, COUNT(DISTINCT b.id) AS level' . 
						' FROM ' . $db->qn ( '#__usergroups' ) . ' AS a' . 
						' LEFT JOIN ' . $db->qn ( '#__usergroups' ) . ' AS b' .
						' ON a.lft > b.lft AND a.rgt < b.rgt' . 
						' WHERE a.parent_id > 0' .
						' GROUP BY a.id, a.title, a.lft, a.rgt' . 
						' ORDER BY a.lft ASC' );
		$options = $db->loadObjectList ();
		
		$noActiveOption = JHtml::_('select.option', '0', JTEXT::_('NO_LIVE_ACTIVE'));
		$noActiveOption->level = 0;
		array_unshift($options, $noActiveOption);
		
		// Check for a database error.
		if ($db->getErrorNum ()) {
			JError::raiseNotice ( 500, $db->getErrorMsg () );
			return null;
		}
		
		foreach ( $options as &$option ) {
			$option->text = str_repeat ( '- ', $option->level ) . $option->text;
		}
		
		return $options;
	}
}
