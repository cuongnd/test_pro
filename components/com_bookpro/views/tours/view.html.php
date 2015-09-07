<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 43 2012-07-12 05:22:13Z quannv $
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
AImporter::model('tours');
AImporter::helper('image','tour');
if (! defined('SESSION_PREFIX')) {
	define('SESSION_PREFIX', 'bookpro_site_tours_list_');
}

class BookProViewTours extends JViewLegacy
{
	var $document;
	var $_layout;
	var $creteria="";
	
	function display($tpl = null)
	{
		$this->document=JFactory::getDocument();
		$app	= JFactory::getApplication();
		$menu = JSite::getMenu();
		$active = $menu->getActive();
		$this->products_per_row=2;
		$this->count=8;
		if($active) {
			if($active->params->get('products_per_row'))
				$this->products_per_row=$active->params->get('products_per_row');
			$this->count=$active->params->get('count');
			$this->showheading=$active->params->get('showheading');
			$this->_layout=$active->params->get('listlayout');
		}
		$model=new BookProModelTours();
		
		$this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $this->count, 'int');
		$this->lists['state'] = 1;
		$this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
		$this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'ordering', 'cmd');
		$this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
		if(is_null($this->tours)){
			$this->lists['duration']= JRequest::getInt('duration',null);
			$this->lists['dest_id']= JRequest::getInt('dest_id',null);
			$this->lists['cat_id']= JRequest::getInt('cat_id',null);
			$model->init($this->lists);
			$this->tours=$model->getData();
			$this->pagination = &$model->getPagination();
		}
		
		if($this->lists['dest_id']){
			
			AImporter::model('airport');

			$dmodel=new BookProModelAirport();
			$d=$dmodel->getObject($this->lists['dest_id']);
			$this->creteria = '<span class="label label-info">'. $d->title . '</span>';
		}
		
		if($this->lists['cat_id']){
			AImporter::model('category');
			$dmodel=new BookProModelCategory();
			$dmodel->setId($this->lists['cat_id']);
			$d=$dmodel->getObject();
			$this->creteria.= ',<span class="label label-info">'. $d->title . '</span>';
		}
		if($this->lists['duration']){
			AImporter::model('category');
			$dmodel=new BookProModelCategory();
			$dmodel->setId($this->lists['duration']);
			$d=$dmodel->getObject();
			$this->creteria.= ',<span class="label label-info">'. $d->title . '</span>';
		}
		
		$this->_prepareDisplay();
		$this->setLayout($this->_layout);
		parent::display($tpl);
	}
	protected function  _prepareDisplay(){
		$menuitemid = JRequest::getInt( 'Itemid' );
		if ($menuitemid)
		{
			$menu = JSite::getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$this->document->setDescription($menuparams->get('menu-meta_description'));
			$this->document->setMetadata('keywords', $menuparams->get('menu-meta_keywords'));
		
		}
	}
}
