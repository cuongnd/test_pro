<?php
defined('_JEXEC') or die('Restricted access');
    JHtml::_ ( 'dropdown.init' );
    JHtml::_ ( 'formbehavior.chosen', 'select' );

    JHtml::_('bootstrap.tooltip');
    JHtml::_('behavior.multiselect');
    
$app = JFactory::getApplication();
$input = $app->input;




BookProHelper::setSubmenu(6);

JToolBarHelper::title(JText::_('COM_BOOKPRO_EMAIL_MANAGER'), 'object');

AImporter::helper('route', 'bookpro', 'request', 'touradministrator');

$colspan = $this->selectable ? 7 : 10;
$notFound = '- ' . JText::_('not found') . ' -';
$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = &$this->pagination;
?>

<script type="text/javascript">
    function tableOrdering( order, dir, task )
    {
        var form = document.adminForm;

        form.filter_order.value = order;
        form.filter_order_Dir.value = dir;
        document.adminForm.submit( task );
    } 

</script> 

<div class="span10">
    <form action="<?php echo JRoute::_('index.php?option=com_bookpro&view=sendemails'); ?>" method="post" name="adminForm" id="adminForm">


    <div id="filter-bar" class="btn-toolbar">
        <div class="filter-search btn-group pull-left">
            <label for="filter_search" class="element-invisible"><?php echo JText::_('COM_BOOKPRO_SEARCH_IN_TITLE');?></label>
            <input type="text" name="filter_search" id="filter_search"  value="<?php echo $this->escape($this->state->get('filter.search')); ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_ADDON_TITLE')?>">
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
   
    
        <table class="table-striped table" >
            <thead>
                <tr>
                    <th width="1%">#</th>

                <th width="1%" class="hidden-phone">
                    <?php echo JHtml::_('grid.checkall'); ?>
                </th>
                <th width="1%" style="min-width: 55px" class="nowrap center">
                    <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $this->sortDirection,  $this->sortColumn); ?>
                </th>

                <th>
                    <?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'l.title', $this->sortDirection, $this->sortColumn); ?>
                </th>
                
                    <th width="15%">
                        <?php echo JText::_('COM_BOOKPRO_APP_CODE'); ?>
                    </th>
                                    
                    <th width="15%">
                        <?php echo JText::_('COM_BOOKPRO_APP_EMAIL_SEND_FROM'); ?>
                    </th>

                    <th width="15%">
                        <?php echo JText::_('COM_BOOKPRO_APP_EMAIL_SEND_FROM_NAME'); ?>
                    </th>
                    
                    <th width="15%">
                        <?php echo JText::_('COM_BOOKPRO_APP_EMAIL_SUBJECT'); ?>
                    </th>
                                                            
                <th width="1%" class="nowrap">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'l.id', $this->sortDirection, $this->sortColumn); ?>
                </th>
                
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="<?php echo $colspan; ?>">
                        <?php echo $pagination->getListFooter(); ?>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <?php if (!is_array($this->items) || !$itemsCount && $this->tableTotal) { ?>
                    <tr><td colspan="<?php echo $colspan; ?>" class="emptyListInfo"><?php echo JText::_('No items found.'); ?></td></tr>
                    <?php
                } else {

                    for ($i = 0; $i < $itemsCount; $i++) {

                        $subject = &$this->items[$i];
                        $link = JRoute::_(ARoute::view('sendemail', null, null, array('id' => $subject->id, 'layout' => 'edit')));
                        $ipath = JUri::root() . $subject->path;
                        $item->max_ordering = 0;    
                    	$ordering = ($listOrder == 'a.ordering');
                        ?>
                        <tr>
                            <td  style="text-align: left; white-space: nowrap;"><?php echo number_format($this->pagination->getRowOffset($i), 0, '', ' '); ?></td>
                           
                           
                            <?php if (!$this->selectable) { ?>
                                <td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
                            <?php } ?>

                            <td class="center">
                                <?php echo JHtml::_('jgrid.published', $subject->state, $i, 'sendemails.', true, 'cb', $subject->publish_up, $subject->publish_down); ?>
                            </td>
                            <td>
                                <a href="<?php echo $link; ?>"><?php echo $subject->title; ?></a>
                            </td>
                            <td>
                                <?php echo $subject->code; ?>
                            </td>                            
                            <td>
                                <?php echo $subject->email_send_from; ?>
                            </td>
                            <td>
                                <?php echo $subject->email_send_from_name; ?>
                            </td>
                            <td>
                                <?php echo $subject->email_subject; ?>
                            </td>                                                        
                            <td style="text-align: right; white-space: nowrap;"><?php echo number_format($subject->id, 0, '', ' '); ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>

        <input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>"/> 
        <input type="hidden" name="boxchecked" value="0"/>
        
       <input type="hidden" name="filter_order" value="<?php echo $this->escape($this->state->get('list.ordering')); ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $this->escape($this->state->get('list.direction')); ?>" />

        <?php echo JHTML::_('form.token'); ?>


    </form>	
</div>