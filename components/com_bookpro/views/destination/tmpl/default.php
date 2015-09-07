<?php
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'components/com_bookpro/assets/css/destination.css'); 

$tours = $this->tours;

JHtml::_('behavior.framework');
JHtmlBehavior::modal('a.modal_tour');

?>
<div class="row-fluid">
	<div class="span8 col_left">
		<div class="content_oveview_tour">
			<div style="background: #ecf2f5;">
				<p class="div1_title_col_left text-left">
					Find Great Deals in
					<?php echo $this->dest->title ?>
				</p>
				<p class="div2_title_col_left text-right"><?php echo count($this->tours) ?> tours and excursions,
					discount price</p>
			</div>
			<img src="images/package_tour.jpg" class="image-full">
			<div class="overview_tour destination-info">
				<div class="row-fluid">
					<div class="span9 ">
						<div class="content_title_col_left">
							<div class="title text-left">
								<?php echo JText::sprintf('COM_BOOKPRO_DEST_TITLE_COUNTRY',$this->dest->title,$this->dest->country->country_name)?>
							</div>
							<div class="city-desc">
								<?php echo $this->dest->intro ?>
							</div>
						</div>
						<div class="Popular_tours">
							<div class="content_title_popular_tours text-left">
								<div class="row-fluid div1_popular_tour">
									<div class="pull-left city-title">
										<?php echo JText::sprintf('COM_BOOKPRO_POPULAR_TOURS_IN_CITY',$this->dest->title) ?>
									</div>
									<div class="pull-right city-map">
										 <a
					                        href="index.php?option=com_bookpro&task=displaymap&tmpl=component&dest_id=<?php echo $this->dest->id ?>"
					                        class='modal_tour'
					                        rel="{handler: 'iframe', size: {x: 570, y: 530}}"><sup>+</sup>&nbsp;<?php echo JText::_("COM_BOOKPRO_VIEW_CITY_MAP")?>
					                    </a>
									</div>
								</div>
								
								<?php
									 echo $this->loadTemplate('tours');
								?>
							</div>

						</div>
					</div>
					<div class="span3 content_span4_vacation_tips">
						<?php echo $this->loadTemplate('vacation'); ?>
					</div>
				</div>
			</div>

		</div>
		<div class="banner_center" style="padding-top:10px;padding-bottom:10px">
			<img class="image-full" src="images/banner-center.jpg">
		</div>
		<div class="fearture_tours">
			<div class="title_fearture_tours">
				<span class="text-left div_title_fearture_tours">FEATURED TOURS</span>
				<span class="div1_title_fearture_tours text-right">FIND GREAT DEALS
					IN VIETNAM </span>
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
				<!-- 
				<div class="row-fluid">
					<div class="span4">
						<p class="best_deals text-left">Best deals</p>
						<div class="row-fluid">
							<div class="content_span_detail">
								<div class="span4">
									<img src="/asian/images/best-1.jpg">
								</div>
								<div class="span8 text-left">
									<p class="title_span8">Noi Bai Airport - Hanoi City, Vietnam.</p>
									<p class="detail_span8">Daily sedan service</p>
									<p class="text-right view-detail">View detail ></p>
								</div>
							</div>
							<div class="content_span_detail">
								<div class="span4">
									<img src="/asian/images/best-1.jpg">
								</div>
								<div class="span8 text-left">
									<p class="title_span8">Noi Bai Airport - Hanoi City, Vietnam.</p>
									<p class="detail_span8">Daily sedan service</p>
									<p class="text-right view-detail">View detail ></p>
								</div>
							</div>
							<div class="content_span_detail">
								<div class="span4">
									<img src="/asian/images/best-1.jpg">
								</div>
								<div class="span8 text-left">
									<p class="title_span8">Noi Bai Airport - Hanoi City, Vietnam.</p>
									<p class="detail_span8">Daily sedan service</p>
									<p class="text-right view-detail">View detail ></p>
								</div>
							</div>
							<div class="content_span_detail">
								<p class="limited_offer">LIMITED OFER</p>
							</div>

						</div>
					</div>
					<div class="span8 content_table_span8">
						<table class="table" style="margin: 0px;">
							<thead>
								<tr>
									<th>Hotel name</th>
									<th>Stars</th>
									<th>Destination</th>
									<th>Price</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="color: #003366 !important;">La Residence Phouvao
										Hotel</td>
									<td><img src="/asian/images/star_rate.png" class="img_stars"></td>
									<td style="color: #006699 !important;">Ho Chi Minh, Vietnam</td>
									<td style="color: #006699 !important;">US$450</td>
								</tr>
								<tr>
									<td style="color: #003366 !important;">La Residence Phouvao
										Hotel</td>
									<td><img src="/asian/images/star_rate.png" class="img_stars"></td>
									<td style="color: #006699 !important;">Ho Chi Minh, Vietnam</td>
									<td style="color: #006699 !important;">US$450</td>
								</tr>
								<tr>
									<td style="color: #003366 !important;">La Residence Phouvao
										Hotel</td>
									<td><img src="/asian/images/star_rate.png" class="img_stars"></td>
									<td style="color: #006699 !important;">Ho Chi Minh, Vietnam</td>
									<td style="color: #006699 !important;">US$450</td>
								</tr>
								<tr>
									<td style="color: #003366 !important;">La Residence Phouvao
										Hotel</td>
									<td><img src="/asian/images/star_rate.png" class="img_stars"></td>
									<td style="color: #006699 !important;">Ho Chi Minh, Vietnam</td>
									<td style="color: #006699 !important;">US$450</td>
								</tr>
								<tr>
									<td style="color: #003366 !important;">La Residence Phouvao
										Hotel</td>
									<td><img src="/asian/images/star_rate.png" class="img_stars"></td>
									<td style="color: #006699 !important;">Ho Chi Minh, Vietnam</td>
									<td style="color: #006699 !important;">US$450</td>
								</tr>
								<tr>
									<td style="color: #003366 !important;">La Residence Phouvao
										Hotel</td>
									<td><img src="/asian/images/star_rate.png" class="img_stars"></td>
									<td style="color: #006699 !important;">Ho Chi Minh, Vietnam</td>
									<td style="color: #006699 !important;">US$450</td>
								</tr>
								<tr>
									<td style="color: #003366 !important;">La Residence Phouvao
										Hotel</td>
									<td><img src="/asian/images/star_rate.png" class="img_stars"></td>
									<td style="color: #006699 !important;">Ho Chi Minh, Vietnam</td>
									<td style="color: #006699 !important;">US$450</td>
								</tr>
								<tr>
									<td style="color: #003366 !important;">La Residence Phouvao
										Hotel</td>
									<td><img src="/asian/images/star_rate.png" class="img_stars"></td>
									<td style="color: #006699 !important;">Ho Chi Minh, Vietnam</td>
									<td style="color: #006699 !important;">US$450</td>
								</tr>
								<tr>
									<td style="color: #003366 !important;">La Residence Phouvao
										Hotel</td>
									<td><img src="/asian/images/star_rate.png" class="img_stars"></td>
									<td style="color: #006699 !important;">Ho Chi Minh, Vietnam</td>
									<td style="color: #006699 !important;">US$450</td>
								</tr>
								<tr>
									<td style="color: #003366 !important;">La Residence Phouvao
										Hotel</td>
									<td><img src="/asian/images/star_rate.png" class="img_stars"></td>
									<td style="color: #006699 !important;">Ho Chi Minh, Vietnam</td>
									<td style="color: #006699 !important;">US$450</td>
								</tr>
								<tr>
									<td colspan="4">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="4" style="background: #f2f2f2;">&nbsp;</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				 -->
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
           		<img class="image-full" src="<?php echo JURI::base() . "/components/com_bookpro/assets/images/lucky.jpg"; ?>">
	
           	</div>
           </div> 
		 <div class="row-fluid right-content">
           	<div class="span12">
				<img class="image-full" src="<?php echo JURI::base() . "/components/com_bookpro/assets/images/event.jpg"; ?>">
           		
           	</div>
          </div> 
          <div class="row-fluid right-content">
           	<div class="span12">
				<img class="image-full" src="<?php echo JURI::base() . "/components/com_bookpro/assets/images/top_travel.jpg"; ?>">
           		
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
								<li><a href="#">01. Hanoi Tours</a>
								</li>
								<li><a href="#">02. Hue Tours</a>
								</li>
								<li><a href="#">03. Halong Tours</a>
								</li>
								<li><a href="#">04. Hoian Tours</a>
								</li>
								<li><a href="#">05. Danang Tours</a>
								</li>
							</ul>
		
						</div>
						<div class="span6 text-left">
							<ul>
								<li><a href="#">01. Hanoi Tours</a>
								</li>
								<li><a href="#">02. Hue Tours</a>
								</li>
								<li><a href="#">03. Halong Tours</a>
								</li>
								<li><a href="#">04. Hoian Tours</a>
								</li>
								<li><a href="#">05. Danang Tours</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				</div>
			</div>

	</div>
</div>
