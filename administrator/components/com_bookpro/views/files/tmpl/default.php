<?php 


defined('_JEXEC') or die('Restricted access');

/* @var $this JView */
	
$action = ARequest::getUserStateFromRequest('action', UPLOAD_IMAGE_CLOSE_SET, 'int');

$type = ARequest::getUserStateFromRequest('type', AFILES_TYPE_ONE, 'int');

$filter = ARequest::getUserStateFromRequest('filter', '', 'string');

$testFilter = JString::trim($filter);
$testFilter = JString::strtolower($testFilter);

$mainframe = &JFactory::getApplication();
/* @var $mainframe JApplication */

if ($type == AFILES_TYPE_MORE)
	ADocument::addDomreadyEvent('AFiles.init();');

ADocument::addScriptPropertyDeclaration('selectFile', JText::_('SELECT_FILE', true));
	
$bar = &JToolBar::getInstance('toolbar_files_default');
/* @var $bar JToolBar */
$bar->appendButton('ALink', 'upload', 'Upload', 'AFiles.upload()');
$bar->appendButton('ALink', 'save', 'Save', 'AFiles.' . ($function = $type == AFILES_TYPE_ONE ? 'setMain' : 'setGallery') . '(true)');
$bar->appendButton('ALink', 'apply', 'Apply', 'AFiles.' . $function . '(false)');
$bar->appendButton('ALink', 'delete', 'Delete', 'AFiles.remove()');
$bar->appendButton('ALink', 'cancel', 'Cancel', 'AFiles.close()');

?>
<div id="fileBrowse">
	<form method="post" action="index.php" enctype="multipart/form-data" name="adminForm" id="adminForm">
		<fieldset>
			<legend><?php echo JText::_('TOOLS'); ?></legend>
			<div class="leftToolbar">
				<table width="100%">
					<tr>
						<td>
							<label for="file"><?php echo JText::_('UPLOAD'); ?></label>
						</td>
						<td>
							<input type="file" name="file" id="file" accept="" />
						</td>
					</tr>
					<tr>
						<td>
							<label for="filter"><?php echo JText::_('FILTER'); ?></label>
						</td>
						<td class="filesFilter">
							<input type="text" name="filter" id="filter" value="<?php echo $this->escape($filter); ?>" onchange="document.adminForm.submit()"/>
							<button onclick="AFiles.submit('')"><?php echo JText::_('OK'); ?></button>
							<button onclick="AFiles.reset()"><?php echo JText::_('RESET'); ?></button>
							<input type="checkbox" class="inputCheckbox" name="checkAll" id="checkAll" value="1" onclick="AFiles.checkAll(this, true)" />
							<label for="checkAll" class="checkAll" style="display:inline;"><?php echo JText::_('COM_BOOKPRO_CHECK_ALL'); ?></label>
						</td>
					</tr>
					<tr>
						<td>
							<label for="dirname"><?php echo JText::_('COM_BOOKPRO_NEW_DIRECTORY'); ?></label>
						</td>
						<td class="filesFilter">
							<input type="text" name="dirname" id="dirname" value="" />
							<button onclick="AFiles.mkdir()"><?php echo JText::_('OK'); ?></button>
						</td>
					</tr>
				</table>
			</div>
			<div class="rigthToolbar"><?php echo $bar->render(); ?></div>
		</fieldset>
		<?php 
		
			$fpath = Afile::getFPath();
			$fpath = JPath::clean($fpath . DS . $this->dir);
			
			$files = JFolder::files($fpath, '.' ,false , false, array('.svn', 'CVS', 'index.html'));
			$dirs = JFolder::folders($fpath, '.', false, false);

			$total = $total2 = count($files);
			$total3 = $total4 = count($dirs);
			
			/*
			for ($i = 0; $i < $total; $i++) {				
				if (getimagesize(realpath($fpath . DS . $files[$i])) === false)
					unset($files[$i]);
			}
			*/
			
			if ($total2 != ($total = (count($files)))) {
				$files = array_merge($files);
			}

			$haveFiles = $total != 0;
			
			if ($testFilter){
				if ($haveFiles) {
					$total2 = $total;
					for ($i = 0; $i < $total; $i++)
						if (JString::strpos(JString::strtolower($files[$i]), $testFilter) === false)
							unset($files[$i]);
					if ($total2 != ($total = count($files)))
						$files = array_merge($files);
				}
				foreach ($dirs as $i => $dir)
					if (JString::strpos(JString::strtolower($dir), $testFilter) === false)
						unset($dirs[$i]);
				if ($total3 != ($total4 = count($dirs)))
					$dirs = array_merge($dirs);
			}

			if ($haveFiles && $testFilter && ! $total) {
				$msg = 'Not found any files by your filter.';
			} elseif (! $haveFiles) {
				$msg = 'Any filed uploaded. To upload files click on browse button.';
			} else {
				$msg = 'Click on files to select and click on button save set and close or apply only set. You can upload files new too.';
			}
			
			$mainframe->enqueueMessage(JText::_($msg), 'message');
			
		?>	
			
				<a href="javascript:AFiles.changeDir('')" title=""><?php echo JText::_('ROOT'); ?></a>
		
		<?php	
			$beforeParts = array();
			foreach (explode(DS, $this->dir) as $part) {
				if (($part = JString::trim($part))) {
					$beforeParts[] = $part;
		?>
				
					/ <a href="javascript:AFiles.changeDir('<?php echo str_replace('\\','\\\\',$this->escape(JPath::clean(implode(DS, $beforeParts)))); /* CUSTOMIZATION: ARTIO */?>')" title=""><?php echo $part; ?></a>
		<?php
				}
			}	
		?>

			<fieldset id="files">
			<?php if ($total || $total4) { ?>
				<legend><?php echo JText::_('COM_BOOKPRO_AVAILABLE_FILES'); ?></legend>
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
								<a class="dir" href="javascript:AFiles.changeDir('<?php echo str_replace('\\','\\\\',$this->escape(JPath::clean($this->dir . DS . $dir))); /* CUSTOMIZATION: ARTIO */ ?>')" title=""><?php echo $dir; ?></a>
				<?php
						}
					}

					$count = $filter->count - $total4;

					for ($i = ($filter->limitstart - $total4); $i < $count; $i++) {
						$file = $files[$i];
						$thumb=BookProHelper::getFileThumbnail($file);//document icons
						$file = $this->escape($file);
						$id = AFile::getId($this->dir . DS . $file);
				?>
						<div class="file pointer" id="fileBrowserSource<?php echo $id; ?>" onclick="AFiles.mark(<?php echo $id; ?>,true)" >
							<img src="<?php echo $thumb ?>" title="<?php echo $file; ?>" />
							<span class="filename"><?php echo $file?></span>
						</div>
						<input type="hidden" name="files[]" id="fileBrowserHidden<?php echo $id; ?>" value="<?php echo trim(JPath::clean($this->dir . DS . $file),' '.DS); ?>" />
						
				<?php 
					} 
				?>
				<div class="listing clr">
	    			<?php echo $pagination->getListFooter(); ?>
	    			<div class="clr"></div>
	    		</div>
	    		<?php } ?>
			</fieldset>
		
		<input type="hidden" name="option" value="<?php echo OPTION; ?>" />
		<input type="hidden" name="view" value="files" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="tmpl" value="component" />
		<input type="hidden" name="type" value="<?php echo $type; ?>" />
		<input type="hidden" name="dir" value="<?php echo $this->escape($this->dir); ?>" />
	</form>
</div>