<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 26 2012-07-08 16:07:54Z quannv $
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 //import needed Joomla! libraries
jimport('joomla.application.component.view');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'route');

//import needed models
AImporter::model('applications');
//import custom icons
AHtml::importIcons();

if (! defined('SESSION_PREFIX')) {
	if (IS_ADMIN) {
		define('SESSION_PREFIX', 'bookpro_list_');
	} elseif (IS_SITE) {
		define('SESSION_PREFIX', 'bookpro_site_list_');
	}
}
class BookProViewTest extends BookproJViewLegacy
{
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
    function display($tpl = null)
    {
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        $document->setTitle(COMPONENT_NAME);
        $omodel = new BookProModelApplications();
        $this->lists = array();
        $this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
        $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
        $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'id', 'cmd');
        $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
        $this->lists['state'] = 1;
        $omodel->init($this->lists);
        $this->pagination = &$omodel->getPagination();
        $this->items = &$omodel->getData();
        parent::display($tpl);
    }
}