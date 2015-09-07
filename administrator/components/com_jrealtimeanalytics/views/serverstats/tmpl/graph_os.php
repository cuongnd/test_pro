<div class="lefttext_stats"> 
	<div class="texttitle"><?php echo JText::_('SERVERSTATS_OS');?></div>
	<?php foreach ($this->data[NUMUSERSOSGROUPED] as $os):?>
		<div id="box"></div><div class="datarow"><?php echo $os[1];?>:<span class="datarow_value"><?php echo $os[0];?></span></div>
	<?php endforeach;?> 
</div>

<div class="rightgraph_stats pie">
	<img src="components/com_jrealtimeanalytics/cache/<?php echo $this->userid . '_serverstats_pie_os.png' . $this->nocache;?>" />
</div>