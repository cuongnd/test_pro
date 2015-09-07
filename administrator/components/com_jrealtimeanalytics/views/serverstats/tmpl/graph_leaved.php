<div class="texttitle"><?php echo JText::_('LEAVEOFF_PAGES');?></div>
<div class="titlerow">
	<span class="detailslarge"><?php echo JText::_('SERVERSTATS_PAGE');?></span>
	<span class="detailslittle"><?php echo JText::_('SERVERSTATS_NUMUSERS');?></span> 
</div>

<?php foreach ($this->data[LEAVEOFF_PAGES] as $page):?> 
	<div class="recordrow">
		<span class="detailslarge"><?php echo $page[1];?></span>
		<span class="detailslittle"><?php echo $page[0];?></span> 
	</div>
<?php endforeach;?> 
  