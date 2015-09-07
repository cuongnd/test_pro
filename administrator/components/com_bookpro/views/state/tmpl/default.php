<?php
/**
* @package   JE Form Creation 
* @copyright Copyright (C) 2009 - 2010 Open Source Matters. All rights reserved.
* @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL, see LICENSE.php
* Contact to : emailtohardik@gmail.com, joomextensions@gmail.com
**/	
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');
/*echo 'default';
exit;*/
 ?>
 <script language="javascript" type="text/javascript">
	Joomla.submitbutton=function submitbutton(pressbutton) 
	{		
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		
			submitform( pressbutton );
		
	}
	
</script>
<script type="text/javascript">       
 Joomla.submitbutton = function(task) {
      var form = document.adminForm;
      if (task == 'cancel') {
         form.task.value = task;
         form.submit();
         return;
      }
      if (document.formvalidator.isValid(form)) {
         form.task.value = task;
         form.submit();
       }
       else {
         alert('<?php echo JText::_('Fields highlighted in red are compulsory!'); ?>');
         return false;
       }
   }
	</script>

 <form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
  
    <fieldset class="adminform">
    <legend><?php echo JText::_( 'DETAILS' ); ?></legend>
    <div class="form-horizontal">
        <div class="control-group">
			<label class="control-label" for="country"><?php echo JText::_('COM_BOOKPRO_STATE_COUNTRY_ID'); ?>
			</label>
			<div class="controls">
				<?php echo $this->lists['country']; ?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="state_name"><?php echo JText::_('COM_BOOKPRO_STATE_NAME'); ?>
			</label>
			<div class="controls">
				<input class="text_area required" type="text" name="state_name" id="state_name" size="15" maxlength="32" value="<?php echo $this->detail->state_name;?>" />
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="state_3_code"><?php echo JText::_('COM_BOOKPRO_STATE_3_CODE'); ?>
			</label>
			<div class="controls">
				<input class="text_area required" type="text" name="state_3_code" id="state_3_code" size="3" maxlength="3" value="<?php echo $this->detail->state_3_code;?>" />
			</div>
		</div>
      
	  	<div class="control-group">
			<label class="control-label" for="state_2_code"><?php echo JText::_('COM_BOOKPRO_STATE_2_CODE'); ?>
			</label>
			<div class="controls">
				<input class="text_area required" type="text" name="state_2_code" id="state_2_code" size="3" maxlength="3" value="<?php echo $this->detail->state_2_code;?>" />
			</div>
		</div>
	  
	   	<div class="control-group">
			<label class="control-label" for="published"><?php echo JText::_('COM_BOOKPRO_STATE_PUBLISHED'); ?>
			</label>
			<div class="controls">
				<?php echo $this->lists['published'];?>
			</div>
		</div>
	  
    </fieldset>
  </div>
   
  <input type="hidden" name="state_id" value="<?php echo $this->detail->state_id; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="view" value="state_detail" />
</form>

