<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id$
 **/
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
BookProHelper::setSubmenu(11);
?>

<table class="adminlist">
<tr><td colspan="2"><?php echo JHTML::_('image', JURI::root().'components/com_bookpro/assets/images/bookpro_license_48.png', JText::_('LICENSE')); ?></td></tr>
<tbody>
	<tr><th><?php echo JText::_('REGISTERED'); ?></th><td><?php echo $this->result; ?></td></tr>
	<tr><th><?php echo JText::_('HOST_NAME'); ?></th><td><?php echo $this->hostname; ?></td></tr>
	<tr><th><?php echo JText::_('IP_ADDRESS'); ?></th><td><?php echo $this->ipaddress; ?></td></tr>
</tbody>
</table>


<br />
<table class="adminlist">
<tbody>
<tr><th>Name:</th><td>Bookpro</td></tr>
<tr><th>Version:</th><td>1.0</td></tr>
<tr><th>Coded by:</th><td>Nguyen Dinh Cuong</td></tr>
<tr><th>Contact:</th><td>quan@ibookingonline.com</td></tr>
<tr><th>Support:</th><td><?php echo JHTML::_('link', 'http://www.ibookingonline.com/', 'Joombooking Homepage', 'target="_blank"'); ?></td></tr>
<tr><th>Copyright:</th><td>Copyright (C) 2011 - 2012 Nguyen Dinh Cuong</td></tr>
<tr><th>License:</th><td><?php echo "GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html" ?></td></tr>
</tbody>
</table>
<