<?php
    defined ( '_JEXEC' ) or die ();
    JHtml::_ ( 'dropdown.init' );
    JHtml::_ ( 'formbehavior.chosen', 'select' );
    BookProHelper::setSubmenu ( 1 );
    $input=JFactory::getApplication()->input;
    $type=$input->get('type','','string');
    $object_id=$input->get('object_id',0,'int');
?>

<form
    action="<?php echo JRoute::_('index.php?option=com_bookpro&view=addons'); ?>"
    method="post" name="adminForm" id="adminForm">

    <?php if (!empty( $this->sidebar)) : ?>
        <div id="j-sidebar-container" class="span2">
            <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
        <?php else : ?>
        <div id="j-main-container" class="span10">
        <?php endif;?>
    <div id="filter-bar" class="btn-toolbar">
        <div class="filter-search btn-group pull-left">
            <label for="filter_search" class="element-invisible"><?php echo JText::_('COM_BOOKPRO_SEARCH_IN_TITLE');?></label>
            <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('COM_BOOKPRO_SEARCH_IN_TITLE'); ?>" />
        </div>
        <div class="btn-group pull-left">
            <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
            <button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
        </div>
        <div class="btn-group pull-right hidden-phone">
            <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
            <?php echo $this->pagination->getLimitBox(); ?>
        </div>
    </div>
    <div class="clearfix"></div>


    <table class="table-striped table">
        <thead>
            <tr>
                <th width="1%" class="nowrap center hidden-phone">
                    <?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
                </th>

                <th width="1%" class="hidden-phone">
                    <?php echo JHtml::_('grid.checkall'); ?>
                </th>
                <th width="1%" style="min-width: 55px" class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                </th>
                <th>
                    <?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'l.title', $this->escape($this->state->get('list.direction')), $this->escape($this->state->get('list.ordering'))); ?>
                </th>

                <th>
                    <?php echo JHtml::_('grid.sort', 'COM_BOOKPRO_ADDON_TYPE', 'l.title', $this->escape($this->state->get('list.direction')), $this->escape($this->state->get('list.ordering'))); ?>
                </th>

                <th>
                    <?php echo JHtml::_('grid.sort', 'COM_BOOKPRO_ADDON_PRICE', 'l.title', $this->escape($this->state->get('list.direction')), $this->escape($this->state->get('list.ordering'))); ?>
                </th>




                <th width="1%" class="nowrap">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'l.id', $this->escape($this->state->get('list.direction')), $this->escape($this->state->get('list.ordering'))); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="8">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>

            <?php foreach ( $this->items as $i => $item ) {
                    $ordering = ($listOrder == 'a.ordering'); 
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td class="order nowrap center hidden-phone">
                        <?php
                            $iconClass = '';
                            if (! $canChange) {
                                $iconClass = ' inactive';
                            } elseif (! $saveOrder) {
                                $iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText ( 'JORDERINGDISABLED' );
                            }
                        ?>
                        <span class="sortable-handler<?php echo $iconClass ?>"> <i
                            class="icon-menu"></i>
                        </span>
                        <?php if ($canChange && $saveOrder) : ?>
                            <input type="text" style="display: none" name="order[]" size="5"
                                value="<?php echo $item->ordering;?>"
                                class="width-20 text-area-order " />
                            <?php endif; ?>
                    </td>

                    <td class="center">
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    </td>

                    <td class="center">
                        <?php echo JHtml::_('jgrid.published', $item->state, $i, 'facilities.', true, 'cb', $item->publish_up, $item->publish_down); ?>
                    </td>
                    <td>
                        <?php if ($item->checked_out) { ?>
                            <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'location.', true); ?>
                            <?php } ?>
                        <a
                            href="<?php echo JRoute::_('index.php?option=com_bookpro&task=addon.edit&id='.$item->id);?>">
                            <?php echo $this->escape($item->title); ?>
                        </a>
                    </td>
                    <td><?php echo $this->escape($item->type); ?></td>

                    <td><?php echo $this->escape($item->price); ?></td>

                    <td class="center">
                        <?php echo (int) $item->id; ?>
                    </td>
                </tr>
                <?php } ?>
        </tbody>
    </table>
    <div>
        <input type="hidden" name="task" value="" /> <input type="hidden"
            name="boxchecked" value="0" /> <input type="hidden"
            name="filter_order"
            value="<?php echo $this->escape($this->state->get('list.ordering')); ?>" />
        <input type="hidden" name="type" value="<?php echo $type ?>">
        <input type="hidden" name="object_id" value="<?php echo $object_id ?>">
        <input type="hidden" name="filter_order_Dir"
            value="<?php echo $this->escape($this->state->get('list.direction')); ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>

</form>