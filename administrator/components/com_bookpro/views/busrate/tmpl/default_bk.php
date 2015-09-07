<?php


defined('_JEXEC') or die('Restricted access');
JToolBarHelper::apply();
JToolBarHelper::deleteList('', 'trash', 'Trash');
JToolBarHelper::cancel();
JHtml::_('behavior.formvalidation');
JToolBarHelper::title(JText::_('COM_BOOKPRO_ADD_RATE_MANAGER'), 'object');
$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = & $this->pagination;
?>
<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        var form = document.adminForm;
        var bus_id = form.bus_id.value;
        var check = true;
        form.task.value = task;

        if (form.startdate.value) {
            var startDate = new Date(form.startdate.value);
        } else if (form.enddate.value) {
            var endDate = new Date(form.enddate.value);
        } else if (task == 'apply') {
            if (!bus_id || bus_id == 0) {
                alert('<?php echo JText::_('COM_BOOKPRO_bus_IS_REQUIRED_FIELD'); ?>');
                check = false;
            } else if (!startDate) {
                alert('<?php echo JText::_('COM_BOOKPRO_START_DATE_IS_REQUIRED_FIELD'); ?>');
                check = false;
            } else if (!endDate) {
                alert('<?php echo JText::_('COM_BOOKPRO_END_DATE_IS_REQUIRED_FIELD'); ?>');
                check = false;
            } else if (startDate >= endDate) {
                alert('<?php echo JText::_('COM_BOOKPRO_END_DATA_MUST_BE_GREATER_THAN_START_DATE'); ?>');
                check = false;
            }
        }

        if (check) {
            form.submit();
        }
    }
</script>
<form action="index.php" method="post" name="adminForm" class="form-validate">
    <div class="span4">
        <div class="row-fluid">

            <label><?php echo JText::_('COM_BOOKPRO_bus_'); ?>
            </label>
            <?php echo $this->buss ?>
            <?php $linkrd = ARoute::view('busrates', null, null, array('bustrip_id' => $this->obj->id)); ?>
            <a href="<?php echo $linkrd; ?>" title="Edit"><i class="icon-calendar icon-large"></i>View</a>

            <label><?php echo JText::_('COM_BOOKPRO_START_DATE_'); ?>
            </label>
            <?php echo JHtml::calendar(JFactory::getDate()->format('Y-m-d'), 'startdate', 'startdate', '%Y-%m-%d', 'readonly="readonly"') ?>

            <label><?php echo JText::_('COM_BOOKPRO_END_DATE_'); ?>
            </label>
            <?php echo JHtml::calendar(JFactory::getDate()->add(new DateInterval('P60D'))->format('Y-m-d'), 'enddate', 'enddate', '%Y-%m-%d', 'readonly="readonly"') ?>

            <label><?php echo JText::_('COM_BOOKPRO_WEEK_DAY'); ?>
            </label>

            <?php echo $this->getDayWeek('weekday[]') ?>
            <hr/>

            <table style="width:250px">
                <tr>
                    <td></td>
                    <td><?php echo JText::_('COM_BOOKPRO_ONEWAY'); ?></td>
                    <td><?php echo JText::_('COM_BOOKPRO_ROUNDTRIP'); ?></td>
                </tr>
                <tr>
                    <td><?php echo JText::_('COM_BOOKPRO_PRICE'); ?></td>
                    <td>
                        <input class="input-mini required" type="text" name="adult" id="adult" size="60" maxlength="255" value="0"/>
                    </td>
                    <td>
                        <input class="input-mini required" type="text" name="adult_roundtrip" id="adult_roundtrip" size="60" value="0"/>
                    </td>
                </tr>
                <tr>
                    <td><?php echo JText::_('COM_BOOKPRO_CHILD'); ?></td>
                    <td>
                        <input class="input-mini required" type="text" name="child" id="child" size="60" maxlength="255" value="0"/>
                    </td>
                    <td>
                        <input class="input-mini required" type="text" name="child_roundtrip" id="child_roundtrip" size="60" maxlength="255" value="0"/>
                    </td>
                </tr>
                <tr>
                    <td><?php echo JText::_('COM_BOOKPRO_INFANT'); ?> </td>
                    <td>
                        <input class="input-mini required" type="text" name="infant" id="infant" size="60" maxlength="255" value="0"/>
                    </td>
                    <td>
                        <input class="input-mini required" type="text" name="infant_roundtrip" id="infant_roundtrip" size="60" maxlength="255" value="0"/>
                    </td>
                </tr>
            </table>


        </div>

    </div>

    <div class="form-horizontal span8">

        <table class="table">
            <thead>
            <tr>
                <th width="30%"><?php echo JText::_("COM_BOOKPRO_BUS_TYPE_NAME"); ?>
                </th>
                <th><?php echo JText::_("COM_BOOKPRO_DATE__END_DATE"); ?></th>
                <th><?php echo JText::_("COM_BOOKPRO_ADULT"); ?>
                </th>
                <th><?php echo JText::_("COM_BOOKPRO_CHILD"); ?>
                </th>
                <?php if (!$this->selectable) { ?>
                    <th><label class="checkbox">
                            <input type="checkbox" class="inputCheckbox" name="toggle" value="" onclick="Joomla.checkAll(this);"/> <?php echo JText::_("COM_BOOKPRO_DELETE"); ?>
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


            <?php if (!is_array($this->items) || !$itemsCount && $this->tableTotal) { ?>
                <tbody>
                <tr>
                    <td colspan="5" class="emptyListInfo"><?php echo JText::_('COM_BOOKPRO_NO_ITEMS_FOUND'); ?>
                    </td>
                </tr>
                </tbody>
            <?php

            } else {

                for ($i = 0; $i < $itemsCount; $i++) {
                    $subject = & $this->items[$i];
                    ?>
                    <tbody>
                    <tr class="record">

                        <td><?php echo $subject->bus_id ?></td>
                        <td style="font-weight: normal;"><?php echo $subject->startdate . ' ' . JText::_('COM_BOOKPRO_TO') . ' ' . $subject->enddate; ?>
                        </td>
                        <td><?php echo CurrencyHelper::displayPrice($subject->adult, $subject->adult_discount) ?></td>
                        <td><?php echo CurrencyHelper::displayPrice($subject->child, $subject->child_discount) ?></td>
                        <?php if (!$this->selectable) { ?>
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

    <input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
    <input type="hidden" name="controller" value="busrate"/>
    <input type="hidden" name="task" value="save"/>
    <input type="hidden" name="boxchecked" value="1"/>
    <input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>

    <?php echo JHTML::_('form.token'); ?>
</form>

