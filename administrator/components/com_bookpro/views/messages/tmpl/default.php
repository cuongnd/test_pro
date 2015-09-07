<?php
defined('_JEXEC') or die();
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
BookProHelper::setSubmenu(1);
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$saveOrder = $this->sortColumn == 'l.ordering';

//$sortFields = $this->getSortFields();
?>


<form action = "<?php echo JRoute::_('index.php?option=com_bookpro&view=messages'); ?>" method = "post" name = "adminForm" id = "adminForm">

    <?php if (!empty($this->sidebar)) : ?>
    <div id = "j-sidebar-container" class = "span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id = "j-main-container" class = "span10">
        <?php else : ?>
        <div id = "j-main-container" class = "span10">
            <?php endif; ?>
            <div id = "filter-bar" class = "btn-toolbar">
                <div class = "filter-search btn-group pull-left">
                    <label for = "filter_search" class = "element-invisible"><?php echo JText::_('COM_BOOKPRO_SEARCH_IN_TITLE'); ?></label>
                    <input type = "text" name = "filter_search" id = "filter_search" value = "<?php echo $this->escape($this->state->get('filter.search')); ?>" placeholder = "<?php echo JText::_('COM_BOOKPRO_MESSAGE_SUBJECT') ?>">
                </div>
                <div class = "btn-group pull-left">
                    <button type = "submit" class = "btn hasTooltip" title = "<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><i class = "icon-search"></i></button>
                    <button type = "button" class = "btn hasTooltip" title = "<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick = "document.id('filter_search').value = '';
                            this.form.submit();"><i class = "icon-remove"></i></button>
                </div>
                <div class = "btn-group pull-right hidden-phone">
                    <label for = "limit" class = "element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
                </div>
            </div>
            <div class = "clearfix"></div>


            <table class = "table-striped table" id = "addonList">
                <thead>
                <tr class = "sortable">
                    <th width = "1%" class = "nowrap center hidden-phone">
                        <?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'l.ordering', $this->sortDirection, $this->sortColumn); ?>
                    </th>

                    <th width = "1%" class = "hidden-phone">
                        <?php echo JHtml::_('grid.checkall'); ?>
                    </th>
                    <th width = "1%" class = "nowrap center">
                        <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $this->sortDirection, $this->sortColumn); ?>
                    </th>

                    <th class = "title" width = "20%">
                        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_MESSAGE_SUBJECT'), 'subject', $orderDir, $order); ?>
                    </th>

                    <th width = "10%">
                        <?php echo JText::_('COM_BOOKPRO_MESSAGE_USER_STATE'); ?>
                    </th>
                    <th width = "5%">
                        <?php echo JText::_('COM_BOOKPRO_MESSAGE_REPLY'); ?>
                    </th>

                    <th width = "5%">
                        <?php echo JText::_('COM_BOOKPRO_CREATED_BY_LABEL'); ?>
                    </th>

                    <th width = "10%">
                        <?php echo JText::_('COM_BOOKPRO_CREATED'); ?>
                    </th>

                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan = "7">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
                </tfoot>
                <tbody>

                <?php
                foreach ($this->items as $i => $item) {
                    $item->max_ordering = 0;
                    $ordering = ($listOrder == 'a.ordering');
                    ?>
                    <tr class = "row<?php echo $i % 2; ?>">
                        <td class = "order nowrap center hidden-phone">
                            <?php
                            $iconClass = '';
                            if (!$canChange) {
                                $iconClass = ' inactive';
                            } elseif (!$saveOrder) {
                                $iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
                            }
                            ?>
                            <span class = "sortable-handler<?php echo $iconClass ?>"> <i
                                    class = "icon-menu"></i>
                                </span>
                            <?php if ($saveOrder) : ?>
                                <input type = "text" style = "display: none" name = "order[]" size = "5"
                                       value = "<?php echo $item->ordering; ?>"
                                       class = "width-20 text-area-order "/>
                            <?php endif; ?>
                        </td>

                        <td class = "center">
                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>

                        <td class = "center">
                            <?php echo JHtml::_('jgrid.published', $item->state, $i, 'messages.', true, 'cb', $item->publish_up, $item->publish_down); ?>
                        </td>
                        <td>
                            <a href = "<?php echo JRoute::_(ARoute::view('message', null, null, array('parent_id' => $item->id, 'cid_from' => $item->cid_from, 'layout' => 'edit'))); ?>" class = ""><?php echo $item->subject; ?></a>
                        </td>

                        <td>
                            <select class="input-small" data-id="<?php echo $item->id ?>" name="user_state" >
                                <option <?php echo $item->user_state=='close'?'selected':'' ?>  value="close"><?php echo JText::_('Close') ?></option>
                                <option <?php echo $item->user_state=='open'?'selected':'' ?> value="open"><?php echo JText::_('Open') ?></option>
                            </select>
                        </td>

                        <td>
                            <a href = "<?php echo JRoute::_(ARoute::view('message', null, null, array('parent_id' => $item->id, 'cid_from' => $item->cid_from, 'layout' => 'edit'))); ?>" class = "btn btn-success">Reply</a>
                        </td>
                        <td>
                            <?php
                            if ($item->cnameto == 0) {
                                echo $item->cnamefrom;
                            } else {
                                echo $item->cnameto;
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            echo JHtml::_('date', $item->created, 'd-m H:i');
                            ?>
                        </td>

                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
            <div>
                <input type = "hidden" name = "task" value = ""/>
                <input type = "hidden" name = "boxchecked" value = "0"/>
                <input type = "hidden" name = "filter_order" value = "<?php echo $this->escape($this->state->get('list.ordering')); ?>"/>
                <input type = "hidden" name = "filter_order_Dir" value = "<?php echo $this->escape($this->state->get('list.direction')); ?>"/>

                <?php echo JHtml::_('form.token'); ?>
            </div>

</form>
<script type="text/javascript">

    jQuery(document).ready(function($){
        $(document).on('change','select[name="user_state"]',function(){
            id=$(this).attr('data-id');
            user_state=$(this).val();
            $.ajax({
                type: "GET",
                url: 'index.php',
                data: (function() {
                    $data = {
                        option: 'com_bookpro',
                        controller: 'message',
                        task: 'change_user_state',
                        id:id,
                        user_state:user_state
                    }
                    return $data;
                })(),
                beforeSend: function() {
                    $('.widgetbookpro-loading').css({
                        display: "none",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                    // $('.loading').popup();
                },
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });

                }
            });

        });
    });

</script>