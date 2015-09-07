<?php
?>
<div id="summary-booking" class="summary-booking">
	
	<?php 
	$layout = new JLayoutFile('summary_booking', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts/flight');
	$html = $layout->render(null);
	echo $html;
	?>
</div>