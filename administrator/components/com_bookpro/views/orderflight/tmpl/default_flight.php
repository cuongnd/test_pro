<?php/** * @package 	Bookpro * @author 		Nguyen Dinh Cuong * @link 		http://ibookingonline.com * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html * @version 	$Id$ **/defined('_JEXEC') or die('Restricted access');AImporter::model('passengers','order');AImporter::helper('date','currency','flight','paystatus');$config=JComponentHelper::getParams('com_bookpro');$company_name=$config->get('company_name');$logo=$config->get('company_logo');$address=$config->get('company_address');JToolBarHelper::cancel();	?><div><div style="width:768px;border: 1px solid #ccc;margin: 0 auto;"><?php if(JRequest::getCmd('task') !='exportpdf'){?>	<a style="float:left; margin:5px 0 0 10px;"		href="<?php echo JUri::root().'index.php?option=com_bookpro&controller=order&task=exportpdf&layout=ticket&order_number='.$this->order->order_number ?>"		target="_blank" class="btn btn-success"> <?php echo JText::_('COM_BOOKPRO_EXPORT_PDF')?>	</a>	<?php }?><form class="invoice" id="tourBookForm" name="tourBookForm"	action="index.php" method="post">	<table width="100%" cellpadding="10">		<tbody>			<tr>				<td style="border: none;">					<table style="text-align:left;" cellpadding="5">						<tr>							<td style="border: none;"><img alt="" src="<?php echo JUri::root().$logo; ?>"								width="220px;"></td>						</tr>						<tr>							<td style="border: none;"><?php echo $company_name; ?></td>						</tr>						<tr>							<td style="border: none;"><?php echo $address; ?></td>						</tr>						<tr>							<td style="border: none;">&nbsp;</td>						</tr>						<tr>							<td style="border: none; font-weight: bold; color: #08c;"><?php echo JText::_("COM_BOOKPRO_BOOKING_BILL_TO")?>							</td>						</tr>						<tr>							<td style="border: none;"><span style="font-weight: bold;"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_NAME'); ?>:&nbsp;</span>								<?php echo $this->customer->lastname. ' '.$this->customer->firstname; ?>							</td>						</tr>						<tr>							<td style="border: none;"><span style="font-weight: bold;"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>								<?php echo $this->customer->email	?></td>						</tr>						<tr>							<td style="border: none;"><span style="font-weight: bold;"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE'); ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>								<?php echo $this->customer->mobile;?></td>						</tr>					</table>				</td>				<td style="border: none; float: right;">					<table cellpadding="10">						<tr>							<td style="border: none;"><h2									style="text-transform: uppercase; float: right; margin-top: 0">									<?php echo JText::_('COM_BOOKPRO_INVOICE')?>								</h2></td>						</tr>					</table>											<table cellpadding="10">							<tr>								<td style="font-weight: bold; border: 1px #000 solid;"><?php echo JText::_('COM_BOOKPRO_INVOICE_NUMBER'); ?>:</td>								<td style="border: 1px #000 solid;"><?php echo $this->order->order_number; ?>								</td>							</tr>							<tr>								<td style="font-weight: bold; border: 1px #000 solid;"><?php echo JText::_('COM_BOOKPRO_INVOICE_DATE')?>:</td>								<td style="border: 1px #000 solid;"><?php echo JHtml::_('date',$this->order->created,'d-m-Y'); ?>								</td>							</tr>							<tr>								<td style="font-weight: bold; border: 1px #000 solid;"><?php echo JText::_('COM_BOOKPRO_ORDER_PAYMENT_STATUS')?>:</td>								<td style="border: 1px #000 solid;"><span									class="btn btn-success"> <?php echo PayStatus::format($this->order->pay_status) ?>								</span></td>							</tr>						</table>								</td>			</tr>		</tbody>	</table>	<div>					<?php 			$model=new BookproModelpassengers();						$state=$model->getState();				    	$state->set('filter.order_id', $this->order->id);			$passengers=$model->getItems();						//get flight information						$flight_info[]=FlightHelper::getFlightDetail($passengers[0]->route_id);						if($passengers[0]->return_route_id){								$flight_info[]=FlightHelper::getFlightDetail($passengers[0]->return_route_id);						}?>			<h4 style="text-align: left;"><?php echo JText::_('COM_BOOKPRO_FLIGHT_DETAIL')?></h4>			<?php 			$data->flights=	$flight_info;			$layout = new JLayoutFile('email_flight', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts/flight');			echo $layout->render($data);			?>			<h4 style="margin-bottom: 20px;"><?php echo JText::_('COM_BOOKPRO_PASSENGER')?></h4>			<?php 			$data->passengers=$passengers;			$layout = new JLayoutFile('email_passenger', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts/flight');			echo $layout->render($data);						?>			</div>	<table style="width: 100%;">		<tr>			<td><?php echo JText::_('COM_BOOKPRO_INVOICE_NOTES')?>			</td>		</tr>	</table>		<input type="hidden" name="option" value="com_bookpro" /> <input		type="hidden" name="controller" value="order" /> 		 <input type="hidden"	name="order_id" value="<?php echo $this->order->id;?>" /> <input		type="hidden" name="<?php echo $this->token?>" value="1" /></form></div></div></div>