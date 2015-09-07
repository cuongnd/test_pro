<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 82 2012-08-16 15:07:10Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');


JHTML::_('behavior.tooltip');

$bar = JToolBar::getInstance('toolbar');

JToolBarHelper::title(JText::_('COM_BOOKPRO_HOTEL_MANAGER'), 'object');

JToolBarHelper::addNew();
JToolBarHelper::editList();
JToolBarHelper::divider();
JToolBarHelper::publish();
JToolBarHelper::unpublishList();
JToolBarHelper::deleteList('', 'trash', 'Trash');
JToolBarHelper::back();
BookProHelper::setSubmenu(3);
$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = &$this->pagination;
JFactory::getDocument();
AImporter::css('hotel');

?>
 
<div class="span10">
<form action="index.php" method="post" name="adminForm" id="adminForm">
    
      <fieldset id="filter-bar">
        <div class="filter-search fltlft">
            <div class="btn-group pull-left hidden-phone fltlft inline">
                    <div class="btn-group pull-left hidden-phone fltlft">
                        <input type="text" name="title"  value="<?php echo $this->lists['title']?>" placeholder="<?php echo JText::_('COM_BOOKPRO_HOTEL_TITLE')?>">
                        <?php echo HotelHelper::getSupplierSelect($this->lists['userid']); ?>
                    </div>  
                    <div class="btn-group pull-left hidden-phone fltlft">
                        <button onclick="this.form.submit();" class="btn">
                            <?php echo JText::_('COM_BOOKPRO_SEARCH'); ?>
                        </button>
                       
                    </div>
            </div>
        </div>
        <div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
			</div>
    </fieldset>
    
	<div id="editcell"> 
		<table class="adminlist table-striped table" >
			<thead>
				<tr>
					<th width="1%">#</th>
					<?php if (! $this->selectable) { ?>
						<th width="1%">
														
							<input type="checkbox" class="inputCheckbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
							
						</th>
					<?php } ?>
					
					<th width="1%" class="nowrap center">
							<?php echo JHtml::_('grid.sort', 'JSTATUS', 'state', $listDirn, $listOrder); ?>
					</th>
					
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_HOTEL_TITLE'), 'title', $orderDir, $order); ?>
					</th>
					
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_SUPPLIER'), 'fullname', $orderDir, $order); ?>
					</th>
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_CITY'), 'city_title', $orderDir, $order); ?>
					</th>
                                        
                    <th class="center" width="20%">
                        <?php echo JText::_('COM_BOOKPRO_ROOM_RATE_MANAGER'); ?>
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
				    	 	$rankstar=JURI::root()."components/com_bookpro/assets/images/". $subject->rank.'star.png';
				    		$link = JRoute::_(ARoute::edit(CONTROLLER_HOTEL, $subject->id));
				    		$js = 'javascript:ListSubjects.select(' . $subject->id . ',\'' . $title . '\',\'' . $this->escape($subject->alias) . '\')';
				?>
				    	<tr>
				    		<td><?php echo number_format($this->pagination->getRowOffset($i), 0, '', ' '); ?></td>
				    			<?php if (! $this->selectable) { ?>
				    				<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
				    			<?php } ?>
				    		<td class="center">
								<?php echo JHtml::_('jgrid.published', $subject->state, $i, 'hotels.', true, 'cb', $subject->publish_up, $subject->publish_down); ?>
							</td>
				    		<td>
					    		<a href="<?php echo $link; ?>" title="<?php echo $titleEdit; ?>"><?php echo $subject->title; ?></a>
				    			<div style="text-align: left"><img src="<?php echo $rankstar; ?>"></div>
				    		</td>
				    		<td class="left"">
								<?php echo $subject->fullname ?>
							</td>
				    		
				    		<td class="left">
								<?php echo $subject->city_title ?>
							</td>
				    		                   
                            <td class="center">
                            <?php $linkr = ARoute::view('rooms',null,null,array('hotel_id'=>$subject->id));?>
                                <a href="<?php echo $linkr;?>" title="New"><span title="Room Manager"><img src="<?php echo JURI::root().'components/com_bookpro/assets/images/room/room_manage.png'?>"
							        alt="Room" class="room_manager"></span></a>
                                
                                <?php $linkr = ARoute::view('roomrate',null,null,array('hotel_id'=>$subject->id));?>
                                <a href="<?php echo $linkr;?>" title="New"><span title="Rate Manager"><img src="<?php echo JURI::root().'components/com_bookpro/assets/images/room/new_rate.png'?>"
							        alt="Room" class="rate_manager"></span></a>
                                
                                 <?php $linkrd = ARoute::view('roomratedetail',null,null,array('hotel_id'=>$subject->id));?>
                                <a href="<?php echo $linkrd;?>" title="Edit"><span title="Edit Room"><img src="<?php echo JURI::root().'components/com_bookpro/assets/images/room/edit_room.png'?>"
							        alt="Room" class="edit_room"></span></a>
                                
                            </td>                                     
				    	</tr>
				    <?php 
				    	}
					} 
					?>
			</tbody>
		</table>
		
	</div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>"/>
	<input type="hidden" name="reset" value="0"/>
	<input type="hidden" name="cid[]"	value="" /> 
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_HOTEL; ?>"/>
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
	<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
	<?php echo JHTML::_('form.token'); ?>
</form>	
</div>
