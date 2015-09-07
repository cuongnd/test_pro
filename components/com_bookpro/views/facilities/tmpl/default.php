<?php

    /**
    * @package 	Bookpro
    * @author 		Nguyen Dinh Cuong
    * @link 		http://ibookingonline.com
    * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
    * @version 	$Id: default.php 26 2012-07-08 16:07:54Z quannv $
    **/
    defined('_JEXEC') or die('Restricted access');
    AImporter::helper('hotel');
    JHTML::_('behavior.tooltip');

    $orderDir = $this->lists['order_Dir'];
    $order = $this->lists['order'];
    $itemsCount = count($this->items);
    $pagination = &$this->pagination;

?>  
<script type="text/javascript">    
    function Delete(id)
    {    
        jQuery("input[name='task']").val('trash'); 
        jQuery("input[name='cid[]']").val(id);    
        jQuery("form[name='facilities']").submit();
    }
    function listItemTask(id, task){
        var f = document.facilities;
        var cb = f[id];
        if (cb) {
            for (var i = 0; true; i++) {
                var cbx = f['cb'+i];
                if (!cbx)
                    break;
                //cbx.checked = false;
            } // for
            cb.checked = true;
            f.boxchecked.value = 1;
            f.task.value = task;
            f.submit();
        }
        return false;
    }
    function submitSearch()
    {
        var linkfacilities = "<?php echo JURI::base().'index.php?option=com_bookpro&view=facilities&Itemid='.JRequest::getVar('Itemid');?>";
        linkfacilities = linkfacilities + '&hotel_id=' + jQuery("select[name='search_hotel_id']").val();
        window.location.href = linkfacilities;
    }
</script>


<div class="row-fluid">
    <div class="span12">    
        <?php
            $layout = new JLayoutFile('suppliermenu', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
            $html = $layout->render(array());
            echo $html;
        ?>
        <fieldset>                                                       
            <legend>
                <?php echo JText::_('COM_BOOKPRO_FACILITY_MANAGER');    
                ?>     
            </legend>   
            <div class="row-fluid">
                <div class="btn-group pull-left hidden-phone fltlft">
                    <?php echo $this->hotels; ?>
                </div>  
                <div class="btn-group pull-left hidden-phone fltlft">
                    <button class="btn" onclick="submitSearch();">
                        <?php echo JText::_('COM_BOOKPRO_SEARCH'); ?>                       
                    </button>
                </div>
            </div> 

            <div class="right-button">       
                <?php $linkfacility = JURI::base().'index.php?option=com_bookpro&view=facility&hotel_id='.$this->lists['hotel_id'].'&Itemid='.JRequest::getVar('Itemid');?>
                <a href="<?php echo $linkfacility?>">
                    <button class="btn btn-medium btn-success"> <span class="icon-new icon-white"></span><?php echo JText::_('COM_BOOKPRO_NEW')?></button>
                </a>        
            </div>
        </fieldset>
    </div>
</div>

<form action="index.php" method="post" name="facilities" id="facilities">        
    <table class="table-striped table">
        <thead>
            <tr>
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
                    <?php echo JHtml::_('grid.sort', 'COM_BOOKPRO_FACILITY_TYPE', 'l.title', $this->escape($this->state->get('list.direction')), $this->escape($this->state->get('list.ordering'))); ?>
                </th>
                <th>
                    <?php echo JHtml::_('grid.sort', 'COM_BOOKPRO_HOTEL', 'l.hotel_name', $this->escape($this->state->get('list.direction')), $this->escape($this->state->get('list.ordering'))); ?>
                </th>

                <th>
                    <?php echo JHtml::_('grid.sort', 'COM_BOOKPRO_FACILITY_PRICE', 'l.title', $this->escape($this->state->get('list.direction')), $this->escape($this->state->get('list.ordering'))); ?>
                </th>

                <th width="5%">
                    <?php echo JText::_('COM_BOOKPRO_EDIT_RATE'); ?>
                </th> 
                <th width="5%">
                    <?php echo JText::_('COM_BOOKPRO_DELETE'); ?>
                </th>  

                <th width="1%" class="nowrap">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'l.id', $this->escape($this->state->get('list.direction')), $this->escape($this->state->get('list.ordering'))); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="10">
                    <?php echo $this->pagination->getPagesLinks(); ?>
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
                            href="<?php echo JRoute::_('index.php?option=com_bookpro&view=facility&id='.$item->id);?>">
                            <?php echo $this->escape($item->title); ?>
                        </a>
                    </td>
                    <td><?php echo $this->escape($item->ftype?'ROOM':'HOTEL'); ?></td>
                    <td><?php echo $this->escape($item->hotel_name); ?></td>

                    <td><?php echo $this->escape($item->price); ?></td>
                    <td style="text-align: center;">
                        <?php $linkrd = ARoute::view('facility', null, null, array('id'=>$item->id, 'Itemid'=>JRequest::getVar(Itemid)));?>
                        <a href="<?php echo $linkrd;?>" title="<?php echo JText::_('COM_BOOKPRO_EDIT_RATE');?>"><span class="icon-edit">&nbsp;</span></a>
                    </td>
                    <td style="text-align: center;">                                
                        <a href="javascript:void(0)" onclick="Delete(<?php echo $item->id; ?>);" title="<?php echo JText::_('COM_BOOKPRO_DELETE');?>"><span class="icon-remove-sign">&nbsp;</span></a>
                    </td>  
                    <td class="center">
                        <?php echo (int) $item->id; ?>
                    </td>
                </tr>
                <?php } ?>
        </tbody>
    </table>

    <div class="clr"></div>
    </div>
    <input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
    <input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>" id="task"/>
    <input type="hidden" name="cid[]"	value="" id="cid"/>   
    <input type="hidden" name="controller" value="facility"/>
    <input type="hidden" name="boxchecked" value="0">
    <input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
    <input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
    <input type="hidden" name="hotel_id" value="<?php echo $this->lists['hotel_id'];?>"/>
    <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar(Itemid);?>" id="Itemid"/>
    <?php echo JHTML::_('form.token'); ?>
</form>	
</fieldset>    
 </div>          
