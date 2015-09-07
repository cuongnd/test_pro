<?php 
	AImporter::helper('currency','image');
?>
		<table class="table table-condensed">
			<thead>
				<tr>
					<th width="50%"><?php echo JText::_('COM_BOOKPRO_ROOM_TYPE') ?>
					</th>
					<th width="20%" align="right"><?php echo JText::_('COM_BOOKPRO_ROOM_MAX_PERSON') ?>
					</th>
					<th align="right"><?php echo JText::_('COM_BOOKPRO_ROOM_PRICE') ?>
					</th>
					<th align="center" class="text-right"><?php echo JText::_('COM_BOOKPRO_ROOM_SELECT')?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php if (count($this->rooms)>0) {?>
				<?php foreach ($this->rooms as $room){
					$no_room =  0;
					if ($cart->room) {
						$no_room = $cart->room;
					}else{
						$no_room = $room->total;
					}
					
					?>
				<tr>
					<td width="50%">
						
							<?php /* ?>
							<div style="float: left; width: 130px;">
								<?php $thumb = null;

								$ipath = BookProHelper::getIPath($room->image);
								$thumb = AImage::thumb($ipath, $this->config->subjectThumbWidth, $this->config->subjectThumbHeight);
								$slide = AImage::thumb($ipath, $this->config->galleryPreviewWidth, $this->config->galleryPreviewHeight);
								if ($thumb) {
									?>
								<a href="<?php echo $slide; ?>" title="" rel="lightbox-atomium"
									style="position: relative;"> <img src="<?php echo $thumb; ?>"
									alt="" width="120" />
								</a>
								<?php
							} ?>
							</div>
							<?php */ ?>
								<!-- 	
								<span><?php echo $room->room_type?> </span><br />
								 -->
								<?php echo $room->title ?>
							
					</td>
					<td align="right">
					<?php //echo $room->max_person ?>
					<?php 
						if ($room->adult) {
							echo JText::sprintf('COM_BOOKPRO_ADULT',$room->adult);
						}
						if ($room->child) {
							echo JText::sprintf('COM_BOOKPRO_CHILD',$room->child);
						}
					?>
					
					</td>
					<td align="right"><?php echo CurrencyHelper::formatprice($room->price)?>
					</td>
					<td align="center" class="text-right"><input type="hidden" name="room_type[]"
						value="<?php echo $room->id ?>"> 
						<?php 
							if ($room->total){
						?>
						<?php echo JHtmlSelect::integerlist(0,$no_room, 1, 'no_room[]','class="input-small"') ?>
						<?php }else{
							echo JText::_('COM_BOOKPRO_HOTEL_NOT_ROM');
						} ?>
					</td>
                    <td class="text-right" align="right">&nbsp;
                        </td>
                        <td class="text-right" align="right">&nbsp;
                        </td>
				</tr>
                 <tr class="perroom room_id_<?php echo $room->id ?>" style="display: none;"  >
                        <td colspan="3">&nbsp;</td>
                        <td ><span class="roomnumber">room 1</span></td> 
                        <td class="text-right" align="right"><?php echo JHtmlSelect::integerlist(0,$room->adult, 1, 'adult[]') ?>
                        </td>
                        <td class="text-right" align="right"><?php echo $room->child?JHtmlSelect::integerlist(0,$room->child, 1, 'child[]'):'' ?>
                        </td>
                    </tr>
                    
				<?php }
				}else { ?>
				<tr>
					<td colspan="4"><?php echo JText::_('COM_BOOKPRO_ROOM_UNAVAILABLE')?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
			
		</table>
	



