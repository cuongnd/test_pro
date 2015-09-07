<?php

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');
AImporter::model('categories');
AImporter::helper('route', 'bookpro', 'request', 'touradministrator');
//import needed assets
AHtml::importIcons();
if (!defined('SESSION_PREFIX')) {
    define('SESSION_PREFIX', 'bookpro_tours_list_');
}

class BookProViewTours extends BookproJViewLegacy {

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
    var $params;

    /**
     * Prepare to display page.
     * 
     * @param string $tpl name of used template
     */
    function display($tpl = null) {
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */

        $document = &JFactory::getDocument();
        /* @var $document JDocument */

        $document->setTitle(JText::_('List of Tours'));

        $session = JFactory::getSession();
        $session->set('type', 1);

        $model = new BookProModelTours();

        $this->lists = array();
        $this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
        $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
        $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'ordering', 'cmd');
        $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
        $this->lists['title'] = ARequest::getUserStateFromRequest('title', '', 'string');
        $this->lists['tcat_id'] = ARequest::getUserStateFromRequest('tcat_id', '', 'int');
        $model->init($this->lists);
        $this->items = &$model->getData();
        $this->pagination = &$model->getPagination();
        $this->params = &JComponentHelper::getParams(OPTION);
        $this->selectable = JRequest::getCmd('task') == 'element';
        $this->turnOnOrdering = ($this->lists['order'] == 'ordering');
        $this->assignRef("duration", $this->getDurationBox($this->lists['duration']));
        $this->assign("category", $this->getCategoryBox($this->lists['tcat_id']));
        parent::display($tpl);
    }

    function getDurationBox($select) {

        return JHtmlSelect::integerlist(1, 20, 1, 'days');
    }

    function getCategoryBox($select) {
        $model = new BookProModelCategories();
        $lists = array('type' => TOUR);
        $model->init($lists);
        $items = $model->getData();
        return AHtml::getFilterSelect('tcat_id', JText::_('COM_BOOKPRO_SELECT_CATEGORY'), $items, $select, true, '', 'id', 'title');
    }

    function getCountrySelectBox($select, $field = 'country_id', $autoSubmit = false) {
        $model = BookProHelper::getCountryModel();
        $lists = array('limit' => null, 'limitstart' => null, 'state' => null, 'access' => null, 'order' => 'ordering', 'order_Dir' => 'ASC', 'search' => null);
        $model->init($lists);
        $fullList = $model->getFullList();

        return AHtml::getFilterSelect($field, JText::_("COM_BOOKPRO_SELECT_COUNTRY"), $fullList, $select, $autoSubmit, '', 'value', 'text');
    }

}

?>