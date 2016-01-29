<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	26 March 2012
 * @file name	:	views/project/tmpl/placebid.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows details of the project (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHTML::_('behavior.framework');
 JHTML::_('behavior.formvalidation');
 JHTML::_('behavior.tooltip');
 JHTML::_('behavior.modal');
 
 $doc =& JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/utility.js");
 $doc->addScript("components/com_jblance/js/upclick-min.js");
 
 $project = $this->project;
 $bid = $this->bid;
 
 $config =& JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;
 $currencycode = $config->currencyCode;
 ?>
 <script language="javascript" type="text/javascript">
<!--
function validateForm(f){
	var valid = document.formvalidator.isValid(f);

	

	if(valid == true){
		//check if agreement is selected
		if(!$('is_nda_signed').checked){
			alert('<?php echo JText::_('COM_JBLANCE_PLEASE_REVIEW_AGREE_NDA'); ?>');
			return false;
		}
		f.check.value='<?php echo JSession::getFormToken(); ?>';//send token
    }
    else {
    	var msg = '<?php echo JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'); ?>';
    	if($('amount').hasClass('invalid') || $('delivery').hasClass('invalid')){
	    	msg = msg+'\n\n* '+'<?php echo JText::_('COM_JBLANCE_PLEASE_ENTER_AMOUNT_IN_NUMERIC'); ?>';
	    }
		alert(msg);
		return false;
    }
	return true;
}
function togglePrivateMsg() {
	if($('sendpm').checked){
		$('messagediv').setStyle('display', 'block');
		$('message').addClass('required');
	}
	else {
		$('messagediv').setStyle('display', 'none');
		$('message').removeClass('required');
	}
}
window.addEvent('domready', function(){
	if($('uploadmessage')){
		attachFile('uploadmessage', 'message.attachfile');
	}
});
//-->
</script>
<form action="index.php" method="post" name="userFormBid" id="userFormBid" class="form-validate" onsubmit="return validateForm(this);" enctype="multipart/form-data">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_BID_ON_PROJECT').' : '.$project->project_title; ?></div>
	<table width="100%" cellpadding="0" cellspacing="0" class="jbltable">
		<tr>		
			<td class="key" style="width:30%;">
				<label for="amount"><?php echo JText::_('COM_JBLANCE_YOUR_BID_FOR_PROJECT'); ?>:</label><br />
				<small>
					<?php echo JText::_('COM_JBLANCE_BUDGET_RANGE'); ?> :<?php echo $currencysym.' '.number_format($project->budgetmin); ?> - <?php echo $currencysym.' '.number_format($project->budgetmax).' '.$currencycode; ?>
				</small>
			</td>
			<td>
				<?php echo $currencysym; ?> <input type="text" name="amount" id="amount" size="6" class="inputbox required validate-numeric" value="<?php echo $bid->amount; ?>" /> <?php echo $currencycode; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="delivery"><?php echo JText::_('COM_JBLANCE_DAYS_DELIVER_PROJECT'); ?>:</label><br />
			</td>
			<td><input type="text" name="delivery" id="delivery" size="6" class="inputbox required validate-numeric" value="<?php echo $bid->delivery; ?>" />&nbsp;<?php echo JText::_('COM_JBLANCE_BID_DAYS'); ?></td>
		</tr>
		<tr>
			<td class="key">
				<label for="details"><?php echo JText::_('COM_JBLANCE_BID_DETAILS'); ?>:</label>
			</td>
			<td>
				<textarea name="details" id="details" rows="8" cols="52" class="inputbox required"><?php echo $bid->details; ?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="checkbox" name="outbid" id="outbid" value="1" <?php  echo ($bid->outbid == 1) ? 'checked' : '';?> >
				<?php echo JText::_('COM_JBLANCE_NOTIFY_OUT_BIDS'); ?>
			</td>
		</tr>
		<!-- show the PM only for the first time placing bid -->
		<?php 
		if($bid->id == 0) :
		?>
		<tr>
			<td colspan="2">
				<input type="checkbox" name="sendpm" id="sendpm" value="1" onclick="javascript:togglePrivateMsg();"> <?php echo JText::_('COM_JBLANCE_SEND_PM_TO_PUBLISHER'); ?><br>
				<div id="messagediv" style="display: none;">
				<textarea name="message" id="message" rows="8" cols="52" class="inputbox"></textarea>
				<div id="ajax-container-uploadmessage"></div>
				<div id="file-attached-uploadmessage"></div>
				<input type="button" id="uploadmessage" value="<?php echo JText::_('COM_JBLANCE_ATTACH_FILE'); ?>" class="button">
				<?php 
				$tipmsg = JText::_('COM_JBLANCE_ATTACH_FILE').'::'.JText::_('COM_JBLANCE_ALLOWED_FILE_TYPES').' : '.$config->projectFileText.'<br>'.JText::_('COM_JBLANCE_MAXIMUM_FILE_SIZE').' : '.$config->projectMaxsize.' kB';
				?>
				<img src="components/com_jblance/images/tooltip.png" class="hasTip" title="<?php echo $tipmsg; ?>"/>
				<input type="hidden" name="subject" value="<?php echo $project->project_title;?>" />
				</div>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<td colspan="2">
				
			</td>
		</tr>
	</table>
	<!-- show the agreement form is it is NDA project and not signed -->
	<?php if($this->project->is_nda && !($bid->is_nda_signed)) : ?>
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_SIGN_NDA'); ?></div>
	<div class="plan-choose plan-choose-shadow" style="padding: 15px;">
		<?php 
		$ndaFile  =  JURI::root().'components/com_jblance/images/nda.txt';
		echo JText::sprintf('COM_JBLANCE_NDA_BID_MUST_AGREE', $ndaFile); ?>
		<div class="sp20">&nbsp;</div>
		<input type="checkbox" name="is_nda_signed" id="is_nda_signed" value="1" />
		<?php echo JText::_('COM_JBLANCE_NDA_FREELANCER_AGREE_TO_NDA'); ?>
	</div>
	<table>
		<tr>
		</tr>  
	</table>
	<?php elseif($this->project->is_nda && $bid->is_nda_signed) : ?>
		<div class="noactiveplan jb-aligncenter"><?php echo JText::_('COM_JBLANCE_NDA_ALREADY_SIGNED_AGREEMENT');?>
		<!-- Show attachment if found -->
		<?php
		if(!empty($bid->attachment)) : ?>
			<div style="display: inline;">
		<?php 
		$attachment = explode(";", $bid->attachment);
		$showName = $attachment[0];
		$fileName = $attachment[1];
		?>	
				<a href="<?php echo JBBIDNDA_URL.$fileName; ?>" target="_blank"><img src="components/com_jblance/images/nda.png" width="20px" title="<?php echo JText::_('COM_JBLANCE_NDA_SIGNED'); ?>"/></a>
			</div>
		<?php	
		endif;
		?>
		</div>
	<?php endif; ?>
	<div style="clear:both;"></div>
	<div class="sp10">&nbsp;</div>
	<div class="jb-aligncenter">
		<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SAVE'); ?>" class="button" />
		<input type="button" onclick="javascript:history.back()" value="<?php echo JText::_('COM_JBLANCE_CANCEL'); ?>" class="button"/>
	</div>
	
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="project.savebid" />		
	<input type="hidden" name="id" value="<?php echo $bid->id;?>" />
	<input type="hidden" name="project_id" value="<?php echo $project->id;?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>