
<?php 
$relateds = $this->tour->relateds;
$now = JHtml::date('now');
$date = new JDate($now);
$from_date = JFactory::getDate($date)->format('d-m-Y',true);

$date->add(new DateInterval('P30D'));
$to_date = JFactory::getDate($date)->format('d-m-Y',true);
AImporter::helper('image','currency','tour','string');
?>
<div class="content_relate text-left">
                    <div class="content_title">
                        <p class="title_relate pull-right">RELATED TRIPS</p>
						<div class="clr"></div>
                        <p
                            style="background: #ebf1f4; margin: 0px; padding: 5px;">MORE
                            OPTIONS FOR YOUR DREAM HOLIDAY</p>
                    </div>
                    <div class="content-related-row">
                    	<?php foreach ($relateds AS $related){
                    		$link = JRoute::_('index.php?option=com_bookpro&controller=tour&view=tour&id=' . $related -> id . '&Itemid=' . JRequest::getVar('Itemid'));
                    		$ipath = BookProHelper::getIPath($related->image);
                    		$thumb = AImage::thumb($ipath, 102, 50);
                    		?>
                    	
                    	
                    	
							<div class="related-row">
								
								<div class="row-fluid ">
									<div class="span4">
										<a href="<?php echo $link; ?>">
											<img class="img-rounded img-polaroid" src="<?php echo $thumb; ?>">
										</a>
										
									</div>
									<div class="span8 tour_right">
										<p style="color: #007294; font-weight: bold; margin: 0px; text-align:right">
										<a href="<?php echo $link; ?>">
										<?php echo $related->title; ?>, <?php echo $related->days; ?> day</a></p>
										<div class="content_price">
											<div class="content_price_left">
												<img src="<?php echo JURI::base() . "/components/com_bookpro/assets/images/star.jpg"; ?>">
											  
												<p style="color: #2c8fab; font-weight: bold; font-size:16px;"><span style="font-size:12px;">Fr.</span>
													<?php echo CurrencyHelper::formatprice(TourHelper::getMinPriceTour($related->id, $from_date, $to_date)) ?>
												</p>
											</div>
											<div class="content_price_right">
												<p style="color: #95a5a5; font-weight: bold; margin: 0px;">115
													review</p>
												<button type="button" class="button_book btn">Book now</button>
											</div>
										</div>
									</div>
								</div>
									
								<p style="font-size:11px;line-height:12px;text-align:justify;margin:0px;">
									<?php echo AString::wordLimit($related->short_desc,25); ?>
								</p>
								
							</div>
                       
                        <?php } ?>
                     </div>   
                    
                </div>