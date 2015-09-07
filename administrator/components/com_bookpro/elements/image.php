<?php

/**
 * Popup element to select destination.
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: destination.php 44 2012-07-12 08:05:38Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.parameter.element');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
AImporter::js('view-images');

class JFormFieldImage extends JFormFieldList
{
	
	protected function getInput() {
		
		jimport('joomla.filesystem.folder');
		
		$bar = &JToolBar::getInstance('toolbar_image');
		$bar->addButtonPath(JPATH_COMPONENT_BACK_END . DS . 'helpers' . DS . 'toolbar');
		
		/* @var $bar JToolBar */
		$bar->appendButton('Popup', 'new1', 'Add', (ARoute::view(VIEW_IMAGES, null, null, array('tmpl' => 'component', 'type' => '1'))), 800, 600);
		$bar->appendButton('ALink', 'delete', 'Delete', 'javascript:AImages.removeMain()', 'imageMainRemove');
		
		$html[]= $bar->render();
		$thumb=AImage::thumb(BookProHelper::getIPath($this->image), null, ADMIN_SET_IMAGES_WIDTH);
		$html[]= '<img src="'.$thumb.'" alt="" id="imageMainSource" class="thumb'.$thumb ? '' : ' blind'.'" />';
			if (! $thumb)
				$html[]=ADocument::addDomreadyEvent('AImages.hideRemoveMain()'); 
		$html[]='<input type="hidden" name="image" id="imageMainHidden" value="'. $this->escape($this->image).'" />';
		return implode("\n", $html);
	}

}
?>
