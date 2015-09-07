<div class="texttitle"><?php echo JText::_('SERVERSTATS_PAGES_DETAILS');?></div>
<div class="titlerow">
	<span class="detailslarge"><?php echo JText::_('SERVERSTATS_PAGES_DETAILS_VISITEDUSERS');?></span>
	<span class="detailslittle"><?php echo JText::_('SERVERSTATS_USERS_DETAILS_LASTVISIT');?></span> 
</div>

<?php foreach ($this->detailData as $userDetail):?> 
	<div class="recordrow">
		<span class="detailslarge"><?php echo $userDetail->customer_name;?></span>
		<span class="detailslittle"><?php echo date('Y-m-d H:i:s',  $userDetail->visit_timestamp);?></span>
	</div>
<?php endforeach;?> 