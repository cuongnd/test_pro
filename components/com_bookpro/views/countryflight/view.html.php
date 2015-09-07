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
    AImporter::model('destinations','country','flights','airlines');

    class BookProViewCountryFlight extends JViewLegacy
    {

        function display($tpl = null)
        {
        	
            $app=&JFactory::getApplication();
            $document = &JFactory::getDocument();
            $input=$app->input;
            $menu = &JSite::getMenu();
            $active = $menu->getActive();
            $country_id=$input->getInt('country_id');
            
            $tourModel=new BookProModelFlights();
            $tourModel->init(array('state'=>1,'country_id'=>$country_id));
          
            $this->flights=$tourModel->getData();
            
            $model=new BookProModelDestinations();
            
            
           	$state=$model->getState();
    		$state->set('filter.air',1);
    		$state->set('filter.state', 1);
    		
    		
    		$dealModel = new BookProModelFlights();
    		$lists = array('state'=>1,'country_id'=>$country_id,'featured'=>1);
    		$dealModel->init($lists);
    		$this->bestdeals = $dealModel->getData();
            $this->destinations = $model->getFullList();
            
            //$model->init($this->lists);
            
            $this->pagination=$model->getPagination();
            $countryModel = new BookProModelCountry();
            $this->country = $countryModel->getObjectById($country_id);
            
            $airlineModel = new BookProModelAirlines();
            $astate=$airlineModel->getState();
           
           $astate->set('filter.country_id', $country_id);
            $this->airlines = $airlineModel->getItems();
            
            $this->country->airlines = $this->airlines;
           // $boxsort = $this->getBoxSort($this->lists['order']);
            //$this->assignRef('boxsort', $boxsort);
            //$boxsortdir = $this->getboxSortDir($this->lists['order_Dir']);
           // $this->assignRef('boxsortdir', $boxsortdir);
            
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