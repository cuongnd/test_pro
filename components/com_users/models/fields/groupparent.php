<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_users
 * @since       1.6
 */
class JFormFieldGroupParent extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since   1.6
	 */
	protected $type = 'GroupParent';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 * @since   1.6
	 */
	protected function getInput()
	{
		$options = array();
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
        $list_user_group=JUserHelper::get_list_user_group();
        $root_user_group_id=JUserHelper::get_root_user_group_id();

        $children = array();
        // First pass - collect children
        foreach ($list_user_group as $v) {
            $pt = $v->parent_id;
            $pt=($pt==''||$pt==$v->id)?'list_root':$pt;
            $list = @$children[$pt] ? $children[$pt] : array();
            array_push($list, $v);
            $children[$pt] = $list;
        }
        $root_group=reset($children['list_root']);
        unset($children['list_root']);
        ob_start();
        ?>
        <select name="<?php echo $this->name ?>" id="<?php echo $this->id ?>">
            <option value="<?php echo $root_group->id ?>"><?php echo $root_group->title ?></option>
            <?php
            if (!function_exists('sub_render_group_user')) {
                function sub_render_group_user($root_group_user_id, $children,$group_user_id_selected=0, $level=0, $max_level=999)
                {
                    if($root_group_user_id==$group_user_id_selected)
                    {
                        return;
                    }
                    if ($children[$root_group_user_id]) {
                        $level1=$level+1;
                        foreach ($children[$root_group_user_id] as $v) {
                            $root_group_user_id=$v->id;
                            $title=$v->title;
                            $title=str_repeat('<span class="gi">|&mdash;</span>',$level1).$title;
                            ?>
                                <option <?php echo $v->id==$group_user_id_selected?'selected':'' ?>  value="<?php echo $v->id ?>">
                                    <?php echo $title ?>
                                </option>
                            <?php
                            sub_render_group_user($root_group_user_id, $children,$group_user_id_selected,$level1,$max_level);
                        }
                    }
                }
            }
            sub_render_group_user($root_user_group_id,$children,$this->value);
        ?>
        </select>

        <?php
        $html=ob_get_clean();
        return $html;
	}
}
