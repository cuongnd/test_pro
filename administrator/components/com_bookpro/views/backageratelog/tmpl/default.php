<?php


defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.html.html.select' );
JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();
JHtml::_('behavior.formvalidation');
	
?>
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
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
	
		<fieldset class="adminform">
    		<legend><?php echo JText::_('COM_BOOKPRO_package_EDIT'); ?></legend>
    		<div class="form-horizontal">
    			<div class="control-group">
					<label class="control-label" for="tours"><?php echo JText::_('package'); ?>
					</label>
					<div class="controls">
						<?php echo $this->packages ?>
					</div>
				</div>
    			
    			<div class="control-group">
					<label class="control-label" for="package_type"><?php echo JText::_('Start Date'); ?>
					</label>
					<div class="controls">
                        <?php
                                $startdate='';
                             if($this->obj->startdate !='0000-00-00 00:00:00')
                                $startdate=JFactory::getDate($this->obj->startdate)->format('Y-m-d');
                         ?>
                    <td><?php echo JHtml::calendar($startdate, 'startdate', 'startdate','%Y-%m-%d') ?></td> 
					</div>
				</div>
    			
                <div class="control-group">
                    <label class="control-label" for="package_type"><?php echo JText::_('End Date'); ?>
                    </label>
                    <div class="controls">
                        <?php
                                $enddate='';
                             if($this->obj->enddate !='0000-00-00 00:00:00')
                                $enddate=JFactory::getDate($this->obj->enddate)->format('Y-m-d');
                         ?>                    
                    <td><?php echo JHtml::calendar($enddate, 'enddate', 'enddate','%Y-%m-%d') ?></td> 
                    </div>
                </div>                
                
				<div class="control-group">
					<label class="control-label" for="quantity"><?php echo JText::_('Rate'); ?>
					</label>
					<div class="controls">
				
					</div>
				</div>
                
                <div class="control-group">
                    <label class="control-label" for="quantity"><?php echo JText::_('Weekday'); ?>
                    </label>
                    <div class="controls">
                        <input class="text_area required" type="text" name="dayrate" id="dayrate" size="60" maxlength="255" value="<?php echo $this->obj->dayrate; ?>" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="quantity"><?php echo JText::_('WeekEnd'); ?>
                    </label>
                    <div class="controls">
                        <input class="text_area required" type="text" name="endrate" id="endrate" size="60" maxlength="255" value="<?php echo $this->obj->endrate; ?>" />
                    </div>
                </div>                
                                
                
    			
    	</fieldset>
    	
       
    </div>
   
   	
   	<div class="compulsory"><?php echo JText::_('Compulsory items'); ?></div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_PACKAGE_RATE_LOG; ?>"/>
	<input type="hidden" name="task" value="save"/>
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>
	
	<?php echo JHTML::_('form.token'); ?>
</form>
