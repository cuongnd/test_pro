<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.html.select');
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
$checkGroupTour = 'false';
if (!$this->tour->daytrip) {
    if (trim($this->tour->stype) == "shared") {
        $checkGroupTour = 'true';
    }
}
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task) {
        var form = document.adminForm;
        var check = true;
        form.task.value = task;
        var startDate = jQuery('input[name="startdate"]').val();
        var endDate = jQuery('input[name="enddate"]').val();

        if (task == 'apply') {
            if (!startDate) {
                alert('<?php echo JText::_('COM_BOOKPRO_START_DATE_IS_REQUIRED_FIELD'); ?>');
                check = false;
            } else if (!endDate) {
                alert('<?php echo JText::_('COM_BOOKPRO_END_DATE_IS_REQUIRED_FIELD'); ?>');
                check = false;
            } else if (new Date(startDate) > new Date(endDate)) {
                alert('<?php echo JText::_('COM_BOOKPRO_END_DATA_MUST_BE_GREATER_THAN_START_DATE'); ?>');
                check = false;
            } else if ("<?php echo $checkGroupTour; ?>" == "true") {
                if (new Date(startDate) < new Date(endDate)) {
                    alert('<?php echo JText::_('COM_BOOKPRO_THIS_TOUR_SHARED_SHOULD_SELECTE_ONLY_ONE_DAY'); ?>');
                    check = false;
                }
            }
        }
        if (check) {
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
        <label ><?php echo JText::_('COM_BOOKPRO_TOUR_PACKAGE_NAME') . ":"; ?><strong> <?php echo $this->tourpackagename; ?></strong></label>

        <label ><strong><?php echo JText::_('COM_BOOKPRO_START_DATE_'); ?></strong> </label> 
        <?php echo JHtml::calendar('', 'startdate', 'startdate', '%Y-%m-%d', 'readonly="readonly"') ?>

        <label ><strong><?php echo JText::_('COM_BOOKPRO_END_DATE_'); ?></strong> </label>
        <?php echo JHtml::calendar('', 'enddate', 'enddate', '%Y-%m-%d', 'readonly="readonly"') ?>

        <table>
            <tr>
                <td><label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_ADULT'); ?></strong> </label></td>
                <td><label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_TEEN'); ?></strong> </label></td>
                <td><label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_EXTRA_BED'); ?></strong> </label></td>
            </tr>
            <tr>
                <td><input class="text_area input-mini" type="text" name="adult" id="adult" size="60" maxlength="255" /></td>
                <td><input class="text_area input-mini" type="text" name="teen" id="teen" size="60" maxlength="255" /></td>
                <td><input class="text_area input-mini" type="text" name="extra_bed" id="extra_bed" size="60" maxlength="255" value="" /></td>
            </tr>
        </table>

        <table>
            <tr>
                <td><label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_CHILD1'); ?></strong> </label></td>
                <td><label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_CHILD2'); ?></strong> </label></td>
                <td><label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_CHILD3'); ?></strong> </label></td>


            </tr>
            <tr>                   
                <td><input class="text_area input-mini" type="text" name="child1" id="child1" size="60" maxlength="255" value="" /></td>
                <td><input class="text_area input-mini" type="text" name="child2" id="child2" size="60" maxlength="255" value="" /></td> 
                <td><input class="text_area input-mini" type="text" name="child3" id="child3" size="60" maxlength="255" value="" /></td> 

            </tr>
        </table>

        <table>
            <tr>
                <td><label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_ADULT_PROMO'); ?></strong> </label></td>
                <td><label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_TEEN_PROMO'); ?></strong> </label></td>
                <td><label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_CHILD_PROMO'); ?></strong> </label></td>


            </tr>
            <tr>                   
                <td><input class="text_area input-mini" type="text" name="adult_promo" id="adult_promo" size="60" maxlength="255" value="" /></td>
                <td><input class="text_area input-mini" type="text" name="teen_promo" id="teen_promo" size="60" maxlength="255" value="" /></td> 
                <td><input class="text_area input-mini" type="text" name="child_promo" id="child_promo" size="60" maxlength="255" value="" /></td> 

            </tr>
        </table>

        <table>
            <tr>
                <td><label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_POST_TRANSFER'); ?></strong> </label></td>
                <td><label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_PRETRANSFER'); ?></strong> </label></td>

            </tr>
            <tr>
                <td><input class="text_area input-mini" type="text" name="posttransfer" id="posttransfer" size="60" maxlength="255" /></td>
                <td><input class="text_area input-mini" type="text" name="pretransfer" id="pretransfer" size="60" maxlength="255" /></td>

            </tr>
        </table>

<!--        <table>
            <tr>
                
                <td><label><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_AVAILABLE'); ?></strong> </label></td>
                <td><label><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_REQUEST'); ?></strong> </label></td>
                <td><label><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_GUARANTEED'); ?></strong> </label></td>
            </tr>
            <tr>
                <td><input class="checkbox" type="checkbox" name="state" value="1"/></td>
                <td><input class="checkbox" type="checkbox" name="available" value="1"/></td>
                <td><input class="checkbox" type="checkbox" name="request" value="1"/></td>
                <td><input class="checkbox" type="checkbox" name="guaranteed" value="1"/></td>
            </tr>
        </table>-->

        <table>

            <tr>
                <label>
                    
                    <td style="padding-right:40px;"><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_AVAILABLE'); ?></strong></td>
                    <td><strong>YES</strong><input type="radio" name="available" value="1"></td>
                    <td><strong>NO </strong>  <input type="radio" name="available" value="0"></td>
                    
                </label>
            </tr>
            
            <tr>
                <label>
                   
                        <td><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_REQUEST'); ?></strong> </td>
                        <td><strong>YES</strong> <input type="radio" name="request" value="1"></td>
                        <td><strong>NO</strong>    <input type="radio" name="request" value="0"></td>
                     
                </label>
            </tr>
            
            <tr>
                <label>
                    
                        <td><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_GUARANTEED'); ?></strong> </td>
                        <td><strong>YES</strong> <input type="radio" name="guaranteed" value="1"></td>
                        <td><strong>NO</strong>    <input type="radio" name="guaranteed" value="0"></td>
                    
                </label>
            </tr>
            
            <tr>
                <label>
                   
                        <td><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_CLOSE'); ?></strong> </td>
                        <td><strong>YES</strong> <input type="radio" name="close" value="1"></td>
                        <td><strong>NO</strong>    <input type="radio" name="close" value="0"></td>
                    
                </label>
            </tr>

           



        </table>

    </div>             

    <div class="form-horizontal span9" style="">
        <?php
        if ($this->tour) {
            ?>
            <h3><?php echo JText::_('COM_BOOKPRO_TOUR_') . "  " . $this->tour->title; ?></h3>  
            <?php
        }
        ?>
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo JText::_("COM_BOOKPRO_PACKAGE_TYPE_NAME"); ?></th>
                    <th width="15%"><?php echo JText::_("COM_BOOKPRO_DATE__END_DATE"); ?></th>
                    <th><?php echo JText::_("COM_BOOKPRO_PACKAGE_RATE_PRICE"); ?></th>
                    <?php if (!$this->selectable) { ?>
                        <th>
                            <label class="checkbox">
                                <input type="checkbox" class="inputCheckbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
                                <?php echo JText::_("COM_BOOKPRO_DELETE"); ?>
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


            <?php if (!is_array($this->items) || !$itemsCount && $this->tableTotal) { ?>
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
                            <td style="font-weight:normal;"><?php echo $subject->startdate . '<br> ' . JText::_('COM_BOOKPRO_TO') . ' ' . $subject->enddate; ?></td>
                            <td>
                                <?php
                                echo
                                JText::_('COM_BOOKPRO_PACKAGE_RATE_ADULT') . ": " . "<strong>" . $subject->adult . "</strong>" . ", " .
                                Jtext::_('COM_BOOKPRO_PACKAGE_RATE_TEEN') . ": " . "<strong>" . $subject->teen . "</strong>" . ", " .
                                Jtext::_('COM_BOOKPRO_PACKAGE_RATE_EXTRA_BED') . ": " . "<strong>" . $subject->extra_bed . "</strong>" . ", " .
                                Jtext::_('COM_BOOKPRO_PACKAGE_RATE_CHILD1') . ": " . "<strong>" . $subject->child1 . "</strong>" . ", " .
                                Jtext::_('COM_BOOKPRO_PACKAGE_RATE_CHILD2') . ": " . "<strong>" . $subject->child2 . "</strong>" . ", " .
                                Jtext::_('COM_BOOKPRO_PACKAGE_RATE_CHILD3') . ": " . "<strong>" . $subject->child3 . "</strong>" . ", " .
                                Jtext::_('COM_BOOKPRO_PACKAGE_RATE_ADULT_PROMO') . ": " . "<strong>" . $subject->adult_promo . "</strong>" . ", " .
                                Jtext::_('COM_BOOKPRO_PACKAGE_RATE_TEEN_PROMO') . ": " . "<strong>" . $subject->teen_promo . "</strong>" . ", " .
                                Jtext::_('COM_BOOKPRO_PACKAGE_RATE_CHILD_PROMO') . ": " . "<strong>" . $subject->child_promo . "</strong>" . ", " .
                                Jtext::_('COM_BOOKPRO_PACKAGE_RATE_POST_TRANSFER') . ": " . "<strong>" . $subject->posttransfer . "</strong>" . ", " .
                                Jtext::_('COM_BOOKPRO_PACKAGE_RATE_PRETRANSFER') . ": " . "<strong>" . $subject->pretransfer . "</strong><br>" . ", " .
                                 Jtext::_('COM_BOOKPRO_PACKAGE_RATE_AVAILABLE') . ": " . "<strong>" . $subject->available . "</strong><br>" . ", " .
                                 Jtext::_('COM_BOOKPRO_PACKAGE_RATE_REQUEST') . ": " . "<strong>" . $subject->request . "</strong><br>" . ", " .
                                 Jtext::_('COM_BOOKPRO_PACKAGE_RATE_GUARANTEED') . ": " . "<strong>" . $subject->guaranteed . "</strong><br>" . ", " .
                                 Jtext::_('COM_BOOKPRO_PACKAGE_RATE_CLOSE') . ": " . "<strong>" . $subject->close . "</strong><br>"
                                ;
                                ?>
                            </td>

                            <?php if (!$this->selectable) { ?>
                                <td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
                            <?php } ?>         
                        </tr>
                    </tbody>   
                    <?php
                }
            }
            // echo "<pre>";var_dump($subject);
            ?>              
        </table>

        <div class="clr"></div>
    </div>

    <input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
    <input type="hidden" name="controller" value="<?php echo CONTROLLER_PACKAGE_RATEDAYTRIPJOINGROUP; ?>"/>
    <input type="hidden" name="task" value="save"/>
    <input type="hidden" name="boxchecked" value="1"/>
    <input type="hidden" name="tour_id" value="<?php echo $this->lists['tour_id']; ?>"/>
    <input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>
    <input type="hidden" name="tourpackage_id" value="<?php echo $this->tourpackage_id; ?>" id="tourpackage_id"/>

    <?php echo JHTML::_('form.token'); ?>
</form>  	

