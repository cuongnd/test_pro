<?php


defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.html.html.select' );
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
?>

<script type="text/javascript">       
 Joomla.submitbutton = function(task) {     
      var form = document.adminForm;
      var package_id = form.package_id.value;
      var check = true;  
      form.task.value = task;
                        
      if(form.startdate.value){
        var startDate = new Date(form.startdate.value);
      }else if(form.enddate.value){
        var endDate = new Date(form.enddate.value);
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
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">     
    <div class="form-horizontal span3 pull-left">   
					<label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_'); ?></strong> </label>
					<?php echo $this->packages ?>
				    
					<label ><strong><?php echo JText::_('COM_BOOKPRO_START_DATE_'); ?></strong> </label> 
                    <?php echo JHtml::calendar('', 'startdate', 'startdate','%Y-%m-%d','readonly="readonly"') ?>
             
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_END_DATE_'); ?></strong> </label>
                   <?php echo JHtml::calendar('', 'enddate', 'enddate','%Y-%m-%d','readonly="readonly"') ?>
                
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_WEEKDAY'); ?></strong> </label>
                     <input class="text_area required" type="text" name="dayrate" id="dayrate" size="60" maxlength="255" value="" />
                    
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_WEEKEND'); ?></strong> </label>
                    <input class="text_area required" type="text" name="endrate" id="endrate" size="60" maxlength="255" value="" />                                                          
</div>             

  <div class="form-horizontal span9" style="">
            <?php
                if($this->tour){
            ?>
              <h3><?php echo JText::_('COM_BOOKPRO_TOUR_').$this->tour->title; ?></h3>  
            <?php
                }
              ?>
                <table class="table">
                            <thead>
                                <tr>
                                        <th><?php echo JText::_("COM_BOOKPRO_PACKAGE_TYPE_NAME");?></th>
                                        <th><?php echo JText::_("COM_BOOKPRO_DATE__END_DATE");?></th>
                                        <th style="text-align:center;"><?php echo JText::_("COM_BOOKPRO_WEEKDAY");?></th>
                                        <th style="text-align:center;"><?php echo JText::_("COM_BOOKPRO_WEEKEND");?></th>
                                        <?php if (! $this->selectable) { ?>
                                            <th>
                                                <label class="checkbox">
                                                <input type="checkbox" class="inputCheckbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
                                                 <?php echo JText::_("COM_BOOKPRO_DELETE");?>
                                                </label>                             
                                                
                                            </th>
                                        <?php } ?>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td colspan="9">
                                        <?php echo $pagination->getListFooter(); ?>
                                    </td>
                                </tr>
                            </tfoot>     
 
                         
                            <?php if (! is_array($this->items) || ! $itemsCount && $this->tableTotal) { ?>
                            <tbody>
                                <tr><td colspan="5" class="emptyListInfo"><?php echo JText::_('COM_BOOKPRO_NO_ITEMS_FOUND'); ?></td></tr>
                            </tbody>    
                            <?php 
                            
                                } else {
                                                            
                                     for ($i = 0; $i < $itemsCount; $i++) { 
                                         $subject = &$this->items[$i]; 
                            ?>   <tbody>
                                    <tr class="record">
             
                                            <td><?php echo $subject->package_id ?></td>
                                            <td style="font-weight:normal;"><?php echo $subject->startdate.' '.JText::_('COM_BOOKPRO_TO').' '.$subject->enddate; ?></td>
                                            <td>USD <input type="text" class="span8" size="4" value="<?php echo $subject->dayrate ?>" name="updWeekday_0" id="updWeekday175" readonly="readonly"></td>
                                            <td>USD <input type="text" class="span8" size="4" value="<?php echo $subject->endrate ?>" name="updWeekend_0" id="updWeekend175" readonly="readonly"></td>
                                                <?php if (! $this->selectable) { ?>
                                                    <td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
                                                <?php } ?>         
                                    </tr>
                                 </tbody>   
                                <?php 
                                    }
                                } 
                                ?>              
                     </table>
        
        <div class="clr"></div>
  </div>
   
                     <input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
                    <input type="hidden" name="controller" value="<?php echo CONTROLLER_PACKAGE_RATE; ?>"/>
                    <input type="hidden" name="task" value="save"/>
                    <input type="hidden" name="boxchecked" value="1"/>
                    <input type="hidden" name="tour_id" value="<?php echo $this->lists['tour_id'];?>"/>
                    <input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>
    
    <?php echo JHTML::_('form.token'); ?>
    </form>  	

