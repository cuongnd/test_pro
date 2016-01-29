<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-2014 by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v6.2.2
 * @build-date      2014/11/14
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

if(defined('SC30')):
JHtml::_('bootstrap.tooltip');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.multiselect');

$userTableClass = "table table-striped table-condensed";
endif; //SC30

if(defined('SC16')):
JHtml::_('behavior.tooltip');

$userTableClass = "adminlist";
endif; //SC16

$input     = JFactory::getApplication()->input;
$field     = $input->getCmd('field');
$function  = 'jSelectUser_' . $field;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

$providerName  = JRequest::getString('provider');
$provider = JFBCFactory::provider($providerName)->name;
$userList = $this->channelModel->getUserList($provider);

?>
<form action="<?php echo JRoute::_('index.php?option=com_jfbconnect&view=channel&layout=user&provider='.$provider.'&tmpl=component');?>" method="post" name="adminForm" id="adminForm">
    <fieldset class="filter">

        <?php
        if(defined('SC30')):
            ?>
            <div id="filter-bar" class="btn-toolbar">
                <div class="filter-search btn-group pull-left">
                    <label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER'); ?></label>
                    <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('COM_JFBCONNECT_FILTER'); ?>" data-placement="bottom"/>
                </div>
                <div class="btn-group pull-left">
                    <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>" data-placement="bottom"><i class="icon-search"></i></button>
                    <button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" data-placement="bottom" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
                    <button type="button" class="btn" onclick="if (window.parent) window.parent.<?php echo $this->escape($function); ?>('', '<?php echo JText::_('JLIB_FORM_SELECT_USER'); ?>');"><?php echo JText::_('JOPTION_NO_USER'); ?></button>
                </div>
            </div>
        <?
        endif; //SC30
        if(defined('SC16')):
            ?>
            <div class="left">
                <label for="filter_search"><?php echo JText::_('JSEARCH_FILTER'); ?></label>
                <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" size="40" title="<?php echo JText::_('COM_USERS_SEARCH_IN_NAME'); ?>" />
                <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
                <button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
                <button type="button" onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('', '<?php echo JText::_('JLIB_FORM_SELECT_USER') ?>');"><?php echo JText::_('JOPTION_NO_USER')?></button>
            </div>
        <?
        endif; //SC16
        ?>
    </fieldset>
    <h4><?php echo JText::sprintf('COM_JFBCONNECT_CHANNEL_USERS_AUTHENTICATED_LABEL', $provider);?></h4>
    <table class="<?php echo $userTableClass;?>">
        <thead>
        <tr>
            <th class="left">
                <?php echo JHtml::_('grid.sort', 'COM_JFBCONNECT_USERMAP_TITLE_JOOMLA_USER', 'a.name', $listDirn, $listOrder); ?>
            </th>
            <th class="nowrap" width="25%">
                <?php echo JHtml::_('grid.sort', 'JGLOBAL_USERNAME', 'a.username', $listDirn, $listOrder); ?>
            </th>
        </tr>
        </thead>
        <!--<tfoot>
        <tr>
            <td colspan="15">
                <?php /*echo $this->pagination->getListFooter(); */?>
            </td>
        </tr>
        </tfoot>-->
        <tbody>
        <?php
        $i = 0;

        foreach ($userList as $item) : ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td>
                    <a class="pointer" onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('<?php echo $item->id; ?>', '<?php echo $this->escape(addslashes($item->name)); ?>');">
                        <?php echo $item->name; ?></a>
                </td>
                <td align="center">
                    <?php echo $item->username; ?>
                </td>
                <!--<td align="left">
                    <?php /*echo nl2br($item->group_names);*/ ?>
                </td>-->
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="field" value="<?php echo $this->escape($field); ?>" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>