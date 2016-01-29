<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	23 March 2012
 * @file name	:	views/project/tmpl/editproject.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Post / Edit project (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 JHTML::_('behavior.modal');
 JHTML::_('behavior.tooltip');
 //JHTML::script('jbvalidate.js', 'components/com_jblance/js/');
 
 JblanceHelper::getMultiSelect('id_category', JText::_('COM_JBLANCE_SEARCH_SKILLS'));

 $select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
 $editor =& JFactory::getEditor();
 $user = JFactory::getUser();

 $config =& JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;
 $fileLimitConf = $config->projectMaxfileCount;
 $reviewProjects = $config->reviewProjects;

 $title = ($this->row->id == 0) ? JText::_('COM_JBLANCE_POST_NEW_PROJECT') : JText::_('COM_JBLANCE_EDIT_PROJECT');

 //get the project upgrade amounts based on the plan
 $plan = JblanceHelper::whichPlan($user->id);
 $featuredProjectFee = $plan->buyFeePerFeaturedProject;
 $urgentProjectFee = $plan->buyFeePerUrgentProject;
 $privateProjectFee = $plan->buyFeePerPrivateProject;
 $sealedProjectFee = $plan->buyFeePerSealedProject;
 $ndaProjectFee = $plan->buyFeePerNDAProject;
 
 $totalFund = JblanceHelper::getTotalFund($user->id);
 JText::script('COM_JBLANCE_CLOSE');
 
 $ndaFile = JURI::root().'components/com_jblance/images/nda.txt';
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
	
	function updateTotalAmount(el){
		var element = el.name;
		var tot = parseFloat($('totalamount').get('value'));
		var fee = 0;
		
		if(element == 'is_featured')
			fee = parseFloat('<?php echo $featuredProjectFee; ?>');
		else if(element == 'is_urgent')
			fee = parseFloat('<?php echo $urgentProjectFee; ?>');
		else if(element == 'is_private')
			fee = parseFloat('<?php echo $privateProjectFee; ?>');
		else if(element == 'is_sealed')
			fee = parseFloat('<?php echo $sealedProjectFee; ?>');
		else if(element == 'is_nda')
			fee = parseFloat('<?php echo $ndaProjectFee; ?>');

		if($(element).checked){
			tot = parseFloat(tot + fee);
		}
		else {
			tot = parseFloat(tot - fee);
		}
		$('subtotal').set('html', tot);
		$('totalamount').set('value', tot);
	}
//-->
</script>

<form action="index.php" method="post" name="userFormProject" id="userFormProject" class="form-validate" onsubmit="return validateForm(this);" enctype="multipart/form-data">
	<div class="jbl_h3title"><?php echo $title; ?></div>
	<fieldset class="jblfieldset">
		<legend><?php echo JText::_('COM_JBLANCE_YOUR_PROJECT_DETAILS'); ?></legend>
		<table width="100%" cellpadding="0" cellspacing="0"	class="jbltable">
			<tr>
				<td class="key"><label for="project_title"><?php echo JText::_('COM_JBLANCE_PROJECT_TITLE'); ?>:</label>
				</td>
				<td>						
					<input type="text" class="inputbox required searchbox" name="project_title" id="project_title" size="60" value="<?php echo $this->row->project_title;?>">
				</td>
			</tr>
			<tr>
				<td class="key"><label for="id_category"><?php echo JText::_('COM_JBLANCE_PROJECT_CATEGORIES'); ?>:</label>
				</td>
				<td>						
					<?php 
					$attribs = 'class="inputbox required" size="20" multiple ';
					$defaultCategory = empty($this->row->id_category) ? 0 : explode(',', $this->row->id_category);
					$categtree = $select->getSelectCategoryTree('id_category[]', $defaultCategory, 'COM_JBLANCE_PLEASE_SELECT', $attribs, '', true);
					echo $categtree; ?>
				</td>
			</tr>
			<tr>
				<td class="key"><label for="start_date"><?php echo JText::_('COM_JBLANCE_START_DATE'); ?>:</label></td>
				<td>
					 <?php 
					 $now = JFactory::getDate()->toSql();
					 $startdate = (empty($this->row->start_date)) ? $now : $this->row->start_date;
					 echo JHTML::_('calendar', $startdate, 'start_date', 'start_date', '%Y-%m-%d', array('class'=>'inputbox required', 'size'=>'20',  'maxlength'=>'32'));
					 ?>
				</td>
			</tr>
			<tr>
				<td class="key"><label for="expires"><?php echo JText::_('COM_JBLANCE_EXPIRES'); ?>:</label></td>
				<td >				
					<input type="text" class="inputbox required" name="expires" id="expires" size="3" value="<?php echo $this->row->expires; ?>">&nbsp;<?php echo JText::_('COM_JBLANCE_DAYS'); ?>
				</td>
			</tr>
			<tr>
				<td class="key"><label for="budgetrange"><?php echo JText::_('COM_JBLANCE_BUDGET'); ?>:</label></td>
				<td>
					<?php 
					$attribs = 'class="inputbox required"';
					$default = $this->row->budgetmin.'-'.$this->row->budgetmax;
					echo $select->getSelectBudgetRange('budgetrange', $default, 'COM_JBLANCE_PLEASE_SELECT', $attribs, '');
					?>
				</td>
			</tr>
			<tr>
				<td class="key"><label for="description"><?php echo JText::_('COM_JBLANCE_DESCRIPTION'); ?>:</label></td>
				<td>
					<?php echo $editor->display('description', $this->row->description, '100%', '400', '50', '10', false); ?>
				</td>
			</tr>
			<tr>
				<td class="key"><label><?php echo JText::_('COM_JBLANCE_ATTACHMENT'); ?>:</label></td>
				<td>
					<?php
					for($i=0; $i < $fileLimitConf; $i++){
					?>
					<input name="uploadFile<?php echo $i;?>" type="file" id="uploadFile<?php echo $i;?>" /><br>
					<?php 
					} ?>
					<input name="uploadLimit" type="hidden" value="<?php echo $fileLimitConf;?>" />
					<?php 
					$tipmsg = JText::_('COM_JBLANCE_ATTACH_FILE').'::'.JText::_('COM_JBLANCE_ALLOWED_FILE_TYPES').' : '.$config->projectFileText.'<br>'.JText::_('COM_JBLANCE_MAXIMUM_FILE_SIZE').' : '.$config->projectMaxsize.' kB';
					?>
					<img src="components/com_jblance/images/tooltip.png" class="hasTip" title="<?php echo $tipmsg; ?>"/>
					<div class="lineseparator"></div>
					<?php 
					foreach($this->projfiles as $projfile){ ?>
					<input type="checkbox" name=file-id[] value="<?php echo $projfile->id; ?>" /> <?php echo $projfile->show_name; ?> <a href="<?php echo JBPROJECT_URL.$projfile->file_name; ?>" target="_blank"><img src="components/com_jblance/images/download.png" width="24" alt="Download" title="<?php echo JText::_('COM_JBLANCE_DOWNLOAD'); ?>"/></a><br>
					<?php	
					}
					?>
				</td>
				<td>
					
				</td>
			</tr>
		</table>
	</fieldset>
	
	<?php 
	$fields = JblanceHelper::get('helper.fields');		// create an instance of the class fieldsHelper
	
	$parents = array();$children = array();
	//isolate parent and childr
	foreach($this->fields as $ct){
		if($ct->parent == 0)
			$parents[] = $ct;
		else
			$children[] = $ct;
	}
		
	if(count($parents)){
		foreach($parents as $pt){ ?>
	<fieldset class="jblfieldset">
		<legend><?php echo JText::_($pt->field_title); ?></legend>
		<table class="jbltable" width="100%">
			<?php
			foreach($children as $ct){
				if($ct->parent == $pt->id){ ?>
			<tr>
				<td class="key">
					<?php
					$labelsuffix = '';
					if($ct->field_type == 'Checkbox') $labelsuffix = '[]'; //added to validate checkbox
					?>
					<label for="custom_field_<?php echo $ct->id.$labelsuffix; ?>"><?php echo JText::_($ct->field_title); ?><span class="redfont"><?php echo ($ct->required)? '*' : ''; ?></span>:</label>
				</td>
				<td>
					<?php $fields->getFieldHTML($ct, $this->row->id, 'project'); ?>
				</td>
			</tr>
			<?php
				}
			} ?>
		</table>
	</fieldset>
			<?php
		}
	}
	?>
	
	<fieldset class="jblfieldset">
		<legend><?php echo JText::_('COM_JBLANCE_SEO_OPTIMIZATION'); ?></legend>
		<table width="100%">
			<tr>
				<td class="key"><label for="metadesc"><?php echo JText::_('COM_JBLANCE_META_DESCRIPTION'); ?>:</label></td>
				<td>
					<textarea name="metadesc" id="metadesc" rows="3" cols="60" class="inputbox"><?php echo $this->row->metadesc; ?></textarea>
					<?php 
					$tipmsg = JText::_('COM_JBLANCE_META_DESCRIPTION').'::'.JText::_('COM_JBLANCE_META_DESCRIPTION_TIPS');
					?>
					<img src="components/com_jblance/images/tooltip.png" class="hasTip" title="<?php echo $tipmsg; ?>"/>
				</td>
			</tr>
			<tr>
				<td class="key"><label for="metakey"><?php echo JText::_('COM_JBLANCE_META_KEYWORDS'); ?>:</label></td>
				<td>
					<textarea name="metakey" id="metakey" rows="3" cols="60" class="inputbox"><?php echo $this->row->metakey; ?></textarea>
					<?php 
					$tipmsg = JText::_('COM_JBLANCE_META_KEYWORDS').'::'.JText::_('COM_JBLANCE_META_KEYWORDS_TIPS');
					?>
					<img src="components/com_jblance/images/tooltip.png" class="hasTip" title="<?php echo $tipmsg; ?>"/>
				</td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset class="jblfieldset">
		<legend><?php echo JText::_('COM_JBLANCE_PROMOTE_YOUR_LISTING'); ?></legend>
		<table width="100%">
			<!-- The project once set as 'Featured' should not be able to change again -->
			<tr class="project_upgrades">
			<?php if(!$this->row->is_featured) : ?>
				<td>
					<input type="checkbox" id="is_featured" name="is_featured" value="1" class="project_upgrades" onclick="updateTotalAmount(this);"> 
				</td>
				<td>
					<span class="featured"></span>
				</td>
				<td>
					<p><?php echo JText::_('COM_JBLANCE_FEATURED_PROJECT_DESC'); ?></p>
				</td>
				<td nowrap>
					<span class="font16 boldfont"><?php echo JblanceHelper::formatCurrency($featuredProjectFee, $currencysym) ; ?></span>
				</td>
			<?php else : ?>
				<td>
					<span class="featured"></span>
				</td>
				<td colspan="3">
					<?php echo JText::_('COM_JBLANCE_THIS_IS_A_FEATURED_PROJECT'); ?>
				</td>
			<?php endif; ?>
			</tr>
			<tr><td></td></tr>
			<!-- The project once set as 'Urgent' should not be able to change again -->
			<tr class="project_upgrades">
			<?php if(!$this->row->is_urgent) : ?>
				<td>
					<input type="checkbox" id="is_urgent" name="is_urgent" value="1" class="project_upgrades" onclick="updateTotalAmount(this);"> 
				</td>
				<td>
					<span class="urgent"></span>
				</td>
				<td>
					<p><?php echo JText::_('COM_JBLANCE_URGENT_PROJECT_DESC'); ?></p>
				</td>
				<td nowrap>
					<span class="font16 boldfont"><?php echo JblanceHelper::formatCurrency($urgentProjectFee, $currencysym) ; ?></span>
				</td>
			<?php else : ?>
				<td>
					<span class="urgent"></span>
				</td>
				<td colspan="3">
					<?php echo JText::_('COM_JBLANCE_THIS_IS_AN_URGENT_PROJECT'); ?>
				</td>
			<?php endif; ?>
			</tr>
			<tr><td></td></tr>
			<!-- The project once set as 'Private' should not be able to change again -->
			<tr class="project_upgrades">
			<?php if(!$this->row->is_private) : ?>
				<td>
					<input type="checkbox" id="is_private" name="is_private" value="1" class="project_upgrades" onclick="updateTotalAmount(this);"> 
				</td>
				<td>
					<span class="private"></span>
				</td>
				<td>
					<p><?php echo JText::_('COM_JBLANCE_PRIVATE_PROJECT_DESC'); ?></p>
				</td>
				<td nowrap>
					<span class="font16 boldfont"><?php echo JblanceHelper::formatCurrency($privateProjectFee, $currencysym) ; ?></span>
				</td>
			<?php else : ?>
				<td>
					<span class="private"></span>
				</td>
				<td colspan="3">
					<?php echo JText::_('COM_JBLANCE_THIS_IS_A_PRIVATE_PROJECT'); ?>
				</td>
			<?php endif; ?>
			</tr>
			<tr><td></td></tr>
			<!-- The project once set as 'Sealed' should not be able to change again -->
			<tr class="project_upgrades">
			<?php if(!$this->row->is_sealed) : ?>
				<td>
					<input type="checkbox" id="is_sealed" name="is_sealed" value="1" class="project_upgrades" onclick="updateTotalAmount(this);"> 
				</td>
				<td>
					<span class="sealed"></span>
				</td>
				<td>
					<p><?php echo JText::_('COM_JBLANCE_SEALED_PROJECT_DESC'); ?></p>
				</td>
				<td nowrap>
					<span class="font16 boldfont"><?php echo JblanceHelper::formatCurrency($sealedProjectFee, $currencysym) ; ?></span>
				</td>
			<?php else : ?>
				<td>
					<span class="sealed"></span>
				</td>
				<td colspan="3">
					<?php echo JText::_('COM_JBLANCE_THIS_IS_A_SEALED_PROJECT'); ?>
				</td>
			<?php endif; ?>
			</tr>
			<tr><td></td></tr>
			<!-- The project once set as 'NDA' should not be able to change again -->
			<tr class="project_upgrades">
			<?php if(!$this->row->is_nda) : ?>
				<td>
					<input type="checkbox" id="is_nda" name="is_nda" value="1" class="project_upgrades" onclick="updateTotalAmount(this);"> 
				</td>
				<td>
					<span class="nda"></span>
				</td>
				<td>
					<p><?php echo JText::sprintf('COM_JBLANCE_NDA_PROJECT_DESC', $ndaFile); ?></p>
				</td>
				<td nowrap>
					<span class="font16 boldfont"><?php echo JblanceHelper::formatCurrency($ndaProjectFee, $currencysym) ; ?></span>
				</td>
			<?php else : ?>
				<td>
					<span class="nda"></span>
				</td>
				<td colspan="3">
					<p><?php echo JText::_('COM_JBLANCE_THIS_IS_A_NDA_PROJECT'); ?></p>
				</td>
			<?php endif; ?>
			</tr>
			<tr><td></td></tr>
			<tr class="project_upgrades">
				<td colspan="4" width"50%" align="right" class="font16 boldfont">
					<span style="text-align:left; float: left;"><?php echo JText::_('COM_JBLANCE_CURRENT_BALANCE').' : '.JblanceHelper::formatCurrency($totalFund, $currencysym); ?></span>
					<span><?php echo JText::_('COM_JBLANCE_TOTAL').' : '.$currencysym; ?><span id="subtotal">0.00</span></span>
				</td>
			</tr>
		</table>
	</fieldset>
	<div class="font14 boldfont">
	<?php 
	if($reviewProjects && !$this->row->approved){
		echo JText::_('COM_JBLANCE_PROJECT_WILL_BE_REVIEWED_BY_ADMIN_BEFORE_LIVE');
	}
	?>
	</div>
	<div class="jb-aligncenter">
		<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SAVE_PROJECT'); ?>" class="button"/> 
		<input type="button" value="<?php echo JText::_('COM_JBLANCE_CANCEL'); ?>" onclick="javascript:history.back();" class="button" />
	</div>
	
	<input type="hidden" name="option" value="com_jblance" /> 
	<input type="hidden" name="task" value="project.saveproject" /> 
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="totalamount" id="totalamount" value="0.00" />
	<?php echo JHTML::_('form.token'); ?>
</form>