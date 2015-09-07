<?php

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
AImporter::helper('hotel');
$colspan =  12;

$editCustomer = JText::_('Edit Coupon');
$titleEditAcount = JText::_('Edit Coupon');


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
        jQuery("form[name='coupons']").submit();
    }

    function submitSearch()
    {
         var linkrooms = "<?php echo JURI::base().'index.php?option=com_bookpro&view=coupons&Itemid='.JRequest::getVar('Itemid');?>";
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
            <?php echo JText::_('COM_BOOKPRO_COUPON_MANAGER'); ?>            
         </legend>  
         <?php
             if($this->hotels){
         ?>
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
         <?php
             }
         ?>
                <div class="right-button">      
                                <?php $linkroom = JURI::base().'index.php?option=com_bookpro&view=coupon&Itemid='.JRequest::getVar('Itemid');?>
                                <a href="<?php echo $linkroom?>">
                                    <button class="btn btn-medium btn-success"> <span class="icon-new icon-white"></span><?php echo JText::_('COM_BOOKPRO_NEW')?></button>
                                </a>        
                </div>
<form action="index.php" method="post" name="coupons" id="coupons">
       <div id="editcell">
		<table class="adminlist table-striped table" cellspacing="1">
			<thead>
				<tr>
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_COUPON_TITLE'), 'title', $orderDir, $order); ?>
					</th>
					<th width="10%">
				        <?php echo JText::_('COM_BOOKPRO_TOUR_HOTEL'); ?>
					</th>
					<th width="10%">
				        <?php echo JText::_('COM_BOOKPRO_COUPON_CODE'); ?>
					</th>
					<th width="10%">
				        <?php echo JText::_('COM_BOOKPRO_COUPON_AMOUNT'); ?>
					</th>
				
					<th width="5%">
				        <?php echo JText::_('COM_BOOKPRO_COUPON_TOTAL'); ?>
					</th>
					<th width="5%">
				        <?php echo JText::_('COM_BOOKPRO_COUPON_REMAIN'); ?>
					</th>
					<th width="15%">
				        <?php echo JText::_('COM_BOOKPRO_PUBLISH_DATE'); ?>
					</th>
						<th width="15%">
				        <?php echo JText::_('COM_BOOKPRO_UNPUBLISH_DATE'); ?>
					</th>
                    <th width="5%">
                        <?php echo JText::_('COM_BOOKPRO_DELETE'); ?>
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
				<?php if (! is_array($this->items) || ! $itemsCount) { ?>
					<tr><td colspan="<?php echo $colspan; ?>"><?php echo JText::_('COM_BOOKPRO_NO_ITEM'); ?></td></tr>
				<?php } else { ?>
				    <?php for ($i = 0; $i < $itemsCount; $i++) { ?>
				    
				    	<?php $subject = &$this->items[$i];
                           $link = ARoute::view('coupon',null,null,array('cid[]'=>$subject->id, 'Itemid'=>JRequest::getVar(Itemid)));
				    	?>
				    	     
				    	<tr>
				    		<td><a href="<?php echo $link; ?>"><?php echo $subject->title; ?></a></td>
				    		<td><?php echo $subject->hotel_title; ?> </td>
				    		<td><?php echo $subject->code; ?> </td>
				    		<td><?php echo $subject->amount; ?> </td>
				    		<td><?php echo $subject->total; ?> </td>
				    		<td><?php echo $subject->remain; ?> </td>
				    		<td><?php echo $subject->publish_date; ?> </td>
				    		<td><?php echo $subject->unpublish_date; ?> </td>
                            <td style="text-align: center;">                
                                <a href="javascript:void(0)" onclick="Delete('<?php echo $subject->id; ?>');" title="<?php echo JText::_('COM_BOOKPRO_DELETE'); ?>"><span class="icon-remove-sign">&nbsp;</span></a>
                            </td>                             
				    	</tr>
				    <?php } ?>
				<?php } ?>
			</tbody>
		</table>
	</div>	
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_COUPON; ?>"/>
    <input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>" id="task"/>    	
	<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
    <input type="hidden" name="cid[]"    value="" id="cid"/>  
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
    <input type="hidden" name="hotel_id" value="<?php echo $this->lists['hotel_id']; ?> "/>
    <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar(Itemid);?>" id="Itemid"/>    
	<?php echo JHTML::_('form.token'); ?>
</form>	
            </fieldset>    
        </div>          
</div> 