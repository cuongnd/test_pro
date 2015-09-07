<?php
// namespace administrator\components\com_jchat\views\messages;
/** 
 * @package JCHAT::MESSAGES::administrator::components::com_jchat
 * @subpackage views
 * @subpackage messages
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
define ( 'INDEX_TO', 'receiver_name');
define ( 'INDEX_SENT', 'sent');
define ( 'INDEX_READ', 'read');
define ( 'INDEX_MESSAGE', 'message');
jimport ( 'joomla.application.component.view' );
jimport('joomla.html.pagination');

/**
 * User messages view implementation
 *
 * @package JCHAT::MESSAGES::administrator::components::com_jchat
 * @subpackage views
 * @since 1.0
 */
class JChatViewMessages extends JViewLegacy {
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addShowEntityToolbar() {
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-jchat{background-image:url("components/com_jchat/images/icon-48-readmess.png")}');
		JToolBarHelper::title( JText::_( 'JCHAT_MAINTITLE_TOOLBAR' ) . JText::_( 'MESSAGE_DETAILS' ), 'jchat' );
		JToolBarHelper::custom('messages.display', 'back', 'back', 'BACK_TO_LIST_MESSAGES', false);
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addDisplayToolbar() {
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-jchat{background-image:url("components/com_jchat/images/icon-48-readmess.png")}');
		JToolBarHelper::title( JText::_( 'LIST_MESSAGES' ), 'jchat' );
		JToolBarHelper::editList('messages.showentity', 'VIEW_MESSAGE_DETAILS');
		JToolBarHelper::deleteList(JText::_('DELETE_MESSAGES'), 'messages.deleteentity');
		JToolBarHelper::custom('messages.exportmessages', 'export', 'export', 'EXPORT_MSG', false);
		JToolBarHelper::custom('cpanel.display', 'config', 'config', 'JCHAT_CPANEL', false);
	}

	/**
	 * Default listEntities
	 * @access public
	 */
	public function display($tpl = null) {
		$doc = JFactory::getDocument ();
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_jchat/css/generic.css' );
		JHTML::_('behavior.calendar');
		JHTML::_('bootstrap.framework');
		$doc->addScriptDeclaration("function checkAll(n) {
				var form = jQuery('#adminForm');
				var checkItems = jQuery('input[type=checkbox][data-enabled!=false][name!=toggle]', form);
				if(!jQuery('input[type=checkbox][name=toggle]').prop('checked')) {
					jQuery(checkItems).prop('checked', false);
					jQuery('input[name=boxchecked]', form).val(0);
				} else {
					jQuery(checkItems).prop('checked', true);
					if(checkItems.length) {jQuery('input[name=boxchecked]', form).val(checkItems.length)};
				}
		
		}");
		// Get main records
		$rows = $this->get('Data');
		$lists = $this->get('Lists');
		$total = $this->get('Total');
		
		$orders = array();
		$orders['order'] = $this->getModel()->getState('order');
		$orders['order_Dir'] = $this->getModel()->getState('order_dir');
		// Pagination view object model state populated
		$pagination = new JPagination( $total, $this->getModel()->getState('limitstart'), $this->getModel()->getState('limit') );
		$dates = array('start'=>$this->getModel()->getState('fromPeriod'), 'to'=>$this->getModel()->getState('toPeriod'));
		 
		$this->pagination = $pagination;
		$this->order = $this->getModel()->getState('order');
		$this->searchword = $this->getModel()->getState('searchword');
		$this->lists = $lists;
		$this->orders = $orders;
		$this->items = $rows;
		$this->option = $this->getModel()->getState('option');
		$this->dates = $dates; 
		
		// Add toolbar
		$this->addDisplayToolbar();
		
		parent::display();
	}

	/**
	 * Mostra la visualizzazione dettaglio del record singolo
	 * @param Object& $row
	 * @access public
	 */
	public function showEntity(&$row) {
		// Add toolbar
		$this->addShowEntityToolbar();
		
		$doc = JFactory::getDocument ();
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_jchat/css/generic.css' );
		
		$this->option = $this->getModel()->getState('option');
		$this->record = $row;
		
		$this->setLayout('messages');
		parent::display('details');
	}
	 
	/**
	 * Effettua l'output view del file in attachment al browser
	 * 
	 * @access public
	 * @param string $contents
	 * @param int $size
	 * @param array& $fieldsFunctionTransformation
	 * @return void
	 */
	public function sendCSVMessages($data, &$fieldsFunctionTransformation) {
		$delimiter = ';';
		$enclosure = '"';
		// Clean dirty buffer
		ob_end_clean();
		// Open buffer
		ob_start();
		// Open out stream
		$outstream = fopen("php://output", "w");
		// Funzione di scrittura nell'output stream
		function __outputCSV(&$vals, $key, $userData) {
			// Fields value transformations 
			if(isset($vals[INDEX_SENT]) && (int)$vals[INDEX_SENT]) {
				$vals[INDEX_SENT] = date('Y-m-d H:i:s', $vals[INDEX_SENT]);
			}
			if(isset($vals[INDEX_READ]) && (int)$vals[INDEX_READ]) {
				$vals[INDEX_READ] = JText::_('YESREAD');
			} else {
				if(!$userData[3]) {
					$vals[INDEX_READ] = $vals[INDEX_TO] ? JText::_('NOREAD') : JText::_('MULTIPLE_RECEIVER_USERS');
				}
			}
			if(isset($vals[INDEX_MESSAGE])) {
				$vals[INDEX_MESSAGE] = preg_replace('/<img(.)*\/>/iU', '', $vals[INDEX_MESSAGE]);
			}
			fputcsv($userData[0], $vals, $userData[1], $userData[2]); // add parameters if you want
		}
		// Echo delle intestazioni
		__outputCSV($fieldsFunctionTransformation, null, array($outstream, $delimiter, $enclosure, true));
		// Output di tutti i records
		array_walk($data, "__outputCSV", array($outstream, $delimiter, $enclosure, false));
		fclose($outstream);
		// Recupero output buffer content
		$contents = ob_get_clean();
		$size = strlen($contents);
		
		
		header ( 'Pragma: public' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Expires: ' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
		header ( 'Content-Disposition: attachment; filename="messages.csv"' );
		header ( 'Content-Type: text/plain' );
		header ( "Content-Length: " . $size );
		echo $contents;
			
		exit ();
	}
}
?>