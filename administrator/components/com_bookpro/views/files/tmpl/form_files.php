<?php

/**
 * Images gallery.
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

$bar = &JToolBar::getInstance('toolbar_files');
/* @var $bar JToolBar */
AImporter::helper('toolbar'.DS.'alink');
require_once  JPATH_COMPONENT_BACK_END.'/helpers/toolbar/apopup.php';
$bar->appendButton('APopup', 'new', 'Add', ARoute::safeURL(ARoute::view(VIEW_FILES, null, null, array('tmpl' => 'component', 'type' => AFILES_TYPE_MORE))), 800, 500);
$bar->appendButton('ALink', 'delete', 'Delete', 'AFiles.removeGallery()', 'filesGalleryRemove');

ADocument::addDomreadyEvent('AFiles.updateGalleryToolbar(false)');

echo $bar->render();
?>
<div id="filesGalleryCheckAll">
	<input type="checkbox" name="checkAllFilesGallery" id="checkAllFilesGallery" class="inputCheckbox" value="1" onclick="AFiles.checkAll(this, false)" />
	<label for="checkAllFilesGallery"><?php echo JText::_('COM_BOOKPRO_CHECK_ALL'); ?></label>
	<div class="clr"></div>			
</div>
<div class="clr"></div>
<div id="files">
	<?php 

		foreach (BookProHelper::getSubjectFiles($this->obj) as $file) {
			
			$id = AFile::getId($file->origname);
			
	?>
		<div class="file pointer" id="fileGallerySource<?php echo $id; ?>" onclick="AFiles.mark(<?php echo $id; ?>,false)">
			<img src="<?php echo BookproHelper::getFileThumbnail($file->origname); ?>" title="<?php echo $file->origname?>"  />
			<span class="filename"><?php echo $file->origname?></span>
		</div>
		<input type="hidden" name="files[]" value="<?php echo $this->escape($file->string); ?>" id="fileGalleryHidden<?php echo $id; ?>" />

	<?php		
		}
	?>	
	
</div>