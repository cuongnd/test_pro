<?php 
/**
 * @package     Bookpro
 * @author         Nguyen Dinh Cuong
 * @link         http://ibookingonline.com
 * @copyright     Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version     $Id: form_image.php 26 2012-07-08 16:07:54Z quannv $
 **/    
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.folder');

$bar = &JToolBar::getInstance('toolbar_image');
$bar->addButtonPath(JPATH_COMPONENT_BACK_END . DS . 'helpers' . DS . 'toolbar');

/* @var $bar JToolBar */
$bar->appendButton('Popup', 'new', 'Add', ARoute::safeURL(ARoute::view(VIEW_IMAGES, null, null, array('tmpl' => 'component', 'type' => AIMAGES_TYPE_ONE))), 800, 400);
$bar->appendButton('ALink', 'delete', 'Delete', 'AImages.removeMain()', 'imageMainRemove');

echo $bar->render();
?>
<div class="clr"></div>
<div class="<?php echo $this->fieldname; ?>">
    <img src="<?php echo $thumb = AImage::thumb(BookProHelper::getIPath($this->obj->{$this->fieldname}), null, ADMIN_SET_IMAGES_WIDTH); ?>" alt="" id="imageMainSource" class="thumb<?php echo $thumb ? '' : ' blind'; ?>" />
    <?php
        if (! $thumb)
            ADocument::addDomreadyEvent('AImages.hideRemoveMain()'); 
    ?>    
</div>
<input type="hidden" name="<?php echo $this->fieldname;?>" id="imageMainHidden" value="<?php echo $this->escape($this->obj->{$this->fieldname}); ?>" />
<input type="hidden" name="addimg" id="imageMainHidden" value="<?php echo $this->escape($this->fieldname); ?>" />

