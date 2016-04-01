<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage Currency
 * @author RickG
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8534 2014-10-28 10:23:03Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
JHtml::_('jquery.framework');
JHTML::_('behavior.core');
JHtml::_('jquery.ui');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/datepicker.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/effect.js');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/draggable.js');
$doc->addScript(JUri::root() . '/media/system/js/esimakin-twbs-pagination/jquery.twbsPagination.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
$doc->addScript(JUri::root() . '/media/system/js/base64.js');
$doc->addScript(JPATH_VM_URL.'/assets/js/view_categories_manager.js');
$doc->addLessStyleSheet(JPATH_VM_URL.'/assets/less/view_categories_manager.less');
$doc->addScript(JUri::root() . '/media/jquery-ui-1.11.1/ui/dialog.js');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/core.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/theme.css');
$doc->addStyleSheet(JUri::root() . '/media/jquery-ui-1.11.1/themes/base/dialog.css');
$input = JFactory::getApplication()->input;
$js_content = '';
$task = $input->get('task');
$total_row=30;
$scriptId='view_categories_manager';
ob_start();
?>

<script type="text/javascript">
	jQuery(document).ready(function ($) {
		$('.view-categories-manager').view_categories_manager({
			task: "<?php echo $task ?>",
			items: "<?php echo base64_encode(json_encode($this->items))?>",
			total_row:<?php echo $total_row ?>
		});
	});
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);
$list_list_item=array_chunk($this->items,$total_row);
$list_select=$list_list_item[0];
?>
<div class="view-categories-manager">
	<form action="index.php" method="post" name="adminForm" id="adminForm">
		<table>
			<tr>
				<td width="100%">
					<?php
					$option=array(
						'virtuemart_category_id'=>'',
						'tree_category'=>'select category'
					);
					$options=array_merge_recursive($option,$this->items);
					?>
					<?php echo JHTML::_('select.genericlist',$options , 'virtuemart_category_id', ' class="filter-category"', 'virtuemart_category_id', 'tree_category'); ?>
				</td>
			</tr>
		</table>
		<div id="editcell">

			<table class="adminlist table table-striped " cellspacing="0" cellpadding="0">
				<thead>
				<tr>
					<th class="admin-checkbox">
						<label class="checkbox"><input type="checkbox" name="toggle" value=""
													   onclick="Joomla.checkAll(this)"/><?php echo $this->sort('virtuemart_transfer_addon_id', 'Id'); ?>
						</label>

					</th>
					<th>
						<?php echo $this->sort('title', 'Category name'); ?>
					</th>
					<th>
						<?php echo $this->sort('parent_category_name', 'Parent category'); ?>
					</th>
					<th>
						<?php echo $this->sort('location', 'Location'); ?>
					</th>
					<th>
						<?php echo $this->sort('start_date', 'Start date'); ?>
					</th>
					<th>
						<?php echo $this->sort('end_date', 'End date'); ?>
					</th>
					<th>
						<?php echo $this->sort('description', 'Description'); ?>
					</th>
					<th width="70">
						<?php echo JText::_('Action'); ?>
					</th>
					<?php /*	<th width="10">
				<?php echo vmText::_('COM_VIRTUEMART_SHARED'); ?>
			</th> */ ?>
				</tr>
				</thead>
				<tbody>
				<?php
				$k = 0;
				for ($i = 0, $n = count($list_select); $i < $n; $i++) {
					$row = $list_select[$i];

					$checked = JHtml::_('grid.id', $i, $row->virtuemart_transfer_addon_id);
					$published = $this->gridPublished($row, $i);
					//$delete = $this->grid_delete_in_line($row, $i, 'virtuemart_transfer_addon_id');
					$editlink = JROUTE::_('index.php?option=com_virtuemart&view=categories&task=edit&cid[]=' . $row->virtuemart_categry_id);
					//$edit = $this->gridEdit($row, $i, 'virtuemart_transfer_addon_id', $editlink);
					?>
					<tr class="row<?php echo $k; ?>">
						<td class="admin-checkbox">
							<?php echo $checked; ?>
						</td>
						<td align="left">
							<a href="<?php echo $editlink; ?>" area-key="tree_category"><?php echo $row->tree_category; ?></a>
						</td>
						<td align="left" area-key="parent_category_name">
							<?php echo $row->parent_category_name; ?>
						</td>
						<td align="left">
							<?php echo $row->location; ?>
						</td>
						<td align="left">
							<?php echo $row->price; ?>
						</td>
						<td align="left">
							<?php echo $row->start_date; ?>
						</td>
						<td align="left">
							<?php echo $row->end_date; ?>
						</td>
						<td align="left">
							<?php echo $row->description; ?>
						</td>
						<td align="center">
							<?php echo $published; ?>
							<?php echo $edit; ?>
							<?php echo $delete; ?>
						</td>
					</tr>
					<?php
					$k = 1 - $k;
				}
				?>
				</tbody>
				<tfoot>
				<tr>
					<td colspan="10">
						<ul id="pagination" class="pagination-sm"></ul>
					</td>
				</tr>
				</tfoot>
			</table>
		</div>
		<input type="hidden" value="" name="task">
		<input type="hidden" value="com_virtuemart" name="option">
		<input type="hidden" value="transferaddon" name="controller">
		<input type="hidden" value="transferaddon" name="view">
		<?php echo JHtml::_('form.token'); ?>
	</form>
	<?php

	if ($task == 'add_new_item'||$task == 'edit_item') {
		echo $this->loadTemplate('edit');
	} ?>
</div>

