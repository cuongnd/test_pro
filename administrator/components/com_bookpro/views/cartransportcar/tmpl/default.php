<?php


defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.calendar');
JHtml::_('behavior.modal');
JHtml::_('behavior.formvalidation');

jimport('joomla.html.html.select');
JToolBarHelper::title(JText::_('Car transport Car'));
JToolBarHelper::save();
//JToolBarHelper::save2copy();
JToolBarHelper::apply();
JToolBarHelper::cancel();
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');

	
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
	
    		 <div class="form-horizontal">
             
    			<div class="control-group">
					<label class="control-label" for="name"><?php echo JText::_('Car transport Id'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="car_transport_id" id="car_transport_id" size="60" maxlength="255" value="<?php echo $this->obj->car_transport_id; ?>" />
					</div>
				</div>
				
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo JText::_('Car Id'); ?>
                    </label>
                    <div class="controls">
                        <input class="text_area required" type="text" name="car_id" id="car_id" size="60" maxlength="255" value="<?php echo $this->obj->car_id; ?>" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo JText::_('Car Price'); ?>
                    </label>
                    <div class="controls">
                            <input class="text_area required" type="text" name="car_price" id="car_price" size="60" maxlength="255" value="<?php echo $this->obj->car_price ; ?>" />
                    </div>
                </div>                
				
				
			</div>

   	
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_CAR_TRANSPORT_CAR; ?>"/>
	<input type="hidden" name="task" value="save"/>
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>
	
	<?php echo JHTML::_('form.token'); ?>
</form>
