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

	AImporter::model('airport','flights');
    class BookProViewRouteFlight extends JViewLegacy
    {

        function display($tpl = null)
        {
            $mainframe = JFactory::getApplication();
            $document = &JFactory::getDocument();
            $input=$mainframe->input;
            $dest_from=$input->getInt('desfrom');
            $dest_to=$input->getInt('desto');
            
            
            
            
            //load popular tour in city
           
            AImporter::helper('flight');
            
            
            
            ///
            
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