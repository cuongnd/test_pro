<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage Currency
 * @author Max Milbers, RickG
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id$
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/administrator/components/com_virtuemart/assets/js/view_raovat_edit.js');


$js_content = '';
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-raovat-edit').view_raovat_edit({
            add_new_popup:<?php echo $this->add_new_popup ?>
        });
    });
</script>
<?php
$js_content = ob_get_clean();
$js_content = JUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

?>
<div class="view-raovat-edit">
    <form action="index.php" method="post" class="form-horizontal" name="adminForm" id="adminForm">
        <?php echo $this->render_toolbar_edit('raovat') ?>
        <?php
        $class_left='col-md-2';
        $class_right='col-md-10';
        ?>
        <?php echo JHtml::row_control($class_left,$class_right,'Product  name', 'input.text', 'raovat_name', $this->item->product_name, array('class' => 'required')); ?>
        <?php echo JHtml::row_control($class_left,$class_right,'Product  price', 'input.price', 'Price', $this->item->price, array('class' => 'required')); ?>
        <?php echo JHtml::row_control($class_left,$class_right,'Product  sku', 'input.text', 'product_sku', $this->item->product_sku, array('class' => 'required')); ?>
        <?php echo JHtml::row_control($class_left,$class_right,'Short description', 'editor.basic', 'product_s_desc', $this->item->product_s_desc, array('class' => 'required'),'100','100'); ?>
        <?php echo JHtml::row_control($class_left,$class_right,'Full description', 'editor.basic', 'product_desc', $this->item->product_desc, array('class' => 'required'),'100','100'); ?>
        <?php echo JHtml::row_control($class_left,$class_right,'Gallery', 'galleries.edit_gallery', 'list_image', $this->item->list_image, array('class' => 'required')); ?>
        <?php echo JHtml::row_control($class_left,$class_right,'Product  name', 'input.text', 'product_name', $this->item->product_name, array('class' => 'required')); ?>
        <?php echo JHtml::row_control($class_left,$class_right,'Product  name', 'input.text', 'product_name', $this->item->product_name, array('class' => 'required')); ?>
        <?php echo JHtml::row_control($class_left,$class_right,'Product  name', 'input.text', 'product_name', $this->item->product_name, array('class' => 'required')); ?>
        <?php echo JHtml::_('form.token'); ?>


        <input type="hidden" name="virtuemart_vendor_id" value="<?php echo $this->item->virtuemart_vendor_id; ?>"/>
    </form>
</div>

