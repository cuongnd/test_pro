<div>
<?php 
	AImporter::helper('contants','date','currency','report');
	$tours = ReportHelper::getTours();
	
	
if (count($tours)){	
	$select_tour = array();
	foreach ($tours as $tour){
		$tour->value = $tour->title;
		$select_tour[] = JHtml::_('select.option',$tour->id,$tour->title,'id','title');
	}
	
	
?>

<div>
<?php 
echo JHTML::_('select.genericlist', $select_tour, 'tour_id[]', ' class="inputbox" multiple="multiple" size="10"', 'id', 'title', $this->lists['tour_id']) ;
?>
<div class="clr"></div>
</div>
<div>
	<input type="button" name="go" value="go" id="go" onclick="document.getElementById('adminForm').submit()">
</div>
</div>
<?php } ?>