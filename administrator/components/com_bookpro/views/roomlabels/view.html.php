<?php


defined('_JEXEC') or die;
AImporter::model('roomlabels');

if (!defined('SESSION_PREFIX')) {
	if (IS_ADMIN) {
		define('SESSION_PREFIX', 'bookpro_roomlabel_list_');
	}
}
class BookproViewRoomlabels extends BookproJViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * (non-PHPdoc)
	 * @see JViewLegacy::display()
	 */
	public function display($tpl = null)
	{
		
		$mainframe = &JFactory::getApplication();
		/* @var $mainframe JApplication */
		
		$document = &JFactory::getDocument();
		
		$model = new BookProModelRoomlabels();
		$this->lists = array();
        $this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
        $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
		$this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'id', 'cmd');
        $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
        $this->lists['title'] = ARequest::getUserStateFromRequest('title', null, 'string');
        
        $model->init($this->lists);
        $this->items = &$model->getData();
        $this->pagination = &$model->getPagination();
		$this->addToolbar();
		BookproHelper::setSubmenu('');
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('Roomlabels'), 'roomlabel');
		JToolbarHelper::addNew('roomlabel.add');
		JToolbarHelper::editList('roomlabel.edit');
		JToolbarHelper::divider();
		JToolbarHelper::publish('roomlabels.publish', 'Publish', true);
		JToolbarHelper::unpublish('roomlabels.unpublish', 'UnPublish', true);
		JToolbarHelper::divider();
		JToolbarHelper::deleteList('', 'roomlabels.delete');
	}
}
