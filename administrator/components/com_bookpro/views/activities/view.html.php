<?php


    defined('_JEXEC') or die;
    AImporter::helper('route','request');   
    class BookproViewActivities extends BookproJViewLegacy
    {
        protected $items;
        protected $pagination;
        protected $state;
        var $lists;


        /**
        * (non-PHPdoc)
        * @see JViewLegacy::display()
        */
        public function display($tpl = null)
        {
        	
            $this->items		 = $this->get('Items');
            $this->pagination	 = $this->get('Pagination');
            
            //echo '<pre>';var_dump($this->pagination);
            
            $this->state		 = $this->get('State');  
            $this->sortDirection =  $this -> state->get('list.direction');
            $this->sortColumn    =  $this -> state->get('list.ordering');
            $this->addToolbar();
            parent::display($tpl);
        }

        /**
        * Add the page title and toolbar.
        *
        * @since	1.6
        */
        protected function addToolbar()
        {
            
            $bar = JToolBar::getInstance('toolbar');
            JToolbarHelper::title(JText::_('COM_BOOKRPO_MANAGER_ACTIVITY'), 'weblinks.png');
            JToolbarHelper::addNew('activity.add');
            JToolbarHelper::editList('activity.edit');
            JToolbarHelper::divider();
            JToolbarHelper::publish('activities.publish', 'Publish', true);
            JToolbarHelper::unpublish('activities.unpublish', 'UnPublish', true);
            JToolbarHelper::divider();
            JToolbarHelper::deleteList('', 'activities.delete');
        }

        protected function getSortFields()
        {
            return array(
                'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
                'a.state' => JText::_('JSTATUS'),
                'a.title' => JText::_('JGLOBAL_TITLE'),
                'a.id' => JText::_('JGRID_HEADING_ID')
            );
        }
    }
