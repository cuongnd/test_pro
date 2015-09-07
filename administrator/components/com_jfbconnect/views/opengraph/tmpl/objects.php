<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<div class="sourcecoast">
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <table class="adminlist table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th></th>
                <th class="title"><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_OBJECTS_NAME_LABEL');?></th>
                <th><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_OBJECTS_EXTENSION_LABEL');?></th>
                <th><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_OBJECTS_OBJECTTYPE_LABEL');?></th>
                <th><?php echo JText::_('JPUBLISHED');?></th>
                <th><?php echo JText::_('JGLOBAL_MODIFIED');?></th>
                <th><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_OBJECTS_ID_LABEL');?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($this->objects)
            {
                $key = 0;
                foreach ($this->objects as $object) :
                    $pluginState = (JPluginHelper::isEnabled('opengraph', $object->plugin))? "" : JText::_('COM_JFBCONNECT_OPENGRAPH_PLUGINS_DISABLED_WARNING');
                    ?>
                    <tr class="row<?php echo ($key % 2); ?>">
                        <?php $key++; ?>
                        <td><?php echo $key; ?></td>
                        <td><?php echo $checked = JHTML::_('grid.id', $key, $object->id); ?></td>
                        <td>
                            <a href="<?php echo JRoute::_('index.php?option=com_jfbconnect&controller=opengraph&task=objectedit&plugin=' . $object->plugin . '&name=' . $object->system_name . '&id=' . $object->id); ?>"><?php echo $object->display_name; ?></a>
                        <td class="center"><?php echo ucwords($object->plugin . " - " . $object->system_name).$pluginState; ?></td>
                        <td class="center"><?php echo $object->type ?></td>
                        <td class="center"><?php echo JHTML::_('grid.published', $object, $key) ?></td>
                        <td class="center"><?php echo $object->modified ?></td>
                        <td class="center"><?php echo $object->id ?></td>
                    </tr>
                        <?php
                endforeach;
            }
            ?>
            </tbody>
            <!-- <tfoot>
            <tr>
                <td colspan="8"><?php  #echo $this->page->getListFooter(); ?></td>
            </tr>
            </tfoot>-->
        </table>

        <input type="hidden" name="option" value="com_jfbconnect"/>
        <input type="hidden" name="view" value="<?php echo JRequest::getVar('view'); ?>"/>
        <input type="hidden" name="task" value="<?php echo JRequest::getVar('task'); ?>"/>
        <input type="hidden" name="formtype" value="object"/>
        <input type="hidden" name="boxchecked" value="0"/>
        <?php echo JHTML::_('form.token'); ?>
    </form>
</div>
