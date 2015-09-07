<?php                  
    defined('_JEXEC') or die('Restricted access');
    jimport( 'joomla.html.html.select' );
    //JToolBarHelper::save();
    JToolBarHelper::apply();
    JToolBarHelper::deleteList('', 'trash', 'Trash');
    JToolBarHelper::cancel();
    JHtml::_('behavior.formvalidation');
    JToolBarHelper::title(JText::_('COM_BOOKPRO_ADD_ROOM_PRICE_MANAGER'), 'object');
    $orderDir = $this->lists['order_Dir'];
    $order = $this->lists['order'];
    $itemsCount = count($this->items);
    $pagination = &$this->pagination;	
?>
<script type="text/javascript">       
    Joomla.submitbutton = function(task) {     
        var form = document.adminForm;
        var check = true;  
        form.task.value = task;             
        var startDate   = jQuery('input[name="startdate"]').val();
        var endDate     = jQuery('input[name="enddate"]').val();        

        if(task == 'apply'){  
            if(!startDate){
                alert('<?php echo JText::_('COM_BOOKPRO_START_DATE_IS_REQUIRED_FIELD'); ?>');
                check = false;
            }else if(!endDate){
                alert('<?php echo JText::_('COM_BOOKPRO_END_DATE_IS_REQUIRED_FIELD'); ?>');
                check = false;
            }else if (new Date(startDate) > new Date(endDate)){
                alert('<?php echo JText::_('COM_BOOKPRO_END_DATA_MUST_BE_GREATER_THAN_START_DATE'); ?>');
                check = false;
            }                   
        }              
        if(check){
            form.submit();
        }
    }
</script>  
<style type="text/css">
    table input{
        margin-right:35px;
    }
    table td {
        padding-top:5px;
    }
</style> 
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">     
    <div class="form-horizontal span3 pull-left">   
        <label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_TYPE_NAME'); ?>
            </strong>: <?php echo $this->tourpackagename; ?></label>

        <label ><strong><?php echo JText::_('COM_BOOKPRO_START_DATE_'); ?></strong> </label> 
        <?php echo JHtml::calendar('', 'startdate', 'startdate','%Y-%m-%d','readonly="readonly"') ?>

        <label ><strong><?php echo JText::_('COM_BOOKPRO_END_DATE_'); ?></strong> </label>
        <?php echo JHtml::calendar('', 'enddate', 'enddate','%Y-%m-%d','readonly="readonly"') ?>
        <?php echo $this->roomtypes; ?>
    </div>             


    <div class="form-horizontal span9" style="">
        <?php
            if($this->tour){
            ?>
            <h3><?php echo JText::_('COM_BOOKPRO_TOUR_')."  ".$this->tour->title; ?></h3>  
            <?php
            }
        ?>
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo JText::_("COM_BOOKPRO_PACKAGE_TYPE_NAME");?></th>
                    <th width="15%"><?php echo JText::_("COM_BOOKPRO_DATE__END_DATE");?></th>
                    <th><?php echo JText::_("COM_BOOKPRO_ROOM_TYPE");?></th>
                    <th><?php echo JText::_("COM_BOOKPRO_ROOM_RATE_PRICE");?></th>
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

                            <td><?php echo $subject->packagetitle; ?></td>
                            <td style="font-weight:normal;"><?php echo $subject->startdate.'<br> '.JText::_('COM_BOOKPRO_TO').' '.$subject->enddate; ?></td>
                            <td>
                                <?php
                                    AImporter::model('roomtype');
                                    $modelRoomType  = new BookProModelRoomType();
                                    $modelRoomType->setId($subject->roomtype_id);
                                    $roomtype       = $modelRoomType->getObject(); 
                                    if(is_object($roomtype)){
                                        echo $roomtype->title;    
                                    }
                                ?>
                            </td>
                            <td>
                                <?php echo $subject->price; ?>
                            </td>

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
    <input type="hidden" name="controller" value="<?php echo CONTROLLER_ROOM_PRICE; ?>"/>
    <input type="hidden" name="task" value="save"/>
    <input type="hidden" name="boxchecked" value="1"/>
    <input type="hidden" name="tour_id" value="<?php echo $this->lists['tour_id'];?>"/>
    <input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>
    <input type="hidden" name="tourpackage_id" value="<?php echo $this->tourpackage_id; ?>" id="tourpackage_id"/>

    <?php echo JHTML::_('form.token'); ?>
    </form>  	

