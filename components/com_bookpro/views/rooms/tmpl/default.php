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
        jQuery("form[name='rooms']").submit();
    }

    function submitSearch()
    {
         var linkrooms = "<?php echo JURI::base().'index.php?option=com_bookpro&view=rooms&Itemid='.JRequest::getVar('Itemid');?>";
             linkrooms = linkrooms + '&hotel_id=' + jQuery("select[name='search_hotel_id']").val();
             window.location.href = linkrooms;
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
              <?php echo JText::_('COM_BOOKPRO_ROOM_MANAGER');    
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
                                <?php $linkroom = JURI::base().'index.php?option=com_bookpro&view=room&hotel_id='.$this->lists['hotel_id'].'&Itemid='.JRequest::getVar('Itemid');?>
                                <a href="<?php echo $linkroom?>">
                                    <button class="btn btn-medium btn-success"> <span class="icon-new icon-white"></span><?php echo JText::_('COM_BOOKPRO_NEW')?></button>
                                </a>        
                </div>
               </fieldset>
               </div>
               </div>
   
<form action="index.php" method="post" name="rooms" id="rooms">        
		<table class="adminlist table-striped table" >
			<thead>
				<tr>                                                   
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
					<tr><td colspan="7" class="emptyListInfo"><?php echo JText::_('COM_BOOKPRO_NO_ITEMS_FOUND'); ?></td></tr>
				<?php 
				
					} else {
												
						 for ($i = 0; $i < $itemsCount; $i++) { 
				    	 	$subject = &$this->items[$i]; 
				    		$link = ARoute::view('room',null,null,array('cid[]'=>$subject->id, 'hotel_id'=>$this->lists['hotel_id'], 'Itemid'=>JRequest::getVar(Itemid)));
				?>
				    	<tr>
				    		<td>
					    		<a href="<?php echo $link; ?>" title="<?php echo $titleEdit; ?>"><?php echo $subject->title; ?></a>
				    		</td>
                            <td><?php echo $this->getNameHotelById($subject->hotel_id); ?></td>
				    		<td><?php echo $subject->adult ?></td>
                            <td><?php echo $subject->child ?></td>
                            <td><?php echo $subject->quantity ?></td>
                            <td><?php echo $this->getNameRoomlabelById($subject->roomlabel_id); ?></td>
                            <td style="text-align: center;">                
                                <a href="javascript:void(0)" onclick="Delete('<?php echo $subject->id; ?>');" title="<?php echo JText::_('COM_BOOKPRO_DELETE'); ?>"><span class="icon-remove-sign">&nbsp;</span></a>
                            </td>  
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
	<input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>" id="task"/>
	<input type="hidden" name="cid[]"	value="" id="cid"/>   
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_ROOM; ?>"/>
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
	<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
    <input type="hidden" name="hotel_id" value="<?php echo $this->lists['hotel_id'];?>"/>
    <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar(Itemid);?>" id="Itemid"/>
	<?php echo JHTML::_('form.token'); ?>
</form>	
</fieldset>    
 </div>          
