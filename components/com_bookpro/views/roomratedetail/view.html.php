<?php

    /**
    * @package 	Bookpro
    * @author 		Nguyen Dinh Cuong
    * @link 		http://ibookingonline.com
    * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
    * @version 	$Id: view.html.php 26 2012-07-08 16:07:54Z quannv $
    **/

    defined('_JEXEC') or die('Restricted access');
    
    //import needed Joomla! libraries
    jimport('joomla.application.component.view');

    //import needed JoomLIB helpers
    AImporter::helper('route', 'bookpro', 'request');
    AImporter::model('hotels','roomratedetails','room','rooms');
    
    class BookProViewRoomRateDetail extends JViewLegacy
    {
        function display($tpl = null)
        {                    
            parent::display($tpl);
        }                                                             
    }

?>