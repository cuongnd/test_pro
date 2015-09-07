	<?php
	 
	AImporter::helper('image','currency','tour');
	AImporter::css('country');
	AImporter::model('country');
	$app = JFactory::getApplication();
	$input = $app->input;
	$country_id = $input->get('country_id',0,'int');
	
	$country = TourHelper::getCountryObject($country_id);
	
	?>

	<div class="row-fluid">
						<div class="span8 col_left">
							<img style="height:265px;" class="image-full" src="images/package_tours.jpg">
							<div class="package_tour">
								<div class="row-fluid">
									<div class="span4 text_title text-left">
										<p class="title">Guarantee</p>
										<p>We're so sure the best prices for our hotels are found on our websites that we were the first to offer the most powerful price guarantee ever by Asianventure Tours</p>
									</div>
									<div class="span4 text_title text-left">
										<p class="title">Club</p>
										<p>We're so sure the best prices for our hotels are found on our websites that we were the first to offer the most powerful price guarantee ever by Asianventure Tours</p>
									</div>
									<div class="span4 text_title text-left">
										<p class="title">Reviews</p>
										<p>You'll find millions of tour reviews and opinions from our web so make sure you read these reviews and choose the perfect trip for your dream vacation</p>
									</div>
								</div>
							</div>
							<div class="top_destinations">
								<div style="padding-bottom:10px;">
									<span class="title_destinations text-left">Top Tour Destinations</span>
									<div style="border-bottom: 4px solid #cdcdcd;position:relative; top:-13px;"></div>
								</div>
								<div class="row-fluid">
									<div class="span6">
										<?php echo $this->loadTemplate('destinations') ?>
									</div>
									<div class="span6">
										<img src="images/map-1.jpg">
									</div>
								</div>
							</div>
							<div class="banner_center" style="padding-top:10px;">
								<img class="image-full" src="images/banner-center.jpg">
							</div>
							<div class="fearture_tours">
								<div class="title_fearture_tours">
									<span class="text-left div_title_fearture_tours">FEATURED TOURS</span>
									<span class="div1_title_fearture_tours text-right">FIND GREAT DEALS IN <?php echo $country->country_name; ?> </span>
								</div>
								<div class="container_fearture_tours">
									
									<div class="row-fluid">
										<div class="span4 content_best_deals">
										<p class="best_deals text-left">Best deals</p>

										
											<?php 
											$layout = new JLayoutFile('bestdeals', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
											$html = $layout->render($this->bestdeals);
											echo $html;
											?>
											

										</div>
										<div class="span8 content_table_span8">
											<?php 
											$layout = new JLayoutFile('tours', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
											$html = $layout->render($this->tours);
											echo $html;
											?>

										</div>
									</div>
								</div>
								
							</div>
						</div>
						<div class="span4 col_right">
							
							<div class="row-fluid right-content">
								<div class="span12">
									 <?php
						            $layout = new JLayoutFile('top_discount_tour', $basePath = JPATH_ROOT . '/components/com_bookpro/layouts');
						            $html = $layout->render($this->tour);
						            echo $html;
						            ?>
								</div>
								
							</div>	
							
							
							<div class="row-fluid right-content">
				            	<div class="span12">
				            		<div class="sign_up_col_right">
							                <div class="search-title">Join now for deal & discount alert, save up to 75%</div>
							                <form class="form-search">
							                	<table cellpadding="0" cellspacing="0">
							                		<tr>
							                			<td>
							                				<i class="icon_search"></i>		
							                			</td>
							                			<td valign="bottom">
							                				<div class="input-append">
											                    <input type="text"
											                           class="input-medium button_col_right search-query">
											                    <button type="submit" class="btn btn-small">Sign up</button>
											                    </div>
							                			</td>
							                		</tr>
							                	</table>
							                	
							                	
							                </form>
						            </div>		
				            	</div>
				            </div>
				            <div class="row-fluid right-content">
				            	<div class="span12">
				            		<img class="image-full" src="images/lucky.jpg">
				            	</div>
				            </div>
							<div class="row-fluid right-content">
								<div class="span12">
									<img class="image-full" src="images/event.jpg">
								</div>
							</div>
							<div class="row-fluid right-content">
								<div class="span12">
									<img class="image-full" src="images/top_travel.jpg">
								</div>
							</div>
							
							<div class="row-fluid right-content">
								<div class="span12">
									<div class="content_find_tours">
										<div>
											<div class="title_find_tours">FIND TOURS BY LOCATIONS
											</div>
											<div class="drop_title_find_tour"> </div>
										</div>
									<div class="row-fluid">
										<div class="span6 text-left">
											<ul>
												<li>
													<a href="#">01. Hanoi Tours</a>
												</li>
												<li>
													<a href="#">02. Hue Tours</a>
												</li>
												<li>
													<a href="#">03. Halong Tours</a>
												</li>
												<li>
													<a href="#">04. Hoian Tours</a>
												</li>
												<li>
													<a href="#">05. Danang Tours</a>
												</li>
											</ul>
										
										</div>
										<div class="span6 text-left">
											<ul>
													<li>
														<a href="#">01. Hanoi Tours</a>
													</li>
													<li>
														<a href="#">02. Hue Tours</a>
													</li>
													<li>
														<a href="#">03. Halong Tours</a>
													</li>
													<li>
														<a href="#">04. Hoian Tours</a>
													</li>
													<li>
														<a href="#">05. Danang Tours</a>
													</li>
												</ul>
										</div>
									</div>
								</div>
								</div>
							</div>	
							
						
						</div>
					</div>