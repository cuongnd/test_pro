<?php
/**
*
* Description
*
* @package	VirtueMart
* @subpackage
* @author
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: product_edit.php 6347 2012-08-14 15:49:02Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$document=JFactory::getDocument();

AdminUIHelper::startAdminArea();

vmJsApi::JvalideForm();
$this->editor = JFactory::getEditor();

?>
<form method="post" name="adminForm" action="<?php echo juri::current() ?>" enctype="multipart/form-data" id="adminForm">

<?php // Loading Templates in Tabs
$tabarray = array();
$tabarray['information'] = 'COM_VIRTUEMART_PRODUCT_FORM_PRODUCT_INFO_LBL';
$tabarray['custom'] = 'COM_VIRTUEMART_PRODUCT_CUSTOM_LBL';
$tabarray['description'] = 'COM_VIRTUEMART_PRODUCT_FORM_DESCRIPTION';
$tabarray['images'] = 'COM_VIRTUEMART_PRODUCT_FORM_PRODUCT_IMAGES_LBL';
$tabarray['document'] = 'COM_VIRTUEMART_PRODUCT_FORM_DOCUMENT_TAB';
//$tabarray['customer'] = 'COM_VIRTUEMART_PRODUCT_FORM_CUSTOMER_TAB';


AdminUIHelper::buildTabs ( $this,  $tabarray, $this->product->virtuemart_product_id );
$document->addScript(JUri::root().'/administrator/components/com_virtuemart/assets/js/view-product.js');

// Loading Templates in Tabs END ?>


<!-- Hidden Fields -->

	<?php echo $this->addStandardHiddenToForm(); ?>
<input type="hidden" name="virtuemart_product_id" value="<?php echo $this->product->virtuemart_product_id; ?>" />
<input type="hidden" name="product_parent_id" value="<?php echo JRequest::getInt('product_parent_id', $this->product->product_parent_id); ?>" />

</form>
<?php AdminUIHelper::endAdminArea(); ?>