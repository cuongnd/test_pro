<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 47 2012-07-13 09:43:14Z quannv $
 **/


defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');

//import needed JoomLIB helpers
AImporter::helper('route', 'bookpro', 'request');
AImporter::model('customers');
//import needed assets
AImporter::js('view-customers');
//import custom icons
AHtmlFrontEnd::importIcons();


class BookProViewAjaxCustomtrip extends JViewLegacy
{
    var $total;
	
    function display($tpl = null)
    {
       
        
        parent::display($tpl);
    }
    /*
     * Function load typeGroup
     * 
     */
    function  loadTypeGroup($name = 'passenger',$id = 'passenger'){
    		AImporter::model('cgroups');
    		$model = new BookProModelCGroups();
    		$lists = array('state'=>1);
    		$model->init($lists);
    		$lists = $model->getData();
    		return JHtmlSelect::genericlist($lists, $name,'class=input-small','id','title',$id);
    }
}
?>