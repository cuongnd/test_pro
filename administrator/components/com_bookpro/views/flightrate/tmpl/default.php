<?php

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.html.html.select' );
$input = JFactory::getApplication()->input;
//JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::deleteList('', 'trash', 'Trash');
JToolBarHelper::cancel();
JHtml::_('behavior.formvalidation');
JToolBarHelper::title(JText::_('COM_BOOKPRO_ADD_RATE_MANAGER'), 'object');
$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = &$this->pagination;

AImporter::helper('flight');
$flight_id = $input->get('flight_id',0,'int');

?>
<script type="text/javascript">       
 Joomla.submitbutton = function(task) {     
      var form = document.adminForm;
      var room_id = document.id('flight_id').value;
      var check = true;  
      form.task.value = task;
                        
      if(document.id('startdate').value){
        var startDate = new Date(document.id('startdate').value);
      }else if(document.id('enddate').value){
        var endDate = new Date(document.id('enddate').value);
      }else if (task == 'apply') {
         if(!room_id || room_id==0){
             alert('<?php echo JText::_('COM_BOOKPRO_ROOM_IS_REQUIRED_FIELD'); ?>');
             check = false;
         }else if(!startDate){
             alert('<?php echo JText::_('COM_BOOKPRO_START_DATE_IS_REQUIRED_FIELD'); ?>');
             check = false;
         }else if(!endDate){
             alert('<?php echo JText::_('COM_BOOKPRO_END_DATE_IS_REQUIRED_FIELD'); ?>');
             check = false;
         }else if (startDate >= endDate){
            alert('<?php echo JText::_('COM_BOOKPRO_END_DATA_MUST_BE_GREATER_THAN_START_DATE'); ?>');
            check = false;
        }                   
      }
         
      if(check){
          form.submit();
      }
   }
	</script>
<form action="index.php" method="post" name="adminForm"	class="form-validate">
	
<div class="span3">


	<div class="control-group">
		<label class="control-label"><?php echo JText::_('COM_BOOKPRO_ROOM_'); ?> 
		</label>
		<div class="controls">
			<?php echo FlightHelper::getRoomSelect($flight_id,"flight_id",'jform[flight_id]'); ?>	
		</div>
		
		
	</div>

	<div class="control-group">
		<label class="control-label"><?php echo JText::_('COM_BOOKPRO_START_DATE_'); ?> 
		</label>
		<div class="controls">
			<?php echo JHtml::calendar('', 'jform[startdate]', 'startdate','%Y-%m-%d','readonly="readonly"') ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('COM_BOOKPRO_END_DATE_'); ?> 
		</label>
		<div class="controls">
			<?php echo JHtml::calendar('', 'jform[enddate]', 'enddate','%Y-%m-%d','readonly="readonly"') ?>
		</div>
		
	</div>
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('COM_BOOKPRO_DISCOUNT') ?></label>
		<div class="controls">
			<input class="input-medium" type="text" name="jform[discount]" value="" />
		</div>
	</div>
	<?php echo $this->loadTemplate('base'); ?>
	<?php echo $this->loadTemplate('eco'); ?>
	<?php echo $this->loadTemplate('bus'); ?>
	

	</div>

	<div class="form-horizontal span9">

		<table class="table">
			<thead>
				<tr>
					<th width="30%"><?php echo JText::_("COM_BOOKPRO_ROOM_TYPE_NAME");?>
					</th>
					<th><?php echo JText::_("COM_BOOKPRO_DATE__END_DATE");?></th>
					<th><?php echo JText::_("COM_BOOKPRO_ADULT");?>
					</th>
					<th><?php echo JText::_("COM_BOOKPRO_CHILD");?>
					</th>
					<?php if (! $this->selectable) { ?>
					<th><label class="checkbox"> <input type="checkbox"
							class="inputCheckbox" name="toggle" value=""
							onclick="Joomla.checkAll(this);" /> <?php echo JText::_("COM_BOOKPRO_DELETE");?>
					</label>
					</th>
					<?php } ?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="9"><?php echo $pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>


			<?php if (! is_array($this->items) || ! $itemsCount && $this->tableTotal) { ?>
			<tbody>
				<tr>
					<td colspan="5" class="emptyListInfo"><?php echo JText::_('COM_BOOKPRO_NO_ITEMS_FOUND'); ?>
					</td>
				</tr>
			</tbody>
			<?php 

} else {

                                     for ($i = 0; $i < $itemsCount; $i++) {
                                         $subject = &$this->items[$i];
                                         ?>
			<tbody>
				<tr class="record">

					<td><?php echo $subject->title ?></td>
					<td><?php echo JFactory::getDate($subject->startdate)->format('d-m-Y').' '.JText::_('COM_BOOKPRO_TO').' '. JFactory::getDate($subject->enddate)->format('d-m-Y'); ?>
					</td>
					<td><?php echo CurrencyHelper::displayPrice($subject->adult,$subject->adult_discount) ?></td>
					<td><?php echo CurrencyHelper::displayPrice($subject->child,$subject->child_discount) ?></td>
					<?php if (! $this->selectable) { ?>
					<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?>
					</td>
					<?php } ?>
				</tr>
			</tbody>
			<?php 
                                    }
                                }
                                ?>
		</table>

	</div>

	<input type="hidden" name="option" value="<?php echo OPTION; ?>" /> <input
		type="hidden" name="controller" value="flightrate" /> <input
		type="hidden" name="task" value="save" /> <input type="hidden"
		name="boxchecked" value="1" /> <input type="hidden" name="cid[]"
		value="<?php echo $this->obj->id; ?>" id="cid" />

	<?php echo JHTML::_('form.token'); ?>
</form>

