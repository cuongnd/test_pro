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
AImporter::model('hotels');
$model = new BookProModelHotels();
$lists = array('limit'=>5,'limitstart'=>0,'filter_order'=>'id','filter_order_Dir'=>'DESC');
$model->init($lists);
$items = $model->getData();

?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
    
         
	<div id="editcell"> 
		<table class="adminlist table-striped table" >
			<thead>
				<tr>
					
					<th width="1%" style="min-width: 55px" class="nowrap center">
							<?php echo JHtml::_('grid.sort', 'JSTATUS', 'state', $listDirn, $listOrder); ?>
					</th>
					
					<th class="title" width="10%">
				        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_HOTEL_TITLE'), 'title', $orderDir, $order); ?>
					</th>

					<th width="5%">
				        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_HOTEL_RANK'), 'rank', $orderDir, $order); ?>
					</th>
                                       
					<th width="5%">
				        <?php echo JHTML::_('grid.sort', 'ID', 'id', $orderDir, $order); ?>
					</th>
				</tr>
			</thead>
			
			<tbody>
				<?php 
												
						 for ($i = 0; $i < count($items); $i++) { 
				    	 	$subject = $items[$i]; 
				    		$link = JRoute::_(ARoute::edit(CONTROLLER_HOTEL, $subject->id));
						?>
				    	<tr>
				    		
				    		<td class="center">
								<?php echo JHtml::_('jgrid.published', $subject->state, $i, 'hotels.', true, 'cb', $subject->publish_up, $subject->publish_down); ?>
							</td>
				    		<td>
					    		<a href="<?php echo $link; ?>" title="<?php echo $titleEdit; ?>"><?php echo $subject->title; ?></a>
				    		</td>
				    		                   
							<td class="center">
								<?php echo $subject->rank ?>
							</td>
                                                     
				    		<td><?php echo number_format($subject->id, 0, '', ' '); ?></td>
				    	</tr>
				    <?php 
				    	}
					?>
			</tbody>
		</table>
		
	</div>
	
</form>	
