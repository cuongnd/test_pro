<?php 

/**
 * Main image template.
 * 
 * @version		$Id$
 * @package		ARTIO Booking
 * @subpackage  views
 * @copyright	Copyright (C) 2010 ARTIO s.r.o.. All rights reserved.
 * @author 		ARTIO s.r.o., http://www.artio.net
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link        http://www.artio.net Official website
*/

defined('_JEXEC') or die('Restricted access');
AImporter::helper('document');
/* @var $this JView */

$bar = &JToolBar::getInstance('toolbar_file');
$bar->addButtonPath(JPATH_COMPONENT_BACK_END . DS . 'helpers' . DS . 'toolbar');

/* @var $bar JToolBar */
$bar->appendButton('Popup', 'new', 'Add', ARoute::safeURL(ARoute::view(VIEW_FILES, null, null, array('tmpl' => 'component', 'type' => AFILES_TYPE_ONE))), 800, 400);
$bar->appendButton('ALink', 'delete', 'Delete', 'AFiles.removeMain()', 'fileMainRemove');

echo $bar->render();
?>
<div class="clr"></div>
<img src="<?php echo BookProHelper::getFileThumbnail($this->file); ?>" alt="" id="fileMainSource" class="thumb<?php echo $this->file ? '' : ' blind'; ?>" />
<?php
	if (! $thumb)
		ADocument::addDomreadyEvent('AFiles.hideRemoveMain()'); 
?>	
<input type="hidden" name="file" id="fileMainHidden" value="<?php echo $this->escape($this->file); ?>" />