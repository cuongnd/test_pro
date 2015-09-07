<div class="lefttext_stats"> 
	<div class="texttitle"><?php echo JText::_('SERVERSTATS_DETAILS');?></div>
	<div id="box"></div><div class="datarow"><?php echo JText::_('TOTAL_VISITED_PAGES');?>:<span class="datarow_value"><?php echo $this->data[TOTALVISITEDPAGES];?></span></div>
	<div id="box"></div><div class="datarow"><?php echo JText::_('TOTAL_VISITORS');?>:<span class="datarow_value"><?php echo $this->data[TOTALVISITORS];?></span></div>
	<div id="box"></div><div class="datarow"><?php echo JText::_('MEDIUM_VISIT_TIME');?>:<span class="datarow_value"><?php echo $this->data[MEDIUMVISITTIME];?></span></div> 
	<div id="box"></div><div class="datarow"><?php echo JText::_('MEDIUM_VISITED_PAGES_PERUSER');?>:<span class="datarow_value"><?php echo $this->data[MEDIUMVISITEDPAGESPERSINGLEUSER];?></span></div>
</div>

<div class="rightgraph_stats">
	<img src="components/com_jrealtimeanalytics/cache/<?php echo $this->userid . '_serverstats_bars.png' . $this->nocache;?>" />
</div>

