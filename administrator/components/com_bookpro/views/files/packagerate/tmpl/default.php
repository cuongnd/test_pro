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
        var tourpackage_id = form.tourpackage_id.value;
        var check = true;  
        form.task.value = task;

        if(form.startdate.value){
            var startDate = new Date(form.startdate.value);
        }else if(form.enddate.value){
            var endDate = new Date(form.enddate.value);
        }else if (task == 'apply') {
            if(!tourpackage_id || tourpackage_id==0){
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
        <label ><strong><?php echo JText::_('COM_BOOKPRO_TOUR_PACKAGES'); ?></strong> </label>
        <?php echo $this->tourpackages; ?>

        <label ><strong><?php echo JText::_('COM_BOOKPRO_START_DATE_'); ?></strong> </label> 
        <?php echo JHtml::calendar('', 'startdate', 'startdate','%Y-%m-%d','readonly="readonly"') ?>

        <label ><strong><?php echo JText::_('COM_BOOKPRO_END_DATE_'); ?></strong> </label>
        <?php echo JHtml::calendar('', 'enddate', 'enddate','%Y-%m-%d','readonly="readonly"') ?>
        
        <table>
            <tr>
                <td><label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_ADULT'); ?></strong> </label></td>
                <td><label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_TEEN'); ?></strong> </label></td>
            </tr>
            <tr>
                <td><input class="text_area input-mini" type="text" name="adult" id="adult" size="60" maxlength="255" /></td>
                <td><input class="text_area input-mini" type="text" name="teen" id="teen" size="60" maxlength="255" /></td>
            </tr>
        </table>
        <table>
            <tr>
                <td><label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_CHILD1'); ?></strong> </label></td>
                <td><label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_CHILD2'); ?></strong> </label></td>
                <td><label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_EXTRA_BED'); ?></strong> </label></td>

            </tr>
            <tr>                   
                <td><input class="text_area input-mini" type="text" name="child1" id="child1" size="60" maxlength="255" value="" /></td>
                <td><input class="text_area input-mini" type="text" name="child2" id="child2" size="60" maxlength="255" value="" /></td> 
                 <td><input class="text_area input-mini" type="text" name="extra_bed" id="extra_bed" size="60" maxlength="255" value="" /></td>
            </tr>
        </table>
            
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
                    <th><?php echo JText::_("COM_BOOKPRO_PACKAGE_RATE_PRICE");?></th>
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
                                <?php echo 
                                    JText::_('Adult:').$subject->adult."</strong>".", ".
                                    Jtext::_('Teen:')."<strong>".$subject->teen."</strong>".", ".
                                    Jtext::_('Child1:')."<strong>".$subject->child1."</strong>".", ".
                                    Jtext::_('Child2:')."<strong>".$subject->child2."</strong>".", ".
                                    Jtext::_('Entra Bed:')."<strong>".$subject->extra_bed."</strong>".",<br /> "
                                    ; ?>
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
    <input type="hidden" name="controller" value="<?php echo CONTROLLER_PACKAGE_RATE; ?>"/>
    <input type="hidden" name="task" value="save"/>
    <input type="hidden" name="boxchecked" value="1"/>
    <input type="hidden" name="tour_id" value="<?php echo $this->lists['tour_id'];?>"/>
    <input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>

    <?php echo JHTML::_('form.token'); ?>
    </form>  	

