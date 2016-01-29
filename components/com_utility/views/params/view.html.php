<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Login view class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_users
 * @since       1.5
 */
class UtilityViewParams extends JViewLegacy
{

	/**
	 * Method to display the view.
	 *
	 * @param   string  The template file to include
	 * @since   1.5
	 */
	public function display($tpl = null)
	{
		$layout=$this->getLayout();
		if($layout=='config')
		{
			parent::display('default');
			return;
		}else if($layout=='createitem'){
			parent::display('default');
			return;
		}
		parent::display($tpl);
	}
	function create_html_list($nodes, $indent = '', $list_field_type, $list_field_table_position_config)
	{


		echo '<ol class="dd-list">';
		$i = 1;
		foreach ($nodes as $item) {
			$indent1 = $indent != '' ? $indent . '_' . $i : $i;

			$groupedlist = new JFormFieldGroupedList();
			$groupedlist->setValue($item->group);
			$childNodes = $item->children;
			$list_attribute_config = array();
			foreach ($list_field_type as $item_type) {
				if (strtolower($item_type->name) == strtolower($item->type . '.php')) {
					require_once JPATH_ROOT . '/' . $item_type->path;
					$class_item_type = 'JFormField' . $item->type;
					$class_item_type = new $class_item_type;
					$list_attribute_config = $class_item_type->get_attribute_config();
					break;
				}
			}
			$item->config_property = base64_decode($item->config_property);
			$item->config_property = json_decode($item->config_property);
			$item->config_property = JArrayHelper::pivot($item->config_property, 'property_key');

			foreach ($list_attribute_config as $key_config_property => $value_config_property) {
				if (!$item->config_property[$key_config_property]) {
					$item->config_property[$key_config_property] = (object)array(
						property_key => $key_config_property,
						property_value => $value_config_property
					);
				}
			}
			$item->config_property = JArrayHelper::key_string_to_interger($item->config_property);
			$item->config_property = json_encode($item->config_property);
			$item->config_property = base64_encode($item->config_property);
			ob_start();
			?>

		<li class="dd-item"
			<?php foreach ($item as $key => $value) { ?>
				data-<?php echo $key ?>="<?php echo $value ?>"
			<?php } ?>
			data-id="<?php echo rand(1, 1000) ?>"
			>
			<div class="dd-handle">
				<div class="dd-handle-move pull-left"><i class="fa-move"></i></div>
				<span class="key_name"><?php echo "$item->label ( $item->name ) " ?></span>
				<button  class="dd-handle-remove dd-nodrag pull-right remove_item_nestable"><i
						class="fa-remove"></i></button>

				<button  class="dd-handle-expand dd-nodrag pull-right expand_item_nestable"><i
						class="im-plus"></i></button>
			</div>

			<div class="more_options dd-nodrag">
				<div>
					<button class="add_node">add node</button>
					<button class="add_sub_node">add sub node</button>
				</div>
				<div class="row">
					<div class=" col-md-7">
						<table class="table">
							<tr>
								<td>Name</td>
								<td><input class="form-control select_field_name update_data_column"  data-property="name" style="width: 200px"

										   value="<?php echo $item->name ?>" type="text"/></td>
								<td>label</td>
								<td><input class="form-control update_data_column"  data-property="label"
										   value="<?php echo $item->label ?>" type="text"/></td>

							</tr>

							<tr>
								<td>default</td>
								<td><input class="form-control update_data_column"  data-property="default"
										   value="<?php echo $item->default ?>" type="text"/></td>

								<td>On change</td>
								<td><input class="form-control update_data_column"  data-property="onchange"  style="width: 200px" type="text"

										   value="<?php echo $item->onchange ?>"/></td>
							</tr>

							<tr>
								<td>Description</td>
								<td><textarea class="description form-control" style="width: 200px"  data-property="description"

											  value="<?php echo $item->description ?>"></textarea></td>
								<td>type</td>
								<td><select disableChosen="true" style="width: 200px" data-property="type"

											type="hidden" class="select2 field_type form-control update_data_column">
										<?php
										foreach ($list_field_type as $a_item) {

											$a_item_name = str_replace('.php', '', $a_item->name);
											?>
											<option <?php echo $a_item_name == $item->type ? 'selected' : '' ?>
												data-path="<?php echo $a_item->path ?>"
												value="<?php echo $a_item_name ?>"><?php echo $a_item_name ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>

						</table>
					</div>
					<div class="col-md-5">
						<div class="row">

							<div class="config_property col-md-12">
								<table class="tbl_append_grid_config_property"
									   data-config_property="<?php echo $item->config_property ?>"
									   id="tblAppendGrid_config_property_<?php echo $indent1 ?>"></table>
							</div>

						</div>
						<div class="row">
							<div class="config_params col-md-12">
								<table class="tbl_append_grid" data-config_params="<?php echo $item->config_params ?>"
									   id="tblAppendGrid_<?php echo $indent1 ?>"></table>
							</div>
						</div>

					</div>
				</div>


			</div>

			<?php
			echo ob_get_clean();
			if (is_array($childNodes) && count($childNodes) > 0) {
				UtilityViewParams::create_html_list($childNodes, $indent1, $list_field_type, $list_field_table_position_config);
			}
			echo "</li>";
			$i++;
		}
		echo '</ol>';
	}

}
