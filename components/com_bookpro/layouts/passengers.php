<?php
defined('_JEXEC') or die('Restricted access');
$config = AFactory::getConfig();
JHTML::_('behavior.modal', "a.edit-passenger");
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
                 <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_EDIT') ?>
                </th>
            </tr>
        </thead>
        <?php
        if (count($displayData) > 0) {
            $i=1;
            foreach ($displayData as $pass) {
                ?>
                <tr>
                   
                    <td><?php echo ($i++).'.'.$pass->firstname.' '.$pass->lastname.' ('.($pass->gender ? "Male" : "Female").')'; ?></td>
                    <td><?php echo JHtml::_('date', $pass->birthday, "d-m-Y"); ?></td>
                    <td><?php echo $pass->email; ?></td>
                    <td><?php echo $pass->phone1; ?></td>
                    <td><?php echo $pass->address; ?></td>
                    <td><?php echo $pass->passport; ?></td>
                   
                    <td><a href="index.php?option=com_bookpro&controller=passenger&task=edit&passenger_id=<?php echo $pass->id ?>" rel="{handler: 'iframe', size: {x: 800, y: 500}}" class="btn edit-passenger" ><?php echo JText::_('EDIT') ?></a></td>

                </tr>
                <?php
            }
        }
        ?>
    </table>
</div>
