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

AImporter::model('hotel');
//import needed JoomLIB helpers
AImporter::helper('bookpro','image');

class BookProViewShowImagesForHotel extends JViewLegacy {

	function display($tpl = null) {           
		$id = ARequest::getUserStateFromRequest('id', '', 'int');
	    $model = new BookProModelHotel();
        $model->setId($id);
        $hotel = $model->getObject();
		$this -> assign('hotel', $hotel);
		parent::display($tpl);
	}

}
?>