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

	AImporter::model('airport','tours');
    class BookProViewDestination extends JViewLegacy
    {

        function display($tpl = null)
        {
            $mainframe = JFactory::getApplication();
            $document = &JFactory::getDocument();
            $input=$mainframe->input;
            $dest_id=$input->getInt('dest_id');
            $cmodel=new BookProModelAirport();
            $cmodel->setId($dest_id);
            $this->dest=$cmodel->getObjectFull();
            
            //load popular tour in city
            $tourModel=new BookProModelTours();
            $tourModel->init(array('state'=>1,'dest_id'=>$dest_id));
            $this->tours=$tourModel->getData();
            ///
            $dealModel = new BookProModelTours();
            $lists = array('state'=>1,'dest_id'=>$dest_id,'featured'=>1);
            $dealModel->init($lists);
            $bestdeals = $dealModel->getData();
            $this->assignRef('bestdeals', $bestdeals);
            $this->_prepareDisplay();
            parent::display();             
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