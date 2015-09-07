<?php
// namespace administrator\components\com_jchat\models;
/**
 *
 * @package JCHAT::MESSAGES::administrator::components::com_jchat
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.model' );

/**
 * Messages model responsibilities contract
 *
 * @package JCHAT::MESSAGES::administrator::components::com_jchat
 * @subpackage models
 * @since 1.0
 */
interface IMessagesModel {

	/**
	 * Main get data method
	 * @access public
	 * @return Object[]
	 */
	public function getData();

	/**
	 * Counter result set
	 * @access public
	 * @return int
	 */
	public function getTotal();
	
	/**
	 * Restituisce le select list usate dalla view per l'interfaccia
	 * @access public
	 * @return array
	 */
	public function getLists(); 

	/**
	 * Effettua il load dell'entity singola da orm table
	 * @access public
	 * @param int $id
	 * @return Object&
	 */
	public function loadEntity($id);
	
	/**
	 * Delete entity
	 *
	 * @param array $ids
	 * @access public
	 * @return boolean
	 */
	public function deleteEntity($ids);
	
	/**
	 * Esplica la funzione di esportazione della lista messaggi
	 * in formato CSV per i record estratti dai filtri userstate attivi
	 * @access public
	 * @param array $fieldsToLoadArray
	 * @param array& $fieldsFunctionTransformation
	 * @return Object[]&
	 */
	public function exportMessages($fieldsToLoadArray, &$fieldsFunctionTransformation);
}
 
/**
 * Messages model responsibilities
 *
 * @package JCHAT::MESSAGES::administrator::components::com_jchat
 * @subpackage models
 * @since 1.0
 */
class JChatModelMessages extends JModelLegacy implements IMessagesModel {
	/**
	 * Dataset records
	 * @var Object[]
	 * @access private
	 */
	private $records;

	/**
	 * Restituisce la query string costruita per ottenere il wrapped set richiesto in base
	 * allo userstate, opzionalmente seleziona i campi richiesti
	 * 
	 * @access private
	 * @return string
	 */
	private function buildListQuery($fields = 'a.*') {
		// WHERE
		$where = array();
		$whereString = null;
				
		//Filtro testo
		if($this->state->get('searchword')) {
			$where[] = "\n (a.actualfrom LIKE " .
						$this->_db->quote('%' . $this->state->get('searchword') . '%') .
						"\n OR a.actualto LIKE " . 
						$this->_db->quote('%' . $this->state->get('searchword'). '%')  . 
						"\n OR a.message LIKE " . 
						$this->_db->quote('%' . $this->state->get('searchword'). '%') . ")";
		}
		
		//Filtro periodo
		if($this->state->get('fromPeriod')) {
			$where[] = "\n a.sent > " . strtotime($this->state->get('fromPeriod'));
		}
		
		if($this->state->get('toPeriod')) {
			$where[] = "\n a.sent < " . (strtotime($this->state->get('toPeriod')) + 60*60*24);
		}
		
		if($this->state->get('msgType')) {
			$where[] = "\n a.type = " .  $this->_db->quote($this->state->get('msgType'));
		}
		
		if($this->state->get('msgStatus')) {
			$status = (int)$this->state->get('msgStatus') - 1;
			switch($status) {
				case 1:
				case 0:
					$where[] = "\n a.read = $status AND a.to != 0";
					break;
					
				case -2:
					$where[] = "\n a.read = 0 AND a.to = 0";
					break;
			}
		}
		  
		if (count($where)) {
			$whereString = "\n WHERE " . implode ("\n AND ", $where);
		}
		
		// ORDERBY
		if($this->state->get('order')) {
			$orderString = "\n ORDER BY " . $this->state->get('order') . " ";
		}
		
		//Filtro testo
		if($this->state->get('order_dir')) {
			$orderString .= $this->state->get('order_dir');
		}
		
		
		$query = "SELECT $fields"
				. "\n FROM #__jchat AS a"
				. $whereString 
				. $orderString;
		return $query;
	}

	/**
	 * Main get data method
	 * @access public
	 * @return Object[]
	 */
	public function getData() {
		// Build query 
		$query = $this->buildListQuery();
		$this->_db->setQuery( $query, $this->getState('limitstart'), $this->getState('limit') );
		$this->records = $this->_db->loadObjectList();

		return $this->records;
	}

	/**
	 * Counter result set
	 * @access public
	 * @return int
	 */
	public function getTotal() {
		// Build query 
		$query = $this->buildListQuery();
		$this->_db->setQuery( $query);
		$result = count( $this->_db->loadColumn() );

		return $result;
	}
	
	/**
	 * Restituisce le select list usate dalla view per l'interfaccia
	 * @access public
	 * @return array
	 */
	public function getLists() {
		$lists = array();
		 
		$types[] = JHTML::_('select.option',  '0', '- '. JText::_( 'MESSAGE_TYPE' ) .' -' ); 
		$types[] = JHTML::_('select.option', 'file', JText::_( 'FILE_MESSAGE' ) );
		$types[] = JHTML::_('select.option', 'message', JText::_( 'TEXT_MESSAGE' ) );
		 
		$lists['type'] 	= JHTML::_('select.genericlist', $types, 'msg_type', 'class="inputbox" size="1" onchange="document.adminForm.task.value=\'messages.display\';document.adminForm.submit( );"', 'value', 'text', $this->state->get('msgType'));
			
		$status[] = JHTML::_('select.option',  '', '- '. JText::_( 'STATUS_MSGS' ) .' -' );
		$status[] = JHTML::_('select.option', '2', JText::_( 'DISPLAYED_MSGS' ) );
		$status[] = JHTML::_('select.option', '1', JText::_( 'NOT_DISPLAYED_MSGS' ) );
		$status[] = JHTML::_('select.option', '-1', JText::_( 'TO_GROUP_MSGS' ) );
			
		$lists['status'] 	= JHTML::_('select.genericlist', $status, 'msg_status', 'class="inputbox" size="1" onchange="document.adminForm.task.value=\'messages.display\';document.adminForm.submit( );"', 'value', 'text', $this->state->get('msgStatus'));
			
		
		return $lists;
	}

	/**
	 * Effettua il load dell'entity singola da orm table
	 * @access public
	 * @param int $id
	 * @return mixed Object& in caso di load corretto false altrimenti
	 */
	public function loadEntity($id) {
		// load table record
		$table = $this->getTable('Messages');
		$table->load($id);
		 
		if(!$table){
			JError::raiseNotice ( 400, $this->_db->getErrorMsg () );
			return false;
		}
		return $table;
	}
	
	/**
	 * Delete entity
	 *
	 * @param array $ids
	 * @access public
	 * @return boolean
	 */
	public function deleteEntity($ids) {
		$table = $this->getTable('Messages');
	
		// Ciclo su ogni entity da cancellare
		foreach ($ids as $id) {
			try {
				if (! $table->delete($id)) {
					throw new Exception($table->getError ());
				}
			} catch(Exception $e) {
				switch ($e->getCode()) {
					default:
						JError::raiseNotice(100, $e->getMessage());
				}
				return false;
			}
		}
			
		return true;
	}
	
	
	/**
	 * Esplica la funzione di esportazione della lista messaggi
	 * in formato CSV per i record estratti dai filtri userstate attivi
	 * @access public
	 * @param array $fieldsToLoadArray
	 * @param array& $fieldsFunctionTransformation
	 * @return Object[]&
	 */
	public function exportMessages($fieldsToLoadArray, &$fieldsFunctionTransformation) { 
		$fieldsName = array(); 
		if(is_array($fieldsToLoadArray) && count($fieldsToLoadArray)) {
			$arrayIter = new ArrayIterator($fieldsToLoadArray);
			while ($arrayIter->valid()) { 
				$fieldName = $arrayIter->key();
				$transformedFieldName = $arrayIter->current();
				// Assegnamento duplice name->transformation
				$fieldsName[] = $fieldName;
				$fieldsFunctionTransformation[] = $transformedFieldName;
		
				// Increment pointer
				$arrayIter->next();
			}
		}
		
		$joinedFieldsName = implode(',', $fieldsName);
		
		// Obtain query string
		$query = $this->buildListQuery($joinedFieldsName);
		$this->_db->setQuery($query, $this->getState('limitstart'), $this->getState('limit') );
		$resultSet = $this->_db->loadAssocList();
		
		if(!is_array($resultSet) || !count($resultSet)) {
			return false;
		}
		
		return $resultSet;
	}
} 