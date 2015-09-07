<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );
AImporter::model('tourpackage','tour','orderinfos','passengers');
AImporter::helper('tour');
AImporter::css('tour');

$infomodel=new BookProModelOrderinfos();
$infomodel->init(array('order_id'=>$this->order->id));
$this->orderinfo=$infomodel->getData();
for ($i = 0; $i < count($this->orderinfo); $i++) 
{
	if($this->orderinfo[$i]->type="TOUR"){
		$package=$this->orderinfo[$i];
		unset($this->orderinfo[$i]);
		break;
	}
		
}
$rooms=TourHelper::getRoomType($this->order->id);

$tourpackageModel=new BookProModelTourPackage();
$tourpackageModel->setId($package->obj_id);
$this->package=$tourpackageModel->getObject();

$tourModel=new BookProModelTour();
$tourModel->setId($this->package->tour_id);
$this->tour=$tourModel->getObject();

$passengersModel= new BookProModelPassengers();
$passengersModel->init(array('order_id'=>$this->order->id));
$passengers=$passengersModel->getData();

?>

<table class="table">
			<tbody>
				<tr>
					<th><?php echo JText::_('COM_BOOKPRO_TOUR_TITLE')?>
					</th>
					<td><a
						href="<?php echo JRoute::_('index.php?option=com_bookpro&controller=tour&view=tour&id='.$this->tour->id)?>"><?php echo $this->tour->title?>
					</a>
					</td>
				</tr>
				<tr>
					<th><?php echo JText::_('COM_BOOKPRO_TOUR_CODE')?>
					</th>
					<td>
					<?php echo $this->tour->code?>
					</td>
				</tr>
				<tr>
					<th><?php echo JText::_('COM_BOOKPRO_TOUR_PACKAGE')?>
					</th>
					<td><?php echo $this->package->title?>
					</td>
				</tr>
				
				<tr>
					<th><?php echo JText::_('COM_BOOKPRO_TOUR_DEPART_DATE')?>
					</th>
					<td>
					<?php echo JHtml::_('date',$info->start) ?>
					</td>
				</tr>
				<tr>
					<th><?php echo JText::_('COM_BOOKPRO_ORDER_NOTE')?>
					</th>
					<td>
							<?php echo $this->order->notes?>
					</td>
				</tr>
				
			</tbody>
		</table>

<div class="row-fluid">
	
	<?php 
		$layout = new JLayoutFile('passengers', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
		$html = $layout->render($passengers);
		echo $html;
		$layout = new JLayoutFile('rooms', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
		$html = $layout->render($rooms);
		echo $html;
		
	?>
	</div>
