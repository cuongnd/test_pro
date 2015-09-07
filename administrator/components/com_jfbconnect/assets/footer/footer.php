<?php
/**
 * @package		SourceCoast Extension Version Tool
 * @copyright (C) 2010-2013 by SourceCoast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');
$option = JRequest::getString('option');
$xmlFile = str_replace("com_", "", $option).'.xml';
$xmlElement = simplexml_load_file(JPATH_ADMINISTRATOR.'/components/'.$option.'/'.$xmlFile);
if($xmlElement)
{
    $title = (string) $xmlElement->name;
    $version = (string) $xmlElement->version;
}
?>
<table style="margin-bottom: 5px; padding: 3px; width: 100%; border: 2px solid #BBBBBB; background:#F8F8F8">
	<tbody>
		<tr>
			<td style="text-align: left; width: 25%; padding: 10px 0 0 10px;">
			<a href="http://www.sourcecoast.com" target="_blank"><img src="components/<?php echo $option;?>/assets/footer/SourceCoast_Logo.png" width="250px"/></a>
			</td>
			<td style="text-align: center; width: 49%;">
				<?php echo $title;?>
				<br/>Copyright: 2010-2013 &copy; <a href="http://www.sourcecoast.com" target="_blank">SourceCoast Web Development</a>
				<br/>
				Version: <?php echo $version;?>
			</td>
			<td style="text-align: right; width: 25%;">
				<a href="http://extensions.joomla.org/extensions/owner/sourcecoast" target="_blank">Leave Feedback on the JED</a>
				<br/>
				<a href="http://twitter.com/sourcecoast" target="_blank">Follow Us on Twitter</a>
				<br/>
				<a href="http://www.sourcecoast.com/forums" target="_blank">Support Forums on SourceCoast.com</a>
				<br/>
				<a href="http://www.sourcecoast.com/affiliates" target="_blank">Become an Affiliate and Earn</a>
			</td>
		</tr>
	</tbody>
</table>
