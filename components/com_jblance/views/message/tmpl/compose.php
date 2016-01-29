<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	30 May 2012
 * @file name	:	views/message/tmpl/compose.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Inbox of Private Messages (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHTML::_('behavior.framework');
 JHTML::_('behavior.formvalidation');
 JHTML::_('behavior.tooltip');
 
 $doc =& JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/utility.js");
 $doc->addScript("components/com_jblance/js/upclick-min.js");
 $doc->addScript("components/com_jblance/js/Autocompleter.js");
 $doc->addStyleSheet("components/com_jblance/css/Autocompleter.css");
 
 $app  	 =& JFactory::getApplication();
 $config =& JblanceHelper::getConfig();
 
 $recUsername = $app->input->get('username', '', 'string');
 
 $js = "\nwindow.addEvent('domready', function() {";
 $js .= "\n\tnew Autocompleter.Request.JSON('recipient', 'index.php?option=com_jblance&task=message.getautocompleteusername', {";
 $js .= "\n'postVar': 'recipient',
 postData: {'fieldName': 'recipient'},
 'overflow': true,
 'selectMode': 'type-ahead'
 });
 });\n";
 //$doc->addScriptDeclaration($js);

?>
<script language="javascript" type="text/javascript">
<!--
	function validateForm(f){
		var valid = document.formvalidator.isValid(f);
		
		if(valid == true){
			f.check.value='<?php echo JSession::getFormToken(); ?>';//send token
	    }
	    else {
			alert('<?php echo JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'); ?>');
			return false;
	    }
		return true;
	}
	
	window.addEvent('domready', function() {
		new Autocompleter.Request.JSON('recipient', 'index.php?option=com_jblance&task=message.getautocompleteusername', {
			'postVar': 'recipient',
			 postData: {'recipient': 'recipient'},
			 'overflow': true,
			 'selectMode': 'type-ahead'
		});
	});
	
	window.addEvent('domready', function(){
		attachFile('uploadmessage', 'message.attachfile');
	});
//-->
</script>
<form action="index.php" method="post" name="userFormMessage" id="userFormMessage" class="form-validate" onsubmit="return validateForm(this);" enctype="multipart/form-data">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_COMPOSE'); ?></div>
	<table width="100%" cellpadding="0" cellspacing="0"	class="jbltable">
		<tr>
			<td class="key"><label for="recipient"><?php echo JText::_('COM_JBLANCE_TO'); ?>:</label>
			</td>
			<td>
				<input type="text" name="recipient" id="recipient" value="<?php echo $recUsername; ?>" class="inputbox required" onchange="checkUsername(this);" />
				<?php 
				$tipmsg = JText::_('COM_JBLANCE_TO').'::'.JText::_('COM_JBLANCE_RECIPIENT_WARNING');
				?>
				<img src="components/com_jblance/images/tooltip.png" class="hasTip" title="<?php echo $tipmsg; ?>"/>
				<div id="status_recipient" class="dis-inl-blk"></div>
			</td>
		</tr>
		<tr>
			<td class="key"><label for="subject"><?php echo JText::_('COM_JBLANCE_SUBJECT'); ?>:</label>
			</td>
			<td>
				<input class="inputbox required" type="text" name="subject" id="subject" size="48" maxlength="100" value="" />
			</td>
		</tr>
		<tr>
			<td class="key"><label for="message"><?php echo JText::_('COM_JBLANCE_MESSAGE'); ?>:</label>
			</td>
			<td>
				<textarea name="message" id="message" rows="8" cols="52" class="inputbox required"></textarea>
				<div id="ajax-container-uploadmessage"></div>
				<div id="file-attached-uploadmessage"></div>
				<input type="button" id="uploadmessage" value="<?php echo JText::_('COM_JBLANCE_ATTACH_FILE'); ?>" class="button">
				<?php 
				$tipmsg = JText::_('COM_JBLANCE_ATTACH_FILE').'::'.JText::_('COM_JBLANCE_ALLOWED_FILE_TYPES').' : '.$config->projectFileText.'<br>'.JText::_('COM_JBLANCE_MAXIMUM_FILE_SIZE').' : '.$config->projectMaxsize.' kB';
				?>
				<img src="components/com_jblance/images/tooltip.png" class="hasTip" title="<?php echo $tipmsg; ?>"/>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input class="button" type="submit" name="submit" id="submit" size="32" maxlength="100" value="<?php echo JText::_('COM_JBLANCE_SEND'); ?>" />
				<input type="button" value="<?php echo JText::_('COM_JBLANCE_BACK'); ?>" onclick="javascript:history.back();" class="button" />
			</td>
		</tr>
	</table>
	
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="message.sendcompose" />	
	<input type="hidden" name="id" value="0" />
	<?php echo JHTML::_('form.token'); ?>
</form>