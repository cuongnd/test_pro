<?php
defined('_JEXEC') or die('Restricted access');

/* @var $this JView */
AImporter::helper('document');
$bar = &JToolBar::getInstance('toolbar_images');
/* @var $bar JToolBar */

require_once  JPATH_COMPONENT_FRONT_END.'/helpers/toolbar/apopup.php';
require_once JPATH_COMPONENT_FRONT_END.'/helpers/toolbar/alink.php';
$bar->appendButton('APopup', 'new', 'Add', ARoute::safeURL(ARoute::view(VIEW_IMAGES, null, null, array('tmpl' => 'component', 'type' => AIMAGES_TYPE_MORE))), 800, 500);
$bar->appendButton('ALink', 'delete', 'Delete', 'AImages.removeGallery()', 'imagesGalleryRemove');
$bar->appendButton('ALink', 'star', 'Default', 'javascript:AImages.setDefault()', 'imagesGalleryDefault');
$bar->appendButton('ALink', 'publish', 'Check All', 'javascript:AImages.checkAll(this, false, true)', 'imagesGalleryCheckAll');
$bar->appendButton('ALink', 'unpublish', 'Uncheck All', 'javascript:AImages.checkAll(this, false, false)', 'imagesGalleryUnCheckAll');
ADocument::addDomreadyEvent('AImages.updateGalleryToolbar(false)');
?>

<?php echo $bar->render(); ?>
<div class="clr"></div>
<div id="images">
	<?php 
		foreach (BookproHelper::getSubjectImages($this->obj) as $image) {
			if (($thumb = AImage::thumb(BookproHelper::getIPath($image), null, ADMIN_SET_IMAGES_WIDTH))) {
	?>
				<img src="<?php echo $thumb; ?>" class="thumb pointer<?php if ($this->obj->image == $image) { ?> thumbDefault<?php } ?>" alt="" id="imageGallerySource<?php echo ($id = AImage::getId($image)); ?>" onclick="AImages.mark(<?php echo $id; ?>,false)" />
				<input type="hidden" name="images[]" value="<?php echo $this->escape($image); ?>" id="imageGalleryHidden<?php echo $id; ?>" />
	<?php		
			}
		}
	?>	
</div>
<input type="hidden" name="image" id="image" value="<?php echo $this->obj->image; ?>" />