<?php

    /**
    * @package 	Bookpro
    * @author 		Nguyen Dinh Cuong
    * @link 		http://ibookingonline.com
    * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
    * @version 	$Id: view.html.php 84 2012-08-17 07:16:08Z quannv $
    **/
    defined('_JEXEC') or die('Restricted access');
    AImporter::model('airports','country','tours');

    class BookProViewCountry extends JViewLegacy
    {

        function display($tpl = null)
        {
           
            $app=&JFactory::getApplication();
            $document = &JFactory::getDocument();
            $input=$app->input;
            $menu = &JSite::getMenu();
            $active = $menu->getActive();
            $country_id=$input->getInt('country_id');
            $cmodel=new BookProModelCountry();
            $country=$cmodel->getItem($country_id);
            $tourModel=new BookProModelTours();
            $tourModel->init(array('state'=>1,'country_id'=>$country_id));
            
            $this->tours=$tourModel->getData();
            
            $dealModel = new BookProModelTours();
            $lists = array('state'=>1,'country_id'=>$country_id,'featured'=>1);
            $dealModel->init($lists);
            $bestdeals = $dealModel->getData();
            ///
            $model=new BookProModelAirports();
            if($active) {
                $this->products_per_row=$active->params->get('products_per_row',2);
                $this->count=$active->params->get('count',8);
            }else{
                $this->products_per_row=2;
                $this->count=4;
            }
          
            $this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $this->count, 'int');
            $this->lists['state'] = 1;
            $this->lists['level'] = 1;
            $this->lists['country_id'] = $country_id;
            $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
            $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'title', 'cmd');
            $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');

            //$model->init($this->lists);
            AImporter::helper('airport');
            $this->destinations=AirportHelper::getFullList();
            $this->pagination=$model->getPagination();
            
            
           // $boxsort = $this->getBoxSort($this->lists['order']);
            //$this->assignRef('boxsort', $boxsort);
            //$boxsortdir = $this->getboxSortDir($this->lists['order_Dir']);
           // $this->assignRef('boxsortdir', $boxsortdir);
           $this->assignRef('bestdeals', $bestdeals);
            $this->_prepareDisplay();
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

?>