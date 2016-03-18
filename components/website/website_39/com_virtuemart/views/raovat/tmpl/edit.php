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
$doc->addScript(JUri::root() . '/administrator/components/com_virtuemart/assets/js/view_tourclass_edit.js');
AdminUIHelper::startAdminArea($this);
AdminUIHelper::imitateTabs('start', 'Tour class');

$js_content = '';
ob_start();
?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.view-tourclass-edit').view_tourclass_edit({
                add_new_popup:<?php echo $this->add_new_popup ?>
            });
        });
    </script>
<?php
$js_content = ob_get_clean();
$js_content = JUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content);

?>
    <div class="view-tourclass-edit">
        <form action="index.php" method="post" class="form-horizontal" name="adminForm" id="adminForm">


            <div class="col50">
                <fieldset>
                    <legend><?php echo vmText::_('Current tour class'); ?></legend>
                    <div class="admintable row-fluid">
                        <?php echo VmHTML::row_control('input', 'Tour class name', 'service_class_name', $this->item->service_class_name, 'class="required"'); ?>
                        <?php echo VmHTML::image('Icon', 'icon', $this->item->icon, 'class="required"'); ?>
                        <?php echo VmHTML::row_control('textarea', 'Meta title', 'meta_title', $this->item->meta_title); ?>
                        <?php echo VmHTML::row_control('textarea', 'Key Word', 'key_word', $this->item->key_word); ?>
                        <?php echo VmHTML::row_control('editor', 'Description', 'description', $this->item->description, '100%', 100); ?>

                        <?php echo VmHTML::row_control('booleanlist', 'COM_VIRTUEMART_PUBLISHED', 'published', $this->item->published); ?>

                    </div>
                </fieldset>

            </div>
            <input type="hidden" name="virtuemart_vendor_id" value="<?php echo $this->item->virtuemart_vendor_id; ?>"/>
            <input type="hidden" name="virtuemart_service_class_id"
                   value="<?php echo $this->item->virtuemart_service_class_id; ?>"/>
            <?php echo $this->addStandardHiddenToForm(); ?>
        </form>
    </div>

<?php
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea(); ?>