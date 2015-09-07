<?php 

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 26 2012-07-08 16:07:54Z quannv $
 **/  
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_FRONT_END.'/helpers/toolbar/alink.php';
require_once JPATH_COMPONENT_FRONT_END.'/helpers/toolbar/apopup.php';
AImporter::helper('document','bookpro','request');
AImporter::js('common');	
AImporter::css('general');

$action = ARequest::getUserStateFromRequest('action', UPLOAD_IMAGE_CLOSE_SET, 'int');

$type = JRequest::getVar('type', 1);

$filter = ARequest::getUserStateFromRequest('filter', '', 'string');


$testFilter = JString::trim($filter);
$testFilter = JString::strtolower($testFilter);

$mainframe = &JFactory::getApplication();
/* @var $mainframe JApplication */

if ($type == AIMAGES_TYPE_MORE)
	ADocument::addDomreadyEvent('AImages.init();');

ADocument::addScriptPropertyDeclaration('selectImage', JText::_('Select image', true));
	
$bar = &JToolBar::getInstance('toolbar_images_default');
/* @var $bar JToolBar */
$bar->appendButton('ALink', 'upload', 'Upload', 'AImages.upload()');
$bar->appendButton('ALink', 'save', 'Save', 'AImages.' . ($function = $type == AIMAGES_TYPE_ONE ? 'setMain' : 'setGallery') . '(true)');
$bar->appendButton('ALink', 'apply', 'Apply', 'AImages.' . $function . '(false)');
$bar->appendButton('ALink', 'delete', 'Delete', 'AImages.remove()');
$bar->appendButton('ALink', 'cancel', 'Cancel', 'AImages.close()');

$bar->render();


?>
<div id="imageBrowse">
	<form method="post" action="index.php" enctype="multipart/form-data" name="adminForm" id="adminForm">
		<fieldset>
			<legend><?php echo JText::_('TOOLS'); ?></legend>
			<div class="leftToolbar">
				<table>
					<tr>
						<td>
							<label for="image"><?php echo JText::_('UPLOAD'); ?></label>
						</td>
						<td>
							<input type="file" name="image" id="image" accept="image/jpeg,image/png,image/pjpeg,image/gif" />
						</td>
					</tr>
					<tr>
						<td>
							<label for="filter"><?php echo JText::_('Filter'); ?></label>
						</td>
						<td class="imagesFilter">
							<input type="text" name="filter" id="filter" value="<?php echo $this->escape($filter); ?>" onchange="document.adminForm.submit()"/>
							<button onclick="AImages.submit('')"><?php echo JText::_('OK'); ?></button>
							<button onclick="AImages.reset()"><?php echo JText::_('RESET'); ?></button>
							<input type="checkbox" class="inputCheckbox" name="checkAll" id="checkAll" value="1" onclick="AImages.checkAll(this, true)" />
							<label for="checkAll" class="checkAll"><?php echo JText::_('Check all'); ?></label>
						</td>
					</tr>
					<tr>
						<td>
							<label for="dirname"><?php echo JText::_('New Directory'); ?></label>
						</td>
						<td class="imagesFilter">
							<input type="text" name="dirname" id="dirname" value="" />
							<button onclick="AImages.mkdir()"><?php echo JText::_('OK'); ?></button>
						</td>
					</tr>
				</table>
			</div>
			<div class="rigthToolbar"><?php echo $bar->render(); ?></div>
		</fieldset>
		<?php 
		
			$ipath = &BookproHelper::getIPath();
			$ipath = JPath::clean($ipath . DS . $this->dir);
			
			$images = JFolder::files($ipath, '.' ,false , false, array('.svn', 'CVS', 'index.html'));
			$dirs = JFolder::folders($ipath, '.', false, false);
			
			$total = $total2 = count($images);
			$total3 = $total4 = count($dirs);
			
			for ($i = 0; $i < $total; $i++) {		
				if(realpath($ipath . DS . $images[$i]))		
					if (getimagesize(realpath($ipath . DS . $images[$i])) === false)
						unset($images[$i]);
			}
			
			if ($total2 != ($total = (count($images)))) {
				$images = array_merge($images);
			}
			
			$haveImages = $total != 0;
			
			if ($testFilter){
				if ($haveImages) {
					$total2 = $total;
					for ($i = 0; $i < $total; $i++)
						if (JString::strpos(JString::strtolower($images[$i]), $testFilter) === false)
							unset($images[$i]);
					if ($total2 != ($total = count($images)))
						$images = array_merge($images);
				}
				foreach ($dirs as $i => $dir)
					if (JString::strpos(JString::strtolower($dir), $testFilter) === false)
						unset($dirs[$i]);
				if ($total3 != ($total4 = count($dirs)))
					$dirs = array_merge($dirs);
			}
	    
			if ($haveImages && $testFilter && ! $total) {
				$msg = 'Not found any images by your filter.';
			} elseif (! $haveImages) {
				$msg = 'Any images uploaded. To upload images click on browse button.';
			} else {
				$msg = 'Click on images to select and click on button save set and close or apply only set. You can upload images new too.';
			}
			
			$mainframe->enqueueMessage(JText::_($msg), 'message');
			
		?>	
			
				<a href="javascript:AImages.changeDir('')" title=""><?php echo JText::_('ROOT'); ?></a>
		
		<?php	
			$beforeParts = array();
			foreach (explode(DS, $this->dir) as $part) {
				if (($part = JString::trim($part))) {
					$beforeParts[] = $part;
		?>
				
					/ <a href="javascript:AImages.changeDir('<?php echo $this->escape(JPath::clean(implode(DS, $beforeParts))); ?>')" title=""><?php echo $part; ?></a>
		<?php
				}
			}	
			
			if ($total || $total4) { 
			
		?>
			
			<fieldset id="images">
				<legend><?php echo JText::_('AVAILABLE_IMAGES'); ?></legend>
				<?php
				
					$filter = new stdClass();
					
					$filter->limit = ARequest::getUserStateFromRequest('limit', 10, 'int');
					$filter->limitstart = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
					$filter->total = $total + $total4;

					AModel::checkBrowseFilter($filter);					
					$pagination = new JPagination($filter->total, $filter->limitstart, $filter->limit);
					
					for ($i = $filter->limitstart; $i < $filter->count; $i++) {
						if (isset($dirs[$i])) {
							$filter->limitstart ++;
							$dir = $dirs[$i];
				?>
								<a class="dir" href="javascript:AImages.changeDir('<?php echo $this->escape(JPath::clean($this->dir . DS . $dir)); ?>')" title=""><?php echo $dir; ?></a>
				<?php
						}
					}
					$count = $filter->count - $total4;
					for ($i = ($filter->limitstart - $total4); $i < $count; $i++) {
						$image = $images[$i];
						$thumb = AImage::thumb(JPath::clean($ipath . DS . $image), null, ADMIN_SET_IMAGES_WIDTH);
						$id = AImage::getId($this->dir . DS . $image);
						$image = $this->escape($image);
						if ($thumb) { 
				?>
							<img src="<?php echo $thumb; ?>" alt="" title="<?php echo $image; ?>" class="thumb pointer" id="imageBrowserSource<?php echo $id; ?>" onclick="AImages.mark(<?php echo $id; ?>,true)" />
							<input type="hidden" name="images[]" id="imageBrowserHidden<?php echo $id; ?>" value="<?php echo JPath::clean($this->dir . DS . $image); ?>" />
				<?php 
						}
					} 
				?>
				<div class="listing">
	    			<?php echo $pagination->getListFooter(); ?>
	    			<div class="clr"></div>
	    		</div>
			</fieldset>
		<?php } ?>
		<input type="hidden" name="option" value="<?php echo OPTION; ?>" />
		<input type="hidden" name="view" value="images" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="tmpl" value="component" />
		<input type="hidden" name="type" value="<?php echo $type; ?>" />
		<input type="hidden" name="dir" value="<?php echo $this->escape($this->dir); ?>" />
	</form>
</div>