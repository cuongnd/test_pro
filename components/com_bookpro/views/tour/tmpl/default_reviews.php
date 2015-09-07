<div class="list-review">
<?php
AImporter::css('reviews');
AImporter::helper('image');
AImporter::model('customer','country');
foreach ($this->reviews as $review){
	if($review->image){
		$ipath = BookProHelper::getIPath($review->image);
	}else {
		$ipath = BookProHelper::getIPath('components/com_bookpro/assets/images/no_image.jpg');
	}
	$thumb = AImage::thumb($ipath, 160, 99);
?>
<?php 
	$flag = "";
	$address = "";
	$email = "";
	if ($review->customer_id) {

		$cmodel = new BookProModelCustomer();
		
		$customer = $cmodel->getCustomerByID($review->customer_id);
		
		$flag = $customer->flag;
		$address = JText::sprintf("COM_BOOKPRO_REVIEW_ADDRESS",$customer->firstname,$customer->lastname,$customer->country_code);
		$email = $customer->email;
	}else{
		$cmodel = new BookProModelCountry();
		$country = $cmodel->getObjectById($review->country_id);
		$flag = $country->flag;
		$address = JText::sprintf("COM_BOOKPRO_REVIEW_ADDRESS",$review->firstname,$review->lastname,$country->country_code);
		$email = $review->email;
	}
?>
<div class="row-fluid review-rows">
	
	<div class="review-desc">
		<div class="row-fluid">
			<div class="span3">
				<div class="review-thumb">
				
					<img src="<?php echo $thumb; ?>" />
				</div>
				<div class="review-address">
					
					<img class="pull-left review-flag" alt="" src="<?php echo $flag; ?>">
					<div class="pull-left review-contact">
						<?php 
							echo $address;
						?>
					</div>
					<div class="clr"></div>
				</div>
				<div class="review-date">
					<?php echo JText::sprintf('COM_BOOKPRO_REVIEW_TRAVEL_DATE',JFactory::getDate($review->date)->format('d M, Y')); ?>
				</div>
				
				
				<div class="review-email">
				
				<a href="mailto:<?php echo $email; ?>">
				
				<i class="icon_email pull-left"></i>
				<span class="email-review pull-left"><?php echo JText::_('E-MAIL THE REVIEWER') ?></span>
				<i class="icon_review_arrow pull-right"></i>
				</a>	
				<div class="clr"></div>
				</div>
				
			</div>
			<div class="span9">
				<h3 class="review-title">
					<?php echo $review->title; ?>
				</h3>
				<div class="row-fluid">
					<div class="span12 text-right review_submitted" align="right">
						<?php echo JText::sprintf('COM_BOOKPRO_REVIEW_SUMITED_DATE',JFactory::getDate($review->date)->format('d M, Y')) ?>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<?php echo $review->content; ?>
					</div>
				</div>
				
			</div>
		</div>
		
	</div>
</div>
<?php } ?>
</div>