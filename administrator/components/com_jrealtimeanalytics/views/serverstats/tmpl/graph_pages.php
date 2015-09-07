<div class="texttitle"><?php echo JText::_('SERVERSTATS_PAGES');?></div>
<div class="titlerow">
	<span class="verylarge"><?php echo JText::_('SERVERSTATS_PAGE');?></span>
	<span class="little"><?php echo JText::_('SERVERSTATS_LASTVISIT');?></span>
	<span class="little"><?php echo JText::_('SERVERSTATS_NUMVISITS');?></span> 
</div>

<?php foreach ($this->data[VISITSPERPAGE] as $page):?> 
	<div class="recordrow">
		<span class="verylarge"><?php echo $page[2];?></span>
		<span class="little"><?php echo date('Y-m-d H:i:s', $page[1]);?></span> 
		<span class="little"><a href="index.php?option=com_jrealtimeanalytics&amp;task=serverstats.showEntity&amp;tmpl=component&amp;details=page&amp;identifier=<?php echo rawurlencode($page[2]);?>" class="preview"><?php echo $page[0];?></a></span>
	</div>
<?php endforeach;?> 
  