<?php 
$i = 0;
foreach ($displayData as $tour){
	if( $i == 0 )
	  echo '<div class="row-fluid">';
	?>
<div class="row-fluid" style="margin-top:10px;">
<div class="span4">
<div><img src="/asian/images/img_popular.jpg"></div>
<p style="margin:0px; line-height:14px; text-align:justify; padding-top:5px;">
<?php echo $tour->title ?>, <?php echo $tour->days?> days</p>
<div class="row-fluid">
<div class="span5 detail_popular">
<p style="color:#f89308; margin:0px; line-height:15px;">US$160 <p>/ pers.</p></p>
<button type="submit" class="btn read_more">Read more</button>
</div>
<div class="span7" style="font-size:11px; text-align:justify">
<span style="color:#0d58a7; line-height:14px;">90%<span style="color:#f89308;">  Customer Satisfaction</span></span>
<span><p style="margin:0px; line-height:15px;">Based on <p style="color:#0d58a7;">120 reviews</p></span>
</div>
</div>
</div>

</div>
<?php } ?>