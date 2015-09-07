<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 26 2012-07-08 16:07:54Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');
AImporter::model('reviews');

AImporter::helper('route', 'bookpro', 'request');
//import needed assets
AHtmlFrontEnd::importIcons();
if (! defined('SESSION_PREFIX')) {
	if (IS_ADMIN) {
		define('SESSION_PREFIX', 'bookpro_review_list_');
	} elseif (IS_SITE) {
		define('SESSION_PREFIX', 'bookpro_review_list_');
	}
}
class BookProViewReviews extends JViewLegacy {
	/**
	 * Array containing browse table filters properties.
	 *
	 * @var array
	 */
	var $lists;

	/**
	 * Array containig browse table subjects items to display.
	 *
	 * @var array
	 */
	var $items;

	/**
	 * Standard Joomla! browse tables pagination object.
	 *
	 * @var JPagination
	 */
	var $pagination;

	/**
	 * Sign if table is used to popup selecting customers.
	 *
	 * @var boolean
	 */
	var $selectable;

	/**
	 * Standard Joomla! object to working with component parameters.
	 *
	 * @var $params JParameter
	 */
	var $params;

	/**
	 * Prepare to display page.
	 *
	 * @param string $tpl name of used template
	 */
	function display($tpl = null) {

		$this -> config = &AFactory::getConfig();

		$mainframe = &JFactory::getApplication();
		/* @var $mainframe JApplication */

		$document = &JFactory::getDocument();
		/* @var $document JDocument */

		$document -> setTitle(JText::_('COM_BOOKPRO_REVIEW_LIST'));

		$user = JFactory::getUser();
		$customer_id='';
		if ($user->id) {
			AImporter::model('customer');
			$model1 = new BookProModelCustomer();
			$customer = $model1->getObjectByUserId($user->id);

			$customer_id = $customer->id;
		}
		$this -> items = array();
		
//var_dump($customer_id); die;
		
		if($customer_id){
			$model = new BookProModelReviews();
			$this -> lists = array();
			$this->lists['limit'] = JRequest::getVar('limit', $mainframe->getCfg('list_limit'), 'int');
			$this->lists['limitstart'] = JRequest::getVar('limitstart', 0, 'int');
			$this->lists['customer_id'] = $customer_id;
			//$this->lists['review-state'] = 1;
			$model -> init($this -> lists);
			$this -> items = &$model -> getData();
		}
		//var_export(count($this -> items)); die;
		$this -> pagination = &$model -> getPagination();

		parent::display($tpl);
	}

}
?>
