<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	22 March 2012
 * @file name	:	views/user/tmpl/editpicture.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Edit profile picture (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework');

 $doc =& JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/utility.js");
 $doc->addScript("components/com_jblance/js/upclick-min.js"); 
 $doc->addScript("components/com_jblance/js/ysr-crop.js"); 

 $user=& JFactory::getUser();
 $model = $this->getModel();

?>
<script language="javascript" type="text/javascript">
<!--
	window.addEvent('domready', function(){
		createUploadButton('<?php echo $this->row->user_id; ?>', 'user.uploadpicture');
	});
//-->
</script>

<form action="index.php" method="post" name="profilePicture" class="form-validate" onsubmit="return validateForm(this);">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PROFILE_PICTURE'); ?></div>
	<?php
	include_once(JPATH_COMPONENT.'/views/profilemenu.php');
	?>
	<div id="divpicture"><?php echo JblanceHelper::getLogo($this->row->user_id); ?></div>
	<div class="sp10">&nbsp;</div>
	<div id="ajax-container"></div>
	<input type="button" id="photoupload" value="<?php echo JText::_('COM_JBLANCE_UPLOAD_NEW'); ?>" class="button">
	<input type="button" id="removepicture" value="<?php echo JText::_('COM_JBLANCE_REMOVE_PICTURE'); ?>" onclick="removePicture('<?php echo $this->row->user_id; ?>', 'user.removepicture');" class="button" >

	<input type="hidden" name="option" value="com_jblance">
	<input type="hidden" name="task" value="">
	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $Itemid; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>	

<form action="index.php" method="post" name="profileThumbnail" class="form-validate" onsubmit="return validateForm(this);">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_THUMBNAIL'); ?></div>
	<div class="fl">
		<div id="divthumb"><?php echo JblanceHelper::getThumbnail($this->row->user_id); ?></div>
		<?php if($this->row->picture) : ?>
		<p>
			<a href="javascript:updateThumbnail('user.croppicture')" id="update-thumbnail"><?php echo JText::_('COM_JBLANCE_EDIT_THUMBNAIL'); ?></a>
		</p>
		<?php endif; ?>
	</div>
	
	<!-- show the edit thumbnail if the user has attached any picture -->
	
	<div id="editthumb" style="position:relative; left:200px; float:left; display:none; ">
	<?php if($this->row->picture) : ?>
		<?php 
		//get image size
		$imgLoc = JBPROFILE_PIC_PATH.'/'.$this->row->picture;
		$fileAtr = getimagesize($imgLoc);
		$width = $fileAtr[0];
		$height = $fileAtr[1];
		?>
		<div id="imgouter">
		    <div id="cropframe" style="background-image: url('<?php echo JBPROFILE_PIC_URL.$this->row->picture; ?>')">
		        <div id="draghandle"></div>
		        <div id="resizeHandleXY" class="resizeHandle"></div>
		        <div id="cropinfo" rel="Click to crop">
		            <div title="Click to crop" id="cropbtn"></div>
		            <!--<div id="cropdims"></div>-->
		        </div>
		    </div>
		    <div id="imglayer" style="width: <?=$width; ?>px; height: <?=$height ?>px; background-image: url('<?php echo JBPROFILE_PIC_URL.$this->row->picture?>')">
		    </div>
		</div>
		<div id="tmb-container"></div>
		<input type="hidden" id="imgname" name="imgname" value="<?php echo $this->row->picture; ?>">
		<input type="hidden" id="tmbname" name="tmbname" value="<?php echo $this->row->thumb; ?>">
	<?php endif; ?>
	</div>
	<div style="clear:both;"></div>
	<input type="hidden" name="option" value="com_jblance">
	<input type="hidden" name="task" value="">
	<?php echo JHTML::_('form.token'); ?>
</form>	
