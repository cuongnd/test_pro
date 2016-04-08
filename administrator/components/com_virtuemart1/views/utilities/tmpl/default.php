<?php
/**
*
* Lists all the categories in the shop
*
* @package	VirtueMart
* @subpackage Category
* @author RickG, jseros, RolandD, Max Milbers
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: default.php 6477 2012-09-24 14:33:54Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if (!class_exists ('shopFunctionsF'))
	require(JPATH_VM_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<?php AdminUIHelper::startAdminArea(); ?>
    <div class="row-fluid" style="text-align: center">
        <a class="btn" href="index.php?option=com_virtuemart&controller=utilities&task=setNullField_file_url_thumb">Set null field file_url_thumb</a>
        <br/>
        <a class="btn" href="index.php?option=com_virtuemart&controller=utilities&task=updateFileDownload">Update file download for product</a>
        <br/>
        <a class="btn" href="index.php?option=com_virtuemart&view=utilities&layout=setbaiviet">setbaiviet</a>
        <br/>
        <a class="btn" href="index.php?option=com_virtuemart&view=utilities&layout=thaydoisangzip">thay doi sang zip</a>
        <br/>
        <a class="btn" href="index.php?option=com_virtuemart&view=utilities&layout=asigncategory">asigncategory</a>
        <br/>
        <a class="btn" href="index.php?option=com_virtuemart&view=utilities&layout=makevendor">makevendor</a>
        <br/>
        <a class="btn" href="index.php?option=com_virtuemart&view=utilities&layout=addcategoryfromenvato">addcategoryfromenvato</a>
        <br/>
        <a class="btn" href="index.php?option=com_virtuemart&view=utilities&layout=getcontentevanto">getcontentevanto</a>
        <br/>
        <a class="btn" href="index.php?option=com_virtuemart&view=utilities&layout=setsitemap">setsitemap</a>
        <br/>
        <a class="btn" href="index.php?option=com_virtuemart&view=utilities&layout=wrirechildcategory">wrirechildcategory</a>
    </div>
	<div class="clearfix"> </div>
	<?php AdminUIHelper::endAdminArea(true); ?>
</form>


