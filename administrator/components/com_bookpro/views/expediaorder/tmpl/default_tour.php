
<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: tour.php 113 2012-09-07 08:13:19Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');
AImporter::model('tourpackage','tour','categories');
$pmodel=new BookProModelTourPackage();
$pmodel->setId($this->orderinfo[0]->obj_id);
$price=$pmodel->getObject();

$tourModel=new BookProModelTour();
$tourModel->setId($price->tour_id);
$tour=$tourModel->getObject();
$link = JRoute::_(ARoute::edit(CONTROLLER_TOUR, $tour->id));
$config=AFactory::getConfig();


$catmodel = new BookProModelCategories();
$lists = array('type'=>9);
$catmodel->init($lists);
$list=$catmodel->getData();
$pickup= AHtml::getFilterSelect('location', JText::_('COM_BOOKPRO_SELECT_PICKUP'), $list, $this->orderinfo[0]->location, false, '', 'id', 'title');

?>

<?php echo $this->loadTemplate('order')?>
	

<div class="col width-50">
<fieldset>
	<legend>
	<?php echo JText::_('Order Detail'); ?>
	</legend>
	
    	<table class="table" width="100%">
		
		<tr>
			<td class="key"><label><?php echo JText::_('COM_BOOKPRO_TOUR'); ?>:</label></td>
			<td><a href="<?php echo $link ?>"><?php echo $tour->title ?></a></td>
		</tr>
		<tr>
			<td class="key"><label><?php echo JText::_('COM_BOOKPRO_PACKAGE'); ?>:</label></td>
			<td><?php echo $price->title ?></td>
		</tr>
	
		<tr>
			<td class="key"><label><?php echo JText::_('COM_BOOKPRO_DEPART_DATE'); ?>:</label>
			</td>
			<td>
			<?php 
			$depart=DateHelper::formatDate($this->orderinfo[0]->start,$config->dateNormal);
			echo JHtml::calendar($depart,'start[]', 'start','%d-%m-%Y')?>
			</td>
		</tr>

		<tr>
			<td class="key"><label><?php echo JText::_('COM_BOOKPRO_ORDER_ADULT'); ?>:</label></td>
			
			<td><?php echo JHtmlSelect::integerlist(1, 40, 1, 'adult[]','',$this->orderinfo[0]->adult)?>
			
			</td>
		</tr>
		<tr>
			<td class="key"><label><?php echo JText::_('COM_BOOKPRO_ORDER_CHILDREN'); ?>:</label>
			</td>
			<td><?php echo JHtmlSelect::integerlist(0, 30, 1, 'child[]','',$this->orderinfo[0]->child)?>
			
			</td>
		</tr>
		<tr>
		<td class="key"><?php echo JText::_('COM_BOOKPRO_TOUR_PICKUP_LOCATION')?>
				</td>
				<td><?php echo $pickup ?>
				</td>
		</tr>
				
	</table>

   </fieldset>
	</div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_ORDER; ?>"/>
	<input type="hidden" name="task" value="batchupdate"/>
	<input type="hidden" name="info_id[]" value="<?php echo $this->orderinfo[0]->id ?>"/>
	<input type="hidden" name="order_id" value="<?php echo $this->order->id; ?>"/>
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->orderinfo[0]->id; ?>"/>
	<?php echo JHTML::_('form.token'); ?>

