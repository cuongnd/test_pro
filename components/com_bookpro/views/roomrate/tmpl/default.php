<?php


defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.html.html.select' );
JHTML::_('behavior.tooltip'); 
AImporter::helper('date','bookpro','currency','hotel');
$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = &$this->pagination;    
?>
<script type="text/javascript">  

function onSutmit(){        
      var check = true;  
      var room_id = jQuery("select[name='room_id']").val(); 
                       
      if(jQuery("input[name='startdate']").val()){
        var startDate = new Date(jQuery("input[name='startdate']").val()); 
      }
       
      if(jQuery("input[name='enddate']").val()){
        var endDate = new Date(jQuery("input[name='enddate']").val());
      }

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
        }else{
            if(!jQuery("#dayrate").val()){
                alert('<?php echo JText::_('COM_BOOKPRO_WEEKDAY_PRICE_IS_REQUIRED_FIELD'); ?>');
                check = false;
            }else if(!IsNumeric(jQuery("#dayrate").val())){
                alert('<?php echo JText::_('COM_BOOKPRO_WEEKDAY_PRICE_FORMAT_IS_INCORRECT'); ?>');
                check = false;
            }else if(!jQuery("#endrate").val()){
                alert('<?php echo JText::_('COM_BOOKPRO_ENDRATE_PRICE_IS_REQUIRED_FIELD'); ?>');
                check = false;
            }else if(!IsNumeric(jQuery("#endrate").val())){
                alert('<?php echo JText::_('COM_BOOKPRO_ENDRATE_PRICE_FORMAT_IS_INCORRECT'); ?>');
                check = false;
            }   
        }                   
    
      if(check){                         
          jQuery("#roomrate").submit();
      }else{
          return false;
      }
    }  

     function IsNumeric(input){
        var RE = /^-{0,1}\d*\.{0,1}\d+$/;
        return (RE.test(input));
        }                                                                                    
    </script>   
    
<div class="row-fluid">
<div class="span12">    
    <?php
        $layout = new JLayoutFile('suppliermenu', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
        $html = $layout->render(array());
        echo $html;
    ?>
 <fieldset>                                                       
        <legend><?php echo JText::_('COM_BOOKPRO_ADD_ROOM_RATE_FOR_HOTEL').' '.$this->hotel->title; ?></legend>     
    
             
<form action="index.php" method="post" name="roomrate" id="roomrate">               
<div style="margin-bottom: 10px;" class="row-fluid">
    <div class="form-horizontal" style="float: left;">   
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_ROOM_'); ?></strong> </label>
                    <?php echo $this->rooms ?>
                    
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_START_DATE_'); ?></strong> </label> 
                    <?php echo JHtml::calendar('', 'startdate', 'startdate','%Y-%m-%d','readonly="readonly" style="width:100px;"') ?>
             
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_END_DATE_'); ?></strong> </label>
                   <?php echo JHtml::calendar('', 'enddate', 'enddate','%Y-%m-%d','readonly="readonly" style="width:100px;"') ?>
                
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_WEEKDAY'); ?></strong> </label>
                     <input class="text_area" type="text" name="dayrate" id="dayrate" size="60" maxlength="255" value="" />
                    
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_WEEKEND'); ?></strong> </label>
                    <input class="text_area" type="text" name="endrate" id="endrate" size="60" maxlength="255" value="" />                               
                                       
    </div>             

  <div class="form-horizontal span9" style="">

                <table class="table">
                            <thead>
                                <tr>
                                        <th><?php echo JText::_("COM_BOOKPRO_ROOM_TYPE_NAME");?></th>
                                        <th><?php echo JText::_("COM_BOOKPRO_DATE__END_DATE");?></th>
                                        <th><?php echo JText::_("COM_BOOKPRO_WEEDDAY");?></th>
                                        <th><?php echo JText::_("COM_BOOKPRO_WEEDEND");?></th>
                                        <th width="5%">
                                            <?php echo JText::_('COM_BOOKPRO_DELETE'); ?>
                                        </th> 
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td colspan="9">
                                        <?php echo $pagination->getListFooter(); ?>
                                    </td>
                                </tr>
                            </tfoot>     
 
                         
                            <?php if (! is_array($this->items) || ! $itemsCount) { ?>
                            <tbody>
                                <tr><td colspan="5" class="emptyListInfo"><?php echo JText::_('COM_BOOKPRO_NO_ITEMS_FOUND'); ?></td></tr>
                            </tbody>    
                            <?php 
                            
                                } else {
                                                            
                                     for ($i = 0; $i < $itemsCount; $i++) { 
                                         $subject = &$this->items[$i]; 
                            ?>   <tbody>
                                    <tr class="record">
             
                                            <td><?php echo $subject->room_id ?></td>
                                            <td style="font-weight:normal;"><?php echo $subject->startdate.' to '.$subject->enddate; ?></td>
                                            <td><input type="text" style="width:80px; margin:0;" size="4" value="<?php echo $subject->dayrate ?>" name="updWeekday_0" id="updWeekday175" readonly="readonly"></td>
                                            <td><input type="text" style="width:80px; margin:0;" size="4" value="<?php echo $subject->endrate ?>" name="updWeekend_0" id="updWeekend175" readonly="readonly"></td>
                                            <td style="text-align: center;">                
                                                <a href="javascript:void(0)" onclick="Delete('<?php echo $subject->id; ?>');" title="<?php echo JText::_('COM_BOOKPRO_DELETE'); ?>"><span class="icon-remove-sign">&nbsp;</span></a>
                                            </td>        
                                    </tr>
                                 </tbody>   
                                <?php 
                                    }
                                } 
                                ?>              
                     </table>
        
        <div class="clr"></div>
  </div>
</div>
                    <input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
                    <input type="hidden" name="controller" value="<?php echo CONTROLLER_ROOM_RATE; ?>"/>
                    <input type="hidden" name="task" value="save" id="task"/>
                    <input type="hidden" name="cid[]" value="" id="cid"/>
                    <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar(Itemid);?>" id="Itemid"/>
                    <input type="hidden" name="hotel_id" value="<?php echo JRequest::getVar(hotel_id);?>"/>
      
    <?php echo JHTML::_('form.token'); ?>
</form>  
                    <div class="center-button span2">
                        <input type="button" class="btn btn-primary" name="submit"  value="<?php echo JText::_('COM_BOOKPRO_SUBMIT');?>" onclick="onSutmit();"/>
                    </div> 
            </fieldset>    
        </div>          
</div>     
<script type="text/javascript">    
    function Delete(id)
    {    
        jQuery("input[name='task']").val('trash'); 
        jQuery("input[name='cid[]']").val(id);    
        jQuery("#roomrate").submit();
    }
</script>
  

