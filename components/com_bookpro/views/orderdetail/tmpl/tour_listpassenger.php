<?php
defined('_JEXEC') or die('Restricted access');
$config = AFactory::getConfig();

?>
<?php
$app=  JFactory::getApplication();
$input=$app->input;
?>
<div class="table_passenger">
    <table style="width: 100%" class="table table-bordered">
        <thead>
            <tr>


                <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_FULL_NAME') ?>
                </th>

                <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_BIRTHDAY') ?>
                </th>
                <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_EMAIL') ?>
                </th>
                <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_PHONE1') ?>
                </th>
                <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_ADDRESS') ?>
                </th>
                <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT') ?>
                </th>
                <?php if (!$input->get('tmpl')&&!$this->sendmail) { ?>
                    <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_EDIT') ?>
                    </th>
                <?php } ?>
            </tr>
        </thead>
        <?php
        if (count($this->passengers) > 0) {
            $i = 1;
            foreach ($this->passengers as $pass) {
                ?>
                <tr>

                    <td><?php echo ($i++) . '.' . $pass->firstname . ' ' . $pass->lastname . ' (' . ($pass->gender ? "Male" : "Female") . ')'; ?><?php if($pass->leader){ ?><span class="label label-warning leader"><?php echo JText::_('COM_BOOKPRO_LEADER') ?></span><?php } ?></td>
                    <td><?php echo JHtml::_('date', $pass->birthday, "d-m-Y"); ?></td>
                    <td><?php echo $pass->email; ?></td>
                    <td><?php echo $pass->phone1; ?></td>
                    <td><?php echo $pass->address; ?></td>
                    <td><?php echo $pass->passport; ?></td>
                    <?php if (!$input->get('tmpl')&&!$this->sendmail) { ?>
                        <td><a href="index.php?option=com_bookpro&view=orderdetail&layout=passenger&tpl=edit&order_id=<?php echo $this->order->id ?>&passenger_id=<?php echo $pass->id ?>"  class="btn edit-passenger" ><?php echo JText::_('EDIT') ?></a></td>
                        <?php } ?>
                </tr>
                <?php
            }
        }
        ?>
    </table>
</div>
