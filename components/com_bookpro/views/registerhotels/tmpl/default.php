<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 82 2012-08-16 15:07:10Z quannv $
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.html.html' );
AImporter::helper ( 'date', 'bookpro', 'currency', 'hotel' );
JHTML::_ ( 'behavior.tooltip' );

$orderDir = $this->lists ['order_Dir'];
$order = $this->lists ['order'];
$itemsCount = count ( $this->items );
$pagination = &$this->pagination;
AImporter::css('hotel');
?>

<div class="row-fluid">
	<div class="span12">    
    <?php
				$layout = new JLayoutFile ( 'suppliermenu', $basePath = JPATH_ROOT . '/components/com_bookpro/layouts' );
				$html = $layout->render ( array () );
				echo $html;
				
				
				?>
  <fieldset>
			<legend><?php echo JText::_('COM_BOOKPRO_HOTEL_MANAGER'); ?></legend>
			<div class="right-button">
                               <?php $linkhotel = JURI::base().'index.php?option=com_bookpro&view=registerhotel&Itemid='.JRequest::getVar('Itemid');?>
                                <a href="<?php echo $linkhotel?>">
					<button class="btn btn-medium btn-success">
						<span class="icon-new icon-white"></span><?php echo JText::_('COM_BOOKPRO_NEW'); ?>
                                    </button>
				</a>
			</div>
			<form action="index.php" method="post" name="registerHotels"
				id="registerHotels">
				<table class="table table-striped">
					<thead>
						<tr>
							<th class="title" width="5%">
				        <?php echo JText::_('COM_BOOKPRO_HOTEL_TITLE'); ?>
					</th>

							<th width="20%" class="center">
                        <?php echo JText::_('COM_BOOKPRO_ROOM_RATE_MANAGER'); ?>
                    </th>

							<th width="5%" class="center">
                        <?php echo JText::_('COM_BOOKPRO_DELETE'); ?>
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
				<?php if (! is_array($this->items) || ! $itemsCount) { ?>
					<tr>
							<td colspan="7" class="emptyListInfo"><?php echo JText::_('COM_BOOKPRO_NO_ITEMS_FOUND'); ?></td>
						</tr>
				<?php
				} else {
					
					for($i = 0; $i < $itemsCount; $i ++) {
						
						$subject = &$this->items [$i];
						$rankstar=JURI::base()."/components/com_bookpro/assets/images/". $subject->rank.'star.png';
						$link = ARoute::view ( CONTROLLER_REGISTER_HOTEL, null, null, array (
								'cid[]' => $subject->id,
								'Itemid' => JRequest::getVar ( Itemid ) 
						) );
						?>
				    	<tr>
							<td><a href="<?php echo $link; ?>"
								title="<?php echo $titleEdit; ?>"><?php echo $subject->title; ?></a>
								<div style="text-align: left"><img src="<?php echo $rankstar; ?>"></div>
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

							<td style="text-align: center;"><a href="javascript:void(0)"
								onclick="Delete(<?php echo $subject->id; ?>);"
								title="<?php echo JText::_('COM_BOOKPRO_DELETE');?>"><span
									class="icon-remove-sign">&nbsp;</span></a></td>

						</tr>
				    <?php
					}
				}
				?>
			</tbody>
				</table>
				<input type="hidden" name="option" value="<?php echo OPTION; ?>" />
				<input type="hidden" name="task"
					value="<?php echo JRequest::getCmd('task'); ?>" /> <input
					type="hidden" name="controller"
					value="<?php echo CONTROLLER_REGISTER_HOTEL; ?>" /> <input
					type="hidden" name="filter_order" value="<?php echo $order; ?>" />
				<input type="hidden" name="cid[]" value="" /> <input type="hidden"
					name="filter_order_Dir" value="<?php echo $orderDir; ?>" /> <input
					type="hidden" name="Itemid"
					value="<?php echo JRequest::getVar(Itemid);?>" id="Itemid" /> 
	<?php echo JHTML::_('form.token'); ?>  
</form>
		</fieldset>
	</div>
</div>

<script type="text/javascript">    
    function Delete(id)
    {    
        jQuery("input[name='task']").val('trash'); 
        jQuery("input[name='cid[]']").val(id);    
        jQuery("form[name='registerHotels']").submit();
    }
</script>