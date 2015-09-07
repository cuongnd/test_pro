	 <h2 class="title-categories">
			<span><?php echo $this->hotel->title ?> </span>
		</h2>
		<div class="yourstay">
			<div class="hotel_image">
				<?php
				$ipath = BookProHelper::getIPath($this->hotel->image);
				$thumb = AImage::thumb($ipath, 160, 100);
				?>
				<img src="<?php echo $thumb ?>"
					alt="<?php echo $this->hotel->title ?>">
				<table id="hoteldetails">
					<tbody>
						<tr>
							<th><?php echo JText::_('COM_BOOKPRO_HOTEL_LOCATION') ?></th>
							<td><?php 
							$city=BookProHelper::getObjectAddress($this->hotel->city_id);
							echo $this->hotel->address1.', '. $city->title ?>
							</td>
						</tr>
						<tr>
							<th><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKIN') ?></th>
							<td><?php echo DateHelper::formatDate($this->cart->checkin_date);
							?>
							</td>
						</tr>
						<tr>
							<th><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKOUT') ?></th>
							<td><?php 	echo DateHelper::formatDate($this->cart->checkout_date); ?>
							</td>
						</tr>
						
					</tbody>
				</table>

			</div>
			<div id="pricedetails">

				<ul>
					<li class="charge">
						<div class="key">
							<?php echo JText::sprintf('COM_BOOKPRO_HOTEL_ROOM_QTY',$this->cart->no_room) ?>
						</div>
						<div class="value">
							<?php echo CurrencyHelper::formatprice($this->cart->sum) ?>
						</div>
					</li>
					<?php if ($this->cart->tax ) {?>
					<li class="charge">
						<div class="key">
							<?php echo JText::_('COM_BOOKPRO_VAT')?>
							
						</div>
						<div class="value">
							<?php echo CurrencyHelper::formatprice($this->cart->tax) ?>
						</div>
					</li>
					<?php } ?>
					<?php if ($this->cart->service_fee) {?>
					<li class="charge">
						<div class="key">
							<?php echo JText::_('COM_BOOKPRO_SERVICE_FEE')?>
							
						</div>
						<div class="value">
							<?php echo CurrencyHelper::formatprice($this->cart->service_fee) ?>
						</div>
					</li>
					<?php } ?>

					<li class="total">

						<div class="key">
							<?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL')?>
						</div>
						<div class="value alignright largetotaltext">
							<span class="nowrap hotel_currency"> <?php echo CurrencyHelper::formatprice($this->cart->total) ?>
							</span>

						</div>
					</li>

				</ul>


			</div>
			<div class="clear"></div>
		</div>