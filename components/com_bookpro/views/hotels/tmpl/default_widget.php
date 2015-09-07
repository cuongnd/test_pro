<?php  ob_start(); ?>
<div class="breadcrumb row-fluid" id="info_div">

	<div class="span2" style="line-height: 28px;">
		<?php echo JText::_('COM_BOOKPRO_FILTER_RESULT')?>
	</div>
	<div class="span3" style="line-height: 28px;">
		<?php echo JText::_('COM_BOOKPRO_SORT_BY')?>
		<select id="hotel" name="hotel[]" class="span7"
			style="float: right; margin: 0;"
			onchange="submitrefine('order',this.value,this.id)">
			<option value="rank">
				<?php echo JText::_('COM_BOOKPRO_HOTEL_RANK')?>
			</option>
			<option value="title">
				<?php echo JText::_('COM_BOOKPRO_HOTEL_NAME')?>
			</option>
			<option value="price" selected="selected">
				<?php echo JText::_('COM_BOOKPRO_ROOM_PRICE')?>
			</option>
		</select> &nbsp;

	</div>

</div>
<div class="row-fluid">
	<div class="span12">

		<?php if (count($this->hotels)>0) {?>

		<?php
		$i = 0;
	foreach ($this->hotels as $hotel){?>

		<?php 

		$rankstar = JURI::base() . "/components/com_bookpro/assets/images/" . $hotel -> rank . 'star.png';
		$link = JRoute::_('index.php?option=com_bookpro&controller=hotel&task=displayhotel&id=' . $hotel -> id . '&Itemid=' . JRequest::getVar('Itemid'));
		$ipath = BookProHelper::getIPath($hotel -> image);
		$thumb = AImage::thumb($ipath, 160, 100);
		?>
		<?php
		if ($i == 0)
			echo '<div class="row-fluid">';

		?>
		<div class="span<?php echo (12/$this->products_per_row) ?>">

			<div class="row-fluid">
				<div class="span3">
					<span class="blog_itemimage"><img class="thumbnail"
						src="<?php echo $thumb ?>" alt="<?php echo $hotel->title ?>"> </span>

				</div>
				<div class="span6">
					<div style="font-size: 110%; font-weight: bold;">
						<a href="<?php echo $link ?>"><?php echo $hotel->title ?> </a>
					</div>
					<div class="">
						<img src="<?php echo $rankstar; ?>">
					</div>
					<p>
						<?php 
						$city=BookProHelper::getObjectAddress($hotel->city_id);
						echo $hotel->address1.', '. $city->title.', '.$city->country
						?>
						<?php echo HotelHelper::displayHotelMap($hotel->id) ?>
					</p>
					<?php echo JHtmlString::truncate(strip_tags($hotel -> desc), 100);
					?>
				</div>

				<div class="span3 text-right">

					<p>
						<?php echo JText::_('COM_BOOKPRO_ROOM_PRICE_FROM')?>
					</p>
					<h4>
						<?php 
						AImporter::helper('factory','hotel','currency');
						echo CurrencyHelper::formatprice(hotelHelper::getMinPriceHotelInMonth($hotel->id)?hotelHelper::getMinPriceHotelInMonth($hotel->id):'50') ?>
					</h4>
					<a href="<?php echo $link ?>" class="btn"><?php echo JText::_('COM_BOOKPRO_BOOK_NOW')?>
					</a>


				</div>
			</div>
		</div>
		<?php
		if (($i + 1) % $this ->products_per_row == 0)

			echo '<div class="row-fluid">';
		if (($i + 1) == $this -> count)

			$i++;
		?>
		<?php
	}
	?>
		<?php } else { ?>

		<div>
			<?php echo JText::sprintf('COM_BOOKPRO_NO_RECORD',JText::_('COM_BOOKPRO_HOTEL'))?>
		</div>

		<?php } ?>
	</div>
</div>
<div class="pagination">
	<?php echo $this->pagination->getPagesLinks() ?>
</div>

<input
	type="hidden" name="option"
	value="<?php echo JRequest::getVar('option') ?>" />
<input
	type="hidden" name="<?php echo $this->token?>" value="1" />
<input type="hidden" name="task"
	value="">
<?php
$contents=ob_get_contents();
ob_end_clean();
$callback = JRequest::getVar('callback');
if($callback)
{
	$callback = filter_var($callback, FILTER_SANITIZE_STRING);
}
echo $callback . '('.json_encode($contents).');';
exit();


?>


