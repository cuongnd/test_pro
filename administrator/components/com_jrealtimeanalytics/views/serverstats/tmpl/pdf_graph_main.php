<?php 
/** 
 * @package JREALTIMEANALYTICS::REALSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage views
 * @subpackage serverstats
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );  ?>
<img src="components/com_jrealtimeanalytics/images/stats_48.png"/><b><?php echo JText::_('SERVERSTATS_DETAILS');?></b>
<hr/>
<br/>
<table width="700px">
	<tr>
		<td width="300px">
			<br/>
			<?php echo JText::_('TOTAL_VISITED_PAGES');?>: <?php echo $this->data[TOTALVISITEDPAGES];?><br/>
			<?php echo JText::_('TOTAL_VISITORS');?>: <?php echo $this->data[TOTALVISITORS];?><br/>
			<?php echo JText::_('MEDIUM_VISIT_TIME');?>: <?php echo $this->data[MEDIUMVISITTIME];?><br/> 
			<?php echo JText::_('MEDIUM_VISITED_PAGES_PERUSER');?>: <?php echo $this->data[MEDIUMVISITEDPAGESPERSINGLEUSER];?>
		</td>
		<td width="400px">
			<img src="components/com_jrealtimeanalytics/cache/<?php echo $this->userid . '_serverstats_bars.png'?>" />
		</td>
	</tr>
</table>

<img src="components/com_jrealtimeanalytics/images/stats_48.png"/><b><?php echo JText::_('SERVERSTATS_GEOLOCATION_AWARE');?></b>
<hr/>
<br/>
<table width="700px">
	<tr>
		<td width="300px">
			<br/> 
			<?php foreach ($this->data[NUMUSERSGEOGROUPED] as $index=>$geo):?>
				<?php echo @$this->geotrans[$geo[1]]['name'] ? $this->geotrans[$geo[1]]['name'] : 'Not set';?>: <?php echo $geo[0];
				if($index < count($this->data[NUMUSERSGEOGROUPED]) - 1):
					echo '<br/>';
				endif;?>
			<?php endforeach;?> 
		</td>
		<td width="400px">
			<img src="components/com_jrealtimeanalytics/cache/<?php echo $this->userid . '_serverstats_pie_geolocation.png'?>" />
		</td>
	</tr>
</table>

#newpagestart#
<img src="components/com_jrealtimeanalytics/images/stats_48.png"/><b><?php echo JText::_('SERVERSTATS_OS');?></b>
<hr/>
<br/>
<table width="700px">
	<tr>
		<td width="300px">
			<br/> 
			<?php foreach ($this->data[NUMUSERSOSGROUPED] as $index=>$os):?>
				<?php echo $os[1];?>: <?php echo $os[0];
				if($index < count($this->data[NUMUSERSOSGROUPED]) - 1):
					echo '<br/>';
				endif;?>
			<?php endforeach;?> 
		</td>
		<td width="400px">
			<img src="components/com_jrealtimeanalytics/cache/<?php echo $this->userid . '_serverstats_pie_os.png'?>" />
		</td>
	</tr>
</table>

<img src="components/com_jrealtimeanalytics/images/stats_48.png"/><b><?php echo JText::_('SERVERSTATS_BROWSER');?></b>
<hr/>
<br/> 
<table width="700px">
	<tr>
		<td width="300px">
			<br/> 
			<?php foreach ($this->data[NUMUSERSBROWSERGROUPED] as $index=>$browser):?>
				<?php echo $browser[1];?>: <?php echo $browser[0];
				if($index < count($this->data[NUMUSERSBROWSERGROUPED]) - 1):
					echo '<br/>';
				endif;?>
			<?php endforeach;?> 
		</td>
		<td width="400px">
			<img src="components/com_jrealtimeanalytics/cache/<?php echo $this->userid . '_serverstats_pie_browser.png'?>" />
		</td>
	</tr>
</table>

#newpagestart#
<img src="components/com_jrealtimeanalytics/images/stats_48.png"/><b><?php echo JText::_('LANDING_PAGES');?></b>
<hr/>
<br/> 
<table width="800px">
	<tr>
		<td width="600px">
			<b><?php echo JText::_('SERVERSTATS_PAGE');?></b>
		</td>
		<td width="200px">
			<b><?php echo JText::_('SERVERSTATS_NUMUSERS');?></b>
		</td>
	</tr>
	<?php foreach ($this->data[LANDING_PAGES] as $page):?> 
	<tr>
		<td width="600px"> 
			<?php echo strlen($page[1]) > 100 ? substr($page[1], 0, 100) . '...' : $page[1];?> 
		</td> 
		<td width="200px"> 
			<?php echo $page[0]?> 
		</td>
	</tr>
	<?php endforeach;?> 
</table>

<br/> 
<img src="components/com_jrealtimeanalytics/images/stats_48.png"/><b><?php echo JText::_('LEAVEOFF_PAGES');?></b>
<hr/>
<br/> 
<table width="800px">
	<tr>
		<td width="600px">
			<b><?php echo JText::_('SERVERSTATS_PAGE');?></b>
		</td>
		<td width="200px">
			<b><?php echo JText::_('SERVERSTATS_NUMUSERS');?></b>
		</td>
	</tr>
	<?php foreach ($this->data[LEAVEOFF_PAGES] as $page):?> 
	<tr>
		<td width="600px"> 
			<?php echo strlen($page[1]) > 100 ? substr($page[1], 0, 100) . '...' : $page[1];?> 
		</td> 
		<td width="200px"> 
			<?php echo $page[0]?> 
		</td>
	</tr>
	<?php endforeach;?> 
</table>

<br/> 
<img src="components/com_jrealtimeanalytics/images/stats_48.png"/><b><?php echo JText::_('SERVERSTATS_PAGES');?></b>
<hr/>
<br/> 
<table width="800px">
	<tr>
		<td width="500px">
			<b><?php echo JText::_('SERVERSTATS_PAGE');?></b>
		</td>
		<td width="150px">
			<b><?php echo JText::_('SERVERSTATS_LASTVISIT');?></b>
		</td>
		<td width="150px">
			<b><?php echo JText::_('SERVERSTATS_NUMVISITS');?></b>
		</td>
	</tr>
	<?php foreach ($this->data[VISITSPERPAGE] as $page):?> 
	<tr>
		<td width="500px">  
			<?php echo strlen($page[2]) > 90 ? substr($page[2], 0, 90) . '...' : $page[2];?> 
		</td> 
		<td width="150px"> 
			<?php echo date('Y-m-d H:i:s', $page[1]);?> 
		</td>
		<td width="150px"> 
			<?php echo $page[0];?> 
		</td> 
	</tr>
	<?php endforeach;?> 
</table>

<br/> 
<img src="components/com_jrealtimeanalytics/images/stats_48.png"/><b><?php echo JText::_('SERVERSTATS_USERS');?></b>
<hr/>
<br/> 
<table width="800px">
	<tr>
		<td width="130px">
			<b><?php echo JText::_('SERVERSTATS_NAME');?></b>
		</td>
		<td width="130px">
			<b><?php echo JText::_('SERVERSTATS_LASTVISIT');?></b>
		</td>
		<td width="130px">
			<b>Browser</b>
		</td>
		<td width="130px">
			<b><?php echo JText::_('SERVERSTATS_OS_TITLE');?></b>
		</td>
		<td width="130px">
			<b>IP</b>
		</td>
		<td width="130px">
			<b><?php echo JText::_('SERVERSTATS_VISITED_PAGES');?></b>
		</td>
	</tr>
	<?php foreach ($this->data[TOTALVISITEDPAGESPERUSER] as $user):?> 
	<tr>
		<td width="130px">  
			<?php echo $user[1];?> 
		</td> 
		<td width="130px"> 
			<?php echo date('Y-m-d H:i:s', $user[2]);?> 
		</td>
		<td width="130px"> 
			<?php echo $user[3];?> 
		</td> 
		<td width="130px"> 
			<?php echo $user[4];?> 
		</td> 
		<td width="130px"> 
			<?php echo $user[6];?> 
		</td> 
		<td width="130px"> 
			<?php echo $user[0];?> 
		</td> 
	</tr>
	<?php endforeach;?> 
</table>