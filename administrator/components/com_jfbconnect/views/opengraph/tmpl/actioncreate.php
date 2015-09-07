<?php
/***
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
?>
<h2 class="modal-title"><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTIONCREATE_LABEL');?></h2>
<ul class="menu_types">

    <?php
    if (count($this->plugins) > 0)
    {
        foreach ($this->plugins as $plugin) : ?>
            <?php if (count($plugin->supportedActions) > 0)
            { ?>
                <li>
                    <dl class="menu_type">
                        <dt><?php echo $plugin->extensionName;?></dt>
                        <dd>
                            <ul>
                                <?php foreach ($plugin->supportedActions as $key => $value): ?>
                                <li><a class="choose_type"
                                       href="#"
                                       onclick="top.location = '<?php echo JRoute::_('index.php?option=com_jfbconnect&controller=opengraph&task=actionedit&plugin=' . $plugin->pluginName . '&name=' . $value); ?>';">
                                    <?php echo $key;?>
                                </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </dd>
                    </dl>
                </li>
                <?php
            } ?>
            <?php endforeach;
    }
    ?>
    <li>
        <dl class="menu_type">
            <dt><?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTIONCREATE_CUSTOMACTION_LABEL');?></dt>
            <dd>
                <ul>
                    <li>
                        <a class="choose_type"
                           href="#"
                           onclick="top.location = '<?php echo JRoute::_('index.php?option=com_jfbconnect&controller=opengraph&task=actionedit&id=0'); ?>';">
                            <?php echo JText::_('COM_JFBCONNECT_OPENGRAPH_ACTIONCREATE_CUSTOMACTION_DESC');?>
                        </a>
                    </li>
                </ul>
            </dd>
        </dl>
    </li>
</ul>
