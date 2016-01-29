<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	27 November 2012
 * @file name	:	views/user/tmpl/editportfolio.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Lets user to add/edit porfolio (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHTML::_('behavior.framework');
 JHTML::_('behavior.formvalidation');
 JblanceHelper::getMultiSelect('jformid_category', JText::_('COM_JBLANCE_SEARCH_SKILLS'));
 
 $app  	 =& JFactory::getApplication();
 $doc 	 =& JFactory::getDocument();
 $select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
 $editor =& JFactory::getEditor();
 $config =& JblanceHelper::getConfig();
 $user 	 = JFactory::getUser();
 
 $doc->addScript("components/com_jblance/js/utility.js");
 $doc->addScript("components/com_jblance/js/upclick-min.js");
 $doc->addScript("components/com_jblance/js/LightFace.js");
 $doc->addScript("components/com_jblance/js/light.js");
 
 //get the allowed portfolio for the user's plan
 $plan = JblanceHelper::whichPlan($user->id);
 $allowedPortfolio = $plan->portfolioCount;
 
 $link_new	= JRoute::_('index.php?option=com_jblance&view=user&layout=editportfolio&type=addnew');
 
 $type = $app->input->get('type', '', 'string');
 
 JText::script('COM_JBLANCE_CLOSE');
 ?>
 <script language="javascript" type="text/javascript">
<!--
	function validateForm(f){
		if (document.formvalidator.isValid(f)) {
			f.check.value='<?php echo JSession::getFormToken(); ?>';	//send token
	    }
	    else {
		    var msg = '<?php echo JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'); ?>';
			alert(msg);
			return false;
	    }
		return true;
	}

	window.addEvent('domready', function(){
		attachFile('portfoliopicture', 'user.attachportfoliofile');
		attachFile('portfolioattachment', 'user.attachportfoliofile');
	});
//-->
</script>
<div class="fr">
	<?php if(count($this->portfolios) >= $allowedPortfolio) : ?>
	<?php $msg = JText::sprintf('COM_JBLANCE_REACHED_PORTFOLIO_LIMIT', $allowedPortfolio);?>
	<a href="javascript:void(0);" class="jbbutton" onclick="jbLightAlert('<?php echo JText::_('COM_JBLANCE_LIMIT_EXCEEDED'); ?>', '<?php echo $msg; ?>', false);">
		<span><?php echo JText::_('COM_JBLANCE_ADD_PORTFOLIO'); ?></span>
	</a>
	<?php else : ?>
	<a href="<?php echo $link_new; ?>" class="jbbutton"><span><?php echo JText::_('COM_JBLANCE_ADD_PORTFOLIO'); ?></span></a>
	<?php endif; ?>
</div>
<form action="index.php" method="post" name="userFormPortfolio" id="userFormPortfolio" class="form-validate" onsubmit="return validateForm(this);" enctype="multipart/form-data">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PORTFOLIOS'); ?></div>
	<?php
	include_once(JPATH_COMPONENT.'/views/profilemenu.php');
	?>
	<?php if(count($this->portfolios) > 0){ ?>
	<table width="100%" cellpadding="0" cellspacing="0" class="border">
		<thead>
			<tr class="jbl_rowhead">
				<th>#</th>
				<th width="35%"><?php echo JText::_('COM_JBLANCE_TITLE'); ?></th>
				<th width="35%"><?php echo JText::_('COM_JBLANCE_DESCRIPTION'); ?></th>
				<th width="15%"><?php echo JText::_('COM_JBLANCE_ACTION'); ?></th>
				<th width="15%"><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		for ($i=0, $x=count($this->portfolios); $i < $x; $i++){
			$portfolio = $this->portfolios[$i];
			$link_edit	 = JRoute::_('index.php?option=com_jblance&view=user&layout=editportfolio&id='.$portfolio->id);
			$link_delete = JRoute::_('index.php?option=com_jblance&task=user.deleteportfolio&id='.$portfolio->id.'&'.JSession::getFormToken().'=1');
			?>
			<tr class="jbl_row<?php echo $k; ?>">
				<td><?php echo $i+1; ?></td>
				<td>
					<?php echo $portfolio->title; ?>
				</td>
				<td>
				<?php 
				$position = 40; // Define how many character you want to display.
				$message = strip_tags($portfolio->description); 
				$trimmed = substr($message, 0, $position); 
				echo $trimmed.'...';
				?>
				</td>
				<td>
					<a href="<?php echo $link_edit; ?>"><?php echo JText::_('COM_JBLANCE_EDIT'); ?></a>	|	 
					<a href="<?php echo $link_delete; ?>"><?php echo JText::_('COM_JBLANCE_DELETE'); ?></a>
				</td>
				<td class="jb-aligncenter"><img src="components/com_jblance/images/s<?php echo $portfolio->published; ?>.png" alt="Status"></td>
		  </tr>
			<?php 
			$k = 1 - $k;
		}
		?>
		</tbody>
	</table>
	<?php 
 	}
 	else 
 		echo '<p>'.JText::_('COM_JBLANCE_NO_PORTFOLIO').'</p>';
	?>
<!-- Show the Edit layout only when there is no portfolio or add new or edit link is clicked -->
<?php if(/* count($this->portfolios) == 0 ||  */$this->row->id > 0 || ($type == 'addnew' && $allowedPortfolio > 0)){ ?>
	<div class="jbl_h3title">
	<?php echo ($this->row->id == 0) ? JText::_('COM_JBLANCE_ADD_PORTFOLIO') : JText::_('COM_JBLANCE_EDIT_PORTFOLIO'); ?>
	</div>
	<table width="100%" cellpadding="0" cellspacing="0"	class="jbltable">
		<tr>
			<td class="key"><label for="jform_title"><?php echo JText::_('COM_JBLANCE_TITLE'); ?>:</label>
			</td>
			<td>						
				<input type="text" class="inputbox required" name="jform[title]" id="jform_title" size="60" value="<?php echo $this->row->title;?>">
			</td>
		</tr>
		<tr>
			<td class="key"><label for="jformid_category"><?php echo JText::_('COM_JBLANCE_SKILLS'); ?>:</label>
			</td>
			<td>						
				<?php 
				$attribs = 'class="inputbox required" size="20" multiple ';
				$defaultCategory = empty($this->row->id_category) ? 0 : explode(',', $this->row->id_category);
				$categtree = $select->getSelectCategoryTree('jform[id_category][]', $defaultCategory, 'COM_JBLANCE_PLEASE_SELECT', $attribs, '', true);
				echo $categtree; ?>
			</td>
		</tr>
		<tr>
			<td class="key"><label for="jform_start_date"><?php echo JText::_('COM_JBLANCE_START_DATE'); ?>:</label></td>
			<td>
				 <?php 
				 $startdate = (empty($this->row->start_date)) ? '' : $this->row->start_date;
				 echo JHTML::_('calendar', $startdate, 'jform[start_date]', 'jform_start_date', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'20',  'maxlength'=>'32'));
				 ?>
			</td>
		</tr>
		<tr>
			<td class="key"><label for="jform_finish_date"><?php echo JText::_('COM_JBLANCE_FINISH_DATE'); ?>:</label></td>
			<td>
				 <?php 
				 $finishdate = (empty($this->row->finish_date)) ? '' : $this->row->finish_date;
				 echo JHTML::_('calendar', $finishdate, 'jform[finish_date]', 'jform_finish_date', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'20',  'maxlength'=>'32'));
				 ?>
			</td>
		</tr>
		<tr>
			<td class="key"><label for="jform_link"><?php echo JText::_('COM_JBLANCE_WEB_ADDRESS'); ?>:</label>
			</td>
			<td>						
				<input type="text" class="inputbox" name="jform[link]" id="jform_link" size="60" value="<?php echo $this->row->link; ?>">
			</td>
		</tr>
		<tr>
			<td class="key"><label for="jform_description"><?php echo JText::_('COM_JBLANCE_DESCRIPTION'); ?>:</label></td>
			<td>
				<?php echo $editor->display('jform[description]', $this->row->description, '80%', '250', '30', '5', false, 'jform_description'); ?>
			</td>
		</tr>
		<tr>
			<td class="key"><label for="published"><?php echo JText::_('COM_JBLANCE_PUBLISHED'); ?>:</label></td>
			<td>
				<?php echo $select->YesNoBool('jform[published]', $this->row->published == 0 ? 0 : 1); ?>
			</td>
		</tr>
		<tr>
			<td class="key"><label for="published"><?php echo JText::_('COM_JBLANCE_PORTFOLIO_IMAGE'); ?>:</label></td>
			<td>
				<?php
				if($this->row->picture){
					$attachment = explode(";", $this->row->picture);
					$showName = $attachment[0];
					$fileName = $attachment[1];
					
					$imgLoc = JBPORTFOLIO_URL.$fileName;
					$fileAtr = getimagesize($imgLoc);
					$width = $fileAtr[0];
					$height = $fileAtr[1];
					
					//if image width is > 400px, then set it to 400px
					if($width > 400)
						$width = '400px';
				?>
				<img src='<?php echo $imgLoc; ?>' width="<?php echo $width; ?>" />
				<?php 
				}
				?>
				<div id="ajax-container-portfoliopicture"></div>
				<div id="file-attached-portfoliopicture"></div>
				<input type="button" id="portfoliopicture" value="<?php echo JText::_('COM_JBLANCE_ATTACH_IMAGE'); ?>" class="button">
				<?php 
				//$tipmsg = JText::_('COM_JBLANCE_ATTACH_IMAGE').'::'.JText::_('COM_JBLANCE_ALLOWED_FILE_TYPES').' : '.$config->projectFileText.'<br>'.JText::_('COM_JBLANCE_MAXIMUM_FILE_SIZE').' : '.$config->projectMaxsize.' kB';
				?>
				<!-- <img src="components/com_jblance/images/tooltip.png" class="hasTip" title="<?php echo $tipmsg; ?>"/> -->
			</td>
			<tr>
			<td class="key"><label for="published"><?php echo JText::_('COM_JBLANCE_ATTACHMENT'); ?>:</label></td>
			<td>
				<?php
				if($this->row->attachment){
					$attachment = explode(";", $this->row->attachment);
					$showName = $attachment[0];
					$fileName = $attachment[1];
				?>
				<a href="<?php echo JBPORTFOLIO_URL.$fileName; ?>" target="_blank"><?php echo $showName; ?></a>
				<?php
				}
				?>
				<div id="ajax-container-portfolioattachment"></div>
				<div id="file-attached-portfolioattachment"></div>
				<input type="button" id="portfolioattachment" value="<?php echo JText::_('COM_JBLANCE_ATTACH_FILE'); ?>" class="button">
			</td>
		</tr>
	</table>
	<div class="jb-aligncenter">
		<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SAVE'); ?>" class="button"/> 
		<input type="button" value="<?php echo JText::_('COM_JBLANCE_CANCEL'); ?>" onclick="javascript:history.back();" class="button" />
	</div>
<?php } ?>
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="task" value="user.saveportfolio" />
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
 