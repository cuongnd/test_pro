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

JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');

BookProHelper::setSubmenu(1);

JToolBarHelper::title(JText::_('COM_BOOKPRO_ROOM_MANAGER'), 'object');

JToolBarHelper::addNew();
JToolBarHelper::editList();

JToolBarHelper::divider();
JToolBarHelper::publish();
JToolBarHelper::unpublishList();
JToolBarHelper::deleteList('', 'trash', 'Trash');
JToolBarHelper::back();

$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = &$this->pagination;

?>
<div class="span10" >
<form action="index.php" method="post" name="adminForm" id="adminForm">
    <?php
        if($this->hotel){
    ?>
      <h3><?php echo JText::_('COM_BOOKPRO_HOTEL').$this->hotel->title; ?></h3>  
    <?php
        }
      ?> 
  
      <fieldset id="filter-bar">
        <div class="filter-search fltlft">
            <div class="btn-group pull-left hidden-phone fltlft inline">
                    <div class="btn-group pull-left hidden-phone fltlft">
                        <input type="text" name="title"  value="<?php echo $this->lists['title']?>" placeholder="<?php echo JText::_('COM_BOOKPRO_ROOM_TYPE')?>">
                    </div>
                    <div class="btn-group pull-left hidden-phone fltlft">
                        <button onclick="this.form.submit();" class="btn">
                            <?php echo JText::_('COM_BOOKPRO_SEARCH'); ?>
                        </button>
                        <button onclick="this.form.reset.value=1;this.form.submit();" class="btn">
                            <?php echo JText::_('COM_BOOKPRO_RESET'); ?>
                        </button>
                    </div>
            </div>
        </div>
         <div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
			</div>
    </fieldset>
        
  <table class="filterTable">
	</table>
	<div id="editcell">
		<table class="table" >
			<thead>
				<tr>
					<th width="3%">#</th>
					<?php if (! $this->selectable) { ?>
						<th width="1%">
														
							<input type="checkbox" class="inputCheckbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
							
						</th>
					<?php } ?>
					<th style="text-align: center;" width="5%"><?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_TOUR_STATE'), 'state', $orderDir, $order); ?></th>
					
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_ROOM_TYPE'), 'title', $orderDir, $order); ?>
					</th>                              
                    <th class="title" width="10%">
                       <?php echo JText::_("COM_BOOKPRO_HOTEL_TITLE");?>
                    </th>                              
					<th class="title" width="10%">
				       <?php echo JText::_("COM_BOOKPRO_ADULT");?>
					</th>
                    <th class="title" width="10%">
                       <?php echo JText::_("COM_BOOKPRO_CHILD");?>
                    </th>

                    <th class="title" width="10%">
                       <?php echo JText::_("COM_BOOKPRO_ROOM_TOTAL");?>
                    </th>                    
                    <th class="title" width="10%">
                       <?php echo JText::_("COM_BOOKPRO_ROOM_LABEL");?>
                    </th>                                                                      
					<th width="5%">
				        <?php echo JHTML::_('grid.sort', 'ID', 'id', $orderDir, $order); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
    			<tr>
    				<td colspan="9">
    				    <?php echo $pagination->getListFooter(); ?>
    				</td>
    			</tr>
			</tfoot>
			<tbody>
				<?php if (! is_array($this->items) || ! $itemsCount && $this->tableTotal) { ?>
					<tr><td colspan="5" class="emptyListInfo"><?php echo JText::_('COM_BOOKPRO_NO_ITEMS_FOUND'); ?></td></tr>
				<?php 
				
					} else {
												
						 for ($i = 0; $i < $itemsCount; $i++) { 
				    	 	$subject = &$this->items[$i]; 
				    		$link = JRoute::_(ARoute::edit(CONTROLLER_ROOM, $subject->id));
				    		$js = 'javascript:ListSubjects.select(' . $subject->id . ',\'' . $title . '\',\'' . $this->escape($subject->alias) . '\')';
				?>
				    	<tr>
				    		<td  style="text-align: right; white-space: nowrap;"><?php echo number_format($this->pagination->getRowOffset($i), 0, '', ' '); ?></td>
				    			<?php if (! $this->selectable) { ?>
				    				<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
				    			<?php } ?>
				    		<td class="center">
								<?php echo JHtml::_('jgrid.published', $subject->state, $i, 'rooms.', true, 'cb', $subject->publish_up, $subject->publish_down); ?>
							</td>
				    		<td>
					    		<a href="<?php echo $link; ?>" title="<?php echo $titleEdit; ?>"><?php echo $subject->title; ?></a>
				    		</td>
                            <td style="text-align: center;"><?php echo $this->getNameHotelById($subject->hotel_id); ?></td>
				    		<td style="text-align: center;"><?php echo $subject->adult ?></td>
                            <td style="text-align: center;"><?php echo $subject->child ?></td>
                            <td style="text-align: center;"><?php echo $subject->quantity ?></td>
                            <td style="text-align: center;"><?php echo $this->getNameRoomlabelById($subject->roomlabel_id); ?></td>
				    		<td style="text-align: right; white-space: nowrap;"><?php echo number_format($subject->id, 0, '', ' '); ?></td>
				    	</tr>
				    <?php 
				    	}
					} 
					?>
			</tbody>
		</table>
		
		<div class="clr"></div>
	</div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>"/>
	<input type="hidden" name="reset" value="0"/>
	<input type="hidden" name="cid[]"	value="" /> 
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_ROOM; ?>"/>
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
	<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
    <input type="hidden" name="hotel_id" value="<?php echo $this->lists['hotel_id'];?>"/>
	<?php echo JHTML::_('form.token'); ?>
</form>	
</div>