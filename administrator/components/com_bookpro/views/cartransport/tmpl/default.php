<?php /**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 81 2012-08-11 01:16:36Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');
JToolBarHelper::title(JText::_('COM_BOOKPRO_TRANSPORT_EDIT'));
JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();
JHtml::_('behavior.modal');
?>
<script type="text/javascript">       
 	Joomla.submitbutton = function(task) {
      var form = document.adminForm;
      if (task == 'cancel') {
         form.task.value = task;
         form.submit();
      }
      if (document.formvalidator.isValid(form)) {
         form.task.value = task;
         form.submit();
       }
       else {
         alert('<?php echo JText::_('Fields highlighted in red are compulsory or unacceptable!'); ?>');
		return false;
		}
		};
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
    		<div class="form-horizontal">
            
                <div class="control-group">
                    <label class="control-label" for="from"><?php echo JText::_('Code'); ?>
                    </label>
                    <div class="controls">
                    <input class="text_area required" type="text" name="code" id="code" size="10" maxlength="255" value="<?php echo $this -> obj -> code; ?>" />
                    </div>
                </div>
                            
    			<div class="control-group">
					<label class="control-label" for="from"><?php echo JText::_('COM_BOOKPRO_TRANSPORT_FROM'); ?>
					</label>
					<div class="controls">
						<?php echo $this->dfrom; ?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="to"><?php echo JText::_('COM_BOOKPRO_TRANSPORT_TO'); ?>
					</label>
					<div class="controls">
						<?php echo $this->dto; ?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="price"><?php echo JText::_('Frequency'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="frequency" id="frequency" size="10" maxlength="255" value="<?php echo $this -> obj -> frequency; ?>" />
					</div>
				</div>

                <div class="control-group">
                    <label class="control-label" for="price"><?php echo JText::_('Cars'); ?>
                    </label>
                    <div class="controls">
                        <?php
                              echo $this->cars;
                          ?>
                    </div>
                </div>				
    			
                <div class="control-group">
                    <label class="control-label" for="state"><?php echo JText::_('COM_BOOKPRO_STATUS'); ?>
                    </label>
                    <div class="controls form-inline">

                        <input type="radio" class="inputRadio" name="state" value="1" id="state_active" <?php if ($this->obj->state == 1) echo 'checked="checked"'; ?>/>
                        <label for="state_active"><?php echo JText::_('COM_BOOKPRO_ACTIVE'); ?></label>
                        <input type="radio" class="inputRadio" name="state" value="<?php echo 0; ?>" id="state_deleted" <?php if ($this->obj->state == 0) echo 'checked="checked"'; ?>/>

                        <label for="state_deleted"><?php echo JText::_('COM_BOOKPRO_INACTIVE'); ?></label>
                    </div>
                </div>
                                
    		</div>
   
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_CAR_TRANSPORT; ?>"/>
	<input type="hidden" name="task" value="save"/>
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="cid[]" value="<?php echo $this -> obj -> id; ?>" id="cid"/>
	
	<?php echo JHTML::_('form.token'); ?>
</form>