<div class="lefttext_stats"> 
	<div class="texttitle"><?php echo JText::_('SERVERSTATS_GEOLOCATION');?></div>
	<?php foreach ($this->data[NUMUSERSGEOGROUPED] as $geo):?>
		<div id="box"></div><div class="datarow"><?php echo @$this->geotrans[$geo[1]]['name'] ? $this->geotrans[$geo[1]]['name'] : 'Not set';?>:<span class="datarow_value"><?php echo $geo[0];?></span></div>
	<?php endforeach;?> 
</div>

<div class="rightgraph_stats pie">
	<img src="components/com_jrealtimeanalytics/cache/<?php echo $this->userid . '_serverstats_pie_geolocation.png' . $this->nocache;?>" />
</div>

