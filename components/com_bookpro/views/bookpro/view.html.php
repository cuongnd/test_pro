<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 26 2012-07-08 16:07:54Z quannv $
 * */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
//import needed Joomla! libraries
jimport('joomla.application.component.view');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'route', 'adminui', 'request', 'currency','paystatus');

//import needed models
AImporter::model('orders');
//import custom icons
AHtmlFrontEnd::importIcons();

if (!defined('SESSION_PREFIX')) {
    define('SESSION_PREFIX', 'bookpro_list_');
}

class BookProViewBookPro extends BookproFrontEndJViewLegacy {

    /**
     * Array containig browse table reservations items to display.
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
     * Prepare to display page.
     *
     * @param string $tpl name of used template
     */
    function display($tpl = null) {
        return;
        $mainframe = JFactory::getApplication();
        $user = JFactory::getUser();
        //var_dump($user->password);
        //var_dump(JUserHelper::hashPassword('123456'));
        /* @var $mainframe JApplication */
        $document = JFactory::getDocument();
        /* @var $document JDocument */
        $document->setTitle(COMPONENT_NAME);
        $omodel = new BookProModelOrders();
        $this->lists = array();
        $this->lists['limit'] = 5;
        $this->lists['limitstart'] = 0;
        //$this->lists['order'] = ARequest::getUserStateFromRequest('filter_order','id', 'cmd');
        $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
        //$this->lists['created'] = JFactory::getDate()->format('Y-m-d');
        $omodel->init($this->lists);
        $this->pagination = &$omodel->getPagination();
        $this->items = &$omodel->getData();
        $this->myordersign=$this->getMyOrderAsign();
        $user = JFactory::getUser();
        parent::display($tpl);
    }
	function getMyOrderAsign()
	{
		$user=JFactory::getUser();

		if(!in_array(11, $user->getAuthorisedGroups()))
		{
			return null;
		}
		$omodel = new BookProModelOrders();
		$this->lists = array();
		$this->lists['limit'] = 5;
		$this->lists['sale_id'] = $user->id;
		$this->lists['limitstart'] = 0;
		$omodel->init($this->lists);
		$items = &$omodel->getData();
		return  $items;
	}
    function addButtonBar() {

        $buttons = array(
            array(
                'link' => JRoute::_('index.php?option=com_bookpro&view=customer&group='),
                'image' => JUri::root() . 'components/com_bookpro/assets/images/booking.png',
                'icon' => 'booking',
                'text' => JText::_('COM_BOOKPRO_LATEST_BOOKING'),
                'access' => array('core.manage', 'com_bookpro', 'core.create', 'option=com_bookpro',)
            ),
            array(
                'link' => JRoute::_('index.php?option=com_content&task=article.add'),
                'image' => 'pencil-2',
                'icon' => 'header/icon-48-article-add.png',
                'text' => JText::_('MOD_QUICKICON_ADD_NEW_ARTICLE'),
                'access' => array('core.manage', 'com_content', 'core.create', 'com_content'),
                'group' => 'MOD_QUICKICON_CONTENT'
            ),
            array(
                'link' => JRoute::_('index.php?option=com_content'),
                'image' => 'stack',
                'icon' => 'header/icon-48-article.png',
                'text' => JText::_('MOD_QUICKICON_ARTICLE_MANAGER'),
                'access' => array('core.manage', 'com_content'),
                'group' => 'MOD_QUICKICON_CONTENT'
            )
        );

        $html = JHtmlIcons::buttons($buttons);
        if (!empty($html))
            echo $html;
    }



}