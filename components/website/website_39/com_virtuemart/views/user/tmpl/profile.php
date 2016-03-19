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
$user=JFactory::getUser();
if (!class_exists ('VmHTML')) {
	require(JPATH_VM_SITE . DS . 'helpers' . DS . 'html.php');
}
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

		<div class="panel panel-primary profile-widget">
			<!-- Start .panel -->
			<div class=panel-heading>
				<h4 class=panel-title><?php echo  JText::_('Profile') ?>:<?php echo $user->name ?></h4>
			</div>
			<div class="panel-body ">
				<div class="row-fluid">
					<div class="col-md-6">
						<ul class="profile-info">
							<li><i class="im-user"></i> <?php echo $user->name ?></li>
							<li><i class="ec-mobile"></i> <?php echo $user->phone ?></li>
							<li><i class="ec-location"></i> <?php echo $user->address ?></li>
							<li><i class="ec-mail"></i> <?php echo $user->email ?></li>
							<li><i class="im-office"></i> <?php echo $user->office ?></li>
							<li><i class="fa-bitbucket"></i> <?php echo $user->email ?></li>
						</ul>
					</div>
					<div class="col-md-6">
						<?php echo VmHTML::show_image(JUri::root() . '/' . $user->image, 'class="required"', 200, 200); ?>
					</div>
				</div>



			</div>
		</div>
		<?php echo JHtml::_('form.token'); ?>


		<input type="hidden" name="virtuemart_vendor_id" value="<?php echo $this->item->virtuemart_vendor_id; ?>"/>
	</form>
</div>

