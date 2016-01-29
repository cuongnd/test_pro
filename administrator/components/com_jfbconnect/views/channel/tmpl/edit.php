<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-2014 by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v6.2.4
 * @build-date      2014/12/15
 */

$attribsShown = false;
?>
<div class="sourcecoast channel">
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div class="row-fluid">
            <?php
            foreach ($this->form->getFieldsets() as $fiedsets => $fieldset)
            {
                if ($fieldset->name == 'attribs')
                {
                    $attribsShown = true;

                    echo '</div>';
                    echo '<div class="row-fluid">';
                    echo '<div class="span12">' . "\n";
                    echo '<legend>' . JText::_('COM_JFBCONNECT_CHANNEL_EDIT_ATTRIBUTES') . "</legend>\n";
                    echo '<div id="channel-attribs" class="well">' . "\n";
                    echo $this->displayAttributes();
                    echo "</div>\n";

                    echo '</div>';
                    echo '<div class="row-fluid">';
                    echo '<div class="span12">' . "\n";
                    echo '<legend>' . JText::_('COM_JFBCONNECT_CHANNEL_EDIT_STREAM') . "</legend>\n";
                    echo '<div id="channel-test" class="well">' . "\n";
                    echo $this->displayTest();
                    echo "</div>\n";
                }
                else
                {
                    echo '<div class="span6">' . "\n";
                    echo "<div class=\"well\">\n";
                    echo '<legend>' . JText::_(strtoupper($this->form->getName()) . '_MENU_' . strtoupper($fieldset->name)) . "</legend>\n";
                    foreach ($this->form->getFieldset($fieldset->name) as $field)
                        $this->formShowField($field);
                    echo "</div>\n";
                    echo "</div>\n";
                }
            }
            if (!$attribsShown)
            {
            ?>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <legend><?php echo JText::_('COM_JFBCONNECT_CHANNEL_EDIT_ATTRIBUTES'); ?></legend>
                <div id="channel-attribs" class="well">
                    <?php echo JText::_('COM_JFBCONNECT_CHANNEL_EDIT_ATTRIBUTES_DESC');?>
                </div>
            </div>
            <?php
            }
            ?>
        </div>

        <input type="hidden" name="option" value="com_jfbconnect" />
        <input type="hidden" name="view" value="channel" />
        <input type="hidden" name="task" value="<?php echo JRequest::getVar('task'); ?>" />
        <input type="hidden" name="id" value="<?php echo JRequest::getVar('id'); ?>" />
        <?php echo JHTML::_('form.token'); ?>
    </form>
</div>
