<div class="texttitle"><?php echo JText::_('SERVERSTATS_USERS');?></div>
<div class="titlerow">
	<span style="width:15%"><?php echo JText::_('SERVERSTATS_NAME');?></span>
	<span style="width:14%"><?php echo JText::_('SERVERSTATS_LASTVISIT');?></span>
	<span style="width:14%">Browser</span>
	<span class="little"><?php echo JText::_('SERVERSTATS_OS_TITLE');?></span>
	<span class="little">IP</span>
	<span class="little"><?php echo JText::_('SERVERSTATS_VISITED_PAGES');?></span>
</div>

<?php foreach ($this->data[TOTALVISITEDPAGESPERUSER] as $user):?> 
	<div class="recordrow">
		<span style="width:15%"><?php echo $user[1];?></span>
		<span style="width:14%"><?php echo date('Y-m-d H:i:s', $user[2]);?></span>
		<span style="width:14%"><?php echo $user[3];?></span>
		<span class="little"><?php echo $user[4];?></span>
		<span class="little"><?php echo $user[6];?></span>
		<span class="little"><a href="index.php?option=com_jrealtimeanalytics&amp;task=serverstats.showEntity&amp;tmpl=component&amp;details=user&amp;identifier=<?php echo $user[5];?>" class="preview"><?php echo $user[0];?></a></span>
	</div>
<?php endforeach;?> 
  