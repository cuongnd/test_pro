<?php
    /**
    * @package 	Bookpro
    * @author 		Nguyen Dinh Cuong
    * @link 		http://ibookingonline.com
    * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
    * @version 	$Id: view.html.php 32 2012-07-10 16:53:30Z quannv $
    **/
    // No direct access to this file
    defined('_JEXEC') or die('Restricted access');
    // import Joomla view library
    jimport('joomla.application.component.view');
    AImporter::model('airports');
    AImporter::helper('image');

    class BookProViewDestinations extends JViewLegacy
    {
        // Overwriting JView display method
        var $pagination;
        var $lists;
        function display($tpl = null)
        {
			$model=new BookProModelAirports();
            $app=&JFactory::getApplication();
            $document = &JFactory::getDocument();
            $menu = &JSite::getMenu();
            $active = $menu->getActive();
            if($active) {
                $this->products_per_row=$active->params->get('products_per_row',2);
                $this->count=$active->params->get('count',8);
            }else{
                $this->products_per_row=2;
                $this->count=4;
            }
            
            $this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $this->count, 'int');
            $this->lists['state'] = 1;
            $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
            $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'title', 'cmd');
            $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');

            $model->init($this->lists);
            $this->destinations=$model->getData();
            $this->pagination=$model->getPagination();
            $boxsort = $this->getBoxSort($this->lists['order']);
            $this->assignRef('boxsort', $boxsort);
            $boxsortdir = $this->getboxSortDir($this->lists['order_Dir']);
            $this->assignRef('boxsortdir', $boxsortdir);
            $this->_prepareDocument();
            parent::display($tpl);
        }
        protected function _prepareDocument() {
            $document=JFactory::getDocument();
            $menuitemid = JRequest::getInt( 'Itemid' );
            if ($menuitemid)
            {
                $menu = JSite::getMenu();
                $menuparams = $menu->getParams( $menuitemid );
                $document->setDescription($menuparams->get('menu-meta_description'));
                $document->setMetadata('keywords', $menuparams->get('menu-meta_keywords'));

            }

        }
        function getBoxSort($selected){
            $orders = array('rank'=>JText::_('Rank'),'title'=>JText::_('Hotel Name'),'price'=>JText::_('Price'));
            $options = array();
            foreach ($orders as $value=>$text){
                $options[] = JHtmlSelect::option($value,$text);
            }
            $select=JHtml::_('select.genericlist',$options,'order','class="pull-left input-medium" onchange="document.frontForm.submit()"','value','text',$selected);
            return $select;
        }
        function getboxSortDir($selected){
            $orders = array('ASC'=>JText::_('Ascending'),'DESC'=>JText::_('Descending'));
            $options = array();
            foreach ($orders as $value=>$text){
                $options[] = JHtmlSelect::option($value,$text);
            }
            $select=JHtml::_('select.genericlist',$options,'order_Dir','class="pull-left input-medium" onchange="document.frontForm.submit()"','value','text',$selected);
            return $select;
        }
    }
